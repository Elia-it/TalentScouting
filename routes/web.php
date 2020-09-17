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


Route::get('/test', 'Test@test_mo');

Route::get('/Scraping', 'Test@models');

//Route::get('/models', 'ModelController@index')->name('models');
//Route::get('/pornstars','ModelController@indexPornstar')->name('pornstars');

// Route::get('/new_mo', 'Test@test_mo');

Route::get('/pornstars', 'PornstarController@index')->name('pornstar_get');
Route::post('/pornstars','PornstarController@index')->name('pornstar_get');

Route::post('/x', 'PornstarController@index')->name('pornstar.index');

Route::get('/test1', 'PornstarController@test1')->name('test1');
Route::post('/test1', 'PornstarController@test1')->name('test1');

Route::post('/test2', 'PornstarController@test2')->name('test2');





