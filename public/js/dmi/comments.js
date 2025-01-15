function get_publication_comments(data = null){
    // se obtienen todos los
    ths.visualisity_loading('loading','comments_body');
    document.querySelector('#txt_comments').emojioneArea.content="";

    let url_list = ths.general_data.modal_publications.pagination.next != "" ? ths.general_data.modal_publications.pagination.next : 'publications/list/comments';

    axios.get(url_list,{
        params:{
            publication_id:data.publication_id,
            type_publication:'birthday',
            vw_users_usuario:ths.general_data.modal_publications.headers.receiver_vw_users_usuario,
            birthday_date:ths.general_data.modal_publications.headers.birthday_date,
        }
    })
    .then(response =>{

        var resp = response.data;
        if(resp.success == 1){
            if(resp.data.data.length > 0){
                document.querySelector('#no_comments').classList.add('d-none');
                
                ths.list_comments(resp.data.data);
                ths.general_data.modal_publications.pagination.next = resp.data.next_page_url;

                if(resp.data.next_page_url != null){
                    document.querySelector('#see_more_comments').classList.remove('d-none');
                }else{
                    document.querySelector('#see_more_comments').classList.add('d-none');
                }
            }else{
                document.querySelector('#no_comments').classList.remove('d-none');                
            }

        }else{
            document.querySelector('#no_comments').classList.remove('d-none');
        }

        ths.visualisity_loading('data','comments_body');
    })
    .catch(error =>{

        ths.notification({type:'error',text:'Error, intentelo de nuevo'})
        ths.visualisity_loading('data','comments_body');
    })

}

function see_more_comments(){
    ths.get_publication_comments(ths.general_data.modal_publications.headers);
}

function add_comments(){
    let txt_comment = document.querySelector('#txt_comments').emojioneArea.content;
    let asset_url = document.querySelector('#asset_url').value;
	document.querySelector("#btn_modal_comments").disabled=true;


    if(txt_comment != ""){
        ths.general_data.modal_publications.headers.message = txt_comment;

        $('#comments_publishing').fadeToggle();
        axios.post('publications/add/comments',ths.general_data.modal_publications.headers)
        .then(response =>{

            let comment = response.data.comment;
            let aux_key_temp = ths.general_data.modal_publications.headers.aux_key_publication;

            document.querySelector("#"+ths.general_data.modal_publications.headers.aux_key_publication).dataset.publication=comment.publications_id;

            if((document.querySelector("#list_comments").rows).length > 0){
                let commentHtml = `<tr>
                                <td class="comment-tr-photo">
                                    <img onerror="this.src='${asset_url}/image/icons/user.svg';" class="comment-photo-user" src="${comment.user.photo_src}" alt="">
                                </td>
                                <td >
                                    <div class="comment-td-text">
                                        <div class="comment-full-name">${comment.user.full_name}</div>
                                        <div class="comment-text">${comment.comment}</div>
                                    </div>
                                    <div class="comment-created-at">
                                        ${comment.created_at}
                                    </div>
                                </td>
                            </tr>`;

                document.querySelector("#btn_modal_comments").disabled=false;
				$('#list_comments>tbody').append(commentHtml);

            }else{
                document.querySelector('#no_comments').classList.add('d-none');
                ths.list_comments([comment]);
            }

            ths.clear_txt_comment();
            ths.notification({type:'success',text:'Comentario agregado.'})
			document.querySelector("#btn_modal_comments").disabled=false;
        })
        .catch(error =>{
            console.log(error);
            ths.notification({type:'error',text:'Error, intentelo de nuevo'})
			document.querySelector("#btn_modal_comments").disabled=false;
        });

        $('#comments_publishing').fadeToggle();
    }else{
		document.querySelector("#btn_modal_comments").disabled=false;
        ths.notification({type:'info',text:'Debes agregar un comentario'})
    }

}

function list_comments(_comments){

    let list_comments_html = document.querySelector('#list_comments').innerHTML;
    let asset_url = document.querySelector('#asset_url').value;

    _comments.forEach(comment =>{
        list_comments_html += `
                                <tr>
                                    <td class="comment-tr-photo">
                                        <img onerror="this.src='${asset_url}/image/icons/user.svg';" class="comment-photo-user" src="${comment.user.photo_src}" alt="">
                                    </td>
                                    <td >
                                        <div class="comment-td-text">
                                            <div class="comment-full-name">${comment.user.full_name}</div>
                                            <div class="comment-text">${comment.comment}</div>
                                        </div>
                                        <div class="comment-created-at">
                                            ${ths.filterDate(comment.created_at,'DD-M-YYYY')}
                                        </div>
                                    </td>
                                </tr>
                            `
    });
    document.querySelector('#list_comments').innerHTML= list_comments_html;
}

function clear_comments(){
    if(document.querySelector('#txt_comments').emojioneArea.content != ""){
        Swal.fire({
            text: "Deseas descartar el comentario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí',
            cancelButtonText: 'No'
          }).then((result) => {
            if (result.isConfirmed) {
                ths.clear_txt_comment();
            }
          })
    }

}

function clear_txt_comment(){
    $(".emojionearea-editor").html('');
    document.querySelector('#txt_comments').emojioneArea.content = "";
}


