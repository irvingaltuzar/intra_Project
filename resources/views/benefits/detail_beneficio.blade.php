@extends('layouts.app')
@section('title')
    <title>DMI Comunicación Institucional | Intranet DMI</title>
@endsection
@section('script')
    <link href="{{ asset('css/dark-footer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/light-menu.css') }}" rel="stylesheet">
    <link href="{{ asset('css/general.css') }}" rel="stylesheet">
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container-fluid ptop-150 content-section">
        <div class="pattern"></div>
        <div class="container mheight-80">
            <div class="row">
                <div class="col-sm-12">
                    <h1 class="title-page"><a class="text-dmi btn-back" href="/beneficios_prestaciones{{$benefit->type == 'beneficio' ? '#pane-benefits' : '#pane-benefits-2'}}"><i class="fas fa-arrow-alt-circle-left"></i></a>Beneficios y Prestaciones</h1>
                    <div class="content-profile">
                        <div class="row">
                            <div class="col-lg-7 col-md-12 order-lg-1 order-2">
                                <div class="panel">
                                    <div class="title">
                                        <div class="card-icon">
                                            <i class="fas fa-hand-holding-{{$benefit->type == 'Beneficio' ? 'heart' : 'usd'}}"></i>
                                        </div>
                                    </div>
                                    <h5>{{$benefit->title}}</h5>
                                    <br>
                                    <div class="row m-1 section-description">
                                        <p id="modal-description" class="ms-2 p-2 ">
                                            {{$benefit->description}}
                                        </p>
                                      </div>
                                    @if($benefit->photo)
                                        <img src="{{url($benefit->photo)}}" class="w-100">
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-12 order-lg-2 order-1">
                                <div class="panel">
                                    <h5>Link</h5>
                                    <br>
                                    <i class="fas fa-link"></i><a class="ps-1 text-link" id="modal-link" href="{{$benefit->link}}" target="_blank">{{$benefit->link}}</a>
                                </div>
                                <br>
                                <div class="panel">
                                    <h4>Archivos</h4>
                                    <hr>
                                    <div class="card-body">
                                        <div class="card-content">
                                            @foreach ($benefit->files as $file)
                                                <i class="fas fa-paperclip text-dmi"></i><a class="ps-1 text-link" target="_blank" href="{{ url($file->file) }}">{{$file->name}}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer')
@endsection
