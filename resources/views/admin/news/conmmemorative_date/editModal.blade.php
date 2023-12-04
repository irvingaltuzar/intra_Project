<div class="modal" tabindex="-1" id="modal_edit">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-9">
                        <h4><i class="fas fa-edit"></i> Fechas Conmmemorativas</h4>
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
                    <div class="row mt-3" id="data_form_edit">
                        <div class="row mb-2">
                            <input type="hidden" class="form-control" id="conmmemorative_date_id">
                            <div class="row mb-2">
                                <div class="form-floating col-sm-12 col-md-3">
                                    <div class="form-multiselect" id="input_sm-select_location_edit">
                                        <label class="lbl-multiselect" for="select_location_edit">Ubicación <span class="input-required">*</span></label>
                                        <div class="d-flex text-left align-items-center w-100">
                                            <select id="select_location_edit" class="selectpicker form-control" multiple aria-label="Default select example" data-selected-text-format="count" data-live-search="true" onchange="filterSubgroup(this)">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating col-sm-12 col-md-3">
                                    <div class="form-multiselect" id="input_sm-select_subgroup_edit">
                                        <label class="lbl-multiselect" for="select_subgroup_edit">Subgrupo</label>
                                        <div class="d-flex text-left align-items-center w-100">
                                            <select id="select_subgroup_edit" class="selectpicker form-control" multiple aria-label="Default select example" data-selected-text-format="count" data-live-search="true">
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-floating col-sm-12 col-md-3">
                                    <input type="date" class="form-control" id="txt_publication_date_edit"
                                        placeholder="Fecha conmemorativa" required>
                                    <label for="txt_publication_date_edit">Fecha conmemorativa <span class="input-required">*</span></label>
                                </div>
                                <div class="form-floating col-sm-12 col-md-3">
                                    <input type="date" class="form-control" id="txt_expiration_date_edit"
                                        placeholder="Fecha de expiración" required>
                                    <label for="txt_expiration_date_edit">Fecha de expiración <span class="input-required">*</span></label>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="row mb-2">
                                    <div class="form-floating col-sm-12 col-md-12">
                                        <input type="text" class="form-control" id="txt_title_edit" placeholder="Título"
                                            maxlength="244" required>
                                        <label for="txt_title">Título <span class="input-required">*</span></label>
                                    </div>
                                </div>
                            </div>

                            <br>

                            <div class="row mb-2">
                                <div class="form-floating col-sm-12 col-md-12">
                                    <textarea class="form-control" id="txt_description_edit" rows="3"
                                        placeholder="Descripción" required></textarea>
                                    <label for="txt_description_edit">Descripción</label>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-12 col-md-12">
                                    <div class="input-file" id="input-file_file_photo_edit">
                                        <label for="" class="label-file">Elige una Imagen <span class="input-required">*</span></label>
                                        <span class="icon-file" data-input="file_photo_edit" data-name-file=""><i class="fas fa-image"></i></span>
                                        <span class="name-file" id="name_file_photo_edit"></span>
                                        <input class="form-control d-none" type="file" accept="image/png,image/jpeg" id="file_photo_edit" placeholder="Imagen" onchange="updateImage(this)" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label class="image-upload" for="txt_link text-center">Imagen cargada</label>
                                <div class="col-sm-12 col-md-12 text-center">
                                    <img src="" id="file_photo_view_edit" alt="" width="45%">
                                </div>
                            </div>

                        {{------------- FOoTER -----------------}}
                        <div class="modal-footer mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Descartar</button>
                            <button class="btn btn-primary" onclick="updateRecord()">Actualizar</button>
                        </div>
                    </div>

                    <div id="loading_form_edit" style="display:none;" class="row">
                        <div class="col-sm-12 col-md-12 text-center p-4">
                            <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="modal-footer">

        </div> --}}
      </div>
    </div>
  </div>

  {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}

