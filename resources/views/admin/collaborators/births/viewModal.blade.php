<div class="modal" tabindex="-1" id="modal_view">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-9">
                        <h4><i class="fas fa-eye"></i> Nacimiento</h4>
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
                        <div class="row mb-2">
                            <div class="form-floating col-sm-12 col-md-4">
                                <input type="date" class="form-control" id="txt_birth_view"
                                    placeholder="Fecha de nacimiento" disabled>
                                <label for="txt_birth">Fecha de nacimiento</label>
                            </div>
                            <div class="form-floating col-sm-12 col-md-4">
                                <input type="text" class="form-control" id="txt_collaborator_view"
                                    placeholder="Colaborador" disabled>
                                <label for="txt_collaborator_view">Colaborador</label>
                            </div>
                            <div class="form-floating col-sm-12 col-md-4">
                                <input type="text" class="form-control" id="txt_sex_view"
                                    placeholder="Sexo" disabled>
                                <label for="txt_sex_view">Sexo</label>
                            </div>
                        </div>

                        <br>

                        <div class="row mb-3">
                            <div class="form-floating col-sm-12 col-md-12">
                                <textarea class="form-control" id="txt_message_view" rows="3" maxlength="254"
                                    placeholder="Mensaje" disabled></textarea>
                                <label for="txt_message_view">Mensaje</label>
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

  