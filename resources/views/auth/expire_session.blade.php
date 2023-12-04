<html lang="es">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta id="url_base" content="{{asset(url(''))}}">
        <title>{{ config('app.name', 'Intranet DMI') }}</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
        <script src="{{asset('js/dmi/moment.js')}}"></script>
        <script src="{{asset('js/dmi/function_repository.js')}}"></script>

        <link rel="stylesheet" type="text/css" href="{{asset('css/fonts.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/footer.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/menu.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/estilos_globales.css')}}">
        <link href="{{ asset('css/dark-footer.css') }}" rel="stylesheet">
        <link href="{{ asset('css/general.css') }}" rel="stylesheet">

        <script src="{{ asset('js/app.js') }}" defer></script>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/login.css') }}" rel="stylesheet">
        <link href="{{ asset('css/footer.css') }}" rel="stylesheet">


    </head>
    <body>
        <div>
            <div class="container-fluid" id="login">
                <div class="login-form">
                    <div class="logo">
                        <img src="{{asset('image/logo-grupo-dmi.svg')}}" class="img-fluid">
                    </div>
                    <div class="content-form">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <form action="{{url("")}}">
                                    <br>
                                    <br>
                                    <img class="img-svg-70" src="{{asset('/image/icons/expire_session.svg')}}">
                                    <br>
                                    <br>
                                    <span class="title" style="font-weight:600;font-size: 21px!important;">La sesión ha caducado</span><br>
                                    <span class="title">Vuelva a iniciar sesión</span>
                                    <input type="submit" value="Continuar">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="container-fluid" style="bottom: 0px;
                    position: fixed;
                    z-index: 1000;">
                        <div class="row">
                            <div class="col-sm-12">
                                <ul>
                                    <li>©Copyright Grupo DMI 2022 <span class="ti"><span>-</span> Departamento de desarrollo TI</li>
                                    <li><a href="{{ route('noticePrivacy') }}">Aviso de privacidad</a></li>
                                    <li>Soporte y sugerencias <a href="mailto:soporte@grupodmi.com.mx" class="email-support">soporte@grupodmi.com.mx</a></li>
                                    <li></li>
                                </ul>
                            </div>
                        </div>
                    </footer>
            </div>
        </div>



        {{-- Script-footer --}}


        <script type="text/javascript" src="{{asset('js/menu.js')}}"></script>
        {{-- Script-footer --}}

        <link rel="stylesheet" href="{{asset('OwlCarousel/dist/assets/owl.carousel.min.css')}}"/>
        <script src="{{asset('OwlCarousel/dist/owl.carousel.min.js')}}"></script>
        <script src="{{asset('js/dashboard.js')}}"></script>

        <script>


        </script>
        @yield('script_footer')

    </body>
</html>
