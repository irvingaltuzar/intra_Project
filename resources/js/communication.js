$(document).ready(function(){
    $('.carousel-campaigns .owl-carousel').owlCarousel({
        loop: true,
        margin: 25,
        autoplay: true,
        autoplayTimeout: 3000,
        smartSpeed: 1000,
        nav: true,
        navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
        /*dots: !0,
        dotsEach: !0,*/
        responsive:{
            0: { items: 1 },
            800: { items: 2 },
            991: { items: 3 }
        }
    });
});