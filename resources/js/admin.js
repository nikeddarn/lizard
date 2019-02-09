'use strict';

// bootstrap-select
require('../../vendor/snapappointments/bootstrap-select/js/bootstrap-select.js');

require('../../public/js/jquery.bootstrap-touchspin.js');
//
// <!-- bootstrap-touchspin -->
// <script src="{{ url('/js/jquery.bootstrap-touchspin.js') }}"></script>

window.checkMultiTabForm = function (form) {

    $(form).find('input:invalid').each(function () {

        // Find the tab-pane that this element is inside, and get the id
        let tabId = $(this).closest('.tab-pane').attr('id');

        // Find the link that corresponds to the pane and have it show
        $(form).find('.nav a[href="#' + tabId + '"]').tab('show');

        // Only want to do it once
        return false;
    });
};
