<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;

class PushController extends Controller
{
    public function push_order_result(Request $request)
    {
        $result = $request->result;
        $username = $request->username;
        $type = $request->type;
        $coin = $request->coin;
        $qty = $request->qty;
        $value = $request->value;

        $user = DB::table('users')->select('push_token', 'country')->where('username', $username)->whereNotNull('push_token')->limit(1)->first();

        if($user == null){
            return response()->json(null, 422);
        }

        $country = $user->country;
        $push_title = '';
        $push_alarm = '';

        if($result == 'success') {
            if($type == 'buy') {
                if($country == 'kr'){
                    $push_title = "코인구매";
                    $push_alarm = "구매성공: ".$value."KRW"." -> ".$qty.strtoupper($coin);
                } else if($country == 'jp') {
                    $push_title = "コイン購入";
                    $push_alarm = "購入成功: ".$value."KRW"." -> ".$qty.strtoupper($coin);
                }
            } else if($type == 'sell') {
                if($country == 'kr'){
                    $push_title = "코인판매";
                    $push_alarm = "판매성공: ".$qty.strtoupper($coin)." -> ". $value."KRW";
                } else if($country == 'jp') {
                    $push_title = "コイン販売";
                    $push_alarm = "販売成功: ".$qty.strtoupper($coin)." -> ". $value."KRW";
                }
            }
        } else if($result == 'fail') {
            if($country == 'kr'){
                $push_title = "요청실패";
                $push_alarm = "접수된 구매/판매 요청이 실패하였습니다.";
            } else if($country == 'jp') {
                $push_title = "要求失敗";
                $push_alarm = "受理された購入/販売要求が失敗しました。";
            }
        }

        if($push_title == '' || $push_alarm == '') {
            return response()->json(null, 500);
        }

        $tokens = [$user->push_token];
        $inputData = array("title" => $push_title, "body" => $push_alarm);
        $this->send_notification($tokens, $inputData);
    }
    //얘는 키 추가해야됨
    private function send_notification($tokens, $data)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        //어떤 형태의 data/notification payload를 사용할것인지에 따라 폰에서 알림의 방식이 달라 질 수 있다.
        $msg = array(
            'title'   => $data["title"],
            'body'    => $data["body"],
            'sound' => 'default'
            );

        //data payload로 보내서 앱이 백그라운드이든 포그라운드이든 무조건 알림이 떠도록 하자.
        $fields = array(
            'registration_ids' => $tokens,
            'data'   => $data,
            'notification' => $msg
            );

        // 헤더에 푸시 키 추가
        $headers = array(
            'Authorization:key ='.env('PUSH_ACCESS_KEY'),
            'Content-Type: application/json'
            );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);

        if ($result === FALSE) {
            return false;
        }

        return true;
    }
}
