<div class="modal" tabindex="-1" id="modal_edit">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-9">
                        <h4><i class="fas fa-edit"></i> Evento</h4>
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
                        <input type="hidden" class="form-control" id="event_id">
                        <div class="row mb-2">
                            <div class="form-floating col-sm-12 col-md-4">
                                <select class="form-select" aria-label="Default select example" id="select_type_event_edit"
                                    required>
                                    <option value="">Cargando...</option>
                                </select>
                                <label for="select_type_event_edit">Tipo de evento <span class="input-required">*</span></label>
                            </div>
                            <div class="form-floating col-sm-12 col-md-4">
                                <div class="form-multiselect" id="input_sm-select_location_edit">
                                    <label class="lbl-multiselect" for="select_location_edit">Ubicación <span class="input-required">*</span></label>
                                    <div class="d-flex text-left align-items-center w-100">
                                        <select id="select_location_edit" class="selectpicker form-control" multiple aria-label="Default select example" data-selected-text-format="count" data-live-search="true" onchange="filterSubgroup(this)">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating col-sm-12 col-md-4">
                                <div class="form-multiselect" id="input_sm-select_subgroup_edit">
                                    <label class="lbl-multiselect" for="select_subgroup_edit">Subgrupo</label>
                                    <div class="d-flex text-left align-items-center w-100">
                                        <select id="select_subgroup_edit" class="selectpicker form-control" multiple aria-label="Default select example" data-selected-text-format="count" data-live-search="true">
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row mb-2">
                            <div class="form-floating col-sm-12 col-md-4">
                                <input type="date" class="form-control" id="txt_date_edit"
                                    placeholder="Fecha de inicio" required>
                                <label for="txt_date_edit">Fecha de inicio <span
                                        class="input-required">*</span></label>
                            </div>
                            <div class="form-floating col-sm-12 col-md-4">
                                <input type="time" class="form-control" id="txt_time_edit"
                                    placeholder="Hora de inicio" required>
                                <label for="txt_time_edit">Hora de inicio </label>
                            </div>
                            <div class="form-floating col-sm-12 col-md-4">
                                <input type="text" class="form-control" id="txt_place_edit" rows="3" maxlength="254"
                                    placeholder="Lugar del evento" required>
                                <label for="txt_place_edit">Lugar del evento</label>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="form-floating col-sm-12 col-md-12">
                                <input type="text" class="form-control" id="txt_title_edit" rows="3" maxlength="254"
                                    placeholder="Título" required>
                                <label for="txt_title_edit">Título <span
                                    class="input-required">*</span></label>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="form-floating col-sm-12 col-md-12">
                                <textarea class="form-control" id="txt_description_edit" rows="3" maxlength="254"
                                    placeholder="Descripción" required></textarea>
                                <label for="txt_description_edit">Descripción </label>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-12 col-md-12">
                                <div class="input-file" id="input-file_file_photo_edit">
                                    <label for="" class="label-file">Portada</label>
                                    <span class="icon-file" data-input="file_photo_edit" data-file_type="image"><i
                                            class="fas fa-image"></i></span>
                                    <span class="name-file" id="name_file_photo_edit"></span>
                                    <input class="form-control d-none" type="file" accept="image/png,image/jpeg"
                                        id="file_photo_edit" placeholder="Imagen" onchange="uploadImage(this)" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label class="image-upload" for="txt_link text-center">Portada cargada</label>
                            <div class="col-sm-12 col-md-12 text-center">
                                <img src="" id="file_photo_view_edit" alt="" width="45%">
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-12 col-md-12 text-center">
                                <span class="subtitle-section-1"><i class="fas fa-images m-2"></i> Imagenes y videos cargados</span>
                            </div>
                        </div>
                        <div class="row mt-4 justify-content-center">
                            <div class="col-sm-12 col-md-4 mb-3" id="div_file_imagesj3bh423d3_edit">
                                <div class="input-file" id="input-file_imagesj3bh423d3_edit">
                                    <label for="" class="label-file">Seleccionar imagenes</label>
                                    <span class="icon-file" data-input="imagesj3bh423d3_edit" data-file_type="document"><i class="fas fa-file-upload"></i></span>
                                    <span class="name-file" id="name_imagesj3bh423d3_edit">No se eligió ningún archivo</span>
                                    <input name="gallery_images[]" class="form-control d-none " type="file" accept="image/png,image/jpg,image/jpeg,video/mp4,video/webm,video/mpeg" id="imagesj3bh423d3_edit" placeholder="Imagen" required multiple>
                                </div>
                            </div>
                        </div>
                        <div class="row list_images" id="list_images_edit">

                        </div>

                        <div class="row mt-5">
                            <div class="col-sm-12 col-md-12 text-center">
                                <span class="subtitle-section-1"><i class="fas fa-file-alt m-2"></i>Archivos</span>
                                &nbsp;
                                <span class="btn-plus-file" onclick="addFiles('edit')"><i class="fas fa-plus"></i></span>
                            </div>
                        </div>

                        <div class="row mb-2" id="list_files_edit">

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

