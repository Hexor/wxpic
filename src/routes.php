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

Route::get('hello', 'Hexor\WXPic\WXPicController@index');


Route::get('2local', 'Hexor\WXPic\WXPicController@toLocal');

Route::get('2remote', 'Hexor\WXPic\WXPicController@toRemote');
