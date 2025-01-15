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
        required:['select_user'],
        edit_required:['select_user'],
        total:['select_user'],
    },
    locations:{},
    matriz_permissions:[],
    roles:[],
    permission_edit:{}

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
        let full_name = "";
        _list.forEach((item,key) =>{
            console.log(item);

            full_name = item.user != null ? (item.user.full_name) : "Histórico";

            _rows_HTML +=`<tr>
                            <th scope="row">${_from}</th>
                            <td>${full_name}</td>
                            <td>${item.rol.name}</td>
                            <td>${item.created_at}</td>
                            <td class="text-center">
                                <div id="data_delete_${item.id}">
                                    <button class="btn btn-light btn-action-success" onclick="editRecord(${item.id})" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Editar"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-light btn-action-danger" onclick="deleteRecord(${item.id})" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar"><i class="fas fa-trash"></i></button>
                                </div>
                                <div id="loading_delete_${item.id}" style="display:none;" class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>

                            </td>
                        </tr>`

                        /*
                        <button class="btn btn-light btn-action-primary" onclick="viewRecord(${item.id})" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ver"><i class="fas fa-eye"></i></button>
                        */

            _from++;
        });

        document.querySelector('#data_list').innerHTML = _rows_HTML;
        ths.paginate_control(ths.general_data.data);

    }else{

        document.querySelector('#data_list').innerHTML = ths.noDataFound({
            content:"table",
            colspan:"5"
        });

    }
}

function newRecord(){
    ths.loadUsers();
    ths.loadRoles();
    $('#modal_add').modal('show');
}

function editRecord(_id){

    ths.general_data.data.data.forEach(permission =>{
        if(permission.id == _id){
            ths.general_data.permission_edit= permission;
        }
    })

    document.querySelector("#select_user_edit").value = ths.general_data.permission_edit.vw_users_usuario
    document.querySelector("#select_rol_edit").value = ths.general_data.permission_edit.roles_id

    ths.loadUsers("edit");
    ths.loadRoles("edit");
    ths.addRolItemEdit();
    $('#modal_edit').modal('show');
}

async function loadUsers(_event="new"){

    let users = await ths.getUsers();

    if(_event == "new"){
        if(users.length > 0){

            $('#select_user').empty();
            const option = document.createElement('option');
            option.text = "Selecciona una opción";
            option.selected = true;
            option.value = "";
            //option.disabled = true;
            document.querySelector("#select_user").appendChild(option);

            users.forEach(user => {
                const option = document.createElement('option');
                option.value = user.usuario
                option.text = user.full_name;
                document.querySelector("#select_user").appendChild(option);
            })

            $('#select_user').selectpicker('refresh');
        }
    }else{
        if(users.length > 0){

            $('#select_user_edit').empty();
            const option = document.createElement('option');
            option.text = "Selecciona una opción";
            option.value = "";
            document.querySelector("#select_user_edit").appendChild(option);

            users.forEach(user => {
                const option = document.createElement('option');
                option.value = user.usuario
                option.text = user.full_name;

                if(ths.general_data.permission_edit.vw_users_usuario == user.usuario){
                    option.selected = true;
                }

                document.querySelector("#select_user_edit").appendChild(option);
            })

            $('#select_user_edit').selectpicker('refresh');
        }
    }
}

async function loadRoles(_event="new"){

    ths.general_data.roles = await ths.getRoles();

    if(_event == "new"){
        if(ths.general_data.roles.length > 0){

            $('#select_rol').empty();
            const option = document.createElement('option');
            option.text = "Selecciona una opción";
            option.selected = true;
            option.value = "";
            //option.disabled = true;
            document.querySelector("#select_rol").appendChild(option);

            ths.general_data.roles.forEach(rol => {
                const option = document.createElement('option');
                option.value = rol.id
                option.text = rol.name;

                document.querySelector("#select_rol").appendChild(option);
            })

            $('#select_rol').selectpicker('refresh');
        }
    }else{
        if(ths.general_data.roles.length > 0){

            $('#select_rol_edit').empty();
            const option = document.createElement('option');
            option.text = "Selecciona una opción";
            option.value = "";
            //option.disabled = true;
            document.querySelector("#select_rol_edit").appendChild(option);

            ths.general_data.roles.forEach(rol => {
                const option = document.createElement('option');
                option.value = rol.id
                option.text = rol.name;

                if(ths.general_data.permission_edit.roles_id == rol.id){
                    option.selected = true;
                    console.log();
                }

                document.querySelector("#select_rol_edit").appendChild(option);
            })

            $('#select_rol_edit').selectpicker('refresh');
        }
    }



}

function addRolItem(){

    let isValid = ths.validateSelect();

    if(isValid == true){
        let rol_id = document.querySelector("#select_rol").value;
        document.querySelector('#list_permissions').innerHTML = "";

        let rol = {};
        ths.general_data.roles.forEach(item => {
            if(item.id == rol_id){
                rol= item;
            }
        });

        let permission_HTML=""
        let row_HTML=""

        if(rol.roles_items.length > 0){
            rol.roles_items.forEach(element => {

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
            document.querySelector('#list_permissions').innerHTML = row_HTML;
        }else{
            document.querySelector('#list_permissions').innerHTML= ths.noDataFound({text:'No hay ningún permiso cargado'});
        }
    }




}

function addRolItemEdit(){

        let rol_id = document.querySelector("#select_rol_edit").value;
        document.querySelector('#list_permissions_edit').innerHTML = "";

        let rol = {};
        ths.general_data.roles.forEach(item => {
            if(item.id == rol_id){
                rol= item;
            }
        });

        let permission_HTML=""
        let row_HTML=""

        if(rol.roles_items.length > 0){
            rol.roles_items.forEach(element => {

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
            document.querySelector('#list_permissions_edit').innerHTML = row_HTML;
        }else{
            document.querySelector('#list_permissions_edit').innerHTML= ths.noDataFound({text:'No hay ningún permiso cargado'});
        }





}

function validateSelect(_event = ""){

    let cont=0;

    if(document.querySelector("#select_user").value == ''){
        cont++
    }

    if(document.querySelector("#select_rol").value == ''){
        cont++
    }

    if(cont > 0){
        ths.alert('Aviso',"Complete los campos de Usuario y Rol", 'warning');
        return false;
    }else{
        return true;
    }


}

function validateSelectEdit(_event = ""){

    let cont=0;

    if(document.querySelector("#select_user_edit").value == ''){
        cont++
    }

    if(document.querySelector("#select_rol_edit").value == ''){
        cont++
    }

    if(cont > 0){
        ths.alert('Aviso',"Complete los campos de Usuario y Rol", 'warning');
        return false;
    }else{
        return true;
    }


}

function addAccesControl(){
    let isValid = ths.validateSelect();

    if(isValid == true){

        ths.addRolItem();
        $('#select_user').selectpicker('val', []);
        $('#select_user').selectpicker('refresh');
        $('#select_rol').selectpicker('val', []);
        $('#select_rol').selectpicker('refresh');

    }

}

function saveRecord(){
    let isValid = ths.validateSelect();

    if(isValid == true){

            ths.visualisity_loading('loading','form_add');

            axios.post('/admin/settings/permission-save',{
                user: document.querySelector("#select_user").value,
                rol: document.querySelector("#select_rol").value,
            })
            .then(response =>{

                let resp = response.data;
                if(resp.success == 1){
                    ths.visualisity_loading('data','form_add');
                    ths.getPage(1);
                    $('#modal_add').modal('hide');
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

/* function viewRecord(_id){

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

} */

function updateRecord(){
    let isValid = ths.validateSelectEdit();

    if(isValid == true){

            ths.visualisity_loading('loading','form_edit');

            axios.post('/admin/settings/permission-edit',{
                id: ths.general_data.permission_edit.id,
                rol: document.querySelector("#select_rol_edit").value,
            })
            .then(response =>{

                let resp = response.data;
                if(resp.success == 1){
                    ths.visualisity_loading('data','form_edit');
                    ths.getPage(1);
                    $('#modal_edit').modal('hide');
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

            axios.get('/admin/settings/permission-delete/'+_id)
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

function isUserExist(){
    let user = document.querySelector("#select_user").value;
    console.log(user)
    axios.get('/admin/settings/permission-is-user-exist',{
        params:{
            user:user
        }
    })
    .then(response =>{
        let resp = response.data;

        if(resp.is_user_exist == 1){
            ths.alert('Alerta', 'Este usuario ya tiene un rol asignado.', 'warning');
            $('#select_user').selectpicker('val', []);
            $('#select_user').selectpicker('refresh');
        }
        console.log(resp);
    })
    .catch(error =>{
        console.log(error);
        ths.alert('Error', 'A ocurrido un error, intente de nuevo', 'error');

    })
}

