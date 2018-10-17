// var Isotope = require('isotope-layout');

// ----------------------------------- Input File ------------------------------------

window.inputImageChanged = function (allowClearImage = true) {

    let fileInput = this;
    let fileBlock = $(fileInput).closest('.input-file-block');

    // Set the popover default content
    $(fileBlock).popover({
        trigger: 'manual',
        html: true,
        content: "",
    });

    let popoverImage = $('<img/>', {
        id: 'dynamic',
        width: 160,
    });

    let file = fileInput.files[0];
    let reader = new FileReader();

    reader.onload = function (e) {

        let clearImageButton = $(fileBlock).find(".image-preview-clear");
        let fileNameField = $(fileBlock).find(".image-preview-filename");

        // show clear image button if allowed
        if (allowClearImage) {
            $(clearImageButton).show();
        }

        $(fileNameField).val(file.name);
        popoverImage.attr('src', e.target.result);

        // show and auto hide popover
        $(fileBlock).attr("data-content", $(popoverImage)[0].outerHTML);

        setTimeout(function () {
            $(fileBlock).popover('show');
        }, 100);

        setTimeout(function () {
            $(fileBlock).popover("hide");
        }, 4000);

        // bind show popover on hover
        $(fileBlock).hover(function () {
            $(this).popover('show');
        }, function () {
            $(this).popover('hide');
        });

        // clear file data
        $(clearImageButton).click(function () {
            $(fileBlock).attr("data-content", "").popover('hide');
            $(fileNameField).val("");
            $(clearImageButton).hide();
            $(fileInput).val("");

            // unbind hover
            $(fileBlock).unbind();
        });
    };
    reader.readAsDataURL(file);

    // unbind hover
    $(fileBlock).unbind();
};

$(document).ready(function () {

    // register input file block
    $('.input-file-block .image-preview-input').find('input').change(inputImageChanged);


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