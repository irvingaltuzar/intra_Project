<div class="modal" tabindex="-1" id="modal_add">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-9">
                            <h4><i class="fas fa-plus"></i> Condolencia</h4>
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
                            <div class="row mb-3">
                                <div class="form-floating col-sm-12 col-md-3">
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
                                <div class="form-floating col-sm-12 col-md-3">
                                    <div class="form-multiselect" id="input_sm-select_subgroup">
                                        <label class="lbl-multiselect" for="select_subgroup">Subgrupo </label>
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
                                <div class="form-floating col-sm-12 col-md-3">
                                    <input type="date" class="form-control" id="txt_condolence_date"
                                        placeholder="Fecha de condolencia" required>
                                    <label for="txt_condolence_date">Fecha de condolencia <span
                                            class="input-required">*</span></label>
                                </div>
                                <div class="form-floating col-sm-12 col-md-3">
                                    <input type="date" class="form-control" id="txt_expiration_date"
                                        placeholder="Fecha de expiración" required>
                                    <label for="txt_expiration_date">Fecha de expiración <span
                                            class="input-required">*</span></label>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="form-floating col-sm-12 col-md-6">
                                    <input type="text" class="form-control" id="txt_collaborator" placeholder="Nuestras condolencias" maxlength="244" required
                                    data-input_origin="collaborator" oninput="transcribeInput(this)">
                                    <label for="txt_collaborator">Nuestras condolencias <span class="input-required">*</span></label>
                                </div>
                                <div class="form-floating col-sm-12 col-md-6">
                                    <input type="text" class="form-control" id="txt_accompanies" placeholder="Acompañamos:" maxlength="244" required
                                    data-input_origin="accompanies" oninput="transcribeInput(this)">
                                    <label for="txt_accompanies">Acompañamos: <span class="input-required">*</span></label>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="form-floating col-sm-12 col-md-12">
                                    <textarea class="form-control" id="txt_condolence" rows="3" maxlength="254"
                                        placeholder="Pérdida de:" required data-input_origin="condolence" oninput="transcribeInput(this)"></textarea>
                                    <label for="txt_condolence">Pérdida de: <span
                                        class="input-required">*</span></label>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-sm-12 col-md-12 text-center">
                                    <span class="subtitle-section-1"><i class="fas fa-eye m-2"></i>Preview</span>
                                </div>
                            </div>

                            <div class="row mt-1 condolence-preview">
                                <div class="offset-md-1 col-sm-12 col-md-2 text-center">
                                    <img src="{{url('storage/plantilla/cristo.png')}}" width="80%" alt="">
                                    <p id="span_collaborator" class="condolence-preview-dynamics d-none">{}</p>
                                </div>
                                <div class="col-sm-12 col-md-9 text-center">
                                    <p class="condolence-preview-text">
                                        Acompañamos <span id="span_accompanies" class="condolence-preview-dynamics">{}</span>  en estos momentos de profunda tristeza por la sensible pérdida de <span id="span_condolence" class="condolence-preview-dynamics">{}</span>.
                                    </p>
                                    <p class="condolence-preview-text">
                                        Elevamos nuestras oraciones por su eterno descanso y por el consuelo para toda la familia.
                                    </p>
                                </div>
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
            {{-- <div class="modal-footer">

            </div> --}}
        </div>
    </div>
</div>

{{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
