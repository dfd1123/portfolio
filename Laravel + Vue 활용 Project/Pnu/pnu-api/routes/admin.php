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

Route::middleware(['auth:admin'])->group(function () {
    Route::get('detail', 'AuthController@detail');
    Route::put('detail', 'AuthController@detail_update');

    Route::prefix('users')->group(function () {
        Route::get('/paginate', 'UserController@paginate');
        Route::get('/', 'UserController@index');
    });

    Route::prefix('batches')->group(function () {
        Route::get('/paginate', 'BatchController@paginate');
        Route::get('/', 'BatchController@index');
        Route::post('/', 'BatchController@store');
        Route::put('/{id}', 'BatchController@update');
    });

    Route::prefix('cp_test_templates')->group(function () {
        Route::get('/paginate', 'CpTestTemplateController@paginate');
        Route::get('/', 'CpTestTemplateController@index');
        Route::post('/', 'CpTestTemplateController@store');
        Route::put('/{id}', 'CpTestTemplateController@update');
        Route::get('/export', 'CpTestTemplateController@export');
        Route::post('/import', 'CpTestTemplateController@import');
    });

    Route::prefix('user_cp_test_results')->group(function () {
        Route::get('/raw_total', 'UserCpTestResultController@raw_total');
        Route::delete('/record', 'UserCpTestResultController@delete_record');
        Route::get('/{id}', 'UserCpTestResultController@show');
    });

    Route::prefix('user_cp_test_result_totals')->group(function () {
        Route::get('/paginate', 'UserCpTestResultTotalController@paginate');
        Route::get('/', 'UserCpTestResultTotalController@index');
    });

    Route::prefix('analysises')->group(function () {
        Route::get('/paginate', 'AnalasisController@paginate');
        Route::get('/', 'AnalasisController@index');
        Route::get('/{id}', 'AnalasisController@show');
        Route::post('/', 'AnalasisController@store');
        Route::put('/{id}', 'AnalasisController@update');
    });

    Route::prefix('groups')->group(function () {
        Route::get('/paginate', 'GroupController@paginate');
        Route::get('/', 'GroupController@index');
        Route::get('/{id}', 'GroupController@show');
        Route::post('/', 'GroupController@store');
        Route::put('/{id}', 'GroupController@update');
    });

    Route::post('/push_topic', 'PushController@push_topic');
    Route::get('/push_topic_history', 'PushController@push_topic_history');
});
