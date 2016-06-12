<?php

// FrontOffice
Route::group(['middleware' => 'web'], function () {

    Route::auth();
    Route::get('/admin', 'BackOffice\HomeController@index');
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/comment', 'FrontOffice\CommentController@index');
    Route::get('/comment/question', 'FrontOffice\CommentController@question');
    Route::post('/comment/question', 'FrontOffice\CommentController@saveQuestion');

    Route::get('/question/{id}/{comment?}', 'FrontOffice\CommentController@showConversation');
});

// BackOffice
Route::group(['prefix' => 'admin'], function () {

    Route::group(['prefix' => 'user', 'middleware' => ['web']], function () {

        Route::get('', ['uses'=>'BackOffice\UserController@index', 'as'=>'backoffice_user_index']);
        Route::get('/{id}/show', ['uses'=>'BackOffice\UserController@show', 'as'=>'backoffice_user_show']);
        Route::match(['get','post'],'/create', ['uses'=>'BackOffice\UserController@create', 'as'=>'backoffice_user_create']);

    });

    Route::group(['prefix' => 'comment', 'middleware' => ['web']], function () {

        Route::get('', ['uses'=>'BackOffice\CommentController@index', 'as' => 'backoffice_comment_index']);

    });

});