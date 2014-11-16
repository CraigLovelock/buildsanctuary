@extends('maintemplate')

@section('body')
  
    {{ Form::open(array('url' => 'loginUser', 'class' => 'small-form modal-white')) }}

    	<h3 class="no-top-margin">Login</h3>

    	@if(Session::has('successLogin'))
      	<div class="alert alert-success" role="alert">
      		<span class="glyphicon glyphicon-ok"></span>
      		  Registered, Login to confirm.
      	</div>
      @endif

      {{ Form::text('username', Input::old('username'), array(
      	'class' => 'form-control',
      	'placeholder' => 'Username'
      	)) }}
      @if($errors->has('username'))
      	<p class="input-message input-error full-width">{{ $errors->first('username') }}</p>
      @endif

      {{ Form::password('password', array(
      	'class' => 'form-control',
      	'placeholder' => 'Password'
      	)) }}
      @if($errors->has('password'))
      	<p class="input-message input-error full-width">{{ $errors->first('password') }}</p>
      @endif
      @if(Session::has('loginError'))
      	<p class="input-message input-error full-width">Incorrect username / password.</p>
      @endif

      {{ Form::label('rememberme', 'Keep me logged in:') }}
      {{ Form::checkbox('rememberme', 1, true) }}
      <br><br>

      {{ Form::submit('Login', array('class' => 'btn btn-success full-width')) }}
      
      <br><br>
      <a href="password_reminder">Password Issues? Reset Here</a>

    {{ Form::close() }}

@stop