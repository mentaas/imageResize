<?php

namespace App\Http\Controllers;

use App\Dtos\Dto;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ResizeImageController extends Controller
{
    //
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        return $this->imageService->getAllImage();
    }

    public function show($id)
    {
        return $this->imageService->getImageById($id);
    }

    public function store(Request $request)
    {
        try {

            $resizeImage = $this->imageService->saveImageResize($request->all());
            return response()->json($resizeImage, 201);

        } catch (HttpException $ex) {

            return response()->json("Please provide an image URL", 400);

        } catch (\Exception $ex) {

            $check = $this->imageService->checkIfUrlImageIsStoreInDB($request['image_url']);
            if ($check) {
                $image = $this->imageService->resizeImageFromResizedImage($request->all());
                return response()->json($image, 201);
            }

            //echo "Error : \n" . $ex->getMessage();
            return response()->json($ex->getMessage() ."\nAn error occurred, please check your internet connection or provided url", 400);
        }
    }

    public function delete($id)
    {
        try {
            $this->imageService->removeImage($id);
            return response()->json(null, 204);
        } catch (\Exception $ex) {
            return response()->json($ex->getMessage(), 400);
        }

    }

    public function deleteUrl($url, $width, $height){
        try {
            $this->imageService->removeImageByUrl($url, $width, $height);
            return response()->json(null, 204);
        } catch (\Exception $ex) {
            return response()->json($ex->getMessage(), 400);
        }
    }

    public function deleteImages($url){
        try {
            $this->imageService->removeImages($url);
            return response()->json(null, 204);
        } catch (\Exception $ex) {
            return response()->json($ex->getMessage(), 400);
        }
    }

    public function showImage($url){
        return $this->imageService->getImageByRandomUrl($url);
    }
}
