@extends('maintemplate')

@section('body')

<?php
  $rootAsset = asset('/');
  echo "<div class='rootAsset' style='display:none'>$rootAsset</div>";

  //set up general variables / values
  if (Auth::check()) {
    $userID = Auth::user()->id;
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

?>

<div class="build-information">
  <div class="build-title">{{ $build->blogtitle }}</div>
  <?php
    if ($userID == $buildOwnerID) {
      echo "
        <button class='btn btn-primary new-post-btn' data-toggle='modal' data-target='#myModal'>
          <span class='glyphicon glyphicon-pencil'></span>
        </button>
      ";
    } else {
      echo "
          <button class='btn btn-default follow-button'>
            Follow <span class='glyphicon glyphicon-heart'></span>
          </button>
      ";
    }
    ?>
</div>

  <div id="posts" class="row">

    <?php

    // UNPUBLISHED - User owns the build
    if ($buildFrontpage == 0 && $buildOwnerID == $userID) {
      echo "
        <div class='alert alert-success centre-text' role='alert'>
          <span class='glyphicon glyphicon-ok'></span> <b>Sweet, Your build is created!</b><br>
            Before other users can see your build you need to add an update, click the pencil icon above.
        </div>
        ";
    // UNPUBLISHED - User does not own build
    } else if ($buildFrontpage == 0 && $buildOwnerID != $userID) {
      echo "
        <div class='alert alert-info centre-text' role='alert'>
          <span class='glyphicon glyphicon-remove'></span> <b>Sorry! You cannot view this build.</b><br>
            This build is either unpublished or the user has chosen to hide it from view.
        </div>
        ";
    }

    ?>

    <?php 

    $posts = DB::table('posts')->where('buildID', '=', "$build->id")->paginate(4);
    $postNumber = ($pageNumber * 4) - 4;

    foreach ($posts as $post)
    {
      $postNumber++;
      $post_text = $post->text;
      $post_text = str_ireplace("[img]", "<img class='buildimage' src='", $post_text);
      $post_text = str_ireplace("[/img]", "'/>", $post_text);
      $post_text = str_ireplace("<img", "<img class='buildimage'", $post_text);
      $date_posted = strtotime($post->created_at);
      $date_posted = date("d.m.Y", $date_posted);
      $post_id = $post->id;
      echo "
        <div class='panel panel-default'>
          <div class='panel-body update number-$post_id'>
            $post_text
          </div>
          <div class='panel-footer'>
            $postNumber | $date_posted
          ";
          if ($userIsCreator) {
            echo "
              <button class='btn btn-primary edit-post-btn' data-toggle='modal' data-target='#editPostModal' id='$post_id'>
                Edit
              </button>
            ";
          }
          echo "
          </div>
        </div>
      ";
    }

    ?>

  </div>

  <?php echo $posts->links(); ?>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn btn-primary insert-image"><span class="glyphicon glyphicon-picture"></span></button>
        {{ Form::open(array('class' => 'update-insertimage-form', 'url' => '/saveUploadedImage', "files" => true,)) }}
          {{ Form::file('image', array('class' => 'update-insertimage-btn', 'name' => 'update-insertimage-btn')) }}
        {{ Form::close() }}
        <h4 class="modal-title" id="myModalLabel">New Update</h4>
      </div>
      <div class="modal-body no-padding">
      {{ Form::open(array('url' => 'createpostaction', 'class' => 'newupdateform')) }}
        <div contentEditable="true" placeholder="Enter your update here..." id="newupdate-text-1" class="form-control-addupdate">
        </div>
        <input type="text" name="newupdate-text" id="newupdate-text">
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
        <button type="button" class="btn btn-primary edit-insert-image"><span class="glyphicon glyphicon-picture"></span></button>
        {{ Form::open(array('class' => 'edit-insertimage-form', "files" => true,)) }}
          {{ Form::file('image', array('class' => 'edit-insertimage-btn', 'name' => 'edit-insertimage-btn')) }}
        {{ Form::close() }}
        <h4 class="modal-title" id="myModalLabel">Edit Post</h4>
      </div>
      <div class="modal-body no-padding">
      {{ Form::open(array('class' => 'editupdateform')) }}
        <div contentEditable="true" placeholder="Enter your update here..." id="edit-post-editor" class="form-control-editupdate">
        </div>
        <input type="text" name="newupdate-text-edit" id="newupdate-text-edit">
        {{ Form::hidden('postid', "$post_id")}}
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

@stop

@section('scripts')

  <script src="<?php echo $rootAsset ?>/js/viewbuild-actions.js"></script>

@stop