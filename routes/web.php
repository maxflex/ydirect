<?php

Route::get('auth', 'AuthController@index');
Route::get('auth/continue-session', 'AuthController@continueSession');
Route::group(['middleware' => ['login']], function () {
    Route::get('/{any}', 'AppController@index')->where('any', '.*');
});
