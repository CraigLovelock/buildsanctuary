@extends('layouts/maintemplate')

  <?php

  $rootAsset = asset('/');
  echo "<div class='rootAsset' style='display:none'>$rootAsset</div>";

  //set up general variables / values
  if (Auth::check()) {
    $userID = Auth::user()->id;
    $userSettings = User::getUser($userID);
  } else {
    $userID = false;
  }

  $buildID = $build->id;
  $buildTitle = $build->blogtitle;
  $buildOwnerID = $build->userid;
  $buildFrontpage = $build->frontpage;
  if ($userID == $buildOwnerID) {
    $userIsCreator = true;
  } else {
    $userIsCreator = false;
  }
  if (isset($_GET['page'])) {
    $pageNumber = $_GET['page'];
  } else {
    $pageNumber = 1;
  }

  $followers = DB::table('followers')->where('blogid', $buildID)->where('userid', $userID)->first();
  $followCount = count($followers);
  if ($followCount) {
    $followButtonType = 'btn-success';
    $followButtonClass = 'user-is-following';
    $followButtonText = "Following <span class='glyphicon glyphicon-ok'></span>";
  } else {
    $followButtonType = 'btn-default';
    $followButtonClass = 'user-isNot-following';
    $followButtonText = "Follow <span class='glyphicon glyphicon-plus'></span>";
  }

  $totalUpdates = Blog::countUpdates($buildID);

   echo '
    <div class="full-width-design">

      <div class="home-header-image">
          <div class="attention-line">
            '.$build->blogtitle.'
          </div>
        <img src="'.$rootAsset.'/user_uploads/cover_images/'.$build->coverimage.'.jpeg">
      </div>

      <div class="build-filter-options">
        <div class="build-filter-options-container">
          <span class="build-title-navbar">'.$build->blogtitle.'</span>
          <div class="btn-group build-filter-button">
          ';
            if ($userID == $buildOwnerID) {
              echo "
                <button class='btn btn-primary new-post-btn' data-toggle='modal' data-target='#myModal'>
                  New <span class='glyphicon glyphicon-pencil'></span>
                </button>
              ";
            } else {
              echo "
                <form class='follow-button-form'>
                  <button class='btn $followButtonType follow-button $followButtonClass'>
                    $followButtonText
                  </button>
                  <input type='hidden' name='buildid' value='$buildID'>
                  <input type='hidden' name='userid' value='$userID'>
                </form>
              ";
            }
    echo '</div>
        </div>
      </div>

    </div>
    <div class="pushdown-100px"></div>
    ';
  ?>

@section('body')

  <div id="posts" class="row">

    <?php

    // if the user owns the build
    if ($userIsCreator) {

      switch ($buildFrontpage) {
        // not yet published
        case '0':
          echo "
            <div class='alert alert-success centre-text' role='alert'>
              <b>Sweet, Your build is created!</b><br>
              Before other users can see your build you need to add an update, click the pencil icon above.
            </div>
          ";
          break;

        case '5';
          echo "
            <div class='alert alert-info centre-text' role='alert'>
              <b>Your build has no content!</b><br>
              The build will not show up in any searches until you add a new update.
            </div>
          ";
          break;
        
        default:
          # code...
          break;
      }

    // else if the user doesnt own the build  
    } else {

      switch ($buildFrontpage) {
        // not yet published
        case '0':
        case '5':
          echo "
            <div class='alert alert-info centre-text' role='alert'>
              <b>Sorry! You cannot view this build.</b><br>
              This build is either unpublished or the user has chosen to hide it from view.
            </div>
           ";
          break;
        
        default:
          # code...
          break;
      }

    }

    ?>

    <?php

    if (isset($userSettings) && $userSettings->postorderpref == 1) {
      $posts = DB::table('posts')->where('buildID', '=', "$build->id")->orderBy('id', 'desc')->paginate(4);
      $postNumber_decrease = ($pageNumber * 4) - 5;
      $postNumber = $totalUpdates - $postNumber_decrease;
    } else {
      $posts = DB::table('posts')->where('buildID', '=', "$build->id")->orderBy('id', 'asc')->paginate(4);
      $postNumber = ($pageNumber * 4) - 4;
    }

    foreach ($posts as $post)
    {
      if (isset($userSettings) && $userSettings->postorderpref == 1) {
        $postNumber--;
      } else {
        $postNumber++;
      }
      $post_text = $post->text;
      $post_text = str_ireplace("[img]", "<img class='buildimage' src='", $post_text);
      $post_text = str_ireplace("[/img]", "'/>", $post_text);
      $post_text = str_ireplace("<img", "<img class='buildimage'", $post_text);
      $post_text = str_ireplace("[/url]", "", $post_text);
      $post_text = preg_replace('/\[url=(.*?)\]/i', '', $post_text);
      //$post_text = str_replace("by , on Flickr", "", $post_text);
      $date_posted = strtotime($post->created_at);
      $date_posted = date("d.m.Y", $date_posted);
      $post_id = $post->id;
      echo "
        <div class='panel panel-default'>
          <div class='panel-body update number-$post_id'>
            $post_text
          </div>
          <div class='panel-footer'>
            <button class='btn btn-success btn-xs'>
              <span class='glyphicon glyphicon-heart-empty'></span> Like
            </button>
            <button class='btn btn-info btn-xs show-commentform-button' id='$post_id'>
              <span class='glyphicon glyphicon-comment'></span> Post Comment
            </button>
          ";
          if ($userIsCreator) {
            echo "
              <button class='btn btn-primary edit-post-btn btn-xs' data-toggle='modal' data-target='#editPostModal' id='$post_id'>
                <span class='glyphicon glyphicon-pencil'></span> Edit
              </button>
            ";
          }
          echo "
          </div>
        </div>
      ";
      $comments = DB::table('comments')
                    ->where('updatepostid', $post_id)
                    ->get();
      $commentCount = count($comments);

      $startLimit = $commentCount - 3;

      $selectComments = DB::table('comments')
                    ->where('updatepostid', $post_id)
                    ->skip($startLimit)
                    ->take(3)
                    ->get();
      echo "
        <div class='comments-holder'>

          <div class='post-like-count'>
            <span class='glyphicon glyphicon-heart'></span>
            96 Likes
          </div>";
          if ($commentCount > 3) {
            echo "
              <div class='show-all-comments-button' id='$post_id'>
                <span class='glyphicon glyphicon-comment'></span>
                Show all $commentCount comments...
              </div>
            ";
          }
          echo "
          <ul class='comments comments_$post_id'>
          ";
          if ($commentCount > 0) {
            foreach ($selectComments as $comment) {
              $usernameCommenter = User::usernameFromID($comment->userID);
              if ($userID == $comment->userID) {
                echo "<li><b>$usernameCommenter</b>: $comment->comment <span class='label label-danger'>Delete Post</span></li>";
              } else {
                echo "<li><b>$usernameCommenter</b>: $comment->comment</li>";
              }
            }
          } else {
            echo "<li class='no-comments-message_$post_id'>This post has no comments.</li>";
          }
      echo "
          </ul>
        </div>  
      ";
    }

    ?>

  </div>

  <?php echo $posts->links(); ?>

<?php

if ($userIsCreator) { ?>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn btn-primary insert-image">Insert <span class="glyphicon glyphicon-picture"></span></button>
        {{ Form::open(array('class' => 'update-insertimage-form', 'url' => '/saveUploadedImage', "files" => true,)) }}
          {{ Form::file('image', array('class' => 'update-insertimage-btn', 'name' => 'update-insertimage-btn')) }}
          {{ Form::hidden('buildid', "$build->id")}}
        {{ Form::close() }}
        <h4 class="modal-title" id="myModalLabel">New Update</h4>
      </div>
      <div class="modal-body no-padding">
      {{ Form::open(array('url' => 'createpostaction', 'class' => 'newupdateform')) }}
        <div contentEditable="true" placeholder="Enter your update here..." id="newupdate-text-1" class="form-control-addupdate">
        </div>
        <textarea name="newupdate-text" id="newupdate-text"></textarea>
        {{ Form::hidden('buildid', "$build->id")}}
        {{ Form::hidden('buildtitle', "$build->blogtitle")}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        {{ Form::submit('Post Update', array('class' => 'btn btn-primary submit-newupdate-btn')) }}
      </div>
      {{ Form::close() }}
    </div>
  </div>
</div>

<div class="modal fade" id="editPostModal" tabindex="-1" role="dialog" aria-labelledby="editPost" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn btn-primary edit-insert-image">Insert <span class="glyphicon glyphicon-picture"></span></button>
        {{ Form::open(array('class' => 'edit-insertimage-form', "files" => true,)) }}
          {{ Form::file('image', array('class' => 'edit-insertimage-btn', 'name' => 'edit-insertimage-btn')) }}
          {{ Form::hidden('buildid', "$build->id")}}
        {{ Form::close() }}
        <h4 class="modal-title" id="myModalLabel">Edit Post</h4>
      </div>
      <div class="modal-body no-padding">
      {{ Form::open(array('class' => 'editupdateform')) }}
        <div contentEditable="true" placeholder="Enter your update here..." id="edit-post-editor" class="form-control-editupdate">
        </div>
        <input type="text" name="newupdate-text-edit" id="newupdate-text-edit">
        {{ Form::hidden('postid', "1")}}
        {{ Form::hidden('buildid', "$build->id")}}
      </div>
        <div class="alert alert-danger centre-text check-delete-post" role="alert">
          <strong>Are you sure?&nbsp;</strong>
          <button type="button" class="btn btn-success do-delete-post" >
            </span><span class="glyphicon glyphicon-ok"></span>
          </button>
          <button type="button" class="btn btn-danger dont-delete-post">
            </span><span class="glyphicon glyphicon-remove"></span>
          </button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger delete-update-btn">Delete</button>
        {{ Form::submit('Save Update', array('class' => 'btn btn-success submit-editupdate-btn')) }}
      </div>
      {{ Form::close() }}
    </div>
  </div>
</div>

<?php } ?>

<div class="modal fade" id="addCommentModal" tabindex="-1" role="dialog" aria-labelledby="addCommentModelLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Comment on this update</h4>
      </div>
      <div class="modal-body no-padding">
      {{ Form::open(array('url' => 'createcommentaction', 'class' => 'newcommentform')) }}
        <textarea placeholder="Enter your comment here..." name="newcomment" class="form-control-addupdate"></textarea>
        {{ Form::hidden('postid_addcomment', "") }}
        {{ Form::hidden('buildid', "$build->id")}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        {{ Form::submit('Post Comment', array('class' => 'btn btn-primary addcomment-btn')) }}
      </div>
      {{ Form::close() }}
    </div>
  </div>
</div>


@stop

@section('scripts')

  <script>
  $(function(){
    var pagination = $('.pagination');
    pagination.fadeIn();
  });
  </script>

@stop