<?php

namespace App\Classes;

use Facades\App\Classes\Settings;

use Nexmo\Client\Credentials\Basic;
use Nexmo\Client;

class Nexmo_sms {
    public function send_sms($country, $mobile_number, $description)
    {
        $setting = Settings::Settings();
        
        $basic  = new Basic('21050ec2', '5692d1b54dad7fd7');
        $client = new Client($basic);

        if($country == 'kr'){
            $country_number = '+82';
        }else if($country == 'jp'){
            $country_number = '+81';
        }else if($country == 'ch'){
            $country_number = '+86';
        }else if($country == 'th'){
            $country_number = '+66';
        }else if($country == 'us'){
            $country_number = '+1';
        }

        $number = $this->startsWith($mobile_number, '+') == true ?  $country_number.substr($mobile_number, 3) : $country_number.substr($mobile_number, 1);

        if(preg_match('/^\+[0-9]+$/', $number)){
            $message = $client->message()->send([
                'to'   => $number,
                'from' => 'exchange',
                'text' => $description,
                'type' => 'unicode'
            ]);

            // info($message->getResponseData());

            return true;
        }else{
            return false;
        }
    }

    function startsWith($haystack, $needle) {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

}
