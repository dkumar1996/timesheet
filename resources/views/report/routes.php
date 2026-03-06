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

Route::group(array('before'=>'guest'),function(){

	Route::get('/', function()
	{
		return Redirect::to('/login');        
	});

	Route::resource('login', 'LoginController');
});



Route::group(array('before'=>'auth'),function(){

Route::resource('admin', 'AdminController');

Route::resource('staff', 'StaffController');

Route::resource('site', 'SiteController');

Route::resource('user', 'UserController');

Route::resource('timesheet', 'TimesheetController');

Route::resource('company', 'CompanyController');


Route::get('changelocation',['as'=>'changelocation','uses'=>'UserController@changelocation']);

Route::get('show_login/{id}/{site_id}',['as'=>'show_login','uses'=>'UserController@show_login']);

Route::post('loginupdate',['as'=>'loginupdate','uses'=>'UserController@loginupdate']);

Route::get('show_logout/{id}/{site_id}',['as'=>'show_logout','uses'=>'UserController@show_logout']);

Route::post('logoutupdate',['as'=>'logoutupdate','uses'=>'UserController@logoutupdate']);

Route::get('showlogoff/{userid}/{site_id}',['as'=>'showlogoff','uses'=>'UserController@showlogoff']);

Route::get('force_logout/{id}/{site_id}',['as'=>'force_logout','uses'=>'UserController@force_logout']);

Route::post('force_logout/{id}',['as'=>'post_forcelogout','uses'=>'UserController@post_forcelogout']);

Route::get('post_edit/{site_id}/{userid}',['as'=>'post_edit','uses'=>'UserController@post_edit']);

Route::post('post_site',['as'=>'post_site','uses'=>'UserController@post_site']);

Route::post('assign_user',['as'=>'assign_users','uses'=>'SiteController@assign_user']);

Route::get('logout',['as'=>'logout','uses'=>'LoginController@logout']);

Route::resource('report', 'ReportController');

Route::post('timesheet_store',['as'=>'storeentry','uses'=>'TimesheetController@storeentry']);
Route::post('timesheet_store_ajax',['as'=>'ajax_storeentry','uses'=>'TimesheetController@ajax_storeentry']);


});