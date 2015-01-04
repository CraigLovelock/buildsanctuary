@extends('layouts/maintemplate')

@section('body')

<?php
  $user = $user = DB::table('users')->where('id', Auth::user()->id)->first();
  if ($user->postorderpref == 1) {
    // 1 = NEW TO OLD | 2 = OLD TO NEW
    $postorderpref = true;
  } else {
    $postorderpref = false;
  }
  if ($user->email_list == 1) {
    // 0 = ON EMAIL LIST | 1 = OFF EMAIL LIST
    $emailpref = true;
  } else {
    $emailpref = false;
  }
?>
  
    {{ Form::open(array('url' => 'updateusersettingsaction', 'class' => 'small-form modal-white')) }}

    	<h3 class="no-top-margin">General Settings</h3>

      {{ Form::label('updatesorder', 'Order updates by newest first:') }}
      {{ Form::checkbox('updatesorder', 1, $postorderpref) }}

      {{ Form::label('emailpref', 'Remove me from the email list:') }}
      {{ Form::checkbox('emailpref', 1, $emailpref) }}

      <br><br>
      {{ Form::submit('Save Settings', array('class' => 'btn btn-success full-width')) }}

    {{ Form::close() }}

@stop