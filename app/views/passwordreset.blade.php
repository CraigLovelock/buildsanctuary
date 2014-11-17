@extends('maintemplate')

@section('body')

{{ Form::open(array('action' => 'RemindersController@postReset', 'class' => 'small-form modal-white')) }}

	<h3 class="no-top-margin">Request Reset</h3>

	@if(Session::has('status'))
	<div class="alert alert-success" role="alert">
	  <span class="glyphicon glyphicon-ok"></span>
	    Success, Check your email.
	</div>
	@endif

	{{ Form::hidden('token', $token) }}

	{{ Form::text('email', Input::old('email'), array(
		'class' => 'form-control',
		'placeholder' => 'Enter your email'
	)) }}
	@if($errors->has('email'))
		<p class="input-message input-error full-width">{{ $errors->first('email') }}</p>
	@endif

	{{ Form::password('password', array(
		'class' => 'form-control',
		'placeholder' => 'New Password'
		)) }}
	@if($errors->has('password'))
		<p class="input-message input-error full-width">{{ $errors->first('password') }}</p>
	@endif

	{{ Form::password('password_confirmation', array(
		'class' => 'form-control',
		'placeholder' => 'Confirm New password'
	)) }}
	@if($errors->has('password_confirmation'))
		<p class="input-message input-error full-width">{{ $errors->first('password_confirmation') }}</p>
	@endif

    {{ Form::submit('Reset Password', array('class' => 'btn btn-success full-width')) }}

    {{ Form::close() }}

@stop

@section('scripts')
  <script>

  </script>
@stop