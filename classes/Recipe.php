<?php

class Recipe extends Base
{
	public function view($request, $response, $args)
	{
		$sql = "SELECT id, name, intro, description
                    FROM recipes
                    WHERE path = :path";
		$stmt = $this->db->prepare($sql);
		$result = $stmt->execute(["path" => $args['path']]);

		$data = $stmt->fetch();

		$sql = "SELECT ri.quantity, i.name AS ingredient_name, q.name AS quantity_name
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

		return $this->view->render($response, 'recipe/browse.tpl', $data);
	}

	public function edit($request, $response, $args)
	{
		$data = array();
		if ($args['id']) {
			$sql = "SELECT id, name, intro, description
                    FROM recipes
                    WHERE id = :id";
			$stmt = $this->db->prepare($sql);
			$result = $stmt->execute(["id" => $args['id']]);

			$data = $stmt->fetch();
		}
		
		$data['js'][] = '/js/libs/sortable-min.js';
		$data['js'][] = '/js/recipe.js';

		return $this->view->render($response, 'recipe/edit.tpl', $data);
	}

	public function save($request, $response, $args)
	{
		var_dump($request->getParsedBody());
		die();

		return $this->view->render($response, 'recipe/edit.tpl', $data);
	}
}
