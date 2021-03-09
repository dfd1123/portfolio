<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Utils\JWT;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function store(Request $request)
    {
        $p = $request->all();
        $token =null;
        
        if($request->filled('pwd') && $this->checkLength($p['pwd'], 15,15)){

            if($p['pwd'] ==='j1adminpwd0809!'){
                $JWTObject=  JWT::get_instance();

                $token = $JWTObject->iss_tkn();
                $token  = urlencode($token);  
                //API용 응답
                $this->res['query'] = $token;
                $this->res['state'] =1;
            }
        }else{
            $this->res['query']=null;
            $this->res['state'] =0;
        }

        return response(json_encode($this->res), 200)
        ->header('Content-Type', 'application/json')
        ->header('Authorization', $token)
        //브라우저용 응답
        ->cookie('Authorization', $token);
    }
}
