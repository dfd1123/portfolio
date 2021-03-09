<?php

namespace App\Http\Middleware;

use Closure;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use App\Http\Utils\JWT;
use App\Http\Utils\Encryptor;

class VerifyJwtToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $JWTObject = JWT::get_instance();

        if (isset($_COOKIE['Authorization'])) {
            $token = $_COOKIE['Authorization'];
        }else{
            $token = '';
        }

        if(isset($_COOKIE['Refresh'])){
            $r_token = $_COOKIE['Refresh'];
        }else{
            $r_token = '';
        }

        if (empty($token) && empty($r_token)) {
            return redirect('/login')->with('jsAlert', '로그인이 필요한 서비스입니다.');
        }

        $JWTRes= $JWTObject->decode_tkn($token,config('constant.JWT_SECRET_A_KEY'));
        $r_JWTRes= $JWTObject->decode_tkn($r_token,config('constant.JWT_SECRET_R_KEY'));

        if ($r_JWTRes['uid'] !== null && $JWTRes['uid'] === null) {
            $rtkn = $JWTObject->refresh_tkn_middleware($r_token,$r_JWTRes['uid']);
            setcookie('Authorization', urlencode($rtkn), time() + 900, '/');
            return redirect()->refresh();
        }else if ($JWTRes['uid'] === null) {
            return redirect('/login')->with('jsAlert', '로그인이 필요한 서비스입니다.');
        }

        return $next($request);
    }
}
