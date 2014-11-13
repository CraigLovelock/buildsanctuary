@extends('maintemplate')

@section('body')

<div class="build-information">
  <div class="build-title">{{ $build->build_title }}</div>
  <button class="btn btn-primary new-post-btn" data-toggle="modal" data-target="#myModal">
    @if (Auth::user()->id == $build->build_creator_id)
    <span class="glyphicon glyphicon-pencil"></span>
    @endif
  </button>
</div>

@if ($build->build_status == 1 && Auth::user()->id == 6)
  <div class="alert alert-success centre-text" role="alert">
    <strong>Sweet, Your build is created!</strong>
    <br><br>
    However, you must first add an update before people can see your build. Click the pencil button.
  </div>
@elseif ($build->build_status == 1 && Auth::user()->id != 6)
  <div class="alert alert-warning centre-text" role="alert">
    <strong>Sorry! This build is not yet published!</strong>
  </div>
@else

  <div id="posts" class="row">

    <?php 

    $posts = DB::table('posts')->where('buildid', '=', "$build->id")->paginate(4);

    foreach ($posts as $post)
    {
      echo "
        <div class='panel panel-default'>
          <div class='panel-body'>
            $post->postcontent
          </div>
          <div class='panel-footer'>$post->id | added: $post->created_at</div>
        </div>
      ";
    }

    ?>

  </div>

  <?php echo $posts->links(); ?>

@endif

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn btn-primary insert-image" data-dismiss="modal"><span class="glyphicon glyphicon-picture"></span></button>
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
        {{ Form::hidden('buildtitle', "$build->build_title")}}
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
  var lastScrollTop = 0;
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
  });

    $('.newupdateform').submit(function() {
      $(".submit-newupdate-btn").addClass('disabled');
      $.ajax({
          url: '/laravelcms/public_html/createpostaction',
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
              location.href='/laravelcms/public/viewbuild/'+data.buildID+'/'+data.URLSlug+'?page='+data.lastPage;
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