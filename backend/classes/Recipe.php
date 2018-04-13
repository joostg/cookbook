<?php
namespace cookbook\backend\classes;
class Recipe extends Base
{
	public function list($request, $response, $args)
	{
        $data = array();

        $model = new \model\database\Recipe();

        $recipes = $model->get();

        foreach ($recipes as $recipe) {
            $recipeArray = $recipe->toArray();
            $recipeArray['updated_by'] = $recipe->updatedBy->username;

            $data['recipes'][] = $recipeArray;
        }

		return $this->render($response, $data);
	}
	
	public function edit($request, $response, $args)
	{
        $model = new \model\database\Recipe();

        $data = array();
        if (array_key_exists('id', $args)) {
            $item = $model->find($args['id']);

            if ($item !== NULL) {
                $data['recipe'] = $item->toArray();

                $data['ingredients'] = $item->ingredientrow()->orderBy('position', 'asc')->get()->toArray();
            }
        }

		$data['quantity_list'] = $this->getQuantityList();
		$data['ingredient_list'] = $this->getIngredientList();
		$data['image_list'] = $this->getImageList();

		$data['js'][] = '/js/libs/sortable-min.js';
		$data['js'][] = '/js/recipe.js';

		return $this->render($response, $data);
	}

    public function delete($request, $response, $args)
    {
        $model = new \model\database\Recipe();

        if (array_key_exists('id', $args)) {
            $item = $model->find($args['id']);

            if ($item !== NULL) {
                $name = $item->name;
                $item->delete();

                $this->flash->addMessage('info', 'Recept ' . $name . ' verwijderd.');
            }
        }

        return $response->withHeader('Location', $this->baseUrl . '/recepten');
    }

	public function save($request, $response, $args)
	{
		$post = $request->getParsedBody();

        $user = $_SESSION['user']['id'];

        $recipe = new \model\database\Recipe();

        if ($post['id']) {
            $recipe = $recipe->firstOrNew(['id' => $post['id']]);
        } else {
            $recipe->created_by = $user;
        }

        $recipe->name = $post['name'];
        $recipe->path = $this->slugify->slugify($post['name']);;
        $recipe->intro = $post['intro'];
        $recipe->description = $post['description'];
        $recipe->image_id = $post['image'];

        $recipe->updated_by = $user;

        $recipe->save();

		if (isset($post['ingredient'])) {
		    // store found ingredient IDs so we can later check which were deleted
		    $ingredientIDs = array();

		    foreach ($post['ingredient'] as $item) {
		        if ($item['ingredient_id'] == '') {
                    continue;
                }

                $ingredientRow = new \model\database\Ingredientrow();

                if (isset($item['id'])) {
                    $ingredientRow = $ingredientRow->firstOrNew(['id' => $item['id']]);
                }

                $ingredientRow->position = $item['position'];
                $ingredientRow->amount =  ($item['amount'] != '') ? $item['amount'] : NULL;

                if ($item['ingredient_id'] != '') {
                    $ingredient = (new \model\database\Ingredient())->find($item['ingredient_id']);

                    $ingredientRow->ingredient()->associate($ingredient);
                }

                if ($item['quantity_id'] != '') {
                    $quantity = (new \model\database\Quantity())->find($item['quantity_id']);

                    $ingredientRow->quantity()->associate($quantity);
                } else {
                    $ingredientRow->quantity()->dissociate();
                }

                $ingredientRow->recipe()->associate($recipe);
                $ingredientRow->save();

                $ingredientIDs[] = $ingredientRow->id;
            }

            // delete deleted ingredient rows
            if (!empty($ingredientIDs)) {
                $ingredientRow = new \model\database\Ingredientrow();
                $ingredientRow->whereNotIn('id', $ingredientIDs)->delete();
            }
        }

		return $response->withHeader('Location', $this->baseUrl . '/recepten');
	}

	public function getQuantityList()
	{
        $quantity = new \model\database\Quantity();

        return $quantity->select('id','name')->orderBy('name')->get()->toArray();
	}

	public function getIngredientList()
	{
		$ingredient = new \model\database\Ingredient();

		return $ingredient->select('id','name')->orderBy('name')->get()->toArray();
	}

    public function getImageList()
    {
        $image = new \model\database\Image();

        return $image->select('id','title')->orderBy('title')->get()->toArray();
    }
}
