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
	return View::make('home');
});

Route::get('/register', array('before' => 'guest', function()
{
	return View::make('register');
}));

Route::get('/login', array('before' => 'guest', function()
{
	return View::make('login');
}));

Route::get('/accountsettings', array('before' => 'auth', function()
{
	return View::make('accountsettings');
}));

Route::get('updatepassword', array('before' => 'auth', function()
{
	return View::make('updatepassword');
}));

Route::get('updatecontact', array('before' => 'auth', function()
{
	return View::make('updatecontact');
}));

Route::get('testing', function()
{
	return View::make('testing');
});

Route::get('startbuild', array('before' => 'auth', function()
{
	return View::make('createbuild');
}));

Route::get('password_reminder', function()
{
	return View::make('passwordremind');
});

Route::get('viewbuild/{build_id?}/{build_title?}', function($build_id = null, $build_title = null)
{
  $build = Blog::find($build_id);
  if (!is_null($build)) {
  	return View::make('viewbuild', compact('build'));
	} else {
		return "Build does not exist";
	}
});

Validator::extend('checkMatch', function($attribute, $value, $parameters)
{
   if (count($parameters) < 1)
      throw new InvalidArgumentException("Validation rule checkMatch requires at least 1 parameters.");

   return Hash::check($value, $parameters[0]);
});

Route::controller('password', 'RemindersController');

Route::post('registerUser', array('uses' => 'UserController@registerUser'));
Route::post('loginUser', array('uses' => 'UserController@loginUser'));
Route::get('logout', array('uses' => 'UserController@logout'));
Route::post('updatepasswordaction', array('uses' => 'UserController@updatepassword'));
Route::post('updatecontactaction', array('uses' => 'UserController@updatecontact'));
Route::post('createbuildaction', array('uses' => 'BlogController@create'));
Route::post('createpostaction', array('uses' => 'PostController@create'));
Route::post('saveUploadedImage', array('uses' => 'PostController@saveUploadedImage'));
