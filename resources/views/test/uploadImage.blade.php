@extends('test.layout')
@section( 'content')
    <div class="page-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-9">
                                <h4><i class="fas fa-plus"></i> CISE DMI</h4>
                            </div>
                        </div>
                    </div>

                    <div class="card-block table-border-style">
                        <div class="card-block">
                            <div class="container">
                                <div class="row mt-3" id="data_form_add">
                                    <div class="row mb-2">
                                        <div class="row mb-2">
                                            <div class="form-floating col-sm-12 col-md-12">
                                                <input type="text" class="form-control" id="txt_name_img" placeholder="Título"
                                                    maxlength="244" required>
                                                <label for="txt_name_img">Nombre de Imagen <span class="input-required">*</span></label>
                                            </div>
                                        </div>
                                    </div>

                                    <br>

                                    <div class="row mb-2">
                                        <div class="col-sm-12 col-md-12">
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
                                    </div>

                                    {{------------- FOoTER -----------------}}
                                    <div class="modal-footer mt-3">
                                        <button class="btn btn-primary" onclick="saveRecord()">Cargar</button>
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
    </div>
@endsection

@section('script_footer')

<script>
var ths = this;
function saveRecord(){
    const config = { headers: { 'Content-Type': 'multipart/form-data' } };

        let formData = new FormData();

        formData.append('name_img', document.querySelector('#txt_name_img').value);
        formData.append('photo', document.querySelector('#file_photo').files[0]);

        axios.post('/test/save-upload-image-serverdmi',formData,config)
        .then(response =>{

            let resp = response.data;
            console.log(resp);
            if(resp.success == 1){
                ths.notify('Éxito', 'Registro realizado exitosamente '+resp, 'success');
            }
        })
        .catch(error =>{
            console.log(error);
        });

}

$(".icon-file").on('click',function(){
    let input= this.dataset.input;
    $('#'+input).click();
})

function uploadImage(_event){
    let file = _event.files[0];

    document.querySelector("#name_"+_event.id).innerHTML = file.name;

}
</script>

@endsection
