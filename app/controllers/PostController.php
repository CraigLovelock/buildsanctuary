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

		// add the build to the database
   	$post = new Post;
   	$post->text = Input::get('newupdate-text');
   	$post->userID = Auth::user()->id;
   	$post->buildID = $buildID;
   	$post->save();

   	$build = Blog::find($buildID);
   	$build->status = '2'; // Live build
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
		//
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
		//
	}


}
