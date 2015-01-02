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
	$slug = Route::getCurrentRoute()->uri();
	$builds = Blog::findBuilds($slug);
	return View::make('pages/home', compact('builds'));
});

Route::get('newest', function()
{
	$slug = Route::getCurrentRoute()->uri();
	$builds = Blog::findBuilds($slug);
	return View::make('pages/home', compact('builds'), array('pageTitle' => 'Newly Updated Projects'));
});

Route::get('trending', function()
{
	$slug = Route::getCurrentRoute()->uri();
	$builds = Blog::findBuilds($slug);
	return View::make('pages/home', compact('builds'), array('pageTitle' => 'Trending Projects'));
});

Route::get('following', array('before' => 'auth', function()
{
	$slug = Route::getCurrentRoute()->uri();
	$builds = Blog::findBuilds($slug);
	return View::make('pages/home', compact('builds'), array('pageTitle' => 'Builds You Are Following'));
}));

Route::get('staff-picks', function()
{
	$slug = Route::getCurrentRoute()->uri();
	$builds = Blog::findBuilds($slug);
	return View::make('pages/home', compact('builds'), array('pageTitle' => 'Staff Picked Builds'));
});

Route::get('/register', array('before' => 'guest', function()
{
	return View::make('pages/register', array('pageTitle' => 'Register'));
}));

Route::get('/login', array('before' => 'guest', function()
{
	return View::make('pages/login', array('pageTitle' => 'Login'));
}));

Route::get('/accountsettings', array('before' => 'auth', function()
{
	return View::make('pages/accountsettings', array('pageTitle' => 'Account Settings'));
}));

Route::get('updatepassword', array('before' => 'auth', function()
{
	return View::make('pages/updatepassword', array('pageTitle' => 'Update Your Password'));
}));

Route::get('updatecontact', array('before' => 'auth', function()
{
	return View::make('pages/updatecontact', array('pageTitle' => 'Update Contact Information'));
}));

Route::get('testing', function()
{
	return View::make('pages/testing');
});

Route::get('404', function()
{
	return View::make('errors/404');
});

Route::get('startbuild', array('before' => 'auth', function()
{
	return View::make('pages/createbuild', array('pageTitle' => 'Start Your Build'));
}));

Route::get('managebuilds', array('before' => 'auth', function()
{
	return View::make('pages/managebuilds', array('pageTitle' => 'Build Management'));
}));

Route::get('password_reminder', function()
{
	return View::make('pages/passwordremind', array('pageTitle' => 'Password Help'));
});

Route::get('deniedaccess', function()
{
	return View::make('errors/deniedaccess', array('pageTitle' => 'Access Denied'));
});

Route::get('sendemails', function()
{
	return View::make('emails/wereback', array('pageTitle' => 'Send Emails'));
});

Route::get('viewbuild/{build_id?}/{build_title?}', function($build_id = null, $build_title = null)
{
	Blog::addPageCount($build_id);
	BuildTracking::add_build_tracking($build_id);
  $build = Blog::find($build_id);
  $buildtitle = strtolower($build->blogtitle);
  $buildtitle = ucwords($buildtitle);
  if (!is_null($build)) {
  	return View::make('pages/viewbuild', compact('build'), array('pageTitle' => $buildtitle));
	} else {
		return "Build does not exist";
	}
});

Route::get('search', function()
{
    $q = Input::get('term');
    $searchTerms = explode(' ', $q);

    foreach($searchTerms as $term)
    {
			$results = DB::table('blogs')
				->where('blogtitle', 'LIKE', '%'. $term .'%')
				->where('frontpage', 1)
				->orWhere('tags', 'LIKE', '%'. $term .'%')
				->orderBy('id', 'desc')->paginate(15);
    }
    return View::make('pages/search', compact('results'))->withInput(Input::flashOnly('term'), array('pageTitle' => 'Search')); 
});

Validator::extend('checkMatch', function($attribute, $value, $parameters)
{
   if (count($parameters) < 1)
      throw new InvalidArgumentException("Validation rule checkMatch requires at least 1 parameters.");

   return Hash::check($value, $parameters[0]);
});

Route::controller('password', 'RemindersController');

Route::get('users', array('uses' => 'UserController@updateUser'));

Route::post('registerUser', array('uses' => 'UserController@registerUser'));
Route::post('loginUser', array('uses' => 'UserController@loginUser'));
Route::get('logout', array('uses' => 'UserController@logout'));
Route::post('updatepasswordaction', array('uses' => 'UserController@updatepassword'));
Route::post('updatecontactaction', array('uses' => 'UserController@updatecontact'));
Route::post('createbuildaction', array('uses' => 'BlogController@create'));
Route::post('createpostaction', array('uses' => 'PostController@create'));
Route::post('editpostaction/{id}', array('uses' => 'PostController@edit'));
Route::post('saveUploadedImage', array('uses' => 'PostController@saveUploadedImage'));
Route::post('saveUploadedImageEdit', array('uses' => 'PostController@saveUploadedImageEdit'));
Route::post('get-post-data/{postID}', array('uses' => 'PostController@getPostData'));
Route::post('deletepost/{id}/{buildid}', array('uses' => 'PostController@destroy'));
Route::post('get-build-data/{postID}', array('uses' => 'BlogController@getBuildData'));
Route::post('editbuildinfo/{id}', array('uses' => 'BlogController@edit'));
Route::post('deletebuild/{id}', array('uses' => 'BlogController@destroy'));
Route::post('followbuild/{buildid}/{userid}', array('uses' => 'FollowerController@followbuild'));