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
Route::post('check_naver', 'Auth\LoginController@check_naver_login');
Route::get('decodejwt', 'Auth\LoginController@decodetoken');
Route::resource('login', 'Auth\LoginController');
Route::post('logout', 'Auth\LoginController@logout');
Route::post('refresh', 'Auth\LoginController@refreshTkn');
Route::post('pw_email', 'Auth\LoginController@password_find_link');
Route::post('pw_change', 'Auth\LoginController@password_change');
Route::post('email_verified', 'UserController@email_verified');

Route::resource('Users','UserController');
Route::resource('plnr','PlnrController');
Route::resource('favorite','FavoriteController');
Route::resource('msg','MessageController');
Route::resource('estimate','EstimateController');
Route::resource('estimate_bid','EstimateBidController');
Route::resource('estimate_theme','EstimateThemeController');
Route::resource('estimate_step','EstimateStepController');
Route::resource('reserve','ReserveController');
Route::resource('product','ProductController');
Route::resource('portfolio','PortfolioController');
Route::resource('review','ReviewController');
Route::resource('settings','SettingsController');
Route::resource('triptip','TriptipController');
Route::resource('acmp','AccompanyController');
Route::resource('notice','NoticeController');
Route::resource('bokp','BokpController');
Route::resource('push','PushController');
Route::resource('payletter','PayletterController');
Route::resource('landingplr','LandingPlannerController');



Route::post('fdata', 'UserController@fdata');