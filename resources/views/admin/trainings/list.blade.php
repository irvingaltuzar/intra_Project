@extends('admin.layout.layout')
@section( 'content')
    <div class="page-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-9">
                                <h4>Capacitaciones</h4>
                                <input type="hidden" id="submenu" value="{{$submodule}}">
                            </div>
                            <div class="col-md-3 text-right">
                                <button type="button" class="btn btn-info" onclick="newRecord()"><i class="fas fa-plus"></i> Agregar</button>
                            </div>
                        </div>
                    </div>

                    <div class="card-block table-border-style">
                        <div class="row justify-content-end m-2 p-2">
                            <div class="offset-md-2 col-sm-12 col-md-5">
                                <div class="input-group mb-3">
                                    <span class="input-group-text icon-search"><i class="fas fa-search text-dmi"></i></span>
                                    <input type="text" class="form-control"  id="txt_search" placeholder="Buscar..." aria-label="Username" aria-describedby="basic-addon1">
                                    <span class="input-group-text clear_search"><i class="fas fa-times-circle text-dmi"></i></span>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-2">
                                <select id="order_by" class="form-select" aria-label="Default select example">
                                    <option selected value="desc">Ascendente</option>
                                    <option value="asc">Descendente</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-1">
                                <select id="limit_page" class="form-select" aria-label="Default select example">
                                    <option selected value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Título</th>
                                        <th>Ubicación</th>
                                        <th>Datos de capacitación</th>
                                        <th>Fecha de Expiración</th>
                                        <th>Creación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="data_list" >
                                </tbody>
                                <tbody id="loading_list" style="display:none;">
                                    <tr>
                                        <td colspan="6">
                                            <div class="col-sm-12 col-md-12 text-center p-4">
                                                <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive text-center">
                            @include('componentes_generales.pagination_control')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.trainings.addModal')
    @include('admin.trainings.viewModal')
    @include('admin.trainings.editModal')
@endsection

@section('script_footer')
    <script type="text/javascript" src="{{asset('js/admin/trainings/trainings.js')}}"></script>
    <script src="{{asset('js/generico/pagination_control.js')}}"></script>

    <script>
        ths.general_data.data = @json($list);
        ths.preLoadLocations();
        ths.preLoadSubgroups();
        ths.addList(ths.general_data.data.data,ths.general_data.data.from);


    </script>

@endsection
