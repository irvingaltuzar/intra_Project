var ths = this;
var general_data = {
    see_more:{
        params:{
            limit: 3
        }
    }
}

$('.btn-see-more').on('click',function (){

    if(this.dataset.next_page_url != ''){
        ths.getCommuniques(this.dataset)

    }

})

function getCommuniques (_dataset){

    let data = _dataset;
    ths.visualisity_loading('loading',_dataset.type_communique);

    axios.get(data.next_page_url,{
        params:{
            limit:3
        }
    })
    .then( response =>{
        let {data,next_page_url} = response.data;
        let tr_html = "";
        data.forEach(communique =>{
            tr_html += `
                     <tr>
                        <td>
                            <div class="container-checkbox form-check">
                                <span class=""><i class="fas fa-caret-right text-dmi"></i></span>
                                <a >
                                    <label class="form-check-label" for="checkbox-1">
                                        ${communique.title}
                                        ${communique.communique_date == communique.current_date ? '<i class="fas fa-star yellow-star"></i>' : ''}
                                    </label>
                                </a>
                            </div>
                        </td>
                        <td>
                            <div class="actions">
                                <button class="text-dark images-link" onclick="showCommunique(this)" data-id_communique="${communique.id}"><i class="fas fa-eye"></i></button>
                            </div>
                        </td>
                    </tr>`;
        })

        if(next_page_url == null){
            document.querySelector("#a_link_"+_dataset.type_communique).dataset.next_page_url = "";
            document.querySelector("#a_link_"+_dataset.type_communique).classList.add("d-none");
        }else{
            document.querySelector("#a_link_"+_dataset.type_communique).dataset.next_page_url = next_page_url;
        }
        
        $("#data_"+_dataset.type_communique).before(tr_html)


        ths.visualisity_loading('data',_dataset.type_communique);
    })
    .catch(error =>{
        console.log("Error communications", error);
        ths.visualisity_loading('data',_dataset.type_communique);
        ths.notification({type:'error',text:error})
    })


}