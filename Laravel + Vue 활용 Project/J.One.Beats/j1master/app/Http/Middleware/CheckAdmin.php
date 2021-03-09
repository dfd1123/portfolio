<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Utils\JWT;
use Illuminate\Http\Request;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
         //관리자는 여기서 항상 권한 체크.
         $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http"). " - {$_SERVER['REQUEST_URI']}";

         $this->logger($actual_link, 'admin', $_SERVER["REQUEST_METHOD"]);
 
         $JWTObject = JWT::get_instance();
 

         $token = $request->cookie('Authorization');
       
         if ( $request->header('Authorization') ) {
             $token = $request->header('Authorization');
         }

         if ( empty($token)){
            $this->logger($actual_link, 'admin', 'NO_AUTH' .$_SERVER["REQUEST_METHOD"]);
             return redirect('/');
         }
 
         $JWTRes= $JWTObject->decode_tkn($token);
 
         if($JWTRes ===null){
            //Log::channel('single')->info('ADMIN token null');
            $this->logger($actual_link, 'admin', 'NO_AUTH' .$_SERVER["REQUEST_METHOD"]);
             return redirect('/');
         }


/*
         if( $JWTRes['uid'] === -10 ){
            return redirect('/');
         }*/


         if(  $JWTRes->getClaim('uid') !== -10){
            //Log::channel('single')->info('ADMIN token null');
            $this->logger($actual_link, 'admin', 'INVALID JWT' .$_SERVER["REQUEST_METHOD"]);
             return redirect('/');

         }

         

        return $next($request);
    }
    protected function logger($url, $user, $method)
    {
        $urlinfo =  'Requested URL : '.$url;
        $userinfo =  'Called FROM '.$user;

        $info =  PHP_EOL.$urlinfo.PHP_EOL.$userinfo.PHP_EOL.$method;

        Log::channel('single')->info('ADMIN REQUEST  URL : '.$info);
    }
}
