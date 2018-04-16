<?php
namespace cookbook\backend\classes;
class Quantity extends Base
{
	public function list($request, $response, $args)
	{
	    $items = $this->getItems(new \model\database\Quantity());

        foreach ($items as $quantity) {
            $quantityArray = $quantity->toArray();
            $quantityArray['updated_by'] = $quantity->updatedBy->username;

            $data['items'][] = $quantityArray;
        }

        $data['paging'] = $this->paging->getPagingData();

        return $this->render($response, $data);
	}

	public function edit($request, $response, $args)
	{
        $model = new \model\database\Quantity();

	    $data = array();
		if (array_key_exists('id', $args)) {
		    $item = $model->find($args['id']);

		    if ($item !== NULL) {
                $data['quantity'] = $item->toArray();
            }
		}

		return $this->render($response, $data);
	}

    public function delete($request, $response, $args)
    {
        if (array_key_exists('id', $args)) {
            $model = new \model\database\Quantity();
            $quantity = $model->find( $args['id']);
            
            // dissociate from ingredient rwos
            foreach ($quantity->ingredientrow as $ingredientrow) {
                $ingredientrow->quantity()->dissociate();
                $ingredientrow->save();
            }

            $quantity->delete();
        }

        return $response->withHeader('Location', $this->baseUrl . '/hoeveelheden');
    }

	public function save($request, $response, $args)
	{
		$post = $request->getParsedBody();

		$user = $this->getLoggedInUserID();

        $quantity = new \model\database\Quantity();

        if ($post['id']) {
            $quantity = $quantity->firstOrNew(['id' => $post['id']]);
        } else {
            $quantity->created_by = $user;
        }

        $quantity->name = $post['name'];
        $quantity->plural = ($post['plural'] != '') ? $post['plural'] : NULL;
        $quantity->updated_by = $user;

        $quantity->save();

		return $response->withHeader('Location', $this->baseUrl . '/hoeveelheden');
	}
}