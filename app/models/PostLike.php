<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class PostLike extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'post_likes';

	public static function countPostLikes($postid) {
	  $countLikes = DB::table('post_likes')
                    ->where('postID', $postid)
                    ->get();
    $countLikes = count($countLikes);
    return $countLikes;
	}

	public static function countBuildPostLikes($buildid) {
	  $countLikes = DB::table('post_likes')
                    ->where('buildID', $buildid)
                    ->get();
    $countLikes = count($countLikes);
    return $countLikes;
	}

}
