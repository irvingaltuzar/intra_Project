@component('mail::message')

<img width="50" src="https://grupodmi.com.mx/intranet/img/comunicados/tarjeta_birthday.png" alt="">
<br>
<br>
<span>
    Hola <strong>{{$general_data['birthday_boy']}}</strong>,
</span>
<br>
<br>
<span>
    ¡Feliz Cumpleaños! <br>
</span>
<br>
<b>{{$general_data['congratulator_boy']}}</b> te ha enviado una felicitación:

<br>

<i>"{!! $general_data['message'] !!}"</i>
<br>
Da click para <a href="{{$general_data['link']}}">ver más</a>
<br>
<br>


@endcomponent
