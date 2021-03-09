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

if (!App::environment(['local'])) {
    URL::forceScheme('https');
} else {
    URL::forceScheme('http');
}

/*
Route::post('/nicecheck/checkplus_success', 'AppController@checkplus_success')->name('checkplus_success');
Route::post('/nicecheck/checkplus_fail', 'AppController@checkplus_fail')->name('checkplus_fail');
*/

Route::post('/payletter/beatCB','PayletterController@beatOrderCallback');
Route::post('/payletter/licenseCB','PayletterController@licenseOrderCallback');

Route::get('/email/verify', 'AppController@email_verify');
Route::post('/mobile/auth', 'AppController@mobile_auth_verify');

Route::get('/404', 'AppController@abort');
Route::any('{all}', 'AppController@index')->where(['all' => '.*']);
