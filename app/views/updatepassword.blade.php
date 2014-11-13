@extends('maintemplate')

@section('body')
  
    {{ Form::open(array('url' => 'updatepasswordaction', 'class' => 'small-form modal-white')) }}

    	<h3 class="no-top-margin">Update Password</h3>

      {{ Form::password('password-old', array(
        'class' => 'form-control',
        'placeholder' => 'Old Password'
        )) }}
      @if($errors->has('password-old'))
        <p class="input-message input-error full-width">{{ $errors->first('password-old') }}</p>
      @endif

      {{ Form::password('password-new', array(
      	'class' => 'form-control',
      	'placeholder' => 'New Password'
      	)) }}
      @if($errors->has('password-new'))
      	<p class="input-message input-error full-width">{{ $errors->first('password-new') }}</p>
      @endif

      {{ Form::password('password-new_confirmation', array(
        'class' => 'form-control',
        'placeholder' => 'Confirm New password'
        )) }}
      @if($errors->has('password-new_confirmation'))
        <p class="input-message input-error full-width">{{ $errors->first('password-new_confirmation') }}</p>
      @endif

      {{ Form::submit('Update Password', array('class' => 'btn btn-success full-width')) }}
      <br><br>

    {{ Form::close() }}

@stop