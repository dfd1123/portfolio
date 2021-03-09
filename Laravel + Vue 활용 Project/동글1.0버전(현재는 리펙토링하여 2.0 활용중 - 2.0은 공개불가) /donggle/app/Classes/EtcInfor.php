<?php

namespace App\Classes;

use Illuminate\Http\Request;

use DB;

class EtcInfor {
  public function load(){
    $etcInfor = array(
      array(
        "kind" => "핏",
        "option" => array(
          array(
            "name" => "슬림핏",
            "status" => false
          ),
          array(
            "name" => "보통핏",
            "status" => false
          ),
          array(
            "name" => "박시핏",
            "status" => false
          )
        )
      ),
      array(
        "kind" => " 촉감",
        "option" => array(
          array(
            "name" => "부드러움",
            "status" => false
          ),
          array(
            "name" => "보통",
            "status" => false
          ),
          array(
            "name" => "뻣뻣함",
            "status" => false
          )
        )
      ),
      array(
        "kind" => "신축성",
        "option" => array(
          array(
            "name" => "없음",
            "status" => false
          ),
          array(
            "name" => "보통",
            "status" => false
          ),
          array(
            "name" => "높음",
            "status" => false
          )
        )
      ),
      array(
        "kind" => "비침",
        "option" => array(
          array(
            "name" => "없음",
            "status" => false
          ),
          array(
            "name" => "보통",
            "status" => false
          ),
          array(
            "name" => "있음",
            "status" => false
          )
        )
      ),
      array(
        "kind" => "두께",
        "option" => array(
          array(
            "name" => "얇음",
            "status" => false
          ),
          array(
            "name" => "보통",
            "status" => false
          ),
          array(
            "name" => "두꺼움",
            "status" => false
          )
        )
      )
    );

    return json_encode($etcInfor, JSON_UNESCAPED_UNICODE);
  }

  public function set($etcInfor, $kind, $index){  // ex) EtcInfor::set($etcInfor, '두께', 1)
    $etcInfor = json_decode($etcInfor);

    if($index > 0){
      $index = $index -1;
    }else{
      $index = 0;
    }

    foreach($etcInfor as $key=>$el){
      if($el[$key]['kind'] === $kind){
        for($i=0;$i<3;$i++){
          $etcInfor[$key]['option'][$i]['status'] = false;
        }
        $etcInfor[$key][$index]['status'] = true;
      }
    }

    return json_encode($etcInfor);
  }
}
