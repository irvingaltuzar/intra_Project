var ths = this;
var general_data = {
    modal_publications:{
        headers:{},
        pagination:{
            next:0
        }
    },


};


$('.add-comments').on('click',function(){
    let data_post={};
    let vw_users_usuario = this.dataset.usuario;
    let type_publication = this.dataset.type_publication;

    let data_usuario = document.querySelector(`#${type_publication}_${vw_users_usuario}`).dataset;
    ths.general_data.modal_publications.headers={
        birthday_date: data_usuario.birthday,
        publication_id: data_usuario.publication,
        publication_section : 1,
        receiver_vw_users_usuario: data_usuario.usuario,
        sender_vw_users_usuario:this.auth_user,
        aux_key_publication:`${type_publication}_${vw_users_usuario}`
    }

    ths.general_data.modal_publications.pagination.next=0;

    document.querySelector('#birthday-name').innerHTML = data_usuario.name

    ths.get_publication_comments({
        publication_id:data_usuario.publication,
    });

    ths.clear_txt_comment();
    document.querySelector('#list_comments').innerHTML="";

    $('#messageModal').modal('show');
});

$('.view-reactions').on('click',function(){
    let publication = this.dataset.publication;

    ths.viewReactionsModal(publication);

});



/* ************* Funciones genericas ********* */
function notification(data){
    Toast.fire({
        icon: data.type,
        title: data.text
    })
}

/* ************* Funciones genericas ********* */
