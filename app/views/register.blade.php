@extends('maintemplate')

@section('body')
  
    {{ Form::open(array('url' => 'registerUser', 'class' => 'small-form modal-white')) }}

    	<h3 class="no-top-margin">Register</h3>

      {{ Form::text('username', Input::old('username'), array(
      	'class' => 'form-control',
      	'placeholder' => 'Username'
      	)) }}
      @if($errors->has('username'))
      	<p class="input-message input-error full-width">{{ $errors->first('username') }}</p>
      @endif

      {{ Form::text('email', Input::old('email'), array(
      	'class' => 'form-control',
      	'placeholder' => 'Email'
      	)) }}
      @if($errors->has('email'))
      	<p class="input-message input-error full-width">{{ $errors->first('email') }}</p>
      @endif

      {{ Form::password('password', array(
      	'class' => 'form-control',
      	'placeholder' => 'Password'
      	)) }}
   		@if($errors->has('password'))
      	<p class="input-message input-error full-width">{{ $errors->first('password') }}</p>
      @endif

      {{ Form::submit('Register', array('class' => 'btn btn-success full-width')) }}
      <br><br>

    {{ Form::close() }}

@stop