<div class="modal" tabindex="-1" id="modal_edit">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-9">
                        @if($communique_type == 'council')
                            <h4><i class="fas fa-edit"></i> Comunicado Institucional</h4>
                        @elseif($communique_type == 'organizational')
                            <h4><i class="fas fa-edit"></i> Movimiento Organizacional</h4>
                        @elseif($communique_type == 'institutional')
                            <h4><i class="fas fa-edit"></i> Campaña Institucional</h4>
                        @endif
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
                        <input type="hidden" class="form-control" id="communique_id">
                        <div class="row mb-2">
                            {{-- <div class="form-floating col-sm-12 col-md-4">
                                <select class="form-select" aria-label="Default select example" id="select_location_edit" required>

                                </select>
                                <label for="select_location">Ubicación <span class="input-required">*</span></label>
                            </div> --}}
                            <div class="form-floating col-sm-12 col-md-3">
                                <div class="form-multiselect" id="input_sm-select_location_edit">
                                    <label class="lbl-multiselect" for="select_location_edit">Ubicación <span class="input-required">*</span></label>
                                    <div class="d-flex text-left align-items-center w-100">
                                        <select id="select_location_edit" class="selectpicker form-control" multiple aria-label="Default select example" data-selected-text-format="count" data-live-search="true" onchange="filterSubgroup(this)">
                                        </select>
                                        <div class="input-group-append">
                                            <span class="icon-file" style="font-size:1.0rem;" onclick="showList(1);">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating col-sm-12 col-md-3">
                                <div class="form-multiselect" id="input_sm-select_subgroup_edit">
                                    <label class="lbl-multiselect" for="select_subgroup_edit">Subgrupo</label>
                                    <div class="d-flex text-left align-items-center w-100">
                                        <select id="select_subgroup_edit" class="selectpicker form-control" multiple aria-label="Default select example" data-selected-text-format="count" data-live-search="true">
                                        </select>
                                        <div class="input-group-append">
                                            <span class="icon-file" style="font-size:1.0rem;" onclick="showList(3);">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating col-sm-12 col-md-3">
                                <input type="date" class="form-control" id="txt_expiration_date_edit" placeholder="Fecha de expiración" required>
                                <label for="txt_expiration_date">Fecha de expiración <span class="input-required">*</span></label>
                            </div>
                            <div class="form-floating col-sm-12 col-md-3">
                                <select class="form-select" aria-label="Default select example" id="select_priority_edit" required>
                                    <option selected disabled value="">Selecciona una opción</option>
                                    <option value="Alta">Alta</option>
                                    <option value="Media">Media</option>
                                    <option value="Baja">Baja</option>
                                </select>
                                <label for="select_priority">Prioridad <span class="input-required">*</span></label>
                            </div>
                        </div>

                        <br>

                        <div class="row mb-2">
                              <div class="form-floating col-sm-12 col-md-12">
                                <input type="text" class="form-control" id="txt_title_edit" placeholder="Título" maxlength="244" required>
                                <label for="txt_title">Título <span class="input-required">*</span></label>
                              </div>
                        </div>

                        <div class="row mb-3">
                            <div class="form-floating col-sm-12 col-md-12">
                                <textarea class="form-control" id="txt_description_edit" rows="3" placeholder="Descripción" required></textarea>
                                <label for="txt_description">Descripción</label>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-12 col-md-6">
                                <div class="input-file" id="input-file_file_photo_edit">
                                    <label for="" class="label-file">Elige una Imagen <span class="input-required">*</span></label>
                                    <span class="icon-file" data-input="file_photo_edit" data-name-file=""><i class="fas fa-image"></i></span>
                                    <span class="name-file" id="name_file_photo_edit"></span>
                                    <input class="form-control d-none" type="file" accept="image/png,image/jpeg" id="file_photo_edit" placeholder="Imagen" onchange="updateImage(this)" required>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="input-file" id="input-file_file_video_edit">
                                    <label for="" class="label-file">Elige un video</label>
                                    <span class="icon-file" data-input="file_video_edit" data-file_type="image"><i class="fas fa-video"></i></span>
                                    <span class="name-file" id="name_file_video_edit"></span>
                                    <input class="form-control d-none" type="file" accept="video/mp4"
                                        id="file_video_edit" placeholder="Imagen" onchange="uploadImage(this,'video')" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="form-floating col-sm-12 col-md-12">
                                <input type="text" class="form-control" id="txt_link_edit" placeholder="Link">
                                <label for="txt_link">Link</label>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label class="image-upload" for="txt_link text-center">Imagen y video cargados</label>
                            <div class="col-sm-12 col-md-6 text-center">
                                <img src="" id="file_photo_view_edit" alt="" width="45%">
                            </div>
                            <div class="col-sm-12 col-md-6 text-center">
                                <video class="w-75 border public-video" src="" id="file_video_view_edit" alt="" width="45%" controls="controls" >
                                    Vídeo no es soportado...
                                </video>
                                <div class="no-found-data" id="error_file_video_edit">
                                    <img src="{{url('/image/icons/no-video.svg')}}" alt="" width="45%">
                                    <br>
                                    <br>
                                    No hay video cargado
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-12 col-md-12 text-center">
                                <span class="subtitle-section-1"><i class="fas fa-file-alt m-2"></i>Archivos</span>
                                &nbsp;
                                <span class="btn-plus-file" onclick="addFiles('edit')"><i class="fas fa-plus"></i></span>
                            </div>
                        </div>

                        <div class="row mb-2" id="list_files_edit">

                        </div>

                        <div class="modal-footer mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Descartar</button>
                            <button class="btn btn-primary" onclick="updateCommunique()">Actualizar</button>
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

