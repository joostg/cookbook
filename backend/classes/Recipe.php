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
            $recipeArray['updated_by'] = $recipe->updatedBy->user;

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

                $data['ingredients'] = $item->ingredientrow()->get()->toArray();

               // printr($data);die();
            }
        }

	    /*$data = array();
		if (array_key_exists('id', $args)) {
			$data['id'] = $args['id'];
			
			$sql = "SELECT 
						id,
						name,
						intro,
						description,
						image
                    FROM recipes
                    WHERE id = :id";
			$stmt = $this->db->prepare($sql);
			$result = $stmt->execute(["id" => $args['id']]);

			$data['recipe'] = $stmt->fetch();*/

			/*$sql = "SELECT
						quantity,
						quantity_id,
						ingredient_id
					FROM recipes_ingredients
					WHERE recipe_id = :recipe_id";
			$stmt = $this->db->prepare($sql);
			$result = $stmt->execute(["recipe_id" => $args['id']]);*/

			//$data['ingredients'] = array();
		//}

		$data['quantity_list'] = $this->getQuantityList();
		$data['ingredient_list'] = $this->getIngredientList();
		$data['image_list'] = $this->getImageList();

		$data['js'][] = '/js/libs/sortable-min.js';
		$data['js'][] = '/js/recipe.js';

		return $this->render($response, $data);
	}

    public function delete($request, $response, $args)
    {
        $data = array();
        if (array_key_exists('id', $args)) {
            $data['id'] = $args['id'];

            $sql = "DELETE
                    FROM recipes
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute(["id" => $args['id']]);

            $sql = "DELETE
                    FROM recipes_ingredients
                    WHERE recipe_id = :id";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute(["id" => $args['id']]);
        }

        return $response->withHeader('Location', $this->baseUrl . '/recepten');
    }

	public function save($request, $response, $args)
	{
		$post = $request->getParsedBody();
printr($post);die();
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
        $recipe->image = $post['image'];

        $recipe->updated_by = $user;

        $recipe->save();


		// @TODO: track changes to ingredients so we don't have to delete all rows all the time
		/*$sql = "DELETE FROM recipes_ingredients
				WHERE recipe_id = :recipe_id";
		$stmt = $stmt = $this->db->prepare($sql);
		$result = $stmt->execute([
			'recipe_id' => $id,
		]);*/

		for ($i = 1; array_key_exists('ingredient-ingredient-id-' . $i, $post); $i++) {
		    $ingredientRow = new \model\database\Ingredientrow();

            $amount = $post['ingredient-amount-' . $i];
            if ($amount != '') {
                $ingredientRow->amount = $amount;
            }

            $ingredientRow->position = $i;

            if ($post['ingredient-ingredient-id-' . $i]) {
                $ingredient = (new \model\database\Ingredient())->find($post['ingredient-ingredient-id-' . $i]);

                $ingredientRow->ingredient()->associate($ingredient);
            }

            if ($post['ingredient-quantity-id-' . $i]) {
                $quantity = (new \model\database\Quantity())->find($post['ingredient-quantity-id-' . $i]);

                $ingredientRow->quantity()->associate($quantity);
            }

            $ingredientRow->recipe()->associate($recipe);
            $ingredientRow->save();
		}

		return $response->withHeader('Location', $this->baseUrl . '/recepten');
	}

	public function getQuantityList()
	{
		$sql = "SELECT 
					id, 
					name
				FROM quantities
				ORDER BY name";
		$stmt = $this->db->prepare($sql);
		$result = $stmt->execute();

		return $stmt->fetchAll();
	}

	public function getIngredientList()
	{
		$sql = "SELECT 
					id, 
					name
				FROM ingredients
				ORDER BY name";
		$stmt = $this->db->prepare($sql);
		$result = $stmt->execute();

		return $stmt->fetchAll();
	}

    public function getImageList()
    {
        $sql = "SELECT 
					id, 
					title
				FROM images
				ORDER BY title";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute();

        return $stmt->fetchAll();
    }
}
