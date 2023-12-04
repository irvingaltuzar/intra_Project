@extends('layouts.app')
@section('title')
    <title>Project Board | Intranet DMI</title>
@endsection
@section('script')
    {{-- Start- Estilos genéricos necesarios --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/project_board.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/estilos_globales.css')}}">
    <link href="{{ asset('css/light-menu.css') }}" rel="stylesheet">
    <link href="{{ asset('css/collaborators.css') }}" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('Calendar-09/css/bootstrap-datetimepicker.min.css')}}">

    <script src="{{ asset('Calendar-09/js/popper.js') }}" ></script>
    <script src="{{ asset('Calendar-09/js/jquery.min.js') }}" ></script>
    <script src="{{ asset('Calendar-09/js/moment-with-locales.min.js') }}" ></script>
    <script src="{{ asset('Calendar-09/js/bootstrap-datetimepicker.min.js') }}" ></script>
    <script src="{{ asset('Calendar-09/js/main.js') }}" ></script>


    {{-- End - Estilos genéricos necesarios --}}

    <script>
        /* $(document).ready(function(){
            $('[data-toggle="popover"]').popover();
        }); */
    </script>
@endsection
@section('content')
    <div class="container-fluid ptop-150 content-section">
        <div class="pattern"></div>
        <div class="container">
            <div class="content-blog">
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="title-page"><a class="text-dmi btn-back" href="{{ route('home') }}"><i class="fas fa-arrow-alt-circle-left"></i></a>Project Board</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <br>
                            <div class="content mb-5">
                                @php
                                    $project_revision = collect($projects->where('project_board_categories_id',1));
                                @endphp
                                <div class="row mb-3">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                              <tr>
                                                <th class="col-4" scope="col">Tarea</th>
                                                <th class="col-2" scope="col">Propietario</th>
                                                <th class="col-2" scope="col">Líder</th>
                                                <th class="col-2" scope="col">Estatus</th>
                                                <th class="col-2" scope="col">Progreso</th>
                                              </tr>
                                            </thead>
                                            <tbody class="bg-tbody">
                                                @if (sizeof($project_revision) > 0)
                                                    @foreach ($project_revision as $project)
                                                        <tr class="border-tr b-tr-revisando">
                                                            <td>{{ $project->name }}</td>
                                                            <td>
                                                                <span class="user-name mb-1">{{ $project->owner->full_name }}</span><br>
                                                                <span class="user-job">{{ $project->owner->position_company_full }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="user-name">{{ $project->leader->full_name }}</span><br>
                                                                <span class="user-job">{{ $project->leader->position_company_full }}</span>
                                                            </td>
                                                            <td class="status-task-{{$project->project_board_categories_id}} text-white">{{ $project->category->name }}</td>
                                                            <td>
                                                                <div class="progress">
                                                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="{{ $project->progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $project->progress }}%">{{ $project->progress }}%</div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else  
                                                    <tr class="border-tr b-tr-revisando text-center">
                                                        <td colspan="5" class="h5">Sin tareas</td>
                                                    </tr>                                                    
                                                @endif
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                @php
                                    $project_planeando = collect($projects->where('project_board_categories_id',2));
                                @endphp
                                <div class="row mb-3">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                              <tr>
                                                <th class="col-4" scope="col">Tarea</th>
                                                <th class="col-2" scope="col">Propietario</th>
                                                <th class="col-2" scope="col">Líder</th>
                                                <th class="col-2" scope="col">Estatus</th>
                                                <th class="col-2" scope="col">Progreso</th>
                                              </tr>
                                            </thead>
                                            <tbody class="bg-tbody">
                                                @if (sizeof($project_planeando) > 0)
                                                    @foreach ($project_planeando as $project)
                                                        <tr class="border-tr b-tr-planeando">
                                                            <td>{{ $project->name }}</td>
                                                            <td>
                                                                <span class="user-name mb-1">{{ $project->owner->full_name }}</span><br>
                                                                <span class="user-job">{{ $project->owner->position_company_full }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="user-name">{{ $project->leader->full_name }}</span><br>
                                                                <span class="user-job">{{ $project->leader->position_company_full }}</span>
                                                            </td>
                                                            <td class="status-task-{{$project->project_board_categories_id}} text-white">{{ $project->category->name }}</td>
                                                            <td>
                                                                <div class="progress">
                                                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="{{ $project->progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $project->progress }}%">{{ $project->progress }}%</div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr class="border-tr b-tr-planeando text-center">
                                                        <td colspan="5" class="h5">Sin tareas</td>
                                                    </tr>
                                                @endif
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @php
                                    $project_ejecucion = collect($projects->where('project_board_categories_id',3));
                                @endphp
                                <div class="row mb-3">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                              <tr>
                                                <th class="col-4" scope="col">Tarea</th>
                                                <th class="col-2" scope="col">Propietario</th>
                                                <th class="col-2" scope="col">Líder</th>
                                                <th class="col-2" scope="col">Estatus</th>
                                                <th class="col-2" scope="col">Progreso</th>
                                              </tr>
                                            </thead>
                                            <tbody class="bg-tbody">
                                                @if (sizeof($project_ejecucion) > 0)
                                                    @foreach ($project_ejecucion as $project)
                                                        <tr class="border-tr b-tr-ejecucion">
                                                            <td>{{ $project->name }}</td>
                                                            <td>
                                                                <span class="user-name mb-1">{{ $project->owner->full_name }}</span><br>
                                                                <span class="user-job">{{ $project->owner->position_company_full }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="user-name">{{ $project->leader->full_name }}</span><br>
                                                                <span class="user-job">{{ $project->leader->position_company_full }}</span>
                                                            </td>
                                                            <td class="status-task-{{$project->project_board_categories_id}} text-white">{{ $project->category->name }}</td>
                                                            <td>
                                                                <div class="progress">
                                                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="{{ $project->progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $project->progress }}%">{{ $project->progress }}%</div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr class="border-tr b-tr-ejecucion text-center">
                                                        <td colspan="5" class="h5">Sin tareas</td>
                                                    </tr>
                                                @endif
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                @php
                                    $project_terminado = collect($projects->where('project_board_categories_id',4));
                                @endphp
                                <div class="row mb-3">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                              <tr>
                                                <th class="col-4" scope="col">Tarea</th>
                                                <th class="col-2" scope="col">Propietario</th>
                                                <th class="col-2" scope="col">Líder</th>
                                                <th class="col-2" scope="col">Estatus</th>
                                                <th class="col-2" scope="col">Progreso</th>
                                              </tr>
                                            </thead>
                                            <tbody class="bg-tbody">
                                                @if (sizeof($project_terminado) > 0)
                                                    @foreach ($project_terminado as $project)
                                                        <tr class="border-tr b-tr-terminado">
                                                            <td>{{ $project->name }}</td>
                                                            <td>
                                                                <span class="user-name mb-1">{{ $project->owner->full_name }}</span><br>
                                                                <span class="user-job">{{ $project->owner->position_company_full }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="user-name">{{ $project->leader->full_name }}</span><br>
                                                                <span class="user-job">{{ $project->leader->position_company_full }}</span>
                                                            </td>
                                                            <td class="status-task-{{$project->project_board_categories_id}} text-white">{{ $project->category->name }}</td>
                                                            <td>
                                                                <div class="progress">
                                                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="{{ $project->progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $project->progress }}%">{{ $project->progress }}%</div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr class="border-tr b-tr-terminado text-center">
                                                        <td colspan="5" class="h5">Sin tareas</td>
                                                    </tr>
                                                @endif
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <br>
                <br>
                <br>
                <br>
            </div>
        </div>
    </div>

@endsection

@section('script_footer')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css"/>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
    <script src="{{asset('/js/dmi/project_board.js')}}" type="text/javascript"></script>

    <script>

    </script>
@endsection
