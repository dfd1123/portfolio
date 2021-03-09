<?php

namespace App\Http\Utils;

class Nexmo
{
    public static function send_code($mobile_number)
    {

        $res = array('state'=>false, 'msg'=>'');

        if (preg_match('/^[0-9]{1,3}[0-9]{4,14}(?:x.+)?$/', $mobile_number)) {


        
            $res['msg'] = $rand_num = sprintf('%06d',rand(000000,999999));

            $data =array('from'=>'Tripick'
        ,'text'=>'트리픽 인증문자 입니다 CODE :'.$res['msg']
        //,'text'=>'TRIPCIK SMS Verfication CODE :'.$res['msg']
        ,'to' => $mobile_number
        ,'api_key'=> config('auth.nexmo_api_key')
        ,'api_secret' =>  config('auth.nexmo_secret_key'));


            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://rest.nexmo.com/sms/json?type=unicode");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_POST, true);
            $response = curl_exec($ch);
            curl_close($ch);
    
            $res['state']=true;
            return $res;
        } else {
            $res['state'] =false;
            $res['msg'] = '잘못된 연락처.';
            return $res;
        }
    }

    public static function send_message($mobile, $msg){
        $res = array('res'=>false, 'msg'=>'');

        if (preg_match('/^[0-9]{1,3}[0-9]{4,14}(?:x.+)?$/', $mobile_number)) {
        
            $res['code'] = $rand_num = sprintf('%06d',rand(000000,999999));
            $data =array('from'=>'Tripick'
        ,'text'=>'트리픽 : 인증코드 ['.$res['code'].'['
        ,'to' => $mobile_number
        ,'api_key'=> config('auth.nexmo_api_key')
        ,'api_secret' =>  config('auth.nexmo_secret_key'));

          
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://rest.nexmo.com/sms/json?type=unicode");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_POST, true);
            $response = curl_exec($ch);
            curl_close($ch);
    
    
            return true;
        } else {
            $res['res'] =false;
            $res['msg'] = '잘못된 연락처.';
            return false;
        }
    }
}
