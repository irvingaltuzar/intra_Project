<div id="pane-anniversaries" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-anniversaries">
    <div class="card-header" role="tab" id="heading-anniversaries">
        <a class="collapsed" data-bs-toggle="collapse" href="#collapse-anniversaries" aria-expanded="true"
                aria-controls="collapse-anniversaries">
            <h5 class="mb-0">
                Aniversarios
            </h5>
        </a>
    </div>
    <div id="collapse-anniversaries" class="collapse" data-bs-parent="#content-tabs-collaborators" role="tabpanel" aria-labelledby="heading-anniversaries">
        <div class="card-body">
            <div class="content content-anniversaries">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="carouselAnniversaries" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
                            <div class="carousel-indicators">
                                @foreach ($months as $key => $month)
                                    <button type="button" data-bs-target="#carouselAnniversaries" data-bs-slide-to="{{$key}}" class="{{(date('m')==($key+1))? 'active':''}}" aria-current="{{(date('m')==($key+1))? 'true':'false'}}">{{ $month }}</button>
                                @endforeach
                            </div>
                            <div class="carousel-inner">
                                @foreach ($months as $key => $month)
                                    <div class="carousel-item {{(date('m')==($key+1))? 'active':'d-none'}}">
                                        <div class="row justify-content-center">
                                            @if(count($anniversaries[$key])>0)
                                                @foreach ($anniversaries[$key] as $anniversary)
                                                    @if($anniversary->usuario != "eduardo")
                                                        <div class="card-event">
                                                            <div class="content-card" style="background: url(storage/{{ ($anniversary->position_company_full=='DIRECTOR' ||
                                                                $anniversary->position_company_full=='GERENTE' || $anniversary->position_company_full=='CONSEJO') ? $message_anniversaries['DIRECTOR']['photo'] : $message_anniversaries['GENERAL']['photo'] }}) no-repeat;
                                                                background-size: 100% 100%;">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="photo">
                                                                            @if($anniversary->photo)
                                                                                <img src="{{$anniversary->photo_src}}" class="img-fluid person resize-photo">
                                                                            @else
                                                                                @if($anniversary->sex=='Masculino')
                                                                                    <img src="{{ asset('image/icons/masculino.svg')}}" class="img-fluid person resize-photo">
                                                                                @elseif($anniversary->sex=='Femenino')
                                                                                    <img src="{{ asset('image/icons/femenino.svg')}}" class="img-fluid person resize-photo">
                                                                                @else
                                                                                    @if($anniversary->sex=='Masculino')
                                                                                        <img src="{{ asset('image/icons/masculino.svg')}}" class="img-fluid person resize-photo">
                                                                                    @elseif($anniversary->sex=='Femenino')
                                                                                        <img src="{{ asset('image/icons/femenino.svg')}}" class="img-fluid person resize-photo">
                                                                                    @else
                                                                                        <img src="{{ asset('image/icons/general.svg')}}" class="img-fluid person resize-photo">
                                                                                    @endif
                                                                                @endif
                                                                                @if(date('m')==($key+1)|| date('m')>strftime("%m",strtotime(date('d-m-Y')."- 2 days")))
                                                                                    @if(date('d')==strftime("%d", strtotime($anniversary->antiquity_date)) ||
                                                                                        (strftime("%d",strtotime($anniversary->antiquity_date."+ 2 days")))>=date('d') &&
                                                                                        strftime("%d",strtotime(date('d-m-Y')."- 2 days"))<= strftime("%d", strtotime($anniversary->antiquity_date)) &&
                                                                                        strftime("%d", strtotime($anniversary->antiquity_date))<=date('d'))
                                                                                        <a role='button' data-bs-toggle="modal" data-bs-target="#messageModal" data-bs-whatever="birthday">
                                                                                            <img src="{{ asset('image/icons/comments.svg')}}" class="img-fluid comments-icon">
                                                                                        </a>
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                        <div class="name">
                                                                            {{$anniversary->name}} <br> {{$anniversary->last_name}}
                                                                        </div>
                                                                        <div class="job">
                                                                            {{$anniversary->position_company_full}} / {{$anniversary->deparment}}
                                                                        </div>
                                                                        <div class="development" >
                                                                            <img src="{{$url}}/storage/{{$anniversary->photo_location}}" class="img-fluid" style="width:70%!important;">
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
                                                                            ¡FELIZ ANIVERSARIO!
                                                                        </div>
                                                                        <div class="date text-start">
                                                                            {{strftime("%d %B", strtotime($anniversary->antiquity_date))}}
                                                                        </div>
                                                                        <div class="text-card text-justify message">
                                                                            @if($anniversary->position_company_full=='DIRECTOR' || $anniversary->position_company_full=='GERENTE' || $anniversary->position_company_full=='CONSEJO')
                                                                                @if($message_anniversaries)
                                                                                    {!! $message_anniversaries['DIRECTOR']['message'] !!}
                                                                                @endif
                                                                            @else
                                                                                @if($message_anniversaries)
                                                                                    {!! $message_anniversaries['GENERAL']['message'] !!}
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                        @php
                                                                            $current_year = date("Y");
                                                                            $antiquity_date = strftime("%Y", strtotime($anniversary->antiquity_date));
                                                                            $aniversary= $current_year - $antiquity_date;
                                                                        @endphp
                                                                        <img width="80px" src="{{url("image/insignias/Insignia-Aniversario-años-")}}{{$aniversary}}.png" alt="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <div class="empty">No se encontraron aniversarios para este mes</div>
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
