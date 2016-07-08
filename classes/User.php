<?php

class User extends Base
{
    public function login($request, $response, $args)
    {
        return $this->view->render($response, 'user/login.tpl');
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

                    $uri = '/';
                    if ($_SESSION['returnUrl']) {
                        $uri = $_SESSION['returnUrl'];
                    }

                    return $response->withHeader('Location', $uri);
                }
            }
        }
    
        return $response->withHeader('Location', '/login');
    }

    public function logout($request, $response, $args)
    {
        session_destroy();

        return $response->withHeader('Location', '/login');
    }
}