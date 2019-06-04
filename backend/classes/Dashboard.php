<?php

namespace cookbook\backend\classes;

class Dashboard extends Base
{
    public function browse($request, $response, $args)
    {
        $data['user'] = $_SESSION['user']['firstname'] . ' ' .  $_SESSION['user']['name'];

        return $this->render($response, $data);
    }
}