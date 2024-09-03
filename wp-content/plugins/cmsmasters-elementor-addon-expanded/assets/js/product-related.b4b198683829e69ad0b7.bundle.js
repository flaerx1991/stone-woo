/*! cmsmasters-elementor-addon - v1.11.6 - 05-03-2024 */
"use strict";
(self["webpackChunkcmsmasters_elementor_addon"] = self["webpackChunkcmsmasters_elementor_addon"] || []).push([["product-related"],{

/***/ "../modules/woocommerce/assets/dev/js/frontend/widgets/product-related.js":
/*!********************************************************************************!*\
  !*** ../modules/woocommerce/assets/dev/js/frontend/widgets/product-related.js ***!
  \********************************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _handler = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/handler */ "../assets/dev/js/frontend/base/handler.js"));
var _slider = _interopRequireDefault(__webpack_require__(/*! cmsmasters-slider-module/frontend/slider */ "../modules/slider/assets/dev/js/frontend/slider.js"));
class ProductRelated extends _handler.default {
  getDefaultElements() {
    const $section = this.findElement('section.products, .cross-sells');
    const $products = $section.find('ul.products');
    return {
      $section,
      $products,
      $product: $products.find('.product')
    };
  }
  onInit() {
    super.onInit();
    this.initTemplate();
    this.slider = new _slider.default({
      widget: this
    });
    this.slider.init();
  }
  initTemplate() {
    const {
      $products,
      $product,
      $section
    } = this.elements;
    if (!$section.hasClass('swiper') || !$section.hasClass('swiper-container')) {
      $section.addClass('swiper swiper-container cmsmasters-swiper-container');
    }
    if (!$products.hasClass('swiper-wrapper')) {
      $products.addClass('swiper-wrapper');
    }
    if (!$product.hasClass('swiper-slide')) {
      $product.addClass('swiper-slide');
    }
  }
}
exports["default"] = ProductRelated;

/***/ })

}]);
//# sourceMappingURL=product-related.b4b198683829e69ad0b7.bundle.js.map