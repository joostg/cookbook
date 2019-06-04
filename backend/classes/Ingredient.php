<?php

namespace cookbook\backend\classes;

class Ingredient extends Base
{
    public function list($request, $response, $args)
    {
        $data['query'] = $this->_getQueryFilter();

        $data['listHeaders'] = array(
            $this->createSortLink('Ingrediënt', 'name', 'asc'),
            $this->createSortLink('Meervoud', 'plural', 'asc'),
            $this->createSortLink('Gewijzigd', 'updated_at'),
            $this->createSortLink('Gewijzigd door', 'updated_by'),
        );

        $items = $this->getItems(new \model\database\Ingredient());

        foreach ($items as $item) {
            $itemArray = $item->toArray();
            $itemArray['updated_by'] = $item->updatedBy->username;

            $data['items'][] = $itemArray;
        }

        $data['paging'] = $this->paging->getPagingData();

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