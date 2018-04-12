<?php
namespace cookbook\frontend\classes;

class Home extends Base
{
    public function view($request, $response, $args)
    {
        $data['recipes'] = $this->recipeList();

        return $this->view->render($response, 'frontend/tpl/home/browse.tpl', $data);
    }

    public function recipeList()
    {
        $model = new \model\database\Recipe();

        return $model->orderBy('created_at', 'asc')->take(10)->get()->toArray();
    }
}