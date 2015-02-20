@extends('layouts/admintemplate')

@section('body')

<h1>Send Emails</h1>

<div class="line-divider"></div>

<h5>Choose a template from below<h5>

  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#buildsofweekmodal">Builds of the Week</button>
  <button type="button" class="btn btn-primary">Single Build Feature</button>
  <button type="button" class="btn btn-primary">Blank</button>

<!-- Modals -->
<div class="modal fade" id="buildsofweekmodal" tabindex="-1" role="dialog" aria-labelledby="buildsofweekmodalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Builds of the week</h4>
      </div>
      <div class="modal-body">
      {{ Form::open(array('url' => 'admin/newemail', 'target' => '_blank')) }}
        <label for="email-subject">Subject</label>
        <input type="text" class="form-control" placeholder="Email subject" name="emailsubject" value="Builds of the Week">
        <label for="email-title">Title</label>
        <input type="text" class="form-control" placeholder="Email title" name="email-title" value="Builds of the Week">

        <label for="email-heading">Main Heading</label>
        <input type="text" class="form-control" placeholder="Email main heading" name="email-heading" value="Builds of the Week">
        <label for="email-bodytext">Body Text</label>
        <textarea class="form-control" id="textarea" name="email-bodytext" placeholder="Email body text"></textarea>

        <label for="carimageone">Car One Details</label>
        <input type="text" class="form-control" placeholder="Email image URL" name="carimageone">
        <input type="text" class="form-control" placeholder="Enter image title" name="cartitleone">
        <input type="text" class="form-control" placeholder="Enter image content" name="carcontentone">
        <input type="text" class="form-control" placeholder="Enter build link" name="carlinkone">

        <label for="carimagetwo">Car Two Details</label>
        <input type="text" class="form-control" placeholder="Email image URL" name="carimagetwo">
        <input type="text" class="form-control" placeholder="Enter image title" name="cartitletwo">
        <input type="text" class="form-control" placeholder="Enter image content" name="carcontenttwo">
        <input type="text" class="form-control" placeholder="Enter build link" name="carlinktwo">

        <label for="carimagethree">Car Three Details</label>
        <input type="text" class="form-control" placeholder="Email image URL" name="carimagethree">
        <input type="text" class="form-control" placeholder="Enter image title" name="cartitlethree">
        <input type="text" class="form-control" placeholder="Enter image content" name="carcontentthree">
        <input type="text" class="form-control" placeholder="Enter build link" name="carlinkthree">

        <label for="alluserscheck">All Users</label>
        <input type="checkbox" name="alluserscheck">
        <label for="testusercheck">Test Send</label>
        <input type="checkbox" name="testusercheck">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" name="preview" class="btn btn-default" value="Preview">
        <input type="submit" class="btn btn-primary" name="send" value="Send">
      </div>
      {{ Form::close() }}
    </div>
  </div>
</div>

@stop

@section('scripts')

<script>
$(function(){

});
</script>

@stop