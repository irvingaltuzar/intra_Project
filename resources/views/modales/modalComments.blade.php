<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="messageModalLabel"><i class="fas fa-birthday-cake icon-circle"></i> <span id="birthday-name" class=""></span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="content">
                <input id="asset_url" type="hidden" value="{{asset(url(''))}}">
                @csrf
                <input type="hidden" id="type_message">
                <textarea id="txt_comments" placeholder="Pensamos que te gustaría desearle un feliz cumpleaños" class="form-control" name="summary-ckeditor"></textarea>
                <div class="row m-1 text-center">
                    <div class="offset-sm-8 col-sm-2 offset-md-8 col-md-2">
                        <button type="button" class="btn btn-discard" onclick="clear_comments()">
                            Descartar
                        </button>
                    </div>
                    <div class="col-sm-2 col-md-2">
                        <button type="button" class="btn btn-primary"  id="btn_modal_comments"  onclick="add_comments()">
                            Comentar
                        </button>
                    </div>
                </div>                
                <div class="row" id="data_comments_body" style="display: none;">
                    <div class="row m-1">
                        <div class="row">
                            <table id="list_comments">
                            </table>
                        </div>
                        <div class="row d-none" id="see_more_comments">
                            <div class="col-sm-12 col-md-12 m-1">
                                <a class="btn btn-link" onclick="see_more_comments()">Ver más</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <span id="no_comments" class="d-none publ-no-comments">
                                No hay comentarios, se tú el primero en felicitarlo(a)
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row" id="comments_publishing" style="display:none;">
                    <div class="col-sm-12 col-md-12 m-1">
                        <span class="comment-publishing">Publicando...</span>
                    </div>
                </div>
                <div class="row justify-content-center" id="loading_comments_body">
                    <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<link href="{{asset('pluginEmojiOneArea/emojionearea.min.css')}}" rel="stylesheet">
{{-- <script src="{{asset('pluginEmojiOneArea/jquery.min.js')}}"></script> --}}
<script src="{{asset('js/dmi/comments.js')}}"></script>
<script type="text/javascript" src="{{asset('pluginEmojiOneArea/emojionearea.js')}}" defer></script>


<script>
    $(document).ready(function() {
            $("#txt_comments").emojioneArea({
                pickerPosition: "top",
                search:false,
                useInternalCDN: true,
                tonesStyle: 'radio',
                shortnames: true,
            });
        });
</script>