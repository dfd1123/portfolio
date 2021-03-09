<?php
Route::group(['as' => 'admin.'], function() {
  Route::get('/login', 'admin\AuthController@login')->name('login');
  Route::post('/login', 'admin\AuthController@login_form') -> name('login.form');
    // admin middleware를 사용하고, namespace에 admin.을 추가한다.
  Route::group(['middleware' => 'admin'], function() {
    // 이 라우팅은 route('admin.main') 으로 접근이 가능해진다.
    Route::get('/', 'admin\RequestQuoteController@index');

    Route::get('/logout', 'admin\AuthController@logout') -> name('logout');
    Route::get('/settings', 'admin\SettingsController@index')->name('settings');
    Route::put('/settings/{id}', 'admin\SettingsController@update')->name('settings.update');

    Route::get('/category', 'admin\CategoryController@index')->name('category');
    Route::put('/category/{id}', 'admin\CategoryController@update')->name('category.update');
    Route::post('/category', 'admin\CategoryController@store')->name('category.insert');
    Route::delete('/category/{id}', 'admin\CategoryController@destroy')->name('category.destroy');

    Route::get('/items', 'admin\ItemController@index')->name('items');
    Route::get('/items/create', 'admin\ItemController@create')->name('items.create');
    Route::get('/items/{id}/edit', 'admin\ItemController@edit')->name('items.edit');
    Route::put('/items/{id}', 'admin\ItemController@update')->name('items.update');
    Route::post('/items', 'admin\ItemController@store')->name('items.insert');
    Route::delete('/items/{id}', 'admin\ItemController@destroy')->name('items.destroy');

    Route::get('/item_options', 'admin\ItemOptionsController@index')->name('item_options');
    Route::get('/item_options/create', 'admin\ItemOptionsController@create')->name('item_options.create');
    Route::get('/item_options/{id}/edit', 'admin\ItemOptionsController@edit')->name('item_options.edit');
    Route::put('/item_options/{id}', 'admin\ItemOptionsController@update')->name('item_options.update');
    Route::post('/item_options', 'admin\ItemOptionsController@store')->name('item_options.insert');
    Route::delete('/item_options/{id}', 'admin\ItemOptionsController@destroy')->name('item_options.destroy');

    Route::get('/invoices', 'admin\RequestQuoteController@index')->name('invoices');
    Route::get('/invoices/{id}', 'admin\RequestQuoteController@show')->name('invoices.show');

    Route::post('/Ckfinder/image_upload', 'CkfinderController@image_upload');
  });
});