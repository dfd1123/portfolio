<?php

namespace App\Classes;

use DB;
use Auth;

class Test2 {
    public static function today()
    { 
        return date('Y-m-d H:i:s');
    }
    public static function user_list(){
        if(Auth::check()){
            $users = DB::table('users')->get();

            return $users;
        }else{
            $users = array(
                "name" => "none"
            );
        }
        return $users;
    }
}
