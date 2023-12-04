<div class="modal" tabindex="-1" id="modal_edit">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-9">
                        <h4><i class="fas fa-edit"></i> Nacimientos</h4>
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
                            <input type="hidden" class="form-control" id="birth_id">
                            <div class="form-floating col-sm-12 col-md-4">
                                <input type="date" class="form-control" id="txt_birth_edit"
                                    placeholder="Fecha de nacimiento" required>
                                <label for="txt_birth_edit">Fecha de nacimiento <span
                                        class="input-required">*</span></label>
                            </div>
                            <div class="form-floating col-sm-12 col-md-4">
                                <select class="form-select" aria-label="Default select example" id="select_collaborator_edit"
                                    required>
                                    <option value="">Cargando...</option>
                                </select>
                                <label for="select_collaborator_edit">Colaborador <span class="input-required">*</span></label>
                            </div>
                            <div class="form-floating col-sm-12 col-md-4">
                                <select class="form-select" aria-label="Default select example" id="select_sex_edit"
                                    required>
                                    <option selected disabled value="">Seleccione una opción</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Masculino">Masculino</option>
                                </select>
                                <label for="select_sex_edit">Sexo <span class="input-required">*</span></label>
                            </div>
                        </div>

                        <br>

                        <div class="row mb-3">
                            <div class="form-floating col-sm-12 col-md-12">
                                <textarea class="form-control" id="txt_message_edit" rows="3" maxlength="254"
                                    placeholder="Mensaje" required></textarea>
                                <label for="txt_message_edit">Mensaje <span
                                    class="input-required">*</span></label>
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

  