@component('mail::message')
# {{$general_data['title']}}

<span>
    {{$general_data['description']}}
</span>
<br>
<br>

<a href="{{$general_data['link']}}" target="_blank">
    <img  width="650" src="{{$general_data['photo']}}" alt="">
</a>
<br>
<br>
<a href="{{$general_data['link']}}">Ver más</a>

@endcomponent
