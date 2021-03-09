<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Facades\App\Classes\NiceCheck;
use Facades\App\Classes\Secure;
use Facades\App\Mail\Notify;

use DB;
use Auth;
use File;
use URL;
use Input;

class AppController extends Controller
{
    public function index()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                // api 라우트에 없는 ajax 요청일 경우
                return response()->json(null, 404);
            }
        }

        /* Lang */
        $lang = str_replace('_', '-', app()->getLocale());
        $files = File::files(base_path()."/resources/lang/$lang/");
        $lang_files = [
            "LANG" => $lang
        ];
        foreach ($files as $file) {
            $lang_file = pathinfo($file, PATHINFO_FILENAME);
            $lang_files[$lang_file] = trans($lang_file);
        }

        /* Base url */
        $base_url = url('/');

        $view = view('app');
        $view->lang = $lang;
        $view->lang_data = base64_encode(json_encode($lang_files));
        $view->base_url = $base_url;

        return $view;
    }

    public function email_verify(Request $request)
    {
        $verify_code = Input::get('verify');
        if (!$verify_code) {
            return;
        }

        if (Secure::email_certify_link($verify_code) !== true) {
            return abort(403, '이미 인증이 완료되었거나 만료된 메일입니다.');
        }

        return redirect('/login');
    }

    public function mobile_auth_verify(Request $request)
    {
        // 인증 시작
        info('mobile_auth_verify 시작');

        // 인증결과 로그 찍기
        info($request);

        if($request->code != 0) {
            if($request->code == 301) {
                return abort(503, "해당 통신사 실명인증 시스템 오류입니다. 다른 통신사 휴대폰으로 시도해주시기 바랍니다.");
            }
            return abort(500);
        }

        $tid = $request->tid;
        $client_id = 'j1beatz1';
        $auth_info = $request->auth_info;
        $data = json_decode(base64_decode($request->custom_parameter));
        $verify_code = $data->verify_code;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://pgapi.payletter.com/v1.0/auth/info/$tid?client_id=$client_id&auth_info=$auth_info");
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array('Authorization: PLKEY '.config('p1q.AUTH_KEY_READ')
            , 'Content-Type:application/json')
        );

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);

        info($result);

        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);

        DB::table('mobile_verify_temp')->where('verify_code', $verify_code)->update([
            'verify_info' => $result
        ]);

        // 인증 완료
        info('mobile_auth_verify 완료');

        return redirect('/mobile-auth-popup');
    }

    public function abort()
    {
        return abort(404);
    }
}
