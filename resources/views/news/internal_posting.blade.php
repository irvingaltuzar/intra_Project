<div id="pane-internal-posting" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-internal-posting">
    <div class="card-header" role="tab" id="heading-internal-posting">
        <a class="collapsed" data-bs-toggle="collapse" href="#collapse-internal-posting" aria-expanded="true"
                aria-controls="collapse-internal-posting">
            <h5 class="mb-0">
                Posteo interno
            </h5>
        </a>
    </div>
    <div id="collapse-internal-posting" class="collapse" data-bs-parent="#content-tabs-news" role="tabpanel"
        aria-labelledby="heading-internal-posting">
        <div class="card-body">
            <div class="content">
                @if(sizeof($internal_posting_list) > 0)
                    @foreach($internal_posting_list as $key => $posting)
                        <div class="component-article">
                            <div class="header-article">
                                <h3>{{$posting->title}}</h3>
                                <span class="date">{{ucwords(\Carbon\Carbon::parse($posting->created_at)->translatedFormat('j F')) }}</span>
                            </div>
                            <article class="news">
                                <img src="{{asset($posting->photo)}}" class="image-news">
                                @if(sizeof($posting->files) > 0)
                                    <br>
                                    <br>
                                    <div class="icon-files"><i class="far fa-folder-open"></i> Archivos</div>
                                    @foreach($posting->files as $file)
                                        <a href="{{url($file->file)}}"><i class="fas fa-paperclip"></i> {{$file->name}}</a>
                                        <br>
                                    @endforeach
                                @endif

                            </article>

                        </div>
                    @endforeach

                    <div id="data_internal_posting" class="container-checkbox form-check">
                        <a  id="a_link_internal_posting" class="btn btn-link btn-see-more {{$internal_posting_list->nextPageUrl() == null ? 'd-none' : ''}}"
                            data-next_page_url="{{$internal_posting_list->nextPageUrl()}}" data-module="internal_posting">
                            <label class="form-check-label cursor-hand" for="checkbox-1">
                                Leer más <i class="far fa-angle-double-right"></i>
                            </label>
                        </a>
                    </div>
                    <div id="loading_internal_posting" style="display:none;">
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
