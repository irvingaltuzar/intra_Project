@extends('layouts.app')
@section('title')
    <title>Beneficios y Prestaciones | Intranet DMI</title>
@endsection
@section('script')

    <link href="{{ asset('css/light-menu.css') }}" rel="stylesheet">
    <link href="{{ asset('css/benefits.css') }}" rel="stylesheet">
    <link href="{{ asset('css/blog.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container-fluid ptop-150 content-section">
        <div class="pattern"></div>
        <div class="container">
            <div class="content-blog">
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="title-page">Beneficios y Prestaciones </h1>
                        <div class="container responsive-tabs mheight-80">
                            <ul class="nav nav-tabs justify-content-center" role="tablist">
                                <li class="nav-item">
                                    <a id="tab-benefits" href="#pane-benefits" class="nav-link active" data-bs-toggle="tab" role="tab">Beneficios</a>
                                </li>
                                <li class="nav-item">
                                    <a id="tab-benefits-2" href="#pane-benefits-2" class="nav-link" data-bs-toggle="tab" role="tab">Prestaciones</a>
                                </li>
                            </ul>

                            <div id="content-tabs-blog" class="tab-content" role="tablist">

                                @include('benefits.benefits')
                                @include('benefits.prestaciones')

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

        var _hash = window.location.hash;
        if(_hash == "#pane-benefits"){
            document.querySelector("#tab-benefits").click()
        }else if(_hash == "#pane-benefits-2"){
            document.querySelector("#tab-benefits-2").click()
        }

        var url = new URL(window.location.href);
        var params = new URLSearchParams(url.search);
        params.get("section");
        if(params.get("section") == 'pane-benefits'){
            document.querySelector("#tab-benefits").click()
        }else if(params.get("section") == 'pane-benefits-2'){
            document.querySelector("#tab-benefits-2").click()
        }

</script>
@endsection
