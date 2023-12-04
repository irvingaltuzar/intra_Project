@component('mail::message')
# {{$general_data['subject']}}
<br>
<a href="{{$general_data['link']}}">
<div style="background:black;">
<table border="0" style="background:black;color:white;">
<tr>
<td>
<div  align="center">
<img width="150" src="https://www.grupodmi.com.mx/intranet/img/comunicados/1671169531_council.png" alt="">
<br>
</div>
</td>
<td></td>
<td>
<strong>DESCANSE EN PAZ</strong>
<br>
{{strftime("%d %B", strtotime($general_data['condolence_date']))}}
<br>
<br>
<div align="center">
<img width="30" src="https://www.grupodmi.com.mx/intranet/img/comunicados/1671214805_council.png" alt="">
<br>
Yo les daré consuelo: convertiré su llanto en alegría, y les daré una alegría mayor que su dolor.
<br>
<br>
-Jeremías 31:13
<br>
<br>
<img width="140" src="https://www.grupodmi.com.mx/intranet/img/comunicados/1671214816_council.png" alt="">
<br>
<br>
Acompañamos <strong>{{$general_data['accompanies']}}</strong> en estos momentos de profunda tristeza por la sensible pérdida de <strong>{{$general_data['condolence']}}</strong>.
<br>
<br>
Elevamos nuestras oraciones por su eterno descanso y por el consuelo para toda la familia.
<br>
<br>
<br>
</div>

</td>
</tr>
</table>

</div>

</a>

</div>


@endcomponent
