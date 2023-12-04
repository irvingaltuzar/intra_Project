@extends('layouts.app')
@section('title')
    <title>Perfil | Intranet DMI</title>
@endsection
@section('script')
    <link href="{{ asset('css/light-menu.css') }}" rel="stylesheet">
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container-fluid ptop-150 content-section">
        <div class="pattern"></div>
        <div class="container mheight-80">
            <div class="row">
                <div class="col-sm-12">
                    <h1 class="title-page"><a class="text-dmi btn-back" href="{{ route('home') }}"><i class="fas fa-arrow-alt-circle-left"></i></a>Mi Perfil</h1>
                    <div class="content-profile">
                        <div class="row">
                            <div class="col-lg-7 col-md-12 order-lg-1 order-2">
                                <div class="panel">
                                    <div class="title">
                                        <div class="card-icon">
                                            <i class="far fa-user"></i>
                                        </div>
                                    </div>
                                    <div class="row pt-4">
                                        <div class="col-sm-12 col-md-6">
                                            <label>Número de empleado:</label>
                                            <div>{{$user->personal_id}}</div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label>Fecha de antigüedad:</label>
                                            <div>{{strftime("%d/%m/%Y", strtotime($user->antiquity_date))}}</div>
                                        </div>
                                        {{-- <div class="col-md-12 col-md-12">
                                            <label>Correo electrónico:</label>
                                            <div>{{$user->email}}</div>
                                        </div>
                                        <div class="col-sm-12 col-md-3">
                                            <label>Extensión:</label>
                                            <div>{{$user->extension}}</div>
                                        </div> --}}
                                        <div class="col-sm-12 col-md-6">
                                            <label>Departamento:</label>
                                            <div>{{$user->deparment}}</div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label>Ubicación:</label>
                                            <div>{{$user->location}}</div>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <label>Empresa:</label>
                                            <div>{{$user->company_name}}</div>
                                        </div>
                                        <hr class="mt-3">
                                        <div class="col-sm-12 col-md-12">
                                            <label>Jefe:</label>
                                            <div>{{$jefe != null ? ($jefe->name.' '.$jefe->last_name) : "Sin asignar" }}</div>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <label>Puesto:</label>
                                            <div>{{$jefe != null ? ($jefe->position_company_full) : ""}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-12 order-lg-2 order-1">
                                <div class="panel">
                                    <div class="photo">
                                    @if($user->photo)
                                        <img src="{{ $user->photo_src }}" class="img-fluid person w-50">
                                    @else
                                        @if($user->sex=='Masculino')
                                            <img src="{{ asset('image/icons/masculino.svg')}}" class="img-fluid person w-50">
                                        @elseif($user->sex=='Femenino')
                                            <img src="{{ asset('image/icons/femenino.svg')}}" class="img-fluid person w-50">
                                        @else
                                            <img src="{{ asset('image/icons/general.svg')}}" class="img-fluid person w-50">
                                        @endif
                                    @endif
                                    </div>
                                    <div class="user-info">
                                        <p class="job">{{$user->position_company_full}}</p>
                                        <p class="name">{{$user->name}} {{$user->last_name}}</p>
                                    </div>
                                    <hr>
                                    <label>Horario:</label>
                                    @foreach($horario as $value)
                                        @if($value->week_day != null)
                                            <div><strong>{{ $days[$value->week_day]}}:</strong> {{$value->entrada}} - {{$value->salida}}</div>
                                        @endif

                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
