@extends('layouts.app')
@section('title')
    <title>Revista Digital | Intranet DMI</title>
@endsection
@section('script')
    {{-- Start- Estilos genéricos necesarios --}}
    <link rel="stylesheet" type="text/css" href="{{asset('css/blog.css')}}">
    <link href="{{ asset('css/light-menu.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/news.css') }}">

    {{-- End - Estilos genéricos necesarios --}}

    <link rel="stylesheet" type="text/css" href="{{ asset('/dflip/css/dflip.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/dflip/css/themify-icons.min.css') }}">


    <script>
        $(document).ready(function(){
            $('[data-toggle="popover"]').popover();
        });
    </script>

<script  src="{{asset('dflip/js/dflip.js')}}"></script>
<script src="{{asset('js/conf.js')}}"></script>
@endsection
@section('content')
<div class="container-fluid ptop-150 content-section">
    <div class="pattern"></div>
    <div class="container">
        <div class="content-blog">
            <div class="row">
                <div class="col-sm-12">
                    <h1 class="title-page"><a class="text-dmi btn-back" href="{{ route('home') }}"><i class="fas fa-arrow-alt-circle-left"></i></a>Revista Digital </h1>
                    <div class="container responsive-tabs mheight-80">
                        <ul class="nav nav-tabs justify-content-center" role="tablist">
                            {{-- <li class="nav-item">
                                <a id="tab-digital-magazine" href="#pane-digital-magazine" class="nav-link active" data-bs-toggle="tab" role="tab">Revista Digital</a>
                            </li>
                            <li class="nav-item">
                                <a id="tab-blog-notes" href="#pane-blog-notes" class="nav-link" data-bs-toggle="tab" role="tab">Notas de Blog</a>
                            </li> --}}
                        </ul>

                        <div id="content-tabs-blog" class="tab-content" role="tablist">
                                <div id="pane-digital-magazine" class="card tab-pane fade show active" role="tabpanel" aria-labelledby="tab-digital-magazine">
                                    @include('blog.journal')
                                </div>

                                {{-- <div id="pane-blog-notes" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-blog-notes">
                                    @include('blog.notes')
                                </div> --}}
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('script_footer')
    {{-- <script  src="{{asset('dflip/js/dflip.js')}}"></script>
    <script src="{{asset('js/conf.js')}}"></script>
    <script> console.log("script_footer");</script> --}}

@endsection

@section('script')

    <script src="{{asset('js/tabs.js')}}"></script>



    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

    </script>
@endsection



