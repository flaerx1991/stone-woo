/*! cmsmasters-elementor-addon - v1.11.6 - 05-03-2024 */
"use strict";
(self["webpackChunkcmsmasters_elementor_addon"] = self["webpackChunkcmsmasters_elementor_addon"] || []).push([["add-to-cart"],{

/***/ "../assets/dev/js/frontend/base/handler.js":
/*!*************************************************!*\
  !*** ../assets/dev/js/frontend/base/handler.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
class _default extends elementorModules.frontend.handlers.Base {
  __construct() {
    super.__construct(...arguments);
    this.bindElements = [];
    this.deviceNames = ['mobile', 'tablet', 'desktop'];
    this.devicePrefixMaps = {
      mobile: 'mobile',
      tablet: 'tablet',
      desktop: ''
    };
  }
  bindElementChange(names, callback) {
    this.bindElements.push([names, callback]);
  }
  onElementChange(controlName) {
    if (!this.bindElements || !this.bindElements.length) {
      return;
    }
    this.bindElements.forEach(bindElement => {
      let [bindNames] = bindElement;
      if (!Array.isArray(bindNames)) {
        bindNames = bindNames.split(/\s/);
      }
      const [, callback] = bindElement;
      bindNames.some(name => {
        const bindNamesResponsive = [name, `${name}_tablet`, `${name}_mobile`];
        if (-1 !== bindNamesResponsive.indexOf(controlName)) {
          callback(...arguments);
          return true;
        }
      });
    });
  }
  onDestroy() {
    this.trigger('destroy:before');
    super.onDestroy();
  }
  getCurrentDeviceSettingInherit(settingKey) {
    const devices = ['desktop', 'tablet', 'mobile'];
    const deviceMode = elementorFrontend.getCurrentDeviceMode();
    const settings = this.getElementSettings();
    let deviceIndex = devices.indexOf(deviceMode);
    while (deviceIndex > 0) {
      const currentDevice = devices[deviceIndex];
      const fullSettingKey = settingKey + '_' + currentDevice;
      const deviceValue = settings[fullSettingKey];
      if (deviceValue && 'object' === typeof deviceValue && Object.prototype.hasOwnProperty.call(deviceValue, 'size') && deviceValue.size) {
        return deviceValue;
      }
      deviceIndex--;
    }
    return settings[settingKey];
  }
  getCurrentDeviceSettingSize(settingKey) {
    let deviceValue = this.getCurrentDeviceSettingInherit(settingKey);
    if ('object' === typeof deviceValue && Object.prototype.hasOwnProperty.call(deviceValue, 'size')) {
      deviceValue = deviceValue.size;
    }
    return deviceValue;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/woocommerce/assets/dev/js/frontend/widgets/add-to-cart.js":
/*!****************************************************************************!*\
  !*** ../modules/woocommerce/assets/dev/js/frontend/widgets/add-to-cart.js ***!
  \****************************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _handler = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/handler */ "../assets/dev/js/frontend/base/handler.js"));
class AddToCart extends _handler.default {
  getDefaultSettings() {
    return {
      selectors: {
        variations: '.variations_form',
        wpcvsTerms: '.wpcvs-terms',
        select: '.variations select'
      }
    };
  }
  getDefaultElements() {
    const {
      selectors
    } = this.getSettings();
    return {
      $variations: this.findElement(selectors.variations),
      $wpcvsTerms: this.findElement(selectors.wpcvsTerms),
      $select: this.findElement(selectors.select)
    };
  }
  bindEvents() {
    super.bindEvents();
    this.elements.$wpcvsTerms.on('click', this.changeBadgeSale.bind(this));
    jQuery(document).on('reset_data', () => {
      this.changeBadgeSale();
    });
  }
  onInit() {
    super.onInit();
    this.changeBadgeSale();
  }
  isMatch(variation_attributes, attributes) {
    let match = true;
    for (const attr_name in variation_attributes) {
      if (variation_attributes.hasOwnProperty(attr_name)) {
        const val1 = variation_attributes[attr_name];
        const val2 = attributes[attr_name];
        if (val1 !== undefined && val2 !== undefined && val1.length !== 0 && val2.length !== 0 && val1 !== val2) {
          match = false;
        }
      }
    }
    return match;
  }
  findMatchingVariations(variations, attributes) {
    const matching = [];
    if (!variations) {
      return;
    }
    for (let i = 0; i < variations.length; i++) {
      const variation = variations[i];
      if (this.isMatch(variation.attributes, attributes)) {
        matching.push(variation);
      }
    }
    return matching;
  }
  getChosenAttributes() {
    const data = {};
    let count = 0;
    let chosen = 0;
    this.elements.$select.each(function () {
      const attribute_name = jQuery(this).data('attribute_name') || jQuery(this).attr('name');
      const value = jQuery(this).val() || '';
      if (value.length > 0) {
        chosen++;
      }
      count++;
      data[attribute_name] = value;
    });
    return {
      'count': count,
      'chosenCount': chosen,
      'data': data
    };
  }
  changeBadgeSale(event, chosenAttributes) {
    let $discount = '';
    if (jQuery('body').hasClass('single-product')) {
      $discount = jQuery('body').find('.elementor-widget-cmsmasters-woo-badge-sale');
    } else if (jQuery('body').find('.cmsmasters_product_entry')) {
      $discount = jQuery('.cmsmasters_product_entry').find('.elementor-widget-cmsmasters-woo-badge-sale');
    }
    const variationData = this.elements.$variations.data('product_variations');
    if (!variationData) {
      return;
    }
    const attributes = 'undefined' !== typeof chosenAttributes ? chosenAttributes : this.getChosenAttributes();
    const matching_variations = this.findMatchingVariations(variationData, attributes.data);
    const variation = matching_variations.shift();
    const price = variation['display_price'];
    const regularPrice = variation['display_regular_price'];
    let newDiscount = '';
    if ($discount.hasClass('cmsmasters-discoun-rounding-yes')) {
      newDiscount = Math.round((regularPrice - price) / regularPrice * 100);
    } else {
      newDiscount = ((regularPrice - price) / regularPrice * 100).toFixed(1);
    }
    if (0 === newDiscount || '0.0' === newDiscount) {
      $discount.hide();
    } else {
      const $saleBadgeText = $discount.find('.cmsmasters-woo-badge-inner-text');
      $saleBadgeText.text(function (index, oldText) {
        const match = oldText.match(/[\d.]+/);
        if (match) {
          const number = match[0];
          if (0 === number || '0.0' === number) {
            $discount.hide();
          } else {
            $discount.show();
            return oldText.replace(number, newDiscount);
          }
        } else {
          $discount.show();
          return oldText;
        }
      });
    }
  }
}
exports["default"] = AddToCart;

/***/ })

}]);
//# sourceMappingURL=add-to-cart.31790ec8542ba6025007.bundle.js.map