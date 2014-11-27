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
$buildStatus = $build->status;
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
          <button class='btn btn-success new-post-btn'>
            <span class='glyphicon glyphicon-heart'></span>
          </button>
      ";
    }
    ?>
</div>

  <div id="posts" class="row">

    <?php

    // UNPUBLISHED - User owns the build
    if ($buildStatus == 1 && $buildOwnerID == $userID) {
      echo "
        <div class='alert alert-success centre-text' role='alert'>
          <span class='glyphicon glyphicon-ok'></span> <b>Sweet, Your build is created!</b><br>
            Before other users can see your build you need to add an update, click the pencil icon above.
        </div>
        ";
    // UNPUBLISHED - User does not own build
    } if ($buildStatus == 1 && $buildOwnerID != $userID) {
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
      $post_text = str_ireplace("[img]", "<img class='build-image' src='", $post_text);
      $post_text = str_ireplace("[/img]", "'/>", $post_text);
      echo "
        <div class='panel panel-default'>
          <div class='panel-body update'>
            $post_text
          </div>
          <div class='panel-footer'>$postNumber | added: $post->created_at </div>
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
        <button type="button" class="btn btn-primary insert-image" data-dismiss="modal"><span class="glyphicon glyphicon-picture"></span></button>
        <form class='update-insertimage-form'>
          <input type='file' class='update-insertimage-btn'>
        </form>
        <h4 class="modal-title" id="myModalLabel">New Update</h4>
      </div>
      <div class="modal-body no-padding">
      {{ Form::open(array('url' => 'createpostaction', 'class' => 'newupdateform')) }}
        {{ Form::textarea('newupdate-text', Input::old('newupdate-text'), array(
          'class' => 'form-control-addupdate',
          'placeholder' => 'Add update here'
          ))
        }}
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

@stop

@section('scripts')

<script>
$(document).ready(function() 
{
  $(".insert-image").click(function(){
    $('.update-insertimage-btn').trigger('click'); 
  });
 /* var lastScrollTop = 0;
  $(window).scroll(function(event){
     var st = $(this).scrollTop();
     if (st > lastScrollTop){
        $(".navbar").stop(true, false)
        .animate({ 'marginTop': '-50px'}, 200);
        $(".build-information").stop(true, false)
          .animate({ 'marginTop': '0'}, 200)
          .animate({ 'opacity': '0.8'}, 200)
          .css('backgroundColor', 'white')
          .css('position', 'fixed')
          .css('width', '100%')
          .css('left', '0')
          .css('top', '0px')
          .css('text-align', 'center')
     } else {
        $(".navbar").stop(true, false)
          .animate({ 'marginTop': '0'}, 200);
        $(".build-information").stop(true, false)
          .animate({ 'marginTop': '50px'}, 200);
     }
     lastScrollTop = st;
  });

  var $win = $(window);
  $win.scroll(function () {
    if ($win.scrollTop() == 0) {
      $(".build-information")
        .animate({ 'marginTop': '0'}, 00)
        .css('backgroundColor', '')
        .css('position', '')
        .css('text-align', 'left')
        .css('top', '');
    }
  });*/

    $('.newupdateform').submit(function() {
      $(".submit-newupdate-btn").addClass('disabled');
      var rootAsset = $('.rootAsset').html();
      $.ajax({
          url: rootAsset+'createpostaction',
          type: 'post',
          cache: false,
          dataType: 'json',
          data: $('.newupdateform').serialize(),
          beforeSend: function() {
            $(".modal-error-message").remove();
            $(".submit-newupdate-btn").removeClass('disabled');
          },
          success: function(data) {
            if(data.errors) {
              $('.modal-body').append('<div class="alert alert-danger centre-text modal-error-message" role="alert"><strong>Error!</strong> '+ data.errors +'</div>'); 
            } else if (data.success) {
              location.href= rootAsset+'viewbuild/'+data.buildID+'/'+data.URLSlug+'?page='+data.lastPage;
            }
          },
          error: function(xhr, textStatus, thrownError) {
              alert('Something went to wrong.Please Try again later...');
          }
      });
      return false;
    });

});
</script>

@stop