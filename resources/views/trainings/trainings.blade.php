@extends('layouts.app')
@section('title')
    <title>Capacitación | Intranet DMI</title>
@endsection
@section('script')
    {{-- Start- Estilos genéricos necesarios --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/training.css')}}">
    <link href="{{ asset('css/light-menu.css') }}" rel="stylesheet">
    <link href="{{ asset('css/collaborators.css') }}" rel="stylesheet">


    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('Calendar-09/css/style.css')}}"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('Calendar-09/css/bootstrap-datetimepicker.min.css')}}">

    <script src="{{ asset('Calendar-09/js/popper.js') }}" ></script>
    <script src="{{ asset('Calendar-09/js/jquery.min.js') }}" ></script>
    {{-- <script src="{{ asset('Calendar-09/js/bootstrap.min.js') }}" ></script> --}}
    <script src="{{ asset('Calendar-09/js/moment-with-locales.min.js') }}" ></script>
    <script src="{{ asset('Calendar-09/js/bootstrap-datetimepicker.min.js') }}" ></script>
    <script src="{{ asset('Calendar-09/js/main.js') }}" ></script>


    {{-- End - Estilos genéricos necesarios --}}

    <script>
        /* $(document).ready(function(){
            $('[data-toggle="popover"]').popover();
        }); */
    </script>
@endsection
@section('content')
    <div class="container-fluid ptop-150 content-section">
        <div class="pattern"></div>
        <div class="container">
            <div class="content-blog">
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="title-page"><a class="text-dmi btn-back" href="{{ route('home') }}"><i class="fas fa-arrow-alt-circle-left"></i></a>Capacitación</h1>
                    </div>
                    <div class="col-lg-12">
                        <div class="container responsive-tabs">

                            <ul class="nav nav-tabs justify-content-center" role="tablist">
                                <li class="nav-item">
                                    <a id="tab-calendar" href="#pane-calendar" class="nav-link active" data-bs-toggle="tab" role="tab">CALENDARIO</a>
                                </li>
                                @if(sizeof($video_tips) > 0)
                                    <li class="nav-item">
                                        <a id="tab-video_tips" href="#pane-video_tips" class="nav-link" data-bs-toggle="tab" role="tab">VIDEO TIPS</a>
                                    </li>
                                @endif


                                @if(sizeof($induccion_intelisis) > 0)
                                    <li class="nav-item">
                                        <a id="tab-induccion_intelisis" href="#pane-induccion_intelisis" class="nav-link" data-bs-toggle="tab" role="tab">INDUCCIÓN INTELISIS</a>
                                    </li>
                                @endif

                                @if(sizeof($induccion_basica) > 0)
                                    <li class="nav-item">
                                        <a id="tab-induccion_basica" href="#pane-induccion_basica" class="nav-link" data-bs-toggle="tab" role="tab">INTRODUCCIÓN BÁSICA</a>
                                    </li>
                                @endif
                            </ul>


                            <div id="content-tabs-us" class="tab-content" role="tablist">
                                {{-- CONÓCENOS --}}

                                @include('trainings.calendar')
                                @include('trainings.video_tips')
                                @include('trainings.induccion_intelisis')
                                @include('trainings.induccion_basica')

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('modales.modalTraining')
    @include("modales.learning_video")

@endsection

@section('script_footer')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css"/>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
    <script src="{{asset('/js/tabs.js')}}"></script>
    <link href="{{asset('/FullCalendar/main.css')}}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{asset('/css/calendar.css')}}">
    <script src="{{asset('/FullCalendar/main.js')}}"></script>
    <script src="{{asset('/FullCalendar/locales-all.js')}}"></script>
    <script src="{{asset('/js/dmi/training.js')}}" type="text/javascript"></script>

    <script>

        ths.general_data.trainings = @json($trainings);

        ths.addEventsToCalendar();
        ths.generateTrainingToCalendar();

    </script>

    <script src="{{asset('js/dmi/learning_videos.js')}}"></script>

    <script>
        //var ths = this;
        var general_data_learning = {
            data:@json($data_learning),

        };
    </script>
@endsection
