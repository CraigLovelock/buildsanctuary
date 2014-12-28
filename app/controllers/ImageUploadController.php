<?php

// app/controllers/FollwerController.php

class ImageUploadController extends BaseController
{

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function followBuild($buildid,$userid)
	{
    if ($userid != 'false') {
  		// see if there is a matching row for the user trying to follow / unfollow a build
  		$followers = DB::table('followers')->where('blogid', $buildid)->where('userid', $userid)->first();
    	$followCount = count($followers);

    	$following = false;
    	$unfollowing = false;

    	if ($followCount) {
    		// unfollow - remove row
  			$followerRow = DB::table('followers')->where('blogid', $buildid)->where('userid', $userid);
  			$followerRow->delete();
  			$unfollowing = true;
    	} else {
    		// follow - add row
    		$followerRow = new Follower;
    		$followerRow->userid = $userid;
    		$followerRow->blogid = $buildid;
    		$followerRow->save();
    		$following = true;
    	}
  		return Response::json(array(
     		'success' => true,
     		'following' => $following,
     		'unfollowing' => $unfollowing,
        200)
      );
    } else {
      return Response::json(array(
        'success' => false,
        200)
      );
    }
	}

}