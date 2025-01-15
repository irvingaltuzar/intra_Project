var ths = this;
var general_data = {
    data:{},
    headers:{
        limit:10,
        page:1,
        order_by:'desc',
        type_event_by:'',
        search:{
            isSearch:false,
            text:""
        }
    },
    fields_modal:{
        required:['select_location','txt_expiration_date','txt_date_start','txt_time_start','txt_place','txt_title','txt_link'],
        edit_required:['select_location_edit','txt_expiration_date_edit','txt_date_start_edit','txt_time_start_edit','txt_place_edit','txt_title_edit','txt_link'],
        total:['select_location','select_subgroup','txt_expiration_date','txt_date_start','txt_time_start','txt_title','txt_place','txt_description','file_photo','txt_link'],
    },
    type_events:{},
    locations:{},
    subgroups:{},

}

var form_data_image_gallery = new FormData();

function getPage(_page=1){

    ths.general_data.headers.page = _page;

    ths.visualisity_loading('loading','list');

    axios.get(ths.general_data.data.path,{
        params:ths.general_data.headers
    })
    .then(response =>{

        let resp = response.data;
        if(resp != null){
            ths.general_data.data= resp;
            ths.addList(resp.data,resp.from);
            ths.paginate_control(resp);
        }
        ths.visualisity_loading('data','list');
    })
    .catch( error =>{
        ths.visualisity_loading('data','list');
        console.log(error)
        ths.notification({type:'error',text:'Error al cargar los registros '+error})
    })

}

function addList(_list,_from){
    let _rows_HTML = ``;
    let expiration_type="";

    if(_list.length > 0){
        let locations = "";
        _list.forEach((item,key) =>{

            if(item.bucket_location.length == 0){
                locations = "Sin ubicación";
            }else if(item.bucket_location.length == 1){
                locations = item.bucket_location[0].location.name;
            }else{
                locations = "Más de una ubicación";
            }

            _rows_HTML +=`<tr>
                            <th scope="row">${_from}</th>
                            <td>${item.title}</td>
                            <td>${locations}</td>
                            <td>
                                <i class="fas fa-calendar-alt"></i> ${item.start_date}<br>
                                <i class="fas fa-clock"></i> ${item.start_time}<br>
                            </td>
                            <td>${item.expiration_date}</td>
                            <td>${item.created_at}</td>
                            <td class="text-center">
                                <div id="data_delete_${item.id}">
                                    <button class="btn btn-light btn-action-primary" onclick="viewRecord(${item.id})" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ver"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-light btn-action-success" onclick="editRecord(${item.id})" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Editar"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-light btn-action-danger" onclick="deleteRecord(${item.id})" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar"><i class="fas fa-trash"></i></button>
                                </div>
                                <div id="loading_delete_${item.id}" style="display:none;" class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>

                            </td>
                        </tr>`

            _from++;
        });

        document.querySelector('#data_list').innerHTML = _rows_HTML;
        ths.paginate_control(ths.general_data.data);

    }else{

        document.querySelector('#data_list').innerHTML = ths.noDataFound({
            content:"table",
            colspan:"7"
        });

    }
}


async function loadLocation(){

    let locations = await ths.getLocations();
    if(locations.length > 0){

        $('#select_location').empty();
        const option = document.createElement('option');
        option.text = "Selecciona una opción";
        option.selected = true;
        option.value = "";
        option.disabled = true;

        locations.forEach(location => {
            const option = document.createElement('option');
            option.value = location.id
            option.text = location.name;
            document.querySelector("#select_location").appendChild(option);
        })

        $('#select_location').selectpicker('refresh');
    }


}

function filterSubgroup(_event){
    let type_select = _event.id == "select_location" ? "" : "_edit";
    let subgroups = [];
    let select_locations = $(`#select_location${type_select} option:selected`).toArray();

    if(select_locations.length > 0){
        select_locations.forEach(location =>{
            ths.general_data.subgroups.forEach( subgroup =>{
                if(subgroup.vw_locations_name == location.innerText){
                    subgroups.push(subgroup)
                }
            })
        })
        this.loadSubgroups(subgroups,type_select);
    }else{
        $(`#select_subgroup${type_select}`).empty();
        $(`#select_subgroup${type_select}`).selectpicker('refresh');
    }


}

async function loadSubgroups(_subgroups,_type_select){

    if(_subgroups.length > 0){

        $(`#select_subgroup${_type_select}`).empty();
        const option = document.createElement('option');
        option.text = "Selecciona una opción";
        option.selected = true;
        option.value = "";
        option.disabled = true;

        _subgroups.forEach(subgroup => {
            const option = document.createElement('option');
            option.value = subgroup.id
            option.text = subgroup.name;
            document.querySelector(`#select_subgroup${_type_select}`).appendChild(option);
        })

        $(`#select_subgroup${_type_select}`).selectpicker('refresh');

    }

}

async function preLoadLocations(){
    let locations = await ths.getLocations();
    ths.general_data.locations = locations;
    ths.loadLocation();
}

async function preLoadSubgroups(){
    let subgroups = await ths.getSubgroups();
    ths.general_data.subgroups = subgroups;
}


function newRecord(){
    ths.clearFields(ths.general_data.fields_modal.total);
    ths.loadLocation();
    ths.form_data_image_gallery = new FormData();
    document.querySelector("#list_files").innerHTML = ths.noDataFound({text:'No hay ningún archivo cargado.'});
    $('#modal_add').modal('show');
}

function saveRecord(){
    let isValid = ths.validateFields(ths.general_data.fields_modal.required);
    const config = { headers: { 'Content-Type': 'multipart/form-data' } };

    if(isValid == true){

        ths.visualisity_loading('loading','form_add');

        let formData = new FormData();
        $("#select_location").val().forEach(location =>{
            formData.append('locations[]',location );
        })
        $("#select_subgroup").val().forEach(subgroup =>{
            formData.append('subgroups[]',subgroup );
        })

        formData.append('expiration_date', document.querySelector('#txt_expiration_date').value);
        formData.append('start_date', document.querySelector('#txt_date_start').value);
        formData.append('start_time', document.querySelector('#txt_time_start').value);
        formData.append('title', document.querySelector('#txt_title').value);
        formData.append('place', document.querySelector('#txt_place').value);
        formData.append('description', document.querySelector('#txt_description').value);
        formData.append('link', document.querySelector('#txt_link').value);
        formData.append('photo', document.querySelector('#file_photo').files[0]);

        //Se recogen los documentos cargados
        let cont_files=0;
        document.querySelectorAll(".training_files").forEach((temp_input,key) =>{
            formData.append(`files_${key}`,temp_input.files[0]);
            cont_files++;
        })
        formData.append('cont_files',cont_files);

        axios.post('/admin/training-save',formData,config)
        .then(response =>{

            let resp = response.data;
            if(resp.success == 1){
                ths.clearFields(ths.general_data.fields_modal.total);
                ths.visualisity_loading('data','form_add');
                $('#modal_add').modal('hide');
                ths.getPage(1);
                ths.notify('Éxito', 'Registro realizado exitosamente ', 'success');

            }
        })
        .catch(error =>{
            ths.visualisity_loading('data','form_add');
            console.log(error);
            if(error.response.status && error.response.status == 422){
                ths.alert("Los datos proporcionados no son válidos",error.response.data.errors, 'error','validate');
            }else{
                ths.alert('Error', 'A ocurrido un error, intente de nuevo', 'error');
            }
        });

    }
}

$(".icon-file").on('click',function(){
    let input= this.dataset.input;
    $('#'+input).click();
})

function uploadImage(_event){
    let file = _event.files[0];
    let isValid = ths.validateImage(file);

    if(isValid){
        document.querySelector("#name_"+_event.id).innerHTML = file.name;
    }else{
        document.querySelector("#name_"+_event.id).innerHTML =""
        document.querySelector("#"+_event.id).value="";
    }

}

function updateImage(_event){
    let file = _event.files[0];
    let isValid = ths.validateImage(file);

    if(isValid){
        document.querySelector("#name_"+_event.id).innerHTML = file.name;
        document.querySelector("#file_photo_view_edit").src= ""
    }else{
        document.querySelector("#name_"+_event.id).innerHTML =""
        document.querySelector("#"+_event.id).value="";
    }

}

function validateImage(file){


    // Se valida el peso
    if(((file.size/1024)/1024) < 5){
        // Se valida el tipo de archivo
        if(file.type == "image/png" || file.type == "image/jpg" || file.type == "image/jpeg"){
            return true;
        }else{
            ths.notify('Aviso', 'Seleccione archivo JPG o PNG.', 'info');
            return false;
        }
    }else{
        ths.notify('Aviso', 'El archivo supera el limite de 5 MB.', 'info');
        return false;
    }
}

function viewRecord(_id){

    let element = {};

    for (const item of ths.general_data.data.data) {
        if(item.id == _id){
            element = item;
            break;
        }
    }

    $('#select_location_view').empty();
    let location_selected = [];
    element.bucket_location.forEach(temp_location => {

        let {location} = temp_location;
        if(location_selected.indexOf(location.id) == -1){
            const option = document.createElement('option');
            option.value = location.id
            option.text = location.name;
            option.disabled = true;
            option.selected = true;
            document.querySelector("#select_location_view").appendChild(option);
            location_selected.push(location.id);
        }

    })
    $('#select_location_view').selectpicker('refresh');

    $('#select_subgroup_view').empty();
    element.bucket_location.forEach(temp_location => {
        let {subgroup} = temp_location;
        const option = document.createElement('option');
        option.value = subgroup.id
        option.text = subgroup.name;
        option.disabled = true;
        option.selected = true;
        document.querySelector("#select_subgroup_view").appendChild(option);
    })

    $('#select_subgroup_view').selectpicker('refresh');

    document.querySelector("#txt_expiration_date_view").value= element.expiration_date;
    document.querySelector("#txt_date_start_view").value= element.start_date;
    document.querySelector("#txt_time_start_view").value= element.start_time;
    document.querySelector("#txt_title_view").value= element.title;
    document.querySelector("#txt_place_view").value= element.place;
    document.querySelector("#txt_description_view").value= element.description;
    document.querySelector("#txt_link_view").value= element.link;
    document.querySelector("#file_photo_view").src= ths.url_base+'/'+element.photo;

    // Se agregan los documentos que tiene cargados el comunicado
    if(element.files.length > 0){
        let files_HTML = `<ol class="list-group list-group-numbered">
                        `;
        element.files.forEach(file =>{
            files_HTML += `
                            <li class="list-group-item">
                                <a target="_blank" href="${url_base+'/'+file.file}"><i class="fas fa-paperclip"></i>&nbsp; ${file.name}</a>
                            </li>
            `
        })
        files_HTML+= '</ol>';

        document.querySelector('#list_files_view').innerHTML = files_HTML;
    }else{
        document.querySelector('#list_files_view').innerHTML= ths.noDataFound({text:'No hay ningún documento cargado'});
    }




    $("#modal_view").modal('show');

}

function editRecord(_id){

    let element = {};
    ths.form_data_image_gallery = new FormData();

    for (const item of ths.general_data.data.data) {
        if(item.id == _id){
            element = item;
             break;
        }
    }

    if(ths.general_data.locations.length > 0){
        $('#select_location_edit').empty();
        ths.general_data.locations.forEach(location => {
            const option = document.createElement('option');
            option.value = location.id
            option.text = location.name;
            document.querySelector("#select_location_edit").appendChild(option);
        })
        let selected_locations=[];
        element.bucket_location.forEach(bucket => {
            selected_locations.push(""+bucket.location.id);
        });
        $('#select_location_edit').selectpicker('refresh');
        $('#select_location_edit').selectpicker('val',selected_locations);

        /* Subgrupos */
        $('#select_subgroup_edit').empty();
        element.bucket_location.forEach(temp_location => {
            let {subgroup} = temp_location;
            const option = document.createElement('option');
            option.value = subgroup.id
            option.text = subgroup.name;
            option.selected = true;
            document.querySelector("#select_subgroup_edit").appendChild(option);
        })

        $('#select_subgroup_edit').selectpicker('refresh');
    }


    document.querySelector("#training_id").value= element.id;
    document.querySelector("#txt_date_start_edit").value= element.start_date;
    document.querySelector("#txt_time_start_edit").value= element.start_time;
    document.querySelector("#txt_title_edit").value= element.title;
    document.querySelector("#txt_place_edit").value= element.place;
    document.querySelector("#txt_description_edit").value= element.description;
    document.querySelector("#txt_link_edit").value= element.link;
    document.querySelector("#txt_expiration_date_edit").value= element.expiration_date;

    document.querySelector("#file_photo_edit").value=""
    document.querySelector("#name_file_photo_edit").innerHTML=""
    document.querySelector("#file_photo_view_edit").src= ths.url_base+'/'+element.photo;

    // Se agregan las imagenes que se tienen cargadas
    document.querySelector('#list_files_edit').innerHTML="";

    // Se agregan los documentos que tiene cargados
    if(element.files.length > 0){
        let files_HTML = `<ol class="list-group mb-3">
                        `;
        element.files.forEach(file =>{
            files_HTML += `
                            <li class="list-group-item" id="list-group-file_${file.id}">
                                <div class="row">
                                    <div class="col-md-11 col-sm-11">
                                        <a target="_blank" href="${url_base+'/'+file.file}"><i class="fas fa-paperclip"></i>&nbsp; ${file.name}</a>
                                    </div>
                                    <div class="col-md-1 col-sm-1 text-center">
                                        <span id="data_delete_file_${file.id}" class="delete-item" onclick="deleteUploadedDoc(this)" data-row_id="list-group-file_${file.id}" data-file="${file.id}" data-communique="${element.id}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar"><i class="fas fa-trash"></i></span>
                                        <div id="loading_delete_file_${file.id}" style="display:none;" class="spinner-border spinner-size-sm text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>

                            </li>
            `
        })
        files_HTML+= '</ol>';

        document.querySelector('#list_files_edit').innerHTML = files_HTML;
    }else{
        document.querySelector('#list_files_edit').innerHTML= ths.noDataFound({text:'No hay ningún documento cargado'});
    }

    $("#modal_edit").modal('show');

}

function updateRecord(){
    let isValid = ths.validateFields(ths.general_data.fields_modal.edit_required);
    const config = { headers: { 'Content-Type': 'multipart/form-data' } };

    if(isValid == true){

        ths.visualisity_loading('loading','form_edit');

        let formData = new FormData();
        formData.append('training', document.querySelector('#training_id').value);
        $("#select_location_edit").val().forEach(location =>{
            formData.append('locations[]',location );
        })
        $("#select_subgroup_edit").val().forEach(location =>{
            formData.append('subgroups[]',location );
        })

        formData.append('expiration_date', document.querySelector('#txt_expiration_date_edit').value);
        formData.append('start_date', document.querySelector('#txt_date_start_edit').value);
        formData.append('start_time', document.querySelector('#txt_time_start_edit').value);
        formData.append('title', document.querySelector('#txt_title_edit').value);
        formData.append('place', document.querySelector('#txt_place_edit').value);
        formData.append('description', document.querySelector('#txt_description_edit').value);
        formData.append('link', document.querySelector('#txt_link_edit').value);
        formData.append('photo', document.querySelector('#file_photo_edit').files[0]);

        //Se recogen los documentos cargados
        let cont_files=0;
        document.querySelectorAll(".training_files").forEach((temp_input,key) =>{
            formData.append(`files_${key}`,temp_input.files[0]);
            cont_files++;
        })
        formData.append('cont_files',cont_files);

        axios.post('/admin/training-edit',formData,config)
        .then(response =>{

            let resp = response.data;
            if(resp.success == 1){
                ths.clearFields(ths.general_data.fields_modal.total,"_edit");
                ths.visualisity_loading('data','form_edit');
                $('#modal_edit').modal('hide');
                ths.getPage();
                ths.notify('Éxito', 'Registro realizado exitosamente ', 'success');

            }
        })
        .catch(error =>{
            ths.visualisity_loading('data','form_edit');
            console.log(error);
            if(error.response.status && error.response.status == 422){
                ths.alert("Los datos proporcionados no son válidos",error.response.data.errors, 'error','validate');
            }else{
                ths.alert('Error', 'A ocurrido un error, intente de nuevo', 'error');
            }
        });

    }
}

function deleteRecord(_id){

    Swal.fire({
        title: 'Quieres eliminar el registro?',
        text: "Se eliminará el registro!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {

            ths.visualisity_loading('loading','delete_'+_id);

            axios.get('/admin/training-delete/'+_id)
            .then(response =>{
                let resp = response.data;
                if(resp.success == 1){
                    ths.getPage(1);
                    ths.notify('Éxito', resp.message, 'success');
                }else{
                    ths.alert('Error',resp.message, 'error');
                }
                ths.visualisity_loading('data','delete_'+_id);
            })
            .catch(error =>{
                ths.visualisity_loading('data','delete_'+_id);
                console.log(error);
                ths.alert('Error', 'A ocurrido un error, intente de nuevo', 'error');

            })
        }
    })

}

function addFiles(_component="new"){
    let element_id = ths.generateId();

    let row_HTML = `
        <div class="col-md-12 mb-3" id="div_file_${element_id}">
            <div class="input-file" id="input-file_${element_id}">
                <label for="" class="label-file">Seleccionar archivo</label>
                <span class="icon-file" onclick="selectDocument(this)" data-input="${element_id}" data-file_type="document"><i class="fas fa-file-upload"></i></span>
                <span class="name-file" id="name_${element_id}">No se eligió ningún archivo</span>
                <span class="delete-item" onclick="deleteDocPreUpdate(this)" data-row_id="div_file_${element_id}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar"><i class="fas fa-trash"></i></span>
                <input class="form-control d-none training_files" type="file" accept="application/.xls,.doc,.xlsx,.docx,.pdf" id="${element_id}" placeholder="Imagen" onchange="uploadDocument(this)" required>
            </div>
        </div>
    `;



    if(_component == 'new'){
        if(document.querySelector("#list_files").childNodes[0].className == "col no-found-data"){
            document.querySelector("#list_files").innerHTML = "";
        }
        $("#list_files").append(row_HTML);
    }else{
        if(document.querySelector("#list_files_edit").childNodes[0].className == "col no-found-data"){
            document.querySelector("#list_files_edit").innerHTML = "";
        }
        $("#list_files_edit").append(row_HTML);
    }

}

function selectDocument(_event){
    $("#"+_event.dataset.input).click();
}

function uploadDocument(_event){
    let file = _event.files[0];
    let isValid = ths.validateDocument(file);

    if(isValid){
        document.querySelector("#name_"+_event.id).innerHTML = file.name;
    }else{
        document.querySelector("#name_"+_event.id).innerHTML ="No se eligió ningún archivo"
        document.querySelector("#"+_event.id).value="";
    }
}

function validateDocument(file){
    let extension = ['xls','doc','xlsx','docx','pdf'];

    let nameFile = file.name.split('.');
    // Se valida el peso
    if(((file.size/1024)/1024) < 5){
        // Se valida el tipo de archivo
        if(extension.indexOf(nameFile[1]) != -1){
            return true;
        }else{
            ths.notify('Aviso', 'Seleccione archivo PDF, Word o Excel.', 'info');
            return false;
        }
    }else{
        ths.notify('Aviso', 'El archivo supera el limite de 5 MB.', 'info');
        return false;
    }
}

function deleteDocPreUpdate(_event){
    document.querySelector('#'+_event.dataset.row_id).remove();

    if(document.querySelector('#list_files').children.length == 0){
        document.querySelector("#list_files").innerHTML = ths.noDataFound({text:'No hay ningún documento cargado'});
    }
}

function deleteUploadedDoc(_event){

    let file_id= _event.dataset.file;
    let record_id= _event.dataset.communique;



    Swal.fire({
        title: 'Quieres eliminar el archivo?',
        text: "Se eliminará el archivo!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {

        if (result.isConfirmed) {

            ths.visualisity_loading('loading','delete_file_'+file_id);

            axios.get(`/admin/training-delete-file/${file_id}/${record_id}`)
            .then(response =>{
                let resp = response.data;
                if(resp.success == 1){
                    ths.getPage(1);
                    document.querySelector('#'+_event.dataset.row_id).remove();
                    ths.notify('Éxito', resp.message, 'success');
                }else{
                    ths.alert('Error',resp.message, 'error');
                }
                ths.visualisity_loading('data','delete_file_'+file_id);
            })
            .catch(error =>{
                ths.visualisity_loading('data','delete_file_'+file_id);
                console.log(error);
                ths.alert('Error', 'A ocurrido un error, intente de nuevo', 'error');

            })
        }
    })

}
