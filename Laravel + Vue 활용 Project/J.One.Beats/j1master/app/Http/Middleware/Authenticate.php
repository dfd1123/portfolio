<?php

namespace App\Http\Middleware;
use App\Http\Utils\JWT;
use Illuminate\Http\Request;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }/*

    public function checkAdmin(Request $request){}
        
        $JWTObject = JWT::get_instance();

        $token = $request->cookie('Authorization');
        if ($request->headers->has('Authorization')) {
            $token = $request->header('Authorization');
        }

        if ( empty($token)){
            $res = array('state'=>100, 'query'=>null, 'msg'=>'no-auth');
            die( json_encode($res));
        }

        $JWTRes= $JWTObject->decode_tkn($token);

        if($JWTRes ===null){
            $res = array('state'=>100, 'query'=>null, 'msg'=>'no-auth');
            die( json_encode($res));
        }
    }
*/
}
