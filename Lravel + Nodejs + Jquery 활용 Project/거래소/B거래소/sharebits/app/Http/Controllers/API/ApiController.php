<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;

class ApiController extends Controller
{
    
    
    public function login(Request $request){
        $password = $request->input('password');
        $email = $request->input('email');
		
		$header = array (
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'utf-8'
        );
		$responsecode = 200;
        

        $oauth = DB::table('oauth_key_temp')->where('id',$request->id)->where('secret_key',$request->secret_key)->first();


        if($oauth != null){

            if(Auth::attempt(['email' => $email, 'password' => $password])){     
                $user = DB::table('users')->select('email','fullname as name')->where('email',$email)->first();
                $response = array(
                    "login_yn" => true,
                    "email" => $user->email,
                    "name" => $user->name,
                );
            }else{
                $response = array(
                    "login_yn" => false,
                    "email" => null,
                    "name" => null,
                );
            }

            return response()->json($response,$responsecode,$header,JSON_UNESCAPED_UNICODE);
        }
    }
}
