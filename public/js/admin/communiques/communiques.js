var ths = this;
var general_data = {
    data:{},
    headers:{
        limit:10,
        page:1,
        order_by:'desc',
        search:{
            isSearch:false,
            text:""
        }
    },
    fields_modal:{
        required:['select_location','txt_expiration_date','select_priority','txt_title','file_photo'],
        edit_required:['select_location_edit','txt_expiration_date_edit','select_priority_edit','txt_title_edit',],
        total:['select_location','select_subgroup','txt_expiration_date','select_priority','txt_title','txt_description','file_photo','file_video','txt_link'],
    },
    locations:{},
    subgroups:{},

}

function getPage(_page=1){

    ths.general_data.headers.page = _page;

    ths.visualisity_loading('loading','list_council');

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
        ths.visualisity_loading('data','list_council');
    })
    .catch( error =>{
        ths.visualisity_loading('data','list_council');
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

            expiration_type = item.expiration_date >= current_date ? "expiration-success" : "expiration-danger";

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
                            <td>${item.priority != null ? item.priority : ''}</td>
                            <td>${item.expiration_date} <i class="fas fa-history ${expiration_type}"></i></td>
                            <td>${item.created_at}</td>
                            <td class="text-center">
                                <div id="data_delete_${item.id}">
                                    <button class="btn btn-light btn-action-primary" onclick="viewCommunique(${item.id})" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ver"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-light btn-action-success" onclick="editCommunique(${item.id})" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Editar"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-light btn-action-danger" onclick="deleteCommunique(${item.id})" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar"><i class="fas fa-trash"></i></button>
                                    <button class="btn btn-light btn-action-success" onclick="sendReminder(${item.id})" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Recordatorio"><i class="fas fa-bell"></i></button>
                                </div>
                                <div id="loading_delete_${item.id}" style="display:none;" class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>

                            </td>
                        </tr>`

            _from++;
        });

        document.querySelector('#data_list_council').innerHTML = _rows_HTML;
        ths.paginate_control(ths.general_data.data);

    }else{

        document.querySelector('#data_list_council').innerHTML = ths.noDataFound({
            content:"table",
            colspan:"7"
        });

    }
}

function newCommunique(){
    ths.loadLocation();
    ths.clearFields(ths.general_data.fields_modal.total);
    document.querySelector("#list_files").innerHTML = ths.noDataFound({text:'No hay ningún documento cargado'});
    $('#modal_add').modal('show');
}

function showList(val) {

    var select="",type="";
    if(val == 0){
        select = document.getElementById("select_location");
        type="location";
    }else if(val == 1){
        select = document.getElementById("select_location_edit");
        type="location";
    }else if(val == 2){
        select = document.getElementById("select_subgroup");
        type="subgroup";
    }else if(val == 3){
        select = document.getElementById("select_subgroup_edit");
        type="subgroup";
    }

    var div_user = document.getElementById("user-list-container")

    const selectedOptions = select.selectedOptions
    const arrayValues = []

    for (let i = 0; i < selectedOptions.length; i++) {
        arrayValues.push(parseInt(selectedOptions[i].value))
    }

    axios.post('/admin/communiques/get-list-user', {
        locations: arrayValues,
        type:type
    })
    .then(response =>{
        let html = `<ol class="list-group list-group-numbered">`
        Object.values(response.data).forEach((item, key) =>{

            html += '<li class="list-group-item d-flex justify-content-between align-items-start">' +
                        item.email
                    +'</li>'
        })
        html += `</ol>`
        div_user.innerHTML = html;
        $('#modal_user_list').modal('show')
    })
    .catch(error =>{
        ths.visualisity_loading('data','form_add');
        console.log(error);
        if(error.response.status && error.response.status == 422){
            ths.alert('Error', 'A ocurrido un error, intente de nuevo', 'error');
        }else{
            ths.alert('Error', 'A ocurrido un error, intente de nuevo', 'error');
        }
    });
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

async function preLoadLoacation(){
    let locations = await ths.getLocations();
    ths.general_data.locations = locations;
}
async function preLoadSubgroups(){
    let subgroups = await ths.getSubgroups();
    ths.general_data.subgroups = subgroups;
}

$(".icon-file").on('click',function(){
    let input= this.dataset.input;
    $('#'+input).click();
})

function uploadImage(_event,_type="image"){

    let file = _event.files[0];
    let isValid;

    if(_type == "image"){
        isValid = ths.validateImage(file);
    }else if(_type == "video"){
        isValid = ths.validateVideo(file);
    }

    if(isValid){
        document.querySelector("#name_"+_event.id).innerHTML = file.name;
    }else{
        document.querySelector("#name_"+_event.id).innerHTML =""
        document.querySelector("#"+_event.id).value="";
    }

}

function updateImage(_event,_type="image"){
    let file = _event.files[0];
    let isValid;
    if(_type == "image"){
        isValid = ths.validateImage(file);
    }else if(_type == "video"){
        isValid = ths.validateVideo(file);
    }

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

function validateVideo(file){

    // Se valida el peso en MB
    if(((file.size/1024)/1024) < 1500){
        // Se valida el tipo de archivo
        if(file.type == "video/mp4"){
            return true;
        }else{
            ths.notify('Aviso', 'Seleccione archivo MP4.', 'info');
            return false;
        }
    }else{
        ths.notify('Aviso', 'El archivo supera el limite de 1.5 GB.', 'info');
        return false;
    }
}

function saveCommunique(){

    let isValid = ths.validateFields(ths.general_data.fields_modal.required);
    const config = { headers: { 'Content-Type': 'multipart/form-data' } };

    if(isValid == true){

        ths.visualisity_loading('loading','form_add');

        let formData = new FormData();
        let cont_files=0;

        formData.append('communique_type', document.querySelector('#communique_type').value);
        /* formData.append('locations', ); */
        formData.append('expiration_date', document.querySelector('#txt_expiration_date').value);
        formData.append('priority', document.querySelector('#select_priority').value);
        formData.append('title', document.querySelector('#txt_title').value);
        formData.append('description', document.querySelector('#txt_description').value);
        formData.append('photo', document.querySelector('#file_photo').files[0]);
        formData.append('video', document.querySelector('#file_video').files[0]);
        formData.append('link', document.querySelector('#txt_link').value);

        $("#select_location").val().forEach(location =>{
            formData.append('locations[]',location );
        })

        $("#select_subgroup").val().forEach(subgroup =>{
            formData.append('subgroups[]',subgroup );
        })

        //Se recogen los documentos cargados
        document.querySelectorAll(".communique_files").forEach((temp_input,key) =>{
            formData.append(`files_${key}`,temp_input.files[0]);
            cont_files++;
        })
        formData.append('cont_files',cont_files);

        axios.post('/admin/communiques/save',formData,config)
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

function viewCommunique(_id){

    let communique = {};

    for (const item of ths.general_data.data.data) {
        if(item.id == _id){
             communique = item;
             break;
        }
    }

    $('#select_location_view').empty();
    let location_selected = [];
    communique.bucket_location.forEach(temp_location => {

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
    communique.bucket_location.forEach(temp_location => {
        let {subgroup} = temp_location;
        const option = document.createElement('option');
        option.value = subgroup.id
        option.text = subgroup.name;
        option.disabled = true;
        option.selected = true;
        document.querySelector("#select_subgroup_view").appendChild(option);
    })

    $('#select_subgroup_view').selectpicker('refresh');

    document.querySelector("#txt_expiration_date_view").value= communique.expiration_date;
    document.querySelector("#select_priority_view").value= communique.priority;
    document.querySelector("#txt_title_view").value= communique.title;
    document.querySelector("#txt_description_view").value= communique.description;
    document.querySelector("#file_photo_view").src= ths.url_base+'/'+communique.photo;

    document.querySelector("#error_file_video_view").classList.add('d-none');
    document.querySelector("#file_video_view").classList.remove('d-none');
    if(communique.video != null){
        document.querySelector("#file_video_view").src= ths.url_base+'/'+communique.video;
        document.querySelector("#file_video_view").classList.remove('d-none');
    }else{
        document.querySelector("#error_file_video_view").classList.remove('d-none');
        document.querySelector("#file_video_view").classList.add('d-none');
    }

    document.querySelector("#txt_link_view").value= communique.link;

    // Se agregan los documentos que tiene cargados el comunicado
    if(communique.files.length > 0){
        let files_HTML = `<ol class="list-group list-group-numbered">
                        `;
        communique.files.forEach(file =>{
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

function editCommunique(_id){

    let communique = {};

    for (const item of ths.general_data.data.data) {
        if(item.id == _id){
             communique = item;
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
        communique.bucket_location.forEach(bucket => {
            selected_locations.push(""+bucket.location.id);
        });
        $('#select_location_edit').selectpicker('refresh');
        $('#select_location_edit').selectpicker('val',selected_locations);

        /* Subgrupos */
        $('#select_subgroup_edit').empty();
        communique.bucket_location.forEach(temp_location => {
            let {subgroup} = temp_location;
            const option = document.createElement('option');
            option.value = subgroup.id
            option.text = subgroup.name;
            option.selected = true;
            document.querySelector("#select_subgroup_edit").appendChild(option);
        })

        $('#select_subgroup_edit').selectpicker('refresh');
    }

    document.querySelector("#communique_id").value= communique.id;
    document.querySelector("#txt_expiration_date_edit").value= communique.expiration_date;
    document.querySelector("#select_priority_edit").value= communique.priority;
    document.querySelector("#txt_title_edit").value= communique.title;
    document.querySelector("#txt_description_edit").value= communique.description;
    document.querySelector("#file_photo_edit").value="";
    document.querySelector("#name_file_photo_edit").innerHTML="";
    document.querySelector("#file_video_view_edit").src= ths.url_base+'/'+communique.video;
    document.querySelector("#file_photo_view_edit").src= ths.url_base+'/'+communique.photo;


    document.querySelector("#error_file_video_edit").classList.add('d-none');
    document.querySelector("#file_video_view_edit").classList.remove('d-none');
    if(communique.video != null){
        document.querySelector("#file_video_view_edit").src= ths.url_base+'/'+communique.video;
        document.querySelector("#error_file_video_edit").classList.add('d-none');
        document.querySelector("#file_video_view_edit").classList.remove('d-none');
    }else{
        document.querySelector("#error_file_video_edit").classList.remove('d-none');
        document.querySelector("#file_video_view_edit").classList.add('d-none');
    }
    document.querySelector("#txt_link_edit").value= communique.link;



    // Se agregan los documentos que tiene cargados el comunicado
    if(communique.files.length > 0){
        let files_HTML = `<ol class="list-group mb-3">
                        `;
        communique.files.forEach(file =>{
            files_HTML += `
                            <li class="list-group-item" id="list-group-file_${file.id}">
                                <div class="row">
                                    <div class="col-md-11 col-sm-11">
                                        <a target="_blank" href="${url_base+'/'+file.file}"><i class="fas fa-paperclip"></i>&nbsp; ${file.name}</a>
                                    </div>
                                    <div class="col-md-1 col-sm-1 text-center">
                                        <span id="data_delete_file_${file.id}" class="delete-item" onclick="deleteUploadedDoc(this)" data-row_id="list-group-file_${file.id}" data-file="${file.id}" data-communique="${communique.id}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar"><i class="fas fa-trash"></i></span>
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

function updateCommunique(){
    let isValid = ths.validateFields(ths.general_data.fields_modal.edit_required);
    const config = { headers: { 'Content-Type': 'multipart/form-data' } };

    if(isValid == true){

        ths.visualisity_loading('loading','form_edit');

        let formData = new FormData();
        formData.append('communique_type', document.querySelector('#communique_type').value);
        formData.append('communique', document.querySelector('#communique_id').value);
        /* formData.append('location', document.querySelector('#select_location_edit').value); */
        formData.append('expiration_date', document.querySelector('#txt_expiration_date_edit').value);
        formData.append('priority', document.querySelector('#select_priority_edit').value);
        formData.append('title', document.querySelector('#txt_title_edit').value);
        formData.append('description', document.querySelector('#txt_description_edit').value);
        formData.append('photo', document.querySelector('#file_photo_edit').files[0]);
        formData.append('video', document.querySelector('#file_video_edit').files[0]);
        formData.append('link', document.querySelector('#txt_link_edit').value);
        $("#select_location_edit").val().forEach(location =>{
            formData.append('locations[]',location );
        })
        $("#select_subgroup_edit").val().forEach(location =>{
            formData.append('subgroups[]',location );
        })

        //Se recogen los documentos cargados
        let cont_files=0;
        document.querySelectorAll(".communique_files").forEach((temp_input,key) =>{
            formData.append(`files_${key}`,temp_input.files[0]);
            cont_files++;
        })
        formData.append('cont_files',cont_files);

        axios.post('/admin/communiques/edit',formData,config)
        .then(response =>{

            let resp = response.data;
            if(resp.success == 1){
                /* let server_photo = resp.data.photo.split('/');
                let filename = server_photo[server_photo.length-1];
                ths.uploadImgServerGrupoDMI(document.querySelector('#file_photo_edit').files[0], filename); */


                ths.clearFields(ths.general_data.fields_modal.total,"_edit");
                ths.visualisity_loading('data','form_edit');
                $('#modal_edit').modal('hide');
                ths.getPage(1);
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

function deleteCommunique(_id){

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

            axios.get('/admin/communiques/delete/'+_id)
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

function sendReminder(_id){

    Swal.fire({
        title: '¿Quieres enviar un recordatorio?',
        text: "Se enviará un recordatorio",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '¡Sí, enviar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {

            ths.visualisity_loading('loading','delete_'+_id);

            axios.get('/admin/communiques/send-reminder/'+_id)
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
                <input class="form-control d-none communique_files" type="file" accept="application/.xls,.doc,.xlsx,.docx,.pdf" id="${element_id}" placeholder="Imagen" onchange="uploadDocument(this)" required>
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
    let communique_id= _event.dataset.communique;



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

            axios.get(`/admin/communiques/delete-file/${file_id}/${communique_id}`)
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

$(document).on('hidden.bs.modal', function (event) {
    document.querySelector(".public-video").src="";
})


function uploadImgServerGrupoDMI(_img){

    var data = new FormData();
    data.append('fileToUpload',_img);

    const config = { headers: { 'Content-Type': 'multipart/form-data' } };

    axios.post('https://www.grupodmi.com.mx/intranet/subir_imagen.php',data,config)
        .then(response =>{
            //console.log(response);

        })
        .catch(error =>{
            //console.log(error);
        });

}
