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
    $router->post('login', 'AuthController@login');
});

$router->group(['middleware' => 'auth:admin'], function () use ($router) {
    $router->post('register', 'AuthController@register');

    $router->group(['prefix' => 'detail'], function () use ($router) {
        $router->get('', 'AuthController@detail');
        $router->post('', 'AuthController@detail_update');
    });

    $router->group(['prefix' => 'notices'], function () use ($router) {
        $router->get('', 'NoticeController@index');
        $router->post('', 'NoticeController@store');
        $router->put('{id}', 'NoticeController@update');
        $router->get('paginate', 'NoticeController@paginate');
        $router->delete('{id}', 'NoticeController@destroy');
    });

    $router->group(['prefix' => 'batches'], function () use ($router) {
        $router->get('', 'BatchController@index');
        $router->post('', 'BatchController@store');
        $router->put('{id}', 'BatchController@update');
        $router->get('paginate', 'BatchController@paginate');
    });

    $router->group(['prefix' => 'users'], function () use ($router) {
        $router->get('', 'UserController@index');
        $router->put('{id}', 'UserController@update');
        $router->get('paginate', 'UserController@paginate');
    });

    $router->group(['prefix' => 'admins'], function () use ($router) {
        $router->get('', 'AdminController@index');
        $router->post('', 'AdminController@store');
        $router->post('{id}', 'AdminController@update');
        $router->get('paginate', 'AdminController@paginate');
    });

    $router->group(['prefix' => 'user_batches'], function () use ($router) {
        $router->get('', 'UserBatchController@index');
        $router->post('', 'UserBatchController@store');
        $router->put('{id}', 'UserBatchController@update');
        $router->get('paginate', 'UserBatchController@paginate');
    });

    $router->group(['prefix' => 'user_plans'], function () use ($router) {
        $router->get('', 'UserPlanController@index');
        $router->post('', 'UserPlanController@store');
        $router->put('{id}', 'UserPlanController@update');
        $router->get('paginate', 'UserPlanController@paginate');
    });

    $router->group(['prefix' => 'plan_templates'], function () use ($router) {
        $router->get('', 'PlanTemplateController@index');
        $router->post('', 'PlanTemplateController@store');
        $router->put('{id}', 'PlanTemplateController@update');
        $router->get('paginate', 'PlanTemplateController@paginate');
    });

    $router->group(['prefix' => 'symptoms'], function () use ($router) {
        $router->get('', 'SymptomController@index');
        $router->post('', 'SymptomController@store');
        $router->post('{id}', 'SymptomController@update');
        $router->get('paginate', 'SymptomController@paginate');
    });

    $router->group(['prefix' => 'disease_categories'], function () use ($router) {
        $router->get('', 'DiseaseCategoryController@index');
        $router->post('', 'DiseaseCategoryController@store');
        $router->put('{id}', 'DiseaseCategoryController@update');
        $router->get('paginate', 'DiseaseCategoryController@paginate');
    });

    $router->group(['prefix' => 'health_reports'], function () use ($router) {
        $router->get('', 'HealthReportController@index');
        $router->post('', 'HealthReportController@store');
        $router->put('{id}', 'HealthReportController@update');
        $router->get('paginate', 'HealthReportController@paginate');
    });

    $router->group(['prefix' => 'health_report_pages'], function () use ($router) {
        $router->get('', 'HealthReportPageController@index');
        $router->post('', 'HealthReportPageController@store');
        $router->put('{id}', 'HealthReportPageController@update');
        $router->get('paginate', 'HealthReportPageController@paginate');
        $router->delete('{id}', 'HealthReportPageController@destroy');
    });

    $router->group(['prefix' => 'medicine_infos'], function () use ($router) {
        $router->get('', 'MedicineInfoController@index');
        $router->post('', 'MedicineInfoController@store');
        $router->post('{id}', 'MedicineInfoController@update');
        $router->get('paginate', 'MedicineInfoController@paginate');
    });

    $router->group(['prefix' => 'nutrition_infos'], function () use ($router) {
        $router->get('', 'NutritionInfoController@index');
        $router->post('', 'NutritionInfoController@store');
        $router->post('{id}', 'NutritionInfoController@update');
        $router->get('paginate', 'NutritionInfoController@paginate');
    });

    $router->group(['prefix' => 'health_infos'], function () use ($router) {
        $router->get('', 'HealthInfoController@index');
        $router->post('', 'HealthInfoController@store');
        $router->post('{id}', 'HealthInfoController@update');
        $router->get('paginate', 'HealthInfoController@paginate');
    });

    $router->group(['prefix' => 'symptom_infos'], function () use ($router) {
        $router->get('', 'SymptomInfoController@index');
        $router->post('', 'SymptomInfoController@store');
        $router->post('{id}', 'SymptomInfoController@update');
        $router->get('paginate', 'SymptomInfoController@paginate');
        $router->delete('{id}', 'SymptomInfoController@destroy');
    });

    $router->group(['prefix' => 'terms'], function () use ($router) {
        $router->get('', 'TermController@index');
        $router->post('', 'TermController@store');
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
