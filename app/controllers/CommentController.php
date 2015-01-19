<?php

// app/controllers/CommentController.php

class CommentController extends BaseController
{

	public function addComment() {
    $data = Input::all();
    $rules = array(
      'newcomment' => 'required|min:10',
      );

    $messages = array(
      'required' => 'This field is required',
      'min' => 'Your comment needs to be atleast 10 characters'
    );

    $validator = Validator::make($data, $rules, $messages);

    if ($validator->fails())
    {
      return Response::json(array(
        'errors' => $validator->messages()->all(),
        200)
      );
    }

    $commentInput = Purifier::clean(Input::get('newcomment'));
    $userID = Auth::user()->id;
    $username = User::usernameFromID($userID);
    $postid = Input::get('postid_addcomment');

    // lets count how many comments the post currently has
    $comments = DB::table('comments')
                  ->where('updatepostid', $postid)
                  ->get();
    $commentCount = count($comments);

    $deleteOne = false;
    if ($commentCount > 2) {
      $deleteOne = true;
    }

    $comment = new Comment;
    $comment->userID = $userID;
    $comment->comment = $commentInput;
    $comment->updatepostid = Input::get('postid_addcomment');
    $comment->buildID = Input::get('buildid');
    $comment->save();

    return Response::json(array(
      'success' => true,
      'comment' => $commentInput,
      'commenter' => $username,
      'deleteOne' => $deleteOne,
      200)
    );
  }

  public function fetchall($id) {
    $comments = DB::table('comments')
                    ->where('updatepostid', $id)
                    ->get();
    $commentsArray = array_map(function($comment){
        return array(
                'username' => User::usernameFromID($comment->userID),
                'comment' => $comment->comment
        );
    }, $comments);

    return Response::json(array(
        'success' => true,
        'comments' => $commentsArray
    ));
  }

}
