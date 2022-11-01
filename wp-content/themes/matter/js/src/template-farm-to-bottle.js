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


    $('.pagination div').click(function(){
        var origin = $(this);
        var target_id = origin.data('target');

        if(!origin.hasClass('active')){
            $('.pagination div').removeClass('active');
            origin.addClass('active');
        }

        $('.item-mobile').removeClass('active');
        var target = $(".item-mobile[data-id='"+target_id+"']");
        target.addClass('active');

    });

    $('.item-mobile').click(function(){
        var current = $(this);
        var next = current.next('.item-mobile');
        var target_id = next.data('id');

        //console.log(next);
        if(next.length == 0){
            next = $('.item-mobile').first();
            target_id = 1;
        }

        $('.item-mobile').removeClass('active');
        next.addClass('active');

        $(".pagination div").removeClass('active');
        $(".pagination div[data-target='"+target_id+"']").addClass('active');

    });



})(jQuery);