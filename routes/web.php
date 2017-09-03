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

Route::get('/', 'IndexController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//用户功能
Route::group(['namespace' => 'User', 'prefix' => 'user'], function (){
    Route::get('/', 'HomeController@index');
    Route::get('/breakfast', 'BreakfastController@index');
    Route::get('/breakfast/create', 'BreakfastController@create');
    Route::get('/profile', 'ProfileController@index');
});

//食堂工作人员功能
Route::group(['namespace' => 'Staff', 'prefix' => 'staff', 'middleware' => 'auth'], function (){
    Route::get('/', 'HomeController@index');
    Route::resource('/menu', 'MenuController');
    Route::get('/breakfast', 'BreakfastController@index');
    Route::get('/breakfast/create', 'BreakfastController@create');
    Route::get('/profile', 'ProfileController@index');
});