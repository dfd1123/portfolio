<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Visitor;

class LoginCheckController extends Controller
{
    public function index(){
        Visitor::renew();
        
        if(Auth::check()){

            $response = array(
                "login_yn" => 1,
            );

            return response()->json($response);
        }else{

            $response = array(
                "login_yn" => 0,
            );

            return response()->json($response);
        }
    }
}
