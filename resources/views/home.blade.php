@extends('layouts.app')
@section('title')
    <title>{{ config('app.name', 'Intranet DMI') }}</title>
@endsection
@section('script')
    <link rel="stylesheet" type="text/css" href="{{asset('css/dashboard.css')}}">
    <link href="{{ asset('css/communication.css') }}" rel="stylesheet">

@endsection

@section('content')

<div class="container-fluid" id="dashboard">
    <div class="container" >
        <div class="content-dashboard">
            <div class="row">
                <div class="col-sm-12">
                    <h1>Bienvenido a <span>Intranet</span></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-7 col-md-7 col-sm-12">
                    <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            @foreach($communiques as $key => $communique)
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}" aria-current="true" aria-label="Slide 1"></button>
                            @endforeach
                        </div>
                        <div class="carousel-inner" style="box-shadow: 0px 0px 10px 0px rgb(255 255 255 / 35%);">
                            @foreach($communiques as $key => $communique)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <div>
                                        <img onclick="showCommunique(this)" data-id_communique="{{$communique->id}}" src="{{asset($communique->photo)}}" class="d-block w-100 img-left-carrusel" alt="...">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-12 divider-3">
                    <div class="row divider-3">
                        <div class="container">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-md-9 home-card-transparency-title">
                                        Cumpleaños de la semana
                                    </div>
                                </div>
                                <div class="row home-card-transparency-body justify-content-center" style="height: 195px;display:table;">
                                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach($birthday_boys as $key => $birth)
                                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }} mt-2">
                                                    <div class="row mb-1">
                                                        <div class="col-md-5 col-sm-12" style="display: flex;align-items: center;">
                                                            @if($birth['photo'])
                                                                <img onerror="this.src='{{asset('/image/icons/user.svg')}}';" src="{{$birth['photo_src']}}" class="img-fluid birth-person" style="margin-top: 4px;">
                                                            @else
                                                                @if($birth['sex']=='Masculino')
                                                                    <img src="{{ asset('image/icons/masculino.svg')}}" class="img-fluid birth-person" style="margin-top: 4px;">
                                                                @elseif($birth['sex']=='Femenino')
                                                                    <img src="{{ asset('image/icons/femenino.svg')}}" class="img-fluid birth-person" style="margin-top: 4px;">
                                                                @else
                                                                    <img src="{{ asset('image/icons/general.svg')}}" class="img-fluid birth-person" style="margin-top: 4px;">
                                                                @endif
                                                            @endif
                                                        </div>
                                                        <div class="col-md-7 col-sm-12 text-center mt-3">
                                                            <div class="birth-name">{{ $birth['full_name'] }}</div>
                                                            <div class="birth-job">{{$birth['position_company_full']}}</div>
                                                            <div class="birth-date mt-2">{{strftime("%d %B", strtotime($birth['birth']))}}</div>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="row" style="background:white; border-radius:25px;margin:0px;padding: 2px;" >
                                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="justify-content: center;display: flex;">
                                                            <div class="birth-location mt-2" style="border-radius:30px;">
                                                                @if($birth['photo_location'])
                                                                    <img src="{{url('/')}}/storage/{{$birth['photo_location']}}" style="height: 35px;
                                                                    width: auto;">
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="align-items: center;display: inline-grid;text-align: center;">
                                                            <div class="row">
                                                                <div class="col">
                                                                    <span class="birth-btn-congratulation">
                                                                        {{-- Si ya comento --}}
                                                                        @php
                                                                            $can_like = false;
                                                                            if($birth['publication_birthday'] != null){
                                                                                if(sizeof($birth['publication_birthday']['reactions']) > 0){
                                                                                    $collection_reaction= collect($birth['publication_birthday']['reactions']);
                                                                                    if($collection_reaction->where('vw_users_usuario',auth()->user()->usuario)->first() != null){
                                                                                        $can_like = false;
                                                                                    }else{
                                                                                        $can_like = true;
                                                                                    }
                                                                                }else{
                                                                                    $can_like = true;
                                                                                }
                                                                            }else{
                                                                                $can_like = true;
                                                                            }
                                                                            
                                                                        @endphp
        
                                                                        {{-- @if (auth()->user()->usuario == $birth['usuario'])
                                                                            <i class="fad fa-birthday-cake fa-lg" 
                                                                                style="--fa-primary-color:var(--color-capital-dmi); --fa-secondary-color:var(--color-agroindustrial-dmi); --fa-secondary-opacity: 1;" 
                                                                                data-bs-toggle="tooltip" 
                                                                                data-bs-placement="bottom" 
                                                                                title="Felicitar"
                                                                                onclick="viewReactionsModal({{ $birth['publication_birthday'] == null ? 'null' : $birth['publication_birthday']['id'] }})"
                                                                            ></i>
                                                                        @else
                                                                            <i class="fad fa-birthday-cake fa-lg {{ $can_like == false ? '' : 'd-none' }}" onclick="viewReactionsModalBirthday({{$birth['publication_birthday']['id'] != null ? $birth['publication_birthday']['id'] : 0}},'cumpleaños',{{$birth['usuarioId']}},'{{$birth['usuario']}}','{{$birth['birth']}}')" id="btn_congratulations_{{ $birth['usuarioId'] }}" style="--fa-primary-color:var(--color-capital-dmi); --fa-secondary-color:var(--color-agroindustrial-dmi); --fa-secondary-opacity: 1;" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Felicitar"></i>
                                                                            <i class="fas fa-birthday-cake fa-lg {{ $can_like == false ? 'd-none' : '' }} no-reaction" onclick="giveLikeBirthday({{ $birth['usuarioId'] }},'{{ $birth['usuario'] }}',{{ $birth['publication_birthday'] == null ? 'null' : $birth['publication_birthday']['id'] }},'{{ $birth['birth'] }}')" id="btn_no-congratulations_{{ $birth['usuarioId'] }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Felicitar"></i>    
                                                                        @endif  --}} 
                                                                        <i class="fad fa-birthday-cake fa-lg {{ $can_like == false ? '' : 'd-none' }}" onclick="viewReactionsModalBirthday({{$birth['publication_birthday'] != null ? $birth['publication_birthday']['id'] : 0}},'cumpleaños',{{$birth['usuarioId']}},'{{$birth['usuario']}}','{{$birth['birth']}}')" id="btn_congratulations_{{ $birth['usuarioId'] }}" style="--fa-primary-color:var(--color-capital-dmi); --fa-secondary-color:var(--color-agroindustrial-dmi); --fa-secondary-opacity: 1;" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Felicitar"></i>
                                                                        <i class="fas fa-birthday-cake fa-lg {{ $can_like == false ? 'd-none' : '' }} no-reaction" onclick="giveLikeBirthday({{ $birth['usuarioId'] }},'{{ $birth['usuario'] }}',{{ $birth['publication_birthday'] == null ? 'null' : $birth['publication_birthday']['id'] }},'{{ $birth['birth'] }}')" id="btn_no-congratulations_{{ $birth['usuarioId'] }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Felicitar"></i>       
        
                                                                        
                                                                    </span>
        
                                                                    <span class="birth-font-number pe-3" id="count_reactions_{{ $birth['usuarioId'] }}">{{ (isset($birth['publication_birthday']) ? sizeof($birth['publication_birthday']['reactions']) : '0' ) }}</span>
        
                                                                    <span class="birth-btn-congratulation p-1">
                                                                        <a href="/collaborators?section=pane-birthday#link_{{ str_replace('.','_',$birth['usuarioId']) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Agregar comentario">
                                                                            <i class="fad fa-comments fa-lg" style="--fa-primary-color:var(--color-capital-dmi); --fa-secondary-color:var(--color-agroindustrial-dmi); --fa-secondary-opacity: 1;"></i>
                                                                        </a>
                                                                    </span>
                                                                    <span class="birth-font-number">{{ $birth['publication_birthday'] != null ? $birth['publication_birthday']['total_comments'] : '0' }}</span>
                                                                </div>
                                                            </div>
                                                        </div>                                                
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                          <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                          <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                          <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <div class="row pt-3">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-md-9 home-card-transparency-title" style="background: var(--color-bienes-dmi)!important;">
                                        Revista digital
                                    </div>
                                </div>
                                <div class="row home-card-transparency-body text-center" style="height: 160px;">
                                    <div class="col">
                                        <a href="{{route('blog')}}">
                                            <img src="{{asset('image/portada_revista.png')}}" class="img-magazine rounded " data-bs-toggle="tooltip" data-bs-placement="left" title="Revista digital" alt="...">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 divider-3">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-md-9 home-card-transparency-title"  style="background: var(--color-bienes-dmi)!important;">
                                        aplicaciones
                                    </div>
                                </div>
                                <div class="row home-card-transparency-body text-center" style="height: 160px;">
                                    <div class="col">
                                        <a role="button" href="{{ route('show_board') }}">
                                            <img src="{{asset('image/icons/icon_dashboard.png')}}" class="icon-application" data-bs-toggle="tooltip" data-bs-placement="left" title="Project Board" alt="...">
                                            <h5 class="m-1 text-white" style="font-size:0.8rem;">Project Board</h5>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <div data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight-ticket" aria-controls="offcanvasRight-ticket">
                                            <img src="{{asset('image/icons/icon_fresh_services.png')}}" class="icon-application" data-bs-toggle="tooltip" data-bs-placement="left" title="Sistema de Tickets" alt="...">
                                            <span id="notification-count-freshservices" class="notification-count d-none"></span>
                                        </div>
                                        <h5 class="m-1 text-white" style="font-size:0.8rem;">Freshservice</h5>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                    {{--  --}}
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4 col-sm-12 divider-3">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-9 home-card-transparency-title">
                                Colaboradores
                            </div>
                        </div>
                        <div class="row home-card-transparency-body justify-content-center" style="height: 195px;display:table;">
                            <div id="carouselExampleControls_collaborator" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    
                                    @if (sizeof($publication_collaborators) > 0)
                                        @foreach($publication_collaborators as $key => $publication)
                                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                                    @if ($publication->type_publication == 'publication_promotion')
                                                        <div class="row justify-content-center mb-1">
                                                            <div class="col-6 sub-title-publication text-center">
                                                                Ascenso
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-5 p-1 text-center">
                                                                @if($publication->photo != null)
                                                                    <img onerror="this.src='{{asset('/image/icons/user.svg')}}';" src="{{$publication->photo}}" class="img-fluid birth-person" style="width: 45%!important;">
                                                                @else
                                                                    <img src="{{ asset('image/icons/user.svg')}}" class="img-fluid birth-person"  style="width: 45%!important;">
                                                                @endif
            
                                                            </div>
                                                            <div class="col-md-7 col-sm-12 text-center pt-1">
                                                                <div class="birth-name mb-2">{{ $publication->user_name }}</div>
                                                                <div class="birth-job">Nueva posición</div>
                                                                <div class="birth-name">{{$publication->new_position_company}}</div>
                                                                <a href="{{ url('/collaborators?section=pane-promotions') }}">
                                                                    <button class="btn-blue-dmi mt-2" data-toggle="tooltip" title="Ir a ascensos" ><i class="fas fa-share" style="padding: 2px;"></i></button>
                                                                </a>
                                                            </div>
                                                            <div class="row justify-content-center mt-2" >
                                                                <div class="col-md-3 col-sm-3 text-center" style="background:white; border-radius:25px;margin:0px;padding: 7px;">
                                                                    <span class="birth-btn-congratulation">
                                                                        @php
                                                                            $can_react = false;
                                                                            if($publication->reactions != null){
                                                                                if(sizeof($publication->reactions) > 0){
                                                                                    if($publication->reactions->where('vw_users_usuario',auth()->user()->usuario)->first() != null){
                                                                                        $can_react = false;
                                                                                    }else{
                                                                                        $can_react = true;
                                                                                    }
                                                                                }else{
                                                                                    $can_react = true;
                                                                                }
                                                                            }else{
                                                                                $can_react = true;
                                                                            }
                                                                            
                                                                        @endphp
                                                                        <i class="fad fa-thumbs-up fa-lg no-reaction {{ $can_react == false ? '' : 'd-none' }}" id="btn_like_{{ $publication->id }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Me gusta" style="--fa-primary-color:var(--color-agroindustrial-dmi); --fa-secondary-color:var(--color-capital-dmi); --fa-secondary-opacity: 1;"></i>
                                                                        <i class="fas fa-thumbs-up fa-lg no-reaction {{ $can_react == true ? '' : 'd-none' }}" id="btn_no-like_{{ $publication->id }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Me gusta" onclick="giveLikePromotion({{ $publication->id }})"></i>
                                                                    </span>
                                                                    <span class="birth-font-number" id="count_reactions_promotions_{{ $publication->id }}">{{ $publication->total_reactions }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif ($publication->type_publication == 'new_staff')
                                                        <div class="row justify-content-center mb-3">
                                                            <div class="col-6 sub-title-publication text-center">
                                                                Nuevo ingreso
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-5 p-2 text-center">
                                                                @if($publication->photo != null)
                                                                    <img onerror="this.src='{{asset('/image/icons/user.svg')}}';" src="{{$publication->photo_src}}" class="img-fluid birth-person" style="width: 45%!important;">
                                                                @else
                                                                    <img src="{{ asset('image/icons/user.svg')}}" class="img-fluid birth-person"  style="width: 45%!important;">
                                                                @endif
                                                                <a href="{{ url('/collaborators?section=pane-new-staff') }}">
                                                                    <button class="btn-blue-dmi mt-2" data-toggle="tooltip" title="Ir a nuevos ingresos" ><i class="fas fa-share" style="padding: 2px;"></i></button>
                                                                </a>
            
                                                            </div>
                                                            <div class="col-md-7 col-sm-12 text-center pt-2">
                                                                <div class="birth-name mb-3">{{ $publication->full_name }}</div>
                                                                <div class="birth-name">{{$publication->position_company_full}}</div>
                                                                <div class="birth-job">Posición</div>
                                                                <div class="birth-name mt-2">{{$publication->deparment}}</div>
                                                                <div class="birth-job">Departamento</div>
                                                            </div>
                                                        </div>
                                                    @elseif ($publication->type_publication == 'anniversaries')
                                                        <div class="row justify-content-center mb-3">
                                                            <div class="col-6 sub-title-publication text-center">
                                                                Aniversario
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4 p-2 text-center">
                                                                @if($publication->photo != null)
                                                                    <img onerror="this.src='{{asset('/image/icons/user.svg')}}';" src="{{$publication->photo_src}}" class="img-fluid birth-person" style="width: 45%!important;">
                                                                @else
                                                                    <img src="{{ asset('image/icons/user.svg')}}" class="img-fluid birth-person"  style="width: 45%!important;">
                                                                @endif
                                                                <a href="{{ url('/collaborators?section=pane-anniversaries') }}">
                                                                    <button class="btn-blue-dmi mt-2" data-toggle="tooltip" title="Ir a aniversarios" ><i class="fas fa-share" style="padding: 2px;"></i></button>
                                                                </a>
                                                            </div>
                                                            <div class="col-md-8 col-sm-12 text-center pt-2">
                                                                <div class="birth-name mb-2">{{ $publication->full_name }}</div>
                                                                <div class="birth-name">{{$publication->position_company_full}}</div>
                                                                <div class="birth-job">{{$publication->deparment}}</div>
                                                                @php
                                                                    $current_year = date("Y");
                                                                    $antiquity_date = strftime("%Y", strtotime($publication->antiquity_date));
                                                                    $aniversary= $current_year - $antiquity_date;
                                                                @endphp
                                                                <img width="40px" src="{{url("image/insignias/Insignia-Aniversario-años-")}}{{$aniversary}}.png" alt="">
                                                            </div>
                                                        </div>
                                                    @elseif ($publication->type_publication == 'birthday')
                                                        <div class="row justify-content-center mb-3">
                                                            <div class="col-6 sub-title-publication text-center">
                                                                Nacimientos
                                                            </div>
                                                        </div>
                                                        <div class="row" style="background: {{($publication->sex == 'Femenino') ? '#f0d7de':'#d2e6eb'}};padding: 10px;border-radius: 15px;">
                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="title text-uppercase text-center mb-3" style="font-size:18px; color: white;font-family: 'Gotham-Bold';letter-spacing: 2px;">
                                                                    ¡Es {{ ($publication->sex=='Femenino') ? 'NIÑA':'NIÑO'}}!
                                                                </div>
                                                                <div class="date" style="font-size:12px;">
                                                                    <strong><span style="text-transform: initial;">Nacimiento:</span> {{strftime("%d %B", strtotime($publication->birth))}}</strong>
                                                                </div>
                                                                <div class="name" style="font-size:12px;">
                                                                    @if($publication->user)
                                                                        {{ $publication->user->name}} {{ $publication->user->last_name}}
                                                                    @else
                                                                        {{$publication->collaborator}}
                                                                    @endif
                                                                </div>
                                                                <div class="name justify-content-center mt-2" style="display: flex;">
                                                                    <a href="{{ url('/collaborators?section=pane-births') }}">
                                                                        <button class="btn-blue-dmi" data-toggle="tooltip" title="Ir a ascensos" style="box-shadow: 0px 0px 2px 2px;!important;background-color:{{($publication->sex == 'Femenino') ? '#f0d7de':'#d2e6eb'}}!important; font-size:12px;" >
                                                                            <i class="fas fa-share" style="padding: 2px;"></i>
                                                                        </button>
                                                                    </a>
                                                                </div>
                                                                
                                                            </div>
                                                            <div class="col-md-6 col-sm-12 image-girl-boy">
                                                                <img src="{{ asset('storage/plantilla')}}{{($publication->sex == 'Femenino') ? '/girl.png':'/boy.png'}}" class="img-fluid">
                                                            </div>
                                                        </div>
                                                    @endif                                                     
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="carousel-item active">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12 mt-1 text-center">
                                                    <img class="img-svg-40" src="{{url('/image/icons/sin_publicaciones.svg')}}" alt="">
                                                    <p class="no-user-data mt-3 mb-0"><strong style="color: white;">Sin publicaciones</strong></p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    @endif
                                    
                                </div>
                                <button class="carousel-control-prev" type="
                                button" data-bs-target="#carouselExampleControls_collaborator" data-bs-slide="prev">
                                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                  <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls_collaborator" data-bs-slide="next">
                                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                  <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    
                </div>
                <div class="col-md-4 col-sm-12 divider-3">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-9 home-card-transparency-title">
                                Beneficios
                            </div>
                        </div>
                        <div class="row home-card-transparency-body text-center" style="height: 195px;display:block;">
                            {{--  --}}
                            <div id="carouselBenefits" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                  @foreach($benefits as $key => $benefit)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <img src="{{ $benefit['photo'] }}" class="d-block w-100 img-carousel-benefit" alt="...">
                                        <div class="carousel-caption" style="padding-bottom:unset;">
                                            <a href="/beneficio_prestacion/{{ $benefit->id }}">
                                                <button class="btn-blue-dmi" >Ver</button></a>
                                        </div>
                                    </div>
                                  @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselBenefits" data-bs-slide="prev">
                                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                  <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselBenefits" data-bs-slide="next">
                                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                  <span class="visually-hidden">Next</span>
                                </button>
                              </div>
                            {{--  --}}
                        </div>
                    </div>    
                </div>
                <div class="col-md-4 col-sm-12 divider-3">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-9 home-card-transparency-title" style="background:var(--color-vida-dmi);">
                                fundación
                            </div>
                        </div>
                        <div class="row home-card-transparency-body text-center" style="height: 195px;display:table;">
                            <a href="https://educacionysalud.org/" target="_blank"><img src="{{asset('image/foundation/logo.png')}}" class="w-25" alt="..."></a>
                        </div>
                    </div>  
                </div>
            </div>

            {{-- Tickets --}}
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight-ticket" aria-labelledby="offcanvasRightLabel" style="background:#f2f4f4">
                <div class="offcanvas-header shadow">
                        <div class="col-11 text-center text-dmi">
                            <h5 id="offcanvasRightLabel"><i class="fas fa-ticket-alt me-2"></i>Tickets</h5>
                            <a href="https://grupodmi.freshservice.com/a/tickets/new" target="_blank"><i class="fas fa-plus"></i> Nuevo ticket</a>
                        </div>
                        <div class="col-1">
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                </div>
                <div id="offcanvas-body-ticket" class="offcanvas-body mb-5">
                    <div id="tickets-requested" class="mt-2">
                        <div class="row text-dmi">
                            <h5 id="offcanvasRightLabel">Solicitados</h5>
                        </div>
                        <div id="tickets-requested-list"></div>
                        <div id="tickets-requested-list-no-data" class="d-none">
                            @include("componentes_generales.noDataFound",["size"=>"30","message"=>"Sin tickets solicitados."])
                        </div>
                    </div>
                    <div id="tickets-assigned" class="mt-5 d-none">
                        <div class="row text-dmi">
                            <h5 id="offcanvasRightLabel">Asignados</h5>
                        </div>
                        <div id="tickets-assigned-list"></div>
                        <div id="tickets-assigned-list-no-data" class="d-none">
                            @include("componentes_generales.noDataFound",["size"=>"30","message"=>"Sin tickets asignados."])
                        </div>
                        
                    </div>
                    
                    
                    
                </div>
            </div>


        </div>
        @include('modales.modalImages');
        @include('modales.modalReactions')
    </div>
</div>

@endsection

@section('script_footer')

<script src="{{asset('js/dmi/modal-images_home.js')}}"></script>
<script src="{{asset('js/dmi/reactions.js')}}"></script>
<script src="{{asset('js/dmi/home.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js"></script>

<script>
    ths.communiques = @json($communiques);
    const api_key_freshservices= @json($api_key_freshservices);
    ths.threadFreshServices();
</script>



@endsection
