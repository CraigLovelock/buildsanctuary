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

    // clean the input
    $cleanInput = Purifier::clean(Input::get('newupdate-text'));

		// add the build to the database
   	$post = new Post;
   	$post->text = $cleanInput;
   	$post->userID = Auth::user()->id;
   	$post->buildID = $buildID;
   	$post->save();

   	$build = Blog::find($buildID);
   	$build->frontpage = '1'; // Live build
   	$build->save();

   	$posts = DB::table('posts')->where('buildID', '=', "$buildID")->paginate(4)->getLastPage();

   	//return Redirect::to("/viewbuild/$buildID/$safeURLSlug?page=$posts");

   	return Response::json(array(
   		'success' => true,
        'buildID' => $buildID,
        'URLSlug' => $safeURLSlug,
        'lastPage' => $posts, 
        200)
    );
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
	public function destroy($id)
	{
		$post = DB::table('posts')->where('id', $id);
		$post->delete();
		return Response::json(array(
   		'success' => true,
        200)
    );
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
		$post_text = str_ireplace("<img", "<img class='buildimage'", $post_text);
   	return Response::json(array(
   		'success' => true,
        'postText' => $post_text,
        'postid' => $postid,
        200)
    );
	}

	public function saveUploadedImage() {
	//$image = Input::get('update-insertimage-btn');
	// make the image,resize it in ratio to 600px and then save it
	$uniqueImageID = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 15);
	$imageSavePath = 'user_uploads/build_images/'. $uniqueImageID . '.jpeg';
  $createImage = Image::make(Input::file('update-insertimage-btn'))->orientate();
  $createImage->resize(800, null, function ($constraint) {
  	$constraint->aspectRatio();
	});
	$createImage->save("$imageSavePath");
	return Response::json(array(
   		'success' => true,
   		'name' => $uniqueImageID,
        200)
    );
	}

	public function saveUploadedImageEdit() {
	//$image = Input::get('update-insertimage-btn');
	// make the image,resize it in ratio to 600px and then save it
	$uniqueImageID = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 15);
	$imageSavePath = 'user_uploads/build_images/'. $uniqueImageID . '.jpeg';
  $createImage = Image::make(Input::file('edit-insertimage-btn'))->orientate();
  $createImage->resize(800, null, function ($constraint) {
  	$constraint->aspectRatio();
	});
	$createImage->save("$imageSavePath");
	return Response::json(array(
   		'success' => true,
   		'name' => $uniqueImageID,
        200)
    );
	}

}
