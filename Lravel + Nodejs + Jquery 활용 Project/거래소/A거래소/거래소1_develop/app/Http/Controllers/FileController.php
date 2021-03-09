<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use File;

class FileController extends Controller
{
    public function serve_storage($path)
    {
        try {
            $headers = [];
            $filepath = storage_path('app/public' . '/' . $path);
            $extension = File::extension($filepath);
            if($extension == 'svg') {
                $headers = [
                    'Content-Type' => 'image/svg+xml'
                ];
            }

            return response()->file($filepath, $headers);
        } catch (FileNotFoundException $e) {
            return abort(404);
        }
    }
}
