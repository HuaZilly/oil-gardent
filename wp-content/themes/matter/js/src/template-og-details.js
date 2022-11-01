(($) => {

    $('.question h5').click(function(){
        var target = $(this).parent().find('.answer.content');

        if(target.hasClass('active')){
            target.removeClass('active');
            $(this).css('background-image', 'url('+$(this).data('imgplus')+')');
        }else{
            target.addClass('active');
            $(this).css('background-image', 'url('+$(this).data('imgminor')+')');
        }

    });

})(jQuery);