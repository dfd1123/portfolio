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
}

Route::get('/', function() {
    return redirect('/login');
});
Route::get('/forgotpwd', 'Auth\ForgotPasswordController@showForgotpwdForm')->name('forgotpassword');

Route::get('/email/verify/{token}', 'PassportController@verify_email')->name('verify_email');

Route::group(['as' => 'user_ver.'], function () {
    Route::prefix('user_ver')->group(function () {
        Route::namespace('User_ver')->group(function () {
            Route::group(['middleware' => ['checkapi', 'checkclient']], function() {
                Route::get('/', 'HomeController@index')->name('home');

                Route::resource('estimate_request', 'EstimateController');//->name('estimate_request');

                Route::get('/result_confirm', 'ResultConfirmController@result_confirm')->name('result_confirm');

                Route::get('/request_complete', 'RequestCompleteController@request_complete')->name('request_complete');

                Route::get('/estimate_manage', 'EstimateManageController@estimate_manage')->name('estimate_manage');

                Route::get('/company_page/{kind}', 'CompanyPageController@company_page')->name('company_page');

                Route::get('/construct_status', 'StatusController@construct_status')->name('construct_status');

                Route::get('/ask_estimate', 'AskEstimateController@ask_estimate_list')->name('ask_estimate_list');

                Route::get('/corporation_status/{trd_no}', 'CorporationStatusController@corporation_status')->name('corporation_status');
            }); 
        });
    });
});


Route::group(['as' => 'company_ver.'], function () {
    Route::prefix('company_ver')->group(function () {
        Route::namespace('Company_ver')->group(function () {

            Route::get('/company_regist/{step}', 'RegistController@company_regist')->name('regist');
            
            Route::middleware(['passcookies', 'auth:api', 'checkapi', 'checkagent'])->group(function () {
                
                Route::get('/', 'HomeController@index')->name('home');
            
                Route::get('/detail/{trd_no}', 'HomeController@main2')->name('detail');

                Route::get('/company_construction', 'ConstructionController@company_construction')->name('construction');

                Route::get('/company_const_manage', 'ConstructionController@company_const_manage')->name('const_manage');
                
                Route::get('/company_signup/{step}', 'SignupController@company_signup')->name('signup');
                
                Route::get('/company_login', 'LoginController@company_login')->name('login');

                Route::get('/company_bidding', 'BiddingController@company_bidding')->name('bidding');

                Route::get('/company_bidding_detail', 'BiddingController@company_bidding_detail')->name('biddingdetail');

                Route::get('/company_bidding_regist', 'BiddingController@company_bidding_regist')->name('biddingregist');

                Route::get('/company_myagent', 'MyagentController@company_myagent')->name('myagent');

                Route::get('/company_request', 'RequestController@company_request')->name('request');

                Route::resource('/mypage', 'MypageController');
            });
        });
    });
});

Auth::routes();
