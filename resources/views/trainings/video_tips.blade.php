<div id="pane-video_tips" class="card tab-pane fade show" role="tabpanel" aria-labelledby="tab-video_tips">
    <div class="card-header" role="tab" id="heading-video_tips">
        <a data-bs-toggle="collapse" href="#collapse-video_tips" aria-expanded="true" aria-controls="collapse-video_tips">
            <h5 class="mb-0">
                VIDEO TIPS
            </h5>
        </a>
    </div>
    <div id="collapse-video_tips" class="collapse show" data-bs-parent="#content-tabs-us" role="tabpanel"
        aria-labelledby="heading-video_tips">
        <div class="card-body">
            <div class="container-video_tips-training">
                <div id="video_tips-training" class="video_tips-events">

                    <div class="row pt-5 m-auto">
                        @foreach ($video_tips as $item)
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
