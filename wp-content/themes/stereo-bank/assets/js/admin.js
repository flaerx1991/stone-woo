/*! Stereo Bank - v1.0.0 - 07-11-2023 */
/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ 601:
/***/ (() => {



/* Notices */
(function () {
  jQuery('.cmsmasters-dismiss-notice-permanent').on('click', '.notice-dismiss', function () {
    var $container = jQuery(this).closest('.cmsmasters-dismiss-notice-permanent'),
      optionKey = $container.data('optionKey');
    var ajaxData = {
      action: 'cmsmasters_hide_admin_notice',
      nonce: cmsmasters_admin.nonce,
      option_key: optionKey
    };
    jQuery.post(ajaxurl, ajaxData);
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


/* Admin Scripts */
__webpack_require__(601);
})();

/******/ })()
;
//# sourceMappingURL=admin.js.map