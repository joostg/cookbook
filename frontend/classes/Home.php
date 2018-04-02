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
        $sql = "SELECT
                    name,
                    intro,
                    path
                FROM recipes
                ORDER BY name";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute();

        return $stmt->fetchAll();
    }
}