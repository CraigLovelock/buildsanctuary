@extends('layouts/maintemplate')

@section('body')

  {{ Form::open(array('action' => 'RemindersController@postRemind', 'class' => 'small-form modal-white')) }}

      <h3 class="no-top-margin">Request Password Reset</h3>

      @if(Session::has('status'))
        <div class="alert alert-success" role="alert">
          <span class="glyphicon glyphicon-ok"></span>
            Success, Check your email.
        </div>
      @endif

      {{ Form::text('email', Input::old('email'), array(
        'class' => 'form-control',
        'placeholder' => 'Enter your email'
        )) }}
      @if($errors->has('email'))
        <p class="input-message input-error full-width">{{ $errors->first('email') }}</p>
      @endif

      {{ Form::submit('Send Request', array('class' => 'btn btn-success full-width')) }}

    {{ Form::close() }}

@stop

@section('scripts')
  <script>

  </script>
@stop