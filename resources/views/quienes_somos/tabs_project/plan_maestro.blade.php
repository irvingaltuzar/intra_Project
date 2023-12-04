@if(array_key_exists('MASTER PLAN',$items->toArray()))
                                            <div id="pane-master" class="card tab-pane fade show " role="tabpanel" aria-labelledby="tab-master">
                                                <div class="card-header" role="tab" id="heading-master">
                                                    <a data-bs-toggle="collapse" href="#collapse-master" aria-expanded="true" aria-controls="collapse-master">
                                                        <h5 class="mb-0">
                                                            PLAN MAESTRO
                                                        </h5>
                                                    </a>
                                                </div>
                                                <div id="collapse-master" class="collapse show" data-bs-parent="#content-tabs-us" role="tabpanel"
                                                    aria-labelledby="heading-master">
                                                    <div class="card-body">
                                                        <div class="content">
                                                            <br>
                                                            <br>
                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-12 text-center">
                                                                    <img src="{{url($items['MASTER PLAN'][0]->file)}}" alt="" width="700px">
                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
