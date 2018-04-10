<?php
namespace cookbook\backend\classes;
class User extends Base
{
    public function login($request, $response, $args)
    {


        return $this->render($response, array());
    }

    public function authenticate($request, $response, $args)
    {
        $username = $request->getParam('user');
        $pass = $request->getParam('pass');

        if ($pass || $username) {
            $remaining_delay = $this->getRemainingDelay($username);

            if ($remaining_delay > 0) {
                $errorMsg = 'Probeer het over ' . $remaining_delay . ' seconden nogmaals';
            } else {
                $user = $this->verifyPassword($username, $pass);

                if ($user) {
                    unset($user['hash']);
                    $_SESSION['user'] = $user;

                    $this->createCookie($user['id']);

                    return $response->withHeader('Location', $this->getReturnUri());
                }

                $errorMsg = 'Ongeldige gebruikersnaam / wachtwoord opgegeven, probeer het opnieuw.';
            }
        } else {
            $errorMsg = 'Vul iets in.';
        }

        $this->flash->addMessage('alert', $errorMsg);

        return $response->withHeader('Location', $this->baseUrl . '/login');
    }

    private function verifyPassword($username, $password)
    {
        $select = $this->db->prepare("SELECT * FROM users WHERE user = :username");
        $select->execute(array(
            'username' => $username,
        ));
        $result = $select->fetch();

        // verify credentials
        if ($result) {
            if (password_verify($password, $result['hash'])) {
                $this->logLogin($password, 1);
                return $result;
            }
        }

        $this->logLogin($username, 0);
        return false;
    }

    public function logout($request, $response, $args)
    {
        session_destroy();

        // destroy cookie
        $cookieName = $this->ci->get('settings')->get('cookie_name');

        $expires = new \DateTime('-1 hours');
        setcookie($cookieName.'[selector]', "", $expires->getTimestamp(), '/');
        setcookie($cookieName.'[validator]', "", $expires->getTimestamp(), '/');

        return $response->withHeader('Location', $this->baseUrl . '/login');
    }

    public function restoreCookie()
    {
        $cookieName = $this->ci->get('settings')->get('cookie_name');

        // check if cookie exists
        if (!isset($_COOKIE[$cookieName]) || !isset($_COOKIE[$cookieName]['selector']) || !isset($_COOKIE[$cookieName]['validator'])) {
            return false;
        }

        foreach ($_COOKIE[$cookieName] as $name => $value) {
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

        if ($user) {
            unset($user['hash']);

            $_SESSION['user'] = $user;

            // update cookie with new validator and expires
            $validator = $this->random_string(50);
            $expires = new \DateTime('+30 days');

            setcookie($cookieName.'[selector]', $cookie['selector'], $expires->getTimestamp(), '/');
            setcookie($cookieName.'[validator]', $validator, $expires->getTimestamp(), '/');

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

        return false;
    }

    private function logLogin($user, $result)
    {
        $log = $this->db->prepare(
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
        $select = $this->db->prepare('SELECT MAX(attempted) AS attempted FROM logins WHERE username = :username');
        $select->execute(array('username' => $username));

        if ($select->rowCount() > 0) {
            $latest_attempt = (int) date('U', strtotime($select->fetchColumn(0)));

            // get the global number of failed attempts of last 15 minutes
            $select = $this->db->prepare(
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
        $cookieName = $this->ci->get('settings')->get('cookie_name');

        $selector = $this->random_string(12);
        $validator = $this->random_string(50);

        $expires = new \DateTime('+30 days');

        setcookie($cookieName.'[selector]', $selector, $expires->getTimestamp(), '/');
        setcookie($cookieName.'[validator]', $validator, $expires->getTimestamp(), '/');

        $insert = $this->db->prepare(
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