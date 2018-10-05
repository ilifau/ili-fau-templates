jQuery(document).ready(function($) {
    $('.slick-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        centerMode: false,
        touchMove: true,
        speed: 1000,
        infinite: true,
        focusOnSelect: false,
        accessibility: true,
        adaptiveHeight: false,
        fade: ilifautpl_slider_fade == 1 || false,
        dots: ilifautpl_slider_has_dots == 1 || false,
        arrows: ilifautpl_slider_has_arrows == 1 || false,
        prevArrow: $('.ilifautpl-arrow.prev'),
        nextArrow: $('.ilifautpl-arrow.next'),
    });
});