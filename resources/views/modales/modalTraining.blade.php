<!-- The Modal -->
<div class="modal fade " id="modal-training" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <span>
            <h4 class="modal-title" id="modal_title"></h4>
          </span>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div id="data_modal_body" class="modal-body">
            <div class="row p-3">
                <div class="col-md-12 col-sm-12 card-info-event">
                    <i class="fas fa-calendar-day text-secondary"> </i>&nbsp;<span id="modal-date"></span><br>
                    <i class="fas fa-clock text-secondary"></i>&nbsp;<span id="modal-time"></span><br>
                    <i class="fas fa-map-marker-alt text-secondary"></i>&nbsp;<span id="modal-place"></span><br>
                    <i class="fas fa-link text-secondary"></i>&nbsp;<span id="modal-link"></span><br>
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
            <br>
            <div class="row justify-content-center">
                <img class="img-fluid modal-img" id="modal-img-full" src=""  alt="">
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
