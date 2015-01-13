<?php

class PostController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//sleep(1);
		$data = Input::all();
		$rules = array(
			'newupdate-text' => 'required|min:20',
			);

		$messages = array(
			'newupdate-text.required' => 'Update cannot be empty.',
			'newupdate-text.min' => 'Update must be 20 characters minimum.'
		);

		$validator = Validator::make($data, $rules, $messages);

		if ($validator->fails())
		{
    	return Response::json(array(
        'errors' => $validator->messages()->all(),
        200)
    	);
    }

    // get values for the url
    $buildID = Input::get('buildid');
    $buildTitle = Input::get('buildtitle');
    $safeURLSlug = stringHelpers::safeURLSlug($buildTitle);

		$thisBuild = DB::table('blogs')->where('id', "$buildID")->first();
		$buildOwnerID = $thisBuild->userid;

	  if (Auth::check()) {
	    $userID = Auth::user()->id;
	  } else {
	    $userID = false;
	  }

	  if ($userID != $buildOwnerID) {
	  	return Response::json(array(
        'no_access' => true,
        200)
    	);
	  } else {
	    // clean the input
	    $cleanInput = Purifier::clean(Input::get('newupdate-text'));

			// add the build to the database
	   	$post = new Post;
	   	$post->text = $cleanInput;
	   	$post->userID = Auth::user()->id;
	   	$post->buildID = $buildID;
	   	$post->save();

	   	$now = new DateTime();
			$now->setTimezone(new DateTimeZone('Europe/London'));   			
			$nowTime = $now->format('Y-m-d H:i:s'); 

	   	$build = Blog::find($buildID);
	   	$build->frontpage = '1'; // Live build
	   	$build->lastupdated = $nowTime;
	   	$build->save();

	   	$posts = DB::table('posts')->where('buildID', "$buildID")->paginate(4)->getLastPage();

	   	//return Redirect::to("/viewbuild/$buildID/$safeURLSlug?page=$posts");

	   	return Response::json(array(
	   		'success' => true,
	        'buildID' => $buildID,
	        'URLSlug' => $safeURLSlug,
	        'lastPage' => $posts,
	        'buildowener' => $buildOwnerID,
	        200)
	    );
	  }
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//sleep(1);
		$data = Input::all();
		$rules = array(
			'newupdate-text-edit' => 'required|min:20',
			);

		$messages = array(
			'newupdate-text-edit.required' => 'Update cannot be empty.',
			'newupdate-text-edit.min' => 'Update must be 20 characters minimum.'
		);

		$validator = Validator::make($data, $rules, $messages);

		if ($validator->fails())
		{
    	return Response::json(array(
        'errors' => $validator->messages()->all(),
        200)
    	);
    }

    // get values for the url
    $postID = $id;
    $buildID = Input::get('buildid');

    $thisBuild = DB::table('blogs')->where('id', "$buildID")->first();
		$buildOwnerID = $thisBuild->userid;

	  if (Auth::check()) {
	    $userID = Auth::user()->id;
	  } else {
	    $userID = false;
	  }

	  if ($userID != $buildOwnerID) {
	  	return Response::json(array(
        'no_access' => true,
        200)
    	);
	  } else {
	    // clean the input
	    $cleanInput = Purifier::clean(Input::get('newupdate-text-edit'));

	   	$post = Post::find($postID);
	   	$post->text = $cleanInput; // Live build
	   	$post->save();


			$cleanInput_withclass = str_ireplace("<img", "<img class='buildimage'", $cleanInput);

	   	return Response::json(array(
	   		'success' => true,
	   		'newtext' => $cleanInput_withclass,
	        200)
	    );
	  } 	
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, $buildid)
	{
		$totalPosts = DB::table('posts')->where('buildID', $buildid)->get();
		$count = count($totalPosts);
		if ($count > 1) {
			$post = DB::table('posts')->where('id', $id);
			$post->delete();
			return Response::json(array(
	   		'success' => true,
	        200)
	    );
		} else {
			// delete the post
			$post = DB::table('posts')->where('id', $id);
			$post->delete();

			// change the frontpage to 5 which means it will not show in any results.
			$build = Blog::find($buildid);
	   	$build->frontpage = '5'; // Live but no posts
	   	$build->save();
	   	
			return Response::json(array(
	   		'success' => true,
	        200)
	    );
		}
	}

	/**
	 * Get the post content
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getPostData($postID)
	{
		$post = DB::table('posts')->where('id', $postID)->first();
		$post_text = $post->text;
		$postid = $post->id;
		$buildid = $post->buildID;
		$post_text = str_ireplace("<img", "<img class='buildimage'", $post_text);
   	return Response::json(array(
   		'success' => true,
        'postText' => $post_text,
        'postid' => $postid,
        'buildid' => $buildid,
        200)
    );
	}

	public function saveUploadedImage() {
	$userID = Auth::user()->id;
	// make the image,resize it in ratio to 600px and then save it
	$uniqueImageID = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ$userID"), 0, 40);

	$imageSavePath = 'user_uploads/build_images/'. $uniqueImageID . '.jpeg';
  	$createImage = Image::make(Input::file('update-insertimage-btn'))->orientate();
  	$createImage->resize(800, null, function ($constraint) {
  	$constraint->aspectRatio();
	})->insert('images/watermark.png', 'bottom-right', 10, 10);
	$createImage->save("$imageSavePath");

	//create and save the image name into db.
	$imageDB = New ImageUpload;
	$imageDB->image_file_name = $uniqueImageID;
	$imageDB->folder_link = 'build_images';
	$imageDB->user_id = Auth::user()->id;
	$imageDB->build_id = Input::get('buildid');
	$imageDB->save();

	return Response::json(array(
   		'success' => true,
   		'name' => $uniqueImageID,
        200)
    );
	}

	public function saveUploadedImageEdit() {
	$userID = Auth::user()->id;
	// make the image,resize it in ratio to 600px and then save it
	$uniqueImageID = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ$userID"), 0, 40);

	$imageSavePath = 'user_uploads/build_images/'. $uniqueImageID . '.jpeg';
  $createImage = Image::make(Input::file('edit-insertimage-btn'))->orientate();
  $createImage->resize(800, null, function ($constraint) {
  	$constraint->aspectRatio();
	});
	$createImage->save("$imageSavePath");

	//create and save the image name into db.
	$imageDB = New ImageUpload;
	$imageDB->image_file_name = $uniqueImageID;
	$imageDB->folder_link = 'build_images';
	$imageDB->user_id = Auth::user()->id;
	$imageDB->build_id = Input::get('buildid');
	$imageDB->save();

	return Response::json(array(
   		'success' => true,
   		'name' => $uniqueImageID,
        200)
    );
	}

}
