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


 
Route::get('unauthorized', function() {
    return response()->json([
        'status' => 'error',
        'message' => 'Unauthorized'
    ], 401);
})->name('api.jwt.unauthorized');

Route::post('login', 'JWTAuthController@login')->name('api.jwt.login');

Route::post('register', 'JWTAuthController@register')->name('api.jwt.register');

Route::post('social/register', 'JWTAuthController@social_register')->name('api.jwt.social_register');

Route::resource('itemhit','ItemHitController');

Route::post('payple/auth','PaypleController@auth'); //카드 결제후 부를 api
Route::post('payple/pay_mobile','PaypleController@pay_mobile'); //정기결제후 부를 api
Route::post('payple/order_mobile','PaypleController@order_mobile'); //주문
// Route::group(['middleware' => 'apiauthcheck'], function(){
Route::group(['middleware' => 'auth:api'], function(){

    Route::get('user', 'JWTAuthController@user')->name('api.jwt.user');

    Route::get('refresh', 'JWTAuthController@refresh')->name('api.jwt.refresh');

    Route::get('logout', 'JWTAuthController@logout')->name('api.jwt.logout');

    Route::put('info_change', 'JWTAuthController@info_change')->name('api.jwt.info_change');

    Route::put('profileimgchange', 'JWTAuthController@ProfileImgChange')->name('api.jwt.profileImgChange');

    Route::put('native_token', 'JWTAuthController@native_token')->name('api.jwt.native_token');

    Route::resource('event','EventController')->except(['show']);
    Route::resource('items','ItemController')->except(['show']);
    Route::resource('category','CategoryController')->except(['show']);
    Route::resource('color','ColorController')->except(['index', 'show']);
    Route::resource('review','ReviewController')->except(['show']);
    Route::resource('cart','CartController');
    Route::resource('order','OrderController');
    Route::resource('wish','WishController');
    Route::resource('sellerlike','SellerLikeController');
    Route::resource('delivery','DeliveryController');
    Route::resource('review_comment','ReviewCommentController')->except(['show']);
    Route::resource('company', 'CompanyController')->except(['show']);
    Route::resource('coupon', 'CouponController');
    Route::resource('couponzone', 'CouponZoneController');
    Route::resource('couponuselog', 'CouponUseLogController');
    Route::resource('notification', 'NotificationController');	
    Route::resource('notice','NoticeController')->except(['show']);	
    Route::resource('faq','FaqController')->except(['show']);
    Route::resource('store_qna','StoreQnaController');
    Route::resource('qna','QnaController');
    Route::resource('mypage','MypageController');

    
    Route::post('payple/pay','PaypleController@pay'); //정기결제후 부를 api
    Route::post('payple/order','PaypleController@order'); //주문
    Route::post('payple/terminate','PaypleController@terminate'); //해지
    
    

});

Route::group(['prefix' => 'store', 'middleware' => 'auth:api'], function(){
    Route::resource('order','Store\OrderController');
    Route::resource('item_qna','Store\ItemQnaController');
    Route::resource('store_qna','Store\StoreQnaController');
    Route::resource('review','Store\ReviewController');
    Route::resource('store','Store\StoreController');
    Route::resource('notice','Store\NoticeController');
    Route::resource('faq','Store\FaqController');
    Route::resource('items','Store\ItemController');
    Route::resource('qna','Store\QnaController');
    Route::resource('calculate','Store\CalculateController');
    Route::resource('review_comment','Store\ReviewCommentController');
});

Route::get('/sms/send','SmsController@index');
Route::resource('event','EventController')->only(['show']);
Route::resource('popular','PopularController');
Route::resource('review','ReviewController')->only(['show']);
Route::resource('review_comment','ReviewCommentController')->only(['show']);
Route::resource('color','ColorController')->only(['index', 'show']);
Route::resource('items','ItemController')->only(['show']);
Route::resource('users','UserController');
Route::resource('style','StyleController');
Route::resource('category','CategoryController')->only(['show']);
Route::resource('seller','SellerController');

Route::resource('item_qna','ItemQnaController');
Route::resource('banner','BannerController');
Route::resource('notice','NoticeController')->only(['show']);
Route::resource('faq','FaqController')->only(['show']);
Route::resource('company', 'CompanyController')->only(['show']);

Route::resource('pay','PayController');
Route::resource('naverpay','NaverPayController');

Route::post('/Ckfinder/image_upload', 'CkfinderController@image_upload');
Route::post('/CkCustom/image_upload', 'CkCustomController@image_upload');

Route::resource('popup','PopupController');

Route::group(['middleware' => ['web']], function () {
    Route::get('facebook', [
        'as'   => 'facebook.login',
        'uses' => 'FacebookController@redirectToProvider'
    ]);
    Route::get('facebook/callback', [
        'as'   => 'facebook.callback',
        'uses' => 'FacebookController@handleProviderCallback'
    ]);
    Route::get('facebook/secession', [
        'as'   => 'facebook.secession',
        'uses' => 'FacebookController@secession'
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
    Route::get('kakao/secession', [
        'as'   => 'kakao.secession',
        'uses' => 'KakaoController@secession'
    ]);

    /* 가맹점 소셜 로그인/회원가입 */
    Route::get('store/facebook', [
        'as'   => 'facebook.login',
        'uses' => 'FacebookController@storeRedirectToProvider'
    ]);
    Route::get('store/facebook/callback', [
        'as'   => 'facebook.callback',
        'uses' => 'FacebookController@storeHandleProviderCallback'
    ]);

    Route::get('store/naver', [
        'as'   => 'naver.login',
        'uses' => 'NaverController@storeRedirectToProvider'
    ]);
    Route::get('store/naver/callback', [
        'as'   => 'naver.callback',
        'uses' => 'NaverController@storeHandleProviderCallback'
    ]);

    Route::get('store/kakao', [
        'as'   => 'kakao.login',
        'uses' => 'KakaoController@storeRedirectToProvider'
    ]);
    Route::get('store/kakao/callback', [
        'as'   => 'kakao.callback',
        'uses' => 'KakaoController@storeHandleProviderCallback'
    ]);
});
