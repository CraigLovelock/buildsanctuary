@extends('layouts/maintemplate')

@section('body')

  <div class='alert alert-info centre-text' role='alert'>
  <h1><span class='glyphicon glyphicon-thumbs-down'></span> <b>Well this is awkward.</b></h1>
  <br>
  The page requested cannot be found... double check the url or report this as an issue.
  </div>

@stop

@section('scripts')

<script>
$(document).ready(function() {

});
</script>

@stop