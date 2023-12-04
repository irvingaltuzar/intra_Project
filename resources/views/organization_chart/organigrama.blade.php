@extends('layouts.app')
@section('title')
    <title>Organigrama | Intranet DMI</title>
@endsection
@section('script')

    <link href="{{ asset('css/light-menu.css') }}" rel="stylesheet">
    <link href="{{ asset('css/general.css') }}" rel="stylesheet">
    <link href="{{ asset('css/organigrama.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container-fluid no-padding ptop-150" id="organigrama">
        <div class="container mheight-80">
            <div class="row">
                <div class="col-sm-12">
                    <h1 class="title-page"><a class="text-dmi btn-back" href="{{ route('home') }}"><i class="fas fa-arrow-alt-circle-left"></i></a>Organigrama</h1>
                </div>

                <div class="col-sm-12">
                    <div class="row divisions">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <a href="organigrama/bienes_raices" style="text-decoration: unset;">
                                <div class="container-division">
                                    <img src="{{ asset('image/organigrama/division_bienes_raices.jpg')}}" class="img-fluid">
                                    <p class="title">
                                            DMI <br>Bienes Raíces
                                    </p>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <a href="organigrama/negocios" style="text-decoration: unset;">
                                <div class="container-division">
                                    <img src="{{ asset('image/organigrama/division_desarrollo_negocios.jpg')}}" class="img-fluid">
                                    <p class="title">
                                            DMI Desarrollo <br>de negocios
                                    </p>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <a href="organigrama/resp_social" style="text-decoration: unset;">
                                <div class="container-division">
                                    <img src="{{ asset('image/organigrama/division_responsabilidad_social.jpg')}}" class="img-fluid">
                                    <p class="title">
                                            DMI <br>Responsabilidad Social
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
