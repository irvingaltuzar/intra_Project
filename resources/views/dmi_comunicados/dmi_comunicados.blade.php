@extends('layouts.app')
@section('title')
    <title>Comunicados | Intranet DMI</title>
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
                        <h1 class="title-page"><a class="text-dmi btn-back" href="{{ route('home') }}"><i class="fas fa-arrow-alt-circle-left"></i></a>Comunicados </h1>
                        <div class="card">
                            <div class="title bck-blue-light">
                                Comunicados Institucionales
                            </div>

                            <div class="content">
                                <table>
                                    @foreach($communiques_council as $communique)
                                        <tr>
                                            <td>
                                                <div class="container-checkbox form-check">
                                                    <span class=""><i class="fas fa-caret-right text-dmi"></i></span>
                                                    <a >
                                                        <label class="form-check-label" for="checkbox-1">
                                                            {{$communique->title}}
                                                            @if(strpos($communique->created_at, $communique->current_date) !== false)
                                                                <i class="fas fa-star yellow-star"></i>
                                                            @endif
                                                        </label>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="actions">
                                                    <button class="text-dark" onclick="showCommunique(this)" data-id_communique="{{$communique->id}}"><i class="fas fa-eye"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                    <tr id="data_council">
                                        <td>
                                            <div class="container-checkbox form-check">
                                                <a  id="a_link_council" class="btn btn-link btn-see-more {{$communiques_council->nextPageUrl() == null ? 'd-none' : ''}}"
                                                    data-next_page_url="{{$communiques_council->nextPageUrl()}}" data-type_communique="council">
                                                    <label class="form-check-label" for="checkbox-1">
                                                        Leer más
                                                    </label>
                                                </a>

                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="loading_council" style="display:none;">
                                        <td colspan="2">
                                            <div class="row justify-content-center">
                                                <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                            </div>
                        </div>

                        <div class="card">
                            <div class="title bck-dark-blue">
                                Movimientos organizacionales
                            </div>
                            <div class="content">
                                <table>
                                    @foreach($communiques_organizational as $key => $communique)
                                        <tr>
                                            <td>
                                                <div class="container-checkbox form-check">
                                                    <span class=""><i class="fas fa-caret-right text-dmi"></i></span>
                                                    <a>
                                                        <label class="form-check-label" for="checkbox-1">
                                                            {{$communique->title}}
                                                            @if($communique->communique_date == $communique->current_date)
                                                                <i class="fas fa-star yellow-star"></i>
                                                            @endif
                                                        </label>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="actions">
                                                    <button class="text-dark" onclick="showCommunique(this)" data-id_communique="{{$communique->id}}"><i class="fas fa-eye"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                    <tr id="data_organizational">
                                        <td>
                                            <div class="container-checkbox form-check">
                                                <a id="a_link_organizational" class="btn btn-link btn-see-more {{$communiques_organizational->nextPageUrl() == null ? 'd-none' : ''}}"
                                                    data-next_page_url="{{$communiques_organizational->nextPageUrl()}}" data-type_communique="organizational">
                                                    <label class="form-check-label" for="checkbox-1">
                                                        Leer más
                                                    </label>
                                                </a>

                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="loading_organizational" style="display:none;">
                                        <td colspan="2">
                                            <div class="row justify-content-center">
                                                <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>


                        <div class="card">
                            <div class="title bck-rose">
                                campañas institucionales
                            </div>
                            <div class="carousel-campaigns">
                                <div class="owl-carousel owl-theme">
                                    @foreach( $communiques_institutional as $institucional)
                                        <a class="text-decoration-none text-dark">
                                            <div class="item">
                                                    <div class="campaign">
                                                        <div class="image">
                                                            <img src="{{ asset($institucional->photo)}}" class="img-fluid images-link" data-img="{{ asset($institucional->photo)}}" data-id_communique="{{$institucional->id}}">
                                                        </div>
                                                        <div class="title-campaign">
                                                            {{$institucional->title}}
                                                            <button class="btn-blue-dmi" onclick="showCommunique(this)" data-id_communique="{{$institucional->id}}">Ver</button>
                                                        </div>
                                                    </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('modales.modalImages');

    </div>

@endsection

@section('script_footer')


    <link href="{{ asset('css/owl.carousel.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/owl.carousel.min.js') }}" defer ></script>
    <script src="{{ asset('js/communication.js') }}" ></script>



    {{-- <link href="{{ asset('css/owl.theme.default.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/dashboard.js') }}" defer></script> --}}


    <script>
    </script>
    <script src="{{asset('js/dmi/modal-images.js')}}"></script>
    <script src="{{asset('js/dmi/communiques.js')}}"></script>


@endsection
