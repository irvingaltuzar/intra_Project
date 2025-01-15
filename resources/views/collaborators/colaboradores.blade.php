@extends('layouts.app')
@section('title')
    <title>Colaboradores | Intranet DMI</title>
@endsection
@section('script')
    {{-- <script src="{{ asset('js/jquery-3.6.0.min.js') }}" ></script> --}}

    <link href="{{ asset('css/light-menu.css') }}" rel="stylesheet">
    <link href="{{ asset('css/collaborators.css') }}" rel="stylesheet">
    <link href="{{ asset('css/estilos_globales.css') }}" rel="stylesheet">
    <link href="{{ asset('css/publications.css') }}" rel="stylesheet">


@endsection
@section('content')
    <div class="container-fluid no-padding ptop-150" id="collaborators">
        <div class="pattern"></div>
        <div class="container">
            <div class="content-tabs">
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="title-page"><a class="text-dmi btn-back" href="{{ route('home') }}"><i class="fas fa-arrow-alt-circle-left"></i></a>Colaboradores</h1>
                    </div>
                </div>
                <div class="row">
	                <div class="col-sm-12">
                        <div class="container responsive-tabs">
                            <ul class="nav nav-tabs justify-content-center" role="tablist">
                                <li class="nav-item">
                                    <a id="tab-birthday" href="#pane-birthday" class="nav-link active" data-bs-toggle="tab" role="tab">Cumpleaños</a>
                                </li>
                                <li class="nav-item">
                                    <a id="tab-promotions" href="#pane-promotions" class="nav-link" data-bs-toggle="tab" role="tab">Ascensos</a>
                                </li>
                                <li class="nav-item">
                                    <a id="tab-new-staff" href="#pane-new-staff" class="nav-link" data-bs-toggle="tab" role="tab">Nuevos ingresos</a>
                                </li>
                                <li class="nav-item">
                                    <a id="tab-anniversaries" href="#pane-anniversaries" class="nav-link" data-bs-toggle="tab" role="tab">Aniversarios</a>
                                </li>
                                <li class="nav-item">
                                    <a id="tab-births" href="#pane-births" class="nav-link" data-bs-toggle="tab" role="tab">Nacimientos</a>
                                </li>
                                <li class="nav-item">
                                    <a id="tab-condolences" href="#pane-condolences" class="nav-link" data-bs-toggle="tab" role="tab">Condolencias</a>
                                </li>
                            </ul>
                            <div id="content-tabs-collaborators" class="tab-content" role="tablist">

                                {{-- Cumpleaños --}}
                                @include('collaborators.birthday')

                                {{-- Ascensos --}}
                                @include('collaborators.promotions')

                                {{-- Nuevos ingresos --}}
                                @include('collaborators.newStaff')

                                {{-- Aniversarios --}}
                                @include('collaborators.anniversaries')

                                {{-- Nacimientos --}}
                                @include('collaborators.births')

                                {{-- Condolencias --}}
                                @include('collaborators.condolences')

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modales.modalComments')
    @include('modales.modalReactions')
@endsection

@section('script_footer')
    <script src="{{asset('js/dmi/collaborators.js')}}"></script>
    <script src="{{asset('js/dmi/reactions.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js"></script>
    <script>
        /* var _hash = window.location.hash;
        if(_hash == "#pane-condolences"){
            document.querySelector("#tab-condolences").click()
        } */

        var url = new URL(window.location.href);
        var params = new URLSearchParams(url.search);
        params.get("section");
        if(params.get("section") == 'pane-condolences'){
            document.querySelector("#tab-condolences").click()
            
        }else if(params.get("section") == 'pane-birthday'){
            document.querySelector("#tab-birthday").click()

        }else if(params.get("section") == 'pane-promotions'){
            document.querySelector("#tab-promotions").click()

        }else if(params.get("section") == 'pane-new-staff'){
            document.querySelector("#tab-new-staff").click()

        }else if(params.get("section") == 'pane-anniversaries'){
            document.querySelector("#tab-anniversaries").click()

        }else if(params.get("section") == 'pane-births'){
            document.querySelector("#tab-births").click()

        }
    </script>


@endsection
