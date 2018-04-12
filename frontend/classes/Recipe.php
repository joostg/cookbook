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

                $ingredientRows =  $item->ingredientrow()->orderBy('position', 'asc')->get();

                foreach ($ingredientRows as $ingredientRow) {
                    $ingredientRow->ingredient_name = $ingredientRow->ingredient->name;

                    if ($ingredientRow->quantity) {
                        $ingredientRow->quantity_name = $ingredientRow->quantity->name;
                    }

                    $data['ingredients'][] = $ingredientRow->toArray();
                }
            }
        }

		return $this->view->render($response, 'frontend/tpl/recipe/browse.tpl', $data);
	}
}
