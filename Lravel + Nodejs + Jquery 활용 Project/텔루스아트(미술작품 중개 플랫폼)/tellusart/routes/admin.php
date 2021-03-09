<?php

/* Admin START */
Route::group(['as' => 'admin.'], function() {

    Route::get('/login', 'AdminController@login')->name('login');

    Route::post('/login_form', 'AdminController@login_form') -> name('login.form');

    Route::group(['middleware' => 'admin'], function() {
    
        Route::get('', 'AdminController@index')->name('main');

        Route::post('/logout', 'AdminController@logout')->name('logout');

        Route::get('/company', 'AdminController@company')->middleware('adminlevel:1')->name('company');

        Route::post('/company', 'AdminController@company_update')->middleware('adminlevel:1')->name('company_update');

        Route::get('/user_list/delete/{id}', 'AdminController@user_delete')->middleware('adminlevel:2')->name('user_delete');

        Route::get('/user_list', 'AdminController@user_search')->middleware('adminlevel:4')->name('user_list');

        Route::get('/category_list', 'AdminController@category_list')->middleware('adminlevel:4')->name('category_list');

        Route::get('/category_create', 'AdminController@category_create')->middleware('adminlevel:4')->name('category_create');

        Route::post('/category_update', 'AdminController@category_update')->middleware('adminlevel:4')->name('category_update');

        Route::get('/category_delete/{id}', 'AdminController@category_delete')->middleware('adminlevel:4')->name('category_delete');

        Route::get('/product_list/{sell_yn}', 'AdminController@product_list')->middleware('adminlevel:4')->name('product_list');

        Route::post('/sell_yn_change', 'AdminController@sell_yn_change')->middleware('adminlevel:4')->name('sell_yn_change');

        Route::get('/batting_product/{batting_yn}', 'AdminController@batting_product')->middleware('adminlevel:2')->name('batting_product');

        Route::get('/this_week_batting', 'AdminController@this_week_batting')->middleware('adminlevel:2')->name('week_batting');

        Route::get('/batting_list', 'AdminController@batting_list')->middleware('adminlevel:2')->name('batting_list');

        Route::get('/batting_winner', 'AdminController@batting_winner')->middleware('adminlevel:2')->name('batting_winner');

        Route::get('/order_list', 'AdminController@order_list')->middleware('adminlevel:4')->name('order_list');
		
		Route::get('/order_deposite/{id}', 'AdminController@order_deposite')->middleware('adminlevel:4')->name('order_deposite');
		
		Route::get('/order_refund/{id}', 'AdminController@order_refund')->middleware('adminlevel:4')->name('order_refund');
		
		Route::get('/order_delivery/{id}', 'AdminController@order_delivery')->middleware('adminlevel:4')->name('order_delivery');

        Route::get('/event_list/{state}', 'AdminController@event_list')->middleware('adminlevel:5')->name('event_list');

        Route::get('/event_create', 'AdminController@event_create')->middleware('adminlevel:5')->name('event_create');

        Route::get('/event_show/{id}', 'AdminController@event_show')->middleware('adminlevel:5')->name('event_show');

        Route::post('/event_store', 'AdminController@event_store')->middleware('adminlevel:5')->name('event_store');

        Route::get('/event_delete/{id}', 'AdminController@event_delete')->middleware('adminlevel:5')->name('event_delete');

        Route::get('/event_edit/{id}', 'AdminController@event_edit')->middleware('adminlevel:5')->name('event_edit');

        Route::post('/event_update/{id}', 'AdminController@event_update')->middleware('adminlevel:5')->name('event_update');

        Route::get('/refund_list', 'AdminController@refund_list')->middleware('adminlevel:4')->name('refund_list');

        Route::get('/video_list', 'AdminController@video_list')->middleware('adminlevel:5')->name('video_list');

        Route::get('/video_create', 'AdminController@video_create')->middleware('adminlevel:5')->name('video_create');

        Route::post('/video_store', 'AdminController@video_store')->middleware('adminlevel:5')->name('video_store');

        Route::get('/video_delete/{id}', 'AdminController@video_delete')->middleware('adminlevel:5')->name('video_delete');

        Route::get('/banner_list', 'AdminController@banner_list')->middleware('adminlevel:5')->name('banner_list');

        Route::get('/past_batting_list/{ca_id}/{bat_cnt}', 'AdminController@past_batting_list')->middleware('adminlevel:3')->name('past_batting_list');

        Route::get('/faq', 'AdminController@faq_list')->middleware('adminlevel:5')->name('faq_list');

        Route::get('/faq/{id}', 'AdminController@faq_show')->middleware('adminlevel:5')->name('faq_show');

        Route::get('/faq_create', 'AdminController@faq_create')->middleware('adminlevel:5')->name('faq_create');

        Route::post('/faq', 'AdminController@faq_insert')->middleware('adminlevel:5')->name('faq_insert');

        Route::get('/faq/{id}/edit', 'AdminController@faq_edit')->middleware('adminlevel:5')->name('faq_edit');

        Route::post('/faq/{id}', 'AdminController@faq_update')->middleware('adminlevel:5')->name('faq_update');

        Route::get('/faq/{id}/delete', 'AdminController@faq_delete')->middleware('adminlevel:5')->name('faq_delete');


        Route::get('/notice', 'AdminController@notice_list')->middleware('adminlevel:5')->name('notice_list');

        Route::get('/notice/{id}', 'AdminController@notice_show')->middleware('adminlevel:5')->name('notice_show');

        Route::get('/notice_create', 'AdminController@notice_create')->middleware('adminlevel:5')->name('notice_create');

        Route::post('/notice', 'AdminController@notice_insert')->middleware('adminlevel:5')->name('notice_insert');

        Route::get('/notice/{id}/edit', 'AdminController@notice_edit')->middleware('adminlevel:5')->name('notice_edit');

        Route::post('/notice/{id}', 'AdminController@notice_update')->middleware('adminlevel:5')->name('notice_update');

        Route::get('/notice/{id}/delete', 'AdminController@notice_delete')->middleware('adminlevel:5')->name('notice_delete');

        Route::get('/privacy', 'AdminController@privacy_edit')->middleware('adminlevel:5')->name('privacy_edit');

        Route::post('/privacy/update', 'AdminController@privacy_update')->middleware('adminlevel:5')->name('privacy_update');

        Route::get('/policy', 'AdminController@policy_edit')->middleware('adminlevel:5')->name('policy_edit');

        Route::post('/policy/update', 'AdminController@policy_update')->middleware('adminlevel:5')->name('policy_update');

        Route::get('/howtouse_edit', 'AdminController@howtouse_edit')->middleware('adminlevel:5')->name('howtouse_edit');

        Route::post('/howtouse/update', 'AdminController@howtouse_update')->middleware('adminlevel:5')->name('howtouse_update');

        Route::get('/howtouse/delete/{img}', 'AdminController@howtouse_delete')->middleware('adminlevel:5')->name('howtouse_delete');

        Route::get('/slide_list', 'AdminController@slide_list')->middleware('adminlevel:5')->name('slide_list');

        Route::get('/slide_create', 'AdminController@slide_create')->middleware('adminlevel:5')->name('slide_create');

        Route::post('/slide_store', 'AdminController@slide_store')->middleware('adminlevel:5')->name('slide_store');

        Route::get('/slide_delete/{id}', 'AdminController@slide_delete')->middleware('adminlevel:5')->name('slide_delete');

        Route::get('/fee_list', 'AdminController@fee_list')->middleware('adminlevel:2')->name('fee_list');

        Route::get('/io_list', 'AdminController@io_list')->middleware('adminlevel:3')->name('io_list');

        Route::get('/result_calculate/{status}', 'AdminController@result_calculate')->middleware('adminlevel:4')->name('result_calculate');

        Route::post('/result_all_confirm', 'AdminController@result_all_confirm')->middleware('adminlevel:4')->name('result_all_confirm');



        Route::get('/popup/list', 'AdminController@popup_list')->middleware('adminlevel:5') -> name('popup_list');
		
		Route::get('/popup/create', 'AdminController@popup_create')->middleware('adminlevel:5') -> name('popup_create');
		
		Route::get('/popup/edit/{id}', 'AdminController@popup_edit')->middleware('adminlevel:5') -> name('popup_edit');
		
		Route::get('/popup/delete/{id}', 'AdminController@popup_delete')->middleware('adminlevel:5') -> name('popup_delete');
		
		Route::post('/popup/insert', 'AdminController@popup_insert')->middleware('adminlevel:5') -> name('popup_insert');
		
        Route::post('/popup/update/{id}', 'AdminController@popup_update')->middleware('adminlevel:5') -> name('popup_update');
        
        Route::get('/batting_set', 'AdminController@batting_set')->middleware('adminlevel:2') -> name('batting_set');

        Route::post('/batting_set_update', 'AdminController@batting_set_update')->middleware('adminlevel:2') -> name('batting_set_update');

        Route::get('/admin_user_list', 'AdminController@admin_user_list') -> name('admin_user_list');

        Route::get('/admin_user_create', 'AdminController@admin_user_create') -> name('admin_user_create');

        Route::post('/admin_user_store', 'AdminController@admin_user_store') -> name('admin_user_store');

        Route::get('/admin_user_password_edit/{id}', 'AdminController@admin_user_password_edit') -> name('admin_user_password_edit');

        Route::post('/admin_user_password_change/{id}', 'AdminController@admin_user_password_change') -> name('admin_user_password_change');

        Route::get('/admin_user_delete', 'AdminController@admin_user_delete') -> name('admin_user_delete');
    });
});
//* Admin End */