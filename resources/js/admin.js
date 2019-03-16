'use strict';

// bootstrap-select
require('../../vendor/snapappointments/bootstrap-select/js/bootstrap-select.js');

require('../../public/js/jquery.bootstrap-touchspin.js');
//
// <!-- bootstrap-touchspin -->
// <script src="{{ url('/js/jquery.bootstrap-touchspin.js') }}"></script>

window.checkMultiTabForm = function (form) {

    // --------------- show tab with error input ----------------------------------

    $(form).find('input:invalid').each(function () {

        // Find the tab-pane that this element is inside, and get the id
        let tabId = $(this).closest('.tab-pane').attr('id');

        // Find the link that corresponds to the pane and have it show
        $(form).find('.nav a[href="#' + tabId + '"]').tab('show');

        // Only want to do it once
        return false;
    });
};

$(document).ready(function () {

    // ----------------------------- Activate dropdown-hover -----------------------------

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

});
