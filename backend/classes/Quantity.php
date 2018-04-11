<?php
namespace cookbook\backend\classes;
class Quantity extends Base
{
	public function list($request, $response, $args)
	{
	    $model = new \model\database\Quantity();

        $quantities = $model->get();

        foreach ($quantities as $quantity) {
            $quantityArray = $quantity->toArray();
            $quantityArray['modifier'] = $quantity->modifiedBy->user;

            $data['quantities'][] = $quantityArray;
        }

        return $this->render($response, $data);
	}

	public function edit($request, $response, $args)
	{
		$data = array();
		if (array_key_exists('id', $args)) {
		    $data['quantity'] = \model\database\Quantity::find($args['id'])->toArray();
		}

		return $this->render($response, $data);
	}

    public function delete($request, $response, $args)
    {
        $data = array();
        if (array_key_exists('id', $args)) {
            $data['id'] = $args['id'];

            $sql = "DELETE
                    FROM quantities
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute(["id" => $args['id']]);

            $sql = "UPDATE recipes_ingredients
                    SET quantity_id = NULL            
                    WHERE quantity_id = :id";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute(["id" => $args['id']]);
        }

        return $response->withHeader('Location', $this->baseUrl . '/hoeveelheden');
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

			$sql = "UPDATE quantities
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
			$sql = "INSERT INTO quantities (
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

		return $response->withHeader('Location', $this->baseUrl . '/hoeveelheden');
	}
}