<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facades\App\Classes\File_store;

use File;

class CkfinderController extends Controller
{
    public function image_upload(Request $request)
    {
        $storage_path = "image/";

        if(!File::exists($storage_path.'items')) {
            File::makeDirectory($storage_path.$store_path, $mode = 0775, true, true);
        }

        $files = File_store::Image_store('items', $request->files);

        if (gettype($files) == "array") {
            $response = array(
                "uploaded" => true,
                "url" => url("/public")."/".$files[0]
            );

            return response()->json($response);
        } elseif (gettype($files) == "string") {
            $error = array(
                "message" => $files,
            );

            $response = array(
                "uploaded" => false,
                "error" => $error,
            );

            return response()->json($response);
        }
    }
}
