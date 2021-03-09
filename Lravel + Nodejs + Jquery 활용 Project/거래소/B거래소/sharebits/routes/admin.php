<?php
// admin middleware를 사용하고, namespace에 admin.을 추가한다.
Route::group(['as' => 'admin.'], function() {
	// 이 라우팅은 route('admin.main') 으로 접근이 가능해진다
	Route::get('/login', 'AdminController@login') -> name('login');
	
	Route::post('/login', 'AdminController@login_form') -> name('login.form');

	Route::get('/register', 'AdminRegisterController@showRegistrationForm') -> name('register');

	Route::get('/register_agree', 'AdminController@register_agree') -> name('register_agree');

	Route::get('/register_complete', 'AdminController@register_complete') -> name('register_complete');

	Route::post('/register', 'AdminRegisterController@register') -> name('register.form');

	Route::group(['middleware' => 'admin'], function() {
		Route::get('/', 'AdminController@index') -> name('main');

		Route::post('/logout', 'AdminController@logout') -> name('logout');
		
		Route::get('/coin_listing_list', 'AdminController@coin_listing_list') -> name('coin_listing_list');
		
		Route::get('/coin_listing_update/{id}/{active}', 'AdminController@coin_listing_update') -> name('coin_listing_update');
		
		Route::get('/banner_list', 'AdminController@banner_list') -> name('banner_list');
		
		Route::get('/banner_create', 'AdminController@banner_create') -> name('banner_create');
		
		Route::post('/banner_store', 'AdminController@banner_store') -> name('banner_store');
		
		Route::get('/banner_edit/{id}', 'AdminController@banner_edit') -> name('banner_edit');
		
		Route::post('/banner_update/{id}', 'AdminController@banner_update') -> name('banner_update');
		
		Route::get('/banner_delete/{id}', 'AdminController@banner_delete') -> name('banner_delete');
		
		Route::get('/user_list', 'AdminController@user_list') -> name('user_list');

		Route::get('/user_balance_activity', 'AdminController@user_balance_activity') -> name('user_balance_activity');
		
		Route::get('/notify_create/{type}/{country}', 'AdminController@notify_create') -> name('notify_create');
		
		Route::post('/notify_store/{type}', 'AdminController@notify_store') -> name('notify_store');
		
		Route::get('/account_list/{type}', 'AdminController@account_list') -> name('account_list');
		
		Route::get('/document_list/{type}', 'AdminController@document_list') -> name('document_list');
		
		Route::get('/market_edit', 'AdminController@market_edit') -> name('market_edit');
		
		Route::post('/market_update', 'AdminController@market_update') -> name('market_update');
		
		Route::get('/fee_edit', 'AdminController@fee_edit') -> name('fee_edit');

		Route::post('/fee_update', 'AdminController@fee_update') -> name('fee_update');

		Route::get('/recommender_edit', 'AdminController@recommender_edit') -> name('recommender_edit');

		Route::post('/recommender_update', 'AdminController@recommender_update') -> name('recommender_update');

		Route::get('/event_list', 'AdminController@event_list') -> name('event_list');

		Route::get('/event_create', 'AdminController@event_create') -> name('event_create');

		Route::post('/event_store', 'AdminController@event_store') -> name('event_store');

		Route::get('/event_edit/{id}', 'AdminController@event_edit') -> name('event_edit');

		Route::post('/event_update/{id}', 'AdminController@event_update') -> name('event_update');

		Route::get('/event_delete/{id}', 'AdminController@event_delete') -> name('event_delete');

		Route::get('/coin_lock_list', 'AdminController@coin_lock_list') -> name('coin_lock_list');

		Route::get('/coin_lock_action/{id}/{type}', 'AdminController@coin_lock_action') -> name('coin_lock_action');

		Route::get('/coin_lock_create', 'AdminController@coin_lock_create') -> name('coin_lock_create');

		Route::post('/coin_lock_store', 'AdminController@coin_lock_store') -> name('coin_lock_store');

		Route::get('/coin_lock_edit/{id}', 'AdminController@coin_lock_edit') -> name('coin_lock_edit');

		Route::post('/coin_lock_update/{id}', 'AdminController@coin_lock_update') -> name('coin_lock_update');

		Route::get('/coin_tr_list', 'AdminController@coin_tr_list') -> name('coin_tr_list');

		Route::get('/airdrop_list', 'AdminController@airdrop_list') -> name('airdrop_list');

		Route::get('/airdrop_create', 'AdminController@airdrop_create') -> name('airdrop_create');

		Route::post('/airdrop_store', 'AdminController@airdrop_store') -> name('airdrop_store');

		Route::get('/airdrop_edit/{id}', 'AdminController@airdrop_edit') -> name('airdrop_edit');

		Route::post('/airdrop_update/{id}', 'AdminController@airdrop_update') -> name('airdrop_update');

		Route::get('/p2p_list/{type}', 'AdminController@p2p_list') -> name('p2p_list');

		Route::get('/p2p_confirm/{id}', 'AdminController@p2p_confirm') -> name('p2p_confirm');
		
		Route::get('/p2p_detail/{id}', 'AdminController@p2p_detail') -> name('p2p_detail');

		Route::get('/p2p_stop/{id}', 'AdminController@p2p_stop') -> name('p2p_stop');
		
		Route::get('/rights_management_list', 'AdminController@rights_management_list') -> name('rights_management_list');
		
		Route::get('/rights_management_create', 'AdminController@rights_management_create') -> name('rights_management_create');
		
		Route::post('/rights_management_store', 'AdminController@rights_management_store') -> name('rights_management_store');
		
		Route::get('/rights_management_edit/{id}', 'AdminController@rights_management_edit') -> name('rights_management_edit');
		
		Route::post('/rights_management_update/{id}', 'AdminController@rights_management_update') -> name('rights_management_update');
		
		Route::get('/rights_management_delete/{id}', 'AdminController@rights_management_delete') -> name('rights_management_delete');
		
		Route::get('/rights_management_password_edit/{id}', 'AdminController@rights_management_password_edit') -> name('rights_management_password_edit');
		
		Route::post('/rights_management_password_update/{id}', 'AdminController@rights_management_password_update') -> name('rights_management_password_update');

		Route::get('/notice/{country}', 'AdminController@notice_list') -> name('notice_list');

		Route::get('/notice/create/{country}', 'AdminController@notice_create') -> name('notice_create');

		Route::get('/notice/{country}/{id}', 'AdminController@notice_edit') -> name('notice_edit');

		Route::post('/notice/insert', 'AdminController@notice_insert') -> name('notice_insert');

		Route::post('/notice/update/{id}', 'AdminController@notice_update') -> name('notice_update');

		Route::get('/notice/delete/{country}/{id}', 'AdminController@notice_delete') -> name('notice_delete');

		Route::get('/faqs/{country}/{types}', 'AdminController@faq_list') -> name('faq_list');

		Route::get('/faq/create/{country}', 'AdminController@faq_create') -> name('faq_create');

		Route::get('/faq/{country}/{id}', 'AdminController@faq_edit') -> name('faq_edit');

		Route::post('/faq/insert', 'AdminController@faq_insert') -> name('faq_insert');

		Route::post('/faq/update/{id}', 'AdminController@faq_update') -> name('faq_update');

		Route::get('/faq/delete/{country}/{id}', 'AdminController@faq_delete') -> name('faq_delete');

		Route::get('/qna/{country}', 'AdminController@qna_list') -> name('qna_list');

		Route::get('/qna_answer/create/{id}', 'AdminController@qna_answer_create') -> name('qna_answer_create');

		Route::get('/qna_answer/{id}', 'AdminController@qna_answer_edit') -> name('qna_answer_edit');

		Route::post('/qna_answer/insert/{id}', 'AdminController@qna_answer_insert') -> name('qna_answer_insert');

		Route::post('/qna_answer/update/{id}', 'AdminController@qna_answer_update') -> name('qna_answer_update');

		Route::get('/qna/delete/{id}', 'AdminController@qna_delete') -> name('qna_delete');

		Route::get('/trade/trade_history', 'AdminController@trade_history') -> name('trade_history');

		Route::get('/trade/trade_history', 'AdminController@trade_history') -> name('trade_history');

		//동민 추가 new_trade_history -> 거래이력 
		Route::get('/trade/new_trade_history', 'AdminController@new_trade_history') -> name('new_trade_history');

		Route::get('/coin/coin_out_history/{types}', 'AdminController@coin_out_history') -> name('coin_out_history');
		
		Route::get('/deposite_withdraw_list', 'AdminController@deposite_withdraw_list') -> name('deposite_withdraw_list');

		Route::get('/term/{country}', 'AdminController@term_service') -> name('term_service');

		Route::post('/term/update/{id}', 'AdminController@term_service_update') -> name('term_service_update');
		
		Route::get('/ico_list', 'AdminController@ico_list') -> name('ico_list');
		
		Route::get('/ico_confirm/{id}', 'AdminController@ico_confirm') -> name('ico_confirm');
		
		Route::get('/ico_ban/{id}', 'AdminController@ico_ban') -> name('ico_ban');
		
		Route::get('/ico_people_list/{id}', 'AdminController@ico_people_list') -> name('ico_people_list');
	
		Route::get('/popup/list/{country}', 'AdminController@popup_list') -> name('popup_list');
		
		Route::get('/popup/create/{country}', 'AdminController@popup_create') -> name('popup_create');
		
		Route::get('/popup/edit/{id}/{country}', 'AdminController@popup_edit') -> name('popup_edit');
		
		Route::get('/popup/delete/{id}/{country}', 'AdminController@popup_delete') -> name('popup_delete');
		
		Route::post('/popup/insert', 'AdminController@popup_insert') -> name('popup_insert');
		
		Route::post('/popup/update/{id}', 'AdminController@popup_update') -> name('popup_update');
		
		Route::get('/auto_setting', 'AdminController@auto_setting') -> name('auto_setting');
		
		Route::get('/auto_setting_update/{id}/{active}', 'AdminController@auto_setting_update') -> name('auto_setting_update');

		Route::get('/auto_bot_edit/{id}', 'AdminController@auto_bot_edit') -> name('auto_bot_edit');

		Route::post('/auto_bot_update/{id}', 'AdminController@auto_bot_update') -> name('auto_bot_update');

		Route::get('/coin/coin_has_list', 'AdminController@coin_has_list') -> name('coin_has_list');
	
	});
	
	//admin Ajax START 
	
	Route::post('/email_security_change', 'AjaxController@email_security_change');
	
	Route::post('/mobile_security_change', 'AjaxController@mobile_security_change');
	
	Route::post('/google_security_change', 'AjaxController@google_security_change');
	
	Route::post('/user_security_load', 'AjaxController@user_security_load');
	
	Route::post('/user_available_load', 'AjaxController@user_available_load');

	Route::post('/user_status_change', 'AjaxController@user_status_change');
	
	Route::post('/document_agree', 'AjaxController@document_agree');
	
	Route::post('/document_disagree', 'AjaxController@document_disagree');
	
	Route::post('/document_reject_load', 'AjaxController@document_reject_load');
	
	Route::post('/account_agree', 'AjaxController@document_agree');
	
	Route::post('/account_disagree', 'AjaxController@document_disagree');
	
	Route::post('/account_reject_load', 'AjaxController@document_reject_load');

	Route::post('/add_balance_change', 'AjaxController@add_balance_change');

	Route::post('/coin/external_withdraw_confirm', 'AjaxController@External_withdraw_confirm');

	Route::post('/coin/cancel_coin_io', 'AjaxController@cancel_coin_io');

	Route::post('/coin/manual_confirm', 'AjaxController@manual_confirm');

	Route::post('/coin/internal_withdraw_confirm', 'AjaxController@Internal_withdraw_confirm');
	
	Route::post('/user_secession', 'AjaxController@user_secession');
	
	//admin Ajax END 
});
