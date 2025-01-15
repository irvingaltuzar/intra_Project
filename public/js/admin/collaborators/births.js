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
        required:['txt_birth','select_collaborator','select_sex','txt_message'],
        edit_required:['txt_birth_edit','select_collaborator_edit','select_sex_edit','txt_message_edit'],
        total:['txt_birth','select_collaborator','select_sex','txt_message'],
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

function addList(_list,_from){
    let _rows_HTML = ``;

    if(_list.length > 0){
        let user_name= "";
        _list.forEach((item,key) =>{
            console.log(item);

            user_name = item.user != null ? (item.user.name+' '+item.user.last_name) : "Histórico"

            _rows_HTML +=`<tr>
                            <th scope="row">${_from}</th>
                            <td>${item.birth}</td>
                            <td>${user_name}</td>
                            <td>${item.sex}</td>
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

async function loadUsers(){

    let users = await ths.getUsers();
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
            option.value = user.usuario
            option.text = (user.name+' '+user.last_name);
            document.querySelector("#select_collaborator").appendChild(option);
        })
    }


}

async function preLoadUsers(){
    let users = await ths.getUsers();
    ths.general_data.users = users;
}

function saveRecord(){
    let isValid = ths.validateFields(ths.general_data.fields_modal.required);
    const config = { headers: { 'Content-Type': 'multipart/form-data' } };

    if(isValid == true){

        ths.visualisity_loading('loading','form_add');


        let formData = new FormData();
        formData.append('birth', document.querySelector('#txt_birth').value);
        formData.append('user', document.querySelector('#select_collaborator').value);
        formData.append('sex', document.querySelector('#select_sex').value);
        formData.append('message', document.querySelector('#txt_message').value);

        axios.post('/admin/collaborators/birth-save',formData,config)
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

    document.querySelector("#txt_birth_view").value= element.birth;
    document.querySelector("#txt_collaborator_view").value= element.user.name+' '+element.user.last_name
    document.querySelector("#txt_sex_view").value= element.sex;
    document.querySelector("#txt_message_view").value= element.message;


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
            option.value = user.usuario
            option.text = user.name+' '+user.last_name;
            document.querySelector("#select_collaborator_edit").appendChild(option);
        })
    }

    document.querySelector("#birth_id").value= element.id;
    document.querySelector("#txt_birth_edit").value= element.birth;
    document.querySelector("#select_collaborator_edit").value= element.user.usuario;
    document.querySelector("#select_sex_edit").value= element.sex;
    document.querySelector("#txt_message_edit").value= element.message;

    $("#modal_edit").modal('show');

}

function updateRecord(){
    let isValid = ths.validateFields(ths.general_data.fields_modal.edit_required);
    const config = { headers: { 'Content-Type': 'multipart/form-data' } };

    if(isValid == true){

        ths.visualisity_loading('loading','form_edit');

        let formData = new FormData();
        formData.append('birth_id', document.querySelector('#birth_id').value);
        formData.append('birth', document.querySelector('#txt_birth_edit').value);
        formData.append('user', document.querySelector('#select_collaborator_edit').value);
        formData.append('sex', document.querySelector('#select_sex_edit').value);
        formData.append('message', document.querySelector('#txt_message_edit').value);

        axios.post('/admin/collaborators/birth-edit',formData,config)
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

            axios.get('/admin/collaborators/birth-delete/'+_id)
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
