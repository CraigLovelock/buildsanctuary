<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
  <title>BuildSanctuary | {{ isset($pageTitle) ? $pageTitle : 'The Home of Automotive Projects' }}</title>
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
  <link rel="stylesheet" href="{{ asset('production_assets/css/global.css') }}" />
  <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css">
  <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
  <meta name="_token" content="{{ csrf_token() }}"/>
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&appId=153556418050242&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>

	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
          @if (Auth::check())
            <span class="sr-only">Toggle navigation</span>
            Menu
          @else
            <span class="sr-only">Toggle navigation</span>
            Menu
          @endif
        </button>
        <a class="navbar-brand" href="{{ URL::to('/') }}">
          <div>
            <img src="{{ asset('/') }}images/newlogo.svg">
          </div>
        </a>
        <!--<div class="fb-like" data-href="https://www.facebook.com/buildsanctuary" data-layout="button_count" data-action="like" data-show-faces="false"></div>-->
      </div>
      <div class="collapse navbar-collapse navbar-right">
        <form class="navbar-form navbar-left" action="<?php echo asset('/') . 'search'; ?>">
          <div class="input-group input-group-sm searchterm-box">
            <input type="text" class="form-control" placeholder="Search" name="term" id="term" value="{{ Input::old('term'); }}">
            <div class="input-group-btn">
              <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
            </div>
          </div>
        </form>
        <ul class="nav navbar-nav centre-menu">
          @if (Auth::check())

            <li role="presentation" class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                <span class="glyphicon glyphicon-plus"></span> <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu">
                <li> {{ HTML::link('startbuild', 'Build Project') }}</li>
              </ul>
            </li>

            <li role="presentation" class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                {{ Auth::user()->username; }} <span class="glyphicon glyphicon-user"></span> <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu">
                @if (Auth::user()->rights == 5)
                  <li> {{ HTML::link('admin', 'Admin Area') }}</li>
                @endif
                <li>{{ HTML::link('managebuilds', 'Build Management') }}</li>
                <li>{{ HTML::link('/accountsettings', 'Account Settings') }}</li>
                <li>{{ HTML::link('logout', 'Logout') }}</li>
              </ul>
            </li>

          @else
            <li><a href="{{ URL::to('/login') }}">Login</a></li>
            <li><a href="{{ URL::to('/register') }}">Register</a></li>
          @endif
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>

  <!-- Begin page content -->

  <?php
  $sUri = '/';
  $sUri = Route::getCurrentRoute()->uri();
  if ($sUri == '/' || $sUri == '/viewbuild' || $sUri == 'newest' || $sUri == 'following' || $sUri == 'trending' || $sUri == 'staff-picks')
  {
    $currentBuildType = Route::getCurrentRoute()->uri();
    switch ($currentBuildType) {
      case '/':
      case 'newest':
        $currentBuildType = 'Newly Updated';
        break;

      case 'staff-picks':
        $currentBuildType = 'Staff Picks';
        break;

      default:
        $currentBuildType = ucfirst($currentBuildType);
        break;
    }
    echo '
    <div class="full-width-design">

      <div class="home-header-image">
          <div class="attention-line">
            The Home of Awesome Projects
          </div>
        <img src="http://media-cache-ak0.pinimg.com/736x/39/7a/48/397a48128eef8e0174ceb6d6208d36f2.jpg">
      </div>

      <div class="build-filter-options">
        <div class="build-filter-options-container">
        <div class="btn-group build-filter-button">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Filter: '. $currentBuildType .' <span class="caret"></span>
          </button>
          <ul class="dropdown-menu build-filter" role="menu">
            <li><a href="newest">Newly Updated</a></li>
            <li><a href="following">Following</a></li>
            <li><a href="trending">Trending</a></li>
            <li class="divider"></li>
            <li><a href="staff-picks">Staff Picks</a></li>
          </ul>
        </div>
        </div>
      </div>

    </div>
    <div class="pushdown-100px"></div>
    ';
  }
  ?>

  <div class="container full-width-container">
    @yield('body')
  </div>

  <div class="footer">
    <div class="container">
      <p class="text-muted">© BuildSanctuary</p>
    </div>
  </div>

  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script type="text/javascript" src="{{ asset('production_assets/js/production.min.js')}}"></script>

  <script>
  $(function() {
    FastClick.attach(document.body);
  });
  </script>

  @yield('scripts')

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-46885749-1', 'auto');
  ga('send', 'pageview');

</script>

<!--<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>-->
</body>
</html>
