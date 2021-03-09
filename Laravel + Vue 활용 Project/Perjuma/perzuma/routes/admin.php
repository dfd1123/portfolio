<?php

    if (!App::environment(['local'])) {
        URL::forceScheme('https');
    }

    Route::group(['as' => 'admin.'], function () {   // namespace에 admin.을 추가한다.

        Route::get('/login', 'Admin\AuthController@login')->name('login');

        Route::post('/login_attemp', 'Admin\AuthController@login_attemp')->name('login_attemp');

        Route::post('/admin_reigst', 'Admin\AuthController@admin_store')->name('admin_regist');

        Route::group(['middleware' => 'admin'], function () {   // admin middleware를 사용하여 로그인된 사용자만 접근 가능
            //관리자 View route
            Route::resource('/{page?}', 'Admin\WebController')->only(['index']);

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
            //FCM
            Route::resource('fcm', 'FcmController');
            // AJAX API END
            
        });
    });
