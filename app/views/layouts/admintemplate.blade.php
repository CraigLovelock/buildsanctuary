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
  <meta name="_token" content="{{ csrf_token() }}"/>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/0.2.0/Chart.min.js"></script>
</head>
<body>

  <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container admin-navbar-container">
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
            BuildSanctuary Admin
          </div>
        </a>
      </div>
      <div class="collapse navbar-collapse navbar-right">
        <ul class="nav navbar-nav centre-menu">
            <li role="presentation" class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                {{ Auth::user()->username; }} <span class="glyphicon glyphicon-user"></span> <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu">
                <li>{{ HTML::link('/accountsettings', 'Settings') }}</li>
                <li>{{ HTML::link('logout', 'Logout') }}</li>
              </ul>
            </li>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>

  <!-- Begin page content -->

  <div class="admin-container">
    <div class="pushdown-admin"></div>
    <div class="admin-sidebar">
      <ul>
        <li><span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span>{{ HTML::link('/admin', 'Dashboard') }}</li>
        <li><span class="glyphicon glyphicon-send" aria-hidden="true"></span>{{ HTML::link('/admin/sendemail', 'Send Emails') }}</li>
        <li><span class="glyphicon glyphicon-signal" aria-hidden="true"></span>{{ HTML::link('/admin/sendemail', 'Analytics') }}</li>
        <li><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>{{ HTML::link('/admin/sendemail', 'Blog Control') }}</li>
      </ul>
    </div>
    <div class="admin-rightpanel">
      @yield('body')
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

<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>
</body>
</html>