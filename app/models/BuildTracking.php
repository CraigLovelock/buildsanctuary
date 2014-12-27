<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class BuildTracking extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'build_tracking';

	public static function add_build_tracking($id){
		if (Auth::check()){
			$query = static::
											where('build_id', $id)
											->where('user_id', Auth::user()->id)
											->first();
			$count = count($query);

			if ($count > 0) {
				$rowID = $query->id;
				$tracking = BuildTracking::find($rowID);
				$tracking->touch();
			} else {
				$tracking = new BuildTracking;
		    $tracking->build_id = $id;
		    $tracking->user_id = Auth::user()->id;
		    $tracking->save();
			}

		}
  }


}
