@extends('backend.layouts.app')
@section('title')
    <title>Beneficios | Intranet DMI</title>
@endsection
@section('script')
    <link href="{{ asset('css/dark-footer.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div>
        <a type="button" class="btn btn-primary m-3" href="{{route('admin.addBenefit')}}"><i class="fas fa-plus"></i> Crear Beneficio</a>
        <div class="mt-4">
            @foreach ($benefits as $benefit)
                <div class="border row bg-white rounded m-3 shadow p-3 justify-content-center align-items-center">
                    <div class="col-md-2 text-center">
                        <img src="{{asset('storage/'.$benefit->photo)}}" class="rounded-circle w-100 h-100 border">
                    </div>
                    <div class="col-md-8">
                        <h3>{{$benefit->title}}</h3>
                        <h6>{{$benefit->subtitle}}</h6>
                        <p>Creado por: {{$benefit->user->name}} {{$benefit->user->last_name}}</p>
                    </div>
                    <div class="col-md-2 text-center ">
                        <button class="btn edit btn-outline-success"><i class="fas fa-pencil-alt"></i></button>
                        <button class="btn delete btn-outline-danger"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            @endforeach
            @if($benefits->links() !="" )
                <div class="justify-content-center align-items-center m-3 text-end">
                    <a href="{{$benefits->previousPageUrl()}}" class="btn {{($benefits->previousPageUrl()!='')? 'btn-outline-primary':'btn-outline-secondary disabled'}}">
                        <i class="fas fa-angle-left"></i><i class="fas fa-angle-left"></i>
                    </a>
                    <a href="{{$benefits->nextPageUrl()}}" class="btn {{($benefits->nextPageUrl()!='')? 'btn-outline-primary':'btn-outline-secondary disabled'}}">
                        <i class="fas fa-angle-right"></i><i class="fas fa-angle-right"></i>
                    </a>
                    <div>{{$benefits->currentPage()}} de {{$benefits->total()}} páginas</div>
                </div>
            @endif
        </div>
    </div>
@endsection