(($) => {

    $('.certifications .item').click(function(){
        var target = $(this);


        if(target.hasClass('active')){
            target.find('.item-content').removeClass('active');
            target.removeClass('active');
            $(this).css('background-image', 'url('+$(this).data('imgplus')+')');
        }else{
            $('.certifications .item .item-content').removeClass('active');
            $('.certifications .item').removeClass('active');
            $('.certifications .item').css('background-image', 'url('+$(this).data('imgplus')+')');

            target.find('.item-content').addClass('active');
            target.addClass('active');
            $(this).css('background-image', 'url('+$(this).data('imgminor')+')');
        }

    });

})(jQuery);