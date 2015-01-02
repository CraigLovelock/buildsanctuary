@extends('layouts/maintemplate')

@section('body')

  {{ Form::open(array('url' => 'createbuildaction', 'class' => 'small-form modal-white', "files" => true,)) }}

    <h3 class="no-top-margin">Add Your Build</h3>

    {{ Form::text('build-title', Input::old('build-title'), array(
      'class' => 'form-control',
      'placeholder' => 'Build Title'
      )) }}
    @if($errors->has('build-title'))
      <p class="input-message input-error full-width">{{ $errors->first('build-title') }}</p>
    @endif

    <div class="upload-btn">

      {{ Form::file('image', array('class' => 'file-btn')) }}
      {{ Form::button('Add Image', array('class' => 'btn btn-primary btn-left upload-fake-btn')) }}
      <span class="fake-btn-filename">No image selected.</span>

    </div>

    @if($errors->has('image'))
      <p class="input-message input-error full-width">{{ $errors->first('image') }}</p>
    @endif

    <div class="image_preview_container">
      <img id="image_preview" src="#" alt="your image" />
    </div>

    <ul id="myTags" class="form-control form-control-ultagit">
      @if(Session::has('tags'))
        @foreach (Input::old('tags') as $tag)
          <li>{{ $tag }}</li>
        @endforeach
      @endif
    </ul>
  
    @if($errors->has('tags'))
      <p class="input-message input-error full-width">{{ $errors->first('tags') }}</p>
    @endif

    {{ Form::submit('Start Build', array('class' => 'btn btn-success full-width')) }}

    <br><br>

  {{ Form::close() }}

@stop

@section('scripts')

<script>
$(document).ready(function() {

  $("#myTags").tagit({
    placeholderText: "Tag Your Build",
    allowDuplicates: "true",
    fieldName: 'tags[]'
  });

  $(".upload-fake-btn").click(function(){
    jQuery('.file-btn')[0].click()
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
            $('.image_preview_container').fadeIn();
            $('#image_preview').attr('src', e.target.result);
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