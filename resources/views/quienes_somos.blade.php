@extends('layouts.app')
@section('title')
    <title>Quiénes somos | Intranet DMI</title>
@endsection
@section('script')
    <link href="{{ asset('css/dark-footer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/light-menu.css') }}" rel="stylesheet">
    <link href="{{ asset('css/general.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dmi/us.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container-fluid ptop-150" id="us">
        <div class="container">
            <div class="content-us mheight-80 content-tabs">
                <div class="row">
                    <div class="col-sm-12">

                        <h1 class="title-page"><a class="text-dmi btn-back" href="{{ route('home') }}"><i class="fas fa-arrow-alt-circle-left"></i></a> Quiénes somos </h1>
                        <div class="container responsive-tabs">
                            <ul class="nav nav-tabs justify-content-center" role="tablist">
                                <li class="nav-item">
                                    <a id="tab-manifest" href="#pane-manifest" class="nav-link active" data-bs-toggle="tab" role="tab">Nuestro Manifiesto</a>
                                </li>
                                <li class="nav-item">
                                    <a id="tab-history" href="#pane-history" class="nav-link" data-bs-toggle="tab" role="tab">CONOCE NUESTROS PROYECTOS</a>
                                </li>
                                <li class="nav-item">
                                    <a id="tab-values" href="#pane-values" class="nav-link" data-bs-toggle="tab" role="tab">VIRTUDES Y VALORES</a>
                                </li>
                                <li class="nav-item">
                                    <a id="tab-ethic" href="#pane-ethic" class="nav-link" data-bs-toggle="tab" role="tab">ÉTICA EN LA EMPRESA</a>
                                </li>
                            </ul>

                            <div id="content-tabs-us" class="tab-content" role="tablist">
                                @include('quienes_somos.pane_manifest')
                                @include('quienes_somos.pane_history')
                                @include('quienes_somos.pane_values')
                                @include('quienes_somos.pane_ethic')

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script_footer')
<script>

        document.querySelector("#video_manifiesto").pause()

        var _hash = window.location.hash;
        if(_hash == "#pane-history"){
            document.querySelector("#tab-history").click()
            document.querySelector("#video_manifiesto").pause()
        }

        var url = new URL(window.location.href);
        var params = new URLSearchParams(url.search);
        params.get("section");
        if(params.get("section") == 'pane-history'){
            document.querySelector("#tab-history").click()
            document.querySelector("#video_manifiesto").pause()
        }


</script>
@endsection
