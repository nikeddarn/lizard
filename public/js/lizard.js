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
/******/ 	return __webpack_require__(__webpack_require__.s = 41);
/******/ })
/************************************************************************/
/******/ ({

/***/ 41:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(42);


/***/ }),

/***/ 42:
/***/ (function(module, exports) {

$(document).ready(function () {

    // ------------------------------------ fixing search panel on scroll ----------------------------------

    var fixingTopContainer = $('#header-middle');

    var fixContainerScroll = $(fixingTopContainer).offset().top + $(fixingTopContainer).height();

    $(window).scroll(function () {
        if ($(window).scrollTop() > fixContainerScroll) {
            $(fixingTopContainer).addClass('fixed-top-container');
            $(fixingTopContainer).animate({ top: 0 });
        } else {
            $(fixingTopContainer).removeClass('fixed-top-container');
        }
    });

    // ----------------------------------- Mega Menu ------------------------------------

    var tabPills = $('#v-pills-categories-tabs');
    var contentTabs = $('#v-pills-categoriesContent');

    //only for devices with hover event
    if (!('ontouchstart' in window)) {

        var megaMenuDropdown = $('.mega-menu-dropdown');

        // disable handle click events
        $(megaMenuDropdown).find('.dropdown-toggle').click(function (e) {
            e.stopImmediatePropagation();
        });

        $(megaMenuDropdown).hover(function () {
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
        });
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
        });
    }
});

/***/ })

/******/ });