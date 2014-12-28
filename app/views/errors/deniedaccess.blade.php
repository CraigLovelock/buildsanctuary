@extends('layouts/maintemplate')

@section('body')

  <div class='alert alert-info centre-text' role='alert'>
  <span class='glyphicon glyphicon-remove'></span> <b>Sorry! You do not have access to this action.</b><br>
    You have either tried accessing a page without permission or are trying a malicious attempt.
    <br>
    If you think this is a mistake, report this as a bug and let us know.
  </div>

@stop

@section('scripts')

<script>
$(document).ready(function() {

});
</script>

@stop