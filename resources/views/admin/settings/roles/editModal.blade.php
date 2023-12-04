<div class="modal" tabindex="-1" id="modal_edit">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-9">
                            <h4><i class="fas fa-edit"></i> Rol</h4>
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
                            <input type="hidden" class="form-control" id="record_id">
                            <div class="row mb-2">
                                <div class="form-floating col-sm-12 col-md-12">
                                    <input type="text" class="form-control" id="txt_name_edit" placeholder="Nombre"
                                        maxlength="244" required>
                                    <label for="txt_name_edit">Nombre <span class="input-required">*</span></label>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="form-floating col-sm-12 col-md-6">
                                    <div class="form-multiselect" id="input_sm-select_sub_seccion_edit">
                                        <label class="lbl-multiselect" for="select_sub_seccion_edit">Subsección </label>
                                        <div class="d-flex text-left align-items-center w-100">
                                            <select id="select_sub_seccion_edit" class="selectpicker form-control" aria-label="Default select example" data-selected-text-format="count" data-live-search="true">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating col-sm-12 col-md-4">
                                    <div class="form-multiselect" id="input_sm-select_permissions_edit">
                                        <label class="lbl-multiselect" for="select_permissions_edit">Permisos </label>
                                        <div class="d-flex text-left align-items-center w-100">
                                            <select id="select_permissions_edit" class="selectpicker form-control" multiple aria-label="Default select example" data-selected-text-format="count" data-live-search="false">
                                                <option value="alta">Alta</option>
                                                <option value="editar">Editar</option>
                                                <option value="eliminar">Eliminar</option>
                                                <option value="visualizar">Visualizar</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating col-sm-12 col-md-2">
                                    <span class="btn-plus-file" style="margin-top: 14px!important;" onclick="addRolItemEdit()"><i class="fas fa-plus"></i></span>
                                </div>
                            </div>

                            <br>
                            <div class="row mt-4">
                                <div class="col-sm-12 col-md-12 text-center">
                                    <span class="subtitle-section-1"><i class="fas fa-shield-alt"></i>&nbsp;Permisos agregados</span>
                                </div>
                            </div>

                            <div class="row mb-2" id="list_permissions_edit">

                            </div>


                            {{------------- FOoTER -----------------}}
                            <div class="modal-footer mt-3">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Descartar</button>
                                <button class="btn btn-primary" onclick="updateRecord()">Guardar</button>
                            </div>
                        </div>

                        <div id="loading_form_edit" style="display:none;" class="row">
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

