<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Auth;

class ApiAjaxController extends Controller
{
    //APIKEY 갱신
    public function refresh_apikey(Request $request){
        $id = $request->id;
        $api_info = DB::table('btc_apikey')->where('id',$id)->where('uid',Auth::user()->id)->first();

        $apikey = hash('sha256',Auth::user()->username.time()."private".$api_info->site_url);
        $public_apikey = hash('sha256',Auth::user()->username.time()."public".$api_info->site_url);
        
        $update = DB::table('btc_apikey')->where('id',$id)->where('uid',Auth::user()->id)->update([
            'use' => 0
        ]);

        $expiration_at = date("Y-m-d H:i:s", strtotime("+3 month",time()));

        $result = DB::table('btc_apikey')->insert([
            'uid' => Auth::user()->id,
            'username' => Auth::user()->username,
            'apikey' => $apikey,
            'public_apikey' => $public_apikey,
            'site_url' => $api_info->site_url,
            'expiration_at' => $expiration_at,
            'created_at' => DB::raw('now()'),
            'updated_at' => DB::raw('now()'),
        ]);

        if($update > 0 && $result > 0){
            $response = array(
                "status" => 'OK',
            );
        }else{
            $response = array(
                "status" => 'error',
            );
        }

        return response()->json($response); 
        
    }
    
	//APIKEY 삭제
    public function delete_apikey(Request $request){
        $id = $request->id;

        $delete = DB::table('btc_apikey')->where('id',$id)->where('uid',Auth::user()->id)->update([
            'use' => 0
        ]);
        if($delete > 0){
            $response = array(
                "status" => 'OK',
            );
        }else{
            $response = array(
                "status" => 'error',
            );
        }

        return response()->json($response); 
        
    }
}
