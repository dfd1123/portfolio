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

// 정상운영 시

Route::get('/', 'HomeController@index')->name('main');

Route::get('/home', 'HomeController@index')->name('home');

// 정상운영 시

// 서버 점검 시

//Route::get('/', 'HomeController@server_test')->name('main');

//Route::get('/home', 'HomeController@server_test')->name('home');

// 서버 점검 시

//Route::group(['middleware' => ['simulated']], function(){ //서버 점검 시뮬레이트 태그 시작(점검 안할시 주석 하세요.)*/

Route::get('/laravelphpinfo', 'TestController@test')->middleware('simulated');

Route::get('/storage/{path}', 'FileController@serve_storage')->where(['path' => '.*']); // '^.*\.(html|css|js|png|jpg)$'

Route::get('/aicdss/register_agree', 'AicdssController@register_agree')->name('aicdss.register_agree');

Route::get('/aicdss/register_complete', 'AicdssController@register_complete')->name('aicdss.register_complete');

Route::get('/register_agree', 'Auth\RegisterController@register_agree')->name('register_agree');

Route::get('/register/complete', 'Auth\RegisterController@register_complete')->name('register_complete');

Route::get('/mail_complete', 'Auth\RegisterController@mail_complete')->name('mail_complete');

Route::post('/main/login', 'HomeController@login')->name('main.login');

Route::get('/my_asset', 'MyassetController@index')->name('my_asset.index');

Route::get('/security_lv', 'SecurityController@index')->name('security_lv.index');

Auth::routes();

Auth::routes(['verify' => true]);

Route::get('/mobile_main', 'HomeController@mobile_main')->name('mobile_main');

/*Route::get('/market/{coin}', 'MarketController@index')->name('market');

Route::get('/market_usdc/{coin}', 'MarketBtcController@index')->name('marketUSDC');

Route::get('/market_btc/{coin}', 'MarketBtcController@index')->name('marketBTC');

Route::get('/market_eth/{coin}', 'MarketBtcController@index')->name('marketETH');*/

//Route::group(['middleware' => ['simulated']], function(){
    Route::get('/market_krw/{coin}', 'MarketBtcController@index')->name('marketKRW');

    Route::get('/trans_wallet', 'TranswalletController@index')->name('trans_wallet');
//});

Route::get('/notice', 'NoticeController@index')->name('notice');

Route::get('/notice/{id}', 'NoticeController@view')->name('notice_view');

Route::get('/notice_write', 'NoticeController@write')->name('notice_write');

Route::get('/newsflash', 'NewsflashController@index')->name('newsflash');

Route::get('/newsflash/{id}', 'NewsflashController@view')->name('newsflash_view');

Route::get('/newsflash_write', 'NewsflashController@write')->name('newsflash_write');

Route::get('/news', 'NewsController@index')->name('news');

Route::get('/news/{id}', 'NewsController@view')->name('news_view');

Route::get('/news_write', 'NewsController@write')->name('news_write');

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

Route::group(['middleware' => ['simulated']], function(){
    Route::get('/p2p_onprogress/{type}', 'P2pController@onProgress')->name('p2p_onprogress');

    Route::get('/p2p_history', 'P2pController@hiStory')->name('p2p_history');

    Route::get('/p2p/{type}', 'P2pController@list')->name('p2p_list');

    Route::post('/p2p_insert', 'P2pController@insert')->name('p2p_insert');

    Route::post('/p2p_apply', 'P2pController@apply')->name('p2p_apply');

    Route::post('/p2p_ajax_test', 'P2pController@p2p_ajax_test');

    Route::get('/p2p_create', 'P2pController@create')->name('p2p_create');

    Route::get('/p2p_deleted/{id}', 'P2pController@deleted')->name('p2p_deleted');

    Route::get('/p2p_canceled/{id}', 'P2pController@canceled')->name('p2p_canceled');

    Route::get('/p2p_confirm/{id}', 'P2pController@confirm')->name('p2p_confirm');
});

Route::get('/faq', 'FaqController@index')->name('faq');

Route::get('/qna', 'QnaController@index')->name('qna_list');

Route::get('/qna/{id}', 'QnaController@show')->name('qna_show');

Route::post('/qna/insert', 'QnaController@insert')->name('qna_insert');

Route::get('/mypage/otp_setting', 'MypageController@otp_setting')->name('mypage.otp_setting');

Route::post('/mypage/otp_setting_register', 'MypageController@otp_setting_register')->name('mypage.otp_setting_register');

Route::get('/mypage/alarm_setting', 'MypageController@alarm_setting')->name('mypage.alarm_setting');

Route::post('/mypage/alarm_setting_update', 'MypageController@alarm_setting_update')->name('mypage.alarm_setting_update');

Route::group(['middleware' => ['simulated']], function(){
    Route::get('/mypage/lock_reward/{coin?}', 'MypageController@lock_reward')->name('mypage.lock_reward');

    Route::post('/mypage/lock_reward_update/{coin}', 'MypageController@lock_reward_update')->name('mypage.lock_reward_update');
});

Route::get('/mypage/password_change', 'MypageController@password_change')->name('mypage.password_change');

Route::post('/mypage/password_change_update', 'MypageController@password_change_update')->name('mypage.password_change_update');

Route::get('/mypage/security_setting', 'MypageController@security_setting')->name('mypage.security_setting');

Route::post('/nicecheck/checkplus_success', 'MypageController@checkplus_success')->name('checkplus_success');

Route::post('/nicecheck/checkplus_fail', 'MypageController@checkplus_fail')->name('checkplus_fail');

Route::post('/mypage/security_setting_document', 'MypageController@security_setting_document')->name('mypage.security_setting_document');

Route::post('/mypage/security_setting_account', 'MypageController@security_setting_account')->name('mypage.security_setting_account');

Route::get('/mypage/member_out', 'MypageController@member_out')->name('mypage.member_out');

Route::post('/mypage/member_secession', 'MypageController@member_secession')->name('mypage.member_secession');

Route::get('/mypage/event_coupon', 'MypageController@event_coupon')->name('mypage.event_coupon');

Route::get('/otp', 'OtpController@index')->name('otp_input');

Route::get('/game/game_1', 'GameController@index')->name('game_ch');

Route::get('/convert', 'ConvertReward@index');

Route::resource('/cs_etc', 'CsetcController');

Route::resource('/press', 'PressController');

Route::resource('/social_trade', 'SocialtradeController');

Route::resource('/store', 'StoreController');

Route::resource('/comunity', 'ComunityController');

Route::post('/comunity/{id}', 'ComunityController@show');

Route::get('/guide/guide_cash', 'GuideController@guide_cash');

Route::get('/guide/guide_coin', 'GuideController@guide_coin');

Route::get('/guide/guide_trade', 'GuideController@guide_trade');

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
//Route::group(['middleware' => ['simulated']], function(){
    Route::post('/buysell_coin_data_new', 'MarketBtcController@buysell_coin_data');

    Route::post('/refresh_user_data_new', 'MarketBtcController@refresh_user_data');

    Route::post('/refresh_user_readyorder_new', 'MarketBtcController@refresh_user_readyorder');

    Route::post('/refresh_user_history_new', 'MarketBtcController@refresh_user_history');

    Route::post('/trade_cancel_new', 'MarketBtcController@trade_cancel');

    Route::post('/sess_id_new', 'MarketBtcController@sess_id');

    Route::post('/refresh_user_asset', 'MarketBtcController@refresh_user_asset');
//});



//register_ajax
Route::post('/email/duplicate', 'Ajax\RegisterAjaxController@email_duplicate');

Route::post('/mobile/verify', 'Ajax\RegisterAjaxController@mobile_verify');

Route::post('/mobile/verify/confirm', 'Ajax\RegisterAjaxController@mobile_verify_confirm');

Route::get('/duplicate/{column}/{value}', 'Ajax\RegisterAjaxController@duplicate');



//security_lv_ajax
Route::post('/mobile/existing_user_verify', 'Ajax\SecurityAjaxController@existing_user_mobile_verify');

Route::post('/mobile/existing_user_verify/confirm', 'Ajax\SecurityAjaxController@existing_user_mobile_verify_confirm');


//trans_wallet_ajax
//Route::group(['middleware' => ['simulated']], function(){
    Route::post('/refresh_trans_wallet', 'Ajax\TranswalletAjaxController@refresh_trans_wallet');

    Route::post('/exchangeCashCoin', 'Ajax\TranswalletAjaxController@exchangeCashCoin');

    Route::post('/select_coin', 'Ajax\TranswalletAjaxController@select_coin');

    Route::post('/check_address', 'Ajax\TranswalletAjaxController@check_address');

    Route::post('/withdraw_max_amt', 'Ajax\TranswalletAjaxController@withdraw_max_amt');

    Route::post('/withdraw_onkey_amt', 'Ajax\TranswalletAjaxController@withdraw_onkey_amt');

    Route::post('/send_transaction', 'Ajax\TranswalletAjaxController@send_transaction');

    Route::post('/refresh_withdraw_history', 'Ajax\TranswalletAjaxController@refresh_withdraw_history');

    Route::post('/refresh_erc_eth_balance', 'Ajax\TranswalletAjaxController@refresh_erc_eth_balance');

    Route::post('/cash_deposite', 'Ajax\TranswalletAjaxController@cash_deposite');

    Route::post('/cash_withdraw', 'Ajax\TranswalletAjaxController@cash_withdraw');

    Route::post('/cash_cancel', 'Ajax\TranswalletAjaxController@cash_cancel');
//});




//My_Asset_ajax
Route::post('/show_more_history', 'Ajax\MyAssetAjaxController@show_more_history');

Route::post('/search_date_history', 'Ajax\MyAssetAjaxController@search_date_history');

Route::post('/show_more_not_concluded', 'Ajax\MyAssetAjaxController@show_more_not_concluded');

Route::post('/refresh_not_concluded', 'Ajax\MyAssetAjaxController@refresh_not_concluded');




//lock_reward_ajax
Route::group(['middleware' => ['simulated']], function(){
    Route::post('/history_view_more', 'Ajax\LockRewardAjaxController@history_view_more');

    Route::post('/dividend_view_more', 'Ajax\LockRewardAjaxController@dividend_view_more');
});




//ico_ajax
Route::post('/ico/buy_asset/{pr_id}', 'Ajax\IcoAjaxController@buy_asset');

Route::post('/ico/list/more1', 'Ajax\IcoAjaxController@ico_list_more1');

Route::post('/ico/list/more2', 'Ajax\IcoAjaxController@ico_list_more2');

Route::post('/ico/history/more', 'Ajax\IcoAjaxController@ico_history_more');




//p2p_ajax
Route::group(['middleware' => ['simulated']], function(){
    Route::post('/p2p/history/more', 'Ajax\P2pAjaxController@history_more');

    Route::post('/p2p/list/more', 'Ajax\P2pAjaxController@list_more');

    Route::post('/mobile/p2p/list/more', 'Ajax\P2pAjaxController@mobile_list_more');

    Route::post('/mobile/p2p/onprogress/more', 'Ajax\P2pAjaxController@mobile_p2p_onprogress');

    Route::post('/mobile/p2p/history/more', 'Ajax\P2pAjaxController@mobile_history_more');
});



//comunity_ajax
Route::post('/api/comunity/secret_key', 'Ajax\ComunityController@secret_key_confirm');

Route::resource('/api/comunity', 'Ajax\ComunityController', ['as' => 'ajax']);

Route::post('/Ckfinder/image_upload', 'CkfinderController@image_upload');





//pop_up
Route::post('/nomore/popup', 'AjaxController@popup');




//custom_center
Route::post('/notice/more', 'Ajax\CustomController@notice');

Route::post('/newsflash/more', 'Ajax\CustomController@newsflash');

Route::post('/event/more', 'Ajax\CustomController@event');

Route::post('/qna/more', 'Ajax\CustomController@qna');




//계좌인증
Route::post('/account/certify', 'Ajax\MypageController@security_account_certify');

Route::post('/account/certify/confirm', 'Ajax\MypageController@security_account_confirm');



//API
Route::post('/oauth/login', 'API\ApiController@login');




//1:1채팅
Route::get('/chat', 'ChatController@index');


//}); //서버 점검 시뮬레이트 태그마감(점검 안할시 주석 하세요.)*/

//머니시그널
Route::post('/moneysignal/new_history', 'MoneySignalController@new_history');


//스몰차트, 미들차트
Route::get('/chartdata', 'ChartController@index');


//TEST
Route::group(['middleware' => ['simulated']], function(){
    

    Route::get('/test/chart', function () {
        return view('theme.basic.pc.test');
    });

    Route::get('/test/high_chart', function () {
        return view('theme.basic.pc.test.high_chart');
    });

    Route::get('/test/high_chart_main_sm', function () {
        return view('theme.basic.pc.test.high_chart_main_sm');
    });

    Route::get('/test/high_chart_main_md', function () {
        return view('theme.basic.pc.test.high_chart_main_md');
    });

    Route::get('/test/asdf', function () {
        info(print_r($_SERVER, true));
        return "true";
    });
});

//home_youtube
Route::post('/youtube/list', 'Ajax\HomeAjaxController@youtube_list');
