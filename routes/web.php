<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Example Routes
Route::view('/', 'landing');
Route::match(['get', 'post'], '/dashboard', function(){
    return view('dashboard');
});
Route::view('/pages/slick', 'pages.slick');
Route::view('/pages/datatables', 'pages.datatables');
Route::view('/pages/blank', 'pages.blank');


//Route::get('/Scraping', 'Test@models');

//Route::get('/models', 'ModelController@index')->name('models');
//Route::get('/pornstars','ModelController@indexPornstar')->name('pornstars');

// Route::get('/new_mo', 'Test@test_mo');

//Route::get('/pornstars', 'PornstarController@index')->name('pornstar_get');
//Route::post('/pornstars','PornstarController@index')->name('pornstar_get');
//
//Route::post('/x', 'PornstarController@index')->name('pornstar.index');
//
//Route::view('/testcss', 'dashboard');






Route::get('/test', 'Test@test');

Route::get('/upload_csv', 'Test@upload_csv');





