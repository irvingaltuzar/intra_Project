<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('title')
    @yield('script')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/menu_admin.css') }}" rel="stylesheet">
</head>
<body class="m-0">
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 menu">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100 h-100 pb-2">
                    <div class="text-center w-100 border-bottom pb-2">
                        @if(auth()->user()->photo)
                            <img src="{{auth()->user()->photo_src}}" class="img-fluid person w-100">
                        @else
                            @if(auth()->user()->sex=='Masculino')
                                <img src="{{ asset('image/icons/masculino.svg')}}" class="img-fluid person w-100w-100">
                            @elseif(auth()->user()->sex=='Femenino')
                                <img src="{{ asset('image/icons/femenino.svg')}}" class="img-fluid person w-100">
                            @else
                                <img src="{{ asset('image/icons/general.svg')}}" class="img-fluid person w-100">
                            @endif
                        @endif
                        <div class="first-name d-none d-sm-block pt-1">{{auth()->user()->name}}</div>
                        <div class="last-name d-none d-sm-block pb-2">{{auth()->user()->last_name}}</div>
                    </div>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start pt-3" id="menu">
                        <li class="nav-item">
                            <a href="{{route('admin.benefit')}}" class="nav-link align-middle px-0">
                                <i class="fas fa-hand-holding-medical"></i> <span class="ms-1 d-none d-sm-inline">Beneficios</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link align-middle px-0">
                            <i class="fas fa-hand-holding-usd"></i> <span class="ms-1 d-none d-sm-inline">Prestaciones</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link align-middle px-0" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >
                                <i class="fas fa-sign-out-alt"></i> <span class="ms-1 d-none d-sm-inline">Cerrar sesión</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                    <svg version="1.1" id="GDMI-logo" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 150.8 42.6" style="enable-background:new 0 0 150.8 42.6;" xml:space="preserve">
                        <style type="text/css">
                            .st0{fill:#FFFFFF;}
                        </style>
                        <g>
                            <path class="st0" d="M4,29.1c-2.6,0-4,1.5-4,4.2v4.4c0,3.5,1.2,5,4,5c1.5,0,2.7-0.3,3.9-0.9v-5.8H5.4V37h1.2v3.8l-0.1,0
                                c-0.4,0.3-1.3,0.6-2.5,0.6c-1.9,0-2.6-1.1-2.6-3.8v-4.5c0-2.7,1.8-3,2.6-3c1.7,0,2.5,0.8,2.5,2.4H8c0-1.2-0.2-2-0.8-2.6
                                C6.6,29.4,5.6,29.1,4,29.1"></path>
                            <path class="st0" d="M29.6,42.6c2.8,0,4-1.5,4-5v-8.3h-1.4v8.4c0,2.7-0.7,3.8-2.6,3.8c-1.9,0-2.6-1.1-2.6-3.8v-8.4h-1.4v8.3
                                C25.5,41.1,26.7,42.6,29.6,42.6"></path>
                            <path class="st0" d="M40.4,35.3v-4.8h2.5c0.1,0,2,0,2,2.4c0,2.2-1.8,2.4-2.5,2.4H40.4z M46.4,33c0-2.5-1.2-3.6-3.6-3.6H39v13h1.4
                                v-5.9h2.4C43.4,36.5,46.4,36.3,46.4,33"></path>
                            <path class="st0" d="M57.3,37.7c0,2.7-0.7,3.8-2.6,3.8c-1.9,0-2.6-1.1-2.6-3.8v-4.5c0-2.7,1.8-3,2.6-3c0.8,0,2.6,0.3,2.6,3V37.7z
                                M54.7,29.1c-2.6,0-4,1.5-4,4.2v4.4c0,3.5,1.2,5,4,5c2.8,0,4-1.5,4-5v-4.4C58.7,30.6,57.3,29.1,54.7,29.1"></path>
                            <path class="st0" d="M76.2,9.6v23.1h3.6c3.4,0,5.4-1.3,5.4-6.1V16.2c0-4.8-2-6.6-5.4-6.6H76.2z M65.6,0H80c10.6,0,15.8,4.2,15.8,15
                                v12.5c0,10.8-5.2,14.9-15.8,14.9H65.6V0z"></path>
                            <polygon class="st0" points="122.9,42.4 130.2,13.3 130.2,42.4 	"></polygon>
                            <rect x="140.2" y="0" class="st0" width="10.6" height="42.4"></rect>
                            <polygon class="st0" points="110.8,0 118,29.4 125.3,0 	"></polygon>
                            <polygon class="st0" points="113.2,42.4 105.9,13.3 105.9,42.4 	"></polygon>
                            <path class="st0" d="M14.7,35.3v-4.8h2.5c0.1,0,2,0,2,2.4c0,2.2-1.8,2.4-2.5,2.4H14.7z M19.4,42.3h1.4l-2.4-6l0.1,0
                                c1-0.3,2.2-1.2,2.2-3.3c0-2.5-1.2-3.6-3.5-3.6h-3.8v13h1.4v-5.9h2.3L19.4,42.3z"></path>
                        </g>
                    </svg>
                </div>

            </div>
            <main class="col p-0 min-vh-100 m-md-4 m-2">
                <div class="bg-light  rounded-3 p-md-3 p-2 h-100 ">
                    @yield('content')
                </div>
            </main>
        </div>

    </div>
    @include('layouts.footer')
</body>


