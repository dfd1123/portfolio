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

//if (!App::environment(['local'])) {
//    URL::forceScheme('https');
//}

Route::get('/', 'AppController@app')->name('app');
Route::post('/nicecheck/checkplus_success', 'AppController@checkplus_success')->name('checkplus_success');
Route::post('/nicecheck/checkplus_fail', 'AppController@checkplus_fail')->name('checkplus_fail');

Route::middleware(['localhost'])->group(function () {
    Route::post('/push_order_result', 'PushController@push_order_result');
});
