var ths = this;
var general_data = {
    pagination:{},
    get_list_directory:{
        order_by:'asc',
        per_page:20,
        search:{
            isSearch:false,
            text:""
        }
    }
};


function getPage(_page=1){
    
    ths.view_loading('loading','directorio');

    axios.get('get_list_directory?page='+_page,{
        params:ths.general_data.get_list_directory
    })
    .then(response =>{  
        let resp = response.data;
        if(resp.success == 1){
            ths.create_directory(resp.data.data);
            ths.paginate_control(resp.data);
        }
        ths.view_loading('data','directorio');
    })
    .catch( error =>{
        ths.view_loading('data','directorio');
        ths.notification({type:'error',text:'Error al cargar los registros '+error})
    })

}

function create_directory(_users){
    let rows_html = "";
    
    if(_users.length > 0){
        _users.forEach(user => {
            rows_html += `
                        <tr>
                            <td>${user.name} ${user.last_name}</td>
                            <td>${user.deparment} / ${user.position_company_full}</td>
                            <td>${user.location}</td>
                            <td>${user.email}</td>
                            <td>${user.extension}</td>
                        </tr>
            `;
        });
    
        document.querySelector("#tbody_directorio").innerHTML = rows_html;
    }else{
        ths.no_data_found();
    }
    
}

function view_loading(event,element){
    if(event == "data"){
        $('#loading_'+element).hide();
        $('#data_'+element).fadeIn(300);
    }else if(event == "loading"){
        $('#data_'+element).hide();
        $('#loading_'+element).fadeIn(300);
    }

}

function no_data_found(){
    let row_html=` <tr>
                        <td colspan="5" class="no-data-found">
                            <i class="fas fa-ban icon-no-data-found p-1"></i><br>
                            No se encontró ningún registro.
                        </td>
                    </tr>`;

    document.querySelector("#tbody_directorio").innerHTML = row_html;
}


$('#order_by').change(function(){
    let order_by_selected = document.querySelector('#order_by').value;
    ths.general_data.get_list_directory.order_by = order_by_selected;
    ths.getPage();
})

$('#per_page').change(function(){
    let per_page_selected = document.querySelector('#per_page').value;
    ths.general_data.get_list_directory.per_page = per_page_selected;
    ths.getPage();
})


$('#txt_search').on('input',function (e) {
    let txt_search = this.value;
    
    ths.general_data.get_list_directory.search.isSearch = true;
    ths.general_data.get_list_directory.search.text = txt_search;
    ths.getPage();
},);



$('.clear_search').on('click',function(){
    ths.general_data.get_list_directory.search.isSearch = false;
    ths.general_data.get_list_directory.search.text = "";
    document.querySelector('#txt_search').value="";
    ths.getPage();                
})