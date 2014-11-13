<?php

class BuildController extends \BaseController {

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
		$data = Input::all();
		$rules = array(
			'build-title' => 'required|max:30',
			'image' => 'required',
			'tags' => 'required'
			);

		$messages = array(
			'required' => 'This field is required'
		);

		$validator = Validator::make($data, $rules, $messages);

		if ($validator->fails())
		{
			Input::flash();
			$errors = $validator->messages();
      return Redirect::to('/startbuild')->withErrors($validator)->withInput()->with('tags', Input::get('tags'));
    }

    // create random string for prefix
    $randomString = substr( md5(rand()), 0, 10);
    $username = Auth::user()->username;
    $filenamePrefix = $username . '_' . $randomString;

    // make the image,resize it in ratio to 600px and then save it
    $createImage = Image::make(Input::file('image'))->orientate();
    $createImage->resize(600, null, function ($constraint) {
    	$constraint->aspectRatio();
		});
		$createImage->save("uploads/coverimages/$filenamePrefix.jpg");

		$savedImageName = $filenamePrefix . '.jpg';

		//create the array for the tags / 
		$tags = Input::get('tags');
		$tags = implode(', ', $tags);
		//$tags = str_replace('#', '', $tags);

		$buildTitle = Input::get('build-title');
		
		// create safe for title
		$safeURLTitle = stringHelpers::safeURLSlug($buildTitle);

		// add the build to the database
   	$build = new Build;
   	$build->build_title = $buildTitle;
   	$build->build_image = $savedImageName;
   	$build->build_tags  = $tags;
   	$build->build_status = 1; // 1 = unpublished
   	$build->build_creator_id = Auth::user()->id;
   	$build->save();
   	$lastInsertID = $build->id;

   	return Redirect::to("/viewbuild/$lastInsertID/$safeURLTitle");
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
