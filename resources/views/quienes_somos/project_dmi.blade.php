@extends('layouts.app')
@section('title')
    <title>Quiénes somos | Intranet DMI</title>
@endsection
@section('script')
    <link href="{{ asset('css/light-menu.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dmi/us.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dmi/img_gallery.css') }}" rel="stylesheet">
    <link href="{{ asset('viewerjs/css/viewer.css') }}" rel="stylesheet">
    <link href="{{ asset('viewerjs/css/main.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container-fluid ptop-150" id="us">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="title-page"><a class="text-dmi btn-back" href="/quienes_somos#pane-history"><i class="fas fa-arrow-alt-circle-left"></i></a>Quienes somos</h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <img class="w-100" src="{{url($project->file)}}" alt="">
            </div>
        </div>
        <div class="row img-portada">
            <div class="container">
                <div class="content-us mheight-80 content-tabs">
                     {{-- <div class="row">
                                <h1 class="title-page">{{$project->name}}</h1>
                            </div> --}}

                    <div class="row">

                        <div class="col-sm-12 card-top">
                            <h1 class="title-project text-center">{{$project->name}}</h1>
                            <div class="row">
                                <div class="container responsive-tabs">

                                    <ul class="nav nav-tabs justify-content-center" role="tablist">
                                        <li class="nav-item {{!array_key_exists('CONÓCENOS',$items->toArray()) ? 'd-none' :''}}">
                                            <a id="tab-conocenos" href="#pane-conocenos" class="nav-link active" data-bs-toggle="tab" role="tab">CONÓCENOS</a>
                                        </li>
                                        <li class="nav-item {{!array_key_exists('CARACTERISTICAS',$items->toArray()) ? 'd-none' :''}}">
                                            <a id="tab-history" href="#pane-caracteristicas" class="nav-link" data-bs-toggle="tab" role="tab">CARACTERÍSTICAS</a>
                                        </li>
                                        <li class="nav-item {{!array_key_exists('GALERIA',$items->toArray()) ? 'd-none' :''}}">
                                            <a id="tab-values" href="#pane-galeria" class="nav-link" data-bs-toggle="tab" role="tab">GALERÍA</a>
                                        </li>
                                        <li class="nav-item {{!array_key_exists('VIDEO',$items->toArray()) ? 'd-none' :''}}">
                                            <a id="tab-video" href="#pane-video" class="nav-link" data-bs-toggle="tab" role="tab">VIDEO</a>
                                        </li>
                                        <li class="nav-item {{!array_key_exists('MASTER PLAN',$items->toArray()) ? 'd-none' :''}}">
                                            <a id="tab-ethic" href="#pane-master" class="nav-link" data-bs-toggle="tab" role="tab">MASTER PLAN</a>
                                        </li>
                                    </ul>


                                    <div id="content-tabs-us" class="tab-content" role="tablist">
                                        {{-- CONÓCENOS --}}

                                        @include('quienes_somos.tabs_project.conocenos')
                                        @include('quienes_somos.tabs_project.caracteristicas')
                                        @include('quienes_somos.tabs_project.galeria')
                                        @include('quienes_somos.tabs_project.plan_maestro')
                                        @include('quienes_somos.tabs_project.video')

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script src="{{asset('js/tabs.js')}}"></script>
@endsection

@section('script_footer')

    <script src="{{asset('viewerjs/js/viewer.js')}}"></script>
    <script src="{{asset('viewerjs/js/main.js')}}"></script>

<script>

    //Se pausan los videos al entrar a la pagina
    if(document.querySelector("#project_video") != null){
        document.querySelector("#project_video").pause()
    }


    // Create a lightbox
(function() {
  var $lightbox = $("<div class='lightbox'></div>");
  var $img = $("<img>");
  var $caption = $("<p class='caption'></p>");

  // Add image and caption to lightbox

  $lightbox
    .append($img)
    .append($caption);

  // Add lighbox to document

  $('body').append($lightbox);

  $('.lightbox-gallery img').click(function(e) {
    e.preventDefault();

    // Get image link and description
    var src = $(this).attr("data-image-hd");
    var cap = $(this).attr("alt");

    // Add data to lighbox

    $img.attr('src', src);
    $img.attr('width', "800px");
    $caption.text(cap);

    // Show lightbox

    $lightbox.fadeIn('fast');

    $lightbox.click(function() {
      $lightbox.fadeOut('fast');
    });

    document.addEventListener("keydown", function (event) {
        if (event.keyCode === 27) {
            $lightbox.fadeOut('fast');
        }
    });
  });

}());

</script>
@endsection
