function slickCarousel() {
    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.slider-nav',
    });
    $('.slider-nav').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: '.slider-for',
        dots: false,
        arrows: true,
        focusOnSelect: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2
                }
            }
        ]
    });
}

function destroySlickCarousel(){
    $('.slider-for').slick('destroy');
    $('.slider-nav').slick('destroy');
}

slickCarousel();

document.getElementById('tab-information-capsules').addEventListener('shown.bs.tab', function (event) {
    destroySlickCarousel();
    slickCarousel();
})

document.getElementById('tab-general-information').addEventListener('shown.bs.tab', function (event) {
    destroySlickCarousel();
    slickCarousel();
})

document.getElementById('collapse-information-capsules').addEventListener('shown.bs.collapse', function () {
    destroySlickCarousel();
    slickCarousel();
})

document.getElementById('collapse-general-information').addEventListener('shown.bs.collapse', function () {
    destroySlickCarousel();
    slickCarousel();
})