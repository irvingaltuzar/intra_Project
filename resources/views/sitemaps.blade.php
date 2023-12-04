@extends('layouts.app')
@section('title')
    <title>DMI Comunicación Institucional | Intranet DMI</title>
@endsection
@section('script')
    

    <link href="{{ asset('css/light-menu.css') }}" rel="stylesheet">
    <link href="{{ asset('css/general.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dmi/sitemaps.css') }}" rel="stylesheet">
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
                        <h1 class="title-page"><a class="text-dmi btn-back" href="{{ route('home') }}"><i class="fas fa-arrow-alt-circle-left"></i></a>MAPA DEL SITIO</h1>
                        <div class="card">
                            <div class="content org-chart-content">
                                
                                <div id="organigrama" name="organigrama" class="organigrama organigrama-content p-2">
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>   
            </div>
        </div>
    </div>

@endsection

@section('script_footer')
    <script src="{{asset('js/dmi/sitemaps.js')}}"></script>
    <script>
        ths.general_data.title = "DMI Comunicación Institucional";
        ths.general_organization_chart();
    </script>
    

@endsection