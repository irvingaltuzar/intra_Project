<html lang="es">
    {{-- Agregar paramétro para siempre cargar los script y link --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                updateElementCache();
                setTimeout(() => {
                    
                }, 500);
            });
            function updateElementCache(){
                let links = document.querySelectorAll("link");
                if(links.length > 0){
                    links.forEach(link =>{
                        const currentHref = link.getAttribute('href');
                        const newHref = currentHref + '?timestamp=' + Date.now();
                        link.setAttribute('href', newHref);
                    });
                }  
                
                let scripts = document.querySelectorAll("script");
                if(scripts.length > 0){
                    scripts.forEach(script =>{
                        const currentHref = script.getAttribute('src');
                        const newHref = currentHref + '?timestamp=' + Date.now();
                        script.setAttribute('src', newHref);
                    });
                }
            }
            
        </script>
    {{-- Agregar paramétro para siempre cargar los script y link --}}
    <head>
        
    
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta http-equiv="Expires" content="0">
        <meta http-equiv="Last-Modified" content="0">
        <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
        <meta http-equiv="Pragma" content="no-cache">

        @if(auth()->user() != null)
            {{-- <meta http-equiv="refresh" content="10800; url='{{url('/expire-session')}}'"> --}}
            <meta id="auth_user" content="{{auth()->user()->usuario}}">
            <meta id="user_email" content="{{auth()->user()->email}}">
        @endif
        <meta id="url_base" content="{{asset(url(''))}}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta id="current_date" content="{{\Carbon\Carbon::now()->format('Y-m-d')}}">
        @yield('title')

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
        <script src="{{asset('js/dmi/moment.js')}}"></script>
        <script src="{{asset('js/dmi/function_repository.js')}}"></script>

        <link rel="stylesheet" type="text/css" href="{{asset('css/fonts.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/footer.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/menu.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/estilos_globales.css')}}">
        <link href="{{ asset('css/dark-footer.css') }}" rel="stylesheet">
        <link href="{{ asset('css/general.css') }}" rel="stylesheet">


        @yield('script')

        
    </head>
    <body>

        @if(auth()->user())
            {{-- Navbar --}}
            <header id="intranet-menu" class="header-app" @if (env('APP_ENV_SEND_EMAIL') == 0) style="background:#ff0000!important;" @else @endif>
                <div class="logo">
                    <a href="{{ route('home') }}">

                        @if(env('APP_ENV_SEND_EMAIL') == 0)
                            <img src="{{asset('image/logo-grupo-dmi.png')}}" class="img-fluid">
                        @else
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
                        @endif
                    </a>
                </div>
                {{-- <div class="hamburguer" data-bs-toggle="offcanvas" data-bs-target="#offcanvasLeft-menu" aria-controls="offcanvasLeft-menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </div> --}}
                <i class="fal fa-bars icon-menu-hamburguer" style="font-size: 38px;" data-bs-toggle="offcanvas" data-bs-target="#offcanvasLeft-menu" aria-controls="offcanvasLeft-menu"></i>


                <div class="information">
                    {{-- *************** --}}
                        <div class="dropdown last-check me-3 p-1" id="dropdown_menu"  data-bs-toggle="tooltip" data-bs-placement="top" title="Última checada">
                            <div class="user dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-user-clock text-white" style="font-size:large;"></i>
                                <div class="name-user text-uppercase" >
                                    <div class="last-check-time" id="c_date1"></div>
                                    <div class="last-check-time"><strong id="c_time1"></strong></div>
                                </div>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton2">
                                <li><a class="dropdown-item">Última checada</a></li>
                                <li class="li_label_last_check"><a class="dropdown-item" id="li_label_last_check"></a></li>
                            </ul>
                        </div>

                    {{-- *************** --}}
                    <div class="notifications">
                        <i class="fas fa-bell icon-bell" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight-notification" aria-controls="offcanvasRight-notification" onclick="viewNotifications()"></i>
                        <span id="notification-count-intranet" class="number d-none" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight-notification" aria-controls="offcanvasRight-notification"></span>
                    </div>
                    <div class="dropdown" id="dropdown_menu">
                        <div class="user dropdown-toggle" data-bs-toggle="dropdown">
                            @if(auth()->user()->photo)
                                <img src="{{auth()->user()->photo_src}}" class="img-fluid user-icon" style="border-radius: 30px;width: 100%;">
                            @else
                                @if(auth()->user()->sex == 'Masculino')
                                    <img src="{{ asset('image/icons/masculino.svg')}}" class="img-fluid  user-icon" style="border-radius: 30px;width: 100%;">
                                @elseif(auth()->user()->sex=='Femenino')
                                    <img src="{{ asset('image/icons/femenino.svg')}}" class="img-fluid  user-icon" style="border-radius: 30px;width: 100%;">
                                @else
                                    <img src="{{ asset('image/icons/general.svg')}}" class="img-fluid  user-icon" style="border-radius: 30px;width: 100%;">
                                @endif
                            @endif
                            <div class="name-user text-uppercase">
                                <div class="first-name">{{auth()->user()->name}}</div>
                                <div class="last-name">{{auth()->user()->last_name}}</div>
                            </div>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton2">
                            <li><a class="dropdown-item" href="{{ route('perfil') }}">Mi perfil</a></li>
                            <li><a class="dropdown-item" href="{{ route('show_board') }}">Project board</a></li>
                            {{-- @if(Session::has('permission') == true && sizeof(Session::get('permission.permissions')) > 0)
                                <li><a class="dropdown-item" href="{{ route('admin') }}">Administrador</a></li>
                            @endif --}}
                            <li><a  class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">Administrador</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                    <input type="hidden" name="login_admin" value="true">
                                </form>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a  class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form-cs').submit();">Cerrar sesión</a>
                                <form id="logout-form-cs" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- <div class="menu">
                    <div class="container-menu">
                        <div class="content-menu">
                            <ul>
                                <li>
                                    <a href="{{ route('home') }}" class="{{ (\Request::route()->getName() == 'home') ? 'active' : '' }}">Inicio</a>
                                </li>
                                <li>
                                    <a href="{{env('APP_ENV_SEND_EMAIL') == 0 ? 'http://192.168.3.160:8080' : 'http://192.168.3.170:8080'}}" class=""><strong>Sistemas</strong> Administrativos
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás los sistemas administrativos, tales como, permisos, vacaciones, comedor, etc.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('quienesSomos') }}" class="{{ (\Request::route()->getName() == 'quienesSomos') ? 'active' : '' }}">Quienes somos
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás el Manifiesto, la Historia de Grupo DMI, los Valores de la Empresa y el Código de Ética.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a  href="{{ route('organigrama') }}" class="{{ (\Request::route()->getName() == 'organigrama') ? 'active' : '' }}"> Organigrama
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás el organigrama de Grupo DMI por División de Negocio: DMI Bienes Raíces, DMI Desarrollo de Negocios y DMI Responsabilidad Social.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a  href="{{ route('communication') }}" class="{{ (\Request::route()->getName() == 'communication') ? 'active' : '' }}"> Comunicados
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás información importante como Comunicados Institucionales, Movimientos Organizaciones y Campañas Institucionales.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a  href="{{ route('collaborators') }}" class="{{ (\Request::route()->getName() == 'collaborators') ? 'active' : '' }}"> Colaboradores
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás información relacionada a tus compañeros: Cumpleaños, Ascensos, Nuevos Ingresos, Aniversarios, Nacimientos y Condolencias. ">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('news') }}" class="{{ (\Request::route()->getName() == 'news') ? 'active' : '' }}" href="#"> Noticias
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás información del día a día como: Fechas Conmemorativas, Vacantes en Posteo Interno, Encuestas, Avisos de Área y de Políticas.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('blog')}}" class="{{ (\Request::route()->getName() == 'blog' ? 'active' : '') }}"> Revista Digital
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás la Revista Digital de Grupo DMI y Notas de Blog de interés y cultura general.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('events')}}" class="{{ (\Request::route()->getName() == 'events' ? 'active' : '') }}"> Eventos
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás el calendario mensual de Días Festivos y Eventos de tu Unidad de Negocio.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('fundacion') }}" class="{{ (\Request::route()->getName() == 'fundacion') ? 'active' : '' }}">Fundación
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
                                    <a href="{{route('benefit')}}" class="{{ (\Request::route()->getName() == 'benefit') ? 'active' : '' }}">Beneficios y Prestaciones
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás la información de los Beneficios (Convenios con externos y exclusivos de Grupo DMI) y Prestaciones (prestaciones con las que cuentas superiores a las de Ley).">
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
                                    <a href="{{ route('sitemaps') }}" class="{{ (\Request::route()->getName() == 'sitemaps') ? 'active' : '' }}">Mapa del Sitio
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás un mapa de sitio web de las páginas accesibles de la Intranet DMI.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> --}}

                {{-- Menú --}}
                <div class="offcanvas offcanvas-start offcanvasLeft-menu" tabindex="-1" id="offcanvasLeft-menu" aria-labelledby="offcanvasRightLabel" style="background:url(/image/background/back_pattern-01.jpg);">
                    <div class="offcanvas-header shadow">
                            <div class="col-11 text-center text-dmi text-white">
                                <h5 id="offcanvasRightLabel text-dmi"><i class="fal fa-bars me-2"></i>Menú</h5>
                            </div>
                            <div class="col-1">
                                {{-- <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button> --}}
                                <i class="fal fa-times text-white" data-bs-dismiss="offcanvas" aria-label="Close" style="font-size: 20px;"></i>
                            </div>
                    </div>
                    <div id="offcanvas-body-menu" class="offcanvas-body mb-5" style="padding:unset!important;">
                        <div class="content-offcanvas-menu">
                            <ul class="menu-offcanvas m-4">
                                <li>
                                    <a href="{{ route('home') }}" class="{{ (\Request::route()->getName() == 'home') ? 'active' : '' }}">Inicio</a>
                                </li>
                                <li>
                                    <a href="{{env('APP_ENV_SEND_EMAIL') == 0 ? 'http://192.168.3.160:8080' : 'http://192.168.3.170:8080'}}" class=""><strong>Sistemas</strong> Administrativos
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás los sistemas administrativos, tales como, permisos, vacaciones, comedor, etc.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('quienesSomos') }}" class="{{ (\Request::route()->getName() == 'quienesSomos') ? 'active' : '' }}">Quienes somos
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás el Manifiesto, la Historia de Grupo DMI, los Valores de la Empresa y el Código de Ética.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a  href="{{ route('organigrama') }}" class="{{ (\Request::route()->getName() == 'organigrama') ? 'active' : '' }}"> Organigrama
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás el organigrama de Grupo DMI por División de Negocio: DMI Bienes Raíces, DMI Desarrollo de Negocios y DMI Responsabilidad Social.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a  href="{{ route('communication') }}" class="{{ (\Request::route()->getName() == 'communication') ? 'active' : '' }}"> Comunicados
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás información importante como Comunicados Institucionales, Movimientos Organizaciones y Campañas Institucionales.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a  href="{{ route('collaborators') }}" class="{{ (\Request::route()->getName() == 'collaborators') ? 'active' : '' }}"> Colaboradores
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás información relacionada a tus compañeros: Cumpleaños, Ascensos, Nuevos Ingresos, Aniversarios, Nacimientos y Condolencias. ">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('news') }}" class="{{ (\Request::route()->getName() == 'news') ? 'active' : '' }}" href="#"> Noticias
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás información del día a día como: Fechas Conmemorativas, Vacantes en Posteo Interno, Encuestas, Avisos de Área y de Políticas.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('blog')}}" class="{{ (\Request::route()->getName() == 'blog' ? 'active' : '') }}"> Revista Digital
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás la Revista Digital de Grupo DMI.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('events')}}" class="{{ (\Request::route()->getName() == 'events' ? 'active' : '') }}"> Eventos
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás el calendario mensual de Días Festivos y Eventos de tu Unidad de Negocio.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('fundacion') }}" class="{{ (\Request::route()->getName() == 'fundacion') ? 'active' : '' }}">Fundación
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
                                    <a href="{{route('benefit')}}" class="{{ (\Request::route()->getName() == 'benefit') ? 'active' : '' }}">Beneficios y Prestaciones
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás la información de los Beneficios (Convenios con externos y exclusivos de Grupo DMI) y Prestaciones (prestaciones con las que cuentas superiores a las de Ley).">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                {{-- <li>
                                    <a href="{{ route('perfil') }}" class="{{ (\Request::route()->getName() == 'perfil') ? 'active' : '' }}">Mi Perfil
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás tus datos de colaborador como: número de empleado, horario laboral, correo, extensión.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('sitemaps') }}" class="{{ (\Request::route()->getName() == 'sitemaps') ? 'active' : '' }}">Mapa del Sitio
                                        <button type="button" class="btn btn-secondary tooltip-menu d-none" data-toggle="tooltip" title="En esta sección encontrarás un mapa de sitio web de las páginas accesibles de la Intranet DMI.">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </a>
                                </li> --}}
                            </ul>
                        </div>
                        
                    </div>
                </div>
                {{-- Menú --}}

                {{-- Notificaciones --}}
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight-notification" aria-labelledby="offcanvasRightLabel" style="background:#f2f4f4">
                    <div class="offcanvas-header shadow">
                            <div class="col-11 text-center text-dmi">
                                <h5 id="offcanvasRightLabel text-dmi"><i class="fas fa-bell me-2"></i>Notificaciones</h5>
                            </div>
                            <div class="col-1">
                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                    </div>
                    <div id="offcanvas-body-notification" class="offcanvas-body mb-5">
                        <div id="notifications-list">
                        </div>
                        <div id="notifications-list-no-data" class="d-none">
                            @include("componentes_generales.noDataFound",["size"=>"30","message"=>"Sin notificaciones."])
                        </div>
                    </div>
                </div>
                {{-- Notificaciones --}}
            </header>
            {{-- Navbar --}}
        @endif
        <div>
            @yield('content')
            @include('layouts.footer')
            @include("modales.modalAvisoPrivacidad")
        </div>



        {{-- Script-footer --}}


        <script type="text/javascript" src="{{asset('js/menu.js')}}"></script>
        {{-- Script-footer --}}

        <link rel="stylesheet" href="{{asset('OwlCarousel/dist/assets/owl.carousel.min.css')}}"/>
        <script src="{{asset('OwlCarousel/dist/owl.carousel.min.js')}}"></script>
        <script src="{{asset('js/dashboard.js')}}"></script>

        {{-- Script Snipper PageSense --}}
        <script src="https://cdn.pagesense.io/js/grupodmi/b3e6293c06054446a4d2a7977ca82e75.js"></script>
        @yield('script_footer')
        <script>
            this.getNotifications(); 
            this.getLastCheck();
            
        </script>

    </body>
</html>
