var ths = this;

$('.btn-see-more').on('click',function (){
    if(this.dataset.next_page_url != ''){
        if(this.dataset.module == "conmmemorative_date"){
            ths.getListConmmemorativeDates(this.dataset)
        }else if(this.dataset.module == "internal_posting"){
            ths.getListInternalPosting(this.dataset)
        }else if(this.dataset.module == "poll"){
            ths.getListPoll(this.dataset)
        }else if(this.dataset.module == "area_notices"){
            ths.getListAreaNotice(this.dataset)
        }else if(this.dataset.module == "policies"){
            ths.getListPolicy(this.dataset)
        }
    }

})

/*******************************************     FECHAS CONMEMORATIVAS     ************************************************** */
function getListConmmemorativeDates(_dataset){

    let data = _dataset;
    ths.visualisity_loading('loading',_dataset.module);

    axios.get(data.next_page_url,{
        params:{
            limit:10,
            expiration_date: this.moment(new Date()).format('YYYY-MM-DD')
        }
    })
    .then( response =>{
        let {data,next_page_url} = response.data;
        let rows_html = "";
        let publicaction_date="";
        data.forEach(element =>{

            publicaction_date = this.moment(element.publication_date).format("DD MMMM");

            rows_html += `
                        <div class="component-article">
                            <div class="header-article">
                                <h3>${element.title}</h3>
                                <span class="date">${publicaction_date}</span>
                            </div>
                            <article class="news">
                                <img src="${url_base+'/'+element.photo}" class="image-news">
                            </article>
                        </div>`;
        })

        if(next_page_url == null){
            document.querySelector("#a_link_"+_dataset.module).dataset.next_page_url = "";
            document.querySelector("#a_link_"+_dataset.module).classList.add("d-none");
        }else{
            document.querySelector("#a_link_"+_dataset.module).dataset.next_page_url = next_page_url;
        }

        $("#data_"+_dataset.module).before(rows_html)


        ths.visualisity_loading('data',_dataset.module);
    })
    .catch(error =>{
        console.log("Error Fechas conmmemorativas", error);
        ths.visualisity_loading('data',_dataset.module);
        ths.notification({type:'error',text:error})
    })


}

/*******************************************     POSTEO INTERNO     ************************************************** */
function getListInternalPosting(_dataset){

    let data = _dataset;
    ths.visualisity_loading('loading',_dataset.module);

    axios.get(data.next_page_url,{
        params:{
            limit:10,
            expiration_date: this.moment(new Date()).format('YYYY-MM-DD')
        }
    })
    .then( response =>{
        let {data,next_page_url} = response.data;
        let rows_html = "";
        let publicaction_date="";
        let files = "";
        data.forEach(element =>{
            publicaction_date = new Intl.DateTimeFormat('es-ES', { month: 'long'}).format(new Date(element.publication_date));

            if(element.files.length > 0){
                files = `<br>
                        <br>
                        <div class="icon-files"><i class="far fa-folder-open"></i> Archivos</div>`;

                element.files.forEach(file =>{
                    files+= `<a href="${url_base+'/'+file.file}"><i class="fas fa-paperclip"></i> ${file.name}</a>
                                <br>`
                })

            }

            rows_html += `
                            <div class="component-article">
                                <div class="header-article">
                                    <h3>${element.title}</h3>
                                    <span class="date">${publicaction_date}</span>
                                </div>
                                <article class="news">
                                    <img src="${url_base+'/'+element.photo}" class="image-news">
                                    ${files}
                                </article>

                            </div>
`;
        })

        if(next_page_url == null){
            document.querySelector("#a_link_"+_dataset.module).dataset.next_page_url = "";
            document.querySelector("#a_link_"+_dataset.module).classList.add("d-none");
        }else{
            document.querySelector("#a_link_"+_dataset.module).dataset.next_page_url = next_page_url;
        }

        $("#data_"+_dataset.module).before(rows_html)


        ths.visualisity_loading('data',_dataset.module);
    })
    .catch(error =>{
        console.log("Error posteo interno", error);
        ths.visualisity_loading('data',_dataset.module);
        ths.notification({type:'error',text:error})
    })


}

/*******************************************     ENCUESTAS     ************************************************** */
function getListPoll(_dataset){

    let data = _dataset;
    ths.visualisity_loading('loading',_dataset.module);

    axios.get(data.next_page_url,{
        params:{
            limit:10,
            expiration_date: this.moment(new Date()).format('YYYY-MM-DD')
        }
    })
    .then( response =>{
        let {data,next_page_url} = response.data;
        let rows_html = "";
        let publicaction_date="";
        data.forEach(element =>{

            publicaction_date = new Intl.DateTimeFormat('es-ES', { month: 'long'}).format(new Date(element.created_at));

            rows_html += `
                        <div class="component-article">
                            <div class="header-article">
                                <h3>${element.title}</h3>
                                <span class="date">${publicaction_date}</span>
                            </div>
                            <article class="news">
                                <img src="${url_base+'/'+element.photo}" class="image-news">
                                <br>
                                <br>
                                <div class="icon-files"><i class="far fa-folder-open"></i> Link</div>
                                <a href="${element.link}" target="_blank">${element.link}</a>
                            </article>
                        </div>`;
        })

        if(next_page_url == null){
            document.querySelector("#a_link_"+_dataset.module).dataset.next_page_url = "";
            document.querySelector("#a_link_"+_dataset.module).classList.add("d-none");
        }else{
            document.querySelector("#a_link_"+_dataset.module).dataset.next_page_url = next_page_url;
        }

        $("#data_"+_dataset.module).before(rows_html)


        ths.visualisity_loading('data',_dataset.module);
    })
    .catch(error =>{
        console.log("Error posteo interno", error);
        ths.visualisity_loading('data',_dataset.module);
        ths.notification({type:'error',text:error})
    })


}


/*******************************************     AVISOS DE AREA     ************************************************** */
function getListAreaNotice(_dataset){

    let data = _dataset;
    ths.visualisity_loading('loading',_dataset.module);

    axios.get(data.next_page_url,{
        params:{
            limit:10,
            expiration_date: this.moment(new Date()).format('YYYY-MM-DD')
        }
    })
    .then( response =>{
        let {data,next_page_url} = response.data;
        let rows_html = "";
        let publicaction_date="";
        let files="";
        let link_html="";
        let video_html="";
        data.forEach(element =>{

            publicaction_date = this.moment(element.created_at).format("DD MMMM");
            if(element.files.length > 0){
                files = `<br>
                        <br>
                        <div class="icon-files"><i class="far fa-folder-open"></i> Archivos</div>`;

                element.files.forEach(file =>{
                    files+= `<a href="${url_base+'/'+file.file}"><i class="fas fa-paperclip"></i> ${file.name}</a>
                                <br>`
                })

            }

            if(element.link != null){
                link_html = `<a href="${element.link}" target="_blank"><i class="fa fa-link"></i> ${element.link}</a>
                        <br>`
            }else{
                link_html="";
            }

            if(element.video != null){
                video_html = `<a style="cursor: pointer" onclick="showVideoModal('${element.title}','${element.video}')"><i class="fas fa-video"></i> Da click aquí para visualizar el vídeo</a>
                                <br>`
            }else{
                video_html="";
            }

            rows_html += `
                            <div class="component-article">
                                <div class="header-article">
                                    <h3>${element.title}</h3>
                                    <span class="date">${publicaction_date}</span>
                                </div>
                                <article class="news">
                                    ${link_html}
                                    ${video_html}
                                    <br>
                                    <img src="${url_base+'/'+element.photo}" class="image-news">
                                    ${files}
                                </article>

                            </div>`;
        })

        if(next_page_url == null){
            document.querySelector("#a_link_"+_dataset.module).dataset.next_page_url = "";
            document.querySelector("#a_link_"+_dataset.module).classList.add("d-none");
        }else{
            document.querySelector("#a_link_"+_dataset.module).dataset.next_page_url = next_page_url;
        }

        $("#data_"+_dataset.module).before(rows_html)


        ths.visualisity_loading('data',_dataset.module);
    })
    .catch(error =>{
        console.log("Error Fechas conmmemorativas", error);
        ths.visualisity_loading('data',_dataset.module);
        ths.notification({type:'error',text:error})
    })


}

function showVideoModal(_title,_src){

    $('#modal-video').modal('show');
    document.querySelector('#modal_video_title').innerHTML = _title;
    document.querySelector('#modal_video_full').src = ths.url_base+"/"+_src;

}
$('#modal-video').on('hidden.bs.modal', function() {
    document.querySelector('#modal_video_full').src ="";
    document.querySelector('#modal_video_title').innerHTML ="";
  })

/*******************************************     POLITICAS     ************************************************** */
function getListPolicy(_dataset){

    let data = _dataset;
    ths.visualisity_loading('loading',_dataset.module);

    axios.get(data.next_page_url,{
        params:{
            limit:2,
            expiration_date: this.moment(new Date()).format('YYYY-MM-DD')
        }
    })
    .then( response =>{
        let {data,next_page_url} = response.data;
        let rows_html = "";
        let publicaction_date="";
        let files="";
        data.forEach(element =>{

            publicaction_date = this.moment(element.created_at).format("DD MMMM");
            if(element.files.length > 0){
                files = `<br>
                        <br>
                        <div class="icon-files"><i class="far fa-folder-open"></i> Archivos</div>`;

                element.files.forEach(file =>{
                    files+= `<a href="${url_base+'/'+file.file}"><i class="fas fa-paperclip"></i> ${file.name}</a>
                                <br>`
                })

            }

            rows_html += `
                            <div class="component-article">
                                <div class="header-article">
                                    <h3>${element.title}</h3>
                                    <span class="date">${publicaction_date}</span>
                                </div>
                                <article class="news">
                                    <img src="${url_base+'/'+element.photo} class="image-news">
                                    ${files}
                                </article>
                            </div>`;
        })

        if(next_page_url == null){
            document.querySelector("#a_link_"+_dataset.module).dataset.next_page_url = "";
            document.querySelector("#a_link_"+_dataset.module).classList.add("d-none");
        }else{
            document.querySelector("#a_link_"+_dataset.module).dataset.next_page_url = next_page_url;
        }

        $("#data_"+_dataset.module).before(rows_html)


        ths.visualisity_loading('data',_dataset.module);
    })
    .catch(error =>{
        console.log("Error Politicas", error);
        ths.visualisity_loading('data',_dataset.module);
        ths.notification({type:'error',text:error})
    })


}
