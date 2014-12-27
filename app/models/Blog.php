<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Blog extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'blogs';

	public static function findBuilds($slug){
		switch ($slug) {
			case 'newest':
			case '/':
				$query = static::where('frontpage', '1')->latest('id');
				break;

			case 'following':
				$query = DB::table('blogs')
												->join('followers', 'blogs.id', '=', 'followers.blogid')
												->where('followers.userid', Auth::user()->id)
												->where('frontpage', '1')
												->latest('id');
				break;

			case 'trending':
				$query = static::where('frontpage', '1')
												->latest('pagecount');
				break;

			case 'staff-picks':
			  $query = static::where('frontpage', '1')
			  								->where('featured', '1')
			  								->latest('pagecount');
				break;

			default:
				$query = static::where('frontpage', '1')->latest('id');
				break;
		}
		return $query->paginate(20);
	}

}
