<?php

class Dashboard extends Base
{
    public function view($request, $response, $args)
    {
        $user = $_SESSION['user'];

        return $this->view->render($response, 'dashboard/browse.tpl', ['user' => $user]);
    }
}