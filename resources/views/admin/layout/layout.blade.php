<!DOCTYPE html>
<html lang="es">

<head>
    <title>Intranet DMI - Administrador</title>
    <!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 10]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    @if(auth()->user() != null)
        {{-- <meta http-equiv="refresh" content="1800; url='{{url('/expire-session')}}';"> --}}
        <meta id="auth_user" content="{{auth()->user()->usuario}}">
    @endif
    <meta id="url_base" content="{{asset(url(''))}}">
    <meta id="current_date" content="{{\Carbon\Carbon::now()->format('Y-m-d')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
    <!-- waves.css -->
    <link rel="stylesheet" href="{{asset('assets_admin/pages/waves/css/waves.min.css')}}" type="text/css" media="all">
    <!-- Required Fremwork -->
    {{-- <link rel="stylesheet" type="text/css" href="{{asset('assets_admin/css/bootstrap/css/bootstrap.min.css')}}"> --}}
    <link rel="stylesheet" type="text/css" href="{{asset('bootstrap_v5/css/bootstrap.css')}}">
    <script  src="{{asset('bootstrap_v5/js/bootstrap.bundle.min.js')}}"></script>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>  --}}
    <!-- themify icon -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets_admin/icon/themify-icons/themify-icons.css')}}">
    <!-- font-awesome-n -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <!-- scrollbar.css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets_admin/css/jquery.mCustomScrollbar.css')}}">
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets_admin/css/style.css')}}">
    {{-- Estilos generales --}}
    <link rel="stylesheet" type="text/css" href="{{asset('assets_admin/css/styles_global.css')}}">
    <!-- Notification.css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets_admin/pages/notification/notification.css')}}">
    <!-- SweetAlert.css -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    {{-- bootstrap-select css --}}
    <link rel="stylesheet" type="text/css" href="{{asset('bootstrap-select/css/bootstrap-select.min.css')}}">



</head>

<body>
    <!-- Pre-loader start -->
    <div class="theme-loader">
        <div class="loader-track">
            <div class="preloader-wrapper">
                <div class="spinner-layer spinner-blue">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
                <div class="spinner-layer spinner-gray">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pre-loader end -->

    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <nav class="navbar header-navbar pcoded-header">
                <div class="navbar-wrapper">
                    <div class="navbar-logo">
                        <a class="mobile-menu waves-effect waves-light" id="mobile-collapse" href="#!">
                            <i class="ti-menu"></i>
                        </a>
                        <div class="mobile-search waves-effect waves-light">
                            <div class="header-search">
                                <div class="main-search morphsearch-search">
                                    <div class="input-group">
                                        <span class="input-group-prepend search-close"><i class="ti-close input-group-text"></i></span>
                                        <input type="text" class="form-control" placeholder="Enter Keyword">
                                        <span class="input-group-append search-btn"><i class="ti-search input-group-text"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="{{route('home')}}">
                            <img class="img-fluid" src="{{asset('/image/logo-grupo-dmi.svg')}}" width="50%" alt="Theme-Logo" />
                        </a>
                        {{-- <a class="mobile-options waves-effect waves-light">
                            <i class="ti-more"></i>
                        </a> --}}
                    </div>
                    <div class="navbar-container container-fluid">
                        <ul class="nav-right">
                            <li class="header-notification">
                                {{-- <a href="#!" class="waves-effect waves-light">
                                    <i class="ti-bell"></i>
                                    <span class="badge bg-c-red"></span>
                                </a> --}}
                                <ul class="show-notification">
                                    <li>
                                        <h6>Notificaciones</h6>
                                        <label class="label label-danger">Nuevo</label>
                                    </li>
                                    <li class="waves-effect waves-light">
                                        <div class="media">
                                            <img class="d-flex align-self-center img-radius" src="{{asset('assets_admin/images/avatar-2.jpg')}}" alt="Generic placeholder image">
                                            <div class="media-body">
                                                <h5 class="notification-user">{{auth()->user()->name}} {{auth()->user()->last_name}}</h5>
                                                <p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer elit.</p>
                                                <span class="notification-time">30 minutes ago</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li class="user-profile header-notification">
                                <a href="#!" class="waves-effect waves-light">
                                    @if(auth()->user()->photo)
                                        <img onerror="this.src='/image/icons/user.svg'" src="{{auth()->user()->photo_src}}" class="img-radius" alt="User-Profile-Image">
                                    @else
                                        @if(auth()->user()->sex == 'Masculino')
                                            <img onerror="this.src='/image/icons/user.svg'" src="{{asset('image/icons/masculino.svg')}}" class="img-radius" alt="User-Profile-Image">
                                        @elseif(auth()->user()->sex=='Femenino')
                                            <img onerror="this.src='/image/icons/user.svg'" src="{{asset('image/icons/femenino.svg')}}" class="img-radius" alt="User-Profile-Image">
                                        @endif
                                    @endif
                                    <span>{{auth()->user()->name}} {{auth()->user()->last_name}}</span>
                                    <i class="ti-angle-down"></i>
                                </a>
                                <ul class="show-notification profile-notification">
                                    {{-- <li class="waves-effect waves-light">
                                        <a href="{{route('home')}}">
                                            <i class="fas fa-desktop"></i> Intraner DMI
                                        </a>
                                    </li> --}}
                                    <li class="waves-effect waves-light">
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                            document.getElementById('logout-form').submit();"><i class="fas fa-desktop"></i> Volver a Intraner DMI</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                            <input type="hidden" name="redirect_home_public" value="true">
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            @php
                $module = explode('/',Request::route()->uri());
            @endphp

            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <nav class="pcoded-navbar">
                        <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
                        <div class="pcoded-inner-navbar main-menu">
                            <div class="">
                                <div class="main-menu-header">
                                    @if(auth()->user()->photo)
                                        <img onerror="this.src='/image/icons/user.svg'" class="img-80 img-radius" src="{{auth()->user()->photo_src}}" alt="User-Profile-Image">
                                    @else
                                        @if(auth()->user()->sex == 'Masculino')
                                            <img onerror="this.src='/image/icons/user.svg'" class="img-80 img-radius" src="{{asset('image/icons/masculino.svg')}}" alt="User-Profile-Image">
                                        @elseif(auth()->user()->sex=='Femenino')
                                            <img onerror="this.src='/image/icons/user.svg'" class="img-80 img-radius" src="{{asset('image/icons/femenino.svg')}}" alt="User-Profile-Image">
                                        @endif
                                    @endif

                                    <div class="user-details">
                                        <span id="more-details">{{auth()->user()->name}} {{auth()->user()->last_name}}<i class="fa fa-caret-down"></i></span>
                                    </div>
                                </div>
                                <div class="main-menu-content">
                                    <ul>
                                        <li class="more-details">
                                            <a href="{{route('home')}}"><i class="fas fa-desktop"></i> Intraner DMI</a>
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                                document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <br>
                            <div class="pcoded-navigation-label">Navigation</div>
                            <ul class="pcoded-item pcoded-left-item ">
                                <li class="{{ (\Request::route()->getName() == 'admin') ? 'active' : '' }}">
                                    <a href="{{route('admin')}}" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="fas fa-tachometer-alt-slow"></i><b>D</b></span>
                                        <span class="pcoded-mtext">Dashboard</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                            </ul>
                            @if(in_array('Comunicados', Session('permission.seccion')))
                                <ul class="pcoded-item pcoded-left-item">
                                    <li class="pcoded-hasmenu {{isset($module[1]) && $module[1] == 'communiques' ? 'active pcoded-trigger' : ''}} ">
                                        <a href="javascript:void(0)" class="waves-effect waves-dark">
                                            <span class="pcoded-micon"><i class="fas fa-bullhorn"></i><b>C</b></span>
                                            <span class="pcoded-mtext">Comunicados</span>
                                            <span class="pcoded-mcaret"></span>
                                        </a>
                                        <ul class="pcoded-submenu">
                                            @if(in_array("Mensajes de Consejo", Session('permission.permissions')))
                                                <li class="{{ (\Request::route()->getName() == 'admin.communiques.council') ? 'active' : '' }}">
                                                    <a href="{{route('admin.communiques.council')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                        <span class="pcoded-mtext">Comunicados Institucionales</span>
                                                        <span class="pcoded-mcaret"></span>
                                                    </a>
                                                </li>
                                            @endif

                                            @if(in_array("Movimientos Organizacionales", Session('permission.permissions')))
                                                <li class="{{ (\Request::route()->getName() == 'admin.communiques.organizational') ? 'active' : '' }}">
                                                    <a href="{{route('admin.communiques.organizational')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                        <span class="pcoded-mtext">Movimientos Organizacionales</span>
                                                        <span class="pcoded-mcaret"></span>
                                                    </a>
                                                </li>
                                            @endif

                                            @if(in_array("Campañas Institucionales", Session('permission.permissions')))
                                                <li class="{{ (\Request::route()->getName() == 'admin.communiques.institutional') ? 'active' : '' }}">
                                                    <a href="{{route('admin.communiques.institutional')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                        <span class="pcoded-mtext">Campañas Institucionales</span>
                                                        <span class="pcoded-mcaret"></span>
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                </ul>
                            @endif

                            @if(in_array('Colaboradores', Session('permission.seccion')))
                                <ul class="pcoded-item pcoded-left-item">
                                    <li class="pcoded-hasmenu {{isset($module[1]) && $module[1] == 'collaborators' ? 'active pcoded-trigger' : ''}}">
                                        <a href="javascript:void(0)" class="waves-effect waves-dark">
                                            <span class="pcoded-micon"><i class="fas fa-users"></i><b>C</b></span>
                                            <span class="pcoded-mtext">Colaboradores</span>
                                            <span class="pcoded-mcaret"></span>
                                        </a>
                                        <ul class="pcoded-submenu">
                                            @if(in_array("Condolencias", Session('permission.permissions')))
                                                <li class="{{ (\Request::route()->getName() == 'admin.collaborators.condolences') ? 'active' : '' }} ">
                                                    <a href="{{route('admin.collaborators.condolences')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                        <span class="pcoded-mtext">Condolencias</span>
                                                        <span class="pcoded-mcaret"></span>
                                                    </a>
                                                </li>
                                            @endif
                                            @if(in_array("Nacimientos", Session('permission.permissions')))
                                                <li class="{{ (\Request::route()->getName() == 'admin.collaborators.births') ? 'active' : '' }}">
                                                    <a href="{{route('admin.collaborators.births')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                        <span class="pcoded-mtext">Nacimientos</span>
                                                        <span class="pcoded-mcaret"></span>
                                                    </a>
                                                </li>
                                            @endif
                                            @if(in_array("Ascensos", Session('permission.permissions')))
                                                <li class="{{ (\Request::route()->getName() == 'admin.collaborators.promotions') ? 'active' : '' }} ">
                                                    <a href="{{route('admin.collaborators.promotions')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                        <span class="pcoded-mtext">Ascensos</span>
                                                        <span class="pcoded-mcaret"></span>
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                </ul>
                            @endif

                            @if(in_array('Noticias', Session('permission.seccion')))
                                <ul class="pcoded-item pcoded-left-item">
                                    <li class="pcoded-hasmenu {{isset($module[1]) && $module[1] == 'news' ? 'active pcoded-trigger' : ''}}">
                                        <a href="javascript:void(0)" class="waves-effect waves-dark">
                                            <span class="pcoded-micon"><i class="fas fa-newspaper"></i><b>N</b></span>
                                            <span class="pcoded-mtext">Noticias</span>
                                            <span class="pcoded-mcaret"></span>
                                        </a>
                                        <ul class="pcoded-submenu">
                                            @if(in_array("Fechas Conmemorativas", Session('permission.permissions')))
                                                <li class="{{ (\Request::route()->getName() == 'admin.news.conmmemorative_date') ? 'active' : '' }}">
                                                    <a href="{{route('admin.news.conmmemorative_date')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                        <span class="pcoded-mtext">Fechas conmemorativas</span>
                                                        <span class="pcoded-mcaret"></span>
                                                    </a>
                                                </li>
                                            @endif

                                            @if(in_array("Posteo Interno", Session('permission.permissions')))
                                                <li class="{{ (\Request::route()->getName() == 'admin.news.internal_posting') ? 'active' : '' }}">
                                                    <a href="{{route('admin.news.internal_posting')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                        <span class="pcoded-mtext">Posteo Interno</span>
                                                        <span class="pcoded-mcaret"></span>
                                                    </a>
                                                </li>
                                            @endif

                                            @if(in_array("Encuestas", Session('permission.permissions')))
                                                <li class="{{ (\Request::route()->getName() == 'admin.news.poll') ? 'active' : '' }}">
                                                    <a href="{{route('admin.news.poll')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                        <span class="pcoded-mtext">Encuestas</span>
                                                        <span class="pcoded-mcaret"></span>
                                                    </a>
                                                </li>
                                            @endif

                                            @if(in_array("Aviso de Área", Session('permission.permissions')))
                                                <li class="{{ (\Request::route()->getName() == 'admin.news.area_notice') ? 'active' : '' }}">
                                                    <a href="{{route('admin.news.area_notice')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                        <span class="pcoded-mtext">Avisos de Áreas</span>
                                                        <span class="pcoded-mcaret"></span>
                                                    </a>
                                                </li>
                                            @endif

                                            @if(in_array("Políticas", Session('permission.permissions')))
                                                <li class="{{ (\Request::route()->getName() == 'admin.news.policy') ? 'active' : '' }}">
                                                    <a href="{{route('admin.news.policy')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                        <span class="pcoded-mtext">Políticas</span>
                                                        <span class="pcoded-mcaret"></span>
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                </ul>
                            @endif

                            @if(in_array('Eventos', Session('permission.seccion')))
                                <ul class="pcoded-item pcoded-left-item">
                                    @if(in_array("Eventos", Session('permission.permissions')))
                                        <li class="{{ (\Request::route()->getName() == 'admin.events') ? 'active' : '' }}">
                                            <a href="{{route('admin.events')}}" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="fas fa-calendar"></i><b>E</b></span>
                                                <span class="pcoded-mtext">Eventos</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            @endif

                            @if(in_array('Capacitaciones', Session('permission.seccion')))
                                <ul class="pcoded-item pcoded-left-item">
                                    @if(in_array("Capacitaciones", Session('permission.permissions')))
                                        <li class="{{ (\Request::route()->getName() == 'admin.training') ? 'active' : '' }}">
                                            <a href="{{route('admin.training')}}" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="fas fa-chalkboard-teacher"></i><b>C</b></span>
                                                <span class="pcoded-mtext">Capacitaciones</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            @endif

                            @if(in_array('Beneficios y Prestaciones', Session('permission.seccion')))
                                <ul class="pcoded-item pcoded-left-item">
                                    <li class="pcoded-hasmenu">
                                        <a href="javascript:void(0)" class="waves-effect waves-dark">
                                            <span class="pcoded-micon"><i class="fas fa-hand-holding-usd"></i><b>B y P</b></span>
                                            <span class="pcoded-mtext">Benef. y Prestaciones</span>
                                            <span class="pcoded-mcaret"></span>
                                        </a>
                                        <ul class="pcoded-submenu">
                                            @if(in_array("Beneficios", Session('permission.permissions')))
                                                <li class="{{ (\Request::route()->getName() == 'admin.benefits.benefits') ? 'active' : '' }}">
                                                    <a href="{{route('admin.benefits.benefits')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                        <span class="pcoded-mtext">Beneficios</span>
                                                        <span class="pcoded-mcaret"></span>
                                                    </a>
                                                </li>
                                            @endif

                                            @if(in_array("Prestaciones", Session('permission.permissions')))
                                                <li class="{{ (\Request::route()->getName() == 'admin.benefits.prestacion') ? 'active' : '' }}">
                                                    <a href="{{route('admin.benefits.prestacion')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                        <span class="pcoded-mtext">Prestaciones</span>
                                                        <span class="pcoded-mcaret"></span>
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                </ul>
                            @endif

                            @if(in_array('Cápsulas de Fundación', Session('permission.seccion')))
                                <ul class="pcoded-item pcoded-left-item">
                                    @if(in_array("Cápsulas de Fundación", Session('permission.permissions')))
                                        <li class="{{ (\Request::route()->getName() == 'admin.foundation') ? 'active' : '' }}">
                                            <a href="{{route('admin.foundation')}}" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="fas fa-hand-holding-heart"></i><b>C</b></span>
                                                <span class="pcoded-mtext">Cápsulas de Fundación</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            @endif

                            @if(in_array('Configuraciones', Session('permission.seccion')))
                                <ul class="pcoded-item pcoded-left-item">
                                    <li class="pcoded-hasmenu">
                                        <a href="javascript:void(0)" class="waves-effect waves-dark">
                                            <span class="pcoded-micon"><i class="fas fa-cogs"></i><b>C</b></span>
                                            <span class="pcoded-mtext">Configuraciones</span>
                                            <span class="pcoded-mcaret"></span>
                                        </a>
                                        <ul class="pcoded-submenu">
                                            @if(in_array("Control de Acceso", Session('permission.permissions')))
                                                <li class="{{ (\Request::route()->getName() == 'admin.settings.permissions') ? 'active' : '' }}">
                                                    <a href="{{route('admin.settings.permissions')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                        <span class="pcoded-mtext">Control de Acceso</span>
                                                        <span class="pcoded-mcaret"></span>
                                                    </a>
                                                </li>
                                            @endif

                                            @if(in_array("Roles", Session('permission.permissions')))
                                                <li class="{{ (\Request::route()->getName() == 'admin.settings.rol') ? 'active' : '' }}">
                                                    <a href="{{route('admin.settings.rol')}}" class="waves-effect waves-dark">
                                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                        <span class="pcoded-mtext">Roles</span>
                                                        <span class="pcoded-mcaret"></span>
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                </ul>
                            @endif

                        </div>
                    </nav>
                    <div class="pcoded-content">
                    <!-- Page-header end -->

                        <div class="pcoded-inner-content">
                            <!-- Main-body start -->
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <!-- Page-body start -->
                                        @yield('content')
                                    <!-- Page-body end -->
                                </div>
                                <div id="styleSelector"> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Jquery -->
    <script type="text/javascript" src="{{asset('assets_admin/js/jquery/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets_admin/js/jquery-ui/jquery-ui.min.js')}} "></script>
    <script type="text/javascript" src="{{asset('assets_admin/js/popper.js/popper.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets_admin/js/bootstrap/js/bootstrap.min.js')}} "></script>
    <!-- waves js -->
    <script src="{{asset('assets_admin/pages/waves/js/waves.min.js')}}"></script>
    <!-- jquery slimscroll js -->
    <script type="text/javascript" src="{{asset('assets_admin/js/jquery-slimscroll/jquery.slimscroll.js')}}"></script>

    <!-- slimscroll js -->
    <script src="{{asset('assets_admin/js/jquery.mCustomScrollbar.concat.min.js')}} "></script>

    <!-- menu js -->
    <script src="{{asset('assets_admin/js/pcoded.min.js')}}"></script>
    <script src="{{asset('assets_admin/js/vertical/vertical-layout.min.js')}} "></script>

    <script type="text/javascript" src="{{asset('assets_admin/js/script.js')}} "></script>
    <script type="text/javascript" src="{{asset('js/admin/general/function-repository.js')}} "></script>

    <!-- notification js -->
    <script type="text/javascript" src="{{asset('assets_admin/js/bootstrap-growl.min.js')}}"></script>


    <!-- bootstrap-select js -->
    <script type="text/javascript" src="{{asset('bootstrap-select/js/bootstrap-select.min.js')}}"></script>

    @yield('script_footer')




</body>

</html>
