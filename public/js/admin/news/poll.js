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
        required:['select_location','txt_link','txt_expiration_date','txt_title','file_photo'],
        edit_required:['select_location_edit','txt_link_edit','txt_expiration_date_edit','txt_title_edit'],
        total:['select_location','select_subgroup','txt_link','txt_expiration_date','txt_title','txt_description','file_photo'],
    },
    locations:{},
    subgroups:{},

}

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
    var div_user = val == 0 ? document.getElementById("user-list-container") : document.getElementById("user-list-container-edit")

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
        val == 0 ? $('#modal_user_list_posting').modal('show') : $('#modal_user_list_posting_edit').modal('show')
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
                            <td>${item.expiration_date} <i class="fas fa-history ${expiration_type}"></i></td>
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
            colspan:"6"
        });

    }
}

async function preLoadLocations(){
    let locations = await ths.getLocations();
    ths.general_data.locations = locations;
}
async function preLoadSubgroups(){
    let subgroups = await ths.getSubgroups();
    ths.general_data.subgroups = subgroups;
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

function newRecord(){
    ths.clearFields(ths.general_data.fields_modal.total);
    ths.loadLocation();
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
        formData.append('title', document.querySelector('#txt_title').value);
        formData.append('photo', document.querySelector('#file_photo').files[0]);
        formData.append('description', document.querySelector('#txt_description').value);
        formData.append('link', document.querySelector('#txt_link').value);
        formData.append('expiration_date', document.querySelector('#txt_expiration_date').value);

        axios.post('/admin/news/poll-save',formData,config)
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

    document.querySelector("#txt_title_view").value= element.title;
    document.querySelector("#file_photo_view").src= ths.url_base+'/'+element.photo;
    document.querySelector("#txt_description_view").value= element.description;
    document.querySelector("#txt_link_view").value= element.link;
    document.querySelector("#txt_expiration_date_view").value= element.expiration_date;

    $("#modal_view").modal('show');

}

function editRecord(_id){

    let element = {};

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

    document.querySelector("#poll_id").value= element.id;
    document.querySelector("#txt_title_edit").value= element.title;
    document.querySelector("#txt_description_edit").value= element.description;
    document.querySelector("#txt_link_edit").value= element.link;
    document.querySelector("#txt_expiration_date_edit").value= element.expiration_date;

    document.querySelector("#file_photo_edit").value=""
    document.querySelector("#name_file_photo_edit").innerHTML=""
    document.querySelector("#file_photo_view_edit").src= ths.url_base+'/'+element.photo;

    $("#modal_edit").modal('show');

}

async function updateRecord(){
    let isValid = ths.validateFields(ths.general_data.fields_modal.edit_required);
    const config = { headers: { 'Content-Type': 'multipart/form-data' } };

    if(isValid == true){

        ths.visualisity_loading('loading','form_edit');

        let formData = new FormData();
        $("#select_location_edit").val().forEach(location =>{
            formData.append('locations[]',location );
        })
        $("#select_subgroup_edit").val().forEach(location =>{
            formData.append('subgroups[]',location );
        })
        formData.append('poll', document.querySelector('#poll_id').value);
        formData.append('title', document.querySelector('#txt_title_edit').value);
        formData.append('description', document.querySelector('#txt_description_edit').value);
        formData.append('link', document.querySelector('#txt_link_edit').value);
        formData.append('expiration_date', document.querySelector('#txt_expiration_date_edit').value);
        formData.append('photo', document.querySelector('#file_photo_edit').files[0]);

        await axios.post('/admin/news/poll-edit',formData,config)
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

            axios.get('/admin/news/poll-delete/'+_id)
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
