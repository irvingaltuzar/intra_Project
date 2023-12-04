<div id="pane-promotions" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-promotions">
    <div class="card-header" role="tab" id="heading-promotions">
        <a class="collapsed" data-bs-toggle="collapse" href="#collapse-promotions" aria-expanded="true"
                aria-controls="collapse-promotions">
            <h5 class="mb-0">
                Ascensos
            </h5>
        </a>
    </div>
    <div id="collapse-promotions" class="collapse" data-bs-parent="#content-tabs-collaborators" role="tabpanel" aria-labelledby="heading-promotions">
        <div class="card-body">
            <div class="content content-promotions">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="carouselPromotions" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
                            <div class="carousel-indicators">
                                @foreach ($months as $key => $month)
                                    <button type="button" data-bs-target="#carouselPromotions" data-bs-slide-to="{{$key}}" class="{{(date('m')==($key+1))? 'active':''}}" aria-current="{{(date('m')==($key+1))? 'true':'false'}}">{{ $month }}</button>
                                @endforeach
                            </div>
                            <div class="carousel-inner">
                            @foreach ($months as $key => $month)
                                    <div class="carousel-item {{(date('m')==($key+1))? 'active':''}}">
                                        <div class="row justify-content-center">
                                            @if(count($promotions[$key])>0)
                                                @foreach ($promotions[$key] as $promotion)
                                                    <div class="card-event">
                                                        <div class="content-card">
                                                            <div class="photo">
                                                                @if($promotion->photo)
                                                                    <img onerror="this.src='{{asset('/image/icons/user.svg')}}';" src="{{ url($promotion->photo)}}" class="img-fluid person">
                                                                @else
                                                                    @if($promotion->sex=='Masculino')
                                                                        <img src="{{ asset('image/icons/masculino.svg')}}" class="img-fluid person">
                                                                    @elseif($promotion->sex=='Femenino')
                                                                        <img src="{{ asset('image/icons/femenino.svg')}}" class="img-fluid person">
                                                                    @else
                                                                        <img src="{{ asset('image/icons/general.svg')}}" class="img-fluid person">
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            @if (isset($promotion->user_name))
                                                                <div class="text-card text-center job mb-4">{{$promotion->user_name}}</div>
                                                            @endif


                                                            <span class="text-center job">Nueva posición</span>
                                                            <div class="text-card text-center name">
                                                                {!! $promotion->new_position_company !!}
                                                            </div>
                                                            <br> {{--  --}}
                                                            <br> {{--  --}}
                                                            <span class="text-center job">Reporta a</span>
                                                            <div class="text-card text-center name">
                                                                {!! $promotion->user_top_name !!}
                                                            </div>
                                                            <br>
                                                            <br>
                                                            <div class="text-card text-center">
                                                                <strong>{!! nl2br($promotion->message) !!}</strong>
                                                            </div>
                                                            <br>
                                                            <div class="date">
                                                                {{strftime("%d %B", strtotime($promotion->created_at))}}
                                                            </div>
                                                            <div class="development">
                                                                @if (isset($promotion->user->locations->photo))
                                                                    <img src="{{$url}}/storage/{{$promotion->user->locations->photo}}" class="img-fluid w-50 h-auto">
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="empty">No se encontraron promociones para este mes</div>
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
