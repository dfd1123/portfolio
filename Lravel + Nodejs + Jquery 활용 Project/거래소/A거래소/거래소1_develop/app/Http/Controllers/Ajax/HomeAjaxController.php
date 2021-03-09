<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

class HomeAjaxController extends Controller
{
    public function youtube_list(Request $request){
        $youtube_id = $request->youtube_id;

        $youtube_info = DB::connection('mysql_sub')->table('youtube')->where('id',$youtube_id)->first();

        return response()->json($youtube_info); 
    }
}
