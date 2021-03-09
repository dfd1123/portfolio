<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', function () {
    return view('welcome');
});

Route::get('facebook', [
  'as'   => 'facebook.login',
  'uses' => 'FacebookController@redirectToProvider'
]);
Route::get('facebook/callback', [
  'as'   => 'facebook.callback',
  'uses' => 'FacebookController@handleProviderCallback'
]);

Route::get('/test', function(){
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
});

