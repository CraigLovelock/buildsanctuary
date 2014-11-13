<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ isset($pageTitle) ? $pageTitle : 'BuildSanctuary' }}</title>
    <link rel="stylesheet" href="/css/main.css" />
    <link rel="stylesheet" href="http://getbootstrap.com/examples/sticky-footer-navbar/sticky-footer-navbar.css" />
    <link rel="stylesheet" href="/css/custom.css" />
    <meta name="_token" content="{{ csrf_token() }}"/>
</head>
<body>

	<div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
          @if (Auth::check())
            <span class="sr-only">Toggle navigation</span>
            {{ Auth::user()->username; }}  <span class="glyphicon glyphicon-user"></span>
          @else
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          @endif
        </button>
        <a class="navbar-brand" href="{{ URL::to('/') }}">BuildSanctuary</a>
      </div>
      <div class="collapse navbar-collapse navbar-right">
        <form class="navbar-form navbar-left">
          <div class="input-group input-group-sm searchterm-box">
            <input type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term">
            <div class="input-group-btn">
              <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
            </div>
          </div>
        </form>
        <ul class="nav navbar-nav centre-menu">
          @if (Auth::check())
            <li class="add-build-btn">{{ HTML::link('startbuild', 'Add Your Build') }}</li>
            <li>{{ HTML::link('logout', 'Profile') }}</li>
            <li>{{ HTML::link('/accountsettings', 'Settings') }}</li>
            <li>{{ HTML::link('logout', 'Logout') }}</li>
          @else
            <li><a href="{{ URL::to('/login') }}">Login</a></li>
            <li><a href="{{ URL::to('/register') }}">Register</a></li>
          @endif
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>

  <!-- Begin page content -->
  <div class="container">
    @yield('body')
  </div>

  <div class="footer">
    <div class="container">
      <p class="text-muted">© BuildSanctuary</p>
    </div>
  </div>

  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

  @yield('scripts')

</body>
</html>