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
/******/ 	return __webpack_require__(__webpack_require__.s = 39);
/******/ })
/************************************************************************/
/******/ ({

/***/ 39:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(40);


/***/ }),

/***/ 40:
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

/***/ })

/******/ });