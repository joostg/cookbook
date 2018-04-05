<?php
namespace cookbook\backend\classes;
class Image
{
    protected $baseUrl;
    protected $ci;
    protected $db;
    protected $slugify;

    private $uploadPath = '/var/www/html/cookbook/web/pics/';
    private $imageFile;
    private $errorMessage;
    private $extension;

    private $pathOriginal = '';
    private $pathThumb = '';
    private $pathRecipePage = '';

    public function __construct(\Slim\Container $ci)
    {
        $this->ci = $ci;
        $this->db = $this->ci->get('db');
        $this->slugify = $this->ci->get('slugify');
        $this->baseUrl = $this->ci->get('settings')->get('base_url');
    }
    
    public function saveRecipeImage($imageFile, $title)
    {
        $this->imageFile = $imageFile;
        
        if ( !$this->validateImage() ) {
            return $this->errorMessage;
        }

        if ( !$this->validateImageExtension() ) {
            return $this->errorMessage;
        }

        if ( !$this->uploadImage() ) {
            return $this->errorMessage;
        }

        // @TODO: generate thumbnail and resized recipe page image

        $insertId = $this->saveToDb($title);
        if ( !$insertId ) {
            return $this->errorMessage;
        }

        return $insertId;
    }

    private function validateImage()
    {
        if (!$this->imageFile) {
            $this->imageFile = $_FILES;
        }

        if ( !isset($this->imageFile['image']['error']) ) {
            $this->errorMessage = 'Invalid parameters.';
            return false;
        }

        //multiple uploads not permitted. you should queue file uploads from the client
        if ( is_array($this->imageFile['image']['error']) ) {
            $this->errorMessage = 'Only one file allowed.';
            return false;
        }

        switch ( $this->imageFile['image']['error'] ) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                $this->errorMessage = 'No file sent.';
                return false;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $this->errorMessage = 'Exceeded filesize limit.';
                return false;
            default:
                $this->errorMessage = 'Unknown errors.';
                return false;
        }

        /*$max = ini_get('upload_max_filesize') * 1000 * 1000;
        if ( $this->imageFile['image']['size'] > $max ) {
            $this->errorMessage = 'Exceeded filesize limit.';
            return false;
        }*/

        return true;
    }

    private function validateImageExtension()
    {
        // todo: check the file type - but not the one sent by the browser instead use finfo
        /*$finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($this->imageFile['files']['tmp_name']);*/
        $verifyimg = getimagesize($_FILES['image']['tmp_name']);
        $allowed = array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif');
        $this->extension = array_search($verifyimg['mime'], $allowed, true);

        if ( false === $this->extension ) {
            $this->errorMessage = 'Invalid file format.';
            return false;
        }

        return true;
    }

    public function uploadImage()
    {
        $path = $this->generateUniquePath();

        if (!move_uploaded_file( $this->imageFile['image']['tmp_name'], $path)) {
            $this->errorMessage = 'Failed to move uploaded file.';
            return false;
        } else {
            return true;
        }
    }

    private function generateUniquePath()
    {
        $filenameHash = md5(uniqid($this->imageFile['image']["name"], true));
        $this->pathOriginal = $filenameHash . '.' . $this->extension;;
        $path = $this->uploadPath . $this->pathOriginal;

        // check if file doesn't exist yet
        $incr = 0;
        while(file_exists($path)){
            $this->pathOriginal = $filenameHash . '_' . $incr . '.' . $this->extension;;
            $path = $this->uploadPath . '/' . $this->pathOriginal;
            $incr++;
        }

        return $path;
    }

    private function saveToDb($title)
    {
        $insert = $this->db->prepare(
            "INSERT INTO images (
                `path_orig`,
                `path_thumb`,
                `path_recipe_page`,
                `extension`,
                `title`,
                `created`,
                `creator`,
                `modified`,
                `modifier`
            ) VALUES (
                :path_orig,
                :path_thumb,
                :path_recipe_page,
                :extension,
                :title,
                NOW(),
                1,
                NOW(),
                1
            )"
        );
        $insert->execute(array(
            'path_orig' => $this->pathOriginal,
            'path_thumb' => $this->pathThumb,
            'path_recipe_page' => $this->pathRecipePage,
            'extension' => $this->extension,
            'title' => $title
        ));

        $errorInfo = $insert->errorInfo();
        if ( $errorInfo[0] != '00000' ) {
            $this->errorMessage = $errorInfo[2];
            return false;
        }

        return $this->db->lastInsertId();
    }

    function generate_image_thumbnail($source_image_path, $thumbnail_image_path)
    {
        list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);
        switch ($source_image_type) {
            case IMAGETYPE_GIF:
                $source_gd_image = imagecreatefromgif($source_image_path);
                break;
            case IMAGETYPE_JPEG:
                $source_gd_image = imagecreatefromjpeg($source_image_path);
                break;
            case IMAGETYPE_PNG:
                $source_gd_image = imagecreatefrompng($source_image_path);
                break;
        }
        if ($source_gd_image === false) {
            return false;
        }
        $source_aspect_ratio = $source_image_width / $source_image_height;
        $thumbnail_aspect_ratio = THUMBNAIL_IMAGE_MAX_WIDTH / THUMBNAIL_IMAGE_MAX_HEIGHT;
        if ($source_image_width <= THUMBNAIL_IMAGE_MAX_WIDTH && $source_image_height <= THUMBNAIL_IMAGE_MAX_HEIGHT) {
            $thumbnail_image_width = $source_image_width;
            $thumbnail_image_height = $source_image_height;
        } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
            $thumbnail_image_width = (int) (THUMBNAIL_IMAGE_MAX_HEIGHT * $source_aspect_ratio);
            $thumbnail_image_height = THUMBNAIL_IMAGE_MAX_HEIGHT;
        } else {
            $thumbnail_image_width = THUMBNAIL_IMAGE_MAX_WIDTH;
            $thumbnail_image_height = (int) (THUMBNAIL_IMAGE_MAX_WIDTH / $source_aspect_ratio);
        }
        $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);
        imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);

        $img_disp = imagecreatetruecolor(THUMBNAIL_IMAGE_MAX_WIDTH,THUMBNAIL_IMAGE_MAX_WIDTH);
        $backcolor = imagecolorallocate($img_disp,0,0,0);
        imagefill($img_disp,0,0,$backcolor);

        imagecopy($img_disp, $thumbnail_gd_image, (imagesx($img_disp)/2)-(imagesx($thumbnail_gd_image)/2), (imagesy($img_disp)/2)-(imagesy($thumbnail_gd_image)/2), 0, 0, imagesx($thumbnail_gd_image), imagesy($thumbnail_gd_image));

        imagejpeg($img_disp, $thumbnail_image_path, 90);
        imagedestroy($source_gd_image);
        imagedestroy($thumbnail_gd_image);
        imagedestroy($img_disp);
        return true;
    }
}