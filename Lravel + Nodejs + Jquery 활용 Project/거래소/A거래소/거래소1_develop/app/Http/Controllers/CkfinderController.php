<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facades\App\Classes\File_store;

class CkfinderController extends Controller
{
    public function image_upload(Request $request){

        $files = File_store::Image_store('comunity', $request->files);

        if(gettype($files) == "array"){
            $response = array(
                "uploaded" => true,
                "url" => config("app.url").'/storage/image/comunity'.$files[0],
            );

            return response()->json($response);
        }else if(gettype($files) == "string"){
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
