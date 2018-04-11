<?php
namespace cookbook\frontend\classes;

class Home extends Base
{
    public function view($request, $response, $args)
    {
        $data['recipes'] = $this->recipeList();

        return $this->view->render($response, 'frontend/tpl/home/browse.tpl', $data);
    }

    public function recipeList()
    {
        $model = new \model\database\Quantity();

        $list = $model->take(10)->get();
        foreach ($list as $recipe) {
            printr($recipe->name);
            printr($recipe->modifiedBy->user);

        }
die();
        $sql = "SELECT
                    r.name,
                    r.intro,
                    r.path,
                    i.title,
                    i.path_thumb
                FROM recipes r 
                LEFT JOIN `images` i ON i.id = r.image
                ORDER BY r.name";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute();

        return $stmt->fetchAll();
    }
}