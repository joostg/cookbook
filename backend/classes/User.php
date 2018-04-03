<?php
namespace cookbook\backend\classes;
class User extends Base
{
    public function login($request, $response, $args)
    {
        /*$insert = $this->db->prepare("INSERT INTO users (user, hash) VALUES (?,?)");
        $insert->execute(array(
           'joost',password_hash('banaan',PASSWORD_DEFAULT)
        ));*/

        // Attempt to restore logged in user from cookie first. If successful redirect to returnUrl
        if ($this->restoreCookie()) {
            return $response->withHeader('Location', $this->getReturnUri());
        }

		return $this->render($response, array());
    }

    public function authenticate($request, $response, $args)
    {
        $email = $request->getParam('user');
        $pass = $request->getParam('pass');

        if ($pass || $email) {
            $sql = "SELECT hash
                    FROM users
                    WHERE user = :user";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute(["user" => $email]);

            if ($result) {
                $hash = $stmt->fetch();

                if (password_verify($pass, $hash['hash'])) {
                    $_SESSION['user'] = $email;

                    return $response->withHeader('Location', $this->getReturnUri());
                }
            }
        }
    
        return $response->withHeader('Location', $this->baseUrl . '/login');
    }

    public function logout($request, $response, $args)
    {
        session_destroy();

        return $response->withHeader('Location', $this->baseUrl . '/login');
    }

    function restoreCookie()
    {
        // check if cookie exists
        if (!isset($_COOKIE['onsreceptenboek']) || !isset($_COOKIE['onsreceptenboek']['selector']) || !isset($_COOKIE['onsreceptenboek']['validator'])) {
            return false;
        }

        foreach ($_COOKIE['onsreceptenboek'] as $name => $value) {
            $cookie[htmlspecialchars($name)] = htmlspecialchars($value);
        }

        // select token from db using selector
        $selectToken = $this->db->prepare("SELECT validator, user_id FROM auth_tokens	WHERE selector = ? AND expires > NOW()");
        $selectToken->execute(array($cookie['selector']));

        $authToken = $selectToken->fetch();
        if (!$authToken) return false; // no token found

        if (!password_verify($cookie['validator'], $authToken['validator'])) return false; // invalid validator

        // Valid cookie found. Restore session.
        $selectUser = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $selectUser->execute(array($authToken['user_id']));

        $user = $selectUser->fetch();
        if (!$user) return false; // no user found

        global $session;
        $sessionUser = $session->getSegment('Auth');
        $sessionUser->set('user', $user);

        // update cookie with new validator and expires
        $validator = random_string(50);
        $expires = new DateTime('+30 days');

        setcookie('onsreceptenboek[selector]', $cookie['selector'], $expires->getTimestamp(), '/');
        setcookie('onsreceptenboek[validator]', $validator, $expires->getTimestamp(), '/');

        $updateToken = $this->db->prepare(
            "UPDATE auth_tokens SET `validator` = :validator, `expires` = :expires WHERE `selector` = :selector"
        );
        $updateToken->execute(array(
            'selector' => $cookie['selector'],
            'validator' => password_hash($validator, PASSWORD_DEFAULT),
            'expires' => $expires->format('Y-m-d H:i:s')
        ));

        return true;
    }

    // TODO: incorporate this method in the old authenticate method
    public function authenticate2()
    {
        $username = $_POST['username'];

        if (!$username) {
            $errorMsg = 'Geef een gebruikersnaam op.';
        } else {
            // get throttling data
            $remaining_delay = $this->getRemainingDelay($username);

            if ($remaining_delay > 0) {
                $errorMsg = 'Probeer het over ' . $remaining_delay . ' seconden nogmaals';
            } else {
                $user = $this->_authenticate($username, $_POST['password']);
                if ($user) {
                    $sessionUser = $this->_session->getSegment('Auth');
                    $sessionUser->set('user', $user);

                    $this->createCookie($user['id']);

                    if (empty($_POST['return']) ||
                        $_POST['return'] == $_SERVER['REQUEST_URI']) {
                        $_POST['return'] = '/';
                    }

                    header('Location: ' . $_POST['return']);
                    die();
                }

                $errorMsg = 'Ongeldige gebruikersnaam / wachtwoord opgegeven, probeer het opnieuw.';
            }
        }

        $data = array(
            'name' => $_POST['username'],
            'return' => $_POST['return'],
            'error' => $errorMsg,
        );

        $this->addCss(array(
            'css/form.css',
            'css/login.css',
        ));

        return $this->render($data, 'login');
    }

    // TODO: incorporate this method in original logout method
    public function logout2()
    {
        $this->_session->destroy();

        // destroy cookie
        $expires = new DateTime('-1 hours');
        setcookie('onsreceptenboek[selector]', "", $expires->getTimestamp(), '/');
        setcookie('onsreceptenboek[validator]', "", $expires->getTimestamp(), '/');

        header('Location: ' . $this->baseUrl . 'user/login');
        die();
    }

    protected function _authenticate($user, $pass)
    {
        $select = $this->_db->prepare("SELECT * FROM users WHERE username = :username");
        $select->execute(array(
            'username' => $user,
        ));
        $result = $select->fetch();

        // verify credentials
        if ($result) {
            if (password_verify($pass, $result['password'])) {
                $this->logLogin($user, 1);
                return $result;
            }
        }

        $this->logLogin($user, 0);
        return false;
    }

    private function logLogin($user, $result)
    {
        $log = $this->_db->prepare(
            "INSERT INTO logins 
				(username, ip_address, attempted, success)
			VALUES 
				(:username, INET_ATON(:ip_address), CURRENT_TIMESTAMP, :success)"
        );

        $log->execute(array(
            'username' => $user,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'success' => $result
        ));
    }

    /**
     * Calculate remaining delay till next login attempt in seconds. The base delay is 2 seconds. Every 10 failed
     * attempts last 15 minutes will add 1 extra second. The additional delay is calculated globally, not per account,
     * to prevent large scale password mining.
     *
     * @param $username
     * @return int: remaining delay in seconds
     */
    public function getRemainingDelay($username)
    {
        $remaining_delay = 0;

        // select timestamp of last attempt for this account
        $select = $this->_db->prepare('SELECT MAX(attempted) AS attempted FROM logins WHERE username = :username');
        $select->execute(array('username' => $username));

        if ($select->rowCount() > 0) {
            $latest_attempt = (int) date('U', strtotime($select->fetchColumn(0)));

            // get the global number of failed attempts of last 15 minutes
            $select = $this->_db->prepare(
                'SELECT COUNT(1) AS failed 
				FROM logins 
				WHERE attempted > DATE_SUB(NOW(), INTERVAL 15 MINUTE) AND 
				success = 0'
            );
            $select->execute(array());

            if ($select->rowCount() > 0) {
                $failed_attempts = (int) $select->fetchColumn(0);

                // base delay is always 2 seconds, plus a tenth of the failed attempts last 10 minutes
                $delay = (int)floor($failed_attempts / 10) + 2;

                $remaining_delay = $delay - time() + $latest_attempt;
            }
        }

        return $remaining_delay;
    }

    /**
     * store a cookie to later restore session as described in:
     * https://paragonie.com/blog/2015/04/secure-authentication-php-with-long-term-persistence#title.2.1
     * @param $id user id to be restored later
     */
    private function createCookie($id)
    {
        $selector = $this->random_str(12);
        $validator = $this->random_str(50);

        $expires = new DateTime('+30 days');

        setcookie('onsreceptenboek[selector]', $selector, $expires->getTimestamp(), '/');
        setcookie('onsreceptenboek[validator]', $validator, $expires->getTimestamp(), '/');

        $insert = $this->_db->prepare(
            "INSERT INTO auth_tokens (
				`selector`,
				`validator`,
				`user_id`,
				`expires`
			) VALUES (
				:selector,
				:validator,
				:user_id,
				:expires				
			)"
        );
        $insert->execute(array(
            'selector' => $selector,
            'validator' => password_hash($validator, PASSWORD_DEFAULT),
            'user_id' => $id,
            'expires' => $expires->format('Y-m-d H:i:s')
        ));
    }
}