@extends('layouts.app')
@section('title')
    <title>Noticias | Intranet DMI</title>
@endsection
@section('script')
    {{-- Start- Estilos genéricos necesarios --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/news.css') }}">
    <link href="{{ asset('css/light-menu.css') }}" rel="stylesheet">
    {{-- End - Estilos genéricos necesarios --}}

@endsection
@section('content')
<div class="container-fluid ptop-150 content-section mheight-80 special-tab" id="news">
    <div class="pattern"></div>
    <div class="container">
        <div class="content-tabs">
            <div class="row">
                <div class="col-sm-12">
                    <h1 class="title-page"><a class="text-dmi btn-back" href="{{ route('home') }}"><i class="fas fa-arrow-alt-circle-left"></i></a>Noticias</h1>
                </div>
            </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="container responsive-tabs">
                            <ul class="nav nav-tabs justify-content-center" role="tablist">
                                <li class="nav-item">
                                    <a id="tab-commemorative-dates" href="#pane-commemorative-dates" class="nav-link active" data-bs-toggle="tab" role="tab">Fechas conmemorativas</a>
                                </li>
                                <li class="nav-item">
                                    <a id="tab-internal-posting" href="#pane-internal-posting" class="nav-link" data-bs-toggle="tab" role="tab">Posteo interno</a>
                                </li>
                                <li class="nav-item">
                                    <a id="tab-surveys" href="#pane-surveys" class="nav-link" data-bs-toggle="tab" role="tab">Encuestas</a>
                                </li>
                                <li class="nav-item">
                                    <a id="tab-area-notices" href="#pane-area-notices" class="nav-link" data-bs-toggle="tab" role="tab">Avisos de áreas</a>
                                </li>
                                <li class="nav-item">
                                    <a id="tab-policies" href="#pane-policies" class="nav-link" data-bs-toggle="tab" role="tab">Políticas</a>
                                </li>
                            </ul>

                            <div id="content-tabs-news" class="tab-content" role="tablist">
                                @include('news.conmmemorative_date')

                                @include('news.internal_posting')

                                @include('news.polls')

                                @include('news.area_notices')
                                @include('modales.modalVideo')

                                @include('news.policies')

                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </div>
</div>

@endsection

@section('script_footer')

    <script src="{{asset('js/tabs.js')}}"></script>
    <script src="{{asset('js/dmi/news.js')}}"></script>

    <script>
        $(document).ready(function(){
            $('[data-toggle="popover"]').popover();
        });

        var _hash = window.location.hash;
        if(_hash == "#pane-internal-posting"){
            document.querySelector("#tab-internal-posting").click()
        }else if(_hash == "#pane-surveys"){
            document.querySelector("#tab-surveys").click()
        }if(_hash == "#pane-area-notices"){
            document.querySelector("#tab-area-notices").click()
        }if(_hash == "#pane-policies"){
            document.querySelector("#tab-policies").click()
        }


        var url = new URL(window.location.href);
        var params = new URLSearchParams(url.search);
        params.get("section");
        if(params.get("section") == 'pane-internal-posting'){
            document.querySelector("#tab-internal-posting").click()

        }else if(params.get("section") == 'pane-surveys'){
            document.querySelector("#tab-surveys").click()

        }else if(params.get("section") == 'pane-area-notices'){
            document.querySelector("#tab-area-notices").click()

        }else if(params.get("section") == 'pane-policies'){
            document.querySelector("#tab-policies").click()
        }

    </script>
@endsection
