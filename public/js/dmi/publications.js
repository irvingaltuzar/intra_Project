var ths = this;
var general_data = {
    headers:{
        title:"",
        description:"",
    },
    pagination:{

    }
};

function openModal(){
    $('#publicationModal').modal('show');
}

function save_publication(){
    let txt_description = document.querySelector('#txt_description').emojioneArea.content;
    let txt_title = document.querySelector('#txt_title').emojioneArea.content;

    if(txt_description != "" && txt_title != ""){
        ths.general_data.headers.title = txt_title;
        ths.general_data.headers.description = txt_description;

        ths.visualisity_loading('loading','data_form_note');
        axios.post('/publications/add',ths.general_data.headers)
        .then(response =>{
            let resp = response.data;

            if(resp.success == 1){
                //ths.addPublicationHTML([resp.data]);
                ths.getPage();
            }else{
                ths.notification({type:'error',text:resp.message})
            }

            ths.visualisity_loading('loading','data_form_note');

        })
        .catch(error =>{
            console.log(error);
            $('#comments_publishing').fadeToggle();
            ths.notification({type:'error',text:'Error, intentelo de nuevo'})
        });
    }else{
        ths.notification({type:'info',text:'Completa todos los campos'})
    }
}


function addPublicationHTML(_publications){

    let publication_html = "";

    _publications.forEach(publication => {
        let photo = "";
        if(publication.photo != null){
            photo = `<img onerror="this.src='${ths.url_base}/image/icons/user.svg';" src="${url_base}/${publication.user.photo}" class="img-fluid person" width="100">`;
        }else{

            if(publication.user.sex.toUpperCase() == "MASCULINO"){
                photo = `<img src="${ths.url_base}/image/icons/masculino.svg" class="img-fluid person" width="100">`
            }else{
                photo = `<img src="${ths.url_base}/image/icons/femenino.svg" class="img-fluid person" width="100">`

            }
        }

        publication_html += `<a href="${ths.url_base}/blog/notes/${publication.id}" class="notes-link">
                                <article class="m-3">
                                    <div class="row content-article">
                                        <div class="col-md-2 justify-content-center align-self-center">
                                            <div class="photo">
                                                ${photo}
                                            </div>
                                            <div class="col note-nick">
                                                <div class="note-user">
                                                    ${publication.user.full_name}
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-10 justify-content-center align-self-center">
                                            <h3 class="title">${publication.title}</h3>
                                            <div class="note-description">
                                                ${(publication.description.length > 200) ? (publication.description.substring(0,200)+'...') : (publication.description)}
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 footer">

                                                    <div class="note-created">
                                                        Publicando ${filterDate(publication.created_at,'DD MMM YYYY')}
                                                    </div>
                                                </div>
                                                <div class="offset-3 col-md-3 col-sm-6 note-comments">
                                                    <i class="fas fa-comment"></i> ${publication.comments.length}
                                                    <i class="fas fa-eye ms-2"></i> ${publication.views}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </a>`

    })

    if(document.querySelector('.not-notes') != null){
        document.querySelector('#list_notes').innerHTML = "";
    }

    document.querySelector("#list_notes").innerHTML = publication_html;
    ths.closeModal()

}


function clear_form(){
    $(".emojionearea-editor").html('');
    document.querySelector('#txt_title').emojioneArea.content = "";
    document.querySelector('#txt_description').emojioneArea.content = "";

}

function emptyNotes(){
    let card_html = `
                    <div class="row m-3">
                        <div class="col-md-12 text-center card-no-data not-notes">
                            <img class="img-svg-20" src="${ths.url_base}/image/icons/new_post.svg">
                            <br>
                            <span class="no-data-user">
                                ${ths.general_data.user.full_name}
                            </span>
                            <br>
                            Se el primero en agregar un comentario
                        </div>
                    </div>
    `;

    document.querySelector('#list_notes').innerHTML = card_html;

}

function closeModal(){
    $(".emojionearea-editor").html('');
    document.querySelector('#txt_title').emojioneArea.content = "";
    document.querySelector('#txt_description').emojioneArea.content = "";
    $('#publicationModal').modal('hide');
}

function getPage(_page=1){

    ths.visualisity_loading('loading','notes');

    axios.get(ths.general_data.pagination.path+'?page='+_page)
    .then(response =>{
        let resp = response.data;
        if(resp != null){
            ths.addPublicationHTML(resp.data);
            ths.paginate_control(resp);
        }
        ths.visualisity_loading('data','notes');
    })
    .catch( error =>{
        ths.visualisity_loading('data','notes');
        ths.notification({type:'error',text:'Error al cargar los registros '+error})
    })

}
