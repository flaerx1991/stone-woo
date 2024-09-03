/*! cmsmasters-elementor-addon - v1.11.6 - 05-03-2024 */
"use strict";
(self["webpackChunkcmsmasters_elementor_addon"] = self["webpackChunkcmsmasters_elementor_addon"] || []).push([["theme-builders-slider"],{

/***/ "../modules/theme/assets/dev/js/frontend/widgets/theme-builders-slider.js":
/*!********************************************************************************!*\
  !*** ../modules/theme/assets/dev/js/frontend/widgets/theme-builders-slider.js ***!
  \********************************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _handler = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/handler */ "../assets/dev/js/frontend/base/handler.js"));
var _slider = _interopRequireDefault(__webpack_require__(/*! cmsmasters-slider-module/frontend/slider */ "../modules/slider/assets/dev/js/frontend/slider.js"));
const utils = __webpack_require__(/*! cmsmasters-helpers/utils */ "../assets/dev/js/helpers/utils.js");
class ThemeBuildersSlider extends _handler.default {
  __construct(settings) {
    super.__construct(settings);
    this.slider = null;
  }
  initElements() {
    super.initElements();
    this.slider = new _slider.default({
      widget: this
    });
  }
  bindEvents() {
    this.bindElementChange('slider_height', utils.debounce(this.slider.update.bind(this)));
  }
  onInit() {
    super.onInit(...arguments);
    this.slider.init();
  }
}
exports["default"] = ThemeBuildersSlider;

/***/ })

}]);
//# sourceMappingURL=theme-builders-slider.21b71e2ef233586e9348.bundle.js.map