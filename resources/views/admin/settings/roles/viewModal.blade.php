<div class="modal" tabindex="-1" id="modal_view">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-9">
                            <h4><i class="fas fa-eye"></i> Rol</h4>
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
                                <div class="form-floating col-sm-12 col-md-12">
                                    <input type="text" class="form-control" id="txt_name_view" placeholder="Nombre"
                                        maxlength="244" disabled>
                                    <label for="txt_name">Nombre</label>
                                </div>
                            </div>

                            <br>
                            <div class="row mt-4">
                                <div class="col-sm-12 col-md-12 text-center">
                                    <span class="subtitle-section-1"><i class="fas fa-shield-alt"></i>&nbsp;Permisos agregados</span>
                                </div>
                            </div>

                            <div class="row mb-2" id="list_permissions_view">

                            </div>


                            {{------------- FOoTER -----------------}}
                            <div class="modal-footer mt-3">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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

