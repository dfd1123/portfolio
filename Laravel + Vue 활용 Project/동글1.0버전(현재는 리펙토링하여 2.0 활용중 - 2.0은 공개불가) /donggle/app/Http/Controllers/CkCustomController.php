<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facades\App\Classes\File_store;

class CkCustomController extends Controller
{
    public function image_upload(Request $request)
    {
        $images = File_store::CK_Image_store('CKEDITOR', $request->images);


        return response(json_encode($images), 200)->header('Content-Type', 'application/json');
    }
}
