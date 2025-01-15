$(window).scroll(function() {
	if ($(window).scrollTop() > 50) {
		$('header#intranet-menu').addClass('scrolled');
	}
	else{
		$('header#intranet-menu').removeClass('scrolled');
	}
});

$(document).ready(function(){
	$('header#intranet-menu [data-toggle="tooltip"]').tooltip({
		placement: 'right',
		container: '#intranet-menu'
	});
});

$('header#intranet-menu .hamburguer').click(function(){
	$('header#intranet-menu .menu').slideToggle();
	$('header#intranet-menu .hamburguer').toggleClass('close');
	$('header#intranet-menu').toggleClass('open');
});