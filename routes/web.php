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

Route::get("/", "HomeController@index");

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post("/user", "UserController@search");

Route::get("/user/{username}", "UserController@show");

// Changers

Route::put("/user/{username}", "UserController@update");

Route::delete("/user/{username}", "UserController@delete");



