$(document).ready(function(){
    $('.carousel-publications .owl-carousel').owlCarousel({
        loop: true,
        margin: 20,
        autoplay: true,
        autoplayTimeout: 3000,
        smartSpeed: 1000,
        nav: !1,
        dots: !0,
        dotsEach: !0,
        responsive:{
            0: { items: 1 },
            500: { items: 2 },
            991: { items: 3 },
            1440: { items: 4 },
        }
    });
});