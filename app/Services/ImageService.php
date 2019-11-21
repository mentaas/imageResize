<?php
/**
 * Created by PhpStorm.
 * User: mento
 * Date: 07.08.2019
 * Time: 7:06 PM
 */

namespace App\Services;

use App\Repository\ImageRepository;
use App\ResizedImage;
use Illuminate\Http\Request;
use Illuminate\Database\Connectors\Connector;
use Illuminate\Database\Schema\Grammars\ChangeColumn;

class ImageService
{

    protected $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function getAllImage()
    {
        $images = $this->imageRepository->getAllImages();
        foreach ($images as $image){
            $image['random_generated_path'] = env('APP_URL', 'test.test') . "/image/" . $image['random_generated_path'];
        }
        return $images;
    }

    public function getImageById($id)
    {
        $image = $this->imageRepository->getImageById($id);

        $image['random_generated_path'] = "/image/" . $image['random_generated_path'];

        return $image;
    }

    public function getImageByRandomUrl($url)
    {
        return $this->imageRepository->getImageByRandomUrl($url);
    }

    public function removeImage($id)
    {
        $id = urldecode($id);
        $this->imageRepository->deleteImage($id);
    }

    public function removeImageByUrl($url, $width, $height){
        $this->imageRepository->deleteImageByUrl($url, $width, $height);
    }

    public function removeImages($url){
        $this->imageRepository->deleteImages($url);
    }

    public function getImageContent($imgUrl, $dst_width, $dst_height)
    {
//        $url = $imgUrl;
//        $allow = ['gif', 'jpg', 'jpeg', 'png'];
//        $img = file_get_contents($url);
//
//        $url_info = pathinfo($url);
        $image_array = getimagesize($imgUrl);

        $width = $image_array[0];
        $height = $image_array[1];
        $img_type = $image_array[2];
        $image_mime = $image_array['mime'];
        //list($width, $height, $img_type) = getimagesize($imgUrl);


        if (in_array($img_type, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP))) {
            $resize_image = $this->resImage($imgUrl, $dst_width, $dst_height, $width, $height, $image_mime);
            $arr = array();
            $arr['image'] = $resize_image;
            $arr['extension'] = $image_mime;
            return $arr;
        } else
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(400, "Error Bacooo");

    }

    public function saveImageResize($resized_image)
    {
        return $this->checkIfImageIsStoreInDB($resized_image);
    }

    public function resImage($image, $width, $height, $w, $h, $format)
    {
        if (in_array($format, array('image/jpg', 'image/jpeg', 'image/png', 'image/gif'))) {
            $dst_image_size = $this->getImageProportionSize($w, $h, $width, $height);

            if ($format == 'image/jpg' || $format == 'image/jpeg') {
                $image = imagecreatefromjpeg($image);
            } elseif ($format == 'image/png') {
                $image = imagecreatefrompng($image);
            } elseif ($format == 'image/gif') {
                $image = imagecreatefromgif($image);
            }

            $image_p = imagecreatetruecolor($dst_image_size['width'], $dst_image_size['height']);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $dst_image_size['width'], $dst_image_size['height'], $w, $h);

            ob_start();

            if ($format == 'image/jpg' || $format == 'image/jpeg') {
                imagejpeg($image_p);
            } elseif ($format == 'image/png') {
                imagepng($image_p);
            } elseif ($format == 'image/gif') {
                imagegif($image_p);
            }
            $data = ob_get_contents();
            ob_end_clean();

            if (!empty($data)) {
                $data = base64_encode($data);
                if ($data !== false) {
                    return $data;
                }
            }
        }
        imagedestroy($image_p);
        imagedestroy($image);

        return false;
    }

    public function getImageProportionSize($width, $height, $dst_width, $dst_height)
    {
        $arr = array();
        if ($width > $height) {
            $arr['width'] = $dst_width;

            $ratio_orig = $width / $height;
            $arr['height'] = $dst_width / $ratio_orig;
        } else {
            $arr['height'] = $dst_height;

            $ratio_orig = $height / $width;
            $arr['width'] = $dst_height / $ratio_orig;
        }
        return $arr;
    }

    public function checkIfImageIsStoreInDB($request_image)
    {
        $required_time = time();
        $db_image = $this->imageRepository->getSpecificImage($request_image['image_url'], $request_image['max_width'], $request_image['max_height']);

        if ($db_image) {
            if (strtotime($db_image['updated_at']) + 86400 > $required_time) { //86400sec == 24h  //return cached resize image
                $db_image['random_generated_path'] = env('APP_URL', 'test.test') . "/image/" . $db_image['random_generated_path'];
                return $db_image;
            } else { // refresh resized image if has gone more than 24h
                try {
                    $request_image['id'] = $db_image['id'];
                    return $this->saveResizedImageToDB($request_image, true);
                } catch (\Exception $ex) {
                    return $db_image;
                }

            }
        } else {
            //save image if it fails check if image exist in db than resize image from db
            return $this->saveResizedImageToDB($request_image, false);
        }
    }

    public function saveResizedImageToDB($request_image, bool $isUpdate)
    {
        if (isset($request_image['image_content']))
            $resized_img = $this->getImageContent($request_image['image_content'], $request_image['max_width'], $request_image['max_height']);
        else
            $resized_img = $this->getImageContent($request_image['image_url'], $request_image['max_width'], $request_image['max_height']);

        $request_image['image_content'] = $resized_img['image'];
        $request_image['image_type'] = $resized_img['extension'];
        $request_image['random_generated_path'] = "auto_" . time();

        if ($isUpdate)
            $image = $this->imageRepository->refreshImage($request_image);
        else
            $image = $this->imageRepository->saveImage($request_image);

        $image['random_generated_path'] = env('APP_URL', 'test.test') . "/image/" . $image['random_generated_path'];

        return $image;
    }

    public function resizeImageFromResizedImage($request_image)
    {
        $db_image = $this->imageRepository->getImageByUrl($request_image['image_url']);
        $request_image['image_content'] = "data:" . $db_image['image_type'] . ";base64," . $db_image['image_content'];
        return $this->saveResizedImageToDB($request_image, false);
    }

    public function checkIfUrlImageIsStoreInDB($image_url)
    {
        $db_url = $this->imageRepository->getImageByUrl($image_url);
        if ($db_url)
            return true;

        return false;
    }
}