$(document).ready(function () {

    // ------------------------------------ fixing search panel on scroll ----------------------------------

    let fixingTopContainer = $('#header-middle');

    let fixContainerScroll = $(fixingTopContainer).offset().top + $(fixingTopContainer).height();

    $(window).scroll(function () {
        if ($(window).scrollTop() > fixContainerScroll){
            $(fixingTopContainer).addClass('fixed-top-container');
            $(fixingTopContainer).animate({top: 0});
        }else {
            $(fixingTopContainer).removeClass('fixed-top-container');
        }
    });


    // ----------------------------------- Mega Menu ------------------------------------

    let tabPills = $('#v-pills-categories-tabs');
    let contentTabs = $('#v-pills-categoriesContent');

    //only for devices with hover event
    if (!('ontouchstart' in window)) {

        let megaMenuDropdown = $('.mega-menu-dropdown');

        // disable handle click events
        $(megaMenuDropdown).find('.dropdown-toggle').click(function (e) {
            e.stopImmediatePropagation();
        });

        $(megaMenuDropdown).hover(
            function () {
                $(this).addClass('show');
                $(this).find('.dropdown-menu').addClass('show');
                $(this).find('.mega-menu-content').css({
                    'opacity': 0,
                    'top': '20px'
                }).stop(true, true).animate({
                    opacity: 1,
                    'top': '100%'
                }, 200, null, function () {
                    //highlight active tab pill
                    $(tabPills).find('.active').closest('li').addClass('bg-light');
                    // fit category in parent with isotope
                    $(contentTabs).find('.tab-pane.show .grid').isotope();
                });
            }, function () {
                $(this).removeClass('show');
                $(this).find('.dropdown-menu').removeClass('show');
            }
        );
    }

    // ----------------------------------- Mega Menu Content ------------------------------------

    //only for devices with hover event
    if (!('ontouchstart' in window)) {
        $(tabPills).find('li').hover(function () {

            if ($(this).find('a').hasClass('active')) {
                return;
            }

            // deactivate all tabs
            $(tabPills).find('a').removeClass('active show');
            $(tabPills).find('li').removeClass('bg-light');
            $(contentTabs).find('.tab-pane').removeClass('active show');

            // activate selected
            $(this).find('a').tab('show');
            $(this).closest('li').addClass('bg-light');

            // fit category in parent with isotope
            $(contentTabs).find('.tab-pane.show .grid').isotope();

        })

    }


});