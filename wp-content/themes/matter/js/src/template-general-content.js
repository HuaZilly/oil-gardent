(($) => {



     /***
     *  PLAY VIDEO
     */

    if ($("[data-ytv]").length > 0) {
        var tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        const video_id = $("[data-ytv]").data('ytv');

        function onYouTubeIframeAPIReady() {
            window['player'] = new YT.Player('ytplayer', {
                height: '303.75',
                width: '540',
                videoId: video_id,
                playerVars: { 
                    controls: 0,
                    disablekb: 1,
                    fs: 0,
                    modestbranding: 1,
                    playsinline: 1,
                    rel: 0,
                    showinfo: 0
                },
                events: {
                    'onReady': playVideo
                }
            });
        }

        function playVideo() {
            window['player'].playVideo();
        }

        function stopVideo() {
            window['player'].stopVideo();
        }

        $("[data-ytv]").click(function() {
            const container = $(this).children('div');
            if (container.hasClass('clicked')) {
                container.removeClass('clicked');
                stopVideo();
            } else {
                container.addClass('clicked');
                if (container.hasClass('initialised')) {
                    playVideo();
                } else {
                    onYouTubeIframeAPIReady();
                    container.addClass("initialised");
                }
            }
        });

        $("[data-trigger-ytv]").click(function() {
            $("[data-ytv]").trigger('click');
        });
    }


    $('.tab-sections div').click(function(){
        var target = $(this);
        var id = target.data('id')

        $('.tab-sections div').removeClass('active');
        $('.contents-blocks div').removeClass('active');

        target.addClass('active');
        $(".contents-blocks div[data-target='"+id+"']").addClass('active');

    });


    $('.certifications .item').click(function(){
        var current = $(this);
        var next = current.next('.item');
        var target_id = next.data('id');

        //console.log(next);
        if(next.length == 0){
            next = $('.item').first();
            target_id = 1;
        }

        $('.certifications .item').removeClass('active');
        next.addClass('active');

        $(".certifications .pagination div").removeClass('active');
        $(".certifications .pagination div[data-target='"+target_id+"']").addClass('active');

    });

    $('.certifications .pagination div').click(function(){
        var origin = $(this);
        var target_id = origin.data('target');

        if(!origin.hasClass('active')){
            $('.certifications .pagination div').removeClass('active');
            origin.addClass('active');
        }

        $('.certifications .item').removeClass('active');
        var target = $(".certifications .item[data-id='"+target_id+"']");
        target.addClass('active');

    });

    

})(jQuery);