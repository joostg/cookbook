<?php
namespace cookbook\backend\classes;
class Recipe extends Base
{
	public function list($request, $response, $args)
	{
		$sql = "SELECT 
					r.*,
					u.user AS modifier
				FROM recipes r
				LEFT JOIN users u ON u.id = r.modifier
				ORDER BY name";
		$stmt = $this->db->prepare($sql);
		$result = $stmt->execute();
		
		$data['recipes'] = $stmt->fetchAll();

		return $this->render($response, $data);
	}
	
	public function edit($request, $response, $args)
	{
		$data = array();
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

			$data['recipe'] = $stmt->fetch();

			$sql = "SELECT 
						quantity,
						quantity_id,
						ingredient_id
					FROM recipes_ingredients
					WHERE recipe_id = :recipe_id";
			$stmt = $this->db->prepare($sql);
			$result = $stmt->execute(["recipe_id" => $args['id']]);

			$data['ingredients'] = $stmt->fetchAll();
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

		$path = $this->slugify->slugify($post['name']);

		if ($post['id']) {
			$id = $post['id'];
			$sql = "UPDATE recipes
					SET 
						name = :name,
						path = :path,
						intro = :intro,
						description = :description,
						image = :image
					WHERE id = :id";
			$stmt = $stmt = $this->db->prepare($sql);
			$result = $stmt->execute([
				'name' => $post['name'],
				'path' => $path,
				'intro' => $post['intro'],
				'description' => $post['description'],
				'image' => $post['image'],
				'id' => $id,
			]);
		} else {
			$sql = "INSERT INTO recipes (
						name,
						path,
						intro,
						description,
						image,
						created,
						creator,
						modified,
						modifier
					) VALUES (
						:name,
						:path,
						:intro,
						:description,
						:image,
						NOW(),
						1,
						NOW(),
						1
					)";
			$stmt = $stmt = $this->db->prepare($sql);
			$result = $stmt->execute([
				'name' => $post['name'],
				'path' => $path,
				'intro' => $post['intro'],
				'description' => $post['description'],
				'image' => $post['image'],
			]);

			$id = $this->db->lastInsertId();
		}

		// @TODO: track changes to ingredients so we don't have to delete all rows all the time
		$sql = "DELETE FROM recipes_ingredients
				WHERE recipe_id = :recipe_id";
		$stmt = $stmt = $this->db->prepare($sql);
		$result = $stmt->execute([
			'recipe_id' => $id,
		]);

		for ($i = 1; $i; $i++) {
			if (array_key_exists('ingredient-quantity-' . $i, $post)) {
				if ($post['ingredient-ingredient-id-' . $i]) {
					$quantityId = $post['ingredient-quantity-id-' . $i];
					if ($quantityId == '') {
						$quantityId = NULL;
					}

					$quantity = $post['ingredient-quantity-' . $i];
					if ($quantity == '') {
						$quantity = NULL;
					}

					$sql = "INSERT INTO recipes_ingredients (
								recipe_id,
								ingredient_id,
								quantity_id,
								quantity,
								position
							) VALUES (
								:recipe_id,
								:ingredient_id,
								:quantity_id,
								:quantity,
								:position
							)";
					$stmt = $stmt = $this->db->prepare($sql);
					$result = $stmt->execute([
						'recipe_id' => $id,
						'ingredient_id' => (int) $post['ingredient-ingredient-id-' . $i],
						'quantity_id' => $quantityId,
						'quantity' => $quantity,
						'position' => $i,
					]);
				}
			} else {
				break;
			}
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
