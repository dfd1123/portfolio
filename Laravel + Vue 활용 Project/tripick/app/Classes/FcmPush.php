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
            'sound' => 'default'
        );

		//$tokens = ['cosc0rGC5Es:APA91bFCcFtiZLOIltDUPB5IRVm5zyFNIf3Gm0qWniaGekGlaDjeUJ1qDX0fIGVJD1IgcFl4N5qjBC-vM-w27HKwLCKGbE25q2uG4yi3wI8vocYNREutTGiK4AijB1pjpGEtgZzgs_nv'];
		
		$fields = array(
			'registration_ids'		=> $tokens,
			'data'	=> $msg,
			'notification' => $msg
		);
        // 헤더에 푸시 키 추가
        $headers = array(
            'Authorization:key=AAAAqhil3BI:APA91bGjGI3CSi8TgO0lRosAMnbI-pWhTZM4858DtadNMzN3kiHN5oUNMCPaKLpWVU8a8RItAf1i3apcV_i3VFEH94ctfVhw_iLRzhJiw3ZLhMiI4o5KWS_PJk2bhVolPXXdSxM2cl-P',
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
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
            'sound' => 'default'
        );
		
		$fields = array(
			'to'		=> "/topics/".$topic,
			'data'	=> $msg,
			'notification' => $msg
		);
        
        // 헤더에 푸시 키 추가
        $headers = array(
            'Authorization:key=AAAAqhil3BI:APA91bGjGI3CSi8TgO0lRosAMnbI-pWhTZM4858DtadNMzN3kiHN5oUNMCPaKLpWVU8a8RItAf1i3apcV_i3VFEH94ctfVhw_iLRzhJiw3ZLhMiI4o5KWS_PJk2bhVolPXXdSxM2cl-P',
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);           
        curl_close($ch);		
		
		//info($result);
		return $result;
    }
}
