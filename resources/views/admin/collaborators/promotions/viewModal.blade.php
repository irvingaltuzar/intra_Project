<div class="modal" tabindex="-1" id="modal_view">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-9">
                        <h4><i class="fas fa-eye"></i> Ascenso</h4>
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
                            <label class="image-upload" for="file_photo_view text-center">Foto de colaborador</label>
                            <div class="col-sm-12 col-md-12 text-center">
                                <img class="icon-collaborator" src="" id="file_photo_view" alt="" width="45%">
                            </div>
                        </div>
                        <div class="row mb-2 justify-content-center">
                            <div class="form-floating col-sm-12 col-md-4">
                                <input type="date" class="form-control" id="txt_expiration_date_view"
                                    placeholder="Fecha de expiración" required disabled>
                                <label for="txt_expiration_date">Fecha de expiración <span class="input-required">*</span></label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="form-floating col-sm-12 col-md-4">
                                <input type="text" class="form-control" id="txt_collaborator_view"
                                    placeholder="Colaborador" disabled>
                                <label for="txt_collaborator_view">Colaborador</label>
                            </div>
                            <div class="form-floating col-sm-12 col-md-4">
                                <input type="text" class="form-control" id="txt_report_to_view"
                                    placeholder="Reporta a" disabled>
                                <label for="txt_report_to_view">Reporta a</label>
                            </div>
                            <div class="form-floating col-sm-12 col-md-4">
                                <input type="text" class="form-control" id="txt_new_position_company_view"
                                    placeholder="Nueva posición en la empresa" required disabled>
                                <label for="txt_new_position_company">Nueva posición en la empresa <span class="input-required">*</span></label>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="form-floating col-sm-12 col-md-12">
                                <textarea class="form-control" id="txt_message_view" rows="3" maxlength="254"
                                    placeholder="Mensaje" required disabled></textarea>
                                <label for="txt_message">Mensaje <span
                                    class="input-required">*</span></label>
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

