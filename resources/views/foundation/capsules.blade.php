<div id="pane-information-capsules" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-information-capsules">
    <div class="card-header" role="tab" id="heading-information-capsules">
        <a data-bs-toggle="collapse" href="#collapse-information-capsules" aria-expanded="true" aria-controls="collapse-information-capsules">
            <h5 class="mb-0">
                Cápsulas de información
            </h5>
        </a>
    </div>
    <div id="collapse-information-capsules" class="collapse" data-bs-parent="#content-tabs-foundation" role="tabpanel"
        aria-labelledby="heading-information-capsules">
        <div class="card-body">
            <div class="content">
                @if(sizeof($foundation_capsule_list) > 0)
                    @foreach($foundation_capsule_list as $key => $foundation_capsule)
                        <div class="component-article">
                            <div class="header-article mb-3">
                                <h3>{{$foundation_capsule->title}}</h3>
                                <span class="date">{{ucwords(\Carbon\Carbon::parse($foundation_capsule->created_at)->translatedFormat('j F')) }}</span>
                                <br>
                            </div>
                            <article class="news">
                                @if($foundation_capsule->link != null)
                                    <a href="{{$foundation_capsule->link}}" target="_blank"><i class="fa fa-link"></i> {{$foundation_capsule->link}}</a>
                                    <br>
                                @endif
                                @if($foundation_capsule->video != null)
                                    <a style="cursor: pointer;color: #0d6efd;text-decoration: underline;" onclick="showVideoModal('{{$foundation_capsule->title}}','{{$foundation_capsule->video}}')"><i class="fas fa-video"></i> Da click aquí para visualizar el vídeo</a>
                                    <br>
                                @endif
                                <br>
                                @if(sizeof($foundation_capsule->files) > 0)
                                    <div class="icon-files"><i class="far fa-folder-open"></i> Archivos</div>
                                    @foreach($foundation_capsule->files as $file)
                                        <a href="{{url($file->file)}}"><i class="fas fa-paperclip"></i> {{$file->name}}</a>
                                        <br>
                                    @endforeach
                                    <br>
                                @endif
                                <img src="{{asset($foundation_capsule->photo)}}" class="image-news">

                            </article>
                        </div>
                    @endforeach

                    <div id="data_foundation_capsules" class="container-checkbox form-check">
                        <a  id="a_link_foundation_capsules" class="btn btn-link btn-see-more {{$foundation_capsule_list->nextPageUrl() == null ? 'd-none' : ''}}"
                            data-next_page_url="{{$foundation_capsule_list->nextPageUrl()}}" data-module="foundation_capsules">
                            <label class="form-check-label cursor-hand" for="checkbox-1">
                                Leer más <i class="far fa-angle-double-right"></i>
                            </label>
                        </a>
                    </div>
                    <div id="loading_foundation_capsules" style="display:none;">
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

