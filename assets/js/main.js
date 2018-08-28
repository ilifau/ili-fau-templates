jQuery(document).ready(function($) {
    $('.slick-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        centerMode: false,
        touchMove: true,
        speed: 1000,
        infinite: true,
        focusOnSelect: false,
        adaptiveHeight: false,
        fade: true,
        dots: true,
        arrows: true,
        prevArrow: $('.ilifautpl-arrow.prev'),
        nextArrow: $('.ilifautpl-arrow.next'),
    });
});