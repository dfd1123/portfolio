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

Route::post('login', 'AuthController@login');

Route::middleware(['auth:user'])->group(function () {
    Route::get('detail', 'AuthController@detail');
    
    Route::prefix('cp_test_templates')->group(function () {
        Route::get('/', 'CpTestTemplateController@index');
    });

    Route::prefix('user_cp_tests')->group(function () {
        Route::get('/', 'UserCpTestController@index');
        Route::post('/', 'UserCpTestController@store');
    });

    Route::prefix('user_cp_test_results')->group(function () {
        Route::get('/{id}', 'UserCpTestResultController@show');
        Route::get('/', 'UserCpTestResultController@index');
    });
});
