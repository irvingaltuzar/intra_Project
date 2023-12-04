@if(array_key_exists('GALERIA',$items->toArray()))
                                            <div id="pane-galeria" class="card tab-pane fade show " role="tabpanel" aria-labelledby="tab-galeria">
                                                <div class="card-header" role="tab" id="heading-galeria">
                                                    <a data-bs-toggle="collapse" href="#collapse-galeria" aria-expanded="true" aria-controls="collapse-galeria">
                                                        <h5 class="mb-0">
                                                            GALERÍA
                                                        </h5>
                                                    </a>
                                                </div>
                                                <div id="collapse-galeria" class="collapse show" data-bs-parent="#content-tabs-us" role="tabpanel"
                                                    aria-labelledby="heading-galeria">
                                                    <div class="card-body">
                                                        <div class="content">
                                                            <br>
                                                            <br>
                                                            @php
                                                                $gallery = scandir($items["GALERIA"][0]['description']);
                                                                unset($gallery[0]);
                                                                unset($gallery[1]);
                                                            @endphp
                                                            <div class="docs-galley mb-3">
                                                                <ul class="docs-pictures clearfix">
                                                                    @foreach($gallery as $img)
                                                                        <li><img class="thumbnails-viewerjs" data-original="{{url($items["GALERIA"][0]['description'])}}/{{$img}}" src="{{url($items["GALERIA"][0]['description'])}}/{{$img}}" alt="Cuo Na Lake"></li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
