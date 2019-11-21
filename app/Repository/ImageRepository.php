<?php
/**
 * Created by PhpStorm.
 * User: mento
 * Date: 07.08.2019
 * Time: 11:28 PM
 */

namespace App\Repository;


use App\ResizedImage;

class ImageRepository
{
    protected $imageModel;

    public function __construct(ResizedImage $imageModel)
    {
        $this->imageModel = $imageModel;
    }

    public function saveImage($image)
    {
        return $this->imageModel->create($image);
    }

    public function getImageById($id)
    {
        return $this->imageModel->findOrFail($id);
    }

    public function getImageByUrl($url)
    {
        return $this->imageModel->where('image_url', $url)->first();
    }

    public function getImageByRandomUrl($url)
    {
        return $this->imageModel->where('random_generated_path', $url)->firstOrFail();
    }

    public function getAllImages()
    {
        return $this->imageModel->orderBy('id', 'DESC')->get();
    }

    public function deleteImage($id)
    {
        $image = $this->imageModel->where('id', $id);
        if ($image != null)
            return $image->delete();

    }

    public function deleteImageByUrl($url, $width, $height)
    {
        $image = $this->imageModel->where('image_url', $url)->where('max_width', $width)->where('max_height', $height);

        if ($image != null)
            $image->delete();

    }

    public function deleteImages($url)
    {
        $image = $this->imageModel->where('image_url', trim($url));
        if ($image != null)
            $image->delete();
    }

    public function getSpecificImage($image_url, $max_width, $max_height)
    {
        return $this->imageModel->where('image_url', $image_url)->where('max_width', $max_width)->where('max_height', $max_height)->first();
    }

    public function refreshImage($resized_image)
    {
        return $this->update($resized_image);
    }

    public function update($resized_image)
    {
        $db_image = $this->getImageById($resized_image['id']);
        $db_image->update(
            ['image_url' => $resized_image['image_url'],
                'max_width' => $resized_image['max_width'],
                'max_height' => $resized_image['max_height'],
//                'user_id' => $ResizedImage['user_id'],
                'image_type' => $resized_image['image_type'],
                'random_generated_path' => $resized_image['random_generated_path'],
                'image_content' => $resized_image['image_content']]);

        return $db_image->get();
    }

}