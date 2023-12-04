<div id="pane-induccion_basica" class="card tab-pane fade show" role="tabpanel" aria-labelledby="tab-induccion_basica">
    <div class="card-header" role="tab" id="heading-induccion_basica">
        <a data-bs-toggle="collapse" href="#collapse-induccion_basica" aria-expanded="true" aria-controls="collapse-induccion_basica">
            <h5 class="mb-0">
                INDUCCIÓN INTELISIS
            </h5>
        </a>
    </div>
    <div id="collapse-induccion_basica" class="collapse show" data-bs-parent="#content-tabs-us" role="tabpanel"
        aria-labelledby="heading-induccion_basica">
        <div class="card-body">
            <div class="container-induccion_basica-training">
                <div id="induccion_basica-training" class="induccion_basica-events">

                    <div class="row pt-5 m-auto">
                        @foreach ($induccion_basica as $item)
                            <div class="col-md-4 col-lg-4 pb-3">
                                <div class="card card-custom bg-white border-white border-0">
                                    <div class="card-custom-img">
                                        <video class="w-100 border public-video"  src="http://192.168.3.170:8000/storage/Publico/{{$item['archivo']}}" alt="">
                                    </div>
                                    <div class="card-body m-4" style="overflow-y: auto">
                                        <h5 class="card-title"><strong>{{$item['titulo']}}</strong></h5>
                                    </div>
                                    <div class="card-footer text-center" style="background: inherit; border-color: inherit;">
                                        <a target="_black" onclick="showVideo({{$item['documentodId']}})" class="special-buttom">Ver</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
