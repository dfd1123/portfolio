<?php

namespace App\Classes;

use Illuminate\Http\Request;

use DB;

class HowWash {
  public function load(){
    $howWash = array(
      array(
        "name" => "손세탁",
        "status" => false
      ),
      array(
        "name" => "드라이(클리닝)",
        "status" => false
      ),
      array(
        "name" => "물세탁",
        "status" => false
      ),
      array(
        "name" => "단독세탁",
        "status" => false
      ),
      array(
        "name" => "울세탁",
        "status" => false
      ),
      array(
        "name" => "표백제 사용금지",
        "status" => false
      ),
      array(
        "name" => "다림질 금지",
        "status" => false
      ),
      array(
        "name" => "세탁기 금지",
        "status" => false
      )
    );

    return json_encode($howWash, JSON_UNESCAPED_UNICODE);
  }

  public function set($howWash, $name, $status){
    $howWash = json_decode($howWash);

    foreach($howWash as $key=>$el){
      if($el[$key]['name'] === $name){
        if($status === 1){
          $status = true;
        }else if($status === 0){
          $status = false;
        }
        $howWash[$key]['status'] === $status;
      }
    }

    return json_encode($howWash);
  }
}
