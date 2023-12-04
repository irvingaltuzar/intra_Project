<!-- The Modal -->
<div class="modal fade " id="modal-images" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <span>
            <h4 class="modal-title" id="modal_title"></h4>
            {{-- <i class="fas fa-map-marker-alt text-danger"></i><span id="modal_location" class="modal-name-location ps-1"></span> --}}
          </span>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div id="data_modal_body" class="modal-body">
          <div class="row m-1 section-description">
            <p id="modal-description" class="ms-2 p-2 "></p>
          </div>
          <div id="communique_files" class="row m-1">
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
          <div class="row m-1" id="communique_link">
            <div class="col-sm-12 col-md-12" >
              <div class="card p-3 b-shadow">
                <h5 class="title-card-2">Enlace</h5>
                <div class="card-body">
                  <i class="fas fa-link"></i><a class="ps-1 text-link" id="modal-link" href="" target="_blank"></a>
                </div>
              </div>
            </div>

          </div>
          <div class="row justify-content-center">
            <img class="img-fluid modal-img" id="modal-img-full" src=""  alt="">
            <video class="w-100 border d-none public-video"  src="" id="modal-video-full" alt="" controls="controls" autoplay="autoplay">
              Vídeo no es soportado...
            </video>

          </div>
          <br>


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
