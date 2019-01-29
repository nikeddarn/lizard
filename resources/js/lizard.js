$(document).ready(function () {

    // ------------------------------------ fixing search panel on scroll ----------------------------------

    let fixingTopContainer = $('#header-middle');

    let fixContainerScroll = $(fixingTopContainer).offset().top + $(fixingTopContainer).height();

    $(window).scroll(function () {
        if ($(window).scrollTop() > fixContainerScroll) {
            if (!$(fixingTopContainer).hasClass('fixed-top-container')) {
                $(fixingTopContainer).addClass('fixed-top-container').animate({
                    top: 0
                }, 400);
            }
        } else {
            if (($(fixingTopContainer).hasClass('fixed-top-container'))) {
                $(fixingTopContainer).stop(true).removeClass('fixed-top-container').removeAttr('style');
            }
        }
    });

    // ----------------------------------- Mega Menu Content ------------------------------------

    //only for devices with hover event
    if (!('ontouchstart' in window)) {

        let megaMenuCategories = $('#mega-menu-categories');
        let megaMenuSubcategories = $('#mega-menu-subcategories');

        $(megaMenuCategories).find('a').hover(function () {
            // don't handle current category
            if ($(this).hasClass('show')) {
                return;
            }

            let currentCategory = this;

            // highlight current category
            $(megaMenuCategories).find('.show').removeClass('show');
            $(this).addClass('show');

            // show current subcategories
            $(megaMenuSubcategories).find('.mega-menu-subcategory.show').stop(true).animate({
                opacity: 0
            }, 200, null, function () {
                $(this).removeClass('show');
                $(megaMenuSubcategories).find('#mega-menu-children-' + $(currentCategory).attr('aria-controls').match(/\d+/)[0]).css('opacity', 0).addClass('show').stop(true).animate({
                    opacity: 1
                }, 200, function () {
                    // align subcategories of current category with isotope
                    $(megaMenuSubcategories).find('.grid').masonry();
                });
            });
        })

    }

    // highlight input group border on focus
    let searchForm = $('.form-search');

    let searchInputGroup = $(searchForm).find('.input-group');

    let searchFormInput = $(searchForm).find('input');

    $(searchFormInput).focus(function () {
        $(this).closest(searchInputGroup).addClass('form-search-active');
    });

    $(searchFormInput).blur(function () {
        $(this).closest(searchInputGroup).removeClass('form-search-active');
    });

    let dropdownHover = $('.dropdown-hover');

    if (!('ontouchstart' in window)) {
        // disable open dropdown onclick events
        $(dropdownHover).find('.dropdown-toggle').click(function (e) {
            e.stopImmediatePropagation();
        });
    }

    $(dropdownHover).hover(
        function () {
            $(this).addClass('show');
            $(this).find('.dropdown-menu').addClass('show').css({
                'opacity': 0,
                'top': '50%'
            }).stop(true).animate({
                opacity: 1,
                'top': '100%'
            }, 300, null, function () {
                if ($(this).hasClass('mega-menu')) {
                    // align subcategories by isotope
                    $('#mega-menu-subcategories').find('.mega-menu-subcategory.show .grid').masonry();
                }
            });
        }, function () {
            $(this).removeClass('show');
            $(this).find('.dropdown-menu').removeClass('show');
        }
    );

    // show search on xs
    $('#header-show-search-toggle').click(function () {
        $('#header-search').removeClass('d-none').addClass('d-flex');
    });

    // hide search on xs
    $('#header-hide-search-toggle').click(function () {
        $('#header-search').removeClass('d-flex').addClass('d-none');
    });

    // ----------------------------------------------------- Add and remove to favourite ------------------------------------------

    // add to favourite
    $('.product-favourite:not(.active)').click(function (e) {
        addProductToFavourite(e, this);
    });


    // remove to favourite
    $('.product-favourite.active').click(function (e) {
        removeProductFromFavourite(e, this);
    });

    let addProductToFavourite = function (e, favouriteButtonUrl) {
        e.preventDefault();
        e.stopImmediatePropagation();

        $.ajax({
            url: favouriteButtonUrl,
            success: function (data) {
                // increase header badge's count
                let badge = $('#header-favourite-products').find('span');
                let badgeText = parseInt($(badge).text());
                $(badge).text(badgeText ? badgeText + 1 : 1);

                // highlight favourite button and replace attributes
                $(favouriteButtonUrl).addClass('active').attr('href', data).attr('title', $(favouriteButtonUrl).data('remove-title'));

                // rebind 'onclick'
                $(favouriteButtonUrl).off('click').click(function (e) {
                    removeProductFromFavourite(e, this);
                });

            }
        });
    };

    let removeProductFromFavourite = function (e, favouriteButtonUrl) {
        e.preventDefault();
        e.stopImmediatePropagation();

        $.ajax({
            url: favouriteButtonUrl,
            success: function (data) {
                // decrease header badge's count
                let badge = $('#header-favourite-products').find('span');
                let badgeText = parseInt($(badge).text());
                $(badge).text(badgeText && badgeText > 1 ? badgeText - 1 : '');

                // remove highlight from favourite button and replace attributes
                $(favouriteButtonUrl).removeClass('active').attr('href', data).attr('title', $(favouriteButtonUrl).data('add-title'));

                // rebind 'onclick'
                $(favouriteButtonUrl).off('click').click(function (e) {
                    addProductToFavourite(e, this);
                });
            }
        });
    };

});
