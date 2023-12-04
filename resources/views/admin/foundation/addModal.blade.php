<div class="modal" tabindex="-1" id="modal_add">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-9">
                            <h4><i class="fas fa-plus"></i> Cápsula</h4>
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
                        <div class="row mt-3" id="data_form_add">
                            <div class="row mb-2">
                                <div class="form-floating col-sm-12 col-md-4">
                                    <div class="form-multiselect" id="input_sm-select_location">
                                        <label class="lbl-multiselect" for="select_location">Ubicación <span class="input-required">*</span></label>
                                        <div class="d-flex text-left align-items-center w-100">
                                            <select id="select_location" class="selectpicker form-control" multiple aria-label="Default select example" data-selected-text-format="count" data-live-search="true" onchange="filterSubgroup(this)">
                                            </select>
                                            <div class="input-group-append">
                                                <span class="icon-file" style="font-size:1.0rem;" onclick="showList(0);">
                                                    <i class="fas fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating col-sm-12 col-md-4">
                                    <div class="form-multiselect" id="input_sm-select_subgroup">
                                        <label class="lbl-multiselect" for="select_subgroup">Subgrupo</label>
                                        <div class="d-flex text-left align-items-center w-100">
                                            <select id="select_subgroup" class="selectpicker form-control" multiple aria-label="Default select example" data-selected-text-format="count" data-live-search="true">
                                            </select>
                                            <div class="input-group-append">
                                                <span class="icon-file" style="font-size:1.0rem;" onclick="showList(2);">
                                                    <i class="fas fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating col-sm-12 col-md-4">
                                    <input type="date" class="form-control" id="txt_expiration_date"
                                        placeholder="Fecha de expiración" required>
                                    <label for="txt_expiration_date">Fecha de expiración <span class="input-required">*</span></label>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="form-floating col-sm-12 col-md-12">
                                    <input type="text" class="form-control" id="txt_title" placeholder="Título"
                                        maxlength="244" required>
                                    <label for="txt_title">Título <span class="input-required">*</span></label>
                                </div>
                            </div>

                            <br>

                            <div class="row mb-2">
                                <div class="form-floating col-sm-12 col-md-12">
                                    <textarea class="form-control" id="txt_description" rows="3"
                                        placeholder="Descripción"></textarea>
                                    <label for="txt_description">Descripción</label>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="form-floating col-sm-12 col-md-12">
                                    <input type="text" class="form-control" id="txt_link"
                                        placeholder="Link" required>
                                    <label for="txt_link">Link</label>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-12 col-md-6">
                                    <div class="input-file" id="input-file_file_photo">
                                        <label for="" class="label-file">Elige una Imagen <span
                                                class="input-required">*</span></label>
                                        <span class="icon-file" data-input="file_photo" data-file_type="image"><i
                                                class="fas fa-image"></i></span>
                                        <span class="name-file" id="name_file_photo"></span>
                                        <input class="form-control d-none" type="file" accept="image/png,image/jpeg"
                                            id="file_photo" placeholder="Imagen" onchange="uploadImage(this)" required>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="input-file" id="input-file_file_video">
                                        <label for="" class="label-file">Elige un video</label>
                                        <span class="icon-file" data-input="file_video" data-file_type="image"><i class="fas fa-video"></i></span>
                                        <span class="name-file" id="name_file_video"></span>
                                        <input class="form-control d-none" type="file" accept="video/mp4"
                                            id="file_video" placeholder="Imagen" onchange="uploadImage(this,'video')" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-sm-12 col-md-12 text-center">
                                    <span class="subtitle-section-1"><i class="fas fa-file-alt m-2"></i>Archivos</span>
                                    &nbsp;
                                    <span class="btn-plus-file" onclick="addFiles()"><i class="fas fa-plus"></i></span>
                                </div>
                            </div>

                            <div class="row mb-2" id="list_files">

                            </div>


                            {{------------- FOoTER -----------------}}
                            <div class="modal-footer mt-3">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Descartar</button>
                                <button class="btn btn-primary" onclick="saveRecord()">Guardar</button>
                            </div>
                        </div>

                        <div id="loading_form_add" style="display:none;" class="row">
                            <div class="col-sm-12 col-md-12 text-center p-4">
                                <div class="lds-ellipsis">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

