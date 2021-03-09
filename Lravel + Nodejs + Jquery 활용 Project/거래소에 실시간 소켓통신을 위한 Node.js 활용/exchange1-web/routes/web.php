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

/*Route::get('/', 'TestController@test')->name('main');

Route::get('/home', 'TestController@test')->name('home');*/




Route::get('/aicdss/register_agree', 'AicdssController@register_agree')->name('aicdss.register_agree');

Route::get('/aicdss/register_complete', 'AicdssController@register_complete')->name('aicdss.register_complete');

Route::get('/register_agree', 'Auth\RegisterController@register_agree')->name('register_agree');

Route::get('/register_complete', 'Auth\RegisterController@register_complete')->name('register_complete');

Route::get('/mail_complete', 'Auth\RegisterController@mail_complete')->name('mail_complete');

Route::post('/main/login', 'HomeController@login')->name('main.login');

Route::get('/my_asset', 'MyassetController@index')->name('my_asset.index');

Route::get('/security_lv', 'SecurityController@index')->name('security_lv.index');

Auth::routes();

Auth::routes(['verify' => true]);

Route::get('/', 'HomeController@index')->name('main');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/market/{coin}', 'MarketController@index')->name('market');

Route::get('/market_ucss/{coin}', 'MarketBtcController@index')->name('marketUCSS');

Route::get('/market_btc/{coin}', 'MarketBtcController@index')->name('marketBTC');

Route::get('/market_eth/{coin}', 'MarketBtcController@index')->name('marketETH');

Route::get('/trans_wallet', 'TranswalletController@index')->name('trans_wallet');

Route::get('/notice', 'NoticeController@index')->name('notice');

Route::get('/notice/{id}', 'NoticeController@view')->name('notice_view');

Route::get('/notice_write', 'NoticeController@write')->name('notice_write');

Route::get('/event', 'EventController@index')->name('event');

Route::get('/event/{id}', 'EventController@view')->name('event_view');

Route::get('/icos/{category}', 'IcoController@list')->name('ico_list');

Route::get('/ico_create', 'IcoController@create')->name('ico_create');

Route::get('/ico/{id}', 'IcoController@ico_show')->name('ico_show');

Route::post('/ico_insert', 'IcoController@insert')->name('ico_insert');

Route::post('/ico/{id}/update', 'IcoController@ico_update')->name('ico_update');

Route::get('/my_ico/{category}', 'IcoController@my_ico')->name('my_ico');

Route::get('/ico_history', 'IcoController@my_ico_history')->name('ico_history');

Route::post('/ico_buy/{id}', 'IcoController@ico_buy')->name('ico_buy');

Route::get('/p2p_onprogress/{type}', 'P2pController@onProgress')->name('p2p_onprogress');

Route::get('/p2p_history', 'P2pController@hiStory')->name('p2p_history');

Route::get('/p2p/{type}', 'P2pController@list')->name('p2p_list');

Route::post('/p2p_insert', 'P2pController@insert')->name('p2p_insert');

Route::post('/p2p_apply', 'P2pController@apply')->name('p2p_apply');

Route::post('/p2p_ajax_test', 'P2pController@p2p_ajax_test');

Route::get('/p2p_create', 'P2pController@create')->name('p2p_create');

Route::get('/p2p_deleted/{id}','P2pController@deleted')->name('p2p_deleted');

Route::get('/p2p_confirm/{id}', 'P2pController@confirm')->name('p2p_confirm');

Route::get('/faq', 'FaqController@index')->name('faq');

Route::get('/qna', 'QnaController@index')->name('qna_list');

Route::get('/qna/{id}', 'QnaController@show')->name('qna_show');

Route::post('/qna/insert', 'QnaController@insert')->name('qna_insert');

Route::get('/mypage/otp_setting', 'MypageController@otp_setting')->name('mypage.otp_setting');

Route::post('/mypage/otp_setting_register', 'MypageController@otp_setting_register')->name('mypage.otp_setting_register');

Route::get('/mypage/alarm_setting', 'MypageController@alarm_setting')->name('mypage.alarm_setting');

Route::post('/mypage/alarm_setting_update', 'MypageController@alarm_setting_update')->name('mypage.alarm_setting_update');

Route::get('/mypage/lock_reward/{coin?}', 'MypageController@lock_reward')->name('mypage.lock_reward');

Route::post('/mypage/lock_reward_update/{coin}', 'MypageController@lock_reward_update')->name('mypage.lock_reward_update');

Route::get('/mypage/password_change', 'MypageController@password_change')->name('mypage.password_change');

Route::post('/mypage/password_change_update', 'MypageController@password_change_update')->name('mypage.password_change_update');

Route::get('/mypage/security_setting', 'MypageController@security_setting')->name('mypage.security_setting');

Route::get('/otp', 'OtpController@index')->name('otp_input');

Route::get('/game/game_1', 'GameController@index')->name('game_ch');

Route::get('/convert', 'ConvertReward@index');




/* Ajax START */



Route::post('/check/login', 'Ajax\LoginCheckController@index');

Route::get('/chart/config', 'Chart\TradingviewController@config');

Route::get('/chart/history', 'Chart\TradingviewController@history');

Route::get('/chart/symbols', 'Chart\TradingviewController@symbols');

Route::get('/chart/time', 'Chart\TradingviewController@time');

Route::get('/chart_new/config', 'Chart\TradingviewController@config_new');

Route::get('/chart_new/history', 'Chart\TradingviewController@history_new');

Route::get('/chart_new/symbols', 'Chart\TradingviewController@symbols_new');

Route::get('/chart_new/time', 'Chart\TradingviewController@time_new');

Route::post('/requests/buysell_coin_data', 'MarketController@buysell_coin_data');

Route::post('/requests/refresh_user_data', 'MarketController@refresh_user_data');

Route::post('/livedata/refresh_user_readyorder', 'MarketController@refresh_user_readyorder');

Route::post('/livedata/refresh_user_history', 'MarketController@refresh_user_history');

Route::post('/requests/trade_cancel', 'MarketController@trade_cancel');

Route::post('/requests/sess_id', 'MarketController@sess_id');

// btc market Ajax
Route::post('/buysell_coin_data_new', 'MarketBtcController@buysell_coin_data');

Route::post('/refresh_user_data_new', 'MarketBtcController@refresh_user_data');

Route::post('/refresh_user_readyorder_new', 'MarketBtcController@refresh_user_readyorder');

Route::post('/refresh_user_history_new', 'MarketBtcController@refresh_user_history');

Route::post('/trade_cancel_new', 'MarketBtcController@trade_cancel');

Route::post('/sess_id_new', 'MarketBtcController@sess_id');



//register_ajax
Route::post('/email/duplicate', 'Ajax\RegisterAjaxController@email_duplicate');

Route::post('/mobile/verify', 'Ajax\RegisterAjaxController@mobile_verify');

Route::post('/mobile/verify/confirm', 'Ajax\RegisterAjaxController@mobile_verify_confirm');

Route::post('/username/duplicate', 'Ajax\RegisterAjaxController@username_duplicate');



//security_lv_ajax
Route::post('/mobile/existing_user_verify', 'Ajax\SecurityAjaxController@existing_user_mobile_verify');

Route::post('/mobile/existing_user_verify/confirm', 'Ajax\SecurityAjaxController@existing_user_mobile_verify_confirm');



//trans_wallet_ajax
Route::post('/refresh_trans_wallet', 'Ajax\TranswalletAjaxController@refresh_trans_wallet');

Route::post('/exchangeCashCoin', 'Ajax\TranswalletAjaxController@exchangeCashCoin');

Route::post('/select_coin', 'Ajax\TranswalletAjaxController@select_coin');

Route::post('/check_address', 'Ajax\TranswalletAjaxController@check_address');

Route::post('/withdraw_max_amt', 'Ajax\TranswalletAjaxController@withdraw_max_amt');

Route::post('/withdraw_onkey_amt', 'Ajax\TranswalletAjaxController@withdraw_onkey_amt');

Route::post('/send_transaction', 'Ajax\TranswalletAjaxController@send_transaction');

Route::post('/refresh_withdraw_history', 'Ajax\TranswalletAjaxController@refresh_withdraw_history');

Route::post('/refresh_erc_eth_balance', 'Ajax\TranswalletAjaxController@refresh_erc_eth_balance');




//My_Asset_ajax
Route::post('/show_more_history', 'Ajax\MyAssetAjaxController@show_more_history');

Route::post('/search_date_history', 'Ajax\MyAssetAjaxController@search_date_history');

Route::post('/show_more_not_concluded', 'Ajax\MyAssetAjaxController@show_more_not_concluded');

Route::post('/refresh_not_concluded', 'Ajax\MyAssetAjaxController@refresh_not_concluded');




//lock_reward_ajax
Route::post('/history_view_more', 'Ajax\LockRewardAjaxController@history_view_more');

Route::post('/dividend_view_more', 'Ajax\LockRewardAjaxController@dividend_view_more');




//ico_ajax
Route::post('/ico/buy_asset/{pr_id}', 'Ajax\IcoAjaxController@buy_asset');

Route::post('/ico/list/more1', 'Ajax\IcoAjaxController@ico_list_more1');

Route::post('/ico/list/more2', 'Ajax\IcoAjaxController@ico_list_more2');

Route::post('/ico/history/more', 'Ajax\IcoAjaxController@ico_history_more');




//p2p_ajax
Route::post('/p2p/history/more', 'Ajax\P2pAjaxController@history_more');

Route::post('/p2p/list/more', 'Ajax\P2pAjaxController@list_more');

Route::post('/mobile/p2p/list/more', 'Ajax\P2pAjaxController@mobile_list_more');

Route::post('/mobile/p2p/onprogress/more', 'Ajax\P2pAjaxController@mobile_p2p_onprogress');

Route::post('/mobile/p2p/history/more', 'Ajax\P2pAjaxController@mobile_history_more');




//pop_up
Route::post('/nomore/popup', 'AjaxController@popup');

//custom_center
Route::post('/notice/more', 'Ajax\CustomController@notice');

Route::post('/event/more', 'Ajax\CustomController@event');

Route::post('/qna/more', 'Ajax\CustomController@qna');

//API

Route::post('/oauth/login', 'API\ApiController@login');


