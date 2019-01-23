/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/common.js":
/***/ (function(module, exports) {

// ----------------------------------- Input Image ------------------------------------

window.inputImageChanged = function () {
    var allowClearImage = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;


    var fileInput = this;
    var fileBlock = $(fileInput).closest('.input-file-block');

    // Set the popover default content
    $(fileBlock).popover({
        trigger: 'manual',
        html: true,
        content: ""
    });

    var popoverImage = $('<img/>', {
        id: 'dynamic',
        width: 160
    });

    var file = fileInput.files[0];
    var reader = new FileReader();

    reader.onload = function (e) {

        var clearImageButton = $(fileBlock).find(".image-preview-clear");
        var fileNameField = $(fileBlock).find(".image-preview-filename");

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

    // ----------------------------------------- register input file block -------------------------------

    $('.input-file-block .image-preview-input').find('input').change(inputImageChanged);
});

/***/ }),

/***/ "./resources/js/lizard.js":
/***/ (function(module, exports) {

$(document).ready(function () {

    // ------------------------------------ fixing search panel on scroll ----------------------------------

    var fixingTopContainer = $('#header-middle');

    var fixContainerScroll = $(fixingTopContainer).offset().top + $(fixingTopContainer).height();

    $(window).scroll(function () {
        if ($(window).scrollTop() > fixContainerScroll) {
            if (!$(fixingTopContainer).hasClass('fixed-top-container')) {
                $(fixingTopContainer).addClass('fixed-top-container').animate({
                    top: 0
                }, 400);
            }
        } else {
            if ($(fixingTopContainer).hasClass('fixed-top-container')) {
                $(fixingTopContainer).stop(true).removeClass('fixed-top-container').removeAttr('style');
            }
        }
    });

    // ----------------------------------- Mega Menu Content ------------------------------------

    //only for devices with hover event
    if (!('ontouchstart' in window)) {

        var megaMenuCategories = $('#mega-menu-categories');
        var megaMenuSubcategories = $('#mega-menu-subcategories');

        $(megaMenuCategories).find('a').hover(function () {
            // don't handle current category
            if ($(this).hasClass('show')) {
                return;
            }

            var currentCategory = this;

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
        });
    }

    // highlight input group border on focus
    var searchForm = $('.form-search');

    var searchInputGroup = $(searchForm).find('.input-group');

    var searchFormInput = $(searchForm).find('input');

    $(searchFormInput).focus(function () {
        $(this).closest(searchInputGroup).addClass('form-search-active');
    });

    $(searchFormInput).blur(function () {
        $(this).closest(searchInputGroup).removeClass('form-search-active');
    });

    var dropdownHover = $('.dropdown-hover');

    if (!('ontouchstart' in window)) {
        // disable open dropdown onclick events
        $(dropdownHover).find('.dropdown-toggle').click(function (e) {
            e.stopImmediatePropagation();
        });
    }

    $(dropdownHover).hover(function () {
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
    });

    // show search on xs
    $('#header-show-search-toggle').click(function () {
        $('#header-search').removeClass('d-none').addClass('d-flex');
    });

    // hide search on xs
    $('#header-hide-search-toggle').click(function () {
        $('#header-search').removeClass('d-flex').addClass('d-none');
    });

    // ----------------------------------------------------- Add to favourite ------------------------------------------

    // add to favourite
    $('.product-favourite-add').click(function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        $.ajax({
            url: this,
            success: function success(data) {
                if (data) {
                    // increase header badge's count
                    var badge = $('#header-favourite-products').find('span');
                    $(badge).text($(badge).text().length ? parseInt($(badge).text()) + 1 : 1);
                }

                // activate modal
                var modal = $('#modal-product-favourite-added');
                $(modal).modal('show');
                setTimeout(function () {
                    $(modal).modal('hide');
                }, 3500);
            }
        });
    });
});

/***/ }),

/***/ 1:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__("./resources/js/common.js");
module.exports = __webpack_require__("./resources/js/lizard.js");


/***/ })

/******/ });