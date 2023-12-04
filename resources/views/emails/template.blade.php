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
<br>
@endif


<a href="{{$general_data['link']}}">Ver más</a>

<a href="{{$general_data['link']}}" target="_blank">
    <img width="650" src="{{$general_data['photo']}}" alt="">
</a>

@endcomponent
