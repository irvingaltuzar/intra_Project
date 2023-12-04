@extends('layouts.app')
@section('title')
    <title>E-Learning | Intranet DMI</title>
@endsection
@section('script')

    <link href="{{ asset('css/light-menu.css') }}" rel="stylesheet">
    <link href="{{ asset('css/communication.css') }}" rel="stylesheet">

@endsection
@section('content')
    <div class="container-fluid ptop-150 content-section" id="comunication">
        <div class="pattern"></div>
        <div class="container">
            <div class="content-comunication">
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="title-page"><a class="text-dmi btn-back" href="{{ route('home') }}"><i class="fas fa-arrow-alt-circle-left"></i></a>E-Learning </h1>
                        <div class="card">
                            <div class="title bck-blue-light text-center">
                                Material de aprendizaje
                            </div>

                            <div class="content">

                                <div class="row pt-5 m-auto">
                                    @foreach ($data as $item)
                                        <div class="col-md-6 col-lg-4 pb-3">
                                            <div class="card card-custom bg-white border-white border-0">
                                                <div class="card-custom-img" style="background-image: url('{{$item['video_cover']}}');"></div>
                                                <div class="card-body m-2" style="overflow-y: auto">
                                                    <h5 class="card-title"><strong>{{$item['title']}}</strong></h5>
                                                    <p class="card-text card-custom-text">{{$item['description']}}</p>
                                                </div>
                                                <div class="card-footer text-center" style="background: inherit; border-color: inherit;">
                                                    <a target="_black" onclick="showVideo({{$item['id']}})" class="special-buttom">Ver</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                            </div>
                        </div>
                    </div>
                </div>

                @include("modales.elearning_video")
            </div>
        </div>

    </div>

@endsection

@section('script_footer')
<script src="{{asset('js/dmi/elearning.js')}}"></script>

<script>
    var ths = this;
    var general_data = {
        data:@json($data),

    };
</script>
@endsection
