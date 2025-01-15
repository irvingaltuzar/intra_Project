<div id="pane-birthday" class="card tab-pane fade show active" role="tabpanel" aria-labelledby="tab-birthday">
    <div class="card-header" role="tab" id="heading-birthday">
        <a data-bs-toggle="collapse" href="#collapse-birthday" aria-expanded="false" aria-controls="collapse-birthday">
            <h5 class="mb-0">
                Cumpleaños
            </h5>
        </a>
    </div>
    <div id="collapse-birthday" class="collapse show" data-bs-parent="#content-tabs-collaborators" role="tabpanel"
        aria-labelledby="heading-birthday">
        <div class="card-body">
            <div class="content content-birthday">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="carouselbirthday" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
                            <div class="carousel-indicators">
                                @foreach ($months as $key => $month)
                                    <button type="button" data-bs-target="#carouselbirthday" data-bs-slide-to="{{$key}}" class="{{(date('m')==($key+1))? 'active':''}}" aria-current="{{(date('m')==($key+1))? 'true':'false'}}">{{ $month }}</button>
                                @endforeach
                            </div>
                            <div class="carousel-inner">
                                @foreach ($months as $key => $month)
                                    <div class="carousel-item {{(date('m')==($key+1))? 'active':''}}">
                                        <div class="row justify-content-center">
                                            @if(count($birthdays[$key])>0)
                                                @foreach ($birthdays[$key] as $birthday)
                                                    <div class="card-event" id="link_{{str_replace('.','_',$birthday->usuarioId)}}">
                                                        <div class="content-card">
                                                            <div class="photo">
                                                                @if($birthday->photo)
                                                                    <img onerror="this.src='{{asset('/image/icons/user.svg')}}';" src="{{$birthday->photo_src}}" class="img-fluid person">
                                                                @else
                                                                    @if($birthday->sex=='Masculino')
                                                                        <img src="{{ asset('image/icons/masculino.svg')}}" class="img-fluid person">
                                                                    @elseif($birthday->sex=='Femenino')
                                                                        <img src="{{ asset('image/icons/femenino.svg')}}" class="img-fluid person">
                                                                    @else
                                                                        <img src="{{ asset('image/icons/general.svg')}}" class="img-fluid person">
                                                                    @endif
                                                                @endif

                                                                @php
                                                                    //Se incrementan 3 días a la fecha actual para que puedan hacer felicitaciones adelantadas
                                                                    $start_date_current = date('Y-m-d',strtotime(date('d-m-Y').'-5 days'));
                                                                    $end_date_current = date('Y-m-d',strtotime(date('d-m-Y').'3 days'));
                                                                    $aux_birth = date('Y').'-'.strftime("%m", strtotime($birthday->birth)).'-'.strftime("%d", strtotime($birthday->birth))
                                                                @endphp
                                                                {{-- @if((date('d') == strftime("%d", strtotime($birthday->birth))) ||
                                                                    (strftime("%d",strtotime($birthday->birth."+ 5 days"))) >= date('d') &&
                                                                    strftime("%d",strtotime(date('d-m-Y')."- 5 days")) <= strftime("%d", strtotime($birthday->birth)) &&
                                                                    strftime("%d", strtotime($birthday->birth)) <= $new_date_current) --}}
                                                                
                                                            </div>
                                                            <div class="d-inline-flex mb-2">
                                                                @php
                                                                $publication_birthday = null;
                                                                    if($birthday->publication_birthday != null){
                                                                        $publication_birthday = $birthday->publication_birthday->id;
                                                                    }
                                                                @endphp
                                                                
                                                                {{-- <div class="d-flex" role='button' data-bs-toggle="tooltip" data-bs-placement="bottom" title="Felicitaciones">
                                                                    <img src="{{ asset('image/icons/cake.svg')}}" class="img-fluid comments-icon view-reactions" data-usuario="{{str_replace('.','_',$birthday->usuario)}}" data-publication="{{$publication_birthday != null ? $publication_birthday : 0}}">
                                                                </div>
                                                                <div class="d-flex" role='button' data-bs-toggle="tooltip" data-bs-placement="bottom" title="Comentarios">
                                                                    <input type="hidden" id="birthday_{{str_replace('.','_',$birthday->usuario)}}" data-name="{{$birthday->name}} {{$birthday->last_name}}" data-birthday="{{$birthday->birth}}" data-usuario="{{str_replace('.','_',$birthday->usuario)}}" data-publication="{{$publication_birthday}}">
                                                                    <img src="{{ asset('image/icons/comments.svg')}}" class="img-fluid comments-icon add-comments" data-usuario="{{str_replace('.','_',$birthday->usuario)}}"  data-type_publication="birthday">
                                                                </div> --}}

                                                                <div class="d-flex" role='button' data-bs-toggle="tooltip" data-bs-placement="bottom" title="Felicitaciones">
                                                                    {{-- <div class="button-dmi-blue" onclick="viewReactionsModalBirthday({{$publication_birthday != null ? $publication_birthday : 0}},'cumpleaños')"> --}}
                                                                    <div class="button-dmi-blue" onclick="viewReactionsModalBirthday({{$publication_birthday != null ? $publication_birthday : 0}},'cumpleaños',{{$birthday->usuarioId}},'{{$birthday->usuario}}','{{$birthday->birth}}')">
                                                                        <i class="fas fa-birthday-cake"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex" role='button' data-bs-toggle="tooltip" data-bs-placement="bottom" title="Comentarios">
                                                                    <input type="hidden" id="birthday_{{str_replace('.','_',$birthday->usuario)}}" data-name="{{$birthday->name}} {{$birthday->last_name}}" data-birthday="{{$birthday->birth}}" data-usuario="{{str_replace('.','_',$birthday->usuario)}}" data-publication="{{$publication_birthday}}">
                                                                    <div class="button-dmi-blue add-comments" data-usuario="{{str_replace('.','_',$birthday->usuario)}}"  data-type_publication="birthday">
                                                                        <i class="far fa-comment-alt"></i>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                            <div class="name" >{{$birthday->name}} <br> {{$birthday->last_name}}</div>
                                                            <div class="job">{{$birthday->position_company_full}} / {{$birthday->deparment}} </div>
                                                            <div class="date">{{strftime("%d %B", strtotime($birthday->birth))}}</div>
                                                            <div class="development"  >
                                                                @if($birthday->photo_location)
                                                                    <img src="{{$url}}/storage/{{$birthday->photo_location}}" class="img-fluid w-50 h-auto">
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="empty">No se encontraron cumpleaños para este mes</div>
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
