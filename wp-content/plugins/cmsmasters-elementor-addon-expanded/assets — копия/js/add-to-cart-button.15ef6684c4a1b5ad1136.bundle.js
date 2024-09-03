/*! cmsmasters-elementor-addon - v1.11.6 - 05-03-2024 */
"use strict";
(self["webpackChunkcmsmasters_elementor_addon"] = self["webpackChunkcmsmasters_elementor_addon"] || []).push([["add-to-cart-button"],{

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

/***/ "../modules/woocommerce/assets/dev/js/frontend/widgets/add-to-cart-button.js":
/*!***********************************************************************************!*\
  !*** ../modules/woocommerce/assets/dev/js/frontend/widgets/add-to-cart-button.js ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _handler = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/handler */ "../assets/dev/js/frontend/base/handler.js"));
// @since 1.1.0 Button icons has been moved to span(conflict with background).
class AddToCartButton extends _handler.default {
  getDefaultSettings() {
    return {
      selectors: {
        button: '.cmsmasters-add-to-cart > .button',
        currentButton: '.cmsmasters-add-to-cart > .button.added',
        viewCart: '.cmsmasters-add-to-cart > .added_to_cart'
      }
    };
  }
  getDefaultElements() {
    const {
      selectors
    } = this.getSettings();
    return {
      $button: this.findElement(selectors.button),
      $currentButton: this.findElement(selectors.currentButton),
      $viewCart: this.findElement(selectors.viewCart)
    };
  }
  bindEvents() {
    this.elements.$button.on('click', this.buttonAdding.bind(this));
  }
  onInit() {
    super.onInit();
  }
  buttonAdding() {
    jQuery(document.body).on('added_to_cart', this.iconChange.bind(this));
  }
  iconChange() {
    const iconControls = this.getIconControls();
    this.elements.$button.removeClass(iconControls.iconLoading);
    this.elements.$button.addClass(iconControls.iconAdded);
    this.buttonSwitch();
  }
  buttonSwitch() {
    const {
      selectors
    } = this.getSettings();
    this.findElement(selectors.currentButton).css('display', 'none');
    this.findElement(selectors.viewCart).css('display', 'inline-flex');
  }
  getIconControls() {
    const elementSettings = this.getElementSettings();
    return {
      iconLoading: elementSettings.icon_loading.value,
      iconAdded: elementSettings.icon_added.value
    };
  }
}
exports["default"] = AddToCartButton;

/***/ })

}]);
//# sourceMappingURL=add-to-cart-button.15ef6684c4a1b5ad1136.bundle.js.map