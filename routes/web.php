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
    //早餐
    Route::get('/breakfast', 'BreakfastController@index');
    Route::get('/breakfast/create', 'BreakfastController@create');
    Route::get('/profile', 'ProfileController@index');
});

//食堂工作人员功能
Route::group(['namespace' => 'Staff', 'prefix' => 'staff', 'middleware' => 'auth'], function (){
    Route::get('/', 'HomeController@index');
    //菜单
    Route::resource('/menu', 'MenuController');
    //定餐时限
    Route::resource('/limit', 'LimitController');
    //员工开停餐
    Route::resource('/order', 'OrderController');
    Route::get('/breakfast', 'BreakfastController@index');
    Route::get('/breakfast/create', 'BreakfastController@create');
    Route::get('/profile', 'ProfileController@index');
});

//管理员功能
Route::group(['namespace' => 'admin', 'prefix' => 'admin', 'middleware' => 'auth'], function (){
    Route::get('/', 'HomeController@index');
    Route::resource('/dept', 'DeptController');
    Route::get('/user/search', 'UserController@search'); //search顺序要放在UserController@show之前
    Route::resource('/user', 'UserController');
    Route::resource('/fee', 'FeeController');

});