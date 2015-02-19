@extends('layouts/maintemplate')

@section('body')

  <div class='loadingBuilds'>
    Loading<h3><span id="dots"></span></h3>
  </div>

    @include('includes/buildcard')

  <?php
    echo $builds->links();
  ?>

@stop

@section('scripts')

  <script>
    $(function() {
      var dots = 0;
      setInterval (type, 300);

      function type() {
        if(dots < 3) {
            $('#dots').append('.');
            dots++;
        } else {
            $('#dots').html('');
            dots = 0;
        }
      }
      var container = $('#builds');
      var pagination = $('.pagination');

      imagesLoaded(container, function() {
        $(".loadingBuilds").remove();
        container.isotope({
          itemSelector : '.item',
          getSortData: {
          number: '.number'
          },
          animationEngine: 'css'
        });
        //container.fadeIn();
        pagination.fadeIn();
      });

      $(".testfoo").on('click', function(){
        $('.item').removeClass('col-sm-3').addClass('col-sm-4');
        container.isotope();
      });

      //$('.build-filter-options').affix();

    });

  </script>
@stop