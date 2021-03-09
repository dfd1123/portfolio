<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facades\App\Classes\Coolsms;

class SmsController extends Controller
{
    public function index(Request $request){
        if(!$request->filled('mobile_number', 'txt')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $mobile_number = $request->mobile_number;
        $txt = $request->txt;

        $sms = Coolsms::send_sms($mobile_number, $txt);


		
		if($sms['status'] == 'ok'){
            $this->res['query'] = true;
            $this->res['msg'] = "성공!";
            $this->res['state'] = config('res_code.OK');
        }else{
            $this->res['query'] = false;
            $this->res['msg'] = "실패!";
            $this->res['state'] = config('res_code.OK');
        }

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
