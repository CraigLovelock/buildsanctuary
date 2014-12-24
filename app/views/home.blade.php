@extends('maintemplate')

@section('body')

<div id="sorts" class="button-group">
@if (Auth::check())
  <button data-sort-by="random" class="btn btn-primary">Newest</button>
  <button data-sort-by="number" class="btn btn-primary">Following</button>
  <button data-sort-by="random" class="btn btn-primary">Trending</button>
  <button data-sort-by="random" class="btn btn-primary">Random</button>
@else
  <button data-sort-by="random" class="btn btn-primary">Newest</button>
  <button data-sort-by="random" class="btn btn-primary">Trending</button>
  <button data-sort-by="random" class="btn btn-primary">Random</button>
@endif
</div>

  <div id="builds" class="row">

  <?php 

  $builds = DB::table('blogs')->where('frontpage', '1')->orderBy('id', 'desc')->paginate(15);
  $countBuilds = count($builds);

  $path = '';

  if ($builds) {

  	foreach ($builds as $build)
  	{
      $safeURLSlug = stringHelpers::safeURLSlug($build->blogtitle);
  	  echo '
        <a href="viewbuild/'.$build->id.'/'.$safeURLSlug.'">
          <div class="item col-sm-3">
            <div class="thumbnail">
              <img src="user_uploads/cover_images/'.$build->coverimage.'.jpeg"">
              <div class="caption">
                <h6>'.$build->blogtitle.'</h6>
              </div>
            </div>
          </div>
        </a>
      ';
  	}

  } else {
    echo "No builds returned";
    echo $countBuilds;
  }

	?>

  </div>

  <?php echo $builds->links(); ?>

@stop

@section('scripts')
  <script src="http://imagesloaded.desandro.com/imagesloaded.pkgd.min.js"></script>
  <script src="http://isotope.metafizzy.co/isotope.pkgd.min.js"></script>

  <script>
    $(function() {
      var container = $('#builds');

      imagesLoaded(container, function() {
        container.fadeIn();
        container.isotope({
          itemSelector : '.item',
          getSortData: {
          number: '.number'
          },
          animationEngine: 'css'
        });
      });

    });

  </script>
@stop