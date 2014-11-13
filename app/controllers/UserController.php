<?php

// app/controllers/UserController.php

class UserController extends BaseController
{

	public function registerUser() {
		$data = Input::all();
		$rules = array(
			'username' => 'required|alpha_dash|max:16|unique:users,username',
			'email' => 'required|email|unique:users,email',
			'password' => 'required|min:8'
			);

		$messages = array(
			'username.unique' => 'This username is already in use'
		);

		$validator = Validator::make($data, $rules, $messages);

		if ($validator->fails())
		{
			Input::flash();
			$errors = $validator->messages();
      return Redirect::to('/register')->withErrors($validator)->withInput();
    }

   	$user = new User;
   	$user->username = Input::get('username');
   	$user->email = Input::get('email');
   	$rawPassword = Input::get('password');
   	$hashedPassword = Hash::make($rawPassword);
   	$user->password = $hashedPassword;
   	$user->save();

   	return Redirect::to('/login')->with('successLogin', '1');
	}

	public function loginUser() {
		$data = Input::all();
		$rules = array(
			'username' => 'required',
			'password' => 'required'
			);

		$validator = Validator::make($data, $rules);

		if ($validator->fails())
		{
			Input::flash();
			$errors = $validator->messages();
			return Redirect::to('/login')->withErrors($validator)->withInput();		
		};

		$userData = array(
			'username' => Input::get('username'),
			'password' => Input::get('password')
			);

		if (Auth::attempt($userData))
		{
			return Redirect::to('/');
		}
		else
		{
			return Redirect::to('/login')->with('loginError', '1')->withInput();
		}

	}

	public function logout() {
		if (Auth::check())
		{
			$userID = Auth::user()->id;
			Auth::logout();
			return Redirect::to('/');
    }	
	}

	public function updatepassword() {
		$data = Input::all();
		$currentPassword = Auth::user()->password;
		$passwordInput = Input::get('password-old');

		$rules = array(
			'password-old' => "required|checkMatch:$currentPassword",
			'password-new' => 'required|confirmed|min:8',
			'password-new_confirmation' => 'required|min:8'
			);

		$messages = array(
			'required' => 'This field is required.',
			'confirmed' => 'The new passwords do not match.',
			'check_match' => 'Incorrect current password.'
			);

		$validator = Validator::make($data, $rules, $messages);

		if ($validator -> fails())
		{
			Input::flash();
			$errors = $validator->messages();
			return Redirect::to('/updatepassword')->withErrors($validator)->withInput();
		}

		$userID = Auth::user()->id;
		$user = User::find($userID);
   	$rawPassword = Input::get('password-new');
   	$hashedPassword = Hash::make($rawPassword);
   	$user->password = $hashedPassword;
   	$user->update();

		return Redirect::to('/accountsettings')->with('success', '1');
	}

	public function updatecontact() {
		$data = Input::all();
		$rules = array(
			'email' => 'required|email|unique:users,email'
			);

		$messages = array(
			'unique' => 'This email is already in use.'
			);

		$validator = Validator::make($data, $rules, $messages);

		if ($validator -> fails())
		{
			Input::flash();
			$errors = $validator->messages();
			return Redirect::to('/updatecontact')->withErrors($validator)->withInput();
		}

		$userID = Auth::user()->id;
		$user = User::find($userID);
		$user->email = Input::get('email');
		$user->update();

		return Redirect::to('/accountsettings')->with('success', '1');
	}

}