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
        required:['txt_name'],
        edit_required:['txt_name_edit'],
        total:['txt_name'],
    },
    locations:{},
    matriz_permissions:[],

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

function addList(_list,_from){
    let _rows_HTML = ``;
    let expiration_type="";

    if(_list.length > 0){

        _list.forEach((item,key) =>{

            _rows_HTML +=`<tr>
                            <th scope="row">${_from}</th>
                            <td>${item.name}</td>
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
            colspan:"4"
        });

    }
}

function newRecord(){
    ths.clearFields(ths.general_data.fields_modal.total);
    ths.loadSubSections();
    document.querySelector("#list_permissions").innerHTML = ths.noDataFound({text:'No hay ningún permiso agregado'});
    $('#modal_add').modal('show');
}

async function loadSubSections(_event = "new"){

    let sub_seccions = await ths.getSubSections();

    if(_event == "new"){
        if(sub_seccions.length > 0){

            $('#select_sub_seccion').empty();
            const option = document.createElement('option');
            option.text = "Selecciona una opción";
            option.selected = true;
            option.value = "";
            option.disabled = true;

            sub_seccions.forEach(sub_seccion => {
                const option = document.createElement('option');
                option.value = sub_seccion.id
                option.text = sub_seccion.name;
                document.querySelector("#select_sub_seccion").appendChild(option);
            })

            $('#select_sub_seccion').selectpicker('refresh');
        }
    }else{
        if(sub_seccions.length > 0){

            $('#select_sub_seccion_edit').empty();
            const option = document.createElement('option');
            option.text = "Selecciona una opción";
            option.selected = true;
            option.value = "";
            option.disabled = true;

            sub_seccions.forEach(sub_seccion => {
                const option = document.createElement('option');
                option.value = sub_seccion.id
                option.text = sub_seccion.name;
                document.querySelector("#select_sub_seccion_edit").appendChild(option);
            })

            $('#select_sub_seccion_edit').selectpicker('refresh');
        }
    }



}

function addRolItem(){

    let element_id = ths.generateId();
    let sub_seccion = document.querySelector("#select_sub_seccion").value;
    let sub_seccion_text = document.querySelector("#select_sub_seccion").selectedOptions[0].text
    let permissions = [];
    $("#select_permissions").val().forEach(permission =>{
        permissions.push(permission);
    })

    let isValid = ths.validateRolItem(sub_seccion,permissions);

    if(isValid){
        $('#select_sub_seccion').selectpicker('val', []);
        $('#select_location').selectpicker('refresh');
        $('#select_permissions').selectpicker('val', []);
        $('#select_permissions').selectpicker('refresh');

        ths.general_data.matriz_permissions.push({
            id:element_id,
            sub_seccion,
            sub_seccion_text,
            permissions:permissions,
            status:"new"
        });

        let permission_HTML = ""
        permissions.forEach(element => {
            permission_HTML+=`<span class="badge bg-success">${element}</span><br>`
        });



        let row_HTML=`<div class="col-md-12" id="permission_${element_id}">
                        <div class="row m-0 input-file mb-1">
                            <div class="col-md-7">
                                <strong>${sub_seccion_text}</strong>
                            </div>
                            <div class="col-md-3 text-capitalize">
                                ${permission_HTML}
                            </div>
                            <div class="col-md-2">
                                <span class="delete-item" onclick="deleteRolItem(this)" data-row_id="permission_${element_id}" data-permission_id="${element_id}" data-status="new" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar"><i class="fas fa-trash"></i></span>
                            </div>
                        </div>
                    </div>`


        if(document.querySelector("#list_permissions").childNodes[0].className == "col no-found-data"){
            document.querySelector("#list_permissions").innerHTML = "";
        }
        $("#list_permissions").append(row_HTML);



    }
}
function addRolItemEdit(){

    let element_id = ths.generateId();
    let sub_seccion = document.querySelector("#select_sub_seccion_edit").value;
    let sub_seccion_text = document.querySelector("#select_sub_seccion_edit").selectedOptions[0].text
    let permissions = [];
    $("#select_permissions_edit").val().forEach(permission =>{
        permissions.push(permission);
    })

    let isValid = ths.validateRolItem(sub_seccion,permissions);

    if(isValid){
        $('#select_sub_seccion_edit').selectpicker('val', []);
        $('#select_location_edit').selectpicker('refresh');
        $('#select_permissions_edit').selectpicker('val', []);
        $('#select_permissions_edit').selectpicker('refresh');

        ths.general_data.matriz_permissions.push({
            id:element_id,
            sub_seccion,
            sub_seccion_text,
            permissions:permissions,
            status:"new"
        });

        let permission_HTML = ""
        permissions.forEach(element => {
            permission_HTML+=`<span class="badge bg-success">${element}</span><br>`
        });



        let row_HTML=`<div class="col-md-12" id="permission_${element_id}">
                        <div class="row m-0 input-file mb-1">
                            <div class="col-md-7">
                                <strong>${sub_seccion_text}</strong>
                            </div>
                            <div class="col-md-3 text-capitalize">
                                ${permission_HTML}
                            </div>
                            <div class="col-md-2">
                                <span class="delete-item" onclick="deleteRolItem(this)" data-row_id="permission_${element_id}" data-permission_id="${element_id}" data-status="new" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar"><i class="fas fa-trash"></i></span>
                            </div>
                        </div>
                    </div>`


        if(document.querySelector("#list_permissions_edit").childNodes[0].className == "col no-found-data"){
            document.querySelector("#list_permissions_edit").innerHTML = "";
        }
        $("#list_permissions_edit").append(row_HTML);



    }
}

function validateRolItem(_sub_seccion,_permissions,_event = ""){

    let cont=0;

    if(_sub_seccion <= 0){
        cont++
    }

    if(_permissions.length <= 0){
        cont++;
    }

    if(cont > 0){
        ths.alert('Aviso',"Complete los campos de Subsección y Permisos", 'warning');
        return false;
    }else{
        let cont_permission = 0;
        ths.general_data.matriz_permissions.forEach(permission =>{
            if(permission.sub_seccion == _sub_seccion){
                cont_permission++;
            }
        })

        ths.general_data.data.data.forEach(permission =>{
            if(permission.id == document.querySelector("#record_id").value){
                permission.roles_items.forEach(rol =>{
                    if(rol.sub_seccion_id == _sub_seccion){
                        cont_permission++;
                    }
                })
            }
        })

        if(cont_permission > 0){
            ths.alert('Aviso',"Ya a sido agregada está subsección", 'warning');
            return false;
        }else{
            return true;
        }
    }



}

function deleteRolItem(_event){
    // Eliminarlo desde el servidor tambien
    if(_event.dataset.status == "edit"){
        axios.get('/admin/settings/rol-item-delete/'+_event.dataset.permission_id)
            .then(response =>{
                let resp = response.data;
                if(resp.success == 1){
                    ths.getPage(1);
                    ths.notify('Éxito', resp.message, 'success');
                }else{
                    ths.alert('Error',resp.message, 'error');
                }
                //ths.visualisity_loading('data','delete_'+_id);
            })
            .catch(error =>{
                //ths.visualisity_loading('data','delete_'+_id);
                console.log(error);
                ths.alert('Error', 'A ocurrido un error, intente de nuevo', 'error');

            })
    }

    ths.general_data.matriz_permissions = ths.general_data.matriz_permissions.filter(permission =>{
        return permission.id !== _event.dataset.permission_id;
    })

    document.querySelector('#'+_event.dataset.row_id).remove();

    if(document.querySelector('#list_permissions').children.length == 0){
        document.querySelector("#list_permissions").innerHTML = ths.noDataFound({text:'No hay ningún permiso agregado'});
    }
}


function saveRecord(){
    let isValid = ths.validateFields(ths.general_data.fields_modal.required);

    if(isValid == true){
        if(ths.general_data.matriz_permissions.length > 0){
            ths.visualisity_loading('loading','form_add');

            axios.post('/admin/settings/rol-save',{
                name: document.querySelector("#txt_name").value,
                matriz_permissions: ths.general_data.matriz_permissions
            })
            .then(response =>{

                let resp = response.data;
                if(resp.success == 1){
                    ths.general_data.matriz_permissions = [];
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
        }else{
            ths.alert('Aviso',"No ha sido cargado ningun permiso", 'warning');
        }



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

    document.querySelector("#txt_name_view").value = element.name;

    // Se agregan los documentos que tiene cargados el comunicado
    if(element.roles_items.length > 0){

        let permission_HTML = ""
        let row_HTML='';
        element.roles_items.forEach(element => {

            let permissions = element.actions.split(',')
            permission_HTML=""
            permissions.forEach(permission => {
                permission_HTML+=`<span class="badge bg-success">${permission}</span><br>`

            });

            row_HTML+=`<div class="col-md-12" id="permission_${element.id}">
                        <div class="row m-0 input-file mb-1">
                            <div class="col-md-7">
                                <strong>${element.sub_seccion.name}</strong>
                            </div>
                            <div class="col-md-3 text-capitalize">
                                ${permission_HTML}
                            </div>
                        </div>
                    </div>`
        });


        document.querySelector('#list_permissions_view').innerHTML = row_HTML;
    }else{
        document.querySelector('#list_permissions_view').innerHTML= ths.noDataFound({text:'No hay ningún documento cargado'});
    }

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

    ths.loadSubSections("edit");

    document.querySelector("#record_id").value = element.id;
    document.querySelector("#txt_name_edit").value = element.name;

    // Se agregan los documentos que tiene cargados el comunicado
    if(element.roles_items.length > 0){

        let permission_HTML = ""
        let row_HTML='';
        element.roles_items.forEach(element => {

            let permissions = element.actions.split(',')
            permission_HTML=""
            permissions.forEach(permission => {
                permission_HTML+=`<span class="badge bg-success">${permission}</span><br>`

            });

            row_HTML+=`<div class="col-md-12" id="permission_${element.id}">
                        <div class="row m-0 input-file mb-1">
                            <div class="col-md-7">
                                <strong>${element.sub_seccion.name}</strong>
                            </div>
                            <div class="col-md-3 text-capitalize">
                                ${permission_HTML}
                            </div>
                            <div class="col-md-2">
                                <span class="delete-item" onclick="deleteRolItem(this)" data-row_id="permission_${element.id}" data-permission_id="${element.id}" data-status="edit" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar"><i class="fas fa-trash"></i></span>
                            </div>
                        </div>
                    </div>`
        });


        document.querySelector('#list_permissions_edit').innerHTML = row_HTML;
    }else{
        document.querySelector('#list_permissions_edit').innerHTML= ths.noDataFound({text:'No hay ningún documento cargado'});
    }

    $("#modal_edit").modal('show');

}

function updateRecord(){
    let isValid = ths.validateFields(ths.general_data.fields_modal.edit_required);

    if(isValid == true){
        if(ths.general_data.matriz_permissions.length > 0){
            ths.visualisity_loading('loading','form_edit');

            axios.post('/admin/settings/rol-edit',{
                id:document.querySelector("#record_id").value,
                name: document.querySelector("#txt_name_edit").value,
                matriz_permissions: ths.general_data.matriz_permissions
            })
            .then(response =>{

                let resp = response.data;
                if(resp.success == 1){
                    ths.general_data.matriz_permissions = [];
                    ths.clearFields(ths.general_data.fields_modal.edit_required);
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
        }else{
            ths.alert('Aviso',"No ha sido cargado ningun permiso", 'warning');
        }

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

            axios.get('/admin/settings/rol-delete/'+_id)
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

