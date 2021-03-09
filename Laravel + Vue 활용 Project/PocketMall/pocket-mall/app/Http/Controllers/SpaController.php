<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use File;

class SpaController extends Controller
{

  public function index()
  {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
      if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        // api 라우트에 없는 ajax 요청일 경우
        return response()->json(null, 404);
      }
    }

    /* Lang */
    $lang = str_replace('_', '-', app()->getLocale());
    $files = File::files(base_path() . "/resources/lang/$lang/");
    $lang_files = [
      "LANG" => $lang
    ];
    foreach ($files as $file) {
      $lang_file = pathinfo($file, PATHINFO_FILENAME);
      $lang_files[$lang_file] = trans($lang_file);
    }

    /* Base url */
    $base_url = url('/');

    $view = view('app');
    $view->lang = $lang;
    $view->lang_data = base64_encode(json_encode($lang_files));
    $view->base_url = $base_url;

    return $view;
  }
}