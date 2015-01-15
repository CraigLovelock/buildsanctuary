<?php

// app/controllers/PostLikeController.php

class PostLikeController extends BaseController
{

	public function likePost($postID,$buildID) {
    $postID = $postID;
    $buildID = $buildID;
    $userID = Auth::user()->id;

    $postLike = new PostLike;
    $postLike->postID = $postID;
    $postLike->userID = $userID;
    $postLike->buildID = $buildID;
    $postLike->save();

    return Response::json(array(
      'success' => true,
      'buildid' => $buildID,
      200)
    );
  }

  public function unlikePost($postID) {
    $postID = $postID;
    $userID = Auth::user()->id;

    $likeRow = DB::table('post_likes')->where('userID', $userID)->where('postID', $postID);
    $likeRow->delete();

    return Response::json(array(
      'success' => true,
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

  public function jsonPostLikes($postid) {
    $countLikes = DB::table('post_likes')
                    ->where('postID', $postid)
                    ->get();
    $countLikes = count($countLikes);
    return Response::json(array(
      'success' => true,
      'count' => $countLikes,
    ));
  }

}