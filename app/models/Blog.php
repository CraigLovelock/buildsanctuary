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
				$query = static::where('frontpage', '1')->latest('lastupdated');
				break;

			case 'following':
				$userid = Auth::user()->id;
				$query = DB::table('blogs')
				  ->join('followers', function($q) use ($userid){
				      $q->on('blogs.id', '=', 'followers.blogid');
				      $q->where('followers.userid', '=', $userid);
				  })
				  ->select('blogs.*')
				  ->where('frontpage', '1')
				  ->latest('lastupdated');
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
				$query = static::where('frontpage', '1')->latest('lastupdated');
				break;
		}
		return $query->paginate(20);
	}

	public static function countFollowers($buildid) {
		$query = DB::table('followers')
								->where('blogid', $buildid)
								->get();
		return count($query);
	}

	public static function countViews($buildid) {
		$query = static::where('id', $buildid)
								->first();
		return $query->pagecount;
	}

	public static function countUpdates($buildid) {
		$query = DB::table('posts')->where('buildID', $buildid)
								->get();
		return count($query);
	}

	public static function addPageCount($buildid) {

			$query = static::where('id', $buildid)
							->first();
			$current = $query->pagecount;
			$current++;

			// if the user is logged in
			if (Auth::check()) {
				// check if they have already viewed the build
				$query = DB::table('build_tracking')
										->where('build_id', $buildid)
										->where('user_id', Auth::user()->id)
										->first();
				$count = count($query);

				// if so, get the last time they did
				if ($count > 0) {
					$lastViewTime = $query->updated_at;
					$lastViewTime = strtotime($lastViewTime);
					$now = new DateTime();
					$now->setTimezone(new DateTimeZone('Europe/London'));
					$nowTimeDate = strtotime($now->format('Y-m-d H:i:s'));
					$gap = $nowTimeDate - $lastViewTime;

					if ($gap > 300) {
						$build = Blog::find($buildid);
						$build->pagecount = $current;
						$build->save();
					}
				// otherwise, simply update the pagecount.
				} else {
					$build = Blog::find($buildid);
					$build->pagecount = $current;
					$build->save();
				}

			} else {
				// no cookie = never viewed build in last 5 mins
				if(!isset($_COOKIE["viewedBuild-$buildid"])) {
					// update page count and set a fresh baked cookie :)
				  $build = Blog::find($buildid);
					$build->pagecount = $current;
					$build->save();
					setcookie("viewedBuild-$buildid", time(), time() + 300, "/");
				}
			}
	}

	public static function getTotalBuilds() {
		$query = DB::table('blogs')->get();
		return count($query);
	}
	public static function filterBuilds($value) {
		switch ($value) {
			case 'unpub':
				$query = DB::table('blogs')->where('frontpage', 0)->get();
				return count($query);
				break;
			case 'live':
				$query = DB::table('blogs')->where('frontpage', 1)->get();
				return count($query);
				break;
			case 'hidden':
				$query = DB::table('blogs')->where('frontpage', 2)->get();
				return count($query);
				break;
			case 'empty':
				$query = DB::table('blogs')->where('frontpage', 5)->get();
				return count($query);
				break;

			default:
				# code...
				break;
		}
	}


}
