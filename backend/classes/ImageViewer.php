<?php
namespace cookbook\backend\classes;
class ImageViewer extends Base
{
    public function browse($request, $response, $args)
    {
        $sql = "SELECT 
					id,
					path_thumb,
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

    public function delete($request, $response, $args)
    {
        if (array_key_exists('id', $args)) {
            $id = $args['id'];

            $select = $this->db->prepare(
                "SELECT path_thumb, path_recipe_page FROM images WHERE id = ?"
            );
            $select->execute(array($id));

            $imageFiles = $select->fetch();
            $uploadPath = $this->ci->get('settings')->get('pictures_path');

            foreach ($imageFiles as $imageFile) {
                $fullPath = $uploadPath . $imageFile;
                unlink($fullPath);
            }

            $delete = $this->db->prepare(
                "DELETE FROM images WHERE id = ?"
            );
            $delete->execute(array($id));
        }

        return $response->withHeader('Location', $this->baseUrl . '/afbeeldingen');
    }
}