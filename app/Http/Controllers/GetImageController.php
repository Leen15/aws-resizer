<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Storage;
#use Intervention\Image\Facades\Image as Image;
use Intervention\Image\ImageManagerStatic as Image;

class GetImageController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request, $id)
    {

        $s3 = Storage::disk('s3');

        $size = $request->input('size');

        if ($size == null)
        {
            $size = "original";
        }
        else
        {
            $allowed_dimensions = explode(";", getenv("DIMENSIONS"));

            if (!in_array($size, $allowed_dimensions)) {
                $response = [
                    "error" => "This dimension is not allowed."
                ];
                return response()->json($response, 403);
            }
        }
        $filePath = '/' . $size . '/' . $id;

        $response = new \StdClass();
        $response->get_mode = "cache";

        if (! $s3->exists($filePath))
        {
            // check if the file exists in original dimension
            $originalPath = '/original/' . $id;
            if ($s3->exists($originalPath))
            {
                // ok, we can resize the
                // file to desidered dimension
                $filePath = $this->resizeRemoteFile($id, $size);
                $response->get_mode = "elaborated";
            }
            else
            {
                // file doesn't exists with original dimension, so return an error.
                $response = [
                    "error" => "This file does not exists."
                ];
                return response()->json($response, 403);
            }
        }

        $remoteUrl = str_replace("//", "/", $s3->getDriver()->getAdapter()->getClient()->getObjectUrl(env('AWS_BUCKET'), $filePath));


        $response->requested_path = $filePath;
        $response->remote_url = $remoteUrl;

        return response()->json($response);

    }

    private function resizeRemoteFile($id, $dimension)
    {

        $s3 = Storage::disk('s3');
        $original_file = $s3->get('/original/' . $id);


        Image::configure(array('driver' => 'imagick'));

        $editedImage = Image::make($original_file);
        $dimensions = explode("x", $dimension);
        $editedImage->fit($dimensions[0], $dimensions[1], function ($constraint) {
            $constraint->upsize();
        },"center");
        $editedImage->encode('jpg', 85);

        $resizedRemotePath = '/' . $dimension . '/' . $id;
        $s3->put($resizedRemotePath, $editedImage->__toString(), 'public');

        return $resizedRemotePath;
    }
}
