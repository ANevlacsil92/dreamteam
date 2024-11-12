<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('images/logos/favicon.ico')}}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script type="text/javascript" src="{{mix('js/app.js')}}" defer></script>
    <!-- Bootstrap CSS CDN 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    --><!-- Our Custom CSS -->
    <link rel="stylesheet" href="{{mix('css/app.css')}}">


    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS 
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    <style>
        @media print {
            body {
                background-color: #fff;
            }
        }
    </style>

</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-black shadow-sm">
            <div class="container h-100">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-5">
                        <a class="navbar-brand" href="/">
                            <img src="{{ asset('images/logos/Dreamteam_Logo_bunt.svg') }}" height="100%" alt="">
                        </a>

                        
                    </ul>
                    <ul class="navbar-nav ml-md-5">

                        <li class="nav-item">
                            <a class="nav-link" href="/das-team" style="color: #f39200!important; font-weight:bold">Das Team</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-md-3">

                        <li class="nav-item">
                            <a class="nav-link" href="/produktionen" style="color: #f39200!important; font-weight:bold">Produktionen</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}" style="color: #f39200!important; font-weight:bold">{{ __('Login') }}</a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <!--<li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}" style="color: #f39200!important; font-weight:bold">{{ __('Register') }}</a>
                        </li>-->
                        @endif
                        @else

                        @if (Auth::user()->email == 'a.nevlacsil@hotmail.com' || Auth::user()->email == 'alexander@nevlacsil.at')
                        <li class="nav-item">
                            <a class="nav-link" href="/members/temperature" style="color: #f39200!important; font-weight:bold">Heizungssteuerung</a>
                        </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link" href="/members" style="color: #f39200!important; font-weight:bold">Members</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/members/settings" style="color: #f39200!important; font-weight:bold">Einstellungen</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}" style="color: #f39200!important; font-weight:bold" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }} {{ Auth::user()->name }}
                            </a>
                        </li>
                        <form id="logout-form" action="/logout" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')

    </div>
</body>

</html>