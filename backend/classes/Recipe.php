<?php

namespace cookbook\backend\classes;

class Recipe extends Base
{
    public function list($request, $response, $args)
    {
        $data['query'] = $this->_getQueryFilter();

        $data['listHeaders'] = array(
            $this->createSortLink('Recept', 'name', 'asc'),
            $this->createSortLink('Gewijzigd', 'updated_at'),
            $this->createSortLink('Gewijzigd door', 'updated_by'),
        );

        $items = $this->getItems(new \model\database\Recipe());

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
        $model = new \model\database\Recipe();

        $data = array();
        if (array_key_exists('id', $args)) {
            $item = $model->find($args['id']);

            if ($item !== NULL) {
                $data['recipe'] = $item->toArray();

                $data['ingredients'] = $item->ingredientrow()->orderBy('position', 'asc')->get()->toArray();

                $data['tags'] = array();
                foreach ($item->tag()->select('tag_id')->get()->toArray() as $tag) {
                    $data['tags'][] = $tag['tag_id'];
                }
            }
        }

        $data['quantity_list'] = $this->getQuantityList();
        $data['ingredient_list'] = $this->getIngredientList();
        $data['image_list'] = $this->getImageList();
        $data['tag_list'] = $this->getTagList();

        $data['css'][] = '/js/libs/quill/dist/quill.snow.css';
        $data['css'][] = '/js/libs/selectize.js/css/selectize.bootstrap3.css';
        $data['css'][] = '/css/recipe.css';

        $data['js'][] = '/js/libs/quill/dist/quill.min.js';
        $data['js'][] = '/js/libs/sortable-min.js';
        $data['js'][] = '/js/libs/selectize.js/js/standalone/selectize.js';
        $data['js'][] = '/js/recipe.js';

        return $this->render($response, $data);
    }

    public function delete($request, $response, $args)
    {
        $model = new \model\database\Recipe();

        if (array_key_exists('id', $args)) {
            $item = $model->find($args['id']);

            if ($item !== NULL) {
                $name = $item->name;
                $item->delete();

                $this->flash->addMessage('info', 'Recept ' . $name . ' verwijderd.');
            }
        }

        return $response->withHeader('Location', $this->baseUrl . '/recepten');
    }

    public function save($request, $response, $args)
    {
        $post = $request->getParsedBody();

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
        $recipe->image_id = $post['image'];

        $recipe->updated_by = $user;

        $recipe->save();

        $tagList = array();
        if (isset($post['tags'])) {
            foreach ($post['tags'] as $tag) {
                $tagModel = new \model\database\Tag();

                // existing tags are identified by tag-id-XX
                if (strpos($tag, 'tag-id-') === 0) {
                    $tagId = (int) str_replace('tag-id-', '', $tag);

                    // check if found
                    $tagModel = $tagModel->find($tagId);
                // otherwise create new tag and add tag-id to list of tags
                } else {
                    $tagModel->name = $tag;
                    $tagModel->path = $this->slugify->slugify($tag);;;
                    $tagModel->save();
                }

                $tagList[] = $tagModel->id;
            }

            $recipe->tag()->sync($tagList);
        }

        // remove orphaned tags to keep things clean
        $orphans = \model\database\Tag::doesntHave('recipe')->get();

        foreach ($orphans as $orphan) {
            $orphan->delete();
        }

        $ingredientIDs = array();
        if (isset($post['ingredient'])) {
            // store found ingredient IDs so we can later check which were deleted
            $ingredientIDs = array();

            foreach ($post['ingredient'] as $item) {
                if ($item['ingredient_id'] == '') {
                    continue;
                }

                $ingredientRow = new \model\database\Ingredientrow();

                if (isset($item['id'])) {
                    $ingredientRow = $ingredientRow->firstOrNew(['id' => $item['id']]);
                }

                $ingredientRow->position = $item['position'];
                $ingredientRow->amount =  ($item['amount'] != '') ? $item['amount'] : NULL;

                if ($item['ingredient_id'] != '') {
                    $ingredient = (new \model\database\Ingredient())->find($item['ingredient_id']);

                    $ingredientRow->ingredient()->associate($ingredient);
                }

                if ($item['quantity_id'] != '') {
                    $quantity = (new \model\database\Quantity())->find($item['quantity_id']);

                    $ingredientRow->quantity()->associate($quantity);
                } else {
                    $ingredientRow->quantity()->dissociate();
                }

                $ingredientRow->recipe()->associate($recipe);
                $ingredientRow->save();

                $ingredientIDs[] = $ingredientRow->id;
            }

            // delete deleted ingredient rows
            if (!empty($ingredientIDs)) {
                $ingredientRow = new \model\database\Ingredientrow();
                $ingredientRow
                    ->where('recipe_id', $recipe->id)
                    ->whereNotIn('id', $ingredientIDs)
                    ->delete();
            }
        }

        if (empty($ingredientIDs)) {
            $ingredientRow = new \model\database\Ingredientrow();
            $ingredientRow
                ->where('recipe_id', $recipe->id)
                ->delete();
        }

        return $response->withHeader('Location', $this->baseUrl . '/recepten');
    }

    public function getQuantityList()
    {
        $quantity = new \model\database\Quantity();

        return $quantity->select('id','name')->orderBy('name')->get()->toArray();
    }

    public function getIngredientList()
    {
        $ingredient = new \model\database\Ingredient();

        return $ingredient->select('id','name')->orderBy('name')->get()->toArray();
    }

    public function getImageList()
    {
        $image = new \model\database\Image();

        return $image->select('id','title')->orderBy('title')->get()->toArray();
    }

    public function getTagList()
    {
        $tag = new \model\database\Tag();

        return $tag->select('id','name')->orderBy('name')->get()->toArray();
    }
}
