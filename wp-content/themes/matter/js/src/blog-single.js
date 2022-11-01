(($) => {
    // console.log($.length);

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

    /* Track customer group price difference */
    $(".bc-product__price--base").attrchange(function(attrName){
        if (attrName === 'class') {
            
            if ($(this).hasClass('bc-show-current-price') && $(this).text() !== $(this).parent().children('.bc-product__price--cgOrig').text()) {
                $('.bc-product__price--cgOrig').addClass('show bc-product__original-price');
            }
        }
    });
})(jQuery);