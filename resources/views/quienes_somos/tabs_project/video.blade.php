@if(array_key_exists('VIDEO',$items->toArray()))
                                            <div id="pane-video" class="card tab-pane fade show " role="tabpanel" aria-labelledby="tab-video">
                                                <div class="card-header" role="tab" id="heading-video">
                                                    <a data-bs-toggle="collapse" href="#collapse-video" aria-expanded="true" aria-controls="collapse-video">
                                                        <h5 class="mb-0">
                                                            GALERÍA
                                                        </h5>
                                                    </a>
                                                </div>
                                                <div id="collapse-video" class="collapse show" data-bs-parent="#content-tabs-us" role="tabpanel"
                                                    aria-labelledby="heading-video">
                                                    <div class="card-body">
                                                        <div class="content">
                                                            <br>
                                                            <br>
                                                            <div class="row">
                                                                <div class="text-center pe-1">
                                                                    <video class="w-75 border "  controls="controls" autoplay="0" id="project_video">
                                                                        <source src="{{url($items['VIDEO'][0]->description)}}" type="video/mp4">
                                                                        Vídeo no es soportado...
                                                                    </video>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
