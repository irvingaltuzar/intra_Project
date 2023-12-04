<div id="pane-condolences" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-condolences">
    <div class="card-header" role="tab" id="heading-condolences">
        <a data-bs-toggle="collapse" href="#collapse-condolences" aria-expanded="true" aria-controls="collapse-condolences">
            <h5 class="mb-0">Condolencias</h5>
        </a>
    </div>
    <div id="collapse-condolences" class="collapse" data-bs-parent="#content-tabs-collaborators" role="tabpanel"
        aria-labelledby="heading-condolences">
        <div class="card-body">
            <div class="content content-condolences">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="carouselCondolence" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
                            <div class="carousel-indicators">
                                @foreach ($months as $key => $month)
                                    <button type="button" data-bs-target="#carouselCondolence" data-bs-slide-to="{{$key}}" class="{{(date('m')==($key+1))? 'active':''}}" aria-current="{{(date('m')==($key+1))? 'true':'false'}}">{{ $month }}</button>
                                @endforeach
                            </div>
                            <div class="carousel-inner">
                            @foreach ($months as $key => $month)
                                    <div class="carousel-item {{(date('m')==($key+1))? 'active':''}}">
                                        <div class="row justify-content-center">
                                        @if(count($condolences[$key])>0)
                                                @foreach ($condolences[$key] as $condolence)
                                                    <div class="card-event">
                                                        <div class="content-card">
                                                            <div class="row">
                                                                <div class="col-md-5">
                                                                    <div class="photo">
                                                                        <img src="{{ asset('storage/'.$condolence->templanteCollaborator->photo)}}" class="img-fluid">
                                                                        @if($condolence->user)
                                                                            @if(date('m')==($key+1)|| date('m')>strftime("%m",strtotime(date('d-m-Y')."- 2 days")))
                                                                                @if(date('d')==strftime("%d", strtotime($condolence->condolence_date)) ||
                                                                                (strftime("%d",strtotime($condolence->condolence_date."+ 2 days")))>=date('d') &&
                                                                                strftime("%d",strtotime(date('d-m-Y')."- 2 days"))<= strftime("%d", strtotime($condolence->condolence_date)) &&
                                                                                strftime("%d", strtotime($condolence->condolence_date))<=date('d'))
                                                                                    <a role='button' data-bs-toggle="modal" data-bs-target="#messageModal" data-bs-whatever="birthday">
                                                                                        <img src="{{ asset('image/icons/comments.svg')}}" class="img-fluid comments-icon">
                                                                                    </a>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <div class="title text-uppercase text-start">
                                                                        Descansa en paz
                                                                    </div>
                                                                    <div class="date text-start">
                                                                        {{strftime("%d %B", strtotime($condolence->condolence_date))}}
                                                                    </div>
                                                                    <div class="text-card text-center">
                                                                        <div class="text-center pt-3 pb-3">
                                                                            <img src="{{ asset('image/icons/cruz.png')}}" class="img-fluid">
                                                                        </div>
                                                                        {!! $condolence->templanteCollaborator->message !!}
                                                                        <div class="text-center pb-3">
                                                                            <img src="{{ asset('image/icons/flores.png')}}" class="img-fluid">
                                                                        </div>
                                                                        <p>Acompañamos {{$condolence->accompanies}} en estos momentos de profunda tristeza por la sensible pérdida de {{$condolence->condolence}}.</p>
                                                                        <p>Elevamos nuestras oraciones por su eterno descanso y por el consuelo para toda la familia. </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="empty">No se encontraron condolencias para este mes</div>
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
