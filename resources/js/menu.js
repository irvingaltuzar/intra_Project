$(window).scroll(function() {
	if ($(window).scrollTop() > 50) {
		$('header#intranet-menu').addClass('scrolled');
	}
	else{
		$('header#intranet-menu').removeClass('scrolled');
	}
});



$('header#intranet-menu .hamburguer').click(function(){
	$('header#intranet-menu .menu').slideToggle();
	$('header#intranet-menu .hamburguer').toggleClass('close');
	$('header#intranet-menu').toggleClass('open');
});