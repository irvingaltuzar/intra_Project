<div id="pane-surveys" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-surveys">
    <div class="card-header" role="tab" id="heading-surveys">
        <a data-bs-toggle="collapse" href="#collapse-surveys" aria-expanded="true" aria-controls="collapse-surveys">
            <h5 class="mb-0">
                Encuestas
            </h5>
        </a>
    </div>
    <div id="collapse-surveys" class="collapse" data-bs-parent="#content-tabs-news" role="tabpanel"
        aria-labelledby="heading-surveys">
        <div class="card-body">
            <div class="content">
                {{-- <div class="row pt-5 m-auto">
                  @foreach ($poll_list as $poll)
                    <div class="col-md-6 col-lg-4 pb-3">
                        <div class="card card-custom bg-white border-white border-0">
                            <div onclick="singelPoll()" class="card-custom-img" style="background-image: url({{asset(url($poll->photo))}});"></div>
                            <div class="card-body m-2" style="overflow-y: auto">
                                <h5 class="card-title"><strong>{{$poll->title}}</strong></h5>
                                <p class="card-text card-custom-text">{{$poll->description}}</p>
                            </div>
                            <div class="card-footer text-center" style="background: inherit; border-color: inherit;">
                                <a href="{{$poll->link}}" target="_black" class="special-buttom">Contestar</a>
                            </div>
                        </div>
                    </div>

                  @endforeach
                    <div id="data_poll" class="container-checkbox form-check">
                        <a  id="a_link_poll" class="btn btn-link btn-see-more {{$poll_list->nextPageUrl() == null ? 'd-none' : ''}}"
                            data-next_page_url="{{$poll_list->nextPageUrl()}}" data-module="poll">
                            <label class="form-check-label cursor-hand" for="checkbox-1">
                                Leer más <i class="far fa-angle-double-right"></i>
                            </label>
                        </a>
                    </div>
                    <div id="loading_poll" style="display:none;">
                        <div class="row justify-content-center">
                            <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                        </div>
                    </div>

                </div> --}}

                @if(sizeof($poll_list) > 0)
                    @foreach($poll_list as $key => $poll)
                        <div class="component-article">
                            <div class="header-article">
                                <h3>{{$poll->title}}</h3>
                                <span class="date">{{ucwords(\Carbon\Carbon::parse($poll->created_at)->translatedFormat('j F')) }}</span>
                            </div>
                            <article class="news">
                                <img src="{{asset($poll->photo)}}" class="image-news">
                                @if($poll->link != null)
                                    <br>
                                    <br>
                                    <div class="icon-files"><i class="far fa-folder-open"></i> Link</div>
                                    <a href="{{$poll->link}}">{{$poll->link}}</a>
                                @endif

                            </article>

                        </div>

                    @endforeach
                    <div id="data_poll" class="container-checkbox form-check">
                        <a  id="a_link_poll" class="btn btn-link btn-see-more {{$poll_list->nextPageUrl() == null ? 'd-none' : ''}}"
                            data-next_page_url="{{$poll_list->nextPageUrl()}}" data-module="poll">
                            <label class="form-check-label cursor-hand" for="checkbox-1">
                                Leer más <i class="far fa-angle-double-right"></i>
                            </label>
                        </a>
                    </div>
                    <div id="loading_poll" style="display:none;">
                        <div class="row justify-content-center">
                            <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                        </div>
                    </div>

                @else
                    @include("componentes_generales.noDataFound",["size"=>"30","message"=>"No se encontró ningún registro."])
                @endif



            </div>

            {{--  --}}

        </div>
    </div>
</div>
