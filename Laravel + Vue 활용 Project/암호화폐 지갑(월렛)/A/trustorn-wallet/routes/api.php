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

Route::post('login', 'PassportController@login');
Route::post('register', 'PassportController@register');
Route::post('register_existing', 'PassportController@register_existing');
Route::post('sms/verify/request', 'AppController@sms_verify_request');
Route::post('sms/verify/certify', 'AppController@sms_verify_certify');
Route::post('email/verify/request', 'AppController@email_verify_request');
Route::post('email/verify/certify', 'AppController@email_verify_certify');
Route::get('getCountry', 'AppController@fSearchCountryCode');

Route::middleware('auth:api')->group(function () {
    Route::get('logout', 'PassportController@logout');
    Route::post('refresh', 'PassportController@refresh');
    Route::get('heartbeat', 'PassportController@heartbeat');

    Route::get('detail', 'PassportController@detail');
    Route::put('detail', 'PassportController@detail_update');
    Route::post('detail/confirm_secret', 'PassportController@detail_confirm_secret');

    Route::post('security/setting_document', 'SecurityController@security_setting_document');
    Route::post('security/setting_account', 'SecurityController@security_setting_account');

    Route::get('tos/private_info_term/{locale}', 'TosController@private_info_term');
    Route::get('tos/use_term/{locale}', 'TosController@use_term');

    Route::get('wallet/info/{coin_type}', 'WalletController@info');
    Route::get('wallet/history', 'WalletController@history');
    Route::get('wallet/asset', 'WalletController@asset');
    Route::get('wallet/address/{coin_type}/{user_id}', 'WalletController@user_address');
    Route::get('wallet/coins', 'WalletController@wallet_coins');
    Route::post('wallet/send', 'WalletController@send');
    Route::post('wallet/address/verify', 'WalletController@verify_address');

    Route::post('wallet/cash_deposite', 'WalletController@cash_deposite');
    Route::post('wallet/cash_withdraw', 'WalletController@cash_withdraw');
    Route::post('wallet/cash_cancel', 'WalletController@cash_cancel');
    Route::get('wallet/cash_history', 'WalletController@cash_history');

    Route::post('wallet/buy/estimate', 'WalletController@buy_estimate');
    Route::post('wallet/buy/execute', 'WalletController@buy_execute');
    Route::post('wallet/buy/tru', 'WalletController@buy_tru'); // tru 코인 구매
    Route::post('wallet/sell/estimate', 'WalletController@sell_estimate');
    Route::post('wallet/sell/execute', 'WalletController@sell_execute');

    Route::get('revenue/user_revenue', 'WalletController@user_revenue');
    Route::get('revenue/monthly_revenue', 'WalletController@monthly_revenue');

    Route::get('company/info', 'AppController@company_info');

    Route::post('wallet/pay', 'WalletController@pay');

    Route::apiResources([
        'tests' => 'TestController',
        'favors' => 'FavorController',
        'users' => 'UserController',
        'notices' => 'NoticeController',
        'faqs' => 'FaqController',
        'qnas' => 'QnaController'
    ]);

    Route::get('test', function () {
        return response()->json(['error' => 'check_address'], 422);
    });

    
});
