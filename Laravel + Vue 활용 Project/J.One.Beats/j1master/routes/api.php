<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

*/
Route::resource('producers', 'ProducerController');
Route::resource('banner', 'BannerController');
Route::resource('beat', 'BeatController');
Route::resource('qna','QnaController');
Route::resource('faq','FaqController');
Route::resource('license','LicenseController');
Route::resource('mood','MoodController');
Route::resource('genre','GenreController');
Route::resource('notice','NoticeController');
Route::resource('user','UserController');
Route::resource('bookkeeping', 'BookkeepingController');

Route::resource('login', 'Auth\LoginController');
Route::get('logintest', 'Auth\LoginController@issueTkn');
Route::get('decodetoken', 'Auth\LoginController@decodetoken');
