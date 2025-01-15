
/* ************* Funciones genericas ********* */
var url_base = document.querySelector("#url_base").content;
var current_date = document.querySelector("#current_date").content;
var auth_user = document.querySelector("#auth_user").content;
var user_email = document.querySelector("#user_email").content;
var auth_user_publication = document.querySelector("#auth_user").content.replace('.','_');
var ths = this;

const Toast = Swal.mixin({
    toast: true,
    position: 'bottom-end',
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: false,
    didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

moment.locale('es-mx');

function notification(data){
    Toast.fire({
        icon: data.type,
        title: data.text
    })

}

function visualisity_loading(event,element,segmento=null){
    if(event == "data"){
        $('#loading_'+element).hide();
        $('#data_'+element).fadeIn(300);
    }else if(event == "loading"){
        $('#data_'+element).hide();
        $('#loading_'+element).fadeIn(300);
    }
}

/* ###################################### Filters ###################################### */
function filterDate(_date,_format){
    var date = this.moment(_date).format(_format).replace('.','');
    return date;

}
/* ###################################### End - Filters ###################################### */

function downpage(event){
    let element = event.dataset.href;
    document.querySelector("#"+element).scrollIntoView();
}

function getLastCheck(){

    return new Promise(function(resolve,reject){
        axios.get('/get-last-check')
        .then(response => {
            let _last_check = response.data
            
            if(_last_check.date){
                document.querySelector("#c_date1").textContent = _last_check.date;
                document.querySelector("#c_time1").textContent = _last_check.time;
                document.querySelector("#li_label_last_check").textContent = _last_check.time;
            }else{
                document.querySelector("#c_date1").textContent ="Sin checada";
                document.querySelector("#c_time1").textContent ="";
                document.querySelector("#li_label_last_check").textContent ="Sin checada";
            }
            resolve(_last_check.data);

        })
        .catch(error =>{
            document.querySelector("#c_date1").textContent = "-";
            document.querySelector("#c_time1").textContent = "";
            document.querySelector("#li_label_last_check").textContent = "-";

            console.log("error",error);    
            reject('peticion => /get-last-check ',error);

        })
    })
}

/* ###################################### Notificaciones ###################################### */


async function getNotifications(){
        await axios.get('/get-notifications')
        .then(response => {
            if(response.data.success == 1){
                let notifications = response.data.data;
                ths.addNotifications(notifications);
            }else{

            }

        })
        .catch(error =>{
            
            console.log("error",error);

        })
}

function addNotifications(_notifications){
    if(_notifications.length > 0){
        let html_item="";
        let notification_read_at = 0;
        let photo_usuario_notifying="";

        _notifications.forEach(notification =>{

            if(notification.read_at == null){
                notification_read_at++;
            }

            if(notification.usuario_notifying == 'intranet'){
                photo_usuario_notifying = `<img onerror="this.src='/image/logo-grupo-dmi.svg';" style="background: var(--color-dmi); height: 60px!important;width: 60px!important;" src="/image/avatar_dmi.svg" class="notification-user-photo">`
            }else{
                photo_usuario_notifying = `<img onerror="this.src='/image/icons/user.svg';" src="${notification.usuario_notifying_photo}" class="notification-user-photo">`
            }

            html_item +=`<div class="row ${notification.read_at == null ? 'notification-item-active' : 'notification-item'}">
                            <a class="notification-text" href="${notification.link != null ? notification.link : ''}">
                                <div class="col-md-12 m-1 d-flex">
                                    ${photo_usuario_notifying}
                                    ${this.notificationIcon(notification.type,notification.data)}
                                    <div class="ms-3">
                                        <span class="notification-message">${this.notificationMessage(notification.message)}</span><br>
                                            <span class="notification-date-time">${ths.filterDate(notification.created_at,'DD-MM-YYYY')}</span>
                                    </div>
                                </div>
                            </a>
                        </div>`;

        });

        if(notification_read_at > 0){
            document.querySelector("#notification-count-intranet").textContent= notification_read_at;
            document.querySelector("#notification-count-intranet").classList.remove('d-none');
        }else{
            document.querySelector("#notification-count-intranet").classList.add('d-none');

        }
        

        document.querySelector("#notifications-list").innerHTML= html_item;
        
        document.querySelector("#notifications-list").classList.remove('d-none');
        document.querySelector("#notifications-list-no-data").classList.add('d-none');

    }else{
        document.querySelector("#notifications-list").classList.add('d-none');
        document.querySelector("#notifications-list-no-data").classList.remove('d-none');
    }


}

function notificationMessage(_message){
    let new_text = _message.replace(/\*(.*?)\*/g, function(match, p1) {
        return '<strong>' + p1 + '</strong>';
    });

    if(new_text.length > 135){
        new_text = new_text.substr(0,135)+' ...';
    }

    return new_text;
}

function notificationIcon(_type = 'notification',_data = null){
    let icon = '';

    if(_type == 'notification'){
        icon = `<i class="fas fa-bell notification-icon notification-${_type}"></i>`;
    }else if(_type == 'system'){
        icon = `<i class="fas fa-laptop-code notification-icon notification-${_type}"></i>`;
    }else if(_type == 'reaction'){
        if(_data == 'felicitaciones'){
            //icon = `<i class="fas fa-birthday-cake notification-icon notification-${_type}"></i>`;
            icon = `<span class="notification-icon notification-reaction"><img style="width:20px" src="/image/icons/felicidades.png"></span>`;
        }else if(_data == 'me_encanta'){ 
            icon = `<span class="notification-icon notification-reaction"><img style="width:20px" src="/image/icons/me_encanta.png"></span>`;
        }else if(_data == 'me_divierte'){ 
            icon = `<span class="notification-icon notification-reaction"><img style="width:20px" src="/image/icons/risa.png"></span>`;
        }else if(_data == 'me_gusta'){ 
            icon = `<span class="notification-icon notification-reaction"><img style="width:20px" src="/image/icons/me_gusta.png"></span>`;
        }else if(_data == 'orando'){ 
            icon = `<span class="notification-icon notification-reaction"><img style="width:20px" src="/image/icons/orando.png"></span>`;
        }else{
            icon = `<i class="fas fa-bell notification-icon notification-notification"></i>`;
        }
        //icon = `<i class="fas fa-laptop-code notification-icon notification-${_type}"></i>`;
    }else if(_type == 'comment'){
        icon = `<i class="fas fa-comment notification-icon notification-${_type}"></i>`;
    }else{
        icon = `<i class="fas fa-bell notification-icon notification-${_type}"></i>`;
    }
    

    return icon;
}

async function viewNotifications(){
    
    await axios.post('/view-notifications')
    .then(response => {
        document.querySelector("#notification-count-intranet").classList.add('d-none');
    })
    .catch(error =>{
        
        console.log("error",error);
    })
}

/* ###################################### End - Notificaciones ###################################### */


/* ###################################### Reactiones ###################################### */

async function viewReactionsModal(_publication,_section){
    let reactions=[];
    let can_reaction= false;

    if(parseInt(_publication) > 0){
        /*  */
        reactions = await ths.getReactions(_publication,_section);
        if(reactions != null){  
            if(reactions.length > 0){
                can_reaction = ths.reviewerHaveReacted(reactions);
                ths.addReactions(reactions);

                document.querySelector("#reactionsModal_body_list").classList.remove("d-none");
                document.querySelector("#reactionsModal_body_list_no_data").classList.add("d-none");
            }else{
                can_reaction=true;
                document.querySelector("#reactionsModal_body_list_no_data").classList.remove("d-none");
                document.querySelector("#reactionsModal_body_list").classList.add("d-none");
            }
        }else{
            can_reaction=true;
            document.querySelector("#reactionsModal_body_list_no_data").classList.remove("d-none");
            document.querySelector("#reactionsModal_body_list").classList.add("d-none");
        }

        /*  */
        
    }else{
        can_reaction=true;
        document.querySelector("#reactionsModal_body_list_no_data").classList.remove("d-none");
        document.querySelector("#reactionsModal_body_list").classList.add("d-none");

    }

    if(can_reaction == true){
        /* Se agrega info para que se el primero en reaccionar */
        let icon_html="";

        document.querySelector("#span_icon_reaction").innerHTML="";

        if(_section == 'promotions'){
            icon_html=`<i class="fas fa-thumbs-up fa-lg no-reaction" 
                            data-bs-toggle="tooltip" 
                            data-bs-placement="bottom" 
                            title="Me gusta" 
                            onclick="giveLikePromotion(${_publication},'','si')"
                        ></i>`;

        }
        
        document.querySelector("#span_icon_reaction").innerHTML=icon_html;
		document.querySelector("#div_icon_reaction").classList.remove('d-none');
        document.querySelector("#span_icon_reaction").classList.remove('d-none');
    }else{
        document.querySelector("#span_icon_reaction").classList.add('d-none');
        document.querySelector("#div_icon_reaction").classList.add('d-none');
    }


    $("#reactionsModal").modal('show');
}

async function viewReactionsModalBirthday(_publication,_section,_usuarioId,_usuario,_birth){
    let reactions=[];
    let can_reaction= false;

    if(parseInt(_publication) > 0){
        /*  */
        reactions = await ths.getReactions(_publication,_section);

        if(reactions != null){  
            if(reactions.length > 0){
                can_reaction = ths.reviewerHaveReacted(reactions);
                ths.addReactions(reactions);

                document.querySelector("#reactionsModal_body_list").classList.remove("d-none");
                document.querySelector("#reactionsModal_body_list_no_data").classList.add("d-none");
            }else{
                can_reaction=true;
                document.querySelector("#reactionsModal_body_list_no_data").classList.remove("d-none");
                document.querySelector("#reactionsModal_body_list").classList.add("d-none");
            }
        }else{
            can_reaction=true;
            document.querySelector("#reactionsModal_body_list_no_data").classList.remove("d-none");
            document.querySelector("#reactionsModal_body_list").classList.add("d-none");
        }

        /*  */
        
    }else{
        can_reaction= true;
        document.querySelector("#reactionsModal_body_list_no_data").classList.remove("d-none");
        document.querySelector("#reactionsModal_body_list").classList.add("d-none");
    }

    if(can_reaction == true){
        /* Se agrega info para que se el primero en reaccionar */
        let icon_html="";

        document.querySelector("#span_icon_reaction").innerHTML="";
        document.querySelector("#span_icon_reaction").classList.remove('d-none');
        document.querySelector("#div_icon_reaction").classList.remove('d-none');
        
        icon_html=`<i class="fas fa-birthday-cake fa-lg no-reaction" 
                        onclick="giveLikeBirthday(${_usuarioId},'${_usuario}',${(_publication == null ||  _publication == 0) ? 'null' : _publication},'${_birth}','si')" 
                        data-bs-toggle="tooltip" 
                        data-bs-placement="bottom" 
                        title="Felicitar"></i>`;
        
        document.querySelector("#span_icon_reaction").innerHTML=icon_html;
    }else{
        document.querySelector("#span_icon_reaction").classList.add('d-none');
        document.querySelector("#div_icon_reaction").classList.add('d-none');
    }


    $("#reactionsModal").modal('show');
}

function reviewerHaveReacted(_list_reactions){

    let match = 0;
    _list_reactions.forEach(reaction =>{
        if(reaction.vw_users_usuario == auth_user){
            match++;
        }
    })

    if(match > 0){
        return false;
    }else{
        return true;
    }
}

function getReactions(_publiction,_section){


    return new Promise(function(resolve,reject){
        axios.get('/publications/reactions/list',{
            params:{
                publication:_publiction,
                section:_section
            }
        })
        .then(response =>{
            if(response.data.success == 1){
                return resolve(response.data.data);
            }else{
                return resolve(null);
            }
    
        })
        .catch(error =>{
            ths.notification({type:'error',text:'Error, al obtener las reacciones, intentelo de nuevo'})
            reject('Error al solicitar las reacciones');
        })
    })
}

function reactionIcon(_type_reaction){
    let icon ="";

    if(_type_reaction == 'felicitaciones'){
        //icon = `<i class="fas fa-birthday-cake notification-icon notification-${_type}"></i>`;
        icon = `<span style="padding-right: 7px;" class="notification-icon notification-reaction"><img style="width:20px" src="/image/icons/felicidades.png"></span>`;
    }else if(_type_reaction == 'me_encanta'){ 
        icon = `<span style="padding-right: 7px;" class="notification-icon notification-reaction"><img style="width:20px" src="/image/icons/me_encanta.png"></span>`;
    }else if(_type_reaction == 'me_divierte'){ 
        icon = `<span style="padding-right: 7px;" class="notification-icon notification-reaction"><img style="width:20px" src="/image/icons/risa.png"></span>`;
    }else if(_type_reaction == 'me_gusta'){ 
        icon = `<span style="padding-right: 7px;" class="notification-icon notification-reaction"><img style="width:20px" src="/image/icons/me_gusta.png"></span>`;
    }else if(_type_reaction == 'orando'){ 
        icon = `<span style="padding-right: 7px;" class="notification-icon notification-reaction"><img style="width:20px" src="/image/icons/orando.png"></span>`;
    }else{
        icon = `<i class="fas fa-bell notification-icon notification-notification"></i>`;
    }

    return icon;
}

function addReactions(_reactions){

    let rows_html="";
    document.querySelector("#reactionsModal_body_list").innerHTML="";
    _reactions.forEach(reaction => {
        if(reaction.user != null){
            rows_html += `
                <div class="row">
                    <div class="col-12">
                        <img onerror="this.src='/image/icons/user.svg';" src="${reaction.user.photo_src}" class="reaction-user-photo">
                        ${ths.reactionIcon(reaction.type_reaction)}
                        <span class="reaction-user-name">${reaction.user.full_name}</span>
                    </div>
                </div>
                `;
        }
        
    });
    

    document.querySelector("#reactionsModal_body_list").innerHTML= rows_html;


}

/* ###################################### End - Reactiones ###################################### */



function addTickets(_tickets,_canvas){
    
    let html_row = "";
    if(_tickets.length > 0){
        let category;
        let ubicacion;

        _tickets.forEach(ticket =>{
            
            category = ticket.category != null ? ticket.category : "Sin categoría";
            ubicacion = ticket.custom_fields.ubicacin ? ticket.custom_fields.ubicacin : "Sin ubicación"

            html_row+= `<div class="row notification-item-active">
                            <div class="col-md-12 m-1">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 ticket-title">
                                        ${this.getTicketTitle(ticket.id,ticket.subject)}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 ticket-tags">
                                        ${category} - ${ubicacion} - ${this.getTimeZoneMex(ticket.created_at)}
                                    </div>
                                </div>
                                <div class="row text-end">
                                    <div class="col-sm-12 col-md-12 ticket-tags">
                                        <a target="_blank" href="https://grupodmi.freshservice.com/a/tickets/${ticket.id}?current_tab=details">Ver más</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
        });
        document.querySelector(`#${_canvas}`).innerHTML= html_row;
    }

}



/* ************* Funciones genericas ********* */
