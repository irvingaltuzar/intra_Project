<div id="pane-new-staff" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-new-staff">
    <div class="card-header" role="tab" id="heading-new-staff">
        <a class="collapsed" data-bs-toggle="collapse" href="#collapse-new-staff" aria-expanded="true"
                aria-controls="collapse-new-staff">
            <h5 class="mb-0">
                Nuevos ingresos
            </h5>
        </a>
    </div>
    <div id="collapse-new-staff" class="collapse" data-bs-parent="#content-tabs-collaborators" role="tabpanel" aria-labelledby="heading-new-staff">
        <div class="card-body">
            <div class="content content-new-staff">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="carouselNewStaff" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
                            <div class="carousel-indicators">
                                @foreach ($months as $key => $month)
                                    <button type="button" data-bs-target="#carouselNewStaff" data-bs-slide-to="{{$key}}" class="{{(date('m')==($key+1))? 'active':''}}" aria-current="{{(date('m')==($key+1))? 'true':'false'}}">{{ $month }}</button>
                                @endforeach
                            </div>
                            <div class="carousel-inner">
                                @foreach ($months as $key => $month)
                                    <div class="carousel-item {{(date('m')==($key+1))? 'active':''}}">
                                        <div class="row justify-content-center">
                                            @if(count($new_staff[$key])>0)
                                                @foreach ($new_staff[$key] as $staff)
                                                    <div class="card-event">
                                                        <div class="content-card" style="background: url(storage/{{ ($staff->position_company_full=='DIRECTOR' ||
                                                         $staff->position_company_full=='GERENTE' || $staff->position_company_full=='CONSEJO') ? $message_new_staff['DIRECTOR']['photo'] : $message_new_staff['GENERAL']['photo'] }}) no-repeat;
                                                          background-size: 100% 100%;">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="photo">
                                                                        @if($staff->photo)
                                                                            <img src="{{$staff->photo_src}}" class="img-fluid person resize-photo">
                                                                        @else
                                                                            @if($staff->sex=='Masculino')
                                                                                <img src="{{ asset('image/icons/masculino.svg')}}" class="img-fluid person resize-photo">
                                                                            @elseif($staff->sex=='Femenino')
                                                                                <img src="{{ asset('image/icons/femenino.svg')}}" class="img-fluid person resize-photo">
                                                                            @else
                                                                                <img src="{{ asset('image/icons/general.svg')}}" class="img-fluid person resize-photo">
                                                                            @endif
                                                                        @endif
                                                                        @if(date('m')==($key+1)|| date('m')>strftime("%m",strtotime(date('d-m-Y')."- 2 days")))
                                                                            @if(date('d')==strftime("%d", strtotime($staff->antiquity_date)) ||
                                                                            (strftime("%d",strtotime($staff->antiquity_date."+ 2 days")))>=date('d') &&
                                                                            strftime("%d",strtotime(date('d-m-Y')."- 2 days"))<= strftime("%d", strtotime($staff->antiquity_date)) &&
                                                                            strftime("%d", strtotime($staff->antiquity_date))<=date('d'))
                                                                                <a role='button' data-bs-toggle="modal" data-bs-target="#messageModal" data-bs-whatever="birthday">
                                                                                    <img src="{{ asset('image/icons/comments.svg')}}" class="img-fluid comments-icon">
                                                                                </a>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                    <div class="name">
                                                                        {{$staff->name}} <br> {{$staff->last_name}}
                                                                    </div>
                                                                    <div class="job">
                                                                        {{$staff->position_company_full}} / {{$staff->deparment}}
                                                                    </div>
                                                                    <div class="development" >
                                                                        <img src="{{$url}}/storage/{{$staff->photo_location}}" class="img-fluid  w-auto h-100">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <br>
                                                                    <br>
                                                                    <br>
                                                                    <br>
                                                                    <br>
                                                                    <br>
                                                                    <div class="title text-uppercase text-start">
                                                                        ¡Bienvenida(o)!
                                                                    </div>
                                                                    <div class="date text-start">
                                                                        {{strftime("%d %B", strtotime($staff->antiquity_date))}}
                                                                    </div>
                                                                    <div class="text-card text-justify message">
                                                                        @if($staff->position_company_full=='DIRECTOR' || $staff->position_company_full=='GERENTE' || $staff->position_company_full=='CONSEJO')
                                                                            @if($message_new_staff)
                                                                                {!! $message_new_staff['DIRECTOR']['message'] !!}
                                                                            @endif
                                                                        @else
                                                                            @if($message_new_staff)
                                                                                {!! $message_new_staff['GENERAL']['message'] !!}
                                                                            @endif
                                                                        @endif
                                                                        <br>

                                                                        <div class="job">
                                                                            Reporta a:
                                                                        </div>
                                                                        <div class="name">
                                                                            {!! $staff->commanding_staff->full_name ?? 'Sin usuario' !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="empty">No se encontraron nuevos ingresos para este mes</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
