<div class="modal fade" id="reactionsModal" tabindex="-1" aria-labelledby="reactionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Personas que reaccionaron</h5>
            {{-- <span class="birth-btn-congratulation ms-3" id="span_icon_reaction"></span> --}}
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="content" id="div_icon_reaction">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <span class="birth-btn-congratulation ms-3" id="span_icon_reaction"></span> Aún no has reaccionado
                    </div>
                    <br>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr>
                    </div>
                </div>
            </div>
            <div class="content " id="reactionsModal_body_list" style="height: 500px;overflow-x: hidden;">

            </div>            
            <div class="content d-none text-center" id="reactionsModal_body_list_no_data">
                <p>Se el primero en reaccionar</p>
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