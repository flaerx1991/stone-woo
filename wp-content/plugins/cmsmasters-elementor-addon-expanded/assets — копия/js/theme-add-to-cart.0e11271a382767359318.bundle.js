/*! cmsmasters-elementor-addon - v1.11.6 - 05-03-2024 */
"use strict";
(self["webpackChunkcmsmasters_elementor_addon"] = self["webpackChunkcmsmasters_elementor_addon"] || []).push([["theme-add-to-cart"],{

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

/***/ "../modules/theme/assets/dev/js/frontend/widgets/theme-add-to-cart.js":
/*!****************************************************************************!*\
  !*** ../modules/theme/assets/dev/js/frontend/widgets/theme-add-to-cart.js ***!
  \****************************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _handler = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/handler */ "../assets/dev/js/frontend/base/handler.js"));
class ThemeAddToCart extends _handler.default {
  __construct(settings) {
    super.__construct(settings);
  }
  getDefaultSettings() {
    const baseClass = 'elementor-widget-cmsmasters-theme-add-to-cart';
    const classes = {
      // wrapper: `${ baseClass }__wrapper`,
    };
    const selectors = {
      addToQuote: `.${baseClass}__add-to-quote`,
      inputs: `.${baseClass}__add-to-quote-inputs`,
      inputItem: `.${baseClass}__add-to-quote-input-item`,
      input: `.${baseClass}__add-to-quote-input`,
      inputOperator: `.${baseClass}__add-to-quote-input-operator`,
      onlyEach: '.only_each',
      quoteButtonWrap: `.${baseClass}__add-to-quote-button-wrap`,
      quoteButton: '.theme_add_to_quote',
      addToCart: '.add_to_cart_button'
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
      $addToQuote: this.findElement(selectors.addToQuote),
      $inputs: this.findElement(selectors.inputs),
      $inputItem: this.findElement(selectors.inputItem),
      $input: this.findElement(selectors.input),
      $inputOperator: this.findElement(selectors.inputOperator),
      $quoteButton: this.findElement(selectors.quoteButton),
      $addToCart: this.findElement(selectors.addToCart)
    };
    return elements;
  }
  bindEvents() {
    const {
      selectors
    } = this.getSettings();
    this.elements.$inputOperator.on('click', this.inputOperatorClick.bind(this));
    if ('sqft' === this.elements.$addToQuote.attr('product-uom')) {
      this.elements.$input.on('input', this.textInput.bind(this));
      jQuery(document).on('input', this.activeInput.bind(this));
    }
    this.elements.$input.on('click focus', this.clickActiveInput.bind(this));
    this.elements.$quoteButton.on('click', this.quoteButtonClick.bind(this));
    this.elements.$addToCart.on('click', this.addingToCartAjax.bind(this));
  }
  onInit() {
    super.onInit();
  }
  quoteButtonClick(event) {
    const {
      selectors
    } = this.getSettings();
    event.preventDefault();
    const $this = jQuery(event.currentTarget);
    const $quoteButtonWrap = $this.closest(selectors.quoteButtonWrap);
    let $add_to_cart_el = null;
    let $product_id_el = null;
    if ($this.parents('ul.products').length > 0) {
      $add_to_cart_el = $this.parents('li.product').find('input[name="add-to-cart"]');
      $product_id_el = $this.parents('li.product').find('input[name="product_id"]');
    } else {
      $add_to_cart_el = $this.parents('.product').find('input[name="add-to-cart"]');
      $product_id_el = $this.parents('.product').find('input[name="product_id"]');
    }
    const $addToQuote = this.elements.$addToQuote;
    const wpnonce = $this.data('wp_nonce');
    const productId = $this.data('product_id');
    const listText = $this.data('list_text');
    const uom = $addToQuote.attr('product-uom');
    const $input = $addToQuote.find(selectors.inputItem + '[product-uom="' + uom + '"] input');
    let inputValue = $input.val();
    if (isNaN(inputValue) || 0 === inputValue.length) {
      inputValue = 1;
    }
    const isPremium = $quoteButtonWrap.hasClass('quote_premium');
    let add_to_cart_info;
    if (isPremium) {
      add_to_cart_info = new FormData();
      add_to_cart_info.append('context', 'frontend');
      add_to_cart_info.append('action', 'yith_ywraq_action');
      add_to_cart_info.append('ywraq_action', 'add_item');
      add_to_cart_info.append('product_id', productId);
      add_to_cart_info.append('wp_nonce', wpnonce);
      add_to_cart_info.append('yith-add-to-cart', productId);
      add_to_cart_info.append('quantity', inputValue);
    } else {
      add_to_cart_info = 'ac';
      if ($add_to_cart_el.length > 0 && $product_id_el.length > 0) {
        add_to_cart_info = jQuery('.cart').serialize();
      } else if (jQuery('.cart').length > 0) {
        add_to_cart_info = jQuery('.cart').serialize();
      }
      add_to_cart_info += '&action=yith_ywraq_action&ywraq_action=add_item&quantity=' + inputValue + '&product_id=' + productId + '&_wpnonce=' + wpnonce;
    }
    const ywraqUrl = isPremium ? ywraq_frontend.ajaxurl.toString().replace('%%endpoint%%', 'yith_ywraq_action') : ywraq_frontend.ajaxurl;
    jQuery.ajax({
      type: 'POST',
      url: ywraqUrl,
      dataType: 'json',
      data: add_to_cart_info,
      contentType: false,
      processData: false,
      beforeSend: function () {
        $this.siblings('.ajax-loading').css({
          opacity: '1',
          visibility: 'visible'
        });
      },
      complete: function () {
        $this.siblings('.ajax-loading').css({
          opacity: '0',
          visibility: 'hidden'
        });
      },
      success: response => {
        if (response.result == 'true' || response.result == 'exists') {
          if (ywraq_frontend.go_to_the_list === 'yes') {
            window.location.href = response.rqa_url;
          } else {
            $this.parent().hide().removeClass('show').addClass('addedd');
            const prod_id = typeof $product_id_el.val() == 'undefined' ? '' : '-' + $product_id_el.val();
            $quoteButtonWrap.append('<div class="yith_ywraq_add_item_browse-list' + prod_id + ' yith_ywraq_add_item_browse_message"><a href="' + response.rqa_url + '">' + listText + '</a></div>');
          }
        } else if (response.result == 'false') {
          $quoteButtonWrap.append('<div class="yith_ywraq_add_item_response-' + $product_id_el.val() + '">' + response.message + '</div>');
        }

        // For quote count
        let count = Number(jQuery('.request-quote-icon--count').text());
        if (!count) count = 0;
        jQuery('.request-quote-icon--count').text(count + 1);

        // For quote message
        const $message = jQuery('<div class="add-to-button-success-message fas fa-check"><span>The product has been added to your qoute list.</span><a href="/request-quote/">Open</a></div>');
        $addToQuote.append($message);
        setTimeout(function () {
          $message.fadeOut(300, function () {
            jQuery(this).remove();
          });
        }, 3000);
      }
    });
  }
  run() {
    const requestManager = this;
    const originalCallback = requestManager.requests[0].complete;
    requestManager.requests[0].complete = function () {
      if (typeof originalCallback === 'function') {
        originalCallback();
      }
      requestManager.requests.shift();
      if (requestManager.requests.length > 0) {
        requestManager.run();
      }
    };
    jQuery.ajax(this.requests[0]);
  }
  addRequest(request) {
    this.requests = [];
    this.requests.push(request);
    if (1 === this.requests.length) {
      this.run();
    }
  }
  addingToCartAjax(event) {
    const {
      selectors
    } = this.getSettings();
    if (this.isEdit) {
      return;
    }
    const $this = jQuery(event.currentTarget);
    if (!$this.attr('data-product_id')) {
      return true;
    }
    event.preventDefault();
    if (false === jQuery(document.body).triggerHandler('should_send_ajax_request.adding_to_cart', [$this])) {
      jQuery(document.body).trigger('ajax_request_not_sent.adding_to_cart', [false, false, $this]);
      return true;
    }
    const data = {};
    const $addToQuote = $this.closest(selectors.addToQuote);
    const uom = $addToQuote.attr('product-uom');
    const $input = $addToQuote.find(selectors.inputItem + '[product-uom="' + uom + '"] input');
    let inputValue = $input.val();
    if (isNaN(inputValue) || 0 === inputValue.length) {
      inputValue = 1;
    }
    data['quantity'] = inputValue;
    jQuery.each($this.data(), function (key, value) {
      data[key] = value;
    });
    jQuery.each($this[0].dataset, function (key, value) {
      data[key] = value;
    });
    jQuery(document.body).trigger('adding_to_cart', [$this, data]);
    this.addRequest({
      type: 'POST',
      url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
      data: data,
      beforeSend: function () {
        $this.find('.ajax-loading').css({
          opacity: '1',
          visibility: 'visible'
        });
      },
      complete: function () {
        $this.find('.ajax-loading').css({
          opacity: '0',
          visibility: 'hidden'
        });
      },
      success: function (response) {
        if (!response) {
          return;
        }
        if (response.error && response.product_url) {
          window.location = response.product_url;
          return;
        }
        if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
          window.location = wc_add_to_cart_params.cart_url;
          return;
        }
        jQuery(document.body).trigger('added_to_cart', [response.fragments, $this]);

        // Change Add To Cart on View Cart
        // $this
        // 	.attr( 'href', wc_add_to_cart_params.cart_url )
        // 	.attr( 'title', wc_add_to_cart_params.i18n_view_cart )
        // 	.text( wc_add_to_cart_params.i18n_view_cart );

        const $message = jQuery('<div class="add-to-button-success-message fas fa-check"><span>The product has been added to your cart.</span><a href="/cart/">Open</a></div>');
        $addToQuote.append($message);

        // For button message
        setTimeout(function () {
          $message.fadeOut(300, function () {
            jQuery(this).remove();
          });
        }, 3000);
      },
      dataType: 'json'
    });
  }
  incrementValue($input, value, $uom, fullPackaged, roundedResult, productPack) {
    if ('each' === $uom) {
      value += 1;
      $input.val(value.toFixed(0));
    } else {
      if ('yes' === fullPackaged) {
        value += productPack;
        $input.val(value.toFixed(0));
      } else {
        value += roundedResult;
        let oldValue = value / roundedResult;
        oldValue = oldValue.toFixed(0) * roundedResult;
        const newValue = value - (value - oldValue);
        $input.val(newValue.toFixed(2));
      }
    }
  }
  decrementValue($input, value, $uom, fullPackaged, roundedResult, productPack) {
    if ('each' === $uom) {
      if (value >= 1) {
        value -= 1;
        $input.val(value.toFixed(0));
      } else {
        $input.val(0);
      }
    } else {
      if ('yes' === fullPackaged) {
        if (value > productPack) {
          value -= productPack;
          $input.val(value.toFixed(0));
        } else {
          $input.val(0);
        }
      } else {
        if (value > roundedResult) {
          value -= roundedResult;
          let oldValue = value / roundedResult;
          oldValue = oldValue.toFixed(0) * roundedResult;
          const newValue = value - (value - oldValue);
          $input.val(newValue.toFixed(2));
        } else {
          $input.val(0);
        }
      }
    }
  }
  anotherInputChange($this, $inputItem, $input, fullPackaged, pack) {
    const {
      selectors
    } = this.getSettings();
    const $uom = $inputItem.attr('product-uom');
    const $width = $inputItem.attr('product-width');
    const $height = $inputItem.attr('product-height');
    let area = $width * $height / 144;
    const roundedResult = Number.isInteger(area) ? area : area;
    const inputValue = parseFloat($input.val());
    const productPack = Number.isInteger(pack) ? pack : parseFloat(pack);
    let coefficient = roundedResult;
    if ('yes' === fullPackaged) {
      coefficient = productPack;
    }
    let newVal = '';
    if ('each' === $uom) {
      newVal = Number.isInteger(inputValue * coefficient) ? inputValue * coefficient : (inputValue * coefficient).toFixed(2);
    } else {
      newVal = Math.ceil(inputValue.toFixed(2) / coefficient.toFixed(2));
    }
    if (!isNaN(inputValue)) {
      newVal = newVal;
    } else {
      newVal = '';
    }
    const anotherInputUom = 'each' === $uom ? 'sqft' : 'each';
    const $anotherInput = $this.closest(selectors.inputs).find(selectors.inputItem + '[product-uom="' + anotherInputUom + '"]').find(selectors.input);
    $anotherInput.val(newVal);
  }
  inputChange($this, $inputItem, $input, fullPackaged, pack) {
    const $uom = $inputItem.attr('product-uom');
    const $width = $inputItem.attr('product-width');
    const $height = $inputItem.attr('product-height');
    let area = $width * $height / 144;
    const roundedResult = Number.isInteger(area) ? area : area;
    const inputValue = parseFloat($input.val());
    const productPack = Number.isInteger(pack) ? pack : parseFloat(pack);
    let newVal = '';
    if ('sqft' === $uom) {
      let coefficient = roundedResult;
      if ('yes' === fullPackaged) {
        coefficient = productPack;
      }
      newVal = Math.ceil(inputValue / coefficient);
      if (coefficient % 1 !== 0) {
        newVal = Math.ceil(newVal) * coefficient;
        if (!isNaN(inputValue)) {
          newVal = newVal;
        } else {
          newVal = '';
        }
        $input.val(newVal.toFixed(2));
      } else {
        newVal = newVal * coefficient;
        if (!isNaN(inputValue)) {
          newVal = newVal;
        } else {
          newVal = '';
        }
        $input.val(newVal.toFixed(0));
      }
    }
  }
  inputOperatorClick(event) {
    const {
      selectors
    } = this.getSettings();
    const $this = jQuery(event.currentTarget);
    const $inputItem = $this.parent();
    const $input = $inputItem.find(selectors.input);
    const pack = $input.attr('pack');
    let fullPackaged = 'no';
    if ('yes' === $input.attr('full-packaged')) {
      fullPackaged = 'yes';
    }
    let value = parseFloat($input.val()) || 0;
    const $uom = $inputItem.attr('product-uom');
    const $width = parseFloat($inputItem.attr('product-width'));
    const $height = parseFloat($inputItem.attr('product-height'));
    let area = $width * $height / 144;
    const roundedResult = Number.isInteger(area) ? area : parseFloat(area);
    const productPack = Number.isInteger(pack) ? pack : parseFloat(pack);
    if ($this.hasClass('increment')) {
      this.incrementValue($input, value, $uom, fullPackaged, roundedResult, productPack);
    } else {
      this.decrementValue($input, value, $uom, fullPackaged, roundedResult, productPack);
    }
    this.anotherInputChange($this, $inputItem, $input, fullPackaged, pack);
    this.activeInput();
  }
  textInput(event) {
    const {
      selectors
    } = this.getSettings();
    const $this = jQuery(event.currentTarget);
    const $inputItem = $this.parent();
    const $input = $inputItem.find(selectors.input);
    const pack = $input.attr('pack');
    let fullPackaged = 'no';
    if ('yes' === $input.attr('full-packaged')) {
      fullPackaged = 'yes';
    }
    this.anotherInputChange($this, $inputItem, $input, fullPackaged, pack);
    setTimeout(() => {
      this.inputChange($this, $inputItem, $input, fullPackaged, pack);
    }, 1000);
    this.activeInput();
  }
  activeInput() {
    const {
      selectors
    } = this.getSettings();
    this.elements.$inputs.find(selectors.inputItem).each(function () {
      const $inputItem = jQuery(this);
      const $input = $inputItem.find(selectors.input);
      if ($input.val().trim() !== '') {
        $inputItem.addClass('active');
        $inputItem.find('.decrement').removeClass('disable');
      }
      $input.focus(function () {
        $inputItem.addClass('active');
      });
      $input.blur(function () {
        if (!$input.is(':focus') && $input.val().trim() === '') {
          $input.closest(selectors.inputs).find(selectors.inputItem).removeClass('active');
        }
      });
    });
  }
  clickActiveInput(event) {
    const {
      selectors
    } = this.getSettings();
    const $this = jQuery(event.currentTarget);
    const $inputs = $this.closest(selectors.inputs);
    const $inputItem = $inputs.find(selectors.inputItem);
    const $input = $inputItem.find(selectors.input);
    if ('' !== $this.val().trim()) {
      $this.closest(selectors.inputItem).addClass('active').find('.decrement').removeClass('disable');
    }
    $this.on('focus', function () {
      $this.closest(selectors.inputItem).addClass('active');
    });
    $input.on('blur', function () {
      if (!$input.is(':focus') && '' === $this.val().trim()) {
        $this.closest(selectors.inputItem).removeClass('active');
      }
    });
  }
}
exports["default"] = ThemeAddToCart;

/***/ })

}]);
//# sourceMappingURL=theme-add-to-cart.0e11271a382767359318.bundle.js.map