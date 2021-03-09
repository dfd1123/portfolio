<?php

namespace App\Classes;

use Nexmo\Client\Credentials\Basic;
use Nexmo\Client;

class Nexmo_sms
{
    public function send_sms($country, $mobile_number, $description)
    {
        $basic  = new Basic('ce8e02dd', 'CgTCypeuLj5aoMka');
        $client = new Client($basic);

        if ($country == 'kr') {
            $country_number = '+82';
        } elseif ($country == 'jp') {
            $country_number = '+81';
        } elseif ($country == 'ch') {
            $country_number = '+86';
        } elseif ($country == 'th') {
            $country_number = '+66';
        } elseif ($country == 'us') {
            $country_number = '+1';
        } elseif ($country == 'spain') {
            $country_number = '+34';
        }

        $number = $this->startsWith($mobile_number, '+') == true ?  $country_number.substr($mobile_number, 3) : $country_number.substr($mobile_number, 1);

        if (preg_match('/^\+[0-9]+$/', $number)) {
            $message = $client->message()->send([
                'to'   => $number,
                'from' => 'exchange',
                'text' => $description,
                'type' => 'unicode'
            ]);

            // info($message->getResponseData());

            return true;
        } else {
            return false;
        }
    }

    public function startsWith($haystack, $needle)
    {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }
}
