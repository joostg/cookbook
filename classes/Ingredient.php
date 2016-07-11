<?php

class Ingredient extends Base
{
	public function admin_list($request, $response, $args)
	{
		$sql = "SELECT 
					id,
					name,
					plural
				FROM ingredients
				ORDER BY name";
		$stmt = $this->db->prepare($sql);
		$result = $stmt->execute();

		$data['ingredients'] = $stmt->fetchAll();

		return $this->view->render($response, 'ingredient/admin_list.tpl', $data);
	}

	public function admin_edit($request, $response, $args)
	{
		$data = array();
		if (array_key_exists('id', $args)) {
			$data['id'] = $args['id'];

			$sql = "SELECT 
						id,
						name,
						plural
                    FROM ingredients
                    WHERE id = :id";
			$stmt = $this->db->prepare($sql);
			$result = $stmt->execute(["id" => $args['id']]);

			$data['ingredient'] = $stmt->fetch();
		}

		return $this->view->render($response, 'ingredient/admin_edit.tpl', $data);
	}

	public function admin_save($request, $response, $args)
	{
		$post = $request->getParsedBody();

		$plural = NULL;
		if ($post['plural'] != '') {
			$plural = $post['plural'];
		}

		if ($post['id']) {
			$id = $post['id'];

			$sql = "UPDATE ingredients
					SET 
						name = :name,
						plural = :plural
					WHERE id = :id";
			$stmt = $stmt = $this->db->prepare($sql);
			$result = $stmt->execute([
				'name' => $post['name'],
				'plural' => $plural,
				'id' => $id,
			]);
		} else {
			$sql = "INSERT INTO ingredients (
						name,
						plural
					) VALUES (
						:name,
						:plural
					)";
			$stmt = $stmt = $this->db->prepare($sql);
			$result = $stmt->execute([
				'name' => $post['name'],
				'plural' => $plural,
			]);
		}

		return $response->withHeader('Location', '/achterkant/ingredienten');
	}
}