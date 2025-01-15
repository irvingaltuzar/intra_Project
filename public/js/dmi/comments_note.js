var cont=0;
function generateComments(_comments){
    let commentHtml="";
    

    _comments.forEach(comment => {
        let children_comments = "";

        
        if(comment.children_comments){
            if(comment.children_comments.length > 0){
                cont++;
                children_comments = ths.generateComments(comment.children_comments)
                cont--;
            }
        }

        commentHtml += `
                    <div class="row content-article m-1 card-comment">
                        <div class="col-md-12">
                            <div class="row header-photo mb-1">
                                <div class="col-md-1">
                                    <div class="photo">
                                        <img onerror="this.src='/image/icons/user.svg'" src="${comment.user.photo}" class="img-fluid person" width="40">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col">
                                            <span class="note-user">
                                                ${comment.user.full_name}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="note-created">
                                            Publicado ${filterDate(comment.created_at,'DD MMM YYYY')}
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row body-comment mt-1 mb-1">
                                <div class="col-md-12">
                                    <div class="note-description">
                                        ${comment.comment}
                                    </div>
                                </div>
                            </div>
                            <div class="row footer-options">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="offset-md-8 col-md-2 text-center col-sm-6">
                                            <div 
                                                class="note-comment ${(comment.children_comments && comment.children_comments.length > 0) ? '' : 'd-none'}" 
                                                id="btn_comment_${comment.id}"
                                                data-parent_comment="${comment.id}" 
                                                data-bs-toggle="collapse" 
                                                href="#collapseComments_${comment.id}" 
                                                role="button" 
                                                aria-expanded="false" 
                                                aria-controls="collapseComments_${comment.id}">
                                                Comentarios 
                                                    <span id="count_comment_${comment.id}" class="badge rounded-pill bg-secondary">
                                                        ${(comment.children_comments && comment.children_comments.length > 0) ? (comment.children_comments.length) : ''}
                                                    </span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-6 text-center">
                                            <div class="note-response" 
                                                data-bs-toggle="collapse" 
                                                id="linkResponse_${comment.id}"
                                                href="#collapseResponse_${comment.id}" 
                                                role="button" 
                                                aria-expanded="false" 
                                                aria-controls="collapseResponse_${comment.id}"
                                                data-comment="${comment.id}"
                                                >
                                                Responder
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row footer-comments">
                                <div class="collapse" id="collapseComments_${comment.id}">
                                    ${children_comments}
                                </div>
                            </div>
                            
                            <div class="row footer-response">
                                <div class="collapse" id="collapseResponse_${comment.id}">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
            `;
    })
     return commentHtml;
}

function paintComments(_comments){
    let _comments_HTML = ths.generateComments(_comments);
    $('#comment_list').append(_comments_HTML).fadeIn();
    
};

function add_comments(_comment_id = ''){

    if(document.querySelector(".card-no-data") != null){
        document.querySelector('#comment_list').innerHTML = "";
    }

    let txt_comment = document.querySelector('#txt_comments'+_comment_id).emojioneArea.content;
    
    if(txt_comment != ""){
        ths.general_data.headers.message = txt_comment;
        
        ths.general_data.headers.parent_comments = (_comment_id > 0 ? _comment_id : null)

        $('#comments_publishing').fadeToggle();
        axios.post('/publications/add/comments',ths.general_data.headers)
        .then(response =>{

            if(ths.general_data.comment.answer.active == true){
                ths.paintAnswer(response.data.comment);
            }else{
                ths.paintComments([response.data.comment]);
                ths.clear_txt_comment();
                $('#comments_publishing').fadeToggle();
                ths.notification({type:'success',text:'Comentario agregado.'})
            }
            
        })
        .catch(error =>{
            $('#comments_publishing').fadeToggle();
            ths.notification({type:'error',text:'Error, intentelo de nuevo'})
        });
    }else{
        ths.notification({type:'info',text:'Debes agregar un comentario'})
    }
}


$(document).on('click', '.note-response', function () {

    let comment_id = this.dataset.comment

    if(ths.general_data.comment.answer.active == true ){
        if(ths.general_data.comment.answer.id != comment_id){
            ths.clear_txt_comment(ths.general_data.comment.answer.id)
            ths.general_data.comment.answer.active = false;
        }
        
    }

    ths.general_data.comment.answer = {
        active:true,
        id:comment_id
    }

    let field_response = `
        <div class="row mt-1 ">
            <textarea id="txt_comments${comment_id}" placeholder="Añade tu respuesta" class="form-control" name="summary-ckeditor"></textarea>
            <div class="row m-1 text-center">
                <div class="offset-md-8 col-sm-6 col-md-2">
                    <button type="button" class="btn btn-discard" onclick="clear_comments(${comment_id})">
                        Descartar
                    </button>
                </div>
                <div class="col-sm-6 col-md-1">
                    <button type="button" class="btn btn-primary" onclick="add_comments(${comment_id})">
                        Comentar
                    </button>
                </div>
            </div>        
        </div>`;

    document.querySelector('#collapseResponse_'+comment_id).innerHTML = field_response;
    $("#txt_comments"+comment_id).emojioneArea({
        pickerPosition: "top",
        search:false,
        useInternalCDN: true,
        tonesStyle: 'radio',
        shortnames: true,
    });

    $('#collapseResponse_'+comment_id).collapse();

});

function clear_comments(_comment_id = ''){
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
            ths.clear_txt_comment(_comment_id);
        }
        })
    
}

function clear_txt_comment(_comment_id = ''){
    $(".emojionearea-editor").html('');
    if(_comment_id > 0 ){
        document.querySelector('#txt_comments'+_comment_id).emojioneArea.content = "";
        document.querySelector('#txt_comments'+_comment_id).emojioneArea.content = "";
        document.querySelector('#collapseResponse_'+_comment_id).innerHTML = "";
        $('#linkResponse_'+_comment_id).click();
        ths.general_data.comment.answer.active = false;
        ths.general_data.comment.answer.id = null;
    }else{
        document.querySelector('#txt_comments').emojioneArea.content = "";
        document.querySelector('#txt_comments').emojioneArea.content = "";
    }
    
}

function paintAnswer(_answer){
    let _answer_HTML = ths.generateComments([_answer]);
    let parent_comment_id = ths.general_data.comment.answer.id;
    $(`#collapseComments_${parent_comment_id}`).append(_answer_HTML);
    ths.clear_txt_comment(parent_comment_id);

    if(document.querySelector(`#btn_comment_${parent_comment_id}`).ariaExpanded == false ){
        $('#btn_comment_'+parent_comment_id).toggle();
    }


    ths.general_data.comment.answer = {
        active:false,
        id:null
    }
    document.querySelector(`#btn_comment_${parent_comment_id}`).classList.remove('d-none');
    document.querySelector(`#count_comment_${parent_comment_id}`).innerText = document.querySelector(`#collapseComments_${parent_comment_id}`).children.length;
    
}