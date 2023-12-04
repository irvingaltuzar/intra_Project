<div class="modal" tabindex="-1" id="modal_add">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-9">
                            <h4><i class="fas fa-plus"></i> Control de Acceso</h4>
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
                                <div class="form-floating col-sm-12 col-md-5">
                                    <div class="form-multiselect" id="input_sm-select_user">
                                        <label class="lbl-multiselect" for="select_user">Usuario </label>
                                        <div class="d-flex text-left align-items-center w-100">
                                            <select id="select_user" onchange="isUserExist()" class="selectpicker form-control" aria-label="Default select example" data-selected-text-format="count" data-live-search="true">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating col-sm-12 col-md-5">
                                    <div class="form-multiselect" id="input_sm-select_rol">
                                        <label class="lbl-multiselect" for="select_rol">Rol </label>
                                        <div class="d-flex text-left align-items-center w-100">
                                            <select id="select_rol" class="selectpicker form-control" aria-label="Default select example" data-selected-text-format="count" data-live-search="true">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating col-sm-12 col-md-2">
                                    <span class="btn-plus-file" style="margin-top: 14px!important;" onclick="addRolItem()"><i class="fas fa-plus"></i></span>
                                </div>
                            </div>
                            <br>

                            <div class="row mt-4">
                                <div class="col-sm-12 col-md-12 text-center">
                                    <span class="subtitle-section-1"><i class="fas fa-shield-alt"></i>&nbsp;Permisos agregados</span>
                                </div>
                            </div>

                            <div class="row mb-2" id="list_permissions">

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

