<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::get('/get_pornstars', 'PornstarController@index');

//Route::get('/pornstars', 'PornstarJsonController@index');

//Route::get('/test_pornstars', 'PornstarJsonController@testJson');
//
//Route::get('/update_filters', 'PornstarController@update_filters');
//
//
//Route::get('/ninja', 'NinjaliticController@index');

//Route::post('/upload_csv', 'UploadCsvController@store')->name('upload_csv');
//
//Route::get('/track_model', 'PornstarController@track_model')->name('track_model');


Route::middleware('catch_error:api')->group(function () {

    Route::get('/update_filters', 'PornstarController@update_filters');

    Route::post('/upload_csv', 'UploadCsvController@store')->name('upload_csv');

    Route::post('/track_model', 'PornstarController@track_model')->name('track_model');

});
