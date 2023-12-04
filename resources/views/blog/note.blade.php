@extends('layouts.app')
@section('title')
    <title>DMI Blog | Intranet DMI</title>
@endsection
@section('script')
    {{-- Start- Estilos genéricos necesarios --}}
    <link rel="stylesheet" type="text/css" href="{{asset('css/blog.css')}}">
    <link href="{{ asset('css/light-menu.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/news.css') }}">
    {{-- End - Estilos genéricos necesarios --}}

    <script>
        $(document).ready(function(){
            $('[data-toggle="popover"]').popover();
        });
    </script>
@endsection
@section('content')
<div class="container-fluid ptop-150 content-section">
    <div class="pattern"></div>
    <div class="container">
        <div class="content-blog">
            <div class="row">
                <div class="col-sm-12">
                    <h1 class="title-page"><a class="text-dmi btn-back" href="/blog#pane-blog-notes"><i class="fas fa-arrow-alt-circle-left"></i></a> Blog </h1>

                    <div class="content content-blog-notes">
                        <input id="asset_url" type="hidden" value="{{asset(url(''))}}">
                        {{-- Publicacion Principal --}}
                        <article>
                            <div class="row content-article">
                                <div class="col-md-2 justify-content-center">
                                    <div class="photo">
                                        @if($note->user->photo)
                                            <img onerror="this.src='{{asset('/image/icons/user.svg')}}';" src="{{$$note->user->photo_src}}" class="img-fluid person" width="100">
                                        @else
                                            @if($note->user->sex=='Masculino')
                                                <img src="{{ asset('image/icons/masculino.svg')}}" class="img-fluid person" width="100">
                                            @elseif($note->user->sex=='Femenino')
                                                <img src="{{ asset('image/icons/femenino.svg')}}" class="img-fluid person" width="100">
                                            @else
                                                <img src="{{ asset('image/icons/general.svg')}}" class="img-fluid person" width="100">
                                            @endif
                                        @endif
                                        <div class="col note-nick">
                                            <span class="note-user">
                                                {{$note->user->full_name}}
                                            </span>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-10 justify-content-center align-self-center">
                                    <h3 class="note-title-round">{!! $note->title !!}</h3>
                                    <br>
                                    <div class="description">
                                        {!! $note->description !!}
                                    </div>

                                    <br>

                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 footer">
                                            <div class="note-created">
                                                Publicado {{ date("d M Y",strtotime($note->created_at))}}
                                            </div>
                                        </div>
                                        <div class="offset-md-3 col-md-3 col-sm-6 note-comments">
                                            <i class="fas fa-comment"></i> <span id="note_comment_count">{{$note->comments->count()}}</span>
                                            <i class="fas fa-eye ms-2"></i> {{$note->views}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>


                        <div class="row note-count-comments m-1 ">
                            {{ $note->comments->count() > 0 ? 'Comentarios' : 'Sin comentarios' }}
                        </div>

                        {{-- Comentarios y respuestas--}}
                        <div class="mt-1 mb-5 ">
                            <article>
                                {{-- Lista de comentarios --}}
                                <div id="comment_list">
                                    <div class="row m-3">
                                        <div class="col-md-12 text-center card-no-data">
                                            {{-- <i class="fas fa-comments no-data-icon"></i><br> --}}

                                            <img class="img-svg-20" src="{{asset('/image/icons/post.svg')}}">
                                            <br>
                                            <span class="no-data-user">
                                                {{auth::user()->full_name}}
                                            </span>
                                            <br>
                                            Se el primero en agregar un comentario
                                        </div>
                                    </div>
                                </div>


                                <br>
                                <br>
                                {{-- Respuesta --}}
                                <div class="p-4">
                                    <div class="row">
                                        Tu respuesta
                                    </div>
                                    <div class="row mt-1 ">
                                        <input id="asset_url" type="hidden" value="{{asset(url(''))}}">
                                        @csrf
                                        <input type="hidden" id="type_message">
                                        <textarea id="txt_comments" placeholder="Pensamos que te gustaría agregar un comentario" class="form-control" name="summary-ckeditor"></textarea>
                                        <div class="row m-1 text-center">
                                            <div class="offset-md-10 col-sm-6 col-md-1">
                                                <button type="button" class="btn btn-discard" onclick="clear_comments()">
                                                    Descartar
                                                </button>
                                            </div>
                                            <div class="col-sm-6 col-md-1">
                                                <button type="button" class="btn btn-primary" onclick="add_comments()">
                                                    Comentar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="comments_publishing" style="display:none;">
                                        <div class="col-sm-12 col-md-12 m-1">
                                            <span class="comment-publishing">Publicando...</span>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script_footer')

    <script src="{{asset('js/tabs.js')}}"></script>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

    <link href="{{asset('pluginEmojiOneArea/emojionearea.min.css')}}" rel="stylesheet">
    <script src="{{asset('js/dmi/comments_note.js')}}"></script>
    <script type="text/javascript" src="{{asset('pluginEmojiOneArea/emojionearea.js')}}" defer></script>


    <script>
        $(document).ready(function() {

            $("#txt_comments").emojioneArea({
                pickerPosition: "bottom",
                search:false,
                useInternalCDN: true,
                tonesStyle: 'radio',
                shortnames: true,
            });

        });

        /* Info de la publicacion */
        var ths = this;
        var general_data = {
            headers:{
                publication_id: @json($note->id),
                parent_comments:null,
                publication_section : @json($note->publications_section_id),
                message:""
            },
            pagination:{
                next:0
            },
            comment:{
                    list:@json($note->comments),
                    answer:{
                        active:false,
                        id:null
                    }
                }
        };

        if(ths.general_data.comment.list.length > 0){
            document.querySelector('#comment_list').innerHTML = "";
        }

        ths.paintComments(ths.general_data.comment.list);


    </script>
@endsection
