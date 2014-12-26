<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
  <title>{{ isset($pageTitle) ? $pageTitle : 'BuildSanctuary' }}</title>
  <link rel="stylesheet" href="{{ asset('css/main.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}" />
  <meta name="_token" content="{{ csrf_token() }}"/>
</head>
<body>

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
          <img src="/public_html/images/logo1.svg">
          </div>
        </a>
      </div>
      <div class="collapse navbar-collapse navbar-right">
        <form class="navbar-form navbar-left" action="search">
          <div class="input-group input-group-sm searchterm-box">
            <input type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term">
            <div class="input-group-btn">
              <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
            </div>
          </div>
        </form>
        <ul class="nav navbar-nav centre-menu">
          @if (Auth::check())
            <li class="add-build-btn">{{ HTML::link('managebuilds', 'Build Management') }}</li>
              <li role="presentation" class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                  {{ Auth::user()->username; }} <span class="glyphicon glyphicon-user"></span> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                  <li>{{ HTML::link('logout', 'Profile') }}</li>
                  <li>{{ HTML::link('/accountsettings', 'Settings') }}</li>
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
  if (Route::getCurrentRoute()->uri() == '/')
  {
    echo '
    <div class="full-width-design">

      <div class="home-header-image">
        <div style="position: absolute; left: 50%;">
          <div class="attention-line">
            The Home of Awesome Projects
          </div>
      </div>
        <img src="http://media-cache-ak0.pinimg.com/736x/39/7a/48/397a48128eef8e0174ceb6d6208d36f2.jpg">
      </div>

      <div class="build-filter-options">
        <div class="build-filter-options-container">
        <div class="btn-group build-filter-button">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Newest <span class="caret"></span>
          </button>
          <ul class="dropdown-menu build-filter" role="menu">
            <li><a href="#">Newest</a></li>
            <li><a href="#">Following</a></li>
            <li><a href="#">Trending</a></li>
            <li class="divider"></li>
            <li><a href="#">Staff Picks</a></li>
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

  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

  <script>

  </script>

  @yield('scripts')

</body>
</html>