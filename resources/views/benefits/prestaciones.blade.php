<div id="pane-benefits-2" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-benefits-2">
    <div class="card-header" role="tab" id="heading-benefits-2">
        <a class="collapsed" data-bs-toggle="collapse" href="#collapse-benefits-2" aria-expanded="true"
                aria-controls="collapse-benefits-2">
            <h5 class="mb-0">
                Prestaciones
            </h5>
        </a>
    </div>
    <div id="collapse-benefits-2" class="collapse" data-bs-parent="#content-tabs-blog" role="tabpanel"
        aria-labelledby="heading-benefits-2">
        <div class="card-body">
            <div class="content content-benefits-2">
                @foreach($prestaciones as $prestacion)
                    <article>
                        <div class="row content-article">
                            <div class="col-md-2 justify-content-center align-self-center">
                                <div class="photo">
                                    <img src="{{url($prestacion->photo)}}" class="img-fluid">
                                </div>
                            </div>
                            <div class="col-md-10 justify-content-center align-self-center">
                                <h3 class="title">{{$prestacion->title}}</h3>
                                <div class="description">
                                    {{$prestacion->subtitle}}
                                </div>
                                <a href="{{url('beneficio_prestacion/'.$prestacion->id)}}" class="read-more">Leer más...</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</div>
