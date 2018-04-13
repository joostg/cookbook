<?php
namespace cookbook\frontend\classes;

class Recipe extends Base
{
	public function view($request, $response, $args)
	{
        $model = new \model\database\Recipe();

        $data = array();
        if (array_key_exists('path', $args)) {
            $item = $model->where(['path' => $args['path']])->first();

            if ($item !== NULL) {
                $data = $item->toArray();

                $data['path_recipe_page'] = $item->image->path_recipe_page;

                $ingredientRows =  $item->ingredientrow()->orderBy('position', 'asc')->get();

                foreach ($ingredientRows as $ingredientRow) {
                    $data['ingredients'][] = $ingredientRow->toString();
                }
            }
        }

		return $this->view->render($response, 'frontend/tpl/recipe/browse.tpl', $data);
	}
}
