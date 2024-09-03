/*! Stereo Bank - v1.0.0 - 07-11-2023 */
/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ 784:
/***/ (() => {



/* Install demo */
(function () {
  jQuery(document).ready(function () {
    jQuery('.cmsmasters-install-button').on('click', function (event) {
      event.preventDefault();
      var goto = this.getAttribute('href'),
        key = this.getAttribute('data-key');
      var type = 'custom',
        contentImport = 'custom';
      if (jQuery(this).hasClass('cmsmasters-express')) {
        type = 'express';
        if (jQuery(this).closest('.cmsmasters-installer-demos__item').find('.cmsmasters-import-content-status')[0].checked) {
          contentImport = 'enabled';
        } else {
          contentImport = 'disabled';
        }
      }
      var ajaxData = {
        action: 'cmsmasters_installer',
        wpnonce: installer_params.wpnonce,
        type: type,
        content_import: contentImport,
        demo_key: key
      };
      jQuery.post(installer_params.ajaxurl, ajaxData, function () {
        window.location = goto;
      });
    });
    if ('express' === installer_params.type) {
      jQuery('.merlin__body').addClass('cmsmasters-is-express-install');
      setTimeout(function () {
        jQuery('.merlin__button--next').trigger('click');
      }, 500);
    }
    jQuery('.cmsmasters-pre-installation-notice__close').on('click', function () {
      jQuery(this).closest('.cmsmasters-pre-installation-notice').addClass('cmsmasters-pre-installation-notice-hide');
    });
  });
})();

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {


/* Installer Script */
__webpack_require__(784);
})();

/******/ })()
;
//# sourceMappingURL=installer.js.map