<?php
namespace cookbook\frontend\classes;

class Recipe extends Base
{
    public function list($request, $response, $args)
    {
        $_SESSION['returnUrl'] = $_SERVER['REQUEST_URI'];

        $data['query'] = $this->_getQueryFilter();

        $items = $this->getItems(new \model\database\Recipe());

        foreach ($items as $item) {
            $itemArray = $item->toArray();

            if ($item->image !== NULL) {
                $itemArray['path_thumb'] = $item->image->path_thumb;
            }

            $data['items'][] = $itemArray;
        }

        $data['paging'] = $this->paging->getPagingData();

        $data['tag_filter'] = $this->getTagFilter();

        $data['css'][] = '/css/frontend-recipe-view.css';

        return $this->render($response, $data);
    }

    protected function getItems($model)
    {
        // get filters from query string
        $queryData = $this->qs->getQueryData();

        if ($queryData['sort'] == 'updated_at') {
            $queryData['sort'] = 'created_at';
        }

        $model = $model->orderBy($queryData['sort'], $queryData['order']);

        if (isset($queryData['query'])) {
            // TODO: use $model = $model->setQuery($queryData['query']);
            $model = $model->where('name', $queryData['query']);
        }
        if (isset($queryData['filters']) && !empty($queryData['filters'])) {
            foreach ($queryData['filters'] as $key => $value) {
                $model = $model->where($key, $value);
            }
        }

        $tag = $this->qs->getValue('tag');
        if ($tag) {
            $model = $model->whereHas('tag', function($query) use ($tag) {
                $query->where('path', $tag);
            });
        }

        $this->paging->setNumResults($model->count());

        $this->paging->setLimit(9);

        $model = $model->offset(($this->paging->getCurrentPage() - 1) * $this->paging->getLimit());
        $model = $model->limit($this->paging->getLimit());

        return $model->get();
    }

	public function view($request, $response, $args)
	{
        $model = new \model\database\Recipe();

        $data = array();
        if (array_key_exists('path', $args)) {
            $item = $model->where(['path' => $args['path']])->first();

            if ($item !== NULL) {
                $data = $item->toArray();

                $data['path_recipe_page'] = $item->image->path_recipe_page;

                $ingredientRows =  $item->ingredientrow()->orderBy('position', 'asc')->get();

                foreach ($ingredientRows as $ingredientRow) {
                    $data['ingredients'][] = $ingredientRow->toString();
                }
            }
        }

        return $this->render($response, $data);
	}

	private function getTagFilter()
    {
        $tags = \model\database\Tag::get(['path','name'])->toArray();

        $selected = $this->qs->getValue('tag');

        if ($selected) {
            $selectedKey = array_search($selected, array_column($tags, 'path'));

            $tags[$selectedKey]['selected'] = true;
        }

        return $tags;
    }
}
