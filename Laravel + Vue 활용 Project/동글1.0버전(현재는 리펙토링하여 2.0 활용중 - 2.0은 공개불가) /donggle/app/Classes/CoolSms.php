<?php

namespace App\Classes;

class Coolsms {
    private $base;
    private $api_key;
    private $api_secret;
    private $from;
    
    function __construct() {
        $this->base = 'api.solapi.com/messages/v4';
        $this->api_key =  'NCSSCUAVVCVBCTUP';
        $this->api_secret = 'H7WSX08SE1LOQVKTW940428VXVCRMUY7';
        $this->from = '18333092';
    }

    // SMS(단문) 발송
    public function send_sms($to, $text) {
        $url = "https://$this->base/send";
        $date = date('Y-m-d\TH:i:s.Z\Z', time());
        $salt = uniqid();
        $signature = hash_hmac('sha256', $date.$salt, $this->api_secret);

        $fields = new \stdClass();
        $message = new \stdClass();
        $message->text = $text;
        $message->type = 'LMS';
		$message->subject = '(주) 동글';
        $message->to = $to;
        $message->from = $this->from;
        $fields->message = $message;
        $fields_string = json_encode($fields);
        $header = "Authorization: HMAC-SHA256 apiKey={$this->api_key}, date={$date}, salt={$salt}, signature={$signature}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($header, "Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_POST, count((array)$fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        $result = json_decode(curl_exec($ch));
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($httpcode == '200' && $result->statusCode == '2000') {
            return ['status' => 'ok'];
        } else if($httpcode == '400') {
            return ['status' => 'bad_request', 'errorCode' => $result->errorCode, 'errorMessage' => $result->errorMessage];
        } else if($httpcode == '402') {
            return ['status' => 'payment_required', 'errorCode' => $result->errorCode, 'errorMessage' => $result->errorMessage];
        } else {
            return ['status' => 'unknown'];
        }
    }
}