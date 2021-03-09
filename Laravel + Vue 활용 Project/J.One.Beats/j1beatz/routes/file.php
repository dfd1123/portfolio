<?php

use Illuminate\Http\Request;

if (!App::environment(['local'])) {
    URL::forceScheme('https');
} else {
    URL::forceScheme('http');
}

Route::get('/403', 'AppController@abort')->name('login');
Route::get('/temp/{filename}', 'FileAuthController@streaming')->where('filename', '^.*\.(mp3|wav)$');

Route::middleware([/*'passcookie', */'auth:api'])->group(function () {
    Route::get('/down/{extension}/{id}', 'FileAuthController@download')->where('extension', '^(mp3|wav)$');
    Route::get('/free/{id}', 'FileAuthController@free');
});
