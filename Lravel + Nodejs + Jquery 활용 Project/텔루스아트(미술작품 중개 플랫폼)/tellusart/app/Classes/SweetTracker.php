<?php

namespace App\Classes;

use GuzzleHttp\Client;
use DB;

class SweetTracker {
    private $base;
    private $key;
    
    function __construct() {
        $this->base = 'info.sweettracker.co.kr';
        $this->key = env('SWEETTRACKER_KEY', '2VsrDbVzHp0wTFyQifQC8Q');
        $this->companys = null;
    }

    // 택배사 목록 가져오기
    public function companylist() {
        $client = new Client();
        $res = $client->get("http://$this->base/api/v1/companylist?t_key=$this->key");
        $this->companys = $res->getBody();
        
        return json_decode($this->companys, true);
    }

    // 운송장 번호 조회
    public function trackingInfo($code, $invoice) {
        $client = new Client();
        $res = $client->get("http://$this->base/api/v1/trackingInfo?t_key=$this->key&t_code=$code&t_invoice=$invoice");

        $data = json_decode($res->getBody(), true);

        if($res->getStatusCode() != 200) {
            info($data);
            return ['result' => 'N'];
        }

        return $data; //'result' => 'Y'
    }

    public function companyname($code){
        if(empty($this->companys)) {
            $client = new Client();
            $res = $client->get("http://$this->base/api/v1/companylist?t_key=$this->key");
            $this->companys = $res->getBody();
        }
		
		$companys = json_decode($this->companys, true);
	
		foreach($companys['Company'] as $company_list){
			if($company_list['Code'] == $code){
				return $company_list['Name'];
				break;	
			}	
		}
		
		return null; 
	}
}