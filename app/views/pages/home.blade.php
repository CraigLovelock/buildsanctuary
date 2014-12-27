@extends('layouts/maintemplate')

@section('body')

  <div class='loadingBuilds'>
    <h3><span id="dots"></span></h3>
  </div>

  <div id="builds" class="row">

    @include('includes/buildcard')

  </div>

  <?php
    echo $builds->links();
  ?>

@stop

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.0.4/jquery.imagesloaded.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.0.0/isotope.pkgd.min.js"></script>

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
        container.fadeIn();
        pagination.fadeIn();
        container.isotope({
          itemSelector : '.item',
          getSortData: {
          number: '.number'
          },
          animationEngine: 'css'
        });
      });

      $(".testfoo").on('click', function(){
        $('.item').removeClass('col-sm-3').addClass('col-sm-4');
        container.isotope();
      });

      $('.build-filter-options').affix({
        offset: {
          top: 90,
          bottom: function () {
            //return (this.bottom = $('.footer').outerHeight(true))
          }
        }
      });

    });

  </script>
@stop