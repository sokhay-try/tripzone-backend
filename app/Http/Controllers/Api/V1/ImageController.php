<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Image;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Image\ImageResource;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\CustomPaginator;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends BaseController
{
    public function index(Request $request) {
        $perPage = $request->query('per_page');
        $image = new CustomPaginator(Image::with('place')->paginate($perPage));
        return $this->sendResponse($image, 'Image retrieved successfully!');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'place_id' => 'required',
            'images' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $images = $request->file('images');
        $imageUploaded = array();

        foreach ($images as $key => $image)  {
            $fullFileName = $this->getFileName($image);

            if ($this->checkFileExists($fullFileName)) {
                $fullFileName = $this->getFileName($image);
            }

            $image->move(public_path('images'), $fullFileName);
            $imgUrl = $this->getImageUrl($fullFileName);

            $imageCreated = Image::create([
                'place_id' => $request->place_id,
                'url' => $imgUrl,
            ]);
            $imageCreated->place;
            array_push($imageUploaded, new ImageResource($imageCreated));
        }

        return $this->sendResponse($imageUploaded, 'Images upload successfully');
    }

    public function updateImage(Request $request, $id)
    {

        $validator = Validator::make($request->all(),
        [
            'images' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $image = Image::findOrFail($id);
        $fileName = $this->extractOnlyFileName($image->url);

        if ($this->checkFileExists($fileName)) {
            // delete imagee in public folder
            \File::delete(public_path('images/'.$fileName));
        }

        // upload new image to public folder and update in db
        $fullFileName = $this->getFileName($request->images);

        $request->images->move(public_path('images'), $fullFileName);
        $imgUrl = $this->getImageUrl($fullFileName);

        $image->url = $imgUrl;
        $image->place;
        $image->save();

        return $this->sendResponse(new ImageResource($image), 'Image updated successfully.');
    }

    public function destroy(Image $image)
    {
        $image->delete();

        return $this->sendResponse([], 'Image deleted successfully.');
    }

    public function getImageUrl($fileName)
    {
        return env('APP_URL'). '/images/' . $fileName;
    }

    public function checkFileExists($fileName)
    {
        // Get the path to the public folder
        $path = public_path();

        // Check if the file exists
        return File::exists($path . '/images/' . $fileName);
    }

    public function getFileName($image)
    {
        $uuid = Uuid::uuid4();
        $fileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $extendtion = '.'.$image->getClientOriginalExtension();
        $filename = $uuid->toString()."-".$fileName."-".time().$extendtion;

        return $filename;
    }

    public function extractOnlyFileName($url)
    {
        $pathinfo = pathinfo($url);
        return $pathinfo['filename'].'.'.$pathinfo['extension'];
    }

}
