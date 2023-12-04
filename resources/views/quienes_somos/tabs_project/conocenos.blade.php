@if(array_key_exists('CONÓCENOS',$items->toArray()))
                                            <div id="pane-conocenos" class="card tab-pane fade show active" role="tabpanel" aria-labelledby="tab-conocenos">
                                                <div class="card-header" role="tab" id="heading-conocenos">
                                                    <a data-bs-toggle="collapse" href="#collapse-conocenos" aria-expanded="true" aria-controls="collapse-conocenos">
                                                        <h5 class="mb-0">
                                                            CONÓCENOS
                                                        </h5>
                                                    </a>
                                                </div>
                                                <div id="collapse-conocenos" class="collapse show" data-bs-parent="#content-tabs-us" role="tabpanel"
                                                    aria-labelledby="heading-conocenos">
                                                    <div class="card-body">
                                                        <div class="content">
                                                            <br>
                                                            <br>
                                                            @foreach($items['CONÓCENOS'] as $item)
                                                                <p>{!!$item->description!!}</p>
                                                                <br>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        @endif
