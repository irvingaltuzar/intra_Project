@extends('layouts.app')
@section('title')
    <title>Beneficios | Intranet DMI</title>
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
                        <h1 class="title-page"><a class="text-dmi btn-back" href="{{ route('home') }}"><i class="fas fa-arrow-alt-circle-left"></i></a>Beneficios y prestaciones </h1>
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
                                    <div id="pane-benefits" class="card tab-pane fade show active" role="tabpanel" aria-labelledby="tab-benefits">
                                        <div class="card-header" role="tab" id="heading-benefits">
                                            <a data-bs-toggle="collapse" href="#collapse-benefits" aria-expanded="false" aria-controls="collapse-benefits">
                                                <h5 class="mb-0">
                                                    Beneficios
                                                </h5>
                                            </a>
                                        </div>
                                        <div id="collapse-benefits" class="collapse show" data-bs-parent="#content-tabs-blog" role="tabpanel"
                                            aria-labelledby="heading-benefits">
                                            <div class="card-body">
                                                <div class="content content-benefits">
                                                    @foreach($benefits as $benefit)
                                                        <article>
                                                            <div class="row content-article">
                                                                <div class="col-md-2 justify-content-center align-self-center">
                                                                    <div class="photo">
                                                                        <img src="{{asset('storage/'.$benefit->photo)}}" class="img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-10 justify-content-center align-self-center">
                                                                    <h3 class="title">{{$benefit->title}}</h3>
                                                                    <div class="description">
                                                                        {{$benefit->subtitle}}
                                                                    </div>
                                                                    <a  href="{{url('beneficio_prestacion/'.$benefit->id)}}" class="read-more">Conocer más...</a>
                                                                </div>
                                                            </div>
                                                        </article>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="pane-benefits-2" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-benefits-2">
                                        <div class="card-header" role="tab" id="heading-benefits-2">
                                            <a class="collapsed" data-bs-toggle="collapse" href="#collapse-benefits-2" aria-expanded="true"
                                                    aria-controls="collapse-benefits-2">
                                                <h5 class="mb-0">
                                                    Prestaciones
                                                </h5>
                                            </a>
                                        </div>
                                        <div id="collapse-benefits-2" class="collapse" data-bs-parent="#content-tabs-blog" role="tabpanel"
                                            aria-labelledby="heading-benefits-2">
                                            <div class="card-body">
                                                <div class="content content-benefits-2">
                                                    @foreach($prestaciones as $prestacion)
                                                        <article>
                                                            <div class="row content-article">
                                                                <div class="col-md-2 justify-content-center align-self-center">
                                                                    <div class="photo">
                                                                        <img src="{{asset('storage/'.$prestacion->photo)}}" class="img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-10 justify-content-center align-self-center">
                                                                    <h3 class="title">{{$prestacion->title}}</h3>
                                                                    <div class="description">
                                                                        {{$prestacion->subtitle}}
                                                                    </div>
                                                                    <a href="{{url('beneficio_prestacion/'.$prestacion->id)}}" class="read-more">Leer más...</a>
                                                                </div>
                                                            </div>
                                                        </article>
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
    </div>
@endsection
