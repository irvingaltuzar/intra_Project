var ths = this;

$(".images-link" ).on( "click", function() {

    let _event =this.dataset;
    ths.getInfoCommunique(_event);

});

function getInfoCommunique(_event){

    document.querySelector("#list_files").innerHTML = "";
    document.querySelector("#communique_files").classList.add("d-none")

    ths.visualisity_loading('loading',"modal_body");
    $('#modal-images').modal('show');

    axios.get('/dmi_comunicados/communique/'+_event.id_communique)
    .then(response => {
        let communique = response.data.communique;

        if(communique.video != null){
            document.querySelector('#modal-video-full').classList.remove('d-none');
            document.querySelector('#modal-img-full').classList.add('d-none');
            document.querySelector('#modal-video-full').src = ths.url_base+"/"+communique.video;
        }else{
            document.querySelector('#modal-img-full').classList.remove('d-none');
            document.querySelector('#modal-video-full').classList.add('d-none');
            document.querySelector('#modal-img-full').src = ths.url_base+"/"+communique.photo;
        }

        document.querySelector('#modal_title').innerHTML = communique.title;
        /* let txt_locations ="";
        communique.bucket_location.forEach(bucket =>{
            txt_locations += bucket.location.name+" - ";

        })
        document.querySelector('#modal_location').innerHTML = txt_locations+filterDate(communique.created_at,'DD-MM-YYYY h:m a'); */
        document.querySelector('#modal-description').innerHTML = communique.description;

        if(communique.link != null){
            document.querySelector('#modal-link').innerHTML = communique.link;
            document.querySelector('#modal-link').href = communique.link;
            document.querySelector("#communique_link").classList.remove("d-none")
        }else{
            document.querySelector("#communique_link").classList.add("d-none")
        }

        if(communique.files.length > 0){
            let link_html = "";

            communique.files.forEach( file =>{
                link_html += `
                    <div class="col-md-4 col-sm-12">
                        <a class="download_files text-link" target="_blank" href="${file.file}"><i class="fas fa-paperclip text-dmi"></i> ${file.name}</a>
                    </div>
                `;
            })

            document.querySelector("#list_files").innerHTML = link_html;
            document.querySelector("#communique_files").classList.remove("d-none")
            ths.visualisity_loading('data',"modal_body");

        }else{
            document.querySelector("#list_files").innerHTML = "";
            document.querySelector("#communique_files").classList.add("d-none")
            ths.visualisity_loading('data',"modal_body");
        }

        if(communique.link != null){
            document.querySelector("#communique_files").classList.remove("d-none")
        }else{
            document.querySelector("#communique_files").classList.add("d-none")
        }

    })
    .catch(error =>{
        console.log("Error > ", error)
    })

}


function showCommunique(_event){
    ths.getInfoCommunique(_event.dataset);
    $('#modal-images').modal('show');
}

$(document).on('hidden.bs.modal', function (event) {
    document.querySelector(".public-video").src="";
})
