@extends('layouts.app')
@section('title')
    <title>Quiénes somos | Intranet DMI</title>
@endsection
@section('script')
    <link href="{{ asset('css/dark-footer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/light-menu.css') }}" rel="stylesheet">
    <link href="{{ asset('css/general.css') }}" rel="stylesheet">
    <link href="{{ asset('css/us.css') }}" rel="stylesheet">
    <link href="{{ asset('css/slick.css') }}" rel="stylesheet">
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}" defer></script>
    <script src="{{ asset('js/slick.min.js') }}" defer></script>
    <script src="{{ asset('js/tabs.js') }}" defer></script>
    <script src="{{ asset('js/development.js') }}" defer></script>
    <link href="{{ asset('css/development.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container-fluid mtop-100">
        <div class="row header-development">
            <div class="col-sm-12 no-padding">
                <div class="logo">
                    <img src="{{ asset('image/development/el-molino-logo.svg')}}" class="img-fluid" width="200">
                </div>
                <img src="{{ asset('image/development/development.jpg')}}" class="img-fluid" style="visibility: hidden;">
                <img src="{{ asset('image/development/el-molino.jpg')}}" class="img-fluid image-development mtop-100">
            </div>
        </div>
    </div>
    <div class="container-fluid content-development content-section">
        <div class="pattern"></div>
            <div class="container">
                <div class="content-tabs">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="container responsive-tabs">
                                <ul class="nav nav-tabs justify-content-center" role="tablist">
                                    <li class="nav-item">
                                        <a id="tab-know-us" href="#pane-know-us" class="nav-link" data-bs-toggle="tab" role="tab">Conócenos</a>
                                    </li>
                                    <li class="nav-item">
                                        <a id="tab-gallery" href="#pane-gallery" class="nav-link active" data-bs-toggle="tab" role="tab">Galería</a>
                                    </li>
                                    <li class="nav-item">
                                        <a id="tab-characteristics" href="#pane-characteristics" class="nav-link" data-bs-toggle="tab" role="tab">Características</a>
                                    </li>
                                    <li class="nav-item">
                                        <a id="tab-master-plan" href="#pane-master-plan" class="nav-link" data-bs-toggle="tab" role="tab">Master Plan</a>
                                    </li>
                                </ul>

                                <div id="content-tabs-development" class="tab-content" role="tablist">
                                    <div id="pane-know-us" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-know-us">
                                        <div class="card-header" role="tab" id="heading-know-us">
                                            <a data-bs-toggle="collapse" href="#collapse-know-us" aria-expanded="false" aria-controls="collapse-know-us">
                                                <h5 class="mb-0">
                                                    Conócenos
                                                </h5>
                                            </a>
                                        </div>
                                        <div id="collapse-know-us" class="collapse" data-bs-parent="#content-tabs-development" role="tabpanel"
                                            aria-labelledby="heading-know-us">
                                            <div class="card-body">
                                                <div class="content">
                                                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et ac</p>
                                                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et ac</p>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>

                                    <div id="pane-gallery" class="card tab-pane fade show active" role="tabpanel" aria-labelledby="tab-gallery">
                                        <div class="card-header" role="tab" id="heading-gallery">
                                            <a class="collapsed" data-bs-toggle="collapse" href="#collapse-gallery" aria-expanded="true"
                                                    aria-controls="collapse-gallery">
                                                <h5 class="mb-0">
                                                    Galería
                                                </h5>
                                            </a>
                                        </div>
                                        <div id="collapse-gallery" class="collapse show" data-bs-parent="#content-tabs-development" role="tabpanel"
                                            aria-labelledby="heading-gallery">
                                            <div class="card-body">
                                                <div class="content content-gallery">
                                                    <div class="slider-for">
                                                        <div>
                                                            <img src="{{ asset('image/development/gallery/gallery-1.jpg')}}" class="img-fluid">
                                                        </div>
                                                        <div>
                                                            <img src="{{ asset('image/development/gallery/gallery-2.jpg')}}" class="img-fluid">
                                                        </div>
                                                        <div>
                                                            <img src="{{ asset('image/development/gallery/gallery-3.jpg')}}" class="img-fluid">
                                                        </div>
                                                        <div>
                                                            <img src="{{ asset('image/development/gallery/gallery-4.jpg')}}" class="img-fluid">
                                                        </div>
                                                        <div>
                                                            <img src="{{ asset('image/development/gallery/gallery-5.jpg')}}" class="img-fluid">
                                                        </div>
                                                    </div>
                                                    <div class="slider-nav">
                                                        <div>
                                                            <img src="{{ asset('image/development/gallery/gallery-1-thumbnail.jpg')}}" class="img-fluid">
                                                        </div>
                                                        <div>
                                                            <img src="{{ asset('image/development/gallery/gallery-2-thumbnail.jpg')}}" class="img-fluid">
                                                        </div>
                                                        <div>
                                                            <img src="{{ asset('image/development/gallery/gallery-3-thumbnail.jpg')}}" class="img-fluid">
                                                        </div>
                                                        <div>
                                                            <img src="{{ asset('image/development/gallery/gallery-4-thumbnail.jpg')}}" class="img-fluid">
                                                        </div>
                                                        <div>
                                                            <img src="{{ asset('image/development/gallery/gallery-5-thumbnail.jpg')}}" class="img-fluid">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="pane-characteristics" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-characteristics">
                                        <div class="card-header" role="tab" id="heading-characteristics">
                                            <a data-bs-toggle="collapse" href="#collapse-characteristics" aria-expanded="true" aria-controls="collapse-characteristics">
                                                <h5 class="mb-0">
                                                    Características
                                                </h5>
                                            </a>
                                        </div>
                                        <div id="collapse-characteristics" class="collapse" data-bs-parent="#content-tabs-development" role="tabpanel"
                                            aria-labelledby="heading-characteristics">
                                            <div class="card-body">
                                                <div class="content characteristics">
                                                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et ac</p>
                                                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et ac</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="pane-master-plan" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-master-plan">
                                        <div class="card-header" role="tab" id="heading-master-plan">
                                            <a data-bs-toggle="collapse" href="#collapse-master-plan" aria-expanded="true" aria-controls="collapse-master-plan">
                                                <h5 class="mb-0">Master Plan</h5>
                                            </a>
                                        </div>
                                        <div id="collapse-master-plan" class="collapse" data-bs-parent="#content-tabs-development" role="tabpanel"
                                            aria-labelledby="heading-master-plan">
                                            <div class="card-body">
                                                <div class="content">
                                                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et ac</p>
                                                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et ac</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                            

                        </div>
                    </div>   
                </div>
            </div>
		</div>
    @include('layouts.footer')
@endsection
