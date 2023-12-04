<div id="pane-births" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-births">
    <div class="card-header" role="tab" id="heading-births">
        <a class="collapsed" data-bs-toggle="collapse" href="#collapse-births" aria-expanded="true"
                aria-controls="collapse-births">
            <h5 class="mb-0">
                Nacimientos
            </h5>
        </a>
    </div>
    <div id="collapse-births" class="collapse" data-bs-parent="#content-tabs-collaborators" role="tabpanel" aria-labelledby="heading-births">
        <div class="card-body">
            <div class="content content-births">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="carouselBirth" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
                            <div class="carousel-indicators">
                                @foreach ($months as $key => $month)
                                    <button type="button" data-bs-target="#carouselBirth" data-bs-slide-to="{{$key}}" class="{{(date('m')==($key+1))? 'active':''}}" aria-current="{{(date('m')==($key+1))? 'true':'false'}}">{{ $month }}</button>
                                @endforeach
                            </div>
                            <div class="carousel-inner">
                            @foreach ($months as $key => $month)
                                    <div class="carousel-item {{(date('m')==($key+1))? 'active':''}}">
                                        <div class="row justify-content-center">
                                            @if(count($birthdayCollaborators[$key])>0)
                                                @foreach ($birthdayCollaborators[$key] as $birthdayCollaborator)
                                                    <div class="card-event">
                                                        <div class="content-card " style="background: {{($birthdayCollaborator->sex == 'Femenino') ? '#f0d7de':'#d2e6eb'}}">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            {{-- 
                                                                            <div class="col-md-4">
                                                                                <div class="photo">
                                                                                    @if($birthdayCollaborator->user)
                                                                                        @if(date('m')==($key+1)|| date('m')>strftime("%m",strtotime(date('d-m-Y')."- 2 days")))
                                                                                            @if(date('d')==strftime("%d", strtotime($birthdayCollaborator->birth)) ||
                                                                                            (strftime("%d",strtotime($birthdayCollaborator->birth."+ 2 days")))>=date('d') &&
                                                                                            strftime("%d",strtotime(date('d-m-Y')."- 2 days"))<= strftime("%d", strtotime($birthdayCollaborator->birth)) &&
                                                                                            strftime("%d", strtotime($birthdayCollaborator->birth))<=date('d'))
                                                                                                <a role='button' data-bs-toggle="modal" data-bs-target="#messageModal" data-bs-whatever="birthday">
                                                                                                    <img src="{{ asset('image/icons/comments.svg')}}" class="img-fluid comments-icon">
                                                                                                </a>
                                                                                            @endif
                                                                                        @endif
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            --}}
                                                                            <div class="col-md-12 text-start">
                                                                                <div class="title text-uppercase text-center" style="font-size:31px;">
                                                                                    ¡Es {{ ($birthdayCollaborator->sex=='Femenino') ? 'NIÑA':'NIÑO'}}!
                                                                                </div>
                                                                                <div class="date" style="font-size:16px;">
                                                                                    <span style="text-transform: initial;"><br><br>Fecha de nacimiento:</span> {{strftime("%d %B", strtotime($birthdayCollaborator->birth))}}
                                                                                </div>
                                                                                <div class="text-card" style="font-size:18px;;">
                                                                                    {!! $birthdayCollaborator->message !!}
                                                                                </div>
                                                                                <br>
                                                                                <div class="name" style="font-size:16px;;">
                                                                                    @if($birthdayCollaborator->user)
                                                                                        {{ $birthdayCollaborator->user->name}} {{ $birthdayCollaborator->user->last_name}}
                                                                                    @else
                                                                                        {{$birthdayCollaborator->collaborator}}
                                                                                    @endif
                                                                                </div>
                                                                                <div class="job" style="font-size:16px;;">
                                                                                    <br>
                                                                                    @if($birthdayCollaborator->user)
                                                                                        {{$birthdayCollaborator->user->position_company_full}} / {{$birthdayCollaborator->user->deparment}}
                                                                                    @endif
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12 image-girl-boy no-padding">
                                                                            <img src="{{ asset('storage/plantilla')}}{{($birthdayCollaborator->sex == 'Femenino') ? '/girl.jpg':'/boy.jpg'}}" class="img-fluid">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="development text-center" >
                                                                                @if($birthdayCollaborator->user)
                                                                                    <img src="{{asset($url.'/storage/'.$birthdayCollaborator->user->locations->photo)}}" class="img-fluid w-auto h-100">
                                                                                @endif
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="empty">No se encontraron nacimientos para este mes</div>
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
