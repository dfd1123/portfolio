<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Secure;
use DB;
use Auth;

class RegisterAjaxController extends Controller
{
    public function email_duplicate(Request $request){
        $email = $request->email;

        $Is_duplicate = Secure::email_Isdupicate($email);


        $response = array(
            "yn" => $Is_duplicate,
        );

        return response()->json($response); 

    }

    public function mobile_verify(Request $request){
        $mobile_number = $request->mobile_number;
        $country = $request->country;

        $sms_success = Secure::sms_verify_code($mobile_number, $country);

        $response = array(
            "yn" => $sms_success,
        );

        return response()->json($response); 
    }

    public function mobile_verify_confirm(Request $request){
        $certify_code = $request->certify_code;
        $mobile_number = $request->mobile_number;
        $country = $request->country;

        $sms_success = Secure::sms_certify($certify_code);

        if($sms_success){
            DB::table('users')->where('id',Auth::id())->update([
                "mobile_number" => $mobile_number,
                "country" => $country,
            ]);
        }

        $response = array(
            "yn" => $sms_success,
        );

        return response()->json($response); 
    }

    public function username_duplicate(Request $request){
        $username = $request->username;

        $Is_duplicate = Secure::username_Isdupicate($username);


        $response = array(
            "yn" => $Is_duplicate,
        );

        return response()->json($response); 
    }
}
