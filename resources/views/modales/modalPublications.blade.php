<div class="modal fade" id="publicationModal" tabindex="-1" aria-labelledby="publicationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="publicationModalLabel">Nueva publicación</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="content">
                @csrf
                <div class="row" id="data_form_note">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="validationServer01" class="form-label">Título <span class="field-required">*</span></label>
                            <input id="txt_title" placeholder="Título de la publicación" class="form-control" name="summary-ckeditor" required>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="validationServer01" class="form-label">Descripción <span class="field-required">*</span></label>
                            <textarea id="txt_description" placeholder="Pensamos que te gustaría desearle un feliz cumpleaños" class="form-control" name="summary-ckeditor" required></textarea>
                        </div>
                    </div>
                    
                    <div class="row m-1 text-center">
                        <div class="offset-sm-8 col-sm-2 offset-md-8 col-md-2">
                            <button type="button" class="btn btn-discard" onclick="clear_form()">
                                Descartar
                            </button>
                        </div>
                        <div class="col-sm-2 col-md-2">
                            <button type="button" class="btn btn-primary" onclick="save_publication()">
                                Comentar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center m-3" id="loading_form_note" style="display:none;">
                    <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<link href="{{asset('pluginEmojiOneArea/emojionearea.min.css')}}" rel="stylesheet">
{{-- <script src="{{asset('pluginEmojiOneArea/jquery.min.js')}}"></script> --}}
{{-- <script src="{{asset('js/dmi/comments.js')}}"></script> --}}
<script type="text/javascript" src="{{asset('pluginEmojiOneArea/emojionearea.js')}}" defer></script>


<script>
    $(document).ready(function() {
            $("#txt_title").emojioneArea({
                pickerPosition: "top",
                search:false,
                useInternalCDN: true,
                tonesStyle: 'radio',
                shortnames: true,
            });
            $("#txt_description").emojioneArea({
                pickerPosition: "top",
                search:false,
                useInternalCDN: true,
                tonesStyle: 'radio',
                shortnames: true,
            });

        });
</script>