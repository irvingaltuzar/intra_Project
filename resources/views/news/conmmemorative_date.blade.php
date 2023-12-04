<div id="pane-commemorative-dates" class="card tab-pane fade show active" role="tabpanel" aria-labelledby="tab-commemorative-dates">
    <div class="card-header" role="tab" id="heading-commemorative-dates">
        <a data-bs-toggle="collapse" href="#collapse-commemorative-dates" aria-expanded="false" aria-controls="collapse-commemorative-dates">
            <h5 class="mb-0">
                Fechas conmemorativas
            </h5>
        </a>
    </div>
    <div id="collapse-commemorative-dates" class="collapse show" data-bs-parent="#content-tabs-news" role="tabpanel"
        aria-labelledby="heading-commemorative-dates">
        <div class="card-body">
            <div class="content">
                @if(sizeof($conmmemorative_list) > 0)
                    @foreach($conmmemorative_list as $key => $conmmemorative)
                        <div class="component-article">
                            <div class="header-article">
                                <h3>{{$conmmemorative->title}}</h3>
                                <span class="date">{{ucwords($conmmemorative->created_at->translatedFormat('j F')) }}</span>
                            </div>
                            <article class="news">
                                <img src="{{asset($conmmemorative->photo)}}" class="image-news">
                            </article>
                        </div>

                    @endforeach
                    <div id="data_conmmemorative_date" class="container-checkbox form-check">
                        <a  id="a_link_conmmemorative_date" class="btn btn-link btn-see-more {{$conmmemorative_list->nextPageUrl() == null ? 'd-none' : ''}}"
                            data-next_page_url="{{$conmmemorative_list->nextPageUrl()}}" data-module="conmmemorative_date">
                            <label class="form-check-label cursor-hand" for="checkbox-1">
                                Leer más <i class="far fa-angle-double-right"></i>
                            </label>
                        </a>
                    </div>
                    <div id="loading_conmmemorative_date" style="display:none;">
                        <div class="row justify-content-center">
                            <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                        </div>
                    </div>

                @else
                    @include("componentes_generales.noDataFound",["size"=>"30","message"=>"No se encontró ningún registro."])
                @endif

            </div>
        </div>
    </div>
</div>
