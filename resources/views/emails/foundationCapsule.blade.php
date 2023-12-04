@component('mail::message')
# {{$general_data['title']}}

<span>
    {{$general_data['description']}}
</span>
<br>
@if($general_data['link_data'] != null)
<a href="{{$general_data['link_data']}}" target="_blank">
    Enlace adjunto
</a>
<br>
@endif
@if($general_data['link_video'] != null)
<a href="{{url($general_data['link_video'])}}" target="_blank">
    Da click aquí para visualizar el vídeo
</a>
<br>
@endif
<br>
<a href="{{$general_data['link']}}">Ver más</a>

<a href="{{$general_data['link']}}" target="_blank">
    <img  width="650" src="{{$general_data['photo']}}" alt="">
</a>
<br>
<br>


@endcomponent
