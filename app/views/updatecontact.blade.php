@extends('maintemplate')

@section('body')
  
    {{ Form::open(array('url' => 'updatecontactaction', 'class' => 'small-form modal-white')) }}

    	<h3 class="no-top-margin">Contact Information</h3>

      <div class="alert alert-info" role="alert">
          Current: {{ Auth::user()->email }}
      </div>

      {{ Form::text('email', Input::old('email'), array(
        'class' => 'form-control',
        'placeholder' => 'Enter new password'
        )) }}
      @if($errors->has('email'))
        <p class="input-message input-error full-width">{{ $errors->first('email') }}</p>
      @endif

      {{ Form::submit('Update Information', array('class' => 'btn btn-success full-width')) }}
      <br><br>

    {{ Form::close() }}

@stop