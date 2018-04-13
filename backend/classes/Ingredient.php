<?php
namespace cookbook\backend\classes;
class Ingredient extends Base
{
	public function list($request, $response, $args)
	{
        $data = array();

        $model = new \model\database\Ingredient();

        $ingredients = $model->get();

        foreach ($ingredients as $ingredient) {
            $ingredientArray = $ingredient->toArray();
            $ingredientArray['updated_by'] = $ingredient->updatedBy->username;

            $data['ingredients'][] = $ingredientArray;
        }

        return $this->render($response, $data);
	}

	public function edit($request, $response, $args)
	{
        $model = new \model\database\Ingredient();

        $data = array();
        if (array_key_exists('id', $args)) {
            $item = $model->find($args['id']);

            if ($item !== NULL) {
                $data['ingredient'] = $item->toArray();
            }
        }

        return $this->render($response, $data);
	}

    public function delete($request, $response, $args)
    {
        if (array_key_exists('id', $args)) {
            $model = new \model\database\Ingredient();
            $model->find( $args['id'])->delete();
        }

        return $response->withHeader('Location', $this->baseUrl . '/ingredienten');
    }

	public function save($request, $response, $args)
	{
        $post = $request->getParsedBody();

        $user = $this->getLoggedInUserID();

        $ingredient = new \model\database\Ingredient();

        if ($post['id']) {
            $ingredient = $ingredient->firstOrNew(['id' => $post['id']]);
        } else {
            $ingredient->created_by = $user;
        }

        $ingredient->name = $post['name'];
        $ingredient->plural = ($post['plural'] != '') ? $post['plural'] : NULL;
        $ingredient->updated_by = $user;

        $ingredient->save();

		return $response->withHeader('Location', $this->baseUrl . '/ingredienten');
	}
}