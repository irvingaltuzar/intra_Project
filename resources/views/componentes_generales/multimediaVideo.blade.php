@extends('layouts.app')
@section('title')
    <title>Multimedia | Intranet DMI</title>
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
            <div class="content-foundation mheight-10 content-tabs">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="container responsive-tabs">
                            <div class="component-article">
                                <div class="header-article mb-3">
                                    <h3>{{$record->title}}</h3>
                                    <span class="date">{{ucwords(\Carbon\Carbon::parse($record->created_at)->translatedFormat('j F')) }}</span>
                                    <br>
                                </div>
                                <article class="news">
                                    <div class="row justify-content-center">
                                        <video class="w-100 border public-video"  src="{{url($record->video)}}" id="modal_video_full" alt="" controls="controls" autoplay="autoplay">
                                          Vídeo no es soportado...
                                        </video>

                                      </div>
                                </article>
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


