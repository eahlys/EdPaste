<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();
// post workaround for logout
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/', 'PasteController@index');
Route::post('/', 'PasteController@submit');
Route::get('/{link}', 'PasteController@view')->where('link', '[a-zA-Z0-9]+');
Route::post('/{link}', 'PasteController@password')->where('link', '[a-zA-Z0-9]+');
Route::get('users/dashboard', 'UserController@dashboard');
Route::get('users/account', 'UserController@account');
Route::get('/users/delete/{link}', 'UserController@delete')->where('link', '[a-zA-Z0-9]+');
