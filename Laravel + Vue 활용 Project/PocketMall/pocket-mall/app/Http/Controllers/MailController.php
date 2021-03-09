<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;

class MailController extends Controller
{
    public function index(Request $request){

        if(!$request->filled('items', 'req_email', 'req_name', 'device')){
            $this->res['query'] = null;
            $this->res['msg'] = "필수 정보 부족!";
            $this->res['state'] = config('res_code.PARAM_ERR');
            return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
        }

        $to = $request->req_email;
        $subject = '[포켓컴퍼니] '.$request->req_name.'님이 요청하신 견적서가 메일로 도착했습니다!';
        $data = [
            'data' => $request,
            'items' => $request->items
        ];

        if($request->device === 'pc'){
            Mail::send('mail.mail', $data, function($message) use($to, $subject) {
                $message->to($to)->subject($subject);
            });
        }else{
            Mail::send('mail.mobile_mail', $data, function($message) use($to, $subject) {
                $message->to($to)->subject($subject);
            });
        }

        $this->res['query'] = true;
        $this->res['msg'] = "성공";
        $this->res['state'] = config('res_code.OK');

        return response(json_encode($this->res), 200)->header('Content-Type', 'application/json');
    }
}
