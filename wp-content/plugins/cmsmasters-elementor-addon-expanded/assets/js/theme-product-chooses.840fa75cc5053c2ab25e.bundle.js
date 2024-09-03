/*! cmsmasters-elementor-addon - v1.11.6 - 05-03-2024 */
"use strict";
(self["webpackChunkcmsmasters_elementor_addon"] = self["webpackChunkcmsmasters_elementor_addon"] || []).push([["theme-product-chooses"],{

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

/***/ "../modules/theme/assets/dev/js/frontend/widgets/theme-product-chooses.js":
/*!********************************************************************************!*\
  !*** ../modules/theme/assets/dev/js/frontend/widgets/theme-product-chooses.js ***!
  \********************************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _handler = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/handler */ "../assets/dev/js/frontend/base/handler.js"));
class ThemeProductChooses extends _handler.default {
  __construct(settings) {
    super.__construct(settings);
  }
  getDefaultSettings() {
    const baseClass = 'elementor-widget-cmsmasters-theme-product-chooses';
    const classes = {
      wrapper: `${baseClass}__wrapper`,
      wrapperInner: `${baseClass}__wrapper-inner`,
      product: `${baseClass}__product`,
      showButton: `${baseClass}__show`,
      elementorWidgetContainer: 'elementor-widget-container',
      headerIcon: 'product-chooses-header-icon',
      popup: 'popup'
    };
    const selectors = {
      wrapper: `.${baseClass}__wrapper`,
      wrapperInner: `.${baseClass}__wrapper-inner`,
      product: `.${baseClass}__product`,
      showButton: `.${baseClass}__show`,
      elementorWidgetContainer: '.elementor-widget-container',
      headerIcon: '.product-chooses-header-icon',
      popup: '.popup'
    };
    return {
      classes,
      selectors
    };
  }
  getDefaultElements() {
    const {
      selectors,
      classes
    } = this.getSettings();
    const elements = {
      $wrapper: this.findElement(selectors.wrapper),
      $wrapperInner: this.findElement(selectors.wrapperInner),
      $product: this.findElement(selectors.product),
      $showButton: this.findElement(selectors.showButton),
      $headerIcon: this.findElement(selectors.headerIcon),
      $popup: this.findElement(selectors.popup)
    };
    return elements;
  }
  bindEvents() {
    this.elements.$headerIcon.on('click', this.popupClose.bind(this));
    this.elements.$showButton.on('click', this.showButtonClick.bind(this));
  }
  onInit() {
    super.onInit();
  }
  popupClose(event) {
    const {
      selectors
    } = this.getSettings();
    const $this = jQuery(event.currentTarget);
    $this.closest(selectors.wrapper).removeClass('visible').addClass('hidden').find(selectors.wrapperInner).removeClass('visible').addClass('hidden');
    $this.closest('html').css('overflow-y', '');
  }
  showButtonClick(event) {
    const {
      selectors
    } = this.getSettings();
    const $this = jQuery(event.currentTarget);
    const type = $this.attr('type');
    $this.closest(selectors.elementorWidgetContainer).find(selectors.popup).removeClass('hidden').addClass('visible').find(`${selectors.wrapperInner}[type="${type}"]`).addClass('visible');
    $this.closest('html').css('overflow-y', 'hidden');
  }
}
exports["default"] = ThemeProductChooses;

/***/ })

}]);
//# sourceMappingURL=theme-product-chooses.840fa75cc5053c2ab25e.bundle.js.map