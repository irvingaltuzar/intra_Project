@if(array_key_exists('CARACTERISTICAS',$items->toArray()))
    <div id="pane-caracteristicas" class="card tab-pane fade show" role="tabpanel" aria-labelledby="tab-caracteristicas">
        <div class="card-header" role="tab" id="heading-caracteristicas">
            <a data-bs-toggle="collapse" href="#collapse-caracteristicas" aria-expanded="true" aria-controls="collapse-caracteristicas">
                <h5 class="mb-0">
                    CARACTERÍSTICAS
                </h5>
            </a>
        </div>
        <div id="collapse-caracteristicas" class="collapse show" data-bs-parent="#content-tabs-us" role="tabpanel"
            aria-labelledby="heading-caracteristicas">
            <div class="card-body">
                <div class="content">
                    <br>
                    <br>
                    <div class="row">
                        @foreach($items['CARACTERISTICAS'] as $item)
                            <div class="col-sm-12 col-md-6">
                                <p><i class="fas fa-circle text-blue-dmi"></i> {!!$item->description!!}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endif
