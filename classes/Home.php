<?php

class Home extends Base
{
    public function view($request, $response, $args)
    {
        return $this->view->render($response, 'home/browse.tpl');
    }
}