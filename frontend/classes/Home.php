<?php
namespace cookbook\frontend\classes;

class Home extends Base
{
    public function view($request, $response, $args)
    {
        $data['recipes'] = $this->recipeList();

        $data['css'][] = '/css/frontend-recipe-view.css';

        return $this->render($response, $data);
    }

    public function recipeList()
    {
        $model = new \model\database\Recipe();

        $recipes = $model->orderBy('created_at', 'desc')->take(3)->get();

        $return = array();
        foreach ($recipes as $recipe) {
            $recipeArray = $recipe->toArray();

            if ($recipe->image !== NULL) {
                $recipeArray['path_thumb'] = $recipe->image->path_thumb;
            }

            $return[] = $recipeArray;
        }

        return $return;
    }
}