<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Bienvenidos | Grupo DMI</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        {{-- <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> --}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="{{ asset('/welcome/css/normalize.css') }}" />
		<link rel="stylesheet" type="text/css" href="{{ asset('/welcome/css/demo.css') }}" />
		<link rel="stylesheet" type="text/css" href="{{ asset('/welcome/css/component.css') }}" />
		<!--[if IE]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
			<![endif]-->
		<script>
		document.documentElement.className = 'js';
		</script>
	</head>
	<body class="demo-1">
		<main>
			<!-- Initial markup -->
			<div class="segmenter" style="background-image: url({{asset("welcome/img/1.jpg")}})"></div>
			<h1 style="font-size: 4vw;pandding:unset;top: -15%;" class="grupo trigger-headline trigger-headline--hidden">
                <span style="text-shadow: black 7px 0px 10px;padding:0 1vw;">G</span>
                <span style="text-shadow: black 7px 0px 10px;padding:0 1vw;">R</span>
                <span style="text-shadow: black 7px 0px 10px;padding:0 1vw;">U</span>
                <span style="text-shadow: black 7px 0px 10px;padding:0 1vw;">P</span>
                <span style="text-shadow: black 7px 0px 10px;padding:0 1vw">O</span>
            </h1>
                <br>
            <h1 class="dmi trigger-headline trigger-headline--hidden">
                <span style="text-shadow: black 7px 0px 10px;">D</span>
                <span style="text-shadow: black 7px 0px 10px;">M</span>
                <span style="text-shadow: black 7px 0px 10px;">I</span></h1>
		</main>
		<script src="{{asset("welcome/js/anime.min.js")}}"></script>
		<script src="{{asset("welcome/js/imagesloaded.pkgd.min.js")}}"></script>
		<script src="{{asset("welcome/js/main.js")}}"></script>
		<script>

            var ths = this;
            
            (function() {
			//ths.getUser();
                var headline = document.querySelector('.dmi'),headline1 = document.querySelector('.grupo')
                    trigger = document.querySelector('.btn--trigger'),
                    segmenter = new Segmenter(document.querySelector('.segmenter'), {
                        onReady: function() {
                            segmenter.animate();
                            headline.classList.remove('trigger-headline--hidden');
                            headline1.classList.remove('trigger-headline--hidden');
                            
                            setTimeout(() => {
                    
                                ths.getUser();

                            }, 2000);
							
                        }
                    });




            })();

            function getUser(){
                $.ajax({
                        url: 'http://dmi-srvdev:81/api/user/me',
                        type: 'GET',
                        xhrFields: {
                            withCredentials: true
                        },
                        success: function (data) {
                            ths.passAd(data.split('\\')[1]);
                            // Manejar la respuesta exitosa
                        },
                        error: function (xhr, status, error) {
                            // Manejar el error
                            console.error('Error en la solicitud: ' + status);
                            window.location.href = "/login";
                        }
                    });

                //this.passAd("eladio.perez");
            }

            function passAd(_data){
                $.ajax({
                        url: 'login-user-ad',
                        type: 'POST',
                        data: {
                            'user': _data,
                            '_token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        success: function (data) {
                            //console.log(data);
                            
                            if(data.success == 1){
                                let request_uri= ths.readCookie('request_uri');
                                window.location.href = request_uri;
                            }else{
                                window.location.href = "/login";
                            }
                        },
                        error: function (xhr, status, error) {
                            // Manejar el error
                            console.error('Error en la solicitud: ' + status);
                            //window.location.href = "/login";
                        }
                    });
            }

            function readCookie(name) {

                var nameEQ = name + "="; 
                var ca = document.cookie.split(';');

                for(var i=0;i < ca.length;i++) {

                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1,c.length);
                if (c.indexOf(nameEQ) == 0) {
                    return decodeURIComponent( c.substring(nameEQ.length,c.length) );
                }

                }

                return null;

            }

		</script>
	</body>
</html>