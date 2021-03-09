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

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::post('/auth/checkplus_main', 'Auth\RegisterController@checkplus_main')->name('auth.checkplus_main');

Route::post('/auth/checkplus_success', 'Auth\RegisterController@checkplus_success')->name('auth.checkplus_success');

Route::post('/auth/checkplus_fail', 'Auth\RegisterController@checkplus_fail')->name('auth.checkplus_fail');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('notice', 'NoticesController@list')->name('notice.list');

Route::get('notices/delete/{id}', 'NoticesController@delete')->name('notices.delete');

Route::get('sel_product/{ca_id}', 'CategorysController@sel_product')->name('product_list.sel_product');

Route::get('bat_product/{id}', 'CategorysController@bat_product')->name('product_list.bat_product');

//Route::get('products/{ca_id}', 'ProductsController@product_cali')->name('products.product_cali');

Route::get('/products/batting/{ca_id}/{status}', 'ProductsController@batting_list')->name('product.batting_list');

Route::get('/products/search_list/{ca_id}', 'ProductsController@search_list')->name('products.search_list');

Route::post('/product/{id}/updates', 'ProductsController@updates')->name('product.updates');

Route::get('/product/{id}/delete', 'ProductsController@delete')->name('product.delete');

Route::get('orders/{order_id}', 'OrdersController@create')->name('orders.create');

Route::resource('notices', 'NoticesController');

Route::resource('products', 'ProductsController');

Route::resource('categorys', 'CategorysController');

Route::post('reviews/{id}', 'ReviewsController@storet')->name('reviews.storet');

Route::resource('reviews', 'ReviewsController');

Route::get('gallery/{id}', 'GalleryController@view')->name('gallery.show');

Route::resource('orders', 'OrdersController');

Route::get('order/complete', 'OrdersController@complete')->name('orders.complete');

Route::get('mypage/password_edit', 'MypageController@password_edit')->name('mypage.password_edit');

Route::post('mypage/password_update', 'MypageController@password_update')->name('mypage.password_update');

Route::get('mypage/myinfor_edit', 'MypageController@myinfor')->name('mypage.myinfor');

Route::post('mypage/myinfor_edit', 'MypageController@myinfor_update')->name('mypage.myinfor_update');

Route::get('mypage/myart_list', 'MypageController@myart_list')->name('mypage.myart_list');

Route::get('mypage/mybatting_list', 'MypageController@mybatting_list')->name('mypage.mybatting_list');

Route::get('mypage/cart', 'MypageController@cart')->name('mypage.cart');

Route::post('mypage/cart/delete', 'MypageController@cart_delete')->name('mypage.cart_delete');

Route::get('mypage/my_order_list', 'MypageController@my_order_list')->name('mypage.my_order_list');

Route::get('mypage/my_sale_list', 'MypageController@my_sale_list')->name('mypage.my_sale_list');

Route::get('mypage/insert_delivery', 'MypageController@insert_delivery')->name('mypage.insert_delivery');

Route::get('mypage/my_comment_list', 'MypageController@my_comment_list')->name('mypage.my_comment_list');

Route::post('/mypage/order/order_cancel', 'MypageController@order_cancel')->name('mypage.order_cancel');

Route::post('/mypage/change/profile_img', 'MypageController@profile_img_change')->name('mypage.profile_img_change');

Route::get('mypage/account_edit', 'MypageController@account_edit')->name('mypage.account_edit');

Route::post('/mypage/account_update', 'MypageController@account_update')->name('mypage.account_update');

Route::get('/howtouse', 'MypageController@howtouse')->name('howtouse.howtouse');

Route::get('/order/bil/{order_id}', 'BilController@show')->name('order.bil');

Route::get('/mobile_mypage', 'MypageController@mobile_mypage')->name('mypage.mobile_mypage');

// Route::get('/coin_charge', 'CoinController@coin_charge')->name('coin.coin_charge');

Route::get('/coin_deposit', 'CoinController@coin_deposit')->name('coin.coin_deposit');

Route::get('/coin_edit', 'CoinController@coin_edit')->name('coin.coin_edit');

// Route::get('/coin_list', 'CoinController@coin_list')->name('coin.coin_list');

Route::resource('events', 'EventsController');

Route::get('/faq', 'FaqController@index')->name('faq.list');

Route::get('/policy/{id}', 'PolicyController@policy')->name('policy.policy');

Route::get('testtlc', 'Testtlc@index');
Route::post('testtlc/create', 'Testtlc@store')->name('testtlc.create');

/* popup */
Route::post('/nomore/popup', 'AjaxController@popup');

/* Ajax 라우팅 */
Route::get('review_test', 'AjaxController@review_test');
Route::post('/review/store', 'AjaxController@review_store');
Route::post('/review/store2', 'AjaxController@review_store2');
Route::post('user/level/change', 'AjaxController@user_level');
Route::post('/adm/category/name/change', 'AjaxController@category_name_change');
Route::post('/adm/category/sm_name/change', 'AjaxController@category_sm_name_change');
Route::post('/adm/category/discript/change', 'AjaxController@category_discript_change');
Route::post('/adm/category/status/change', 'AjaxController@category_status_change');
Route::post('/adm/category/image/change', 'AjaxController@category_image_change');
Route::post('/batting/do', 'AjaxController@batting_do');
Route::post('/batting/edit', 'AjaxController@batting_edit');
Route::post('/batting/load', 'AjaxController@batting_load');
Route::post('/batting/cancel', 'AjaxController@batting_cancel');
Route::post('/cart/insert', 'AjaxController@cart_insert');
Route::post('/cart/delete', 'AjaxController@cart_delete');
Route::post('/refund/change', 'AjaxController@refund_change');
Route::post('/refund/cancel', 'AjaxController@refund_cancel');
Route::post('/video/title/change', 'AjaxController@video_title_change');
Route::post('/video/link/change', 'AjaxController@video_link_change');
Route::post('/video/use/change', 'AjaxController@video_use_change');
Route::post('/banner/file/change', 'AjaxController@banner_file_change');
Route::post('/banner/file/delete', 'AjaxController@banner_file_delete');
Route::post('/banner/time/change', 'AjaxController@banner_time_change');
Route::post('/mypage/mypage_commnet_show', 'AjaxController@mypage_commnet_show');
Route::post('/mypage/mypage_comment_edit', 'AjaxController@mypage_comment_edit');
Route::post('/mypage/mypage_comment_delete', 'AjaxController@mypage_comment_delete');
Route::post('/mypage/mypage_expert_commnet_show', 'AjaxController@mypage_expert_commnet_show');
Route::post('/mypage/mypage_expert_comment_edit', 'AjaxController@mypage_expert_comment_edit');
Route::post('/mypage/mypage_expert_comment_delete', 'AjaxController@mypage_expert_comment_delete');
Route::post('/mypage/mobile/mypage_my_info', 'AjaxController@mobile_mypage_my_info');
Route::post('/mypage/mobile/mypage_my_info_update', 'AjaxController@mobile_mypage_my_info_update');
Route::post('/mypage/mobile/mypage_product', 'AjaxController@mobile_mypage_product');
Route::post('/mypage/mobile/mypage_product_delete', 'AjaxController@mobile_mypage_product_delete');
Route::post('/mypage/mobile/mypage_batting', 'AjaxController@mobile_mypage_batting');
Route::post('/mypage/mobile/mypage_cart', 'AjaxController@mobile_mypage_cart');
Route::post('/mypage/mobile/mypage_cart_delete', 'AjaxController@mobile_mypage_cart_delete');
Route::post('/mypage/mobile/mypage_buy_list', 'AjaxController@mobile_mypage_buy_list');
Route::post('/mypage/mobile/mypage_buy_cancel', 'AjaxController@mobile_mypage_buy_cancel');
Route::post('/mypage/mobile/mypage_sell_list', 'AjaxController@mobile_mypage_sell_list');
Route::post('/mypage/mobile/mypage_comment_list', 'AjaxController@mobile_mypage_comment_list');
Route::post('/mypage/mobile/mypage_comment_delete', 'AjaxController@mobile_mypage_comment_delete');
Route::post('/mypage/mobile/mypage_expertreview_list', 'AjaxController@mobile_mypage_expertreview_list');
Route::post('/mypage/mobile/mypage_expertreview_edit', 'AjaxController@mobile_mypage_expertreview_edit');
Route::post('/mypage/mobile/mypage_expertreview_delete', 'AjaxController@mobile_mypage_expertreview_delete');
Route::post('/mypage/mobile/mypage_insert_delivery', 'AjaxController@mobile_mypage_insert_delivery');
Route::post('/mypage/mobile/product_list', 'AjaxController@mobile_product_list');
Route::post('/mypage/mobile/delivery_company_list', 'AjaxController@mobile_delivery_company_list');
Route::post('/review/recomend', 'AjaxController@recomend');
Route::post('/review/unrecomend', 'AjaxController@unrecomend');
Route::post('/certify/email', 'AjaxController@email_certify');
Route::post('/certify/nickname', 'AjaxController@nickname_certify');
Route::post('/certify/mobile', 'AjaxController@mobile_certify');
Route::post('/sel_product/more', 'AjaxController@more_sel_product');
Route::post('/search_product/more', 'AjaxController@more_search_product');
Route::post('/bat_product/more', 'AjaxController@more_bat_product');
Route::post('/address/valid', 'AjaxController@address_valid');
Route::post('/address/maximum', 'AjaxController@address_maximum');
Route::post('/address/send', 'AjaxController@address_send');
Route::post('/address/refresh', 'AjaxController@address_refresh');
Route::post('/address/infinity', 'AjaxController@address_infinity');
Route::post('/balance/refresh', 'AjaxController@balance_refresh');
Route::post('/charge/refresh', 'AjaxController@charge_refresh');
Route::post('/charge/infinity', 'AjaxController@charge_infinity');
Route::post('/charge/order', 'AjaxController@charge_order');
Route::post('/charge/buy', 'AjaxController@charge_buy');
Route::post('/mypage/order/view_cancel_reason', 'AjaxController@view_cancel_reason');
Route::post('/mypage/order/view_delivery', 'AjaxController@view_delivery');
Route::post('/product/img/delete', 'AjaxController@product_img_delete');
Route::post('/slide/file/change', 'AjaxController@slide_file_change');
Route::post('/normal_review/more', 'AjaxController@normal_review_more');
Route::post('/expert_review/more', 'AjaxController@expert_review_more');
Route::post('/fee/product/change', 'AjaxController@fee_product_change');
Route::post('/fee/withdraw/change', 'AjaxController@fee_withdraw_change');
Route::post('/fee/email/change', 'AjaxController@fee_email_change');
Route::post('/fee/withdraw', 'AjaxController@fee_withdraw');
Route::post('/order/confirm', 'AjaxController@order_confirm');
Route::post('/result_calculate/confirm', 'AjaxController@result_confirm');
Route::post('/balance/refresh_user', 'AjaxController@balance_refresh_user');
Route::post('/balance/add_user', 'AjaxController@balance_add_user');
Route::post('/mobile/notice/more', 'AjaxController@notice_more');
Route::post('/mobile/event/more', 'AjaxController@event_more');
Route::post('/adm/admin_user_level_change', 'AjaxController@admin_user_level_change');
Route::post('/admin/idconfirm','AjaxController@admin_valid');
Route::post('/user_delete','AjaxController@user_delete');
/* Ajax 라우팅  End */





Route::get('github', [
        'as'   => 'github.login',
        'uses' => 'Auth\LoginController@redirectToProvider'
]);
Route::get('github/callback', [
        'as'   => 'github.callback',
        'uses' => 'Auth\LoginController@handleProviderCallback'
]);
Route::get('naver', [
        'as'   => 'naver.login',
        'uses' => 'NaverController@redirectToProvider'
]);
Route::get('naver/callback', [
        'as'   => 'naver.callback',
        'uses' => 'NaverController@handleProviderCallback'
]);
Route::get('kakao', [
        'as'   => 'kakao.login',
        'uses' => 'KakaoController@redirectToProvider'
]);
Route::get('kakao/callback', [
        'as'   => 'kakao.callback',
        'uses' => 'KakaoController@handleProviderCallback'
]);
Route::post('adm/adm_login', 'Auth\LoginController@adm_login')->name('adm_login');