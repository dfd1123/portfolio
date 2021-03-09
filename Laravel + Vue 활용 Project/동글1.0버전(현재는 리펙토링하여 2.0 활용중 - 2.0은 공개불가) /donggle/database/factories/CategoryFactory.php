<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    $category = Category::all()->random(1)->first();
    $ca_id = $category->id;

    

    // 카테고리 id 자동 생성 알고리즘
    $len = strlen($ca_id);
    $len2 = $len + 1;
    $row = DB::table('category')->select(DB::raw("MAX(SUBSTRING(id,".$len2.",2)) AS max_subid"))->whereRaw("SUBSTRING(id,1,".$len.") = '".$ca_id."'")->first();
    $subid = base_convert($row->max_subid,36,10);
    $subid += 36;
    $subid = base_convert($subid,10,36);
    $subid = substr("00".$subid, -2);
    $subid = $ca_id.$subid;
    $ca_id = $subid;

    $ca_name = $faker->jobTitle;

    $length = strlen($ca_id);

    if($length > 2){
        $up_id_array = array();

        for($i = $length/2 - 1; $i <= $length/2; $i++){
            $up_id_array[] = substr($ca_id,0,$i*2);
        }
        
        $up_category = Category::select(DB::raw("GROUP_CONCAT(ca_name separator ' > ') AS ca_name"))->whereIn('id', $up_id_array)->orderBy('id','ASC')->first(); 
        
        $ca_name = $up_category->ca_name." > ".$ca_name;
    }

    return [
        "id" => $ca_id,
        "ca_name" => $ca_name,
        "ca_use" => (int) $faker->boolean
    ];
});
