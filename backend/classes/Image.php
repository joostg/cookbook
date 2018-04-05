<?php
namespace cookbook\backend\classes;
class Image
{
    protected $baseUrl;
    protected $ci;
    protected $db;
    protected $slugify;

    private $uploadPath;
    private $imageFile;
    private $errorMessage;
    private $extension;

    private $pathThumb = '';
    private $pathRecipePage = '';

    public function __construct(\Slim\Container $ci)
    {
        $this->ci = $ci;
        $this->db = $this->ci->get('db');
        $this->slugify = $this->ci->get('slugify');
        $this->baseUrl = $this->ci->get('settings')->get('base_url');
        $this->uploadPath = $this->ci->get('settings')->get('pictures_path');
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
            // tinyfy
            \Tinify\setKey($this->ci->get('settings')['tinypng']['apikey']);

            $source = \Tinify\fromFile($path);

            $resized = $source->resize(array(
                "method" => "cover",
                "width" => 256,
                "height" => 192
            ));
            $resized->toFile($this->uploadPath . '/' . $this->pathThumb);

            $resized = $source->resize(array(
                "method" => "cover",
                "width" => 730,
                "height" => 548
            ));
            $resized->toFile($this->uploadPath . '/' . $this->pathRecipePage);

            return true;
        }
    }

    private function generateUniquePath()
    {
        $filenameHash = md5(uniqid($this->imageFile['image']["name"], true));

        $this->pathRecipePage = $filenameHash . '.' . $this->extension;;
        $this->pathThumb = $filenameHash . '_thumb.' . $this->extension;;

        $path = $this->uploadPath . $this->pathRecipePage;

        // check if file doesn't exist yet
        $incr = 0;
        while(file_exists($path)){
            $this->pathThumb = $filenameHash . '_' . $incr . '_thumb.' . $this->extension;
            $this->pathRecipePage  = $filenameHash . '_' . $incr . '.' . $this->extension;

            $path = $this->uploadPath . '/' . $this->pathRecipePage;
            $incr++;
        }

        return $path;
    }

    private function saveToDb($title)
    {
        $insert = $this->db->prepare(
            "INSERT INTO images (
                `path_thumb`,
                `path_recipe_page`,
                `extension`,
                `title`,
                `created`,
                `creator`,
                `modified`,
                `modifier`
            ) VALUES (
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

    // @deprecated since we use tiny png
    function resize_image($file, $save_path, $box_w, $box_h, $crop=FALSE) {
        list($origwidth, $origheight, $source_image_type) = getimagesize($file);
        switch ($source_image_type) {
            case IMAGETYPE_GIF:
                $src = imagecreatefromgif($file);
                break;
            case IMAGETYPE_JPEG:
                $src = imagecreatefromjpeg($file);
                break;
            case IMAGETYPE_PNG:
                $src = imagecreatefrompng($file);
                break;
        }
        if ($src === false) {
            return false;
        }

        $new = imagecreatetruecolor($box_w, $box_h);
        if($new === false) {
            //creation failed -- probably not enough memory
            return null;
        }

        //Fill the image with a light grey color
        //(this will be visible in the padding around the image,
        //if the aspect ratios of the image and the thumbnail do not match)
        //Replace this with any color you want, or comment it out for black.
        //I used grey for testing =)
        $fill = imagecolorallocate($new, 255, 255, 255);
        imagefill($new, 0, 0, $fill);

        //compute resize ratio
        $hratio = $box_h / imagesy($src);
        $wratio = $box_w / imagesx($src);
        $ratio = max($hratio, $wratio);

        //if the source is smaller than the thumbnail size,
        //don't resize -- add a margin instead
        //(that is, dont magnify images)
        if($ratio > 1.0)
            $ratio = 1.0;

        //compute sizes
        $sy = floor(imagesy($src) * $ratio);
        $sx = floor(imagesx($src) * $ratio);

        //compute margins
        //Using these margins centers the image in the thumbnail.
        //If you always want the image to the top left,
        //set both of these to 0
        $m_y = floor(($box_h - $sy) / 2);
        $m_x = floor(($box_w - $sx) / 2);

        imagecopyresampled($new, $src,
            $m_x, $m_y, //dest x, y (margins)
            0, 0, //src x, y (0,0 means top left)
            $sx, $sy,//dest w, h (resample to this size (computed above)
            imagesx($src), imagesy($src)); //src w, h (the full size of the original)

        imagejpeg($new, $save_path, 90);
        return true;
    }
}