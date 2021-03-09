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
use Illuminate\Http\Request;

if (!App::environment(['local'])) {
    URL::forceScheme('https');
}



Auth::routes();

Auth::routes(['verify' => true]);

Route::get('/', 'HomeController@index')->name('main');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/payment', 'PaymentController@payment')->name('payment');

Route::post('/api/payment_window', 'ApiController@payment_window')->name('payment_window');

Route::get('/payment_history', 'PaymentController@payment_history')->name('payment_history');

Route::get('/company', 'CompanyController@company')->name('company');

Route::post('/company/create', 'CompanyController@create')->name('company.create');

Route::post('/company/update', 'CompanyController@update')->name('company.update');

Route::get('/notice', 'NoticeController@index')->name('notice');

Route::get('/notice/{id}', 'NoticeController@show')->name('notice_show');

Route::get('/security', 'SecurityController@security')->name('security');

Route::post('/nicecheck/checkplus_success', 'SecurityController@checkplus_success')->name('checkplus_success');

Route::post('/nicecheck/checkplus_fail', 'SecurityController@checkplus_fail')->name('checkplus_fail');

Route::post('/security_setting_document', 'SecurityController@security_setting_document')->name('security_setting_document');

Route::post('/security_setting_account', 'SecurityController@security_setting_account')->name('security_setting_account');

Route::get('/api_detail', 'ApiController@api_detail')->middleware('auth')->name('api_detail');

Route::post('/api_insert', 'ApiController@api_insert')->middleware('auth')->name('api_insert');

// AJAX
Route::post('/email/duplicate', 'Ajax\RegisterAjaxController@email_duplicate');

Route::post('/email/verify/request', 'Ajax\RegisterAjaxController@email_verify_request');

Route::post('/email/verify/certify', 'Ajax\RegisterAjaxController@email_verify_certify');

Route::post('/mobile_verify_nicecheck', 'Ajax\RegisterAjaxController@mobile_verify_nicecheck');

Route::post('/auth/checkplus_success', 'Ajax\RegisterAjaxController@checkplus_success')->name('auth.checkplus_success');

Route::post('/auth/checkplus_fail', 'Ajax\RegisterAjaxController@checkplus_fail')->name('auth.checkplus_fail');

Route::post('/api/call_orderbook', 'Ajax\PaymentAjaxController@call_orderbook');

Route::post('/api/timeout', 'Ajax\PaymentAjaxController@timeout');

Route::post('/api/cancel', 'Ajax\PaymentAjaxController@cancel');

Route::post('/api/check_status', 'Ajax\PaymentAjaxController@check_status');

Route::post('/api/payment_refund', 'Ajax\PaymentAjaxController@payment_refund');

Route::post('/api/payment_history', 'Ajax\PaymentAjaxController@payment_history');

Route::post('/refresh_apikey', 'Ajax\ApiAjaxController@refresh_apikey');

Route::post('/delete_apikey', 'Ajax\ApiAjaxController@delete_apikey');


