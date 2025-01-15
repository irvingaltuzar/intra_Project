
/* ************* Funciones genericas ********* */
var url_base = document.querySelector("#url_base").content;
var current_date = document.querySelector("#current_date").content;

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Configura un interceptor en Axios para agregar el token CSRF en cada solicitud
axios.interceptors.request.use((config) => {
    config.params = {
        ...config.params,
        _token: csrfToken,
    };
    return config;
});



/*========================= START - FUNCIONES REUTIZABLES =============================*/
function getLocations(){

    return new Promise(function(resolve,reject){
        axios.get('/locations/all')
        .then(response => {
            let resp = response.data
            resolve(resp.data);

        })
        .catch(error =>{
            ths.notify('Aviso', 'Error al cargar las ubicaciones', 'danger');
            reject('peticion => /locations/all ',error);

        })
    })
}

function getSubgroups(){

    return new Promise(function(resolve,reject){
        axios.get('/locations/subgroups')
        .then(response => {
            let resp = response.data
            resolve(resp.data);

        })
        .catch(error =>{
            ths.notify('Aviso', 'Error al cargar los subgrupos', 'danger');
            reject('peticion => /locations/subgroups ',error);

        })
    })
}

function getSubSections(){

    return new Promise(function(resolve,reject){
        axios.get('/admin/settings/sub-seccion/all')
        .then(response => {
            let resp = response.data
            resolve(resp.data);

        })
        .catch(error =>{
            ths.notify('Aviso', 'Error al cargar las subsecciones', 'danger');
            reject('peticion => /admin/settings/sub-seccion/all ',error);

        })
    })
}


function getUsers(){

    return new Promise(function(resolve,reject){
        axios.get('/users/all')
        .then(response => {
            let resp = response.data
            resolve(resp.data);

        })
        .catch(error =>{
            ths.notify('Aviso', 'Error al cargar los colaboradores', 'danger');
            reject('peticion => /users/all ',error);

        })
    })
}

function getPromotionUsers(){

    return new Promise(function(resolve,reject){
        axios.get('/users/promotion-all')
        .then(response => {
            let resp = response.data
            resolve(resp.data);

        })
        .catch(error =>{
            ths.notify('Aviso', 'Error al cargar los colaboradores', 'danger');
            reject('peticion => /users/all ',error);

        })
    })
}

function getRoles(){

    return new Promise(function(resolve,reject){
        axios.get('/admin/settings/rol/all')
        .then(response => {
            let resp = response.data
            resolve(resp.data);

        })
        .catch(error =>{
            ths.notify('Aviso', 'Error al cargar los roles', 'danger');
            reject('peticion => /admin/settings/rol/all ',error);

        })
    })
}

function getTypeEvents(){

    return new Promise(function(resolve,reject){
        axios.get('/type_events/all')
        .then(response => {
            let resp = response.data
            resolve(resp.data);

        })
        .catch(error =>{
            ths.notify('Aviso', 'Error al cargar los tipos de evento', 'danger');
            reject('peticion => /type_events/all ',error);

        })
    })
}

function validateFields(_fields){
    // Se recibe un arreglo con los id de los fields a validar
    let field_invalid = 0;
    _fields.forEach( item =>{
        let element = document.querySelector('#'+item);
        if(element.checkValidity()){
            if(element.type == 'file'){
                document.querySelector("#input-file_"+item).classList.remove('input-invalid');
            }else if(element.type == "select-multiple"){
                if($('#'+item).val().length > 0){
                    document.querySelector("#input_sm-"+item).classList.remove('input-invalid');
                }else{
                    document.querySelector("#input_sm-"+item).classList.add('input-invalid');
                }
            }else{
                element.classList.remove('input-invalid')

            }
        }else{
            if(element.type == 'file'){
                document.querySelector("#input-file_"+item).classList.add('input-invalid');
            }else if(element.type == "select-multiple"){
                if($('#'+item).val().length > 0){
                    document.querySelector("#input_sm-"+item).classList.remove('input-invalid');
                }else{
                    document.querySelector("#input_sm-"+item).classList.add('input-invalid');
                }
            }else{
                element.classList.add('input-invalid')

            }
            field_invalid++
        }
    })

    if(field_invalid == 0){
        return true;
    }else{
        ths.notify('Aviso', 'Complete los campos obligatorios.', 'danger');
        return false;
    }


}

function clearFields(_fields,_event=""){
    // Se recibe un arreglo con los id de los fields a limpiar
    _fields.forEach(field =>{

        let element = document.querySelector("#"+field+_event);

        if(element.type == 'text' || element.type == "select-one" || element.type == "date" || element.type == "textarea" || element.type == "time"){
            element.value="";
            element.classList.remove('input-invalid')

        }else if(element.type == 'file'){
            element.value ="";
            document.querySelector("#input-file_"+field+_event).classList.remove('input-invalid');
            document.querySelector("#name_"+field+_event).innerHTML =""
        }

    })

}

/*========================= END - FUNCIONES REUTIZABLES =============================*/

function visualisity_loading(event,element,segmento=null){
    if(event == "data"){
        $('#loading_'+element).hide();
        $('#data_'+element).fadeIn(300);
    }else if(event == "loading"){
        $('#data_'+element).hide();
        $('#loading_'+element).fadeIn(300);
    }
}


function filterDate(_date,_format){
    var date = this.moment(_date).format(_format).replace('.','');
    return date;

}


/*
     * Notifications
     */
function notify(title="",message="", type="inverse"){
    $.growl({
        icon:"",
        title: title+" -",
        message: message,
        url: ''
    },{
        element: 'body',
        type: type,
        allow_dismiss: true,
        placement: {
            from: "bottom",
            align: "right"
        },
        offset: {
            x: 30,
            y: 30
        },
        spacing: 10,
        z_index: 999999,
        delay: 3000,
        timer: 3000,
        url_target: '_blank',
        mouse_over: false,
        animate: {
            enter: "",
            exit: ""
        },
        icon_type: 'class',
        template: '<div data-growl="container" class="alert alert-notify" role="alert">' +
        '<span data-growl="icon"></span>' +
        '<span class="alert-title" data-growl="title"></span>' +
        '<span data-growl="message"></span>' +
        '<a href="#" data-growl="url"></a>' +
        '</div>'
    });
};

function alert(_title="",_message="", _icon="error",_type="basic"){

    if(_type == "basic"){
        Swal.fire(
            _title,
            _message,
            _icon
          )
    }else if(_type == "validate"){

        let errors_HTML = "";
        Object.entries(_message).forEach(([key,item])=>{
            errors_HTML += `<ul>${item[0]}</ul>`
        })

        Swal.fire({
            title: `<strong>${_title}</strong>`,
            icon: _icon,
            html:errors_HTML,
            showCloseButton: true,
            focusConfirm: false,
            confirmButtonText:
              'Ok',
            confirmButtonAriaLabel: 'Thumbs up, great!',
          })
    }

}

// Genera un id generico unico para elementos html
function generateId(){
    return Math.random().toString(36).substr(2, 18);
}

function noDataFound(_params){
    let row_HTML="";
    let text = _params.text ? _params.text : "No se encontró ningún registro.";
    let icon = _params.icon ? _params.icon : "empty.svg";
    let content = _params.content ? _params.content : "div";
    let colspan = _params.colspan ? _params.colspan : "2";

    if(content == "div"){
        row_HTML = `<div class="col no-found-data">
                        <img class="img-svg-20" src="${ths.url_base}/image/icons/${icon}">
                        <br>
                        ${text}
                    </div>`

        return row_HTML

    }else if(content == 'table'){
        row_HTML = `<tr>
                        <td colspan="${colspan}" class="no-found-data">
                            <img class="img-svg-20" src="${ths.url_base}/image/icons/${icon}">
                            <br>
                            ${text}
                        </td>
                    <tr>`

        return row_HTML;
    }


}


/* *************** START - FUNCIONES DE BUSQUEDA ***************************** */
$('#order_by').change(function(){
    let order_by_selected = document.querySelector('#order_by').value;
    ths.general_data.headers.order_by = order_by_selected;
    ths.getPage();
})

$('#limit_page').change(function(){
    let limit_page_selected = document.querySelector('#limit_page').value;
    ths.general_data.headers.limit = limit_page_selected;
    ths.getPage();
})


$('#txt_search').on('input',function (e) {
    let txt_search = this.value;
    if(txt_search.length > 0){
        ths.general_data.headers.search.isSearch = true;
        ths.general_data.headers.search.text = txt_search;

    }else{
        ths.general_data.headers.search.isSearch = false;
        ths.general_data.headers.search.text = "";
    }

    ths.getPage();

},);

$('.clear_search').on('click',function(){
    ths.general_data.headers.search.isSearch = false;
    ths.general_data.headers.search.text = "";
    document.querySelector('#txt_search').value="";
    ths.getPage();
})
/* *************** END - FUNCIONES DE BUSQUEDA ***************************** */
