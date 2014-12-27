@extends('layouts/maintemplate')

@section('body')

	<div class="small-form modal-white">

		<h3 class="no-top-margin">Account Settings</h3>

  	@if(Session::has('success'))
    	<div class="alert alert-success" role="alert">
    		<span class="glyphicon glyphicon-ok"></span>
    		  Settings updated!
    	</div>
    @endif

	  <a href="updatepassword">
	  	<button type="button" class="btn btn-primary three-width form-control">Change Password</button>
	  </a>
	  <a href="updatecontact">
	  	<button type="button" class="btn btn-primary three-width form-control">Update Contact Details</button>
	  </a>

	</div>

@stop