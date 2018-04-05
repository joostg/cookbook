<?php
namespace cookbook\frontend\classes;

class Recipe extends Base
{
	public function view($request, $response, $args)
	{
		$sql = "SELECT 
					r.id,
                    r.name,
                    r.intro,
                    r.description,
                    r.path,
                    i.title,
                    i.path_recipe_page
				FROM recipes r
				LEFT JOIN `images` i ON i.id = r.image
				WHERE path = :path";
		$stmt = $this->db->prepare($sql);
		$result = $stmt->execute(["path" => $args['path']]);

		$data = $stmt->fetch();

		$sql = "SELECT 
					ri.quantity,
					i.name AS ingredient_name,
					q.name AS quantity_name
				FROM recipes_ingredients ri
				LEFT JOIN ingredients i ON i.id = ri.ingredient_id
				LEFT JOIN quantities q ON q.id = ri.quantity_id
				WHERE ri.recipe_id = :recipe_id";
		$stmt = $this->db->prepare($sql);
		$result = $stmt->execute(["recipe_id" => $data['id']]);

		$data['ingredients'] = $stmt->fetchAll();

		// cast all quantities as float to get nice decimal format
		foreach ($data['ingredients'] as $ingredient => $values) {
			if ($values['quantity']) {
				$data['ingredients'][$ingredient]['quantity'] = floatval($values['quantity']);
			}
		}

		return $this->view->render($response, 'frontend/tpl/recipe/browse.tpl', $data);
	}

	public function admin_list($request, $response, $args)
	{
		$sql = "SELECT 
					id,
					name,
					created
				FROM recipes
				ORDER BY name";
		$stmt = $this->db->prepare($sql);
		$result = $stmt->execute();
		
		$data['recipes'] = $stmt->fetchAll();

		return $this->view->render($response, 'recipe/admin_list.tpl', $data);
	}
	
	public function admin_edit($request, $response, $args)
	{
		$data = array();
		if (array_key_exists('id', $args)) {
			$data['id'] = $args['id'];
			
			$sql = "SELECT 
						id,
						name,
						intro,
						description
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

		$data['js'][] = '/js/libs/sortable-min.js';
		$data['js'][] = '/js/recipe.js';

		return $this->view->render($response, 'recipe/admin_edit.tpl', $data);
	}

	public function admin_save($request, $response, $args)
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

		return $response->withHeader('Location', '/achterkant/recepten');
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
}
