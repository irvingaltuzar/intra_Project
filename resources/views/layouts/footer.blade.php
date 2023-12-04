<footer class="container-fluid" style="bottom: 0px;
position: fixed!important;
z-index: 1000;font-size: 12px!important;">
	<div class="row">
		<div class="col-sm-12">
			<ul>
				<li>©Copyright Grupo DMI 2022 <span class="ti"><span>-</span> Comunicación interna
				<li><a href="#" onclick="viewAvisoPrivaciodad()">Aviso de privacidad</a></li>
				{{-- <li><a href="{{ route('noticePrivacy') }}">Aviso de privacidad</a></li> --}}
				<li>Soporte <a href="mailto:sit@grupodmi.com.mx" class="email-support">sit@grupodmi.com.mx</a> </li>
				<li>Sugerencias <a href="mailto:comunicacion.interna@grupodmi.com.mx" class="email-support">comunicacion.interna@grupodmi.com.mx</a></li>
			</ul>
		</div>
	</div>
</footer>

<script>
    function viewAvisoPrivaciodad(){
        $('#modal-aviso-privacidad').modal('show');
    }
</script>
