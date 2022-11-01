(($) => {
    $(document).on('click', '.filter-toggle', function(e){
        e.preventDefault();
        let $elem = $(this);
        $elem.toggleClass('open', !$elem.hasClass('open'));
        $($elem.data('target')).toggleClass('active', $elem.hasClass('open'));
    });
    let currentActiveItem = false;
    $(document).on('click.parent-menu', 'li.has-submenu a', (e) => {
        let $elem = $(e.target).closest('.has-submenu');
        if(!$elem.is('.active')){
            if(currentActiveItem){
                currentActiveItem.removeClass('active');
            }
            e.preventDefault();
            $elem.addClass('active');
            currentActiveItem = $elem;
            return false
        }
        return true;
    });
})(jQuery);