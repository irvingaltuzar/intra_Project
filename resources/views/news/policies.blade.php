<div id="pane-policies" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-policies">
    <div class="card-header" role="tab" id="heading-policies">
        <a data-bs-toggle="collapse" href="#collapse-policies" aria-expanded="true" aria-controls="collapse-policies">
            <h5 class="mb-0">Políticas</h5>
        </a>
    </div>
    <div id="collapse-policies" class="collapse" data-bs-parent="#content-tabs-news" role="tabpanel"
        aria-labelledby="heading-policies">
        <div class="card-body">
            <div class="content">
                @if(sizeof($policy_list) > 0)
                    @foreach($policy_list as $key => $policy)
                        <div class="component-article">
                            <div class="header-article">
                                <h3>{{$policy->title}}</h3>
                                <span class="date">{{ucwords(\Carbon\Carbon::parse($policy->created_at)->translatedFormat('j F')) }}</span>
                            </div>
                            <article class="news">
                                <img src="{{asset($policy->photo)}}" class="image-news">
                                @if(sizeof($policy->files) > 0)
                                    <br>
                                    <br>
                                    <div class="icon-files"><i class="far fa-folder-open"></i> Archivos</div>
                                    @foreach($policy->files as $file)
                                        <a href="{{url($file->file)}}"><i class="fas fa-paperclip"></i> {{$file->name}}</a>
                                        <br>
                                    @endforeach
                                @endif

                            </article>

                        </div>
                    @endforeach

                    <div id="data_policies" class="container-checkbox form-check">
                        <a  id="a_link_policies" class="btn btn-link btn-see-more {{$policy_list->nextPageUrl() == null ? 'd-none' : ''}}"
                            data-next_page_url="{{$policy_list->nextPageUrl()}}" data-module="policies">
                            <label class="form-check-label cursor-hand" for="checkbox-1">
                                Leer más <i class="far fa-angle-double-right"></i>
                            </label>
                        </a>
                    </div>
                    <div id="loading_policies" style="display:none;">
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
