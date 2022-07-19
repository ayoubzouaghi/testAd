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

Route::group(['prefix' => 'company/active-directory'], function () {
    // get AD
    Route::get('', 'ActiveDirectoryParameterController@index');

    //add or update if exist AD
    Route::post('', 'ActiveDirectoryParameterController@update');

    // destroy AD
    Route::delete('', 'ActiveDirectoryParameterController@destroy');

    // test connection  AD
    Route::post('test', 'ActiveDirectoryParameterController@testConnection');
});
Route::get('/key', function () {
    return \Illuminate\Support\Str::random(32);
});
