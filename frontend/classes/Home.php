<?php
namespace cookbook\frontend\classes;

class Home extends Base
{
    public function view($request, $response, $args)
    {
        $data['recipes'] = $this->recipeList();
        printr($data['recipes'] );
        return $this->view->render($response, 'frontend/tpl/home/browse.tpl', $data);
    }

    public function recipeList()
    {
        $sql = "SELECT
                    r.name,
                    r.intro,
                    r.path,
                    i.title,
                    i.path_thumb
                FROM recipes r 
                LEFT JOIN i.`images` ON i.id = r.image
                ORDER BY r.name";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute();

        return $stmt->fetchAll();
    }
}