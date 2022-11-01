(($) => {
    
        //console.log($(window).width());

        var slidestoshow    = 1;
        var slidestoscroll  = 1;
        if ($(window).outerWidth() >= 960) {
            slidestoshow    = 3;
            slidestoscroll  = 3;
        }

        if ($(window).outerWidth() <= 500) {
            slidestoshow    = 1;
            slidestoscroll  = 1;
        }

        if ($(window).outerWidth() > 500 && $(window).outerWidth() < 960) {
            slidestoshow    = 2;
            slidestoscroll  = 2;
        }

        $(".slick-carrousel").slick({
            //dots: true,
            arrows: true,
            infinite: true,
            slidesToShow: slidestoshow,
            slidesToScroll: slidestoscroll,
        });

})(jQuery);