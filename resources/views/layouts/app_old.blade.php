<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="CACHE-CONTROL" content="NO-CACHE">
    <meta http-equiv="EXPIRES" content="Mon, 22 Jul 2002 11:12:01 GMT">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('title')
    <!-- Scripts -->
    {{-- <script src="{{ asset('js/jquery-3.6.0.min.js') }}" defer></script> --}}
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/menu.css') }}"  defer rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/estilos_globales.css') }}" rel="stylesheet">
    <link href="{{ asset('css/general.css') }}" rel="stylesheet">
    
    
    @yield('script')
    @yield('css')
</head>
<body>
        @if (auth()->user())
            <header id="intranet-menu">
                <div class="logo">
                    <a href="{{ route('home') }}">
                        <svg version="1.1" id="GDMI-logo" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                viewBox="0 0 150.8 42.6" style="enable-background:new 0 0 150.8 42.6;" xml:space="preserve">
                            <style type="text/css">
                                .st0{fill:#FFFFFF;}
                            </style>
                            <g>
                                <path class="st0" d="M4,29.1c-2.6,0-4,1.5-4,4.2v4.4c0,3.5,1.2,5,4,5c1.5,0,2.7-0.3,3.9-0.9v-5.8H5.4V37h1.2v3.8l-0.1,0
                                    c-0.4,0.3-1.3,0.6-2.5,0.6c-1.9,0-2.6-1.1-2.6-3.8v-4.5c0-2.7,1.8-3,2.6-3c1.7,0,2.5,0.8,2.5,2.4H8c0-1.2-0.2-2-0.8-2.6
                                    C6.6,29.4,5.6,29.1,4,29.1"/>
                                <path class="st0" d="M29.6,42.6c2.8,0,4-1.5,4-5v-8.3h-1.4v8.4c0,2.7-0.7,3.8-2.6,3.8c-1.9,0-2.6-1.1-2.6-3.8v-8.4h-1.4v8.3
                                    C25.5,41.1,26.7,42.6,29.6,42.6"/>
                                <path class="st0" d="M40.4,35.3v-4.8h2.5c0.1,0,2,0,2,2.4c0,2.2-1.8,2.4-2.5,2.4H40.4z M46.4,33c0-2.5-1.2-3.6-3.6-3.6H39v13h1.4
                                    v-5.9h2.4C43.4,36.5,46.4,36.3,46.4,33"/>
                                <path class="st0" d="M57.3,37.7c0,2.7-0.7,3.8-2.6,3.8c-1.9,0-2.6-1.1-2.6-3.8v-4.5c0-2.7,1.8-3,2.6-3c0.8,0,2.6,0.3,2.6,3V37.7z
                                    M54.7,29.1c-2.6,0-4,1.5-4,4.2v4.4c0,3.5,1.2,5,4,5c2.8,0,4-1.5,4-5v-4.4C58.7,30.6,57.3,29.1,54.7,29.1"/>
                                <path class="st0" d="M76.2,9.6v23.1h3.6c3.4,0,5.4-1.3,5.4-6.1V16.2c0-4.8-2-6.6-5.4-6.6H76.2z M65.6,0H80c10.6,0,15.8,4.2,15.8,15
                                    v12.5c0,10.8-5.2,14.9-15.8,14.9H65.6V0z"/>
                                <polygon class="st0" points="122.9,42.4 130.2,13.3 130.2,42.4 	"/>
                                <rect x="140.2" y="0" class="st0" width="10.6" height="42.4"/>
                                <polygon class="st0" points="110.8,0 118,29.4 125.3,0 	"/>
                                <polygon class="st0" points="113.2,42.4 105.9,13.3 105.9,42.4 	"/>
                                <path class="st0" d="M14.7,35.3v-4.8h2.5c0.1,0,2,0,2,2.4c0,2.2-1.8,2.4-2.5,2.4H14.7z M19.4,42.3h1.4l-2.4-6l0.1,0
                                    c1-0.3,2.2-1.2,2.2-3.3c0-2.5-1.2-3.6-3.5-3.6h-3.8v13h1.4v-5.9h2.3L19.4,42.3z"/>
                            </g>
                        </svg>
                    </a>
                </div>
                <div class="hamburguer">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="information">
                    <div class="notifications">
                        <img src="{{asset('image/icons/bell.svg')}}" class="img-fluid icon bell-icon">
                        <span class="number">1</span>
                    </div>
                    <div class="dropdown" id="dropdown_menu">
                        <div class="user dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="{{asset('image/icons/user.svg')}}" class="img-fluid icon user-icon">
                            <div class="name-user text-uppercase">
                                <div class="first-name">{{auth()->user()->name}}</div>
                                <div class="last-name">{{auth()->user()->last_name}}</div>
                            </div>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton2">
                            <li><a class="dropdown-item" href="{{ route('perfil') }}">Mi perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a  class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Cerrar sesión</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                    
                </div>

                <div class="menu">
                    <div class="container-menu">
                        <div class="content-menu">
                            <ul>
                                <li>
                                    <a href="{{ route('home') }}" class="{{ (\Request::route()->getName() == 'home') ? 'active' : '' }}">Inicio</a>
                                </li>
                                <li>
                                    <a href="{{ route('quienesSomos') }}" class="{{ (\Request::route()->getName() == 'quienesSomos') ? 'active' : '' }}">Quienes somos 
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás el Manifiesto, la Historia de Grupo DMI, los Valores de la Empresa y el Código de Ética.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a  href="{{ route('organigrama') }}" class="{{ (\Request::route()->getName() == 'organigrama') ? 'active' : '' }}"><strong>DMI</strong> Organigrama
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás el organigrama de Grupo DMI por División de Negocio: DMI Bienes Raíces, DMI Desarrollo de Negocios y DMI Responsabilidad Social.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a  href="{{ route('communication') }}" class="{{ (\Request::route()->getName() == 'communication') ? 'active' : '' }}"><strong>DMI</strong> Comunicados
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás información importante como Mensajes de Consejo, Movimientos Organizaciones y Campañas Institucionales.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a  href="{{ route('collaborators') }}" class="{{ (\Request::route()->getName() == 'collaborators') ? 'active' : '' }}"><strong>DMI</strong> Colaboradores
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás información relacionada a tus compañeros: Cumpleaños, Promociones, Nuevos Ingresos, Aniversarios, Nacimientos y Condolencias. ">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('news') }}" class="{{ (\Request::route()->getName() == 'news') ? 'active' : '' }}" href="#"><strong>DMI</strong> Noticias
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás información del día a día como: Fechas Conmemorativas, Vacantes en Posteo Interno, Encuestas, Avisos de Área y de Políticas.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('blog')}}" class="{{ (\Request::route()->getName() == 'blog' ? 'active' : '') }}"><strong>DMI</strong> Blog
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás la Revista Digital de Grupo DMI y Notas de Blog de interés y cultura general.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('events')}}" class="{{ (\Request::route()->getName() == 'events' ? 'active' : '') }}"><strong>DMI</strong> Eventos
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás el calendario mensual de Días Festivos y Eventos de tu Unidad de Negocio.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('fundacion') }}" class="{{ (\Request::route()->getName() == 'fundacion') ? 'active' : '' }}">Fundación <strong>DMI</strong>
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás información relacionada a Fundación Educación y Salud.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('trainings') }}" class="{{ (\Request::route()->getName() == 'trainings' ? 'active' : '') }}">Capacitación
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás el calendario de capacitaciones.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('benefit')}}" class="{{ (\Request::route()->getName() == 'benefit') ? 'active' : '' }}">Beneficios y prestaciones
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás la información de los Beneficios (Convenios con externos y exclusivos de Grupo DMI) y Prestaciones (prestaciones con las que cuentas superiores a las de Ley).">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('directory') }}" class="{{ (\Request::route()->getName() == 'directory') ? 'active' : '' }}">Directorio
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás las extensiones, correos electrónicos, puesto y ubicación física de tus compañeros.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('perfil') }}" class="{{ (\Request::route()->getName() == 'perfil') ? 'active' : '' }}">Mi Perfil
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás tus datos de colaborador como: número de empleado, horario laboral, correo, extensión.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">Mapa del Sitio
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tinciLorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tinci">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>
        @endif
        @yield('content')
        @yield('script_footer')
</body>
</html>
