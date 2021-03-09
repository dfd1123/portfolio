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

if (!App::environment(['local'])) {
    URL::forceScheme('https');
}

Route::resource('User', 'UserController');

Route::get('/user_as', function () {
    $user = auth()->user()->check();
    return response()->json($user);
})->middleware('auth:api');

Route::get('/user_no', function () {
    $user_no = auth()->user()->user_no;
    return response()->json($user_no);
})->middleware('auth:api');

Route::post('/login', 'PassportController@login');
Route::get('/logout', 'PassportController@logout');
Route::post('/register', 'PassportController@register');
Route::post('/check_email', 'PassportController@check_email');

//Route::get('disable/passcookie', 'PassportController@disable_passcookie');

Route::middleware('auth:api')->group(function () {
    Route::get('/logout', 'PassportController@logout');
    Route::post('/refresh', 'PassportController@refresh');
    Route::get('/heartbeat', 'PassportController@heartbeat');
    Route::get('/unregist', 'PassportController@unregist');
    //Route::get('/user/as', 'PassportController@auth_infor');
    
    Route::namespace('User_ver')->group(function () {
        

        Route::get('/recomend_company', 'HomeController@recomend_company');
       
        //스텝마다 컨트롤러 달린거 통합
        Route::resource('estimate_request', 'EstimateController');

        Route::post('/result_confirm', 'ResultConfirmController@data_store');// ->estimate_request의 step7로 통합
        Route::post('/result_complete', 'RequestCompleteController@data_load');

        Route::post('/estimate_manage', 'EstimateManageController@data_load');

        Route::post('/estimate_view', 'CompanyPageController@estimate_view_data_load');
        Route::post('/review_view', 'CompanyPageController@review_view_data_load');
        Route::put('/construction_contract', 'CompanyPageController@construction_contract');

        Route::get('/ask_estimate_list/{req}', 'AskEstimateController@data_load');

        Route::get('/construct_status/{req}', 'StatusController@data_load');

        Route::get('/message/{req}', 'MessageController@data_load');

        Route::resource('/user_ver/comment', 'CommentController');

        Route::resource('/user_ver/review', 'ReviewController');

    });
    // AJAX API START
    //유저 and 업체
    Route::resource('user', 'UserController');
    //거래
    Route::resource('trade', 'TradeController');
    //코멘트
    Route::resource('comment', 'CommentController');
    //매니저
    Route::resource('manager', 'ManagerController');
    //감리
    Route::resource('supervison', 'SupervisonController');
    //게시판
    Route::resource('bbs', 'BbsController');
    //공지사항
    Route::resource('notice', 'NoticeController');
    //메세지
    Route::resource('message', 'MessageController');
    //리뷰
    Route::resource('review', 'ReviewController');
    //log
    Route::resource('log', 'LogController');
    //업종
    Route::resource('bllist', 'BlController');
    //escrow
    Route::resource('escrow', 'EscrowController');
    //CS메모
    Route::resource('memo', 'MemoController');
    //업체 정보
    Route::resource('agentinfo', 'AgentinfoController');

    Route::resource('/company_ver/comment', 'Company_ver\AgentCommentController');
    // AJAX API END

});


