<div class="modal" tabindex="-1" id="modal_view">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-9">
                        <h4><i class="fas fa-eye"></i> Política</h4>
                    </div>
                    <div class="col-md-3 text-right">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-body">
            <div class="card-block">
                <div class="container">
                    <div class="row mt-3" id="form_add">
                        <div class="row mb-2">
                            <div class="form-floating col-sm-12 col-md-4">
                                <div class="form-multiselect" id="input_sm-select_location_view">
                                    <label class="lbl-multiselect" for="select_location_view">Ubicación <span class="input-required">*</span></label>
                                    <div class="d-flex text-left align-items-center w-100">
                                        <select id="select_location_view" class="selectpicker form-control" multiple aria-label="Default select example" data-selected-text-format="count" data-live-search="true">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating col-sm-12 col-md-4">
                                <div class="form-multiselect" id="input_sm-select_subgroup_view">
                                    <label class="lbl-multiselect" for="select_subgroup_view">Subgrupo</label>
                                    <div class="d-flex text-left align-items-center w-100">
                                        <select id="select_subgroup_view" class="selectpicker form-control" multiple aria-label="Default select example" data-selected-text-format="count" data-live-search="true">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating col-sm-12 col-md-4">
                                <input type="date" class="form-control" id="txt_expiration_date_view"
                                    placeholder="Fecha de expiración" required disabled>
                                <label for="txt_expiration_date_view">Fecha de expiración <span class="input-required">*</span></label>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="form-floating col-sm-12 col-md-12">
                                <input type="text" class="form-control" id="txt_title_view" placeholder="Título"
                                    maxlength="244" required disabled>
                                <label for="txt_title_view">Título <span class="input-required">*</span></label>
                            </div>
                        </div>

                        <br>

                        <div class="row mb-2">
                            <div class="form-floating col-sm-12 col-md-12">
                                <textarea class="form-control" id="txt_description_view" rows="3" maxlength="254"
                                    placeholder="Descripción" required disabled></textarea>
                                <label for="txt_description_view">Descripción <span
                                    class="input-required">*</span></label>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="form-floating col-sm-12 col-md-12">
                                <input type="text" class="form-control" id="txt_link_view"
                                    placeholder="Link" required disabled>
                                <label for="txt_link_view">Link</label>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label class="image-upload" for="txt_link text-center">Imagen y video cargados</label>
                            <div class="col-sm-12 col-md-12 text-center">
                                <img src="" id="file_photo_view" alt="" width="45%">
                            </div>
                            <div class="col-sm-12 col-md-6 text-center">
                                <video class="w-75 border public-video" src="" id="file_video_view" alt="" width="45%" controls="controls" >
                                    Vídeo no es soportado...
                                </video>
                                <div class="no-found-data" id="error_file_video_view">
                                    <img src="{{url('/image/icons/no-video.svg')}}" alt="" width="45%">
                                    <br>
                                    <br>
                                    No hay video cargado
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <label class="image-upload" for="txt_link text-center"><i class="fas fa-file-alt m-2"></i>Archivos</label>
                        </div>

                        <div class="row ms-0 mb-2" id="list_files_view">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}

