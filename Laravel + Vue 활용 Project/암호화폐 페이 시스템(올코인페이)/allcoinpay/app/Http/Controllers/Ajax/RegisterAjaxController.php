<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Auth;

use Facades\App\Classes\Secure;
use Facades\App\Classes\NiceCheck;


class RegisterAjaxController extends Controller
{
    //입출금 자산 새로고침
    public function email_duplicate(Request $request){
        $email = $request->email;

        $Is_duplicate = Secure::email_Isdupicate($email);


        $response = array(
            "yn" => $Is_duplicate,
        );

        return response()->json($response); 
        
    }
    
	public function email_verify_request(Request $request){
		if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
			info($request->email);
			return response()->json(['error' => 'invalid_format']);
		} 
		
		$user = DB::table('users')->where('email', $request->email)->first();
		if($user != null) {
			return response()->json(['error' => 'already_exists']);
		}
		
		Secure::email_verify_code($request->email);
		return response()->json(true);
	}

	public function email_verify_certify(Request $request){
		if(Secure::email_certify_code( $request->verify_code)) {
			return response()->json(true);
		} else {
			return response()->json(false);
		}
    }
    
    public function mobile_verify_nicecheck(Request $request){
        $nice_info = NiceCheck::NiceCheck_main();

        return response()->json(['enc_data' => $nice_info['enc_data']]);
    }

    public function checkplus_success(Request $request){
		$nice_info = NiceCheck::NiceCheck_success();
		if(is_array($nice_info)){
			$message = $nice_info['returnMsg'];
			$mobile_number = $nice_info['mobileno'];
			$name = $nice_info['name'];
			$name_utf8 = rawurldecode($nice_info['name_utf8']);
			$status = 1;
		}else{
			$message = $nice_info;
			$mobile_number = '';
			$name = '';
			$name_utf8 = '';
			$status = 0;
		}
		$views = view('auth.nicecheck_return');
		
		$views->message = $message;
		$views->name = $name;
		$views->name_utf8 = $name_utf8;
		$views->mobile_number = $mobile_number;
		$views->status = $status;
		
		return $views;
	}
	
	public function checkplus_fail(Request $request){
		$nice_info = NiceCheck::NiceCheck_fail();
		
		$status = 0;
		$message = $nice_info;	
		
		$views = view('auth.nicecheck_return');
		
		$views->message = $message;
        $views->name = '';
        $views->name_utf8 = '';
		$views->mobile_number = '';
		$views->status = $status;
		
		return $views;
    }
}
