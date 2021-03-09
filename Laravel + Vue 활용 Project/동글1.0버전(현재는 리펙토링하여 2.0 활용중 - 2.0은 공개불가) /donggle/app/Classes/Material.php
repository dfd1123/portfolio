<?php

namespace App\Classes;

use Illuminate\Http\Request;

use DB;

class Material {
  public function load(){
    $material = array(
      array(
        "name" => "면",
        "status" => false
      ),
      array(
        "name" => "폴리에스테르",
        "status" => false
      ),
      array(
        "name" => "나일론",
        "status" => false
      ),
      array(
        "name" => "레이온",
        "status" => false
      ),
      array(
        "name" => "울",
        "status" => false
      ),
      array(
        "name" => "아크릴",
        "status" => false
      ),
      array(
        "name" => "린넨",
        "status" => false
      ),
      array(
        "name" => "스판",
        "status" => false
      ),
      array(
        "name" => "실크",
        "status" => false
      ),
      array(
        "name" => "레더",
        "status" => false
      ),
      array(
        "name" => "캐시미어",
        "status" => false
      ),
      array(
        "name" => "알파카",
        "status" => false
      ),
      array(
        "name" => "텐셀",
        "status" => false
      ),
      array(
        "name" => "모달",
        "status" => false
      ),
      array(
        "name" => "기타",
        "status" => false
      )
    );

    return json_encode($material, JSON_UNESCAPED_UNICODE);
  }

  public function set($material, $name, $status){
    $material = json_decode($material);

    foreach($material as $key=>$el){
      if($el[$key]['name'] === $name){
        if($status === 1){
          $status = true;
        }else if($status === 0){
          $status = false;
        }
        $material[$key]['status'] === $status;
      }
    }

    return json_encode($material);
  }
}
