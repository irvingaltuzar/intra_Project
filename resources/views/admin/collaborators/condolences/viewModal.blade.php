<div class="modal" tabindex="-1" id="modal_view">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-9">
                        <h4><i class="fas fa-eye"></i> Condolencia</h4>
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
                        <div class="row mb-3">
                            <div class="form-floating col-sm-12 col-md-3">
                                <div class="form-multiselect" id="input_sm-select_location_view">
                                    <label class="lbl-multiselect" for="select_location_view">Ubicación <span class="input-required">*</span></label>
                                    <div class="d-flex text-left align-items-center w-100">
                                        <select id="select_location_view" class="selectpicker form-control" multiple aria-label="Default select example" data-selected-text-format="count" data-live-search="true">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating col-sm-12 col-md-3">
                                <div class="form-multiselect" id="input_sm-select_subgroup_view">
                                    <label class="lbl-multiselect" for="select_subgroup_view">Subgrupo</label>
                                    <div class="d-flex text-left align-items-center w-100">
                                        <select id="select_subgroup_view" class="selectpicker form-control" multiple aria-label="Default select example" data-selected-text-format="count" data-live-search="true">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating col-sm-12 col-md-3">
                                <input type="date" class="form-control" id="txt_condolence_date_view"
                                    placeholder="Fecha de condolencia" required disabled>
                                <label for="txt_condolence_date">Fecha de condolencia</label>
                            </div>
                            <div class="form-floating col-sm-12 col-md-3">
                                <input type="date" class="form-control" id="txt_expiration_date_view"
                                    placeholder="Fecha de expiración" required disabled>
                                <label for="txt_expiration_date">Fecha de expiración</label>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="form-floating col-sm-12 col-md-6">
                                <input type="text" class="form-control" id="txt_collaborator_view" placeholder="Nuestras condolencias" maxlength="244" required disabled>
                                <label for="txt_collaborator">Nuestras condolencias</label>
                            </div>
                            <div class="form-floating col-sm-12 col-md-6">
                                <input type="text" class="form-control" id="txt_accompanies_view" placeholder="Acompañamos:" maxlength="244" required
                                data-input_origin="accompanies" disabled>
                                <label for="txt_accompanies">Acompañamos: </label>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="form-floating col-sm-12 col-md-12">
                                <textarea class="form-control" id="txt_condolence_view" rows="3" maxlength="254"
                                    placeholder="Pérdida de:" required data-input_origin="condolence"  disabled></textarea>
                                <label for="txt_condolence">Pérdida de:</label>
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
                                <p id="span_collaborator_view" class="condolence-preview-dynamics d-none">{}</p>
                            </div>
                            <div class="col-sm-12 col-md-9 text-center">
                                <p class="condolence-preview-text">
                                    Acompañamos <span id="span_accompanies_view" class="condolence-preview-dynamics">{}</span>  en estos momentos de profunda tristeza por la sensible perdida de <span id="span_condolence_view" class="condolence-preview-dynamics">{}</span>.
                                </p>
                                <p class="condolence-preview-text">
                                    Elevamos nuestras oraciones por su eterno descanso y por el consuelo para toda la familia.
                                </p>
                            </div>
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

