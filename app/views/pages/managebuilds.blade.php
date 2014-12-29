@extends('layouts/maintemplate')

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
    $countBuilds = count($builds);

    if (Auth::check()) {
      $userID = Auth::user()->id;
    } else {
      $userID = false;
    }

    if ($countBuilds) {

      echo '<div id="builds" class="row">';

      foreach ($builds as $build)
      {
        $safeURLSlug = stringHelpers::safeURLSlug($build->blogtitle);
        $followStatus = User::followStatus($build->id, $userID);
        $viewStatus = User::viewStatus($build->id, $userID);
        $followCount = Blog::countFollowers($build->id);
        $viewCount = Blog::countViews($build->id);
        echo '

            <div class="item col-sm-3 buildnumber-'.$build->id.'">
              <div class="thumbnail">
                <img src="user_uploads/cover_images/'.$build->coverimage.'.jpeg">
                <div class="caption">
                  <h5>'.$build->blogtitle.'</h5>
                  <p>
                    <a href="#" class="btn btn-primary edit-build-btn" id="'.$build->id.'" role="button">Edit</a>
                    <a href="viewbuild/'.$build->id.'/'.$safeURLSlug.'" class="btn btn-success" role="button">View</a>
                  </p>
                </div>
                <div class="buildcard-stats">
                  <ul>
                    <li><span class="glyphicon glyphicon-heart-empty buildcard-follow-status-'.$followStatus.'" aria-hidden="true"></span> '.$followCount.'</li>
                    <li><span class="glyphicon glyphicon-eye-open buildcard-follow-status-'.$viewStatus.'" aria-hidden="true"></span> '.$viewCount.'</li>
                  </ul>
                </div>
              </div>
            </div>
        ';
      }

      echo '</div>';

    } else {
        echo "
        <div class='row'>
          <div class='alert alert-info centre-text not-full-width-alert' role='alert'>
            <b>No builds to show</b><br>
            Why not create one using the button above.
          </div>
        </div>
        ";
    }
    ?>

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
              <button type="button" class="btn btn-success do-delete-build" >
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

<script>
$(document).ready(function() {

  function runTags() {
    $("#myTags").tagit({
      placeholderText: "Tag Your Build",
      allowDuplicates: "true",
      fieldName: 'tags[]'
    });
  }

  $(".do-delete-build").on('click', function(){
    var rootAsset = $('.rootAsset').html();
    var buildID = $("input[name=buildid]").val();
    $.ajax({
        url: rootAsset+'deletebuild/'+buildID,
        type: 'post',
        cache: false,
        dataType: 'json',
        data: $(this).serialize(),
        beforeSend: function() {
          $(".modal-error-message").remove();
          $(".submit-newupdate-btn").removeClass('disabled');
        },
        success: function(data) {
          if(data.errors) {
            $('.modal-body').append('<div class="alert alert-danger centre-text modal-error-message" role="alert"><strong>Error!</strong> '+ data.errors +'</div>');
          } else if (data.success) {
            //location.reload();
            $('#editBuildModal').modal('hide');
            $('.check-delete-post').fadeToggle();
            $(".buildnumber-"+data.buildid+"").remove();
            $('#builds').isotope();
            //$('#builds').prepend('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Better check yourself, youre not looking too good.</div>');
          }
        },
        error: function(xhr, textStatus, thrownError) {
            alert('Something went to wrong.Please Try again later...');
        }
    });
    return false;
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

  var dots = 0;
  setInterval (type, 300);

  function type() {
    if(dots < 3) {
        $('#dots').append('.');
        dots++;
    } else {
        $('#dots').html('');
        dots = 0;
    }
  }
  var container = $('#builds');
  var pagination = $('.pagination');

  imagesLoaded(container, function() {
    $(".loadingBuilds").remove();
    container.fadeIn();
    pagination.fadeIn();
    container.isotope({
      itemSelector : '.item',
      getSortData: {
      number: '.number'
      },
      animationEngine: 'css'
    });
  });

});
</script>

@stop