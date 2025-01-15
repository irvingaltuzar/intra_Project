@extends('layouts.app')
@section('title')
    <title>{{ config('app.name', 'Intranet DMI') }}</title>
@endsection
@section('script')
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container-fluid" id="login">
        <div class="login-form">

            @if(env('APP_ENV_SEND_EMAIL') == 0)
                <div class="logo" style="background:#ff0000!important;">
                    <img src="{{asset('image/logo-grupo-dmi.png')}}" class="img-fluid">
                </div>
            @else
                <div class="logo" >
                    <img src="{{asset('image/logo-grupo-dmi.svg')}}" class="img-fluid">
                </div>
            @endif
            <div class="content-form">
                <div class="row">
                    <div class="col-sm-12">
                        <p class="title">Inicia Sesión</p>
                        <form method="POST" action="{{ route('login') }}">
                        @csrf
                            <input id="usuario" type="text"
                            class="form-control @error('usuario') is-invalid @enderror"
                            name="usuario" value="{{ old('usuario') }}"
                            autocomplete="usuario" placeholder="Nombre"  autofocus>

                            @error('usuario')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password" autocomplete="current-password"
                            placeholder="Contraseña" >

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <input type="submit" value="Entrar">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footer')
    </div>
@endsection
