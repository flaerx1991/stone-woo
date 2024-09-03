/*! cmsmasters-elementor-addon - v1.11.6 - 05-03-2024 */
"use strict";
(self["webpackChunkcmsmasters_elementor_addon"] = self["webpackChunkcmsmasters_elementor_addon"] || []).push([["theme-blog-slider"],{

/***/ "../modules/blog/assets/dev/js/frontend/widgets/blog/theme-blog-slider.js":
/*!********************************************************************************!*\
  !*** ../modules/blog/assets/dev/js/frontend/widgets/blog/theme-blog-slider.js ***!
  \********************************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _base = _interopRequireDefault(__webpack_require__(/*! ./base/base */ "../modules/blog/assets/dev/js/frontend/widgets/blog/base/base.js"));
var _documentHandles = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/modules/document-handles */ "../assets/dev/js/frontend/modules/document-handles.js"));
var _slider = _interopRequireDefault(__webpack_require__(/*! cmsmasters-slider-module/frontend/slider */ "../modules/slider/assets/dev/js/frontend/slider.js"));
const utils = __webpack_require__(/*! cmsmasters-helpers/utils */ "../assets/dev/js/helpers/utils.js");
class ThemeBlogSlider extends _base.default {
  __construct() {
    super.__construct(...arguments);
    this.slider = null;
    this.handles = null;
  }
  getDefaultSettings() {
    const settings = super.getDefaultSettings(...arguments);
    const baseClass = 'elementor-widget-cmsmasters-theme-blog';
    settings.selectors = Object.assign(settings.selectors, {
      widgetWrapper: '.cmsmasters-blog',
      post: `.${baseClass}__post`,
      popupTrigger: `.${baseClass}__post-add-to-button-trigger`,
      popupPopup: `.${baseClass}__post-add-to-quote-popup`,
      popupClose: `.${baseClass}__post-add-to-quote-popup-close`,
      addToQuote: `.${baseClass}__post-add-to-quote`,
      inputs: `.${baseClass}__post-add-to-quote-inputs`,
      inputItem: `.${baseClass}__post-add-to-quote-input-item`,
      input: `.${baseClass}__post-add-to-quote-input`,
      inputOperator: `.${baseClass}__post-add-to-quote-input-operator`,
      quoteButtonWrap: `.${baseClass}__post-add-to-quote-button-wrap`,
      quoteButton: '.theme_add_to_quote_popup',
      wrapper: `.${baseClass}__wrapper`,
      wrapperInner: `.${baseClass}__wrapper-inner`,
      // showButton: `.${ baseClass }__show`,
      elementorWidgetContainer: '.elementor-widget-container',
      // headerIcon: '.product-chooses-header-icon',
      popup: '.popup',
      checkbox: `.${baseClass}__post-add-to-quote-popup-choose-product-attr-checkbox`,
      popupCont: `.${baseClass}__post-add-to-quote-popup-cont`,
      popupChooseProduct: `.${baseClass}__post-add-to-quote-popup-choose-product`,
      popupChooseInnerContWrap: `.${baseClass}__post-add-to-quote-popup-collection-inner-cont-wrap`,
      addToCart: '.single_add_to_cart_button'
    });
    return settings;
  }
  getDefaultElements() {
    const elements = super.getDefaultElements(...arguments);
    const {
      selectors
    } = this.getSettings();
    elements.$widgetWrapper = this.findElement(selectors.widgetWrapper);
    elements.$addToQuote = this.findElement(selectors.addToQuote);
    elements.$inputs = this.findElement(selectors.inputs);
    elements.$input = this.findElement(selectors.input);
    return elements;
  }
  initElements() {
    super.initElements();
    this.slider = new _slider.default({
      widget: this
    });
  }
  bindEvents() {
    const {
      selectors
    } = this.getSettings();
    const settings = this.getElementSettings();
    this.bindElementChange('image_ratio image_ratio_switcher', utils.debounce(this.slider.update.bind(this.slider)));
    this.slider.on('options', options => {
      if (options.loop && options.slidesPerView > this.slider.elements.$slides.length) {
        options.loop = false;
      }
    });
    if ('product' === settings.blog_post_type) {
      this.elements.$widgetWrapper.on('click', selectors.popupTrigger, this.popupTriggerClick.bind(this));
      this.elements.$widgetWrapper.on('click', selectors.popupClose, this.popupCloseClick.bind(this));
      this.elements.$widgetWrapper.on('click', selectors.inputOperator, this.inputOperatorClick.bind(this));

      // if ( 'sqft' === this.elements.$addToQuote.attr( 'product-uom' ) ) {
      this.elements.$widgetWrapper.on('input', selectors.input, this.textInput.bind(this));
      jQuery(document).on('input', this.activeInput.bind(this));
      // }

      this.elements.$widgetWrapper.on('click', selectors.quoteButton, this.quoteButtonClick.bind(this));
      this.elements.$widgetWrapper.on('click', selectors.addToCart, this.addingToCartAjax.bind(this));

      // this.elements.$widgetWrapper.on( 'click', selectors.headerIcon, this.popupClose.bind( this ) );

      // this.elements.$widgetWrapper.on( 'click', selectors.showButton, this.showButtonClick.bind( this ) );

      this.elements.$widgetWrapper.on('change', selectors.checkbox, this.checkboxClick.bind(this));
    }
  }
  onInit() {
    super.onInit();
    this.slider.init();
    this.initHandles();
  }
  initHandles() {
    if (!elementorFrontend.isEditMode()) {
      return;
    }
    const controls = {};
    this.handles = new _documentHandles.default({
      widget: this.$element,
      controls: controls,
      type: 'listing'
    });
  }

  // Add To Quote
  popupTriggerClick(event) {
    const {
      selectors
    } = this.getSettings();
    const $this = jQuery(event.currentTarget);
    const $post_id = $this.closest(selectors.post).attr('id');
    $this.addClass('active');
    $this.closest(selectors.widgetWrapper).find(`${selectors.popupPopup}#${$post_id}`).addClass('visible');
    $this.closest('html').css('overflow-y', 'hidden');
  }
  popupCloseClick(event) {
    const {
      selectors
    } = this.getSettings();
    const $this = jQuery(event.currentTarget);
    const $post_id = $this.closest(selectors.popupPopup).attr('id');
    $this.closest(selectors.popupPopup).removeClass('visible');
    $this.closest(selectors.widgetWrapper).find(`${selectors.post}#${$post_id}`).find(selectors.popupTrigger).removeClass('active');
    $this.closest('html').css('overflow-y', '');
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
    const $addToQuote = $this.closest(selectors.addToQuote);
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
        $this.parent().addClass('choose_hide').removeClass('choose_show').closest(selectors.popupCont).find(`${selectors.popupChooseProduct}.choose`).addClass('added-product');
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
            const nextElement = $this.parent().next();
            if (!nextElement.length > 0) {
              const prod_id = typeof $product_id_el.val() == 'undefined' ? '' : '-' + $product_id_el.val();
              $quoteButtonWrap.append('<div class="yith_ywraq_add_item_browse-list' + prod_id + ' yith_ywraq_add_item_browse_message"><a href="' + response.rqa_url + '">' + listText + '</a></div>');
            }
          }
        } else if (response.result == 'false') {
          $quoteButtonWrap.append('<div class="yith_ywraq_add_item_response-' + $product_id_el.val() + '">' + response.message + '</div>');
        }

        // For quote count
        let count = Number(jQuery('.request-quote-icon--count').text());
        if (!count) count = 0;
        jQuery('.request-quote-icon--count').text(count + 1);
        const $message = jQuery('<div class="post-add-to-button-success-message fas fa-check"><span>The product has been added to your qoute list.</span><a href="/request-quote/">Open</a></div>');
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

        const $message = jQuery('<div class="post-add-to-button-success-message fas fa-check"><span>The product has been added to your cart.</span><a href="/cart/">Open</a></div>');
        $addToQuote.append($message);
        setTimeout(function () {
          $message.fadeOut(300, function () {
            jQuery(this).remove();
          });
        }, 3000);
      },
      dataType: 'json'
    });
  }
  incrementValue($input) {
    let value = parseInt($input.val(), 10) || 0;
    value++;
    $input.val(value);
  }
  decrementValue($input) {
    let value = parseInt($input.val(), 10) || 0;
    if (value > 0) {
      value--;
      $input.val(value);
    }
  }
  anotherInputChange($this, $inputItem, $input) {
    const {
      selectors
    } = this.getSettings();
    const $uom = $inputItem.attr('product-uom');
    const $width = $inputItem.attr('product-width');
    const $height = $inputItem.attr('product-height');
    let area = $width * $height / 144;
    const roundedResult = Number.isInteger(area) ? area : area.toFixed(2);
    const inputValue = parseFloat($input.val());
    let newVal = '';
    if ('each' === $uom) {
      newVal = Number.isInteger(inputValue * roundedResult) ? inputValue * roundedResult : (inputValue * roundedResult).toFixed(2);
    } else {
      newVal = Math.ceil(inputValue / roundedResult);
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
  inputOperatorClick(event) {
    const {
      selectors
    } = this.getSettings();
    const $this = jQuery(event.currentTarget);
    const $inputItem = $this.parent();
    const $input = $inputItem.find(selectors.input);
    const isIncrement = $this.hasClass('increment');
    if (isIncrement) {
      this.incrementValue($input);
    } else {
      this.decrementValue($input);
    }
    this.anotherInputChange($this, $inputItem, $input);
    this.activeInput();
  }
  textInput(event) {
    const {
      selectors
    } = this.getSettings();
    const $this = jQuery(event.currentTarget);
    const $inputItem = $this.parent();
    const $input = $inputItem.find(selectors.input);
    this.anotherInputChange($this, $inputItem, $input);
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
          $inputItem.removeClass('active');
        }
      });
    });
  }

  // popupClose( event ) {
  // 	const { selectors } = this.getSettings();

  // 	const $this = jQuery( event.currentTarget );

  // 	$this
  // 		.closest( selectors.wrapper )
  // 		.removeClass( 'visible' )
  // 		.addClass( 'hidden' )
  // 		.find( selectors.wrapperInner )
  // 		.removeClass( 'visible' )
  // 		.addClass( 'hidden' );
  // }

  // showButtonClick( event ) {
  // 	const { selectors } = this.getSettings();

  // 	const $this = jQuery( event.currentTarget );
  // 	const type = $this.attr( 'type' );

  // 	$this
  // 		.closest( selectors.elementorWidgetContainer )
  // 		.find( selectors.popup )
  // 		.removeClass( 'hidden' )
  // 		.addClass( 'visible' )
  // 		.find( `${selectors.wrapperInner}[type="${type}"]` )
  // 		.addClass( 'visible' );
  // }

  checkboxClick(event) {
    const {
      selectors
    } = this.getSettings();
    const $this = jQuery(event.currentTarget);
    const productID = $this.attr('id');
    const dataWpNonce = $this.attr('data-wp_nonce');
    const dataUoms = $this.attr('data-uoms').toLowerCase();
    const dataProductWidth = $this.attr('data-product-width');
    const dataProductHeight = $this.attr('data-product-height');
    const $popupCont = $this.closest(selectors.popupCont);
    const $addToQuote = $popupCont.find(selectors.addToQuote);
    const $quoteButton = $popupCont.find(selectors.quoteButton);
    const $inputItem = $popupCont.find(selectors.inputItem);
    $this.closest(selectors.popupCont).find(selectors.checkbox).each(function () {
      const $checkbox = jQuery(this);
      $checkbox.closest(selectors.popupChooseProduct).removeClass('choose').addClass('not_chooses');
    });
    $this.closest(selectors.popupChooseProduct).removeClass('not_chooses').addClass('choose');
    $addToQuote.attr('product-uom', dataUoms);
    $quoteButton.attr('data-product_id', productID).attr('data-wp_nonce', dataWpNonce);
    $inputItem.attr('product-width', dataProductWidth).attr('product-height', dataProductHeight);
    $popupCont.find(selectors.input).val('');
    $popupCont.find(selectors.popupChooseInnerContWrap).val('');
    if ($this.closest(selectors.popupChooseProduct).hasClass('added-product')) {
      $popupCont.find('.yith-ywraq-add-button').removeClass('choose_show').addClass('choose_hide');
    } else {
      $popupCont.find('.yith-ywraq-add-button').removeClass('choose_hide').addClass('choose_show');
    }
    $popupCont.find(selectors.popupChooseInnerContWrap).removeClass('show');
    $popupCont.find(`${selectors.popupChooseInnerContWrap}[product-id="${productID}"]`).addClass('show');
  }
}
exports["default"] = ThemeBlogSlider;

/***/ })

}]);
//# sourceMappingURL=theme-blog-slider.85950500293a10375808.bundle.js.map