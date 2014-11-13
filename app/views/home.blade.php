@extends('maintemplate')

@section('body')

<div id="sorts" class="button-group">
  <button data-sort-by="number" class="btn btn-primary">original order</button>
  <button data-sort-by="random" class="btn btn-primary">other</button>
</div>

  <div id="posts" class="row">

  <?php 

  $builds = DB::table('blogs')->get();
  $countBuilds = count($builds);

  $path = 'laravelcms/public';

  if ($builds) {

  	foreach ($builds as $build)
  	{
      $safeURLSlug = stringHelpers::safeURLSlug($build->blogtitle);
  	  echo "
        <a href='viewbuild/$build->id/$safeURLSlug'>
        <div id='$build->id' class='item col-md-3'>
          <div class='build-image'><img class='decoded' src='uploads/coverimages/'/></div>
          <p class='number'>($build->id) - $build->blogtitle</p>
        </div>
        </a>
      ";
  	}

  } else {
    echo "No builds returned";
    echo $countBuilds;
  }

	?>

  </div>

@stop

@section('scripts')
  <script src="http://isotope.metafizzy.co/isotope.pkgd.min.js"></script>

  <script>/*
    $(function() {
      var container = $('#posts');

      container.isotope({
        itemSelector : '.item',
        getSortData: {
          number: '.number'
        },
        animationEngine: 'css'
      });

      container.isotope({ sortBy : 'number' });

      $('#sorts').on( 'click', 'button', function() {
        var sortByValue = $(this).attr('data-sort-by');
        container.isotope({ sortBy: sortByValue });
      });

    });*/
  </script>
@stop