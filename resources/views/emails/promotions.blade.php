@component('mail::message')
# {{$general_data['title']}}
<br>
Estimado colaborador,
<br>
<br>
<div class="text-justify">
Te informamos que el colaborador <strong>{{$general_data['user']}}</strong><br>
ascendió al puesto de <strong>{{$general_data['new_position_company']}}</strong><br>
<br>
REPORTARÁ A: <strong>{{$general_data['user_top']}}</strong>
<br>

Esperemos se integre rápidamente a sus nuevas funciones,
desenadoles el mayor de los éxitos.

<span style="color:#00aeef;">
<strong>{{strftime("%d %B", strtotime($general_data['updated_at']))}}</strong>
</span>

<a href="{{$general_data['link']}}">Ver más</a>
</div>


@endcomponent
