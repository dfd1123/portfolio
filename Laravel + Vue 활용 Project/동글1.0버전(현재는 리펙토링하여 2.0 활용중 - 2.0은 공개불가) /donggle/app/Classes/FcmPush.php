<?php
namespace App\Classes;

use Illuminate\Http\Request;

class FcmPush {
	public function push_one($tokens, $data)
    {
		$url = 'https://fcm.googleapis.com/fcm/send';
		
        $msg = array(
            'title'   => $data['title'],
            'body'    => $data['body'],
            'sound' => 'default',
            'icon' => 'ic_launcher_alarm_round'
        );

        $field_data = array(
            'title'   => $data['title'],
            'body'    => $data['body'],
        );
		//$tokens = ['cosc0rGC5Es:APA91bFCcFtiZLOIltDUPB5IRVm5zyFNIf3Gm0qWniaGekGlaDjeUJ1qDX0fIGVJD1IgcFl4N5qjBC-vM-w27HKwLCKGbE25q2uG4yi3wI8vocYNREutTGiK4AijB1pjpGEtgZzgs_nv'];
		
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
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
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
            'title'   => $data['title'],
            'body'    => $data['body'],
            'sound' => 'default',
            'click_action' => 'OPEN_TOPIC',
            'icon' => 'ic_launcher_alarm_round'
        );

        $field_data = array(
            'title'   => $data['title'],
            'body'    => $data['body'],
            'topic' => $topic
        );
		
		$fields = array(
			'to'		=> "/topics/".$topic,
			'data'	=> $field_data,
			'notification' => $msg
		);
        
        // 헤더에 푸시 키 추가
        $headers = array(
            'Authorization:key='.ENV('FCM_KEY'),
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
		
		//info($result);
		return $result;
    }
}
