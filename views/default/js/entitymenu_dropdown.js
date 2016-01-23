require(['require', 'jquery', 'elgg'], function (require, $, elgg) {

    var dropdown_entity = false;
    var dropdown_parent = false;

    $(document).on('click', '.elgg-menu-entity .elgg-menu-item-entitymenu-dropdown', function (e) {
        e.preventDefault();

        var entity = $(this).children('ul').eq(0);
        var html = entity.html();
        var offset = $(this).offset();
        
        // close the dropdown if it was the same one clicked twice
        if (dropdown_entity && dropdown_parent.attr('data-dropdown') === 'open' && $(this).attr('data-dropdown') === 'open') {
            dropdown_reset();
            return;
        }

        if (!html) {
            return;
        }

        // reset existing active
        dropdown_reset();

        entity.html('');

        // set new globals
        dropdown_parent = $(this);
        dropdown_parent.attr('data-dropdown', 'open');
        dropdown_entity = entity;

        $(this).children('.entitymenu-dropdown').eq(0).addClass('active');

        // create our dropdown
        $('body').prepend('<div id="entitymenu-dropdown-menu" class="hidden"><ul></ul></div>');
        var modal = $('#entitymenu-dropdown-menu');
        modal.children('ul').eq(0).html(html);


        var left = Math.round(offset.left - modal.width() + $(this).width());
        var top = Math.round(offset.top + $(this).height() - 2);

        modal.css('marginLeft', left + 'px');
        modal.css('marginTop', top + 'px');
        modal.removeClass('hidden');
    });


    $(document).on('click', '*', function (e) {
        setTimeout(dropdown_hovercheck(), 100);
    });

    var dropdown_hovercheck = function () {
        if (!dropdown_parent) {
            return;
        }

        if (dropdown_parent.is(':hover') || $('#entitymenu-dropdown-menu').is(':hover')) {
            return;
        }

        dropdown_reset();
    };

    var dropdown_reset = function () {
        if (!dropdown_entity) {
            return true;
        }

        var html = $('#entitymenu-dropdown-menu ul').html();

        if (!html) {
            return;
        }

        dropdown_entity.html(html);
        $('#entitymenu-dropdown-menu').remove();

        $('a.entitymenu-dropdown.active').removeClass('active');
        
        dropdown_parent.attr('data-dropdown', '');
        dropdown_entity = false;
        dropdown_parent = false;
    };

});