jQuery(document).ready(function($) {
    $('.slick-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
        centerMode: false,
        touchMove: true,
        speed: 1000,
        infinite: true,
        focusOnSelect: false,
        adaptiveHeight: false,
        fade: false,
    });
});