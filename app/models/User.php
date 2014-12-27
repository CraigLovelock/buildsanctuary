<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	public static function usernameFromID($id) {
		$build = DB::table('users')->where('id', $id)->first();
		return $build->username;
	}

	public static function followStatus($id, $userid) {
		$followCheck = DB::table('followers')
											->where('blogid', $id)
											->where('userid', $userid)
											->first();
		$count = count($followCheck);
		if ($count > 0) {
			return 'true';
		} else {
			return 'false';
		}
	}

	public static function viewStatus($id, $userid) {
		$viewCheck = DB::table('build_tracking')
											->where('build_id', $id)
											->where('user_id', $userid)
											->first();
		$count = count($viewCheck);
		if ($count > 0) {
			return 'true';
		} else {
			return 'false';
		}
	}

	public static function updatedStatus($id, $userid) {
		$viewCheck = DB::table('build_tracking')
											->where('build_id', $id)
											->where('user_id', $userid)
											->first();
		$count = count($viewCheck);
		if ($count > 0) {
			$build = DB::table('blogs')
											->where('id', $id)
											->first();
			$buildUpdateTime = strtotime($build->updated_at);
			$viewTime = strtotime($viewCheck->updated_at);
			if ($buildUpdateTime > $viewTime) {
				return 'true';
			} else {
				return 'false';
			}
		} else {
			return 'false n';
		}
	}

}
