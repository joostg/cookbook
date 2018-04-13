<?php
namespace cookbook\backend\classes;
use model\database\Auth_token;

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
                    $this->saveUserToSession($user);

                    $this->createCookie($user);

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
        $user = new \model\database\User();
        $user = $user->where('username',$username)->first();

        // verify credentials
        if ($user !== NULL) {
            if (password_verify($password, $user->password)) {
                $this->logLogin($username, 1);

                return $user;
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

        $authToken = new \model\database\Auth_token();
        $authToken = $authToken
            ->where('selector',$cookie['selector'])
            ->where('expires','>=',(new \DateTime()))
            ->first();

        if ($authToken === NULL) {
            return false;  // no valid token found
        }

        if (!password_verify($cookie['validator'], $authToken['validator'])) {
            return false; // invalid validator
        }

        // get user
        $user = $authToken->user()->get();

        if ($user) {
            $this->saveUserToSession($user);

            // update cookie with new validator and expires
            $validator = $this->random_string(50);
            $expires = new \DateTime('+30 days');

            setcookie($cookieName.'[selector]', $cookie['selector'], $expires->getTimestamp(), '/');
            setcookie($cookieName.'[validator]', $validator, $expires->getTimestamp(), '/');

            $authToken->validator = password_hash($validator, PASSWORD_DEFAULT);
            $authToken->expires  = $expires;
            $authToken->save();

            return true;
        }

        return false;
    }

    private function saveUserToSession($user)
    {
        $sessionUser = $user->toArray();
        $sessionUser = $sessionUser[0];
        unset($sessionUser['password']);

        $_SESSION['user'] = $sessionUser;
    }

    private function logLogin($username, $result)
    {
        $login = new \model\database\Login();
        $login->username = $username;
        $login->ip_address = ip2long($_SERVER['REMOTE_ADDR']);
        $login->attempted = new \DateTime();
        $login->success = $result;

        $login->save();
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
        $logins = new \model\database\Login();

        $latest_attempt = $logins->where('username',$username)->max('attempted');

        if ($latest_attempt) {
            $latest_attempt = (int) date('U', strtotime($latest_attempt));

            $failed_attempts = $logins
                ->where('attempted','>=',new \DateTime('-15 minutes'))
                ->where('success',0)->count();

            if ($failed_attempts > 0) {
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
    private function createCookie($user)
    {
        $cookieName = $this->ci->get('settings')->get('cookie_name');

        $selector = $this->random_string(12);
        $validator = $this->random_string(50);

        $expires = new \DateTime('+30 days');

        setcookie($cookieName.'[selector]', $selector, $expires->getTimestamp(), '/');
        setcookie($cookieName.'[validator]', $validator, $expires->getTimestamp(), '/');

        // save token credentials to database
        $auth_token = new \model\database\Auth_token();
        $auth_token->selector = $selector;
        $auth_token->validator =  password_hash($validator, PASSWORD_DEFAULT);
        $auth_token->expires = $expires;

        $auth_token->user()->associate($user);
        $auth_token->save();
    }
}