<?php
namespace cookbook\backend\classes;
class ImageViewer extends Base
{
    public function browse($request, $response, $args)
    {
        $sql = "SELECT 
					id,
					path_orig,
					title
				FROM images
				ORDER BY created ASC";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute();

        $data['images'] = $stmt->fetchAll();

        return $this->render($response, $data);
    }

    public function upload($request, $response, $args)
    {
        $image = new Image($this->ci);

        $image->saveRecipeImage($_FILES, $_POST['title']);

        return $response->withHeader('Location', $this->baseUrl . '/afbeeldingen');
    }
}