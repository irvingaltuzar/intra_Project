<div id="pane-area-notices" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-area-notices">
    <div class="card-header" role="tab" id="heading-area-notices">
        <a data-bs-toggle="collapse" href="#collapse-area-notices" aria-expanded="true" aria-controls="collapse-area-notices">
            <h5 class="mb-0">Avisos de áreas</h5>
        </a>
    </div>
    <div id="collapse-area-notices" class="collapse" data-bs-parent="#content-tabs-news" role="tabpanel"
        aria-labelledby="heading-area-notices">
        <div class="card-body">
            <div class="content">
                @if(sizeof($area_notice_list) > 0)
                    @foreach($area_notice_list as $key => $area_notice)
                        <div class="component-article">
                            <div class="header-article mb-3">
                                <h3>{{$area_notice->title}}</h3>
                                <span class="date">{{ucwords(\Carbon\Carbon::parse($area_notice->created_at)->translatedFormat('j F')) }}</span>
                                <br>
                            </div>
                            <article class="news">
                                @if($area_notice->link != null)
                                    <a href="{{$area_notice->link}}" target="_blank"><i class="fa fa-link"></i> {{$area_notice->link}}</a>
                                    <br>
                                @endif
                                @if($area_notice->video != null)
                                    <a style="cursor: pointer;color: #0d6efd;text-decoration: underline;" onclick="showVideoModal('{{$area_notice->title}}','{{$area_notice->video}}')"><i class="fas fa-video"></i> Da click aquí para visualizar el vídeo</a>
                                    <br>
                                @endif
                                <br>
                                @if(sizeof($area_notice->files) > 0)
                                    <div class="icon-files"><i class="far fa-folder-open"></i> Archivos</div>
                                    @foreach($area_notice->files as $file)
                                        <a href="{{url($file->file)}}"><i class="fas fa-paperclip"></i> {{$file->name}}</a>
                                        <br>
                                    @endforeach
                                    <br>
                                @endif
                                <img src="{{asset($area_notice->photo)}}" class="image-news">
                                <br>
                            </article>

                        </div>

                        {{-- <article class="news">
                            <img src="{{asset($area_notice->photo)}}" class="image-news">
                            <div class="information">
                                <div class="line"></div>
                                <div class="content-information">
                                    <div class="title">
                                        <h3>{{$area_notice->title}}</h3>
                                    </div>
                                    <div class="content-news">
                                        <div class="description">
                                            <p>{!!nl2br($area_notice->description) !!}</p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="logo">

                            </div>
                        </article> --}}
                    @endforeach

                    <div id="data_area_notices" class="container-checkbox form-check">
                        <a  id="a_link_area_notices" class="btn btn-link btn-see-more {{$area_notice_list->nextPageUrl() == null ? 'd-none' : ''}}"
                            data-next_page_url="{{$area_notice_list->nextPageUrl()}}" data-module="area_notices">
                            <label class="form-check-label cursor-hand" for="checkbox-1">
                                Leer más <i class="far fa-angle-double-right"></i>
                            </label>
                        </a>
                    </div>
                    <div id="loading_area_notices" style="display:none;">
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
