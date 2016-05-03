<?php

Route::group(['middleware' => 'web'], function () {

    Route::auth();
    Route::get('/admin', 'BackOffice\HomeController@index');
    Route::get('/', function () {
        return view('welcome');
    });
});