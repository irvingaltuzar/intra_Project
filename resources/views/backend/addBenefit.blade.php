@extends('backend.layouts.app')
@section('title')
    <title>Agregar beneficio | Intranet DMI</title>
@endsection
@section('script')
    <link href="{{ asset('css/dark-footer.css') }}" rel="stylesheet">
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}" defer></script>
@endsection
@section('content')
    <div>
        <div class="bg-white shadow p-4">
            <h2 class="text-center pt-2 pb-3">Agregar beneficio</h2>
            <form method="POST" action="/profile" class="pb-4">
                <div class="row">
                    <div class="col-md-6 pb-3">
                        <label for="title" class="fw-bold">Título:</label>
                        <input id="title" type="text" class=" form-control @error('title') is-invalid @enderror">
                        @error('title')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 pb-3">
                        <label for="subtitle" class="fw-bold">Subtítulo:</label>
                        <input id="subtitle" type="text" class=" form-control @error('subtitle') is-invalid @enderror">
                        <input id="type" type="hidden" value="beneficio" class=" form-control @error('type') is-invalid @enderror">
                        @error('subtitle')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 pb-3">
                        <label for="link" class="fw-bold">Liga:</label>
                        <input id="link" type="url" class=" form-control @error('link') is-invalid @enderror">
                        @error('link')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 pb-3">
                        <label for="link" class="fw-bold">Fecha de vigencia:</label>
                        <input id="link" type="date" class=" form-control @error('link') is-invalid @enderror">
                        @error('link')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12 pb-3">
                        <label for="content" class="fw-bold">Contenido:</label>
                        <textarea class="form-control" id="summary-ckeditor" name="content"></textarea>
                    </div>
                </div>
                <div class="text-end">
                    <a href="{{route('admin.benefit')}}" class="btn btn-danger">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        ClassicEditor
            .create( document.querySelector( '#summary-ckeditor' ), {
                toolbar: [ 'heading', '|', 'bold','alignment', 'italic', 'link','alignment', 'bulletedList', 'numberedList', 'blockQuote','undo', 'redo'  ],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Párrafo', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Título 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Título 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Título 3', class: 'ck-heading_heading3' }
                    ]
                }
            } )
            .catch( error => {
                console.log( error );
            } );
    </script>
@endsection