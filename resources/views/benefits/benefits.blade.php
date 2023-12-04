<div id="pane-benefits" class="card tab-pane fade show active" role="tabpanel" aria-labelledby="tab-benefits">
    <div class="card-header" role="tab" id="heading-benefits">
        <a data-bs-toggle="collapse" href="#collapse-benefits" aria-expanded="false" aria-controls="collapse-benefits">
            <h5 class="mb-0">
                Beneficios
            </h5>
        </a>
    </div>
    <div id="collapse-benefits" class="collapse show" data-bs-parent="#content-tabs-blog" role="tabpanel"
        aria-labelledby="heading-benefits">
        <div class="card-body">
            <div class="content content-benefits">
                @foreach($benefits as $benefit)
                    <article>
                        <div class="row content-article">
                            <div class="col-md-2 justify-content-center align-self-center">
                                <div class="photo">
                                    <img src="{{url($benefit->photo)}}" class="img-fluid">
                                </div>
                            </div>
                            <div class="col-md-10 justify-content-center align-self-center">
                                <h3 class="title">{{$benefit->title}}</h3>
                                <div class="description">
                                    {{$benefit->subtitle}}
                                </div>
                                <a  href="{{url('beneficio_prestacion/'.$benefit->id)}}" class="read-more">Conocer más...</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</div>
