<?php

Route::group(['middleware' => 'web'], function () {

    Route::auth();
    Route::get('/admin', 'BackOffice\HomeController@index');
    Route::get('/', function () {
        return view('welcome');
    });

});

Route::group(['prefix' => 'admin'], function () {
    //Route::group(['prefix' => 'user'], function () {
        //Route::get('', ['uses'=>'BackOffice\UserController@index', 'as'=>'backoffice_user_index']);
        //Route::get('/afficher/{id}', ['uses'=>'BackOffice\UserController@show', 'as'=>'backoffice_user_show']);
    //});
    Route::Resource('user','BackOffice\UserController');

});