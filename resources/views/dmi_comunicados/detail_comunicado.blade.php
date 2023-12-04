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
                    <h1 class="title-page">
                        <a class="text-dmi" href="{{ route('communication') }}"><i class="fas fa-arrow-alt-circle-left"></i></a>
                        Comunicado - {{$communique->title}}</h1>
                    <div class="content-profile">
                        <div class="row">
                            <div class="col-lg-9 col-md-12 col-sm-12 order-lg-1 order-1">
                                <div class="panel">
                                    <div class="title">
                                        <div class="card-icon">
                                            <i class="fas fa-bullhorn"></i>
                                        </div>
                                        <i class="fas fa-map-marker-alt text-danger"></i><span id="modal_location" class="modal-name-location ps-1">{{$communique->location->name}} - {{\Carbon\Carbon::parse($communique->created_at)->format('d-M-Y g:i A')}}</span>
                                    </div>
                                    
                                    <div class="card-body">
                                        <div class="card-content mt-5">
                                            <br>
                                            <p>{{$communique->description}}</p>
                                            @if($communique->video)
                                                <video class="w-100 border"  src="{{asset($communique->video)}}" id="modal-video-full" alt="" controls="controls" autoplay="autoplay">
                                                    Vídeo no es soportado...                      
                                                </video>
                                            @else
                                                @if($communique->photo)
                                                    <img src="{{asset(($communique->photo ?? 'communique/default.jpg'))}}" class="w-100">
                                                @else
                                                    <img src="{{asset('storage/communique/default.jpg')}}" class="w-100">
                                                @endif
                                            @endif
                                            
                                        </div>
                                    </div>

                                    
                                </div>
                                
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 order-lg-2 order-2">
                                <div class="panel">
                                    <h4>Archivos</h4>
                                    <hr>
                                    <div class="card-body">
                                        <div class="card-content">
                                            @foreach ($communique->files as $file)
                                                <i class="fas fa-paperclip text-dmi"></i><a class="ps-1 text-link" target="_blank" href="{{ asset('storage/'.$file->file) }}">{{$file->name}}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="panel">
                                    <h4>Enlace</h4>
                                    <hr>
                                    <div class="card-body">
                                        <div class="card-content">
                                            @if ($communique->link != null)
                                            <i class="fas fa-link text-dmi"></i><a class="ps-1 text-link" target="_blank" href="{{url($communique->link)}}">{{url($communique->link)}}</a>
                                            @endif
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

@endsection