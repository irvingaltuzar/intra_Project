<div id="pane-general-information" class="card tab-pane fade show active" role="tabpanel" aria-labelledby="tab-general-information">
    <div class="card-header" role="tab" id="heading-general-information">
        <a class="collapsed" data-bs-toggle="collapse" href="#collapse-general-information" aria-expanded="false"
                aria-controls="collapse-general-information">
            <h5 class="mb-0">
                Información General
            </h5>
        </a>
    </div>
    <div id="collapse-general-information" class="collapse" data-bs-parent="#content-tabs-foundation" role="tabpanel"
        aria-labelledby="heading-general-information">
        <div class="card-body">
            <h3>INCENTIVANDO EL AFÁN DE CRECIMIENTO PERSONAL</h3>
            <p>Más allá de mejorar la calidad de vida de los beneficiados,<strong> nuestro objetivo central</strong> es incentivar en ellos el afán de crecimiento personal que les permitiría por si mismos reconocerse como personas con potencial, que pueden mejorar su forma de vida y por ende la de sus familias.</p>
            <br><br>
            <h3>Misión</h3>
            <p>Generar, promover o apoyar oportunidades de bienestar social y/o formativas que mejoren la calidad de vida de los individuos y comunidades vulnerables, tanto de los diferentes desarrollos inmobiliarios de Grupo DMI, como de la población en donde estos se construyan, a través del establecimiento, coordinación y financiamiento de proyectos de asistencia social propios o externos que contribuyan al bien común y el respeto del orden natural.</p>
            <br>
            <h3>Visión</h3>
            <ul>
                <li>Ser una <strong>referencia</strong> como fundación para <strong>fomentar</strong> en otras <strong>empresas</strong> el afán por <strong>generar programas</strong> de apoyo <strong>integral</strong> para sus colaboradores.</li>
                <li>Lograr que nuestros <strong>beneficiados</strong> se conviertan en <strong>generadores</strong> de cambio personal, familiar, laboral y social, así como que alcancen un <strong>mejor nivel profesional.</strong> </li>
            </ul>
            <br>
            <a href="http://educacionysalud.org/" target="_blank" class="special-buttom">Ir al sitio web</a>


            <div class="content">
                <div id="main" role="main">

                    @php
                        $gallery = scandir('image/foundation/gallery');
                        unset($gallery[0]);
                        unset($gallery[1]);
                    @endphp

                    <section class="slider">
                      <div id="slider" class="flexslider">
                        <ul class="slides">
                            @foreach($gallery as $img)
                                <li>
                                    <img src="{{ asset('image/foundation/gallery/')}}/{{$img}}"/>
                                </li>
                            @endforeach

                        </ul>
                      </div>
                      <div id="carousel" class="flexslider">
                        <ul class="slides">
                            @foreach($gallery as $img)
                                <li>
                                    <img src="{{ asset('image/foundation/gallery/')}}/{{$img}}"/>
                                </li>
                            @endforeach
                        </ul>
                      </div>
                    </section>
                </div>
                <br>
                <br>
                <div class="row justify-content-center">
                    <div class="col-md-9">
                        <video class="w-100 border "  controls="controls" autoplay="0" id="video_fundacion">
                            <source src="{{ asset('image/foundation/video.mp4')}}" type="video/mp4">
                            Vídeo no es soportado...
                        </video>
                    </div>
                </div>
                <br>
                <br>
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <img src="{{ asset('image/foundation/QUE-SIGNIFICA-SER-BENEFICIADO.png')}}" class="img-fluid">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
