<?php
namespace App\Classes;

use Illuminate\Http\Request;
use App\Http\Utils\JWT;

class LoginInfo {
	public function get() {
        $JWTObject = JWT::get_instance();
        if (isset($_COOKIE['Authorization'])) {
                $token = $_COOKIE['Authorization'];
                $decode_res = $JWTObject->decode_tkn($token,config('constant.JWT_SECRET_A_KEY'));
                $user_id = json_decode($decode_res['uid']);
        }else{
                $token = '';
                $user_id = '';
        }
        

        return $user_id;
	}
}
