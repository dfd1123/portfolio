<?php
namespace App\Classes;

use Illuminate\Http\Request;
use Auth;
use DB;

class FcmPush
{
    public function push_one($tokens, $data)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        
        $msg = array(
            'title' => $data['title'],
            'body' => $data['body'],
            'sound' => 'default',
            'icon' => 'ic_launcher_alarm_round'
        );

        $field_data = array(
            'title' => $data['title'],
            'body' => $data['body'],
        );

        $fields = array(
            'registration_ids'	=> $tokens,
            'data'	=> $field_data,
            'notification' => $msg
        );
        
        // 헤더에 푸시 키 추가
        $headers = array(
            'Authorization:key='.env('FCM_KEY'),
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        
        //info($result);
        return $result;
    }

    public function push_topic($topic, $data)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        
        $msg = array(
            'title' => $data['title'],
            'body' => $data['body'],
            'sound' => 'default',
            'click_action' => 'OPEN_TOPIC',
            'icon' => 'ic_launcher_alarm_round'
        );

        $field_data = array(
            'title' => $data['title'],
            'body' => $data['body'],
            'id' => $data['topic_id'],
            'topic' => $topic
        );
        
        $fields = array(
            'to' => $topic,
            'data' => $field_data,
            'notification' => $msg
        );
        
        // 헤더에 푸시 키 추가
        $headers = array(
            'Authorization:key='.env('FCM_KEY'),
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        
        return $result;
    }

    public function store_history(array $params)
    {
        DB::insert("
        INSERT INTO pushes (
            id,
            title,
            body
        ) VALUES (
            :id,
            :title,
            :body
        )", [
            'id' => data_get($params, 'id'),
            'title' => data_get($params, 'title'),
            'body' => data_get($params, 'body')
        ]);
        
        // lastInsertId
        return DB::getPdo()->lastInsertId();
    }

    public function index_history(array $params)
    {
        $res = DB::select("
        SELECT
            id,
            title,
            body,
            reg_dt
        FROM pushes
        ORDER BY reg_dt ASC
        LIMIT :limit OFFSET :offset
        ", [
            'offset' => data_get($params, 'offset') ?? 0,
            'limit' => data_get($params, 'limit') ?? PHP_INT_MAX
        ]);
        
        return $res;
    }
}
