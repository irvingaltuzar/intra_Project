@extends('layouts.app')
@section('title')
    <title>DMI Comunicación Institucional | Intranet DMI</title>
@endsection
@section('script')


    <link href="{{ asset('css/light-menu.css') }}" rel="stylesheet">
    <link href="{{ asset('css/general.css') }}" rel="stylesheet">
    <link href="{{ asset('css/organigrama.css') }}" rel="stylesheet">
    <link href="{{ asset('css/communication.css') }}" rel="stylesheet">

    <script>

    </script>
@endsection
@section('content')
    <div class="container-fluid ptop-150 content-section" id="comunication">
        <div class="container mheight-80">
            <div class="content-comunication">
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="title-page"><a class="text-dmi btn-back" href="{{ route('organigrama') }}"><i class="fas fa-arrow-alt-circle-left"></i></a>ORGANIGRAMA</h1>
                        <div class="card">
                            <div class="title bck-blue-light">
                                {{$title}}
                            </div>
                            <div>
                                <iframe id="inlineFrameExample"
                                    title="Inline Frame Example"
                                    width="100%"
                                    height="750"
                                    style="border: 1px solid #3f5465bf;
                                    border-radius: 11px;"
                                    src="{{url('/organigrama/frame')}}/{{$type}}">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script_footer')
    <script src="{{asset('js/dmi/organization_chart.js')}}"></script>
    {{-- <script>
        ths.general_data.organization_back = @json($organigrama);
        ths.general_data.title = @json($title);
        ths.general_organization_chart();

    </script>
 --}}

@endsection
