@extends('layouts.app')
@section('title')
    <title>Fundación | Intranet DMI</title>
@endsection
@section('script')
    <link href="{{ asset('css/light-menu.css') }}" rel="stylesheet">
    <link href="{{ asset('css/foundation.css') }}" rel="stylesheet">
    <link href="{{ asset('css/news.css') }}" rel="stylesheet">
    <link href="{{ asset('css/slick.css') }}" rel="stylesheet">
    <script src="{{ asset('js/slick.min.js') }}" defer></script>
    <script src="{{ asset('js/tabs.js') }}" defer></script>
    <script src="{{ asset('js/foundation.js') }}" defer></script>


    <!-- Syntax Highlighter -->
  {{-- <link href="{{ asset('FlexSlider/css/shCore.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('FlexSlider/css/shThemeDefault.css') }}" rel="stylesheet" type="text/css"> --}}

  <!-- Demo CSS -->
  {{-- <link href="{{ asset('FlexSlider/css/demo.css') }}" rel="stylesheet" type="text/css" media="screen"> --}}
  <link href="{{ asset('FlexSlider/flexslider.css') }}" rel="stylesheet" type="text/css" media="screen">

	<!-- Modernizr -->
  <script src="{{ asset('FlexSlider/js/modernizr.js') }}"></script>



@endsection

@section('content')
    <div class="container-fluid ptop-150" id="foundation">
        <div class="container">
            <div class="content-foundation mheight-80 content-tabs">
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="title-page"><a class="text-dmi btn-back" href="{{ route('home') }}"><i class="fas fa-arrow-alt-circle-left"></i></a>Fundación</h1>
                        <div class="container responsive-tabs">
                            <ul class="nav nav-tabs justify-content-center" role="tablist">
                                <li class="nav-item">
                                    <a id="tab-general-information" href="#pane-general-information" class="nav-link active" data-bs-toggle="tab" role="tab">Información General</a>
                                </li>
                                <li class="nav-item">
                                    <a id="tab-information-capsules" href="#pane-information-capsules" class="nav-link" data-bs-toggle="tab" role="tab">Cápsulas de información</a>
                                </li>

                            </ul>

                            <div id="content-tabs-foundation" class="tab-content" role="tablist">
                                @include('foundation.capsules')
                                @include('foundation.generalInformation')
                                @include('modales.modalVideo')
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script_footer')
<!-- jQuery -->
<script src="{{asset('js/dmi/foundation.js')}}"></script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.min.js">\x3C/script>')</script>

    <script src="{{ asset('FlexSlider/jquery.flexslider.js') }}"></script>
    <script type="text/javascript">

        var _hash = window.location.hash;
        if(_hash == "#pane-information-capsules"){
            document.querySelector("#tab-information-capsules").click()
        }

        var url = new URL(window.location.href);
        var params = new URLSearchParams(url.search);
        params.get("section");
        if(params.get("section") == 'pane-information-capsules'){
            document.querySelector("#tab-information-capsules").click()
        }

        document.querySelector("#video_fundacion").pause()

        $(function(){
          SyntaxHighlighter.all();
        });


        $(window).load(function(){
          $('#carousel').flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            itemWidth: 210,
            itemMargin: 5,
            asNavFor: '#slider'
          });

          $('#slider').flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            sync: "#carousel",
            start: function(slider){
              $('body').removeClass('loading');
            }
          });
        });
      </script>

      <!-- Syntax Highlighter -->

    <script src="{{ asset('FlexSlider/js/shCore.js') }}"></script>
    <script src="{{ asset('FlexSlider/js/shBrushXml.js') }}"></script>
    <script src="{{ asset('FlexSlider/js/shBrushJScript.js') }}"></script>


    <!-- Optional FlexSlider Additions -->

    <script src="{{ asset('FlexSlider/js/jquery.easing.js') }}"></script>
    <script src="{{ asset('FlexSlider/js/jquery.mousewheel.js') }}"></script>
    <script src="{{ asset('FlexSlider/js/demo.js') }}"></script>





@endsection


