<div class="card-header" role="tab" id="heading-blog-notes">
    <a class="collapsed" data-bs-toggle="collapse" href="#collapse-blog-notes" aria-expanded="true"
            aria-controls="collapse-blog-notes">
        <h5 class="mb-0">
            Notas de Blog
        </h5>
    </a>
</div>
<div id="collapse-blog-notes" class="collapse" data-bs-parent="#content-tabs-blog" role="tabpanel"
    aria-labelledby="heading-blog-notes">
    <div class="card-body">
        <div class="row mb-2 justify-content-end">
            <div class="col-md-2 text-end">
                <button class="btn btn-ligth-dmi" onclick="openModal()"><i class="fas fa-plus"></i> Publicar nota</button>
            </div>
        </div>
        <div id="data_notes">
            <div class="content content-blog-notes" id="list_notes">
            </div>
        </div>
        <div class="row m-5" id="loading_notes" style="display:none;">
            <div class="col-sm-12 col-md-12 text-center">
                <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
            </div>
        </div>
        @include('modales.modalPublications')
        <div class="table-responsive">
            @include('componentes_generales.pagination_control')
        </div>
    </div>
</div>

@section('script_footer')
<script src="{{asset('js/dmi/publications.js')}}"></script>
<script src="{{asset('js/generico/pagination_control.js')}}"></script>

<script>

    var _hash = window.location.hash;
    if(_hash == "#pane-blog-notes"){
        document.querySelector("#tab-blog-notes").click()
    }

    var url = new URL(window.location.href);
    var params = new URLSearchParams(url.search);
    params.get("section");
    if(params.get("section") == 'pane-blog-notes'){
        document.querySelector("#tab-blog-notes").click()
    }


    $(document).ready(function(){
        ths.general_data.user = {
            full_name: @json(auth::user()->full_name)
        }
        ths.general_data.pagination = @json($notes);
        if(ths.general_data.pagination.data.length > 0){
            ths.addPublicationHTML(ths.general_data.pagination.data);
        }else{
            ths.emptyNotes();
        }

        ths.paginate_control(ths.general_data.pagination);
    });
</script>

@endsection
