<?php
namespace cookbook\backend\classes;
class Ingredient extends Base
{
	public function list($request, $response, $args)
	{
		$sql = "SELECT 
					i.*,
					u.user AS modifier
				FROM ingredients i
				LEFT JOIN users u ON u.id = i.modifier
				ORDER BY name";
		$stmt = $this->db->prepare($sql);
		$result = $stmt->execute();

		$data['ingredients'] = $stmt->fetchAll();

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
						plural
                    FROM ingredients
                    WHERE id = :id";
			$stmt = $this->db->prepare($sql);
			$result = $stmt->execute(["id" => $args['id']]);

			$data['ingredient'] = $stmt->fetch();
		}

		return $this->render($response, $data);
	}

    public function delete($request, $response, $args)
    {
        $data = array();
        if (array_key_exists('id', $args)) {
            $data['id'] = $args['id'];

            $sql = "DELETE
                    FROM ingredients
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute(["id" => $args['id']]);

            $sql = "DELETE
                    FROM recipes_ingredients
                    WHERE ingredient_id = :id";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute(["id" => $args['id']]);
        }

        return $response->withHeader('Location', $this->baseUrl . '/ingredienten');
    }

	public function save($request, $response, $args)
	{
		$post = $request->getParsedBody();

        $user = $_SESSION['user']['id'];

		$plural = NULL;
		if ($post['plural'] != '') {
			$plural = $post['plural'];
		}

		if ($post['id']) {
			$id = $post['id'];

			$sql = "UPDATE ingredients
					SET 
						name = :name,
						plural = :plural,
						modified = NOW(),
						modifier = :user_id
					WHERE id = :id";
			$stmt = $stmt = $this->db->prepare($sql);
			$result = $stmt->execute([
				'name' => $post['name'],
				'plural' => $plural,
				'id' => $id,
                'user_id' => $user,
			]);
		} else {
			$sql = "INSERT INTO ingredients (
						name,
						plural,
						created,
						modified,
						creator,
						modifier
					) VALUES (
						:name,
						:plural,
						NOW(),
						NOW(),
						:user_id,
						:user_id
					)";
			$stmt = $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                'name' => $post['name'],
                'plural' => $plural,
                'user_id' => $user,
            ]);
		}

		return $response->withHeader('Location', $this->baseUrl . '/ingredienten');
	}
}