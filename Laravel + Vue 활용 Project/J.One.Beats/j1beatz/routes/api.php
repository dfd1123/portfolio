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
} else {
    URL::forceScheme('http');
}


Route::post('login', 'PassportController@login');
Route::post('register', 'PassportController@register');

Route::post('email/check/duplicate', 'PassportController@check_email_duplicate');
Route::post('mobile/auth', 'PayletterController@store');
Route::post('password/find/request', 'PassportController@password_find_request');
Route::post('password/find/verify', 'PassportController@password_find_verify');


Route::apiResources([
    'banners' => 'BannerController',
    'moods' => 'MoodController',
    'producers' => 'ProducerController',
    'categorys' => 'CategoryController',
    'beats' => 'BeatController',
    'comments' => 'CommentController',
    'licenses' => 'LicenseController',
    'notices' => 'NoticeController',
    'faqs' => 'FaqController',
    'terms' => 'TermsController'
]);

Route::middleware([/*'passcookie', */'auth:api'])->group(function () {
    Route::post('logout', 'PassportController@logout');
    Route::post('refresh', 'PassportController@refresh');
    Route::get('heartbeat', 'PassportController@heartbeat');

    Route::get('info', 'PassportController@info');
    Route::post('info', 'PassportController@info_change');
    Route::post('register_producer', 'PassportController@register_producer');
    Route::post('leave_producer', 'PassportController@leave_producer');

    Route::post('request_url', 'PassportController@request_url');

    Route::apiResources([
        'payletter'=> 'PayletterController',
        'tests' => 'TestController',
        'mood_selects' => 'MoodSelectController',
        'playlists' => 'PlaylistController',
        'beat_likes' => 'BeatLikeController',
        'follows' => 'FollowController',
        'carts' => 'CartController',
        'alarms' => 'AlarmController',
        'playfolders' => 'PlayfolderController',
        'beatorders' => 'BeatOrderController',
        'qnas' => 'QnaController',
        'license_orders' => 'LicenseOrderController'
    ]);
});
