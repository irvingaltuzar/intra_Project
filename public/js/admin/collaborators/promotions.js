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
        required:['file_photo','select_collaborator','select_report_to','txt_new_position_company','txt_expiration_date','txt_message'],
        edit_required:['select_collaborator_edit','select_report_to_edit','txt_new_position_company_edit','txt_expiration_date_edit','txt_message_edit'],
        total:['file_photo','select_collaborator','select_report_to','txt_new_position_company','txt_expiration_date','txt_message'],
    },
    users:{}

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

async function loadUsers(){

    let users = await ths.getPromotionUsers();
    if(users.length > 0){

        document.querySelector("#select_collaborator").innerHTML="";
        const option = document.createElement('option');
        option.text = "Selecciona una opción";
        option.selected = true;
        option.value = "";
        option.disabled = true;
        document.querySelector("#select_collaborator").appendChild(option);

        users.forEach(user => {
            const option = document.createElement('option');
            option.value = (user.name+' '+user.last_name);
            option.text = (user.name+' '+user.last_name);
            document.querySelector("#select_collaborator").appendChild(option);
        })

        document.querySelector("#select_report_to").innerHTML="";
        const option1 = document.createElement('option');
        option1.text = "Selecciona una opción";
        option1.selected = true;
        option1.value = "";
        option1.disabled = true;
        document.querySelector("#select_report_to").appendChild(option1);

        users.forEach(user => {
            const option1 = document.createElement('option');
            option1.value = user.complete_name
            option1.text = (user.name+' '+user.last_name);
            document.querySelector("#select_report_to").appendChild(option1);
        })
    }


}

async function preLoadUsers(){
    let users = await ths.getPromotionUsers();
    ths.general_data.users = users;
}

function addList(_list,_from){
    let _rows_HTML = ``;
    let expiration_type="";

    if(_list.length > 0){
        _list.forEach((item,key) =>{

            expiration_type = item.expiration_date >= current_date ? "expiration-success" : "expiration-danger";

            _rows_HTML +=`<tr>
                            <th scope="row">${_from}</th>
                            <td>${item.user_name}</td>
                            <td>${item.new_position_company}</td>
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

function newRecord(){
    ths.loadUsers();
    ths.clearFields(ths.general_data.fields_modal.total);
    $('#modal_add').modal('show');
}

function saveRecord(){
    let isValid = ths.validateFields(ths.general_data.fields_modal.required);
    const config = { headers: { 'Content-Type': 'multipart/form-data' } };

    if(isValid == true){

        ths.visualisity_loading('loading','form_add');

        let temp_user_name = document.querySelector('#select_collaborator').value;
        let location = "";

        ths.general_data.users.forEach(user =>{
            if(user.complete_name == temp_user_name){
                location = user.location;
            }
        })


        let formData = new FormData();
        formData.append('vw_users_usuario', temp_user_name);
        formData.append('vw_users_usuario_top', document.querySelector('#select_report_to').value);
        formData.append('new_position_company', document.querySelector('#txt_new_position_company').value);
        formData.append('photo', document.querySelector('#file_photo').files[0]);
        formData.append('message', document.querySelector('#txt_message').value);
        formData.append('location', location);
        formData.append('expiration_date', document.querySelector('#txt_expiration_date').value);

        axios.post('/admin/collaborators/promotion-save',formData,config)
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

function viewRecord(_id){

    let element = {};

    for (const item of ths.general_data.data.data) {
        if(item.id == _id){
            element = item;
            break;
        }
    }

    document.querySelector("#file_photo_view").src= ths.url_base+'/'+element.photo;
    document.querySelector("#txt_collaborator_view").value= element.user_name;
    document.querySelector("#txt_report_to_view").value= element.user_top_name;
    document.querySelector("#txt_new_position_company_view").value= element.new_position_company;
    document.querySelector("#txt_expiration_date_view").value= element.expiration_date;
    document.querySelector("#txt_message_view").value= element.message;


    $("#modal_view").modal('show');

}

function editRecord(_id){
    ths.clearFields(ths.general_data.fields_modal.edit_required);

    let element = {};

    for (const item of ths.general_data.data.data) {
        if(item.id == _id){
            element = item;
             break;
        }
    }

    if(ths.general_data.users.length > 0){

        document.querySelector("#select_collaborator_edit").innerHTML="";
        const option = document.createElement('option');
        option.text = "Selecciona una opción";
        option.selected = true;
        option.value = "";
        option.disabled = true;
        document.querySelector("#select_collaborator_edit").appendChild(option);

        ths.general_data.users.forEach(user => {
            const option = document.createElement('option');
            option.value = user.name+' '+user.last_name
            option.text = user.name+' '+user.last_name;
            document.querySelector("#select_collaborator_edit").appendChild(option);
        })

        document.querySelector("#select_report_to_edit").innerHTML="";
        const option1 = document.createElement('option');
        option1.text = "Selecciona una opción";
        option1.selected = true;
        option1.value = "";
        option1.disabled = true;
        document.querySelector("#select_report_to_edit").appendChild(option1);

        ths.general_data.users.forEach(user => {
            const option1 = document.createElement('option');
            option1.value = (user.name+' '+user.last_name);
            option1.text = (user.name+' '+user.last_name);
            document.querySelector("#select_report_to_edit").appendChild(option1);
        })
    }

    document.querySelector("#promotion_id").value= element.id;
    document.querySelector("#file_photo_view_edit").src = ths.url_base+'/'+element.photo;
    document.querySelector("#file_photo_edit").value="";
    document.querySelector("#select_collaborator_edit").value=element.user_name
    document.querySelector("#select_report_to_edit").value=element.user_top_name;
    document.querySelector("#txt_new_position_company_edit").value= element.new_position_company;
    document.querySelector("#txt_expiration_date_edit").value= element.expiration_date;
    document.querySelector("#txt_message_edit").value= element.message;

    $("#modal_edit").modal('show');

}

function updateRecord(){
    let isValid = ths.validateFields(ths.general_data.fields_modal.edit_required);
    const config = { headers: { 'Content-Type': 'multipart/form-data' } };

    if(isValid == true){

        ths.visualisity_loading('loading','form_edit');
        let temp_user_name = document.querySelector('#select_collaborator_edit').value;
        let location = "";

        ths.general_data.users.forEach(user =>{
            if(user.complete_name == temp_user_name){
                location = user.location;
            }
        })

        let formData = new FormData();
        formData.append('promotion_id', document.querySelector('#promotion_id').value);
        formData.append('vw_users_usuario', temp_user_name);
        formData.append('vw_users_usuario_top', document.querySelector('#select_report_to_edit').value);
        formData.append('new_position_company', document.querySelector('#txt_new_position_company_edit').value);
        formData.append('expiration_date', document.querySelector('#txt_expiration_date_edit').value);
        formData.append('message', document.querySelector('#txt_message_edit').value);
        formData.append('location', location);
        formData.append('photo', document.querySelector('#file_photo_edit').files[0]);

        axios.post('/admin/collaborators/promotion-edit',formData,config)
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

            axios.get('/admin/collaborators/promotion-delete/'+_id)
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
