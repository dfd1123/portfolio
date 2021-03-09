<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'auth:api'], function(){

});
Route::post('/mail/invoce','MailController@index');
Route::resource('category','CategoryController')->only(['index']);
Route::resource('items','ItemsController')->only(['index']);
Route::resource('request_quote','RequestQuoteController')->only(['store', 'show']);
Route::resource('settings','SettingsController')->only(['index']);
