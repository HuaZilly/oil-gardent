(($) => {
    const $window = $(window);

    /***
     * Hero slider
     */

    function moveSlide() {
        const current = $("#hero .slide.active");
        let next = current.next();
        if (next.length === 0) next = $("#hero .slide").first();

        current.removeClass('active');
        next.addClass('active');

        const dotCurrentActive = $('.custom-dots .dot-active');
        let dotNext = dotCurrentActive.next(),
            dotCurrent = $(".custom-dots  .dot-slide");
        if (dotNext.length === 0) dotNext = dotCurrent.first();
        dotCurrentActive.removeClass('dot-active');
        dotNext.addClass('dot-active');

        dotCurrent.on('click', function (){
            clearInterval(setTime)
        })

    }

    if ($("#hero .slide").length > 1) {
        let timer = setInterval(moveSlide, 5000);
        let dotCurrent = $(".custom-dots  .dot-slide");
        dotCurrent.on('click', function () {
            clearInterval(timer);
            timer = setInterval(moveSlide, 5000);

            let slideIndex = $(this).attr('slideIndex'),
                slideHeroIndex = $('[slideIndex='+ slideIndex +']'),
                heroBanner = $('#hero'),
                heroSlide = heroBanner.find(slideHeroIndex);

            dotCurrent.removeClass('dot-active');
            $(this).addClass('dot-active');
            $(heroBanner.find('.slide')).removeClass('active');
            heroSlide.addClass('active')
        });
    }

    /***
     * Health attrs fadein
     */

    $("#health-benefits .health-attrs").addClass('initialised').on("inview", function(e,v) {
        if (v) {
            $(this).addClass('inview');
        } else {
            $(this).removeClass('inview');
        }
    });

    /***
     * About us selector
     */

    $("#about-us h3").click(function() {
        if (!$(this).parent().hasClass("active")) {
            const blocks = $(this).parents('.blocks');
            const block = $(this).parent();

            blocks.children(".active").removeClass('active');
            block.addClass('active');
        }
    });

    /***
     * Icons scrolling
     */
    const $scrollContainer = $('[data-overflow-scroll]');
    const $scrollTrack = $scrollContainer.find('[data-overflow-track]');
    const $scrollCtrls = $scrollContainer.find('[data-overflow-control]');

    let timer;

    const iconsScrollHandler = event => {
        if (timer) clearTimeout(timer);

        timer = setTimeout(() => {
            // left end
            if ($scrollTrack.scrollLeft() < 10) {
                $scrollContainer.addClass('is-left');
            } else {
                $scrollContainer.removeClass('is-left');
            }

            // right end
            if ($scrollTrack.scrollLeft() + $scrollContainer.width() > $scrollTrack[0].scrollWidth - 10) {
                $scrollContainer.addClass('is-right');
            } else {
                $scrollContainer.removeClass('is-right');
            }
        }, 200);
    };

    const iconScrollActions = event => {
        event.preventDefault();
        console.log('click');

        const $target = $(event.currentTarget);

        $target.animate(
            {
                scrollLeft: 100
            },
            {
                duration: 300,
                // complete: () => {
                //     targetElement.animate({ scrollLeft: 0 },
                //     {
                //         duration: speed,
                //         complete: function () {
                //             animatethis(targetElement, speed);
                //         }
                //     });
                // }
            });
    };

    $('.health-attrs-ctrl.right').click(function(){
        var pos = $('div.health-attrs-track').scrollLeft() + 150;
        $('div.health-attrs-track').scrollLeft(pos);
    });

    $('.health-attrs-ctrl.left').click(function() {
        var pos = $('div.health-attrs-track').scrollLeft() - 150;
        $('div.health-attrs-track').scrollLeft(pos);
    });

    $window.on('load resize', event => {
        if (timer) clearTimeout(timer);

        timer = setTimeout(() => {
            if ($scrollContainer.data('overflow-scroll') === 'horizontal') {
                if ($scrollTrack[0].offsetWidth < $scrollTrack[0].scrollWidth) {
                    $scrollContainer.addClass('is-scrollable');
                    iconsScrollHandler();
                    $scrollTrack.on('scroll', iconsScrollHandler);
                    $scrollCtrls.on('click', iconScrollActions);
                } else {
                    $scrollContainer.removeClass('is-scrollable');
                    $scrollTrack.off('scroll', iconsScrollHandler);
                    $scrollCtrls.off('click', iconScrollActions);
                }
            }
        }, 200);
    });
    

    /***
     * Parallax scrolling
     */
    const $parallax = $('[data-parallax]');
    const parallaxBaseSpeed = 0.1;

    $window.on('load scroll', event => {
        $parallax.map((index, item) => {
            const windowHeight = $window.height();
            const buffer = windowHeight / 3;
            const $item = $(item);
            const itemRect = item.getBoundingClientRect();

            if (itemRect.top < windowHeight + buffer && itemRect.top + itemRect.height > -1 * buffer) {
                let speed = $item.data('parallax-speed') ? parseFloat($item.data('parallax-speed')) : 1;

                if ($window.width() < 768) {
                    speed = ($item.data('parallax-mobile-speed') !== undefined) ? parseFloat($item.data('parallax-mobile-speed')) : speed;
                }

                const scale = $item.data('parallax-scale') ? parseFloat($item.data('parallax-scale')) : 1;
                const position = $item.data('parallax-position');
                const itemDiff = Math.floor(itemRect.top - $window.height() / 2);
                const parallaxOffset = Math.floor(itemDiff * speed * parallaxBaseSpeed);
    
                if (position === 'center' || position === 'centre') {
                    $item.css({
                        transform: `translate3d(-50%, ${parallaxOffset - Math.floor($item.height() / 2)}px, 0) scale(${scale})`
                    });
                } else {
                    $item.css({
                        transform: `translate3d(0, ${parallaxOffset}px, 0) scale(${scale})`
                    });
                }
            }
        });
    });

})(jQuery)