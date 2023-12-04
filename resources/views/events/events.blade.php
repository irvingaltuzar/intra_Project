@extends('layouts.app')
@section('title')
    <title>Eventos | Intranet DMI</title>
@endsection
@section('script')
    {{-- Start- Estilos genéricos necesarios --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/events.css') }}">
    <link href="{{asset('/FullCalendar/main.css')}}" rel="stylesheet" />
    <link href="{{ asset('css/light-menu.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('/css/calendar.css')}}">
    <script src="{{asset('/FullCalendar/main.js')}}"></script>
    <script src="{{asset('/FullCalendar/locales-all.js')}}"></script>

    {{-- End - Estilos genéricos necesarios --}}

    <script>

    </script>
@endsection
@section('content')
    <div class="container-fluid ptop-150 content-section">
        <div class="pattern"></div>
        <div class="container">
            <div class="content-blog">
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="title-page"><a class="text-dmi btn-back" href="{{ route('home') }}"><i class="fas fa-arrow-alt-circle-left"></i></a>Eventos </h1>
                    </div>
                    <div class="col-lg-9">
                        <div class="container responsive-tabs mheight-80">
                            <ul class="nav nav-tabs justify-content-center" role="tablist">
                                <li class="nav-item">
                                    <a id="tab-holidays" href="#pane-holidays" class="nav-link active" data-bs-toggle="tab" role="tab">Calendario {{date('Y')}}</a>
                                </li>
                            </ul>

                            <div id="content-tabs-dmi-calendar" class="tab-content" role="tablist">
                                <div id="pane-holidays" class="card tab-pane fade show active" role="tabpanel" aria-labelledby="tab-holidays">
                                    <div class="card-header" role="tab" id="heading-holidays">
                                        <a data-bs-toggle="collapse" href="#collapse-holidays" aria-expanded="false" aria-controls="collapse-holidays">
                                            <h5 class="mb-0">
                                                Calendario
                                            </h5>
                                        </a>
                                    </div>
                                    <div id="collapse-holidays" class="collapse show" data-bs-parent="#content-tabs-dmi-calendar" role="tabpanel"
                                        aria-labelledby="heading-holidays">
                                        <div class="card-body">
                                            <div class="content content-holidays">
                                                <div class="row">
                                                    <div class="col-lg-2" id="list_type_events">
                                                    </div>
                                                    <div class="col-lg-10">
                                                        <div id="calendar" class="calendar-events"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 sidebar">
                        <div class="gallery sidebar-section">
                            <p class="title">Galería</p>
                            <div id="carouselGallery" class="carousel slide" data-bs-ride="carousel" data-bs-touch="true">
                                <div class="carousel-inner">
                                    @php
                                        $cont=0;
                                    @endphp
                                    @foreach ($events as $key => $item)
                                        @if($item->photo != null)
                                            @if ($cont == 0)
                                                <div class="carousel-item active">
                                                    <img src="{{url($item->photo ?? '')}}" class="img-fluid">
                                                </div>
                                            @endif
                                            <div class="carousel-item">
                                                <img src="{{url($item->photo ?? '')}}" class="img-fluid">
                                            </div>
                                            @php
                                                $cont++;
                                            @endphp
                                        @endif

                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselGallery" data-bs-slide="prev">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselGallery" data-bs-slide="next">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>

                            <div class="position">
                                <span class="active-slide">1</span> / <span class="total"></span>
                            </div>
                        </div>
                        {{-- <div class="video sidebar-section">
                            <p class="title">Video</p>
                            <a href="https://www.youtube.com/watch?v=3XNjjmcvgiw" data-fancybox style="position: relative;">
                                <img src="{{asset('storage/events/gallery-5.jpg')}}" class="img-fluid">
                                <div class="play">
                                    <i class="fas fa-play"></i>
                                </div>
                            </a>
                            <p></p>
                        </div> --}}
                    </div>

                    @include("modales.modalEvent")

                </div>
            </div>
        </div>
    </div>

@endsection

@section('script_footer')
    <script src="{{asset('/js/tabs.js')}}"></script>
    <script src="{{asset('/js/dmi/events.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/dmi/event-calendar_settings.js')}}" type="text/javascript"></script>

    <script>

        $('#carouselGallery').val();

        ths.general_data.type_events = @json($type_events);
        ths.general_data.events = @json($events);

        ths.generateEventType();
        ths.addEventsToCalendar();
        ths.generateEventsToCalendar();

    </script>
@endsection
