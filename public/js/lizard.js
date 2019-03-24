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

    // ------------------------------ fixing top panel on scroll ----------------------------------

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

    // ----------------------------- Activate dropdown-hover -----------------------------

    var dropdownHover = $('.dropdown-hover');

    if (!('ontouchstart' in window)) {
        // disable open dropdown onclick events
        $(dropdownHover).find('.dropdown-toggle').click(function (e) {
            e.stopImmediatePropagation();
        });
    }

    $(dropdownHover).hover(function (event) {
        var dropdownHover = event.currentTarget;
        $(dropdownHover).addClass('show');
        $(dropdownHover).find('.dropdown-menu').addClass('show').css({
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

    // ----------------------------- Add and remove to favourite -----------------------------

    $('.product-favourite').click(function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var clickedButton = e.currentTarget;
        var productWrapper = $(clickedButton).closest('.product-wrapper');

        var productFavouriteRemoveButtons = $(productWrapper).find('.product-favourite-remove');
        var productFavouriteAddButtons = $(productWrapper).find('.product-favourite-add');

        $.ajax({
            url: clickedButton,
            success: function success() {
                if ($(clickedButton).hasClass('product-favourite-remove')) {

                    // change buttons visibility
                    $(productFavouriteRemoveButtons).removeClass('active d-flex').addClass('d-none');
                    $(productFavouriteAddButtons).removeClass('d-none').addClass('d-flex');

                    // decrease header badge's count
                    var badge = $('#header-favourite-products').find('span');
                    var badgeText = parseInt($(badge).text());
                    $(badge).text(badgeText && badgeText > 1 ? badgeText - 1 : '');
                } else if ($(clickedButton).hasClass('product-favourite-add')) {

                    // change buttons visibility
                    $(productFavouriteAddButtons).removeClass('d-flex').addClass('d-none');
                    $(productFavouriteRemoveButtons).removeClass('d-none').addClass('active d-flex');

                    // increase header badge's count
                    var _badge = $('#header-favourite-products').find('span');
                    var _badgeText = parseInt($(_badge).text());
                    $(_badge).text(_badgeText ? _badgeText + 1 : 1);
                }
            }
        });
    });

    // ------------------------------ search form -------------------------------------

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

    // show search on xs
    $('#header-show-search-toggle').click(function () {
        $('#header-search').removeClass('d-none').addClass('d-flex');
    });

    // hide search on xs
    $('#header-hide-search-toggle').click(function () {
        $('#header-search').removeClass('d-flex').addClass('d-none');
    });

    var mainSearchForm = $('#main-search-form');

    $(mainSearchForm).submit(function (event) {

        // prevent to post roo short search query
        if ($(mainSearchForm).find('input[name="search_for"]').val().length < 2) {

            $(mainSearchForm).popover('show');
            setTimeout(function () {
                $(mainSearchForm).popover('hide');
            }, 3000);

            event.preventDefault();
            event.stopImmediatePropagation();

            return false;
        }

        return true;
    });
});

/***/ }),

/***/ 1:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__("./resources/js/common.js");
module.exports = __webpack_require__("./resources/js/lizard.js");


/***/ })

/******/ });