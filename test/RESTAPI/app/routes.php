<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
	{

		return Redirect::to('api/v1/login');        
	});



Route::group(array('prefix' => 'api/v1'), function()
    {

Route::resource('login','LoginController');
Route::get('user/getscreeninfo','UserController@getscreeninfo');
Route::post('user/postlogin','UserController@postlogin');
Route::resource('user','UserController');



});