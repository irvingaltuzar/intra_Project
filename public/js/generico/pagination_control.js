function paginate_control(_pagination){

    let pagination = _pagination;
    let total_page = Math.ceil((pagination.total/pagination.per_page));
    let start = 1,next_page=1,previous_page=1;
    let end = total_page;
    let pages_html="";


    for (let index =start; index <=end; index++) {
        if(index == pagination.current_page){
            pages_html += `<li class="page-item active"><a class="page-link" onclick="getPage(${index})">${index}</a></li>`;
        }else{
            pages_html += `<li class="page-item"><a class="page-link " onclick="getPage(${index})">${index}</a></li>`;
        }
        
	}

    document.querySelector('#list-pages').innerHTML= `<ul class="pagination justify-content-center" >
                                                            <li class="page-item ${pagination.prev_page_url == null ? 'disabled' : ''}">
                                                                <a class="page-link btn-previous" onclick="getPage(${pagination.prev_page_url == null ? '1' : pagination.current_page-1})" aria-label="Previous">
                                                                <span aria-hidden="true">&laquo;</span>
                                                                </a>
                                                            </li>
                                                            ${pages_html}
                                                            <li class="page-item ${pagination.next_page_url == null ? 'disabled' : ''}" >
                                                                <a class="page-link btn-next"onclick="getPage(${pagination.next_page_url == null ? '1' : pagination.current_page+1})" aria-label="Next">
                                                                    <span aria-hidden="true">&raquo;</span>
                                                                </a>
                                                            </li>
                                                        </ul>`;

    
    
}

