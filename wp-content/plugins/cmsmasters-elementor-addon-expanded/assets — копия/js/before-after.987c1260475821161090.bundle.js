/*! cmsmasters-elementor-addon - v1.11.6 - 05-03-2024 */
"use strict";
(self["webpackChunkcmsmasters_elementor_addon"] = self["webpackChunkcmsmasters_elementor_addon"] || []).push([["before-after"],{

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

/***/ "../modules/before-after/assets/dev/js/frontend/handlers/before-after.js":
/*!*******************************************************************************!*\
  !*** ../modules/before-after/assets/dev/js/frontend/handlers/before-after.js ***!
  \*******************************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _handler = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/handler */ "../assets/dev/js/frontend/base/handler.js"));
class BeforeAfter extends _handler.default {
  __construct(settings) {
    super.__construct(settings);
    this.offsetX = 0;
    this.offsetY = 0;
    this.imgWidth = 0;
    this.imgHeight = 0;
  }
  getDefaultSettings() {
    const widgetSelector = 'elementor-widget-cmsmasters-before-after';
    const classes = {
      widget: widgetSelector,
      imageBefore: `${widgetSelector}__image-before`,
      imageAfter: `${widgetSelector}__image-after`
    };
    const selectors = {
      container: `.${classes.widget}__container`,
      imageWrap: `.${classes.widget}__image-wrap`,
      handle: `.${classes.widget}__handle`,
      overlay: `.${classes.widget}__overlay`
    };
    return {
      classes,
      selectors
    };
  }
  getDefaultElements() {
    const {
      selectors
    } = this.getSettings();
    const elements = {
      $window: jQuery(window),
      $container: this.findElement(selectors.container),
      $imageWrap: this.findElement(selectors.imageWrap),
      $beforeImg: this.findElement('img:first'),
      $afterImg: this.findElement('img:last'),
      $handle: this.findElement(selectors.handle)
    };
    return elements;
  }
  bindEvents() {
    const settings = this.getElementSettings();
    let sliderPct = settings.default_offset.size / 100;
    this.elements.$window.on('resize', () => {
      this.adjustSlider(sliderPct);
    });
    jQuery(document).ready(() => {
      setTimeout(() => {
        this.adjustSlider(sliderPct);
      }, 500);
    });
    if ('on_hover' === settings.move) {
      this.elements.$container.on('mouseenter', () => this.onMoveStart(event));
      this.elements.$container.on('mousemove', () => this.onMove(event));
      this.elements.$container.on('mouseleave', () => this.onMoveEnd(event));
    } else {
      this.elements.$container.on('movestart', () => this.onMoveStart(event));
      this.elements.$container.on('move', () => this.onMove(event));
      this.elements.$container.on('mouseup', () => this.onMoveEnd(event));
    }
    if (settings.handle) {
      this.elements.$handle.on('touchmove', function (event) {
        event.preventDefault();
      });
    }
    this.elements.$container.find('img').on('mousedown', function (event) {
      event.preventDefault();
    });
    if ('click' === settings.move) {
      this.elements.$container.on('click', event => {
        const $container = this.elements.$container;
        const $beforeImg = this.elements.$beforeImg;
        this.offsetX = $container.offset().left;
        this.offsetY = $container.offset().top;
        this.imgWidth = $beforeImg.width();
        this.imgHeight = $beforeImg.height();
        sliderPct = this.getSliderPercentage(event.pageX, event.pageY);
        this.adjustSlider(sliderPct);
      });
    }
    if (settings.handle && settings.imitation_handle) {
      this.imitationHandle();
    }
    this.elements.$window.trigger('resize');
  }
  onInit() {
    super.onInit();
    const {
      classes
    } = this.getSettings();
    this.elements.$beforeImg.addClass(classes.imageBefore);
    this.elements.$afterImg.addClass(classes.imageAfter);
  }
  calcOffset(dimensionPct) {
    const w = this.elements.$beforeImg.width();
    const h = this.elements.$beforeImg.height();
    const handleW = this.elements.$handle.outerWidth();
    const handleH = this.elements.$handle.outerHeight();
    return {
      w: w + 'px',
      h: h + 'px',
      handleW: handleW + 'px',
      handleH: handleH + 'px',
      cw: dimensionPct * w + 'px',
      ch: dimensionPct * h + 'px',
      horHandleCW: dimensionPct * w - handleW / 2 + 'px',
      horHandleCH: h / 2 - handleH / 2 + 'px',
      vertHandleCW: w / 2 - handleW / 2 + 'px',
      vertHandleCH: dimensionPct * h - handleH / 2 + 'px'
    };
  }
  adjustContainer(offset) {
    if ('vertical' === this.getElementSettings('orientation')) {
      this.elements.$afterImg.css('clip', 'rect(' + offset.ch + ',' + offset.w + ',' + offset.h + ',0)');
    } else {
      this.elements.$afterImg.css('clip', 'rect(0,' + offset.w + ',' + offset.h + ',' + offset.cw + ')');
    }
    this.elements.$container.css({
      height: offset.h
    });
    this.elements.$imageWrap.css({
      height: offset.h
    });
  }
  adjustSlider(pct) {
    const settings = this.getElementSettings();
    const sliderOrientation = settings.orientation;
    const offset = this.calcOffset(pct);
    if (settings.handle) {
      if ('horizontal' === sliderOrientation) {
        this.elements.$handle.css('left', offset.horHandleCW);
        this.elements.$handle.css('top', offset.horHandleCH);
        this.elements.$handle.css('margin', 0);
      }
      if ('vertical' === sliderOrientation) {
        this.elements.$handle.css('top', offset.vertHandleCH);
        this.elements.$handle.css('left', offset.vertHandleCW);
        this.elements.$handle.css('margin', 0);
      }
    }
    this.adjustContainer(offset);
  }

  // Return the number specified or the min/max number if it outside the range given.
  minMaxNumber(num, min, max) {
    return Math.max(min, Math.min(max, num));
  }

  // Calculate the slider percentage based on the position.
  getSliderPercentage(positionX, positionY) {
    const sliderPercentage = 'vertical' === this.getElementSettings('orientation') ? (positionY - this.offsetY) / this.imgHeight : (positionX - this.offsetX) / this.imgWidth;
    return this.minMaxNumber(sliderPercentage, 0, 1);
  }
  onMoveStart(event) {
    const settings = this.getElementSettings();
    const sliderOrientation = settings.orientation;
    if ((event.distX > event.distY && event.distX < -event.distY || event.distX < event.distY && event.distX > -event.distY) && 'vertical' !== sliderOrientation) {
      event.preventDefault();
    } else if ((event.distX < event.distY && event.distX < -event.distY || event.distX > event.distY && event.distX > -event.distY) && 'vertical' === sliderOrientation) {
      event.preventDefault();
    }
    this.elements.$container.addClass('active');
    const $container = this.elements.$container;
    const $beforeImg = this.elements.$beforeImg;
    this.offsetX = $container.offset().left;
    this.offsetY = $container.offset().top;
    this.imgWidth = $beforeImg.width();
    this.imgHeight = $beforeImg.height();
  }
  onMove(event) {
    const {
      selectors
    } = this.getSettings();
    if (this.elements.$container.hasClass('active')) {
      this.adjustSlider(this.getSliderPercentage(event.pageX, event.pageY));
      if ('mousemove' === event.type) {
        jQuery(this).find(selectors.overlay).addClass('hover');
      }
    }
  }
  onMoveEnd(event) {
    const {
      selectors
    } = this.getSettings();
    this.elements.$container.removeClass('active');
    if ('mouseleave' === event.type) {
      jQuery(this).find(selectors.overlay).removeClass('hover');
    }
  }
  imitationHandle() {
    jQuery(document).ready(() => {
      setInterval(() => {
        const sliderOrientation = this.getElementSettings('orientation');
        if (!this.elements.$container.hasClass('active')) {
          if ('horizontal' === sliderOrientation) {
            this.elements.$handle.css('transform', 'translateX(10px)');
            setTimeout(() => {
              this.elements.$handle.css('transform', 'translateX(-10px)');
            }, 250);
            setTimeout(() => {
              this.elements.$handle.css('transform', 'translateX(0)');
            }, 500);
          }
          if ('vertical' === sliderOrientation) {
            this.elements.$handle.css('transform', 'translateY(10px)');
            setTimeout(() => {
              this.elements.$handle.css('transform', 'translateY(-10px)');
            }, 250);
            setTimeout(() => {
              this.elements.$handle.css('transform', 'translateY(0)');
            }, 500);
          }
        }
      }, 6000);
    });
  }
}
exports["default"] = BeforeAfter;

/***/ })

}]);
//# sourceMappingURL=before-after.987c1260475821161090.bundle.js.map