<!-- The Modal -->
<div class="modal fade " id="modal-event" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <span>
            <h4 class="modal-title" id="modal_title"></h4>
            {{-- <i class="fas fa-map-marker-alt text-danger"></i> --}}<span id="modal_location" class="modal-name-location ps-1 d-none"></span>
          </span>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div id="data_modal_body" class="modal-body">
            <div class="row p-3">
                <div class="col-md-12 col-sm-12 card-info-event">
                    <i class="fas fa-calendar-day text-secondary"> </i>&nbsp;<span id="modal-date"></span><br>
                    <i class="fas fa-clock text-secondary"></i>&nbsp;<span id="modal-time"></span><br>
                    <i class="fas fa-map-marker-alt text-secondary"></i>&nbsp;<span id="modal-place"></span><br>
                    <div class="about-colors fc-event" style="width:150px;" id="modal-type-event"></div>
                </div>

            </div>
            <div class="row m-1 section-description">
                <p id="modal-description" class="ms-2 p-2 "></p>
            </div>
            <div class="row m-1">
                <div class="col-sm-12 col-md-12">
                    <div class="card p-3 b-shadow">
                      <h5 class="title-card-2">Archivos</h5>
                      <div class="card-body">
                          <div class="row" id="list_files">

                          </div>
                      </div>
                    </div>
                  </div>
            </div>


            <div class="row" id="carrusel_portada">
                <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators" id="modal-carousel-indicators" >

                    </div>
                    <div class="carousel-inner" id="modal-carrusel-inner">
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Next</span>
                    </button>
                  </div>
            </div>

        </div>
        <div id="loading_modal_body" class="modal-body">
          <br>
          <br>
          <div class="row justify-content-center">
            <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
          </div>
          <br>
          <br>
        </div>
      </div>
    </div>
  </div>
