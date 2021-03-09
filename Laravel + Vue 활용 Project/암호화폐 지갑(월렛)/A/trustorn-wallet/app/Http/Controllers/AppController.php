<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Facades\App\Classes\NiceCheck;
use Facades\App\Classes\Secure;
use Facades\App\Mail\Notify;

use DB;
use Auth;
use File;

class AppController extends Controller
{
    public function app()
    {
        $lang = str_replace('_', '-', app()->getLocale());
        $files = File::files(base_path()."/resources/lang/$lang/");
        $lang_files = [
            "LANG" => $lang
        ];
        foreach ($files as $file) {
            $lang_file = pathinfo($file, PATHINFO_FILENAME);
            $lang_files[$lang_file] = trans($lang_file);
        }

        $enc_data = NiceCheck::NiceCheck_main()['enc_data'];

        $view = view('app');
        $view->lang = $lang;
        $view->lang_data = base64_encode(json_encode($lang_files));
        $view->enc_data = $enc_data;

        return $view;
    }

    public function checkplus_success(Request $request)
    {
        $nice_info = NiceCheck::NiceCheck_success();
        if (is_array($nice_info)) {
            $message = $nice_info['returnMsg'];
            $name = $nice_info['name'];
            $mobile_no = $nice_info['mobileno'];
            $status = 1;
        } else {
            $message = $nice_info;
            $name = '';
            $mobile_no = '';
            $status = 0;
        }

        $views = view('nicecheck_return');

        $views->message = $message;
        $views->name = $name;
        $views->mobile_no = $mobile_no;
        $views->status = $status;

        return $views;
    }

    public function checkplus_fail(Request $request)
    {
        $nice_info = NiceCheck::NiceCheck_fail();

        $status = 0;
        $message = $nice_info;

        $views = view('nicecheck_return');

        $views->message = json_encode($message);
        $views->status = $status;

        return $views;
    }

    public function sms_verify_request(Request $request)
    {
        $duplicate = DB::table('users')->where('mobile_number', $request->mobile_number)->count();
        if ($duplicate > 0) {
            return response()->json(['error' => 'already_exists'], 422);
        }

        if (Secure::sms_verify_code($request->mobile_number, $request->country) != false) {
            return response()->json(true);
        } else {
            return response()->json(false, 422);
        }
    }

    public function sms_verify_certify(Request $request)
    {
        if (Secure::sms_certify_code($request->mobile_number, $request->verify_code)) {
            return response()->json(true);
        } else {
            return response()->json(false, 422);
        }
    }

    public function email_verify_request(Request $request)
    {
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['error' => 'invalid_format'], 422);
        }

        $user = DB::table('users')->where('email', $request->email)->first();
        if ($user != null) {
            return response()->json(['error' => 'already_exists'], 422);
        }

        Secure::email_verify_code($request->email, $request->country);
        return response()->json(true);
    }

    public function email_verify_certify(Request $request)
    {
        $result = Secure::email_certify_code($request->email, $request->verify_code);
        if ($result == 'certify_ok') {
            return response()->json(null);
        } elseif ($result == 'certify_fail') {
            return response()->json([
                'error' => 'invalid_code'
            ], 422);
        } else {
            return response()->json([
                'error' => 'certify_not_exists'
            ], 422);
        }
    }

    public function company_info(){
        $company = DB::table('btc_settings')->first();
        return response()->json($company);
    }

    public function fSearchCountryCode() {
        $key = "2019121312500004057510";
        $ip = $_SERVER['REMOTE_ADDR'];
        $dataFormat = "json";
        $url ="http://whois.kisa.or.kr/openapi/ipascc.jsp?query=".$ip."&key=".$key."&answer=".$dataFormat."";
    
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url); curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_NOSIGNAL, 1);
        //curl_setopt($ch,CURLOPT_POST, 1); //Method를 POST로 지정.. 이 라인이 아예 없으면 GET
    
        $data = curl_exec($ch);
        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
    
        curl_close($ch);
        $decodeJsonData = json_decode($data, true);
        return $decodeJsonData['whois']['countryCode'];
    }
}
