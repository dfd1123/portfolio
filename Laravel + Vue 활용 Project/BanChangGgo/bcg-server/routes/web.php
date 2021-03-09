<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->group(['middleware' => 'throttle:50,1'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    
    $router->post('email/duplicate', 'AuthController@check_email_duplicate');
    $router->post('email/verify', 'AuthController@email_verify_request');
    $router->post('email/certify', 'AuthController@email_verify_certify');

    $router->post('password/find/request', 'AuthController@password_find_request');
    $router->post('password/find/certify', 'AuthController@password_find_certify');
    
    $router->get('terms', 'TermController@index');
});

$router->group(['middleware' => 'auth:api'], function () use ($router) {
    $router->post('secession', 'AuthController@secession');
    
    $router->group(['prefix' => 'detail'], function () use ($router) {
        $router->get('', 'AuthController@detail');
        $router->post('', 'AuthController@detail_update');
    });

    $router->group(['prefix' => 'notices'], function () use ($router) {
        $router->get('', 'NoticeController@index');
        $router->get('{id}', 'NoticeController@show');
    });

    $router->group(['prefix' => 'user_plans'], function () use ($router) {
        $router->get('', 'UserPlanController@index');
        $router->post('', 'UserPlanController@store');
        $router->put('{id}', 'UserPlanController@update');
        $router->delete('{id}', 'UserPlanController@destroy');
    });

    $router->group(['prefix' => 'symptoms'], function () use ($router) {
        $router->get('', 'SymptomController@index');
    });

    $router->group(['prefix' => 'user_batches'], function () use ($router) {
        $router->get('', 'UserBatchController@index');
        $router->post('', 'UserBatchController@store');
        $router->get('{id}', 'UserBatchController@show');
        $router->put('{id}', 'UserBatchController@update');
    });

    $router->group(['prefix' => 'health_reports'], function () use ($router) {
        $router->get('', 'HealthReportController@index');
        $router->get('{id}', 'HealthReportController@show');
    });

    $router->group(['prefix' => 'health_report_pages'], function () use ($router) {
        $router->get('', 'HealthReportPageController@index');
    });

    $router->group(['prefix' => 'batches'], function () use ($router) {
        $router->get('', 'BatchController@index');
        $router->get('{id}', 'BatchController@show');
    });

    /*
    $router->group(['prefix' => 'examples'], function () use ($router) {
        $router->get('', 'ExampleController@index');
        $router->post('', 'ExampleController@store');
        $router->get('{id}', 'ExampleController@show');
        $router->put('{id}', 'ExampleController@update');
        $router->delete('{id}', 'ExampleController@destroy');
    });
    */
});
