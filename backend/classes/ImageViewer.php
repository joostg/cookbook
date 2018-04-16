<?php
namespace cookbook\backend\classes;
class ImageViewer extends Base
{
    public function browse($request, $response, $args)
    {
        $model = new \model\database\Image();

        $data['images'] = $model->orderBy('created_at','asc')->get()->toArray();

        $data['js'][] = '/js/imageviewer.js';

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
            $image = new \model\database\Image();
            $image = $image->find($args['id']);

            if ($image !== NULL) {
                $uploadPath = $this->ci->get('settings')->get('pictures_path');

                unlink($uploadPath . $image->path_thumb);
                unlink($uploadPath . $image->path_recipe_page);
            }

            $image->delete();
        }

        return $response->withHeader('Location', $this->baseUrl . '/afbeeldingen');
    }
}