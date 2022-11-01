/**
 * author Christopher Blum
 *    - based on the idea of Remy Sharp, http://remysharp.com/2009/01/26/element-in-view-event-plugin/
 *    - forked from http://github.com/zuk/jquery.inview/
 */ (function(factory) {
    if (typeof define == 'function' && define.amd) {
        // AMD
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        // Node, CommonJS
        module.exports = factory(require('jquery'));
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function($) {

    /**
     * Showing items on Bag
     */
    function update_cart(){

        function getCookie(name) {
            var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
            var result = '';
            if(v)
            result = v[2];

            return result;
        }

        if(getCookie('bigcommerce_cart_id') !== ''){
            $.ajax({
                url: bigcommerce_config.cart.api_url+"/"+getCookie('bigcommerce_cart_id')+"/",
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': bigcommerce_config.cart.ajax_cart_nonce
                }
            }).done(function(ret) {
                if (ret) {
                    let products = Object.keys(ret.items).length;
                    let count = 0;
                    $.each(ret.items, function(i,a) {
                        count += a.quantity;
                    });
    
                    if(count > 0){
                        $("#menu-item-540").addClass('items-in-cart');
                    }else{
                        $("#menu-item-540").removeClass('items-in-cart');
                    }
    
                    $("#menu-item-540 a").text("");
                    $("#menu-item-540").children('a').append("BAG ("+count+")");
                }
            });
        }else{
            $("#menu-item-540").removeClass('items-in-cart');
            $("#menu-item-540 a").text("BAG");
        }
        
        
    }
    
    update_cart();

    // listen to XHR and find cart items change
    // update bag number
    function refresh_cart() {
        var send = XMLHttpRequest.prototype.send;
        XMLHttpRequest.prototype.send = function() { 
            this.addEventListener('load', function() {
                if (this.responseURL.includes('/wp-json/bigcommerce/v1/cart')) {
                    if (this.response) {
                        const respObj = JSON.parse(this.response);
                        const $cartMenuItem = $('#menu-item-540');
                        const cartItems = Object.values(respObj.items);

                        let totalItems = 0;

                        if (cartItems.length) {
                            cartItems.map(item => {
                                totalItems += item.quantity;
                            });

                            if (totalItems === 0) {
                                $cartMenuItem.find('a').text('BAG');
                            } else {
                                $cartMenuItem.find('a').text(`BAG (${totalItems})`);
                            }
                        } else {
                            $cartMenuItem.find('a').text('BAG');
                        }
                    }
                    // update_cart();
                }
            });
    
            return send.apply(this, arguments)
        }
    };

    refresh_cart();
    
    /**
     * New Product Added on Bag
     */
    // $("button.bc-btn--add_to_cart").click(function(){
    //     setTimeout(function(){
    //         update_cart();
    //     },3000);
    // });

    /**
     * Product removed from Bag
     */
    // $("button.bc-cart-item__remove-button").click(function(){
    //     setTimeout(function(){
    //         update_cart();
    //     },3000);
    // });

    /**
     * When Qty is updated
     */
    // $("input.bc-cart-item__quantity-input").on('change', function(){
    //     setTimeout(function(){
    //         update_cart();
    //     },4000);
    // });
    


    var inviewObjects = [],
        viewportSize, viewportOffset,
        d = document,
        w = window,
        documentElement = d.documentElement,
        timer;

    $.event.special.inview = {
        add: function(data) {
            inviewObjects.push({
                data: data,
                $element: $(this),
                element: this
            });
            // Use setInterval in order to also make sure this captures elements within
            // "overflow:scroll" elements or elements that appeared in the dom tree due to
            // dom manipulation and reflow
            // old: $(window).scroll(checkInView);
            //
            // By the way, iOS (iPad, iPhone, ...) seems to not execute, or at least delays
            // intervals while the user scrolls. Therefore the inview event might fire a bit late there
            //
            // Don't waste cycles with an interval until we get at least one element that
            // has bound to the inview event.
            if (!timer && inviewObjects.length) {
                timer = setInterval(checkInView, 250);
            }
        },

        remove: function(data) {
            for (var i = 0; i < inviewObjects.length; i++) {
                var inviewObject = inviewObjects[i];
                if (inviewObject.element === this && inviewObject.data.guid === data.guid) {
                    inviewObjects.splice(i, 1);
                    break;
                }
            }

            // Clear interval when we no longer have any elements listening
            if (!inviewObjects.length) {
                clearInterval(timer);
                timer = null;
            }
        }
    };

    function getViewportSize() {
        var mode, domObject, size = {
            height: w.innerHeight,
            width: w.innerWidth
        };

        // if this is correct then return it. iPad has compat Mode, so will
        // go into check clientHeight/clientWidth (which has the wrong value).
        if (!size.height) {
            mode = d.compatMode;
            if (mode || !$.support.boxModel) { // IE, Gecko
                domObject = mode === 'CSS1Compat' ? documentElement : // Standards
                d.body; // Quirks
                size = {
                    height: domObject.clientHeight,
                    width: domObject.clientWidth
                };
            }
        }

        return size;
    }

    function getViewportOffset() {
        return {
            top: w.pageYOffset || documentElement.scrollTop || d.body.scrollTop,
            left: w.pageXOffset || documentElement.scrollLeft || d.body.scrollLeft
        };
    }

    function checkInView() {
        if (!inviewObjects.length) {
            return;
        }

        var i = 0,
            $elements = $.map(inviewObjects, function(inviewObject) {
                var selector = inviewObject.data.selector,
                    $element = inviewObject.$element;
                return selector ? $element.find(selector) : $element;
            });

        viewportSize = viewportSize || getViewportSize();
        viewportOffset = viewportOffset || getViewportOffset();

        for (; i < inviewObjects.length; i++) {
            // Ignore elements that are not in the DOM tree
            if (!$.contains(documentElement, $elements[i][0])) {
                continue;
            }

            var $element = $($elements[i]),
                elementSize = {
                    height: $element[0].offsetHeight,
                    width: $element[0].offsetWidth
                },
                elementOffset = $element.offset(),
                inView = $element.data('inview');

            // Don't ask me why because I haven't figured out yet:
            // viewportOffset and viewportSize are sometimes suddenly null in Firefox 5.
            // Even though it sounds weird:
            // It seems that the execution of this function is interferred by the onresize/onscroll event
            // where viewportOffset and viewportSize are unset
            if (!viewportOffset || !viewportSize) {
                return;
            }

            if (elementOffset.top + elementSize.height > viewportOffset.top && elementOffset.top < viewportOffset.top + viewportSize.height && elementOffset.left + elementSize.width > viewportOffset.left && elementOffset.left < viewportOffset.left + viewportSize.width) {
                if (!inView) {
                    $element.data('inview', true).trigger('inview', [true]);
                }
            } else if (inView) {
                $element.data('inview', false).trigger('inview', [false]);
            }
        }
    }

    $(w).on("scroll resize scrollstop", function() {
        viewportSize = viewportOffset = null;
    });

    // IE < 9 scrolls to focused elements without firing the "scroll" event
    if (!documentElement.addEventListener && documentElement.attachEvent) {
        documentElement.attachEvent("onfocusin", function() {
            viewportOffset = null;
        });
    }
}));

window.bigcommerce_i18n.cart.ajax_add_to_cart_success = "Added to bag";

(($) => {
    /* 
     * ATTR Watcher 
     *
     * $(ele).attrchange(function(attrName){
     *      if (attrName === 'class') {
     *          // Do something
     *      }
     * });
     */
    var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;
    $.fn.attrchange = function(callback) {
        if (MutationObserver) {
            var options = {
                subtree: false,
                attributes: true
            };

            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(e) {
                    callback.call(e.target, e.attributeName);
                });
            });

            return this.each(function() {
                observer.observe(this, options);
            });

        }
    }

    $(".btn.secondary").each(function() {
        $(this).data('btn-label', $(this).text());
    });
    
    /***
     * Scrolling functions
     */
    $(window).scroll(function() {
        const top = $(this).scrollTop();

        const header_target = $("header");

        if ($('body').hasClass('single-bigcommerce_product')) {
            const buybar = $("#buy-bar");
            const buybar_point = buybar.offset().top;
            
            if (top >= buybar_point) {
                if (!header_target.hasClass('hide')) {
                    activate_buy_bar();
                    header_target.addClass('hide');
                    buybar.addClass('active');
                }
            } else {
                header_target.removeClass('hide');
                buybar.removeClass('active');
            }
        }

        if (top > 30) {
            $("header #main-navigation").addClass('scrolling');
        } else {
            $("header #main-navigation").removeClass('scrolling');
        }
    });
    
    $("#buy-bar").on("click", '#action-cart', function() {
        $(".bc-product-single__meta .bc-btn--add_to_cart").trigger('click');
        const faux_btn = $(this);
        faux_btn.text("Adding...").css({opacity: 0.5});
    
        const checker = setInterval(function() {
            if (!$(".bc-product-single__meta .bc-btn--add_to_cart").hasClass('bc-ajax-cart-processing')) {
                clearInterval(checker);
                faux_btn.text("Add to cart").removeAttr('style');
            }
        }, 100);
    });


    // GIFT CERTIFICATE - ON SELECT CHANGE UPDATE AMOUNT
    $('#select-amount').on('change', (e) => {
        $('#bc-gift-purchase-amount').val(parseInt($(e.currentTarget).val()));
    });

    /***
     *  Nav functionality
     */
    $('header').removeClass('no-js-menu').addClass('js-menu');
    $("#menu-header > li").mouseenter(function() {
        if ($(window).outerWidth() >= 768) {
            const index = $(this).index();
            $("#subnav-backing div").removeClass('active');
            $("#search-bar").removeClass('active');
            $("#subnav-backing div[data-menu="+index+"]").addClass('active');
            $("body").addClass('partial-fadeout');
        }
    });
    
    $("#menu-header-right-menu .search").mouseenter(function() {
        //console.log("in", $(window).width(), $(window).outerWidth());
        if ($(window).outerWidth() >= 768) {
            $("body").addClass('partial-fadeout');
            $("#subnav-backing div").removeClass('active');
            $("#search-bar").addClass('active');
        }
    }).click(function(e){
        //e.preventDefault();
        if ($(window).outerWidth() <= 767) {

            $(".mobile-handle").removeClass('active');
            $(".menu-container").removeClass('active');

            if($("#search-bar").hasClass('active')){
                $("#search-bar").removeClass('active');
                $("#subnav-backing div").removeClass('active');
                $("body").removeClass('partial-fadeout');
            }else{
                $("body").addClass('partial-fadeout');
                $("#subnav-backing div").removeClass('active');
                $("#search-bar").addClass('active');
            }
            
        }
    });

    $("header").mouseleave(function() {
        $("#subnav-backing div").removeClass('active');
        $("#search-bar").removeClass('active');
        $("body").removeClass('partial-fadeout');
    });

    $("header .mobile-handle").click(function() {
        $(this).toggleClass("active").next(".menu-container").toggleClass('active');
    });

    $(".menu-item-has-children > a").click(function(e) {
        if ($(window).outerWidth() <= 767) {
            $(this).toggleClass('active');
            console.log("asdd");
        }
    });

    $(".menu-item-has-children > a").append($('<div class="arrow-span"></div>',true));

    $('.menu-item-has-children a .arrow-span').click(function(e){
        e.preventDefault();

        var subMenu = $(this).parents().eq(1).find('.sub-menu').first();

        if(subMenu.hasClass('active')){
            subMenu.addClass('no-active');
            subMenu.removeClass('active');
        }else{
            subMenu.addClass('active');
            subMenu.removeClass('no-active');
        }
        
    });

    /***
     * Add pricing to catalog cart buttons - Removed by request from Rom
     */
    // $("body.post-type-archive-bigcommerce_product .bc-btn--add_to_cart, body.tax-bigcommerce_category .bc-btn--add_to_cart").each(function() {
    //     const btn = $(this);
    //     const card = $(this).parents(".bc-product-card");
    //     const price = card.find(".bc-product__pricing--api span");
    //     const priceNode = price[0];
    //     const config = { attributes: true, childList: true, subtree: true };
    //     const callback = function(mutationsList, observer) {
    //         for(let mutation of mutationsList) {
    //             if (mutation.type === 'childList') {
    //                 if (mutation.target.innerText !== "") {
    //                     btn.html(btn.text()+"<span> - "+mutation.target.innerText+"</span>");

    //                     $(document).on('facetwp-refresh', function() {
    //                         if ($(".facetwp-facet-price .facetwp-radio.checked").length > 0) $("#fwp_price_all").removeAttr('checked');
    //                         if ($(".facetwp-facet-size .facetwp-radio.checked").length > 0) $("#fwp_size_all").removeAttr('checked');
    //                         $("body.post-type-archive-bigcommerce_product .bc-btn--add_to_cart, body.tax-bigcommerce_category .bc-btn--add_to_cart").each(function() {
    //                             const btn = $(this);
    //                             const card = $(this).parents(".bc-product-card");
    //                             const price = card.find(".bc-product-price");
    //                             btn.html(btn.text()+"<span> - "+price.text()+"</span>");
    //                         });
    //                     });
    //                 }
    //             }
    //         }
    //     };

    //     const observer = new MutationObserver(callback);
    //     observer.observe(price[0], config);
    //     observer.observe(price[2], config);
    // });

    /***
     *  Mobile filtering functionality
     */

    function toggleFilterNav() {
        if ($(window).width() <= 850) {
            const filterNav = $(".bc-product-archive__refinery .bc-form");
            if (filterNav.hasClass('active')) {
                $('body').removeClass('fadeout');
                filterNav.removeClass('active');
            } else {
                $('body').addClass('fadeout');
                filterNav.addClass('active');
            }
        }
    }

    $("html").on('click', "[data-js='bc-product-archive__mobile-nav'], body.fadeout", function(e) {
        if ($(e.target).hasClass('fadeout') || $(e.currentTarget).hasClass("bc-product-archive__mobile-nav")) {toggleFilterNav();}
    });

    $(".bc-product-archive__refinery .bc-form").click(function(e) {
        if ($(e.target).hasClass('bc-form')) {
            toggleFilterNav();
        }
    });

    $(".bc-product-archive__refinery .bc-product-archive__select-label").click(function() {
        $(this).parent().toggleClass('active');
    });

    /***
     *  Product page functionality
     */

    $("#bc-single-product__details h4").click(function() {
        const parent = $(this).parent();
        if (parent.hasClass('active')) {
            parent.removeClass('active');
        } else {
            parent.addClass('active');
        }
    });

    $('.bc-product-form__quantity-input-control.plus').click(function(){
        var input = $(this).parent().find('input');
        var qty = input.val();
        qty++;
        input.val(qty);
    });
    
    $('.bc-product-form__quantity-input-control.sub').click(function(){
        var input = $(this).parent().find('input');
        var qty = input.val();
        if(qty > 1){
            qty--;
            input.val(qty);
        }
    });

    $("body").on('mouseenter', ".bc-product-gallery--has-carousel .swiper-slide-active", function() {
        $("#zoomContainer, .img-zoom-lens").remove();
        $(this).children('img').attr('id', "zoomSource");
        $(".bc-product__gallery").append("<div id='zoomContainer'></div>");
        imageZoom('zoomSource', 'zoomContainer');
    }).on("mouseleave", ".bc-product-gallery--has-carousel .swiper-slide-active", function() {
        $("#zoomContainer, .img-zoom-lens").remove();
        $("#zoomSource").removeAttr("id");
    });

    /***
     *  Placeholder control
     */

    function checkField(field) {
        if (field.val() !== "") {
           field.addClass('dirty');
        } else {
            field.removeClass('dirty');
        }
    }

    $("body").on("keyup", ".placeholder-control", function() {
        checkField($(this));
    });

    $(".placeholder-control").each(function() {
        checkField($(this));
    });

    /***
     *  Scaler
     * 
     * class="scaler"
     */
    $("img.scaler").each(function() {
        const img = $(this);
        const img_url = img.data('src') ? img.data('src') : img.attr('src');
        const img_offset = img.offset();
        const img_data = {};
        $("<img />").attr('src', img_url).on('load', function() {
            img_data['width'] = this.width;
            img_data['height'] = this.height;

            img.removeClass('lazyload');
            img.parent().css({display: 'block'});
            img.wrap("<div class='scaler-container' />");
            img.parent().append("<img src='"+img_url+"' class='scaler-image' />");
            img.attr('src', "data:image/svg+xml;charset=utf-8,%3Csvg xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg' viewBox%3D'0 0 200 150'%2F%3E").css({'width': img_data.width+"px", 'height': img_data.height+"px"});
        });

        $(window).scroll(function() {
            const top = $(this).scrollTop();
            const height = $(this).height();
            const bottom = top + height;
            const half = height / 2;
            const size = {width: img.width(), height: img.height()};
            const img_center = img_offset.top + (size.height/2);
            const start = img_center - height;
            const end = img_center + height;

            const scale_floor = 1.2;
            const scale_ceil = 1;
            const scale_range = scale_ceil - scale_floor;
            const trans_floor = 45;
            const trans_ceil = 50;
            const trans_range = trans_ceil - trans_floor;
            
            if (img_center <= bottom && img_center >= top) {
                const dist = top - start;
                const perc = dist/height;
                const scale = scale_floor + (scale_range * perc);
                const trans = trans_floor + (trans_range * perc);
                img.next().css({'transform': 'translate(-'+trans+'%,-'+trans+'%) scale('+scale+')'})
            }
            
        });
    });

    /***
     * Entrance
     * 
     * data-js="md-entrance"
     */
    $("[data-js='md-entrance']").each(function() {
        let ele = $(this);
        const dir = ele.data('dir') ? ele.data('dir') : 'right';
        if (ele.is('img')) {
            const img = $(this);
            const img_url = img.data('src') ? img.data('src') : img.attr('src');
            const img_offset = img.offset();
            const img_data = {};
            $("<img />").attr('src', img_url).on('load', function() {
                img_data['width'] = this.width;
                img_data['height'] = this.height;

                img.removeClass('lazyload');
                img.parent().css({display: 'block'});
                img.wrap("<div class='scaler-container entrance "+dir+"' />");
                img.parent().append("<img src='"+img_url+"' class='scaler-image' />").parent().append("<span class='anchor'></span>");
                img.attr('src', "data:image/svg+xml;charset=utf-8,%3Csvg xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg' viewBox%3D'0 0 200 150'%2F%3E").css({'width': img_data.width+"px", 'height': img_data.height+"px"});
                ele = img.parent().parent().children('.anchor');

                ele.on('inview', function(e,v) {
                    const target = ele.hasClass('entrance') ? ele : ele.parent().children('.entrance');
                    if (v) {
                        target.addClass('active');
                    } else {
                        target.removeClass('active');
                    }
                });
            });
        } else {
            ele.on('inview', function(e,v) {
                const target = ele.hasClass('entrance') ? ele : ele.parents('.entrance');
                if (v) {
                    target.addClass('active');
                } else {
                    target.removeClass('active');
                }
            });
        }
        
    });

    /***
     * Fader
     * 
     * class="fader" data-delay="400" data-speed="800"
     */
    $("img.fader").each(function () {
        $(this).on('inview', function(e,v) {
            const img = $(this);
            const delay = img.data('delay') ? img.data('delay') : 0;
            const speed = img.data('speed') ? img.data('speed') : 600;
            if (v) {
                setTimeout(function() {
                    img.animate({opacity: 1, position: 'relative', top: '0px'}, speed);
                }, delay);
            } else {
                img.css({opacity: 0, position: 'relative', top: '30px'});
            }
        })
    });

    /***
     * FadeIn treatment
     * 
     * data-js="md-fadein"
     */
    $("[data-js='md-fadein']").each(function() {
        const ele = $(this);
        ele.addClass('initilised');
        ele.on('inview', function(e,v) {
            if (v) {
                ele.addClass('inview');
            } else {
                ele.removeClass('inview');
            }
        });
    });

    /***
     * Sign up form newsletter signup
     */
    $("form.bc-account-form--register").submit(function(e) {
        if ($("#bc-account-register-newsletter").is(":checked")) {
            const data = {
                'EMAIL': $("#bc-account-register-email").val(),
                'b_eadd0a8ae6715a55dd535c040_16811bc713': ''
            };
            $.ajax({
                type: 'POST',
                url: 'https://oilgarden.us13.list-manage.com/subscribe/post-json?u=eadd0a8ae6715a55dd535c040&amp;id=16811bc713&c=?', 
                data: data, 
                cache: false,
                dataType: 'json',
                contentType: "application/json; charset=utf-8",
                success: function(e) {
                    // e contains return
                    return;
                }
            });
        } else {
            return;
        }
    });

    /***
     * Ajax submit footer form
     */


    $("#sf-subscribe-form").submit(function(e) {
        e.preventDefault();
        const form = $(this);
        const data = {
            action: 'submitsf',
            fields: {
                'email': $("#email").val(),
                'first_name': $("#first_name").val(),
                'last_name': $("#last_name").val(),
                'mobile': $("#mobile").val(),
                'oid': $("#oid").val(),
                'retURL': $("#retURL").val(),
                'recordType': $("#recordType").val(),
                'HasOptedOutOfEmail': $("#HasOptedOutOfEmail").val(),
                '00N2v00000V1WQS': $("#00N2v00000V1WQS").val(),
                '00N2v00000V1WQX': $("#00N2v00000V1WQX").val(),
                'lead_source': $("#lead_source").val()
            }
        };
        $.ajax({
            type: 'POST',
            url: site_params.ajaxurl, 

            data: data, 
            cache: false,
            success: function(e) {
                console.log("success", e);
                // e contains return

                if (e === 0) {
                    $("#first_name").val('');
                    $("#last_name").val('');
                    $("#mobile").val('');
                    $("#email").val('');
                    form.addClass('invalid').append('<span class="msg">An error occured. Please try again</span>');

                } else {
                    form.addClass('success').append("<span class='msg'>Thank you for subscribing.</span>");
                }
                return false;
            },
            error: function(e) {
                console.log("error", e);
                return false;
            }
        });
    });

    $("#email").on('focus', function() {

        $("#sf-subscribe-form").removeClass('invalid').children('.msg').remove();

    });

    /***
     *  Display all reviews
     */

    $("#see-all-reviews").click(function() {
        $(".bc-product-review-list").addClass('display-all');
        $("html, body").animate({"scrollTop": $(".bc-product-review-list").offset().top - $("#page").children('header').height()});
    });

    $("[data-js='bc-product-review-write']").click(function() {
        $("html, body").animate({"scrollTop": $(".bc-product-review-form-custom").offset().top - $("#page").children('header').height()});
    });

    /***
     * Reorder facet output
     */
    if ($(".facetwp-facet-price").length > 0) {
        const order = [
            'Less than $30',
            '$30 to $60',
            '$60 to $100',
            'More than $100'
        ];
        const facet_output = [];
        let facet_reorder = false;
        const config = { attributes: true, childList: true, subtree: true };
        const callback = function(mutationsList, observer) {
            for(let mutation of mutationsList) {
                if (mutation.type === 'childList') {
                    if (mutation.target.innerText !== "") {
                        let last_pos = 0;
                        $(".facetwp-facet-price div").each(function() {
                            let text = $(this)[0].innerText;
                            let pos = order.indexOf(text);
                            if (pos < last_pos) facet_reorder = true;
                            last_pos = pos;
                            facet_output[pos] = $(this);
                        });
                        if (facet_reorder) {
                            $(".facetwp-facet-price").html(facet_output);
                            facet_reorder = false;
                        }
                    }
                }
            }
        };

        const observer = new MutationObserver(callback);
        observer.observe($(".facetwp-facet-price")[0], config);

        $(".facetwp-facet div").on('click', function() {
            if ($(this).parent().find('.facetwp-radio.checked').length > 0) {
                $(this).parent().prev().find('input[type=radio]').removeAttr('checked');
            }
        });
    }

    $("body").on("click", ".bc-ajax-add-to-cart__message-wrapper", function(e){
        const ele = $(e.target);
        ele.fadeOut(function() {
            $(this).remove();
        });
    });

    /* Track customer group price difference */
    $(".bc-product__price--base").attrchange(function(attrName){
        if (attrName === 'class') {
            //console.log("check", $(this).text(), $(this).parent().children('.bc-product__price--cgOrig').text());
            if ($(this).hasClass('bc-show-current-price') && $(this).text() !== $(this).parent().children('.bc-product__price--cgOrig').text()) {
                //console.log($(this).text(), $(this).parent().children('.bc-product__price--cgOrig').text());
                $(this).parent().children('.bc-product__price--cgOrig').addClass('show bc-product__original-price');
            }
        }
    });

    $(".bc-cart-subtotal__amount").bind('DOMSubtreeModified', function(){
        location.reload();
    });
})(jQuery);

function activate_buy_bar() {
    const price_data = $(".bc-product-single__meta > div > [data-js='bc-product-pricing']").clone();
    const price_container = $("#buy-bar").find('.price');

    price_container.html(price_data);
}

function imageZoom(imgID, resultID) {
    var img, lens, result, cx, cy;
    img = document.getElementById(imgID);
    result = document.getElementById(resultID);
    /* Create lens: */
    lens = document.createElement("DIV");
    lens.setAttribute("class", "img-zoom-lens");
    /* Insert lens: */
    img.parentElement.insertBefore(lens, img);
    /* Calculate the ratio between result DIV and lens: */
    cx = result.offsetWidth / lens.offsetWidth;
    cy = result.offsetHeight / lens.offsetHeight;
    /* Set background properties for the result DIV */
    result.style.backgroundImage = "url('" + img.dataset.zoom + "')";
    result.style.backgroundSize = (img.width * cx) + "px " + (img.height * cy) + "px";
    /* Execute a function when someone moves the cursor over the image, or the lens: */
    lens.addEventListener("mousemove", moveLens);
    img.addEventListener("mousemove", moveLens);
    /* And also for touch screens: */
    lens.addEventListener("touchmove", moveLens);
    img.addEventListener("touchmove", moveLens);
    function moveLens(e) {
      var pos, x, y;
      /* Prevent any other actions that may occur when moving over the image */
      e.preventDefault();
      /* Get the cursor's x and y positions: */
      pos = getCursorPos(e);
      /* Calculate the position of the lens: */
      x = pos.x - (lens.offsetWidth / 2);
      y = pos.y - (lens.offsetHeight / 2);
      /* Prevent the lens from being positioned outside the image: */
      if (x > img.width - lens.offsetWidth) {x = img.width - lens.offsetWidth;}
      if (x < 0) {x = 0;}
      if (y > img.height - lens.offsetHeight) {y = img.height - lens.offsetHeight;}
      if (y < 0) {y = 0;}
      /* Set the position of the lens: */
      lens.style.left = x + "px";
      lens.style.top = y + "px";
      /* Display what the lens "sees": */
      result.style.backgroundPosition = "-" + (x * cx) + "px -" + (y * cy) + "px";
      x = pos.x + (result.offsetWidth / 2) + 20;
      y = pos.y + (result.offsetHeight / 4);
      result.style.left = x + "px";
      result.style.top = y + "px";
    }
    function getCursorPos(e) {
      var a, x = 0, y = 0;
      e = e || window.event;
      /* Get the x and y positions of the image: */
      a = img.getBoundingClientRect();
      /* Calculate the cursor's x and y coordinates, relative to the image: */
      x = e.pageX - a.left;
      y = e.pageY - a.top;
      /* Consider any page scrolling: */
      x = x - window.pageXOffset;
      y = y - window.pageYOffset;
      return {x : x, y : y};
    }
}
