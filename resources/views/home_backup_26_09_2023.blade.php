@extends('layouts.app')
@section('title')
    <title>{{ config('app.name', 'Intranet DMI') }}</title>
@endsection
@section('script')
    <link rel="stylesheet" type="text/css" href="{{asset('css/dashboard.css')}}">
    <link href="{{ asset('css/communication.css') }}" rel="stylesheet">

@endsection

@section('content')

<div class="container-fluid" id="dashboard">
    <div class="container" >
        <div class="content-dashboard">
            <div class="row">
                <div class="col-sm-12">
                    <h1>Bienvenido a <span>Intranet</span></h1>

                    <div class="carousel-publications">
                        <p class="title-section text-uppercase">Últimas publicaciones</p>
                        <div class="owl-carousel owl-theme">
                            @foreach($communiques as $communique)
                                <a class="text-decoration-none text-dark images-link" data-id_communique="{{$communique->id}}">
                                    <div class="item">
                                        <div class="row publication">
                                            @if($communique->photo)
                                                <div class="col-md-6 justify-content-center align-self-center photo-column" >
                                                    <img src="{{asset($communique->photo)}}" class="principal-image img-fluid h-100">
                                                </div>
                                            @endif
                                            <div class="{{($communique->photo)? 'col-md-6':'col-12'}} justify-content-center align-self-center">
                                                <p class="title"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="{{$communique->title}}"
                                                >{{ substr($communique->title,0,25) }}{{strlen($communique->title) > 25 ? '...' : ''}}

                                                </p>
                                                <p class="description">{{strftime("%d/%m/%Y", strtotime($communique->created_at))}}</p>
                                                <button class="btn-blue-dmi" onclick="showCommunique(this)" data-id_communique="{{$communique->id}}">Ver</button>
                                            </div>

                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('modales.modalImages');
    </div>
</div>

@endsection

@section('script_footer')

<script src="{{asset('js/dmi/modal-images_home.js')}}"></script>
<script>
    ths.communiques = @json($communiques);
</script>



@endsection
