<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Secure;
use DB;
use Auth;

class SecurityAjaxController extends Controller
{
    public function existing_user_mobile_verify(Request $request){
        $mobile_number = $request->mobile_number;
        $country = $request->country;
        $duplicate_confirm = $request->duplicate_confirm;

        $sms_success = Secure::sms_verify_code($mobile_number, $country, $duplicate_confirm);

        $response = array(
            "yn" => $sms_success,
        );

        return response()->json($response); 
    }

    public function existing_user_mobile_verify_confirm(Request $request){
        $certify_code = $request->certify_code;

        $sms_success = Secure::sms_certify($certify_code);

        if($sms_success == true) {
            DB::table('btc_security_lv')->where('uid', Auth::user()->id)->update(['mobile_verified' => 1]);
        }

        $response = array(
            "yn" => $sms_success,
        );

        return response()->json($response); 
    }
}
