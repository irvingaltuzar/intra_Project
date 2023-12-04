@extends('layouts.app')
@section('title')
    <title>Directorio | Intranet DMI</title>
@endsection
@section('script')
    <link href="{{ asset('css/light-menu.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dmi/directory.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container-fluid ptop-150 content-section">
        <div class="pattern"></div>
        <div class="container mheight-80">
            <div class="row">
                <div class="col-sm-12">
                    <h1 class="title-page"><a class="text-dmi btn-back" href="{{ route('home') }}"><i class="fas fa-arrow-alt-circle-left"></i></a>Directorio</h1>
                    <div class="content-table">
                        <div class="title">
                            <span>Empleados</span><br>
                        </div>
                        <div class="information">
                            <div class="row justify-content-end m-2 p-2">
                                <div class="offset-2 col-sm-12 col-md-5">
                                    {{-- <input type="email" class="form-control" id="txt_search" placeholder="Buscar..."><i class="fas fa-search"></i> --}}
                                    <div class="input-group mb-3">
                                        {{-- <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span> --}}
                                        <input type="text" class="form-control"  id="txt_search" placeholder="Buscar..." aria-label="Username" aria-describedby="basic-addon1">
                                        <span class="input-group-text clear_search"><i class="fas fa-times-circle"></i></span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-2">
                                    {{-- <label for="order_by">Ordenar por</label> --}}
                                    <select id="order_by" class="form-select" aria-label="Default select example">
                                        <option selected value="asc">Ascendente</option>
                                        <option value="desc">Descendente</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 col-md-2">
                                    {{-- <label for="per_page">Por página</label> --}}
                                    <select id="per_page" class="form-select" aria-label="Default select example">
                                        <option selected value="20">20</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="data_directorio">
                                <div class="table-responsive">
                                    <table class="table ">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th scope="col">Departamento / Puesto</th>
                                                <th scope="col">Ubicación</th>
                                                <th scope="col">Correo electrónico</th>
                                                <th scope="col">Extensión</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody_directorio">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-responsive">
                                    @include('componentes_generales.pagination_control')
                                </div>

                            </div>
                            <div class="row m-5" id="loading_directorio" style="display:none;">
                                <div class="col-sm-12 col-md-12 text-center">
                                    <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
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

    <script src="{{asset('js/dmi/directory.js')}}"></script>
    <script src="{{asset('js/generico/pagination_control.js')}}"></script>
    <script>
        ths.getPage();

    </script>



@endsection
