@extends('maintemplate')

@section('body')

<?php
  $rootAsset = asset('/');
  echo "<div class='rootAsset' style='display:none'>$rootAsset</div>";
?>
    
    <div class="pageheader-withbutton">
      <h3>Manage Builds <a href="startbuild" class="btn btn-success float-right-headerbutton" role="button">Create</a></h3>
    </div>
    <?
      $builds = DB::table('blogs')->where('userid', Auth::user()->id)->orderBy('id', 'desc')->paginate(15); 
    ?>

    @foreach ($builds as $build)
    <? 
      $buildcover = $build->coverimage;
      $buildtitle = $build->blogtitle;
      $safeURLSlug = stringHelpers::safeURLSlug($build->blogtitle);
    ?>
      <div class="row">
        <div class="col-sm-3">
          <div class="thumbnail">
            <img src="user_uploads/cover_images/<? echo "$buildcover";?>.jpeg" alt="<? echo $buildtitle ?>">
            <div class="caption">
              <h5 class="build-title-buildcard"><? echo $buildtitle ?></h5>
              <p>
                <a href="#" class="btn btn-primary edit-build-btn" id="<? echo $build->id ?>" role="button">Edit</a>
                <a href="viewbuild/<?php echo $build->id . '/' . $safeURLSlug ?>" class="btn btn-success" role="button">View</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    @endforeach

    <div class="modal fade" id="editBuildModal" tabindex="-1" role="dialog" aria-labelledby="editBuild" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Edit Build</h4>
          </div>
          <div class="modal-body">
          {{ Form::open(array('class' => 'editbuildform')) }}
          <input type="text" class="form-control" placeholder="Build Title" name="build-title" id="build-title-edit">
            <ul id="myTags" class="form-control form-control-ultagit">
            </ul>
          <!--<p class="text-muted">Cover Image</p>
          <div class="upload-btn">
            {{ Form::file('image', array('class' => 'file-btn')) }}
            {{ Form::button('Change Image', array('class' => 'btn btn-primary btn-left upload-fake-btn')) }}
            <span class="fake-btn-filename">No image selected.</span>
          </div>
            <div class="image_preview_container_show">
              <img id="image_preview" src="http://localhost/public_html/user_uploads/cover_images/testaccount123_8f8c9d0cc6.jpeg" alt="your image" />
            </div>-->
            {{ Form::hidden('buildid', "")}}
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
            {{ Form::submit('Save', array('class' => 'btn btn-success save-build-information')) }}
          </div>
          {{ Form::close() }}
        </div>
      </div>
    </div>
  
@stop

@section('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<script src="js/tag-it.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css">
<link href="css/jquery.tagit.css" rel="stylesheet" type="text/css">

<script>
$(document).ready(function() {

  function runTags() {
    $("#myTags").tagit({
      placeholderText: "Tag Your Build",
      allowDuplicates: "true",
      fieldName: 'tags[]'
    });
  }

  $(".delete-update-btn").on('click', function(){
    $('.check-delete-post').fadeToggle();
  });

  $(".dont-delete-post").on('click', function(){
    $(".check-delete-post").fadeOut();
  });

  $(".edit-build-btn").on('click', function(){
    $('#editBuildModal').modal('show');
    $("#myTags").remove();
    $(".editbuildform").append('<ul id="myTags" class="form-control form-control-ultagit"></ul>');
    var id = this.id;
    var rootAsset = $('.rootAsset').html();
      $.ajax({
      url: rootAsset+'get-build-data/'+id,
      type: 'post',
      cache: false,
      dataType: 'json',
      data: $(this).serialize(),
      beforeSend: function() {
        $(".modal-error-message").remove();
      },
      success: function(data) {
        if(data.errors) {
          $('.modal-body').append('<div class="alert alert-danger centre-text modal-error-message" role="alert"><strong>Error!</strong> '+ data.errors +'</div>'); 
        } else if (data.success) {
          $("#build-title-edit").val(data.buildTitle);
          $("input[name=buildid]").val(data.buildid);
          $('#myTags').html(data.buildtags).promise().done(function(){
            runTags();
          });
        }
      },
      error: function(xhr, textStatus, thrownError) {
          alert('Something went to wrong.Please Try again later...');
      }
    });
    return false;
  });

  $('.editbuildform').submit(function() {
    var $btn = $(".save-build-information").val('Saving...');
    var rootAsset = $('.rootAsset').html();
    var buildID = $("input[name=buildid]").val();
    $.ajax({
        url: rootAsset+'editbuildinfo/'+buildID,
        type: 'post',
        cache: false,
        dataType: 'json',
        data: $(this).serialize(),
        beforeSend: function() {
          $(".modal-error-message").remove();
        },
        success: function(data) {
          if(data.errors) {
            $('.modal-body').append('<div class="alert alert-danger centre-text modal-error-message" role="alert"><strong>Error!</strong> '+ data.errors +'</div>');
          } else if (data.success) {
            $(".build-title-buildcard").text(data.newtitle);
            $('#editBuildModal').modal('hide');
            //location.reload();
          }
          $btn.val('Save');
        },
        error: function(xhr, textStatus, thrownError) {
            alert('Something went to wrong.Please Try again later...');
        }
    });
    return false;
  });

  $(".upload-fake-btn").click(function(){
    $('.file-btn').trigger('click'); 
  });

  $(document).on('change', '.file-btn', function() {
    var input = $(this),
    numFiles = input.get(0).files ? input.get(0).files.length : 1,
    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    label = trancateTitle(label);
    input.trigger('fileselect', [numFiles, label]);
  });

  $(document).ready( function() {
    $('.file-btn').on('fileselect', function(event, numFiles, label) {
    $('.fake-btn-filename').text(label);
    });
  });

  function trancateTitle (title) {
    var length = 10;
    if (title.length > length) {
       title = title.substring(0, length)+'...';
    }
    return title;
  }

  function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('.image_preview_container_show').fadeOut(function(){
              $('#image_preview').attr('src', e.target.result);
              $('.image_preview_container_show').fadeIn();
            });
            $('.upload-fake-btn').html('Change Image');
        }

        reader.readAsDataURL(input.files[0]);
    }
  }

  $(".file-btn").change(function(){
      readURL(this);
      $('.image_preview_container').fadeOut();
  });

});
</script>

@stop