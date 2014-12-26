@extends('maintemplate')

@section('body')

  <?php
  $resultsCount = count($results);

  if ($resultsCount > 0) {

    echo "
      <div class='alert alert-success centre-text not-full-width-alert' role='alert'>
        <b>Sweet,</b> Your search returned $resultsCount results.
      </div>
    ";
    echo "<div id='builds' class='row'>";

    foreach ($results as $build)
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

    echo "</div>";

  } else {
    echo "
    <div class='row'>
      <div class='alert alert-danger centre-text not-full-width-alert' role='alert'>
        <b>Oh snap!</b><br>
        Your search returned 0 results. Check your spelling or broaden your term.
      </div>
    </div>
    ";
  }

  ?>

  <?php echo $results->links(); ?>

  </div>


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