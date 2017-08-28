<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//用户功能
Route::group(['namespace' => 'User', 'prefix' => 'user'], function (){
    Route::get('/', 'HomeController@index');
    Route::get('/breakfast', 'BreakfastController@index');
});
