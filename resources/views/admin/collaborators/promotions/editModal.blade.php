<div class="modal" tabindex="-1" id="modal_edit">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-9">
                        <h4><i class="fas fa-edit"></i> Ascenso</h4>
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
                        <input type="hidden" class="form-control" id="promotion_id">
                        <div class="row mb-2">
                            <label class="image-upload" for="file_photo_view_edit text-center">Foto de colaborador cargada</label>
                            <div class="col-sm-12 col-md-12 text-center">
                                <img class="icon-collaborator mb-2" src="" id="file_photo_view_edit" alt="" width="45%">
                            </div>
                        </div>
                        <div class="row mb-3 justify-content-center">
                            <div class="form-floating col-sm-12 col-md-4 ">
                                <div class="input-file" id="input-file_file_photo_edit">
                                    <label for="" class="label-file">Foto del colaborador <span class="input-required">*</span></label>
                                    <span class="icon-file" data-input="file_photo_edit" data-name-file=""><i class="fas fa-image"></i></span>
                                    <span class="name-file" id="name_file_photo_edit"></span>
                                    <input class="form-control d-none" type="file" accept="image/png,image/jpeg" id="file_photo_edit" placeholder="Imagen" onchange="updateImage(this)" required>
                                </div>
                            </div>
                            <div class="form-floating col-sm-12 col-md-4 offset-md-4">
                                <input type="date" class="form-control" id="txt_expiration_date_edit"
                                    placeholder="Fecha de expiración" required>
                                <label for="txt_expiration_date">Fecha de expiración <span class="input-required">*</span></label>
                            </div>
                        </div>
                        <br>
                        <div class="row mb-3">
                            <div class="form-floating col-sm-12 col-md-4">
                                <select class="form-select" aria-label="Default select example" id="select_collaborator_edit"
                                    required>
                                    <option value="">Cargando...</option>
                                </select>
                                <label for="select_collaborator">Colaborador <span class="input-required">*</span></label>
                            </div>
                            <div class="form-floating col-sm-12 col-md-4">
                                <select class="form-select" aria-label="Default select example" id="select_report_to_edit"
                                    required>
                                    <option value="">Cargando...</option>
                                </select>
                                <label for="select_report_to_edit">Reporta a:<span class="input-required">*</span></label>
                            </div>
                            <div class="form-floating col-sm-12 col-md-4">
                                <input type="text" class="form-control" id="txt_new_position_company_edit"
                                    placeholder="Nueva posición en la empresa" required>
                                <label for="txt_new_position_company">Nueva posición en la empresa <span class="input-required">*</span></label>
                            </div>

                        </div>

                        <div class="row mb-2">
                            <div class="form-floating col-sm-12 col-md-12">
                                <textarea class="form-control" id="txt_message_edit" rows="3" maxlength="254"
                                    placeholder="Mensaje" required></textarea>
                                <label for="txt_message">Mensaje <span
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

