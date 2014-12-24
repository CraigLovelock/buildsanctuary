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
    <? $buildcover = $build->coverimage; ?>
    <? $buildtitle = $build->blogtitle; ?>
      <div class="row">
        <div class="col-sm-3">
          <div class="thumbnail">
            <img src="user_uploads/cover_images/<? echo "$buildcover";?>.jpeg" alt="<? echo $buildtitle ?>">
            <div class="caption">
              <h5 class="build-title-buildcard"><? echo $buildtitle ?></h5>
              <p>
                <a href="#" class="btn btn-primary edit-build-btn" id="<? echo $build->id ?>" role="button">Edit</a>
                <a href="#" class="btn btn-success" role="button">View</a>
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
              <li>tag1</li>
            </ul>
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

  $("#myTags").tagit({
    placeholderText: "Tag Your Build",
    allowDuplicates: "true",
    fieldName: 'tags[]'
  });

  $(".delete-update-btn").on('click', function(){
    $('.check-delete-post').fadeToggle();
  });

  $(".dont-delete-post").on('click', function(){
    $(".check-delete-post").fadeOut();
  });

  $(".edit-build-btn").on('click', function(){
    $('#editBuildModal').modal('show');
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

});
</script>

@stop