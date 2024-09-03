/*! cmsmasters-elementor-addon - v1.11.6 - 05-03-2024 */
(self["webpackChunkcmsmasters_elementor_addon"] = self["webpackChunkcmsmasters_elementor_addon"] || []).push([["frontend"],{

/***/ "../assets/dev/js/frontend/base/module.js":
/*!************************************************!*\
  !*** ../assets/dev/js/frontend/base/module.js ***!
  \************************************************/
/***/ ((__unused_webpack_module, exports) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
class _default extends elementorModules.ViewModule {
  __construct(settings) {
    super.__construct(settings);
    this.widgets = {};
    this.handlers = {};
  }
  onInit() {
    super.onInit(...arguments);
    this.addHandlers();
    this.initHandlers();
  }
  addHandlers() {
    this.widgets = this.initWidgets();
    jQuery.each(this.widgets, (widgetName, funcCallback) => {
      const widgetNameArray = widgetName.split('.');
      let skin = 'default';
      if (1 < widgetNameArray.length) {
        widgetName = widgetNameArray[0];
        skin = widgetNameArray[1];
      }
      this.addHandler(widgetName, funcCallback, skin);
    });
  }
  initWidgets() {
    console.error('Please add at least one widget with `initWidgets` class method!!!'); // eslint-disable-line no-console

    return {};
  }
  addHandler(widgetName, funcCallback, skin) {
    const elementName = `cmsmasters-${widgetName}.${skin}`;
    this.handlers[elementName] = funcCallback;
  }
  initHandlers() {
    jQuery.each(this.handlers, (elementName, funcCallback) => {
      elementorFrontend.hooks.addAction(`frontend/element_ready/${elementName}`, $element => {
        const intersectionObserver = new IntersectionObserver((elements, observer) => {
          elements.forEach(async element => {
            if (element.isIntersecting) {
              if (!elementorFrontend.isEditMode() && 0 < $element.find('.cmsmasters-lazyload-widget-settings').length) {
                await this.lazyLoadWidget_Render($element);
              }
              const handlerValue = funcCallback();
              if (!handlerValue) {
                return;
              }
              if (handlerValue instanceof Promise) {
                handlerValue.then(_ref => {
                  let {
                    default: dynamicHandler
                  } = _ref;
                  elementorFrontend.elementsHandler.addHandler(dynamicHandler, {
                    $element
                  }, true);
                });
              } else {
                elementorFrontend.elementsHandler.addHandler(handlerValue, {
                  $element
                }, true);
              }
              observer.unobserve(element.target);
            }
          });
        }, {
          rootMargin: '1000px 0px 1000px 0px'
        });
        intersectionObserver.observe($element.get(0));
      });
    });
  }
  async lazyLoadWidget_Render($element) {
    return new Promise((resolve, reject) => {
      const parameters = this.lazyLoadWidget_GetAjaxParameters($element);
      let multisiteCurrentBlogIdPrefix = '';
      if ('' !== elementorCmsmastersFrontendConfig.multisite_current_blog_id) {
        multisiteCurrentBlogIdPrefix = elementorCmsmastersFrontendConfig.multisite_current_blog_id + '-';
      }
      const cachedDataKey = 'cmsmasters-cached-widget-' + multisiteCurrentBlogIdPrefix + $element.data().id;
      const cachedDataString = localStorage.getItem(cachedDataKey);
      let cachedDataObj = false;
      if (cachedDataString) {
        cachedDataObj = JSON.parse(cachedDataString);
        this.lazyLoadWidget_PasteContent($element, cachedDataObj);
      }
      const jqXHR = jQuery.ajax(parameters);
      jqXHR.done(response => {
        if (response && response.success) {
          const responseDataString = JSON.stringify(response.data);
          if (!cachedDataString) {
            this.lazyLoadWidget_PasteContent($element, response.data);
            localStorage.setItem(cachedDataKey, responseDataString);
          } else if (cachedDataString !== responseDataString) {
            this.lazyLoadWidget_PasteContent($element, response.data, cachedDataObj);
            localStorage.setItem(cachedDataKey, responseDataString);
          }
          resolve();
        } else {
          reject(jqXHR);
        }
      });
      jqXHR.fail(() => {
        reject(jqXHR);
      });
    });
  }
  lazyLoadWidget_GetAjaxParameters($element) {
    let documentId = $element.data().documentId;
    if (!documentId) {
      documentId = elementorFrontendConfig.post.id;
    }
    if (!documentId) {
      documentId = $element.parents('.elementor[data-elementor-id]').data('elementor-id');
    }
    const widgetId = $element.data('id');
    const currentSettings = $element.find('.cmsmasters-lazyload-widget-settings').data('settings');
    let widgetType = $element.data('widget_type');
    if (!widgetType) {
      widgetType = '';
    } else {
      widgetType = widgetType.split('.')[0];
    }
    const parameters = {
      url: elementorCmsmastersFrontendConfig.ajaxurl,
      type: 'POST',
      dataType: 'json',
      data: {
        _ajax_nonce: elementorCmsmastersFrontendConfig.nonces.ajax_widget,
        action: 'ajax_widget_lazyload_widget_ajax_render_content',
        document_id: documentId,
        widget_id: widgetId,
        element_data: {
          id: widgetId,
          elType: $element.data('element_type'),
          widgetType: widgetType,
          elements: [],
          isInner: false,
          settings: currentSettings
        }
      }
    };
    return parameters;
  }
  lazyLoadWidget_PasteContent($element, newContent) {
    let oldContent = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
    let elementToReplace = '.cmsmasters-lazyload-widget-settings';
    if (false !== oldContent) {
      elementToReplace = '.' + jQuery(oldContent).attr('class').split(' ').join('.');
    }
    $element.find(elementToReplace).replaceWith(newContent);
    $element.find('.elementor-element').each((index, element) => {
      elementorFrontend.elementsHandler.runReadyTrigger(element);
    });
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../assets/dev/js/frontend/frontend.js":
/*!*********************************************!*\
  !*** ../assets/dev/js/frontend/frontend.js ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
var _frontend = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/template-pages/assets/dev/js/frontend/frontend */ "../modules/template-pages/assets/dev/js/frontend/frontend.js"));
var _frontend2 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/template-sections/assets/dev/js/frontend/frontend */ "../modules/template-sections/assets/dev/js/frontend/frontend.js"));
var _frontend3 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-slider-module/frontend/frontend */ "../modules/slider/assets/dev/js/frontend/frontend.js"));
var _frontend4 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/meta-data/assets/dev/js/frontend/frontend */ "../modules/meta-data/assets/dev/js/frontend/frontend.js"));
var _frontend5 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-blog-module/frontend/frontend */ "../modules/blog/assets/dev/js/frontend/frontend.js"));
var _frontend6 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/effects/assets/dev/js/frontend/frontend */ "../modules/effects/assets/dev/js/frontend/frontend.js"));
var _frontend7 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/sticky/assets/dev/js/frontend/frontend */ "../modules/sticky/assets/dev/js/frontend/frontend.js"));
var _popup = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/popup/assets/dev/js/frontend/popup */ "../modules/popup/assets/dev/js/frontend/popup.js"));
var _frontend8 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/popup/assets/dev/js/frontend/frontend */ "../modules/popup/assets/dev/js/frontend/frontend.js"));
var _giveWpFormsScrollbar = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/give-wp/assets/dev/js/frontend/give-wp-forms-scrollbar */ "../modules/give-wp/assets/dev/js/frontend/give-wp-forms-scrollbar.js"));
var _frontend9 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/social/assets/dev/js/frontend/frontend */ "../modules/social/assets/dev/js/frontend/frontend.js"));
var _frontend10 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/social-counter/assets/dev/js/frontend/frontend */ "../modules/social-counter/assets/dev/js/frontend/frontend.js"));
var _frontend11 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/share-buttons/assets/dev/js/frontend/frontend */ "../modules/share-buttons/assets/dev/js/frontend/frontend.js"));
var _frontend12 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/table-of-contents/assets/dev/js/frontend/frontend */ "../modules/table-of-contents/assets/dev/js/frontend/frontend.js"));
var _frontend13 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-tabs-module/frontend/frontend */ "../modules/tabs/assets/dev/js/frontend/frontend.js"));
var _frontend14 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/toggles/assets/dev/js/frontend/frontend */ "../modules/toggles/assets/dev/js/frontend/frontend.js"));
var _frontend15 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/library-template/assets/dev/js/frontend/frontend */ "../modules/library-template/assets/dev/js/frontend/frontend.js"));
var _frontend16 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/mailchimp/assets/dev/js/frontend/frontend */ "../modules/mailchimp/assets/dev/js/frontend/frontend.js"));
var _frontend17 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/media/assets/dev/js/frontend/frontend */ "../modules/media/assets/dev/js/frontend/frontend.js"));
var _frontend18 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/google-maps/assets/dev/js/frontend/frontend */ "../modules/google-maps/assets/dev/js/frontend/frontend.js"));
var _frontend19 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/gallery/assets/dev/js/frontend/frontend */ "../modules/gallery/assets/dev/js/frontend/frontend.js"));
var _frontend20 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/image-scroll/assets/dev/js/frontend/frontend */ "../modules/image-scroll/assets/dev/js/frontend/frontend.js"));
var _frontend21 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/infinite-scroll/assets/dev/js/frontend/frontend */ "../modules/infinite-scroll/assets/dev/js/frontend/frontend.js"));
var _frontend22 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/instagram/assets/dev/js/frontend/frontend */ "../modules/instagram/assets/dev/js/frontend/frontend.js"));
var _frontend23 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/animated-text/assets/dev/js/frontend/frontend */ "../modules/animated-text/assets/dev/js/frontend/frontend.js"));
var _frontend24 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/contact-form/assets/dev/js/frontend/frontend */ "../modules/contact-form/assets/dev/js/frontend/frontend.js"));
var _frontend25 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/testimonials/assets/dev/js/frontend/frontend */ "../modules/testimonials/assets/dev/js/frontend/frontend.js"));
var _frontend26 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/timetable/assets/dev/js/frontend/frontend */ "../modules/timetable/assets/dev/js/frontend/frontend.js"));
var _frontend27 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/before-after/assets/dev/js/frontend/frontend */ "../modules/before-after/assets/dev/js/frontend/frontend.js"));
var _frontend28 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/progress-tracker/assets/dev/js/frontend/frontend */ "../modules/progress-tracker/assets/dev/js/frontend/frontend.js"));
var _frontend29 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/countdown/assets/dev/js/frontend/frontend */ "../modules/countdown/assets/dev/js/frontend/frontend.js"));
var _frontend30 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/mode-switcher/assets/dev/js/frontend/frontend */ "../modules/mode-switcher/assets/dev/js/frontend/frontend.js"));
var _frontend31 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/hotspot/assets/dev/js/frontend/frontend */ "../modules/hotspot/assets/dev/js/frontend/frontend.js"));
var _frontend32 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/weather/assets/dev/js/frontend/frontend */ "../modules/weather/assets/dev/js/frontend/frontend.js"));
var _frontend33 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/woocommerce/assets/dev/js/frontend/frontend */ "../modules/woocommerce/assets/dev/js/frontend/frontend.js"));
var _frontend34 = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/theme/assets/dev/js/frontend/frontend */ "../modules/theme/assets/dev/js/frontend/frontend.js"));
/**
 * Frontend scripts module handlers.
 *
 * @since 1.0.0
 * @default
 */
const moduleHandlers = {
  sticky: _frontend7.default,
  effects: _frontend6.default,
  templatePages: _frontend.default,
  templateSections: _frontend2.default,
  popup: _popup.default,
  giveWpFormsScrollbar: _giveWpFormsScrollbar.default,
  slider: _frontend3.default,
  theme: _frontend34.default,
  metaData: _frontend4.default,
  blog: _frontend5.default,
  social: _frontend9.default,
  socialCounter: _frontend10.default,
  shareButtons: _frontend11.default,
  tableOfContents: _frontend12.default,
  tabs: _frontend13.default,
  toggles: _frontend14.default,
  template: _frontend15.default,
  timePopup: _frontend8.default,
  mailchimp: _frontend16.default,
  media: _frontend17.default,
  googleMaps: _frontend18.default,
  gallery: _frontend19.default,
  infiniteScroll: _frontend21.default,
  imageScroll: _frontend20.default,
  instagram: _frontend22.default,
  animatedText: _frontend23.default,
  cmsForminator: _frontend24.default,
  TestimonialsSlider: _frontend25.default,
  timetable: _frontend26.default,
  beforeAfter: _frontend27.default,
  progressTracker: _frontend28.default,
  Countdown: _frontend29.default,
  modeSwitcher: _frontend30.default,
  hotspot: _frontend31.default,
  weather: _frontend32.default,
  wooCommerce: _frontend33.default
};
class CmsmastersElementorFrontend extends elementorModules.ViewModule {
  /**
   * Frontend script.
   *
   * Constructs main `Frontend` script that is responsible for
   * frontend elementor modules scripts.
   *
   * @since 1.0.0
   *
   * @augments `elementorModules.ViewModule`
   *
   * @fires `bindEvents`
   *
   * @param {...*} args Elementor `View` module arguments.
   */
  constructor() {
    super(...arguments);

    /**
     * Frontend script config.
     *
     * @since 1.0.0
     * @default
     */
    this.config = elementorCmsmastersFrontendConfig;

    /**
     * Frontend modules.
     *
     * @since 1.0.0
     */
    this.modules = {};
  }

  /**
   * Adds event listeners for frontend-related events.
   *
   * @since 1.0.0
   */
  bindEvents() {
    jQuery(window).on('elementor/frontend/init', this.onElementorFrontendInit.bind(this));
  }

  /**
   * Initialize class methods on frontend init event.
   *
   * @since 1.0.0
   * @listens `elementor/frontend/init`
   */
  onElementorFrontendInit() {
    this.initModules();
    this.goToElementorElement();
  }

  /**
   * Initialize frontend modules.
   *
   * @since 1.0.0
   */
  initModules() {
    jQuery.each(moduleHandlers, (handlerName, module) => {
      this.modules[handlerName] = new module();
    });
  }

  /**
   * Go to element on elementor template document preview,
   * if the page URL is something like this -
   * http://domain.com/any-page?cmsmasters_template_id=6479&preview=true
   *
   * @since 1.0.0
   */
  goToElementorElement() {
    jQuery(() => {
      const match = location.search.match(/cmsmasters_template_id=(\d*)/);
      const $element = match ? jQuery('.elementor-' + match[1]) : [];
      if ($element.length) {
        const scrollValue = $element.offset().top - window.innerHeight / 2;
        jQuery('html, body').animate({
          scrollTop: scrollValue
        });
      }
    });
  }

  /**
   * Translate frontend strings and replace specifiers with arguments.
   *
   * @since 1.0.0
   *
   * @param {string} stringKey Translatable string key.
   * @param {string[]} templateArgs Translatable string arguments.
   *
   * @return {string} Translated string.
   */
  translate(stringKey, templateArgs) {
    return elementorCommon.translate(stringKey, null, templateArgs, this.config.i18n);
  }
}

/**
 * @name cmsmastersElementorFrontend
 * @global
 */
window.cmsmastersElementorFrontend = new CmsmastersElementorFrontend();

/***/ }),

/***/ "../assets/dev/js/helpers/utils.js":
/*!*****************************************!*\
  !*** ../assets/dev/js/helpers/utils.js ***!
  \*****************************************/
/***/ ((module) => {

"use strict";


module.exports = {
  debounce(callback) {
    let timeout = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 100;
    let timer = null;
    return function () {
      for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
        args[_key] = arguments[_key];
      }
      const onComplete = () => {
        callback.apply(this, args);
        timer = null;
      };
      if (timer) {
        clearTimeout(timer);
      }
      timer = setTimeout(onComplete, timeout);
    };
  },
  throttle(callback) {
    let timeout = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 500;
    let isThrottled = false,
      savedArgs,
      savedThis;
    function wrapper() {
      if (isThrottled) {
        savedArgs = arguments;
        savedThis = this;
        return;
      }
      callback.apply(this, arguments);
      isThrottled = true;
      setTimeout(() => {
        isThrottled = false;
        if (savedArgs) {
          wrapper.apply(savedThis, savedArgs);
          savedArgs = savedThis = null;
        }
      }, timeout);
    }
    return wrapper;
  },
  saveParameters(parametersObject) {
    if (elementorFrontend.isEditMode()) {
      return;
    }
    if (!parametersObject || !Object.keys(parametersObject).length) {
      return;
    }
    const locationParameters = new URLSearchParams(location.search);
    for (const parameterName in parametersObject) {
      const parameterValue = parametersObject[parameterName];
      if (parameterValue) {
        locationParameters.set(parameterName, parameterValue);
      } else {
        locationParameters.delete(parameterName);
      }
    }
    const parameters = locationParameters.toString();
    if (parameters) {
      history.replaceState({}, '', `?${parameters}`);
    } else {
      history.replaceState({}, '', location.pathname);
    }
  }
};

/***/ }),

/***/ "../modules/animated-text/assets/dev/js/frontend/frontend.js":
/*!*******************************************************************!*\
  !*** ../modules/animated-text/assets/dev/js/frontend/frontend.js ***!
  \*******************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      'animated-text': () => __webpack_require__.e(/*! import() | animated-text */ "animated-text").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/animated-text */ "../modules/animated-text/assets/dev/js/frontend/handlers/animated-text.js")),
      'fancy-text': () => __webpack_require__.e(/*! import() | fancy-text */ "fancy-text").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/fancy-text */ "../modules/animated-text/assets/dev/js/frontend/handlers/fancy-text.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/before-after/assets/dev/js/frontend/frontend.js":
/*!******************************************************************!*\
  !*** ../modules/before-after/assets/dev/js/frontend/frontend.js ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      'before-after': () => __webpack_require__.e(/*! import() | before-after */ "before-after").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/before-after */ "../modules/before-after/assets/dev/js/frontend/handlers/before-after.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/blog/assets/dev/js/frontend/document.js":
/*!**********************************************************!*\
  !*** ../modules/blog/assets/dev/js/frontend/document.js ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, exports) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
class _default extends elementorModules.frontend.Document {
  getDefaultElements() {
    const elements = super.getDefaultElements();
    elements.$parent = this.$element.parent();
    return elements;
  }
  onInit() {
    super.onInit();
    if (!elementorFrontend.isEditMode()) {
      return;
    }
    this.initModal();
  }
  getElementMessage() {
    return this.$element;
  }
  async initModal() {
    if (this.modal) {
      return;
    }
    const documentSettings = this.getDocumentSettings();
    if (!Object.keys(documentSettings).length) {
      return;
    }
    const {
      id
    } = this.getSettings();
    let className = 'cmsmasters-widget-template-modal';
    if (documentSettings.classes) {
      className += ` ${documentSettings.classes}`;
    }
    if (!window.DialogsManager) {
      await elementorFrontend.utils.assetsLoader.load('script', 'dialog');
    }
    this.modal = elementorFrontend.getDialogsManager().createWidget('lightbox', {
      id: `cmsmasters-widget-template-${id}`,
      className,
      closeButton: false,
      hide: false,
      position: {
        enable: false
      }
    });
    this.modal.setMessage(this.getElementMessage()).show();
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/blog/assets/dev/js/frontend/frontend.js":
/*!**********************************************************!*\
  !*** ../modules/blog/assets/dev/js/frontend/frontend.js ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
var _document = _interopRequireDefault(__webpack_require__(/*! ./document */ "../modules/blog/assets/dev/js/frontend/document.js"));
class _default extends _module.default {
  onInit() {
    super.onInit();
    elementorFrontend.hooks.addAction('elementor/frontend/documents-manager/init-classes', this.addDocumentClass);
  }

  /**
   * @since 1.0.3 Added 'archive-posts' widget
   */
  initWidgets() {
    const widgets = {
      'archive-posts': () => Promise.all(/*! import() | blog-grid */[__webpack_require__.e("assets_dev_js_frontend_base_handler_js-modules_ajax-widget_assets_dev_js_frontend_ajax-widget-86c5d3"), __webpack_require__.e("assets_dev_js_frontend_modules_document-handles_js-modules_blog_assets_dev_js_frontend_widget-10a6f5"), __webpack_require__.e("modules_blog_assets_dev_js_frontend_widgets_blog_base_base-blog-elements_js"), __webpack_require__.e("blog-grid")]).then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/blog/grid */ "../modules/blog/assets/dev/js/frontend/widgets/blog/grid.js")),
      'blog-grid': () => Promise.all(/*! import() | blog-grid */[__webpack_require__.e("assets_dev_js_frontend_base_handler_js-modules_ajax-widget_assets_dev_js_frontend_ajax-widget-86c5d3"), __webpack_require__.e("assets_dev_js_frontend_modules_document-handles_js-modules_blog_assets_dev_js_frontend_widget-10a6f5"), __webpack_require__.e("modules_blog_assets_dev_js_frontend_widgets_blog_base_base-blog-elements_js"), __webpack_require__.e("blog-grid")]).then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/blog/grid */ "../modules/blog/assets/dev/js/frontend/widgets/blog/grid.js")),
      'theme-blog-grid': () => Promise.all(/*! import() | theme-blog-grid */[__webpack_require__.e("assets_dev_js_frontend_base_handler_js-modules_ajax-widget_assets_dev_js_frontend_ajax-widget-86c5d3"), __webpack_require__.e("theme-blog-grid")]).then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/blog/theme-blog-grid */ "../modules/blog/assets/dev/js/frontend/widgets/blog/theme-blog-grid.js")),
      'theme-blog-slider': () => Promise.all(/*! import() | theme-blog-slider */[__webpack_require__.e("assets_dev_js_frontend_base_handler_js-modules_slider_assets_dev_js_frontend_slider_js"), __webpack_require__.e("assets_dev_js_frontend_modules_document-handles_js-modules_blog_assets_dev_js_frontend_widget-10a6f5"), __webpack_require__.e("theme-blog-slider")]).then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/blog/theme-blog-slider */ "../modules/blog/assets/dev/js/frontend/widgets/blog/theme-blog-slider.js")),
      'blog-featured': () => Promise.all(/*! import() | blog-featured */[__webpack_require__.e("assets_dev_js_frontend_base_handler_js-modules_ajax-widget_assets_dev_js_frontend_ajax-widget-86c5d3"), __webpack_require__.e("assets_dev_js_frontend_modules_document-handles_js-modules_blog_assets_dev_js_frontend_widget-10a6f5"), __webpack_require__.e("modules_blog_assets_dev_js_frontend_widgets_blog_base_base-blog-elements_js"), __webpack_require__.e("blog-featured")]).then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/blog/featured */ "../modules/blog/assets/dev/js/frontend/widgets/blog/featured.js")),
      'blog-slider': () => Promise.all(/*! import() | blog-slider */[__webpack_require__.e("assets_dev_js_frontend_base_handler_js-modules_slider_assets_dev_js_frontend_slider_js"), __webpack_require__.e("assets_dev_js_frontend_modules_document-handles_js-modules_blog_assets_dev_js_frontend_widget-10a6f5"), __webpack_require__.e("blog-slider")]).then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/blog/slider */ "../modules/blog/assets/dev/js/frontend/widgets/blog/slider.js")),
      'ticker.slider': () => __webpack_require__.e(/*! import() | ticker-slider */ "ticker-slider").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/ticker/slider */ "../modules/blog/assets/dev/js/frontend/widgets/ticker/slider.js"))
    };
    return widgets;
  }
  addDocumentClass(documentsManager) {
    documentsManager.addDocumentClass('cmsmasters_entry', _document.default);
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/contact-form/assets/dev/js/frontend/frontend.js":
/*!******************************************************************!*\
  !*** ../modules/contact-form/assets/dev/js/frontend/frontend.js ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      forminator: () => __webpack_require__.e(/*! import() | cms-forminator */ "cms-forminator").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/cms-forminator */ "../modules/contact-form/assets/dev/js/frontend/handlers/cms-forminator.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/countdown/assets/dev/js/frontend/frontend.js":
/*!***************************************************************!*\
  !*** ../modules/countdown/assets/dev/js/frontend/frontend.js ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      countdown: () => __webpack_require__.e(/*! import() | countdown */ "countdown").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/countdown */ "../modules/countdown/assets/dev/js/frontend/handlers/countdown.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/effects/assets/dev/js/frontend/frontend.js":
/*!*************************************************************!*\
  !*** ../modules/effects/assets/dev/js/frontend/frontend.js ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _scroll = _interopRequireDefault(__webpack_require__(/*! ./modules/scroll */ "../modules/effects/assets/dev/js/frontend/modules/scroll.js"));
var _tilt = _interopRequireDefault(__webpack_require__(/*! ./modules/tilt */ "../modules/effects/assets/dev/js/frontend/modules/tilt.js"));
var _floating = _interopRequireDefault(__webpack_require__(/*! ./modules/floating */ "../modules/effects/assets/dev/js/frontend/modules/floating.js"));
var _transform = _interopRequireDefault(__webpack_require__(/*! ./modules/transform */ "../modules/effects/assets/dev/js/frontend/modules/transform.js"));
class _default extends elementorModules.ViewModule {
  onInit() {
    super.onInit(...arguments);
    this.addHandlers();
  }
  addHandlers() {
    const handlers = [_scroll.default, _tilt.default, _floating.default, _transform.default];
    handlers.forEach(handler => {
      elementorFrontend.hooks.addAction('frontend/element_ready/global', handler);
    });
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/effects/assets/dev/js/frontend/modules/floating.js":
/*!*********************************************************************!*\
  !*** ../modules/effects/assets/dev/js/frontend/modules/floating.js ***!
  \*********************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
const utils = __webpack_require__(/*! cmsmasters-helpers/utils */ "../assets/dev/js/helpers/utils.js");
class Floating extends elementorModules.frontend.handlers.Base {
  __construct(settings) {
    super.__construct(settings);
    this.config = {};
    this.animation = undefined;
    this.effectElementType = this.$element.data('element_type');
    this.effectContainer = undefined;
    const elementorExperiments = elementorFrontend.config.experimentalFeatures;
    this.columnClassElement = true === elementorExperiments.e_dom_optimization ? 'widget' : 'column';
    this.changeControls = ['background_background'];
    const changeControls = ['cms_effect_type',
    // translate
    'cms_floating_translate_toggle', 'cms_floating_translate_x', 'cms_floating_translate_y', 'cms_floating_translate_delay',
    // rotate
    'cms_floating_rotate_toggle', 'cms_floating_rotate_type', 'cms_floating_rotate', 'cms_floating_rotate_x', 'cms_floating_rotate_y', 'cms_floating_rotate_z', 'cms_floating_rotate_delay',
    // scale
    'cms_floating_scale_toggle', 'cms_floating_scale_type', 'cms_floating_scale', 'cms_floating_scale_x', 'cms_floating_scale_y', 'cms_floating_scale_delay', 'cms_floating_duration'];
    changeControls.forEach(control => {
      this.changeControls.push(control);
      this.changeControls.push(control.replace('cms_', 'cms_bg_'));
    });
  }
  getDefaultSettings() {
    const bgClass = 'cmsmasters-bg';
    const bgEffect = 'cmsmasters-bg-effect';
    const classes = {
      floating: 'cmsmasters-effect-type-floating',
      bgEffect: bgEffect,
      bgFloating: `${bgEffect}-type-floating`,
      bgContainer: `${bgClass}-effects-container`,
      bgElement: `${bgClass}-effects-element`
    };
    return {
      classes
    };
  }

  // @since 1.6.3 Fixed loading the effect on iphone.
  onInit() {
    setTimeout(() => {
      this.initFloatingEffects();
    }, 500);
  }
  onElementChange(propertyName) {
    if (-1 !== this.changeControls.indexOf(propertyName)) {
      this.deactivateFloating();
      utils.debounce(this.initFloatingEffects(), 200);
    }
  }
  initFloatingEffects() {
    const {
      floating,
      bgFloating
    } = this.getSettings('classes');
    if (!this.$element.hasClass(floating) && !this.$element.hasClass(bgFloating)) {
      return;
    }
    this.setEffectContainer();
    this.config = {
      targets: this.effectContainer,
      loop: true,
      direction: 'alternate',
      easing: 'easeInOutSine'
    };
    const {
      translate_toggle: translateToggle,
      rotate_toggle: rotateToggle,
      scale_toggle: scaleToggle,
      duration
    } = this.getFloatingSettings();
    if (!translateToggle && !rotateToggle && !scaleToggle) {
      return;
    }
    this.initTranslateFloating();
    if (!this.$element.hasClass(bgFloating)) {
      this.initRotateFloating();
    }
    this.initScaleFloating();
    this.config.duration = duration.size * 1000;
    this.animation = anime(this.config);
  }
  setEffectContainer() {
    const {
      bgFloating,
      bgContainer,
      bgElement
    } = this.getSettings('classes');
    let elementClass;
    switch (this.effectElementType) {
      case 'widget':
        let $exclusion = '';
        const $exclusionWidgets = ['elementor-widget-cmsmasters-offcanvas', 'cmsmasters-search-type-search-popup'];
        for (var i = 0; i < $exclusionWidgets.length; i++) {
          if (this.$element.hasClass($exclusionWidgets[i])) {
            $exclusion = $exclusionWidgets[i];
            break;
          }
        }
        if (this.$element.hasClass($exclusion)) {
          if ('elementor-widget-cmsmasters-offcanvas' === $exclusion) {
            elementClass = this.$element.find('.elementor-widget-cmsmasters-offcanvas__trigger');
          } else if ('cmsmasters-search-type-search-popup' === $exclusion) {
            elementClass = this.$element.find('.elementor-widget-cmsmasters-search__popup-trigger-inner');
          }
        } else {
          elementClass = this.$element.find('.elementor-widget-container');
        }
        break;
      case 'column':
        elementClass = this.$element.find(`.elementor-${this.columnClassElement}-wrap`);
        break;
      case 'section':
        elementClass = this.$element.find('.elementor-container');
        break;
      case 'container':
        elementClass = this.$element;
        break;
    }
    let $effectContainer = elementClass;
    if (this.$element.hasClass(bgFloating)) {
      const $container = jQuery('<div>', {
        class: bgContainer
      });
      jQuery('<div>', {
        class: bgElement
      }).appendTo($container);
      if ('section' === this.effectElementType || 'container' === this.effectElementType) {
        $effectContainer = this.$element;
      }
      let $effectContainerParent = $effectContainer.find(`> .${bgContainer}`);
      if (!$effectContainerParent.length) {
        $effectContainer.prepend($container);
      } else {
        $container.insertBefore($effectContainer);
      }
      $effectContainerParent = $effectContainer.find(`> .${bgContainer}`).last();
      $effectContainer = $effectContainerParent.find(`.${bgElement}`);
    }
    this.effectContainer = $effectContainer.get(0);
  }
  getFloatingSettings() {
    let name = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
    const {
      bgFloating
    } = this.getSettings('classes');
    const elementSettings = super.getElementSettings();
    let suffix = '';
    if (this.$element.hasClass(bgFloating)) {
      suffix = 'bg_';
    }
    const prefix = `cms_${suffix}floating_`;
    const settings = {};
    for (const [settingName, settingValue] of Object.entries(elementSettings)) {
      if (-1 === settingName.indexOf(prefix)) {
        continue;
      }
      const settingKey = settingName.replace(prefix, '');
      settings[settingKey] = settingValue;
    }
    if ('' !== name) {
      return settings[name] ? settings[name] : '';
    }
    return settings;
  }
  initTranslateFloating() {
    const {
      translate_toggle: translateToggle,
      translate_delay: translateDelay,
      translate_x: translateX,
      translate_y: translateY
    } = this.getFloatingSettings();
    if (!translateToggle) {
      return;
    }
    const {
      bgFloating
    } = this.getSettings('classes');
    if (this.$element.hasClass(bgFloating)) {
      this.checkBgContainerSize();
    }
    this.setConfigAttr('translateX', translateX, translateDelay);
    this.setConfigAttr('translateY', translateY, translateDelay);
  }
  checkBgContainerSize() {
    const containerParameters = {
      x: {
        size: 'width',
        position: 'left'
      },
      y: {
        size: 'height',
        position: 'top'
      }
    };
    Object.keys(containerParameters).forEach(key => {
      const setting = this.getFloatingSettings(`translate_${key}`);
      if (!setting.size && !setting.sizes.to) {
        return;
      }
      const properties = containerParameters[key];
      const from = setting.sizes.from || 0;
      const to = setting.size || setting.sizes.to;
      let size = 100;
      let position = -to;
      if (0 <= from) {
        size += Math.max(from, to);
      } else if (0 > to) {
        size += Math.abs(Math.min(from, to));
        position = 0;
      } else {
        size += Math.abs(from) + to;
      }
      this.effectContainer.style.setProperty(properties.size, `${size}%`);
      this.effectContainer.style.setProperty(properties.position, `${position}%`);
    });
  }
  initRotateFloating() {
    const {
      rotate_toggle: rotateToggle,
      rotate_type: rotateType,
      rotate_delay: rotateDelay,
      rotate,
      rotate_x: rotateX,
      rotate_y: rotateY,
      rotate_z: rotateZ
    } = this.getFloatingSettings();
    if (!rotateToggle) {
      return;
    }
    if ('simple' === rotateType) {
      this.setConfigAttr('rotate', rotate, rotateDelay);
      return;
    }
    this.setConfigAttr('rotateX', rotateX, rotateDelay);
    this.setConfigAttr('rotateY', rotateY, rotateDelay);
    this.setConfigAttr('rotateZ', rotateZ, rotateDelay);
  }
  initScaleFloating() {
    const {
      scale_toggle: scaleToggle,
      scale_type: scaleType,
      scale_delay: scaleDelay,
      scale,
      scale_x: scaleX,
      scale_y: scaleY
    } = this.getFloatingSettings();
    if (!scaleToggle) {
      return;
    }
    if ('simple' === scaleType) {
      this.setConfigAttr('scale', scale, scaleDelay);
      return;
    }
    this.setConfigAttr('scaleX', scaleX, scaleDelay);
    this.setConfigAttr('scaleY', scaleY, scaleDelay);
  }
  setConfigAttr(attr, param, delay) {
    if (!param.size && !param.sizes.to) {
      return;
    }
    const {
      bgFloating
    } = this.getSettings('classes');
    const parameter = attr.replace(/([A-Z])/g, '');
    let from = param.sizes.from || 0;
    let to = param.size || param.sizes.to;
    if ('translate' === parameter && this.$element.hasClass(bgFloating)) {
      from = from / 2;
      to = to / 2;
    }
    const unit = this.getAttrUnit(parameter);
    this.config[attr] = {
      value: [`${from}${unit}`, `${to}${unit}`],
      delay: delay.size * 1000 || 0
    };
  }
  getAttrUnit(attr) {
    const {
      bgFloating
    } = this.getSettings('classes');
    let unit = '';
    switch (attr) {
      case 'translate':
        unit = this.$element.hasClass(bgFloating) ? '%' : 'px';
        break;
      case 'rotate':
        unit = 'deg';
        break;
    }
    return unit;
  }
  onDestroy() {
    this.deactivateFloating();
  }
  deactivateFloating() {
    const {
      bgElement
    } = this.getSettings('classes');
    if (jQuery(this.effectContainer).hasClass(bgElement)) {
      jQuery(this.effectContainer).parent().remove();
    }
    if (this.animation) {
      this.animation.reset();
      this.animation.remove(this.effectContainer);
      delete this.animation;
      this.config = {};
      jQuery(this.effectContainer).removeAttr('style');
    }
  }
}
var _default = $scope => {
  elementorFrontend.elementsHandler.addHandler(Floating, {
    $element: $scope
  });
};
exports["default"] = _default;

/***/ }),

/***/ "../modules/effects/assets/dev/js/frontend/modules/scroll.js":
/*!*******************************************************************!*\
  !*** ../modules/effects/assets/dev/js/frontend/modules/scroll.js ***!
  \*******************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
const utils = __webpack_require__(/*! cmsmasters-helpers/utils */ "../assets/dev/js/helpers/utils.js");
class Scroll extends elementorModules.frontend.handlers.Base {
  __construct(settings) {
    super.__construct(settings);
    this.effectsVars = {
      vertical: '--cmsmasters-scroll-vertical',
      horizontal: '--cmsmasters-scroll-horizontal',
      rotate: '--cmsmasters-scroll-rotate',
      scale: '--cmsmasters-scroll-scale',
      opacity: '--cmsmasters-scroll-opacity',
      blur: '--cmsmasters-scroll-blur',
      grayscale: '--cmsmasters-scroll-grayscale',
      sepia: '--cmsmasters-scroll-sepia',
      saturate: '--cmsmasters-scroll-saturate',
      brightness: '--cmsmasters-scroll-brightness',
      contrast: '--cmsmasters-scroll-contrast',
      huerotate: '--cmsmasters-scroll-hue-rotate'
    };
    this.originVars = {
      x: '--cmsmasters-scroll-origin-x',
      y: '--cmsmasters-scroll-origin-y'
    };
    this.config = {};
    this.scroll = {};
    this.effectElementType = this.$element.data('element_type');
    this.effectContainer = undefined;
    const elementorExperiments = elementorFrontend.config.experimentalFeatures;
    this.columnClassElement = true === elementorExperiments.e_dom_optimization ? 'widget' : 'column';
    this.bgElementSizes = {};
    this.bodyPrevHeight = 0;
    this.scrollRefresh = utils.throttle(this.scrollRefresh, 10);
    this.changeControls = ['background_background'];
    const changeControls = ['cms_effect_type'];
    changeControls.forEach(control => {
      this.changeControls.push(control);
      this.changeControls.push(control.replace('cms_', 'cms_bg_'));
    });
  }
  getDefaultSettings() {
    const bgClass = 'cmsmasters-bg';
    const bgEffect = 'cmsmasters-bg-effect';
    const classes = {
      scroll: 'cmsmasters-effect-type-scroll',
      bgEffect: bgEffect,
      bgScroll: `${bgEffect}-type-scroll`,
      bgContainer: `${bgClass}-effects-container`,
      bgElement: `${bgClass}-effects-element`
    };
    return {
      classes
    };
  }
  onInit() {
    const {
      classes
    } = this.getSettings();
    if (!this.$element.hasClass(classes.scroll) && !this.$element.hasClass(classes.bgScroll)) {
      return;
    }
    setTimeout(() => {
      this.initScroll();
      this.setScrollDevices();
      if (window.ResizeObserver) {
        const $body = elementorFrontend.elements.$body;
        this.bodyPrevHeight = $body.height();
        const resizeObserver = new ResizeObserver(entries => {
          const currentHeight = entries[0].target.clientHeight;
          const difference = Math.abs(currentHeight - this.bodyPrevHeight);
          if (50 < difference) {
            this.scrollRefresh();
            this.bodyPrevHeight = currentHeight;
          }
        });
        resizeObserver.observe($body.get(0));
      }
    }, 500);
  }
  onElementChange(propertyName) {
    if (-1 !== this.changeControls.indexOf(propertyName)) {
      this.scrollDeactivate();
      const {
        classes
      } = this.getSettings();
      setTimeout(() => {
        if (!this.$element.hasClass(classes.scroll) && !this.$element.hasClass(classes.bgScroll)) {
          return;
        }
        this.initScroll();
        this.setScrollDevices();
      }, 200);
    }
  }
  initScroll() {
    this.setEffectContainer();
    this.setConfig();
    const {
      bgScroll
    } = this.getSettings('classes');
    const settings = this.getScrollSettings();
    if (!settings.effects.length) {
      return;
    }
    settings.effects.forEach(effect => {
      if (!settings[effect]) {
        return;
      }
      this.attachEffect(effect);
    });
    this.scroll = basicScroll.create(this.config);
    this.scroll.calculate();
    this.scroll.update();
    this.scroll.start();
    if (!this.$element.hasClass(bgScroll)) {
      this.transformOriginInit();
    }
  }
  setEffectContainer() {
    const {
      bgScroll,
      bgContainer,
      bgElement
    } = this.getSettings('classes');
    let elementClass;
    switch (this.effectElementType) {
      case 'widget':
        let $exclusion = '';
        const $exclusionWidgets = ['elementor-widget-cmsmasters-offcanvas', 'cmsmasters-search-type-search-popup'];
        for (var i = 0; i < $exclusionWidgets.length; i++) {
          if (this.$element.hasClass($exclusionWidgets[i])) {
            $exclusion = $exclusionWidgets[i];
            break;
          }
        }
        if (this.$element.hasClass($exclusion)) {
          if ('elementor-widget-cmsmasters-offcanvas' === $exclusion) {
            elementClass = this.$element.find('.elementor-widget-cmsmasters-offcanvas__trigger');
          } else if ('cmsmasters-search-type-search-popup' === $exclusion) {
            elementClass = this.$element.find('.elementor-widget-cmsmasters-search__popup-trigger-inner');
          }
        } else {
          elementClass = this.$element.find('.elementor-widget-container');
        }
        break;
      case 'column':
        elementClass = this.$element.find(`.elementor-${this.columnClassElement}-wrap`);
        break;
      case 'section':
        elementClass = this.$element.find('.elementor-container');
        break;
      case 'container':
        elementClass = this.$element;
        break;
    }
    let $effectContainer = elementClass;
    if (this.$element.hasClass(bgScroll)) {
      const $container = jQuery('<div>', {
        class: bgContainer
      });
      jQuery('<div>', {
        class: bgElement
      }).appendTo($container);
      if ('section' === this.effectElementType || 'container' === this.effectElementType) {
        $effectContainer = this.$element;
      }
      let $effectContainerParent = $effectContainer.find(`> .${bgContainer}`);
      if (!$effectContainerParent.length) {
        $effectContainer.prepend($container);
      } else {
        $container.insertBefore($effectContainer);
      }
      $effectContainerParent = $effectContainer.find(`> .${bgContainer}`).last();
      $effectContainer = $effectContainerParent.find(`.${bgElement}`);
      this.bgElementSizes = {
        width: $effectContainerParent.width(),
        height: $effectContainerParent.height()
      };
    }
    this.effectContainer = $effectContainer.get(0);
  }
  setConfig() {
    const {
      from,
      to
    } = this.getScrollRange();
    this.config = {
      elem: this.effectContainer,
      direct: true,
      from,
      to,
      props: {}
    };
  }
  getScrollSettings() {
    let name = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
    const {
      bgScroll
    } = this.getSettings('classes');
    const elementSettings = super.getElementSettings();
    let suffix = '';
    if (this.$element.hasClass(bgScroll)) {
      suffix = 'bg_';
    }
    const prefix = `cms_${suffix}scroll_`;
    const settings = {};
    for (const [settingName, settingValue] of Object.entries(elementSettings)) {
      if (-1 === settingName.indexOf(prefix)) {
        continue;
      }
      const settingKey = settingName.replace(prefix, '');
      settings[settingKey] = settingValue;
    }
    if ('' !== name) {
      return settings[name] ? settings[name] : '';
    }
    return settings;
  }
  getScrollRange() {
    const range = {};
    if ('page' !== this.getScrollSettings('range')) {
      range.from = this.getViewportPositions('start');
      range.to = this.getViewportPositions('end');
    } else {
      range.from = this.getPagePositions('start');
      range.to = this.getPagePositions('end');
    }
    return range;
  }
  getViewportPositions(position) {
    const $effectContainer = jQuery(this.effectContainer);
    const viewport = this.getScrollSettings('viewport');
    const windowHeight = elementorFrontend.elements.$window.height();
    const elementScreenPoint = windowHeight / 100 * viewport.sizes[position];
    let windowPosition = $effectContainer.offset().top - windowHeight + elementScreenPoint;
    if ('end' === position) {
      windowPosition += $effectContainer.outerHeight();
    }
    return `${windowPosition}px`;
  }
  getPagePositions(position) {
    const {
      $document,
      $window
    } = elementorFrontend.elements;
    const viewport = this.getScrollSettings('viewport');
    const activeScreenHeight = $document.height() - $window.height();
    const windowPosition = activeScreenHeight / 100 * viewport.sizes[position];
    return `${windowPosition}px`;
  }
  attachEffect(effect) {
    const {
      bgScroll
    } = this.getSettings('classes');
    const settings = this.getScrollSettings();
    if (this.$element.hasClass(bgScroll)) {
      this.checkBgContainerSize(effect);
    }
    this.config.props[this.effectsVars[effect]] = {
      from: this.getEffectFromProperty(effect),
      to: this.getEffectToProperty(effect),
      timing: settings[`${effect}_timing`]
    };
  }
  checkBgContainerSize(effect) {
    let cssProperty = '';
    switch (effect) {
      case 'horizontal':
        cssProperty = 'width';
        break;
      case 'vertical':
        cssProperty = 'height';
        break;
    }
    if ('' === cssProperty) {
      return;
    }
    const settings = this.getScrollSettings();
    const cssValue = 100 + settings[`${effect}_speed`].size * 10;
    this.effectContainer.style.setProperty(cssProperty, `${cssValue}%`);
  }
  getEffectFromProperty(effect) {
    const {
      bgScroll
    } = this.getSettings('classes');
    const settings = this.getScrollSettings();
    const direction = settings[`${effect}_direction`];
    const speedSize = settings[`${effect}_speed`].size;
    let fromEffect;
    switch (effect) {
      case 'vertical':
        fromEffect = 50 * speedSize;
        if (this.$element.hasClass(bgScroll)) {
          fromEffect = -this.bgElementSizes.height / 10 * speedSize;
        }
        if ('reverse' === direction) {
          fromEffect = this.$element.hasClass(bgScroll) ? 0 : -fromEffect;
        }
        break;
      case 'horizontal':
        fromEffect = 50 * speedSize;
        if (this.$element.hasClass(bgScroll)) {
          fromEffect = -this.bgElementSizes.width / 10 * speedSize;
        }
        if ('reverse' === direction) {
          fromEffect = this.$element.hasClass(bgScroll) ? 0 : -fromEffect;
        }
        break;
      case 'rotate':
        fromEffect = 900 / 10 * speedSize;
        if ('reverse' === direction) {
          fromEffect = 0;
        }
        break;
      case 'scale':
        fromEffect = 1;
        if ('reverse' === direction) {
          fromEffect = 1 + speedSize / 10;
        }
        break;
      case 'opacity':
        fromEffect = 100 - speedSize * 10;
        if ('reverse' === direction) {
          fromEffect = 100;
        }
        break;
      case 'blur':
        fromEffect = 20 / 10 * speedSize;
        if ('reverse' === direction) {
          fromEffect = 0;
        }
        break;
      case 'grayscale':
      case 'sepia':
        fromEffect = speedSize * 10;
        if ('reverse' === direction) {
          fromEffect = 0;
        }
        break;
      case 'saturate':
      case 'brightness':
      case 'contrast':
        fromEffect = 100 + speedSize * 10;
        if ('reverse' === direction) {
          fromEffect = 100;
        }
        break;
      case 'huerotate':
        fromEffect = 360 / 10 * speedSize;
        if ('reverse' === direction) {
          fromEffect = 0;
        }
        break;
    }
    return fromEffect + this.getEffectUnit(effect);
  }
  getEffectToProperty(effect) {
    const {
      bgScroll
    } = this.getSettings('classes');
    const settings = this.getScrollSettings();
    const direction = settings[`${effect}_direction`];
    const speedSize = settings[`${effect}_speed`].size;
    let toEffect;
    switch (effect) {
      case 'vertical':
        toEffect = 50 * speedSize;
        if (this.$element.hasClass(bgScroll)) {
          toEffect = -this.bgElementSizes.height / 10 * speedSize;
        }
        if ('default' === direction) {
          toEffect = this.$element.hasClass(bgScroll) ? 0 : -toEffect;
        }
        break;
      case 'horizontal':
        toEffect = 50 * speedSize;
        if (this.$element.hasClass(bgScroll)) {
          toEffect = -this.bgElementSizes.width / 10 * speedSize;
        }
        if ('default' === direction) {
          toEffect = this.$element.hasClass(bgScroll) ? 0 : -toEffect;
        }
        break;
      case 'rotate':
        toEffect = 0;
        if ('reverse' === direction) {
          toEffect = 900 / 10 * speedSize;
        }
        break;
      case 'scale':
        toEffect = 1;
        if (0 !== speedSize) {
          toEffect = 1 + speedSize / 10;
          if ('reverse' === direction) {
            toEffect = 1;
          }
        }
        break;
      case 'opacity':
        toEffect = 100;
        if ('reverse' === direction) {
          toEffect = 100 - speedSize * 10;
        }
        break;
      case 'blur':
        toEffect = 0;
        if ('reverse' === direction) {
          toEffect = 20 / 10 * speedSize;
        }
        break;
      case 'grayscale':
      case 'sepia':
        toEffect = 0;
        if ('reverse' === direction) {
          toEffect = speedSize * 10;
        }
        break;
      case 'saturate':
      case 'brightness':
      case 'contrast':
        toEffect = 100;
        if ('reverse' === direction) {
          toEffect = 100 + speedSize * 10;
        }
        break;
      case 'huerotate':
        toEffect = 0;
        if ('reverse' === direction) {
          toEffect = 360 / 10 * speedSize;
        }
        break;
    }
    return toEffect + this.getEffectUnit(effect);
  }
  getEffectUnit(effect) {
    let unit = '';
    switch (effect) {
      case 'vertical':
      case 'horizontal':
      case 'blur':
        unit = 'px';
        break;
      case 'rotate':
      case 'huerotate':
        unit = 'deg';
        break;
      case 'opacity':
      case 'grayscale':
      case 'sepia':
      case 'saturate':
      case 'brightness':
      case 'contrast':
        unit = '%';
        break;
    }
    return unit;
  }
  transformOriginInit() {
    const settings = this.getScrollSettings();
    if (!settings.effects) {
      return;
    }
    if (-1 !== settings.effects.indexOf('rotate') && settings.rotate || -1 !== settings.effects.indexOf('scale') && settings.scale) {
      for (const [axis, cssVar] of Object.entries(this.originVars)) {
        this.effectContainer.style.setProperty(cssVar, settings[`origin_${axis}`]);
      }
    }
  }
  onDestroy() {
    this.scrollDeactivate();
  }
  scrollDeactivate() {
    if (!Object.keys(this.scroll).length || !this.scroll.isActive()) {
      return;
    }
    const {
      bgElement
    } = this.getSettings('classes');
    this.scroll.stop();
    this.scroll.destroy();
    if (jQuery(this.effectContainer).hasClass(bgElement)) {
      jQuery(this.effectContainer).parent().remove();
    } else {
      Object.keys(this.originVars).forEach(cssVar => this.effectContainer.style.removeProperty(cssVar));
    }
    this.config = {};
    this.scroll = {};
  }
  scrollRefresh() {
    if (!Object.keys(this.scroll).length || !this.scroll.isActive()) {
      return;
    }
    const scrollRange = this.getScrollRange();
    const differences = {
      from: Math.abs(parseFloat(scrollRange.from) - parseFloat(this.config.from)),
      to: Math.abs(parseFloat(scrollRange.to) - parseFloat(this.config.to))
    };
    if (50 <= differences.from || 50 <= differences.to) {
      this.scrollDeactivate();
      this.initScroll();
      this.setScrollDevices();
      return;
    }
    this.scroll.calculate();
    this.scroll.update();
  }

  // @since 1.6.3 set scroll devices.
  setScrollDevices() {
    const devices = ['desktop', 'tablet', 'mobile'];
    let scrollDevices = this.getElementSettings('cms_scroll_devices');
    if (jQuery(this.effectContainer).hasClass('cmsmasters-bg-effects-element')) {
      scrollDevices = this.getElementSettings('cms_bg_scroll_devices');
    }
    if (scrollDevices) {
      devices.forEach(item => {
        if (scrollDevices.includes(item)) {
          this.$element.attr('data-scroll-' + item, '');
        } else {
          this.$element.removeAttr('data-scroll-' + item);
        }
      });
    }
  }
}
var _default = $scope => {
  elementorFrontend.elementsHandler.addHandler(Scroll, {
    $element: $scope
  });
};
exports["default"] = _default;

/***/ }),

/***/ "../modules/effects/assets/dev/js/frontend/modules/tilt.js":
/*!*****************************************************************!*\
  !*** ../modules/effects/assets/dev/js/frontend/modules/tilt.js ***!
  \*****************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
const utils = __webpack_require__(/*! cmsmasters-helpers/utils */ "../assets/dev/js/helpers/utils.js");
class Tilt extends elementorModules.frontend.handlers.Base {
  __construct(settings) {
    super.__construct(settings);
    this.config = {};
    this.effectElementType = this.$element.data('element_type');
    this.effectContainer = undefined;
    const elementorExperiments = elementorFrontend.config.experimentalFeatures;
    this.columnClassElement = true === elementorExperiments.e_dom_optimization ? 'widget' : 'column';
    this.initTilt = utils.debounce(this.initTilt, 200);
    this.changeControls = ['background_background'];
    const changeControls = ['cms_effect_type', 'cms_tilt_direction', 'cms_tilt_shift_direction', 'cms_tilt_axis', 'cms_tilt_event_area', 'cms_tilt_reset'];
    changeControls.forEach(control => {
      this.changeControls.push(control);
      this.changeControls.push(control.replace('cms_', 'cms_bg_'));
    });
  }
  getDefaultSettings() {
    const baseClass = 'cmsmasters-tilt';
    const bgClass = 'cmsmasters-bg';
    const bgEffect = 'cmsmasters-bg-effect';
    const classes = {
      tilt: 'cmsmasters-effect-type-tilt',
      bgEffect: bgEffect,
      bgTilt: `${bgEffect}-type-tilt`,
      bgContainer: `${bgClass}-effects-container`,
      bgElement: `${bgClass}-effects-element`,
      tiltReverseDirection: `${baseClass}-direction-reverse`,
      shiftReverseDirection: `${baseClass}-shift-direction-reverse`,
      axisX: `${baseClass}-axis-x`,
      axisY: `${baseClass}-axis-y`,
      reset: `${baseClass}-reset-yes`,
      windowEventArea: `${baseClass}-event-area-window`
    };
    const selectors = {
      tiltInner: '.js-tilt-glare'
    };
    return {
      classes,
      selectors
    };
  }
  onInit() {
    this.setEffectContainer();
    this.initTilt();
    this.setTiltDevices();
  }
  onElementChange(propertyName) {
    if (-1 !== this.changeControls.indexOf(propertyName)) {
      this.tiltDeactivate();
      this.initTilt();
      this.setTiltDevices();
    }
  }
  initTilt() {
    const {
      classes
    } = this.getSettings();
    if (!this.$element.hasClass(classes.tilt) && !this.$element.hasClass(classes.bgTilt)) {
      return;
    }
    const {
      angle,
      perspective,
      shift,
      scale,
      speed,
      glare
    } = this.getTiltSettings();
    const perspectiveSetting = perspective ? Number(perspective) : 1000;
    let transitionSetting = true;
    let speedSetting = 1000;
    if ('' !== speed.size) {
      transitionSetting = 0 < speed.size ? true : false;
      speedSetting = Number(speed.size) * 1000;
    }
    this.config = {
      transition: transitionSetting,
      speed: speedSetting
    };
    this.config.perspective = this.$element.hasClass(classes.bgTilt) ? 0 : perspectiveSetting;
    if (this.$element.hasClass(classes.bgTilt)) {
      this.config.max = 0;
    } else if ('' !== angle.size) {
      this.config.max = Number(angle.size);
    }
    if ('' !== scale.size) {
      this.config.scale = Number(scale.size);
    }
    if (!this.$element.hasClass(classes.bgTilt) && '' !== glare.size && 0 < glare.size) {
      this.config.glare = true;
      this.config['max-glare'] = Number(glare.size);
    }
    if (this.$element.hasClass(classes.tiltReverseDirection)) {
      this.config.reverse = true;
    }
    if (this.$element.hasClass(classes.axisX)) {
      this.config.axis = 'x';
    } else if (this.$element.hasClass(classes.axisY)) {
      this.config.axis = 'y';
    }
    if (!this.$element.hasClass(classes.reset)) {
      this.config.reset = false;
    }
    if (this.$element.hasClass(classes.windowEventArea) || this.$element.hasClass(classes.bgTilt)) {
      this.config['full-page-listening'] = true;
      if ('' !== shift.size && 0 < shift.size) {
        this.config.translate = true;
        this.config['translate-speed'] = 5 < Number(shift.size) ? 5 : Number(shift.size);
        if (!this.$element.hasClass(classes.shiftReverseDirection)) {
          this.config['translate-reverse'] = false;
        }
        if (this.$element.hasClass(classes.bgTilt)) {
          this.config['translate-background'] = true;
          this.checkBgContainerSize();
        }
      }
    }
    VanillaTilt.init(this.effectContainer, this.config);
    if (!this.$element.hasClass(classes.bgTilt)) {
      this.resetInnerTranslate(perspectiveSetting);
    }
  }
  setEffectContainer() {
    const {
      bgTilt,
      bgContainer,
      bgElement
    } = this.getSettings('classes');
    let elementClass;
    switch (this.effectElementType) {
      case 'widget':
        let $exclusion = '';
        const $exclusionWidgets = ['elementor-widget-cmsmasters-offcanvas', 'cmsmasters-search-type-search-popup'];
        for (var i = 0; i < $exclusionWidgets.length; i++) {
          if (this.$element.hasClass($exclusionWidgets[i])) {
            $exclusion = $exclusionWidgets[i];
            break;
          }
        }
        if (this.$element.hasClass($exclusion)) {
          if ('elementor-widget-cmsmasters-offcanvas' === $exclusion) {
            elementClass = this.$element.find('.elementor-widget-cmsmasters-offcanvas__trigger');
          } else if ('cmsmasters-search-type-search-popup' === $exclusion) {
            elementClass = this.$element.find('.elementor-widget-cmsmasters-search__popup-trigger-inner');
          }
        } else {
          elementClass = this.$element.find('.elementor-widget-container');
        }
        break;
      case 'column':
        elementClass = this.$element.find(`.elementor-${this.columnClassElement}-wrap`);
        break;
      case 'section':
        elementClass = this.$element.find('.elementor-container');
        break;
      case 'container':
        elementClass = this.$element;
        break;
    }
    let $effectContainer = elementClass;
    if (this.$element.hasClass(bgTilt)) {
      const $container = jQuery('<div>', {
        class: bgContainer
      });
      jQuery('<div>', {
        class: bgElement
      }).appendTo($container);
      if ('section' === this.effectElementType || 'container' === this.effectElementType) {
        $effectContainer = this.$element;
      }
      let $effectContainerParent = $effectContainer.find(`> .${bgContainer}`);
      if (!$effectContainerParent.length) {
        $effectContainer.prepend($container);
      } else {
        $container.insertBefore($effectContainer);
      }
      $effectContainerParent = $effectContainer.find(`> .${bgContainer}`).last();
      $effectContainer = $effectContainerParent.find(`.${bgElement}`);
    }
    this.effectContainer = $effectContainer.get(0);
  }
  getTiltSettings() {
    let name = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
    const {
      bgTilt
    } = this.getSettings('classes');
    const elementSettings = super.getElementSettings();
    let suffix = '';
    if (this.$element.hasClass(bgTilt)) {
      suffix = 'bg_';
    }
    const prefix = `cms_${suffix}tilt_`;
    const settings = {};
    for (const [settingName, settingValue] of Object.entries(elementSettings)) {
      if (-1 === settingName.indexOf(prefix)) {
        continue;
      }
      const settingKey = settingName.replace(prefix, '');
      settings[settingKey] = settingValue;
    }
    if ('' !== name) {
      return settings[name] ? settings[name] : '';
    }
    return settings;
  }
  checkBgContainerSize() {
    if (!this.config['translate-speed']) {
      return;
    }
    let speed = this.config['translate-speed'];
    const cssSize = 100 + speed * 20;
    const cssPosition = speed * 10;
    this.effectContainer.style.setProperty('width', `${cssSize}%`);
    this.effectContainer.style.setProperty('height', `${cssSize}%`);
    this.effectContainer.style.setProperty('top', `-${cssPosition}%`);
    this.effectContainer.style.setProperty('left', `-${cssPosition}%`);
  }
  resetInnerTranslate(perspective) {
    const {
      tiltInner
    } = this.getSettings('selectors');
    const $innerElements = jQuery(this.effectContainer).find(`> *:not(${tiltInner})`);
    if (0 !== perspective && !perspective) {
      $innerElements.removeAttr('style');
      return;
    } else if (!this.effectContainer.vanillaTilt) {
      return;
    }
    const translate = Math.abs(perspective * 0.07);
    $innerElements.css('transform', `translateZ(${translate}px)`);
  }
  onDestroy() {
    this.tiltDeactivate();
  }
  tiltDeactivate() {
    if (!this.effectContainer || !this.effectContainer.vanillaTilt) {
      return;
    }
    const {
      bgTilt,
      bgElement
    } = this.getSettings('classes');
    if (!this.$element.hasClass(bgTilt)) {
      this.resetInnerTranslate(false);
    }
    this.effectContainer.vanillaTilt.destroy();
    if (jQuery(this.effectContainer).hasClass(bgElement)) {
      setTimeout(() => jQuery(this.effectContainer).parent().remove(), 10);
    }
  }

  // @since 1.6.3 set tilt devices.
  setTiltDevices() {
    const devices = ['desktop', 'tablet', 'mobile'];
    let scrollDevices = this.getElementSettings('cms_tilt_devices');
    if (jQuery(this.effectContainer).hasClass('cmsmasters-bg-effects-element')) {
      scrollDevices = this.getElementSettings('cms_bg_tilt_devices');
    }
    if (scrollDevices) {
      devices.forEach(item => {
        if (scrollDevices.includes(item)) {
          this.$element.attr('data-tilt-' + item, '');
        } else {
          this.$element.removeAttr('data-tilt-' + item);
        }
      });
    }
  }
}
var _default = $scope => {
  elementorFrontend.elementsHandler.addHandler(Tilt, {
    $element: $scope
  });
};
exports["default"] = _default;

/***/ }),

/***/ "../modules/effects/assets/dev/js/frontend/modules/transform.js":
/*!**********************************************************************!*\
  !*** ../modules/effects/assets/dev/js/frontend/modules/transform.js ***!
  \**********************************************************************/
/***/ ((__unused_webpack_module, exports) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
class Transform extends elementorModules.frontend.handlers.Base {
  onInit() {
    this.initTransform();
  }
  initTransform() {
    const settings = this.getElementSettings();
    const outRowID = settings.cms_transform_out_row_id_hover;
    if ('section' === settings.cms_transform_hover_type && 'undefined' !== typeof outRowID) {
      const elementClassMatches = this.$element.attr('class').match(/(?:^|\s)(elementor-element-[a-zA-Z0-9-_]+)/);
      const elementClass = elementClassMatches ? elementClassMatches[1] : '';
      const parsedSelectors = settings.cms_transform_out_row_custom_selector_hover.map(selector => {
        return `html body#cmsmasters_body ${outRowID}${selector.replace(/\elementor-element-\{\{ID\}\}/g, elementClass)}`;
      });
      let styleContent = parsedSelectors.join(',\n');
      const styles = `/* Transform effect on hover with "${outRowID}" custom container class or ID*/\n${styleContent} {\n\t${settings.cms_transform_out_row_custom_value_hover}\n}`;
      const styleElement = document.createElement('style');
      styleElement.type = 'text/css';
      styleElement.appendChild(document.createTextNode(styles));
      document.getElementsByTagName('head')[0].appendChild(styleElement);
    }
  }
}
var _default = $scope => {
  elementorFrontend.elementsHandler.addHandler(Transform, {
    $element: $scope
  });
};
exports["default"] = _default;

/***/ }),

/***/ "../modules/gallery/assets/dev/js/frontend/frontend.js":
/*!*************************************************************!*\
  !*** ../modules/gallery/assets/dev/js/frontend/frontend.js ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      gallery: () => __webpack_require__.e(/*! import() | gallery */ "gallery").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/gallery */ "../modules/gallery/assets/dev/js/frontend/widgets/gallery.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/give-wp/assets/dev/js/frontend/give-wp-forms-scrollbar.js":
/*!****************************************************************************!*\
  !*** ../modules/give-wp/assets/dev/js/frontend/give-wp-forms-scrollbar.js ***!
  \****************************************************************************/
/***/ ((__unused_webpack_module, exports) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
class _default extends elementorModules.ViewModule {
  __construct(settings) {
    super.__construct(settings);
  }
  onInit() {
    super.onInit(...arguments);
    const isGivePage = jQuery('body').hasClass('give-page');
    if (!isGivePage) {
      return;
    }
    this.ScrollForTriggerButton();
    this.ScrollForTriggerGrid();
  }
  ScrollForTriggerButton() {
    const observer = new MutationObserver((mutationsList, observer) => {
      for (let mutation of mutationsList) {
        if (jQuery(mutation.target).hasClass('give-modal-open')) {
          this.initScroll();
        }
      }
    });
    observer.observe(jQuery('body')[0], {
      attributes: true,
      attributeFilter: ['class'],
      subtree: true
    });
  }
  ScrollForTriggerGrid() {
    jQuery('.js-give-grid-modal-launcher').on('mfpOpen', () => {
      this.initScroll();
    });
  }
  initScroll() {
    const $scrollElement = jQuery('.give-page ').find('.mfp-content')[0];
    new PerfectScrollbar($scrollElement, {
      wheelSpeed: 0.5,
      suppressScrollX: false,
      suppressScrollX: true
    });
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/google-maps/assets/dev/js/frontend/frontend.js":
/*!*****************************************************************!*\
  !*** ../modules/google-maps/assets/dev/js/frontend/frontend.js ***!
  \*****************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      'google-maps': () => __webpack_require__.e(/*! import() | google-maps */ "google-maps").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/google-maps */ "../modules/google-maps/assets/dev/js/frontend/widgets/google-maps.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/hotspot/assets/dev/js/frontend/frontend.js":
/*!*************************************************************!*\
  !*** ../modules/hotspot/assets/dev/js/frontend/frontend.js ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      hotspot: () => __webpack_require__.e(/*! import() | hotspot */ "hotspot").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/hotspot */ "../modules/hotspot/assets/dev/js/frontend/handlers/hotspot.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/image-scroll/assets/dev/js/frontend/frontend.js":
/*!******************************************************************!*\
  !*** ../modules/image-scroll/assets/dev/js/frontend/frontend.js ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      'image-scroll': () => __webpack_require__.e(/*! import() | image-scroll */ "image-scroll").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/image-scroll */ "../modules/image-scroll/assets/dev/js/frontend/widgets/image-scroll.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/infinite-scroll/assets/dev/js/frontend/frontend.js":
/*!*********************************************************************!*\
  !*** ../modules/infinite-scroll/assets/dev/js/frontend/frontend.js ***!
  \*********************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
const utils = __webpack_require__(/*! cmsmasters-helpers/utils */ "../assets/dev/js/helpers/utils.js");
class _default extends _module.default {
  __construct() {
    super.__construct(...arguments);
    this.$currentPost = null;
    this.onScroll = utils.debounce(this.onScroll.bind(this));
  }
  getDefaultSettings() {
    return {
      selectors: {
        wrap: '.cmsmasters-post-infinite-scroll',
        button: '.cmsmasters-post-infinite-scroll button',
        posts: '.cmsmasters-single-post'
      },
      classes: {
        _loading: '--loading',
        _currentPost: 'cmsmasters-single-post__current'
      }
    };
  }
  getDefaultElements() {
    const {
      selectors
    } = this.getDefaultSettings();
    return {
      $wrap: jQuery(selectors.wrap),
      $button: jQuery(selectors.button),
      $posts: jQuery(selectors.posts)
    };
  }
  bindEvents() {
    if (!this.elements.$wrap.length) {
      return;
    }
    this.elements.$button.on('click', this.onClick.bind(this));
    elementorFrontend.elements.$window.on('scroll', this.onScroll);
  }
  initWidgets() {
    return {};
  }
  onInit() {
    if (window.elementor) {
      return;
    }
    super.onInit();
    if (!this.elements.$wrap.length) {
      return;
    }
    this.initWayPointinfiniteScroll();
    this.initFirstPostData();
  }
  initWayPointinfiniteScroll() {
    this.infiniteScroll = new Waypoint({
      element: this.elements.$wrap,
      offset: 'bottom-in-view',
      handler: direction => {
        if ('down' !== direction) {
          return;
        }
        this.elements.$button.trigger('click');
        this.infiniteScroll.destroy();
      }
    });
  }
  initFirstPostData() {
    this.getGeneralPost().data('post', {
      document_title: document.title,
      permalink: location.href
    });
  }
  getGeneralPost() {
    return this.elements.$posts.eq(0);
  }
  findPost() {
    if (0 === scrollY) {
      this.setCurrentPost(this.getGeneralPost());
      return;
    }
    const center = screen.height / 2;
    this.elements.$posts.each((index, post) => {
      const $post = jQuery(post);
      const {
        top,
        height
      } = post.getBoundingClientRect();
      const bottom = top + height;
      if (top < center && bottom > center) {
        this.setCurrentPost($post);
        return true;
      }
    });
  }
  setCurrentPost($post) {
    const {
      classes
    } = this.getDefaultSettings();
    if ($post.hasClass(classes._currentPost)) {
      return;
    }
    this.$currentPost = $post;
    this.elements.$posts.removeClass(classes._currentPost);
    this.$currentPost.addClass(classes._currentPost);
    this.updatePost();
  }
  updatePost() {
    history.replaceState(null, null, this.getPermalink());
    document.title = this.getDocumentTitle();
  }
  getPermalink() {
    return this.$currentPost.data('post').permalink;
  }
  getDocumentTitle() {
    return this.$currentPost.data('post').document_title;
  }
  onClick(event) {
    event.preventDefault();
    this.processStart();
    const parameters = {
      url: elementorCmsmastersFrontendConfig.ajaxurl,
      type: 'POST',
      dataType: 'json',
      data: {
        _ajax_nonce: elementorCmsmastersFrontendConfig.nonces.infinite_scroll,
        action: 'cmsmasters_single_infinite_scroll',
        post_id: this.elements.$button.data().postId
      }
    };
    jQuery.ajax(parameters).done(this.onDone.bind(this)).always(this.onAlways.bind(this));
  }
  processStart() {
    const {
      classes
    } = this.getDefaultSettings();
    this.elements.$wrap.addClass(classes._loading);
    this.elements.$wrap.css({
      height: `${this.elements.$wrap.get(0).scrollHeight}px`
    });
  }
  onDone(response) {
    if (!response || !response.success || !response.data || !(!Array.isArray(response.data) && !response.data.length) || !response.data.previous_post_html) {
      this.destroy();
      return;
    }
    this.addPosts(response);
    this.elements.$button.data('post-id', response.data.previous_post_id);
    this.initWayPointinfiniteScroll();
  }
  destroy() {
    this.elements.$wrap.remove();
    elementorFrontend.elements.$window.off('scroll', this.onScroll);
  }
  addPosts(response) {
    const $html = jQuery(response.data.previous_post_html);
    let $post;
    if (response.data.is_elementor) {
      const {
        selectors
      } = this.getSettings();
      $post = $html.filter(selectors.posts);
    } else {
      $post = $html;
    }
    if (!$post.length) {
      return;
    }
    $post.data('post', response.data.previous_post_data);
    this.elements.$wrap.before($html);
    this.elements.$posts.push($post.get(0));
    this.findPost();
  }
  onAlways() {
    this.processEnd();
  }
  processEnd() {
    const {
      classes
    } = this.getDefaultSettings();
    this.elements.$wrap.removeClass(classes._loading);
  }
  onScroll() {
    this.findPost();
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/instagram/assets/dev/js/frontend/frontend.js":
/*!***************************************************************!*\
  !*** ../modules/instagram/assets/dev/js/frontend/frontend.js ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      instagram: () => Promise.all(/*! import() | instagram */[__webpack_require__.e("assets_dev_js_frontend_base_handler_js-modules_slider_assets_dev_js_frontend_slider_js"), __webpack_require__.e("instagram")]).then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/instagram */ "../modules/instagram/assets/dev/js/frontend/widgets/instagram.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/library-template/assets/dev/js/frontend/frontend.js":
/*!**********************************************************************!*\
  !*** ../modules/library-template/assets/dev/js/frontend/frontend.js ***!
  \**********************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      'template': () => __webpack_require__.e(/*! import() | template */ "template").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/template */ "../modules/library-template/assets/dev/js/frontend/widgets/template.js")) // eslint-disable-line quote-props
    };

    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/mailchimp/assets/dev/js/frontend/frontend.js":
/*!***************************************************************!*\
  !*** ../modules/mailchimp/assets/dev/js/frontend/frontend.js ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      mailchimp: () => __webpack_require__.e(/*! import() | mailchimp */ "mailchimp").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/mailchimp */ "../modules/mailchimp/assets/dev/js/frontend/handlers/mailchimp.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/media/assets/dev/js/frontend/frontend.js":
/*!***********************************************************!*\
  !*** ../modules/media/assets/dev/js/frontend/frontend.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      'video': () => __webpack_require__.e(/*! import() | video */ "video").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/video */ "../modules/media/assets/dev/js/frontend/widgets/video.js")),
      // eslint-disable-line quote-props
      'video-stream': () => __webpack_require__.e(/*! import() | video-stream */ "video-stream").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/video-stream */ "../modules/media/assets/dev/js/frontend/widgets/video-stream.js")),
      'video-slider': () => __webpack_require__.e(/*! import() | video-slider */ "video-slider").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/video-slider */ "../modules/media/assets/dev/js/frontend/widgets/video-slider.js")),
      'video-playlist': () => __webpack_require__.e(/*! import() | video-playlist */ "video-playlist").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/video-playlist */ "../modules/media/assets/dev/js/frontend/widgets/video-playlist.js")),
      'audio': () => __webpack_require__.e(/*! import() | audio */ "audio").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/audio */ "../modules/media/assets/dev/js/frontend/widgets/audio.js")),
      // eslint-disable-line quote-props
      'audio-playlist': () => __webpack_require__.e(/*! import() | audio-playlist */ "audio-playlist").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/audio-playlist */ "../modules/media/assets/dev/js/frontend/widgets/audio-playlist.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/meta-data/assets/dev/js/frontend/frontend.js":
/*!***************************************************************!*\
  !*** ../modules/meta-data/assets/dev/js/frontend/frontend.js ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, exports) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
class _default extends elementorModules.ViewModule {
  getDefaultElements() {
    const elements = {
      $document: jQuery(document)
    };
    return elements;
  }
  bindEvents() {
    this.elements.$document.on('click', '.cmsmasters-postmeta[data-name="count"][data-type="like"]', this.onClickLinks.bind(this));
  }
  onInit() {
    super.onInit();
    this.setViews();
  }

  /**
   * Increases the number of views on page load.
   *
   * @since 1.0.0
   */
  setViews() {
    if (elementorFrontend.isEditMode() || !elementorFrontend.config.post.id) {
      return;
    }
    const $el = this.getViews().filter(`[data-id="${elementorFrontend.config.post.id}"]:not(.active)`).eq(0);
    this.ajaxStart({
      id: elementorFrontend.config.post.id,
      type: 'view',
      $el
    });
  }

  /**
   * Returns a view element.
   *
   * @since 1.0.0
   *
   * @returns {jQuery}
   */
  getViews() {
    return this.getByType('view');
  }

  /**
   * Returns a element by type.
   *
   * @since 1.0.0
   *
   * @returns {jQuery}
   */
  getByType(type) {
    return jQuery(`.cmsmasters-postmeta[data-name="count"][data-type="${type}"]`);
  }

  /**
   * Starts ajax handler
   *
   * @param {Object} args - The arguments for ajax.
   * @param {string} args.id - The postID.
   * @param {string} args.type - The type of element.
   * @param {string} args.$el - The element on which the handler is triggered.
   *
   * @since 1.0.0
   *
   * @return {jqXHR}
   */
  ajaxStart(_ref) {
    let {
      id: postID,
      type,
      $el = null
    } = _ref;
    if ($el && $el.length) {
      this.toggleProcess($el, true);
    }
    const {
      ajaxurl: url,
      nonces: {
        meta_data: nonce
      }
    } = cmsmastersElementorFrontend.config;
    return jQuery.post({
      url,
      dataType: 'json',
      data: {
        post_id: postID,
        action: `cmsmasters_pm_${type}`,
        nonce
      }
    }).fail(() => this.endProcess($el)).done(response => {
      if ($el && $el.length) {
        this.endProcess($el, response);
      }
    });
  }

  /**
   * End of ajax handler connection error.
   *
   * @since 1.0.0
   *
   * @returns {jQuery}
   */
  startProcess($el) {
    this.toggleProcess($el, true);
  }

  /**
   * End of ajax handler connection error.
   *
   * @since 1.0.0
   */
  endProcess($el) {
    let response = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
    const $elements = this.toggleProcess($el, false);
    if (response.success) {
      $elements.toggleClass('active', response.data.active).find('.cmsmasters-postmeta__content').text(response.data.count);
    }
  }

  /**
   * Toggle ajax state.
   *
   * @since 1.0.0
   *
   * @returns {jQuery}
   */
  toggleProcess($el, state) {
    const {
      id,
      type
    } = $el.data();
    const $elements = this.getByType(type).filter(`[data-id="${id}"]`);
    $elements.toggleClass('loading', state);
    return $elements;
  }
  onClickLinks(event) {
    event.preventDefault();
    const $el = jQuery(event.currentTarget);
    const jqXHR = this.ajaxStart({
      id: $el.data().id,
      type: 'like',
      $el
    });
    jqXHR.always(() => {
      if (200 !== jqXHR.status || !jqXHR.responseJSON || !jqXHR.responseJSON.success) {
        return;
      }
      const {
        metadata_unlike: textUnlike,
        metadata_like: textLike
      } = cmsmastersElementorFrontend.config.i18n.meta_data;
      if (jqXHR.responseJSON.data.active) {
        $el.attr('title', textUnlike);
      } else {
        $el.attr('title', textLike);
      }
    });
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/mode-switcher/assets/dev/js/frontend/frontend.js":
/*!*******************************************************************!*\
  !*** ../modules/mode-switcher/assets/dev/js/frontend/frontend.js ***!
  \*******************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      'mode-switcher': () => __webpack_require__.e(/*! import() | mode-switcher */ "mode-switcher").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/mode-switcher */ "../modules/mode-switcher/assets/dev/js/frontend/widgets/mode-switcher.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/popup/assets/dev/js/frontend/document.js":
/*!***********************************************************!*\
  !*** ../modules/popup/assets/dev/js/frontend/document.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, exports) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
class _default extends elementorModules.frontend.Document {
  getDefaultElements() {
    const elements = super.getDefaultElements();
    elements.$parent = this.$element.parent();
    return elements;
  }
  onInit() {
    super.onInit();
    if (!elementorFrontend.isEditMode()) {
      return;
    }
    this.initModal();
  }
  getElementMessage() {
    return this.$element;
  }
  async initModal() {
    if (this.modal) {
      return;
    }
    const documentSettings = this.getDocumentSettings();
    if (!Object.keys(documentSettings).length) {
      return;
    }
    const {
      id
    } = this.getSettings();
    let className = `cmsmasters-widget-template-modal cmsmasters-widget-template-popup cmsmasters-widget-template-popup-${id}`;
    if (documentSettings.classes) {
      className += ` ${documentSettings.classes}`;
    }
    if (!window.DialogsManager) {
      await elementorFrontend.utils.assetsLoader.load('script', 'dialog');
    }
    this.modal = elementorFrontend.getDialogsManager().createWidget('lightbox', {
      className,
      closeButton: false,
      hide: false,
      position: {
        enable: false
      }
    });
    this.modal.setMessage(this.getElementMessage()).show();
    this.addCloceButton();
  }
  addCloceButton() {
    const $popup = jQuery('.elementor[data-elementor-type="cmsmasters_popup"]');
    const $popupInner = jQuery('.elementor[data-elementor-type="cmsmasters_popup"] .elementor-section-wrap > .elementor-element-edit-mode');
    const iconHtml = $popup.attr('data-cms-icon');
    const buttonClose = `<span class="cmsmasters-popup-close">${iconHtml}</span>`;
    const $documentSettings = this.getDocumentSettings();
    if ('outside' !== $documentSettings.cms_close_button_position) {
      $popup.addClass('cmsmasters-elementor-popup__close-popup-inner');
    }
    $popup.append(buttonClose);
    $popupInner.append(buttonClose);
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/popup/assets/dev/js/frontend/frontend.js":
/*!***********************************************************!*\
  !*** ../modules/popup/assets/dev/js/frontend/frontend.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      'time-popup': () => __webpack_require__.e(/*! import() | time-popup */ "time-popup").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/time-popup */ "../modules/popup/assets/dev/js/frontend/handlers/time-popup.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/popup/assets/dev/js/frontend/popup.js":
/*!********************************************************!*\
  !*** ../modules/popup/assets/dev/js/frontend/popup.js ***!
  \********************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _document = _interopRequireDefault(__webpack_require__(/*! ./document */ "../modules/popup/assets/dev/js/frontend/document.js"));
class _default extends elementorModules.ViewModule {
  __construct(settings) {
    super.__construct(settings);
  }
  onInit() {
    super.onInit();
    elementorFrontend.hooks.addAction('elementor/frontend/documents-manager/init-classes', this.addDocumentClass);
    if (elementorFrontend.isEditMode()) {
      return;
    }
    this.clickPopupButton();
    this.closePopupOverlay();
    this.closePopupButton();
  }
  clickPopupButton() {
    const button = '[href*="#cmsmasters-popup-"]';
    let uniqueID = 1;
    const beforeAppend = true;
    jQuery(document).on('click', button, event => {
      event.preventDefault();
      const href = jQuery(event.currentTarget).attr('href');
      const number = href.match(/\d+/)[0];
      let templateID = `-${number}`;
      this.addCloceButton(number);
      const currentElements = this.popupCurrentElements(event, beforeAppend, templateID);
      if (!currentElements.$currentButton.attr('data-trigger-id')) {
        currentElements.$currentButton.attr('data-trigger-id', uniqueID);
        uniqueID++;
      }
      const $triggerID = currentElements.$currentButton.attr('data-trigger-id');
      currentElements.$currentPopup.attr('data-trigger-popup-id', $triggerID);
      this.appendPopup(event, $triggerID, beforeAppend, templateID);
      this.showPopup($triggerID);
    });
  }
  appendPopup(event, $triggerID, beforeAppend, templateID) {
    const currentElements = this.popupCurrentElements(event, beforeAppend, templateID);
    const $currentWrapper = jQuery('.cmsmasters-current-popup-trigger');
    const isPopup = $currentWrapper.find(currentElements.$currentPopup).length;
    if (isPopup) {
      currentElements.$currentPopup.addClass(`cmsmasters-popup-trigger-${$triggerID}`);
      const $currentPopupHtml = currentElements.$currentPopup.prop('outerHTML');
      jQuery('body').append($currentPopupHtml);
      currentElements.$currentPopup.remove();
      jQuery(`.cmsmasters-popup-trigger-${$triggerID}`).find('.elementor-element').each((index, element) => {
        elementorFrontend.elementsHandler.runReadyTrigger(element);
      });
    }
  }
  showPopup($triggerID) {
    const settings = this.settingsTemplatePopup($triggerID);
    const $currentPopup = settings.$popup;
    const $currentPopupInner = settings.$popupInner;
    const currentPopupSettings = settings.settingsPopupObj;
    const $scrollElement = $currentPopup.find('.elementor-section-wrap')[0];
    let initScroll = false;
    if (!initScroll) {
      this.initPerfectScrollbar($scrollElement);
      initScroll = true;
    }
    if ('yes' === currentPopupSettings.cms_overlay) {
      $currentPopup.addClass('cmsmasters-elementor-popup__overlay-hide');
    }
    if ('outside' !== currentPopupSettings.cms_close_button_position) {
      $currentPopup.addClass('cmsmasters-elementor-popup__close-popup-inner');
    }
    if ('yes' !== currentPopupSettings.cms_multiple_popup) {
      jQuery('.cmsmasters-elementor-popup').removeClass('cmsmasters-elementor-popup__show');
      jQuery('*[class^="cmsmasters-elementor-popup__hide-scroll-"]').removeClass((index, className) => {
        return className.split(' ').filter(c => {
          return c.indexOf('cmsmasters-elementor-popup__hide-scroll-') === 0;
        }).join(' ');
      });
      jQuery('html').css('overflow', 'visible');
    } else {
      $currentPopup.removeClass('cmsmasters-elementor-popup__show');
    }
    setTimeout(() => {
      $currentPopup.addClass('cmsmasters-elementor-popup__show');
      $currentPopupInner.addClass(currentPopupSettings.cms_entrance_animation);
      if ('yes' == currentPopupSettings.cms_prevent_scroll) {
        this.hideScroll($triggerID, $currentPopup);
      }
    }, 300);
  }
  closePopupOverlay() {
    const overlay = '.elementor[data-elementor-type="cmsmasters_popup"]';
    const beforeAppend = false;
    const templateID = '';
    jQuery(document).on('click', overlay, event => {
      const currentElements = this.popupCurrentElements(event, beforeAppend, templateID);
      const $triggerID = currentElements.$currentPopup.attr('data-trigger-popup-id');
      const settings = this.settingsTemplatePopup($triggerID);
      const currentPopupSettings = settings.settingsPopupObj;
      const $content = currentElements.$currentPopup.find('.elementor-section-wrap');
      if ('yes' !== currentPopupSettings.cms_prevent_close_on_background_click) {
        if (!$content.is(event.target) && $content.has(event.target).length === 0) {
          currentElements.$currentPopupInner.removeClass(currentPopupSettings.cms_entrance_animation);
          currentElements.$currentPopup.removeClass('cmsmasters-elementor-popup__show');
          if ('yes' == currentPopupSettings.cms_prevent_scroll) {
            this.hideScroll($triggerID, currentElements.$currentPopup);
          }
        }
      } else {
        return false;
      }
    });
  }
  closePopupButton() {
    const close = '.cmsmasters-popup-close';
    const beforeAppend = false;
    const templateID = '';
    jQuery(document).on('click', close, event => {
      const currentElements = this.popupCurrentElements(event, beforeAppend, templateID);
      const $triggerID = currentElements.$currentPopup.attr('data-trigger-popup-id');
      const settings = this.settingsTemplatePopup($triggerID);
      const currentPopupSettings = settings.settingsPopupObj;
      currentElements.$currentPopupInner.removeClass(currentPopupSettings.cms_entrance_animation);
      currentElements.$currentPopup.removeClass('cmsmasters-elementor-popup__show');
      if ('yes' == currentPopupSettings.cms_prevent_scroll) {
        this.hideScroll($triggerID, currentElements.$currentPopup);
      }
    });
  }
  settingsTemplatePopup($triggerID) {
    const $popup = jQuery(`.cmsmasters-elementor-popup.cmsmasters-popup-trigger-${$triggerID}`);
    const $popupInner = $popup.find('.elementor-inner');
    const $templateID = $popup.attr('data-popup-id');
    const $currentPopupClose = $popup.find('.cmsmasters-popup-close');
    const $settingsPopup = $popup.find(`.elementor-${$templateID}`).attr('data-elementor-settings');
    const settingsObj = JSON.parse($settingsPopup);
    const settingsPopupObj = jQuery.extend({}, this.defaultSettings(), settingsObj);
    return {
      $popup: $popup,
      $popupInner: $popupInner,
      settingsPopupObj: settingsPopupObj,
      $currentPopupClose: $currentPopupClose
    };
  }
  defaultSettings() {
    return {
      cms_close_button_icon: {
        library: 'regular',
        value: 'far fa-times-circle'
      }
    };
  }
  popupCurrentElements(event, beforeAppend, templateID) {
    const $currentElement = jQuery(event.currentTarget);
    let $currentPopup = '';
    if (beforeAppend) {
      jQuery('.elementor-widget-container').removeClass('cmsmasters-current-popup-trigger');
      $currentElement.closest('.elementor-widget-container').addClass('cmsmasters-current-popup-trigger');
      $currentPopup = jQuery(`.cmsmasters-current-popup-trigger .cmsmasters-elementor-popup${templateID}`);
    } else {
      $currentPopup = $currentElement.closest('.cmsmasters-elementor-popup');
    }
    const $currentPopupInner = $currentPopup.find('.elementor-inner');
    return {
      $currentButton: $currentElement,
      $currentPopup: $currentPopup,
      $currentPopupInner: $currentPopupInner
    };
  }
  addCloceButton(number) {
    const $popup = jQuery(`.elementor[data-elementor-type="cmsmasters_popup"][data-elementor-id=${number}]`);
    const $popupInner = $popup.find('.elementor-section-wrap');
    const iconHtml = $popup.attr('data-cms-icon');
    const buttonClose = `<span class="cmsmasters-popup-close">${iconHtml}</span>`;
    $popup.append(buttonClose);
    $popupInner.append(buttonClose);
  }
  hideScroll($triggerID, $currentPopup) {
    const isShow = $currentPopup.hasClass('cmsmasters-elementor-popup__show');
    let state = '';
    if (isShow) {
      state = 'show';
      jQuery('html').removeClass(`cmsmasters-elementor-popup__hide-scroll-hide-${$triggerID}`);
      jQuery('html').addClass(`cmsmasters-elementor-popup__hide-scroll-${state}-${$triggerID}`);
      jQuery(`.cmsmasters-elementor-popup__hide-scroll-${state}-${$triggerID}`).css('overflow', 'hidden');
    } else {
      state = 'hide';
      jQuery('html').removeClass(`cmsmasters-elementor-popup__hide-scroll-show-${$triggerID}`);
      jQuery('html').addClass(`cmsmasters-elementor-popup__hide-scroll-${state}-${$triggerID}`);
      jQuery(`.cmsmasters-elementor-popup__hide-scroll-${state}-${$triggerID}`).css('overflow', 'visible');
    }
    jQuery('html').addClass(`cmsmasters-elementor-popup__hide-scroll-${state}-${$triggerID}`);
  }
  initPerfectScrollbar($scrollElement) {
    new PerfectScrollbar($scrollElement, {
      wheelSpeed: 0.5,
      suppressScrollX: false,
      suppressScrollX: true
    });
  }
  addDocumentClass(documentsManager) {
    documentsManager.addDocumentClass('cmsmasters_popup', _document.default);
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/progress-tracker/assets/dev/js/frontend/frontend.js":
/*!**********************************************************************!*\
  !*** ../modules/progress-tracker/assets/dev/js/frontend/frontend.js ***!
  \**********************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      'progress-tracker': () => __webpack_require__.e(/*! import() | progress-tracker */ "progress-tracker").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/handlers/progress-tracker */ "../modules/progress-tracker/assets/dev/js/frontend/widgets/handlers/progress-tracker.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/share-buttons/assets/dev/js/frontend/frontend.js":
/*!*******************************************************************!*\
  !*** ../modules/share-buttons/assets/dev/js/frontend/frontend.js ***!
  \*******************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      'share-buttons': () => __webpack_require__.e(/*! import() | share-buttons */ "share-buttons").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/share-buttons */ "../modules/share-buttons/assets/dev/js/frontend/widgets/share-buttons.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/slider/assets/dev/js/frontend/frontend.js":
/*!************************************************************!*\
  !*** ../modules/slider/assets/dev/js/frontend/frontend.js ***!
  \************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      'media-carousel': () => Promise.all(/*! import() | media-carousel */[__webpack_require__.e("assets_dev_js_frontend_base_handler_js-modules_slider_assets_dev_js_frontend_slider_js"), __webpack_require__.e("media-carousel")]).then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/media-carousel */ "../modules/slider/assets/dev/js/frontend/handlers/media-carousel.js")),
      'slider': () => Promise.all(/*! import() | slider */[__webpack_require__.e("assets_dev_js_frontend_base_handler_js-modules_slider_assets_dev_js_frontend_slider_js"), __webpack_require__.e("slider")]).then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/slider */ "../modules/slider/assets/dev/js/frontend/handlers/slider.js")) // eslint-disable-line quote-props
    };

    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/social-counter/assets/dev/js/frontend/frontend.js":
/*!********************************************************************!*\
  !*** ../modules/social-counter/assets/dev/js/frontend/frontend.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      'social-counter.box': () => __webpack_require__.e(/*! import() | social-counter */ "social-counter").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/social-counter */ "../modules/social-counter/assets/dev/js/frontend/widgets/social-counter.js")),
      'social-counter.side': () => __webpack_require__.e(/*! import() | social-counter */ "social-counter").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/social-counter */ "../modules/social-counter/assets/dev/js/frontend/widgets/social-counter.js")),
      'social-counter.tooltip': () => __webpack_require__.e(/*! import() | social-counter */ "social-counter").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/social-counter */ "../modules/social-counter/assets/dev/js/frontend/widgets/social-counter.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/social/assets/dev/js/frontend/frontend.js":
/*!************************************************************!*\
  !*** ../modules/social/assets/dev/js/frontend/frontend.js ***!
  \************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      facebook: () => __webpack_require__.e(/*! import() | facebook */ "facebook").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/facebook */ "../modules/social/assets/dev/js/frontend/handlers/facebook.js")),
      twitter: () => __webpack_require__.e(/*! import() | twitter */ "twitter").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/twitter */ "../modules/social/assets/dev/js/frontend/handlers/twitter.js")),
      pinterest: () => __webpack_require__.e(/*! import() | pinterest */ "pinterest").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/pinterest */ "../modules/social/assets/dev/js/frontend/handlers/pinterest.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/sticky/assets/dev/js/frontend/frontend.js":
/*!************************************************************!*\
  !*** ../modules/sticky/assets/dev/js/frontend/frontend.js ***!
  \************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _sticky = _interopRequireDefault(__webpack_require__(/*! ./modules/sticky */ "../modules/sticky/assets/dev/js/frontend/modules/sticky.js"));
class _default extends elementorModules.ViewModule {
  onInit() {
    super.onInit(...arguments);
    this.addHandlers();
  }
  addHandlers() {
    const handlers = {
      section: _sticky.default,
      container: _sticky.default,
      widget: _sticky.default
    };
    jQuery.each(handlers, (elementName, funcCallback) => {
      elementorFrontend.hooks.addAction(`frontend/element_ready/${elementName}`, funcCallback);
    });
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/sticky/assets/dev/js/frontend/modules/sticky.js":
/*!******************************************************************!*\
  !*** ../modules/sticky/assets/dev/js/frontend/modules/sticky.js ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, exports) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
class Sticky extends elementorModules.frontend.handlers.Base {
  __construct(settings) {
    super.__construct(settings);
    this.breakpoints = {
      mobile: elementorFrontend.config.breakpoints.md - 1,
      tablet: elementorFrontend.config.breakpoints.lg - 1
    };
    this.resetStickyGlobals();
    this.resetHeadroomGlobals();
    this.stickyControls = ['cms_sticky_scroll_in', 'cms_sticky_disable_on', 'cms_sticky_offset_top', 'cms_sticky_offset_top_tablet', 'cms_sticky_offset_top_mobile', 'cms_sticky_offset_bottom', 'cms_sticky_offset_bottom_tablet', 'cms_sticky_offset_bottom_mobile', 'cms_sticky_follow_scroll'];
    this.stickyTypingUpdate = elementorFrontend.debounce(this.stickyTypingUpdate, 2000);
    this.stickyDebounceUpdate = elementorFrontend.debounce(this.stickyDebounceUpdate, 300);
    this.stickyRefresh = elementorFrontend.debounce(this.stickyRefresh, 300);
    this.fixedControls = ['cms_sticky_fixed_style', 'cms_sticky_fixed_offset'];
    this.headroomDebounceActivate = elementorFrontend.debounce(this.headroomDebounceActivate, 300);
    this.headroomReactivate = elementorFrontend.debounce(this.headroomReactivate, 300);
    this.bodyPadding = 0;
  }
  resetStickyGlobals() {
    this.sticky = undefined;
    this.stickyActive = false;
    this.bodyPrevHeight = 0;
    this.$customStickyParent = undefined;
  }
  resetHeadroomGlobals() {
    this.headroom = undefined;
    this.headroomActive = false;
  }
  getDefaultSettings() {
    const blockClass = 'cmsmasters-block';
    const stickyClass = 'cmsmasters-sticky';
    const fixedClass = 'cmsmasters-fixed';
    const classes = {
      sticky: `${blockClass}-sticky`,
      fixed: `${blockClass}-fixed`,
      stickyActive: `${stickyClass}-active`,
      stickyShow: `${stickyClass}-show`,
      stickyHide: `${stickyClass}-hide`,
      stickyParentBody: `${stickyClass}-parent-body`,
      stickyParentCustom: `${stickyClass}-parent-custom`,
      stickyDisableNone: `${stickyClass}-disable-none`,
      stickyDisableMobile: `${stickyClass}-disable-mobile`,
      stickyDisableTablet: `${stickyClass}-disable-tablet`,
      fixedBodyTopGap: `${fixedClass}-body-top-gap-yes`,
      insideSection: 'elementor-section--handles-inside'
    };
    const selectors = {
      sticky: `.${classes.sticky}`,
      page: 'body',
      section: '.elementor-section',
      container: '.e-con'
    };
    return {
      classes,
      selectors
    };
  }
  onInit() {
    const {
      classes
    } = this.getSettings();
    this.elementType = this.$element.data('element_type');
    if (this.$element.hasClass(classes.sticky)) {
      this.stickyActivate();
    } else if (this.$element.hasClass(classes.fixed)) {
      this.headroomActivate();
    }
    if (window.ResizeObserver && this.stickyActive) {
      const $body = elementorFrontend.elements.$body;
      this.bodyPrevHeight = $body.height();
      const resizeObserver = new ResizeObserver(entries => {
        const currentHeight = entries[0].target.clientHeight;
        const difference = Math.abs(currentHeight - this.bodyPrevHeight);
        if (!this.$element.hasClass(classes.stickyActive) && 10 < difference) {
          this.stickyRefresh();
          this.bodyPrevHeight = currentHeight;
        }
      });
      resizeObserver.observe($body.get(0));
    }
  }
  onElementChange(propertyName, controlView) {
    const {
      classes
    } = this.getSettings();
    if (-1 !== ['cms_sticky_type'].indexOf(propertyName)) {
      const stickyType = controlView.getOption('elementSettingsModel').get(propertyName);
      if (this.stickyActive) {
        this.stickyDeactivate();
      } else if (this.headroomActive) {
        this.headroomDeactivate();
      }
      if ('sticky' === stickyType) {
        if (this.$element.hasClass(classes.sticky)) {
          this.stickyActivate();
          this.stickyRefresh();
        }
      } else if ('fixed' === stickyType) {
        if (this.$element.hasClass(classes.fixed)) {
          this.headroomActivate();
        }
      }
    }
    if (-1 !== ['cms_sticky_custom_selector'].indexOf(propertyName)) {
      this.stickyTypingUpdate();
    }
    if (-1 !== this.stickyControls.indexOf(propertyName)) {
      this.stickyDebounceUpdate();
    }
    if (-1 !== ['cms_sticky_fixed_top_gap'].indexOf(propertyName)) {
      this.headroomResetTopGap();
    }
    if (-1 !== this.fixedControls.indexOf(propertyName)) {
      this.headroomReactivate();
    }
  }
  stickyActivate() {
    const {
      classes
    } = this.getSettings();
    const stickyOptions = Object.assign({
      stickyClass: classes.stickyActive
    }, this.stickyUpdate(false));
    if (elementorFrontend.isEditMode() && !this.$element.hasClass(classes.insideSection)) {
      stickyOptions.onStart = () => this.$element.addClass(classes.insideSection);
      stickyOptions.onStop = () => this.$element.removeClass(classes.insideSection);
    }
    this.sticky = this.$element.hcSticky(stickyOptions);
    this.stickyActive = true;
  }
  stickyDebounceActivate() {
    const {
      classes
    } = this.getSettings();
    if (this.$element.hasClass(classes.sticky)) {
      this.stickyActivate();
    }
  }
  stickyUpdate() {
    let update = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
    if (update && !this.stickyActive) {
      return;
    }
    const {
      cms_sticky_offset_top: offsetTop,
      cms_sticky_offset_bottom: offsetBottom
    } = this.getElementSettings();
    const stickyOptions = {};
    stickyOptions.stickTo = this.getStickyParent().get(0);
    if (elementorFrontend.elements.$wpAdminBar.length) {
      const adminBarHeight = elementorFrontend.elements.$wpAdminBar.height();
      stickyOptions.top = adminBarHeight;
    }
    if (offsetTop) {
      if (stickyOptions.top) {
        stickyOptions.top += offsetTop;
      } else {
        stickyOptions.top = offsetTop;
      }
    }
    if (offsetBottom) {
      stickyOptions.bottomEnd = offsetBottom;
    }
    stickyOptions.responsive = this.getStickyResponsiveOptions();
    if (!this.$element.hasClass('cmsmasters-sticky-follow-scroll-yes')) {
      stickyOptions.followScroll = false;
    }
    if (update) {
      this.sticky.hcSticky('update', stickyOptions);
    } else {
      return stickyOptions;
    }
  }
  stickyDebounceUpdate() {
    this.stickyUpdate();
  }
  stickyTypingUpdate() {
    this.stickyUpdate();
  }
  getStickyResponsiveOptions() {
    const {
      classes
    } = this.getSettings();
    const {
      cms_sticky_offset_top_tablet: offsetTopTablet,
      cms_sticky_offset_top_mobile: offsetTopMobile,
      cms_sticky_offset_bottom_tablet: offsetBottomTablet,
      cms_sticky_offset_bottom_mobile: offsetBottomMobile
    } = this.getElementSettings();
    const responsive = {};
    if (!this.$element.hasClass(classes.stickyDisableNone)) {
      if (this.$element.hasClass(classes.stickyDisableMobile)) {
        responsive[this.breakpoints.mobile] = {
          disable: true
        };
      } else if (this.$element.hasClass(classes.stickyDisableTablet)) {
        responsive[this.breakpoints.tablet] = {
          disable: true
        };
      }
    }
    if (offsetTopTablet || offsetBottomTablet) {
      if (!responsive[this.breakpoints.tablet]) {
        responsive[this.breakpoints.tablet] = {};
      }
      if (offsetTopTablet) {
        responsive[this.breakpoints.tablet].top = offsetTopTablet;
      }
      if (offsetBottomTablet) {
        responsive[this.breakpoints.tablet].bottom = offsetBottomTablet;
      }
    }
    if (offsetTopMobile || offsetBottomMobile) {
      if (!responsive[this.breakpoints.mobile]) {
        responsive[this.breakpoints.mobile] = {};
      }
      if (offsetTopMobile) {
        responsive[this.breakpoints.mobile].top = offsetTopMobile;
      }
      if (offsetBottomMobile) {
        responsive[this.breakpoints.mobile].bottom = offsetBottomMobile;
      }
    }
    return responsive;
  }
  stickyRefresh() {
    if (!this.stickyActive) {
      return;
    }
    this.sticky.hcSticky('refresh');
  }
  getStickyParent() {
    if (this.checkStickyCustomParent()) {
      return this.$customStickyParent;
    }
    const {
      classes,
      selectors
    } = this.getSettings();
    let $parent = this.$element.parent();
    switch (this.elementType) {
      case 'section':
        $parent = this.$element.closest(selectors.page);
        break;
      case 'container':
        $parent = this.$element.closest(selectors.page);
        break;
      case 'widget':
        /* if (
        	this.$element.hasClass( classes.stickyParentBody ) ||
        	( this.$element.hasClass( classes.stickyParentDefault ) && 0 === this.$element.siblings().length )
        ) { */
        if (this.$element.hasClass(classes.stickyParentBody)) {
          $parent = this.$element.closest(selectors.page);
        } else {
          const container = this.$element.closest(selectors.container);
          if (0 !== this.$element.closest(selectors.section).length) {
            $parent = this.$element.closest(selectors.section);
          } else if (0 !== container.length) {
            const containerParent = this.$element.closest(selectors.container + '.e-parent');
            if (0 !== containerParent.length) {
              $parent = containerParent;
            } else {
              $parent = container;
            }
          }
        }
        break;
    }
    return $parent;
  }
  checkStickyCustomParent() {
    const {
      cms_sticky_custom_selector: customSelector
    } = this.getElementSettings();
    const $customParent = this.$element.closest(customSelector);
    if (!$customParent.length) {
      return false;
    }
    this.$customStickyParent = $customParent;
    return true;
  }
  stickyDeactivate() {
    if (!this.sticky || !this.stickyActive) {
      return;
    }
    this.sticky.hcSticky('destroy');
    this.resetStickyGlobals();
  }
  headroomActivate() {
    const {
      classes: settingsClasses
    } = this.getSettings();
    const fixedOptions = {
      tolerance: 10,
      classes: {
        initial: `${settingsClasses.fixed} headroom_animated`
      }
    };
    if (elementorFrontend.isEditMode() && !this.$element.hasClass(settingsClasses.insideSection)) {
      fixedOptions.classes.initial += ` ${settingsClasses.insideSection}`;
    }
    const {
      cms_sticky_fixed_style: fixedStyle,
      cms_sticky_fixed_offset: offset
    } = this.getElementSettings();
    switch (fixedStyle) {
      // case 'back':
      // 	fixedOptions.classes.pinned = 'animate__backInDown';
      // 	fixedOptions.classes.unpinned = 'animate__backOutUp';

      // 	break;
      // case 'bounce':
      // 	fixedOptions.classes.pinned = 'animate__bounceInDown';
      // 	fixedOptions.classes.unpinned = 'animate__bounceOutUp';

      // 	break;
      // case 'fadeIn':
      // 	fixedOptions.classes.pinned = 'animate__fadeInDown';
      // 	fixedOptions.classes.unpinned = 'animate__fadeOutUp';

      // 	break;
      // case 'fadeOut':
      // 	fixedOptions.classes.pinned = 'animate__fadeInUp';
      // 	fixedOptions.classes.unpinned = 'animate__fadeOutDown';

      // 	break;
      // case 'flip':
      // 	fixedOptions.classes.pinned = 'animate__flipInX';
      // 	fixedOptions.classes.unpinned = 'animate__flipOutX';

      // 	break;
      // case 'lightSpeed':
      // 	fixedOptions.classes.pinned = 'animate__lightSpeedInLeft';
      // 	fixedOptions.classes.unpinned = 'animate__lightSpeedOutRight';

      // 	break;
      // case 'zoom':
      // 	fixedOptions.classes.pinned = 'animate__zoomIn';
      // 	fixedOptions.classes.unpinned = 'animate__zoomOut';

      // 	break;
      // case 'zoomDown':
      // 	fixedOptions.classes.pinned = 'animate__zoomInDown';
      // 	fixedOptions.classes.unpinned = 'animate__zoomOutUp';

      // 	break;
      // case 'zoomUp':
      // 	fixedOptions.classes.pinned = 'animate__zoomInUp';
      // 	fixedOptions.classes.unpinned = 'animate__zoomOutUp';

      // 	break;
      // case 'slide':
      // 	fixedOptions.classes.pinned = 'animate__slideInDown';
      // 	fixedOptions.classes.unpinned = 'animate__slideOutUp';

      // 	break;
      case 'swing':
        fixedOptions.classes.pinned = 'headroom-swing-in';
        fixedOptions.classes.unpinned = 'headroom-swing-out';
        break;
      case 'flip':
        fixedOptions.classes.pinned = 'headroom-flip-in';
        fixedOptions.classes.unpinned = 'headroom-flip-out';
        break;
      case 'bounce':
        fixedOptions.classes.pinned = 'headroom-bounce-in';
        fixedOptions.classes.unpinned = 'headroom-bounce-out';
        break;
      default:
        fixedOptions.classes.pinned = 'headroom-slide-in';
        fixedOptions.classes.unpinned = 'headroom-slide-out';
    }
    fixedOptions.offset = '' !== offset ? offset : this.$element.outerHeight(true);
    if (elementorFrontend.elements.$wpAdminBar.length) {
      const adminBarHeight = elementorFrontend.elements.$wpAdminBar.height();
      this.$element.css('top', adminBarHeight);
    }
    this.headroomResetTopGap();
    this.headroom = this.$element.headroom(fixedOptions);
    this.headroomActive = true;
  }
  headroomDebounceActivate() {
    this.headroomActivate();
  }
  headroomResetTopGap() {
    const {
      classes,
      selectors
    } = this.getSettings();
    this.bodyPadding = this.$element.hasClass(classes.fixedBodyTopGap) ? this.$element.outerHeight(true) : 'inherit';
    this.$element.closest(selectors.page).css('padding-top', this.bodyPadding);
  }
  headroomDeactivate() {
    if (!this.headroom || !this.headroomActive) {
      return;
    }
    this.headroom.headroom('destroy');
    if ('inherit' !== this.bodyPadding) {
      const {
        selectors
      } = this.getSettings();
      this.bodyPadding = 'inherit';
      this.$element.closest(selectors.page).css('padding-top', this.bodyPadding);
    }
    this.resetHeadroomGlobals();
  }
  headroomReactivate() {
    this.headroomDeactivate();
    this.headroomDebounceActivate();
  }
  onDestroy() {
    this.stickyDeactivate();
    this.headroomDeactivate();
  }
}
var _default = $scope => {
  elementorFrontend.elementsHandler.addHandler(Sticky, {
    $element: $scope
  });
};
exports["default"] = _default;

/***/ }),

/***/ "../modules/table-of-contents/assets/dev/js/frontend/frontend.js":
/*!***********************************************************************!*\
  !*** ../modules/table-of-contents/assets/dev/js/frontend/frontend.js ***!
  \***********************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      'table-of-contents': () => __webpack_require__.e(/*! import() | table-of-contents */ "table-of-contents").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/table-of-contents */ "../modules/table-of-contents/assets/dev/js/frontend/widgets/table-of-contents.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/tabs/assets/dev/js/frontend/frontend.js":
/*!**********************************************************!*\
  !*** ../modules/tabs/assets/dev/js/frontend/frontend.js ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      tabs: () => __webpack_require__.e(/*! import() | tabs */ "tabs").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/tabs */ "../modules/tabs/assets/dev/js/frontend/handlers/tabs.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/template-pages/assets/dev/js/frontend/frontend.js":
/*!********************************************************************!*\
  !*** ../modules/template-pages/assets/dev/js/frontend/frontend.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      'post-excerpt': () => __webpack_require__.e(/*! import() | post-excerpt */ "post-excerpt").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/post-excerpt */ "../modules/template-pages/assets/dev/js/frontend/handlers/post-excerpt.js")),
      'archive-description': () => __webpack_require__.e(/*! import() | post-excerpt */ "post-excerpt").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/post-excerpt */ "../modules/template-pages/assets/dev/js/frontend/handlers/post-excerpt.js")),
      'post-navigation-fixed': () => __webpack_require__.e(/*! import() | post-navigation-fixed */ "post-navigation-fixed").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/post-navigation-fixed */ "../modules/template-pages/assets/dev/js/frontend/handlers/post-navigation-fixed.js")),
      'post-media': () => Promise.all(/*! import() | post-media */[__webpack_require__.e("assets_dev_js_frontend_base_handler_js-modules_slider_assets_dev_js_frontend_slider_js"), __webpack_require__.e("post-media")]).then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/post-media */ "../modules/template-pages/assets/dev/js/frontend/handlers/post-media.js")),
      'post-comments.facebook': () => __webpack_require__.e(/*! import() | facebook */ "facebook").then(__webpack_require__.bind(__webpack_require__, /*! cmsmasters-social-module/frontend/handlers/facebook */ "../modules/social/assets/dev/js/frontend/handlers/facebook.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/template-sections/assets/dev/js/frontend/frontend.js":
/*!***********************************************************************!*\
  !*** ../modules/template-sections/assets/dev/js/frontend/frontend.js ***!
  \***********************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      'search': () => __webpack_require__.e(/*! import() | search */ "search").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/search */ "../modules/template-sections/assets/dev/js/frontend/handlers/search.js")),
      // eslint-disable-line quote-props
      // 'search-advanced': () => import( /* webpackChunkName: 'search-advanced' */ './handlers/search-advanced' ),
      'offcanvas': () => __webpack_require__.e(/*! import() | off-canvas */ "off-canvas").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/off-canvas */ "../modules/template-sections/assets/dev/js/frontend/handlers/off-canvas.js")),
      // eslint-disable-line quote-props
      'nav-menu': () => __webpack_require__.e(/*! import() | nav-menu */ "nav-menu").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/nav-menu */ "../modules/template-sections/assets/dev/js/frontend/handlers/nav-menu.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/testimonials/assets/dev/js/frontend/frontend.js":
/*!******************************************************************!*\
  !*** ../modules/testimonials/assets/dev/js/frontend/frontend.js ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      'testimonials-slider': () => Promise.all(/*! import() | testimonials-slider */[__webpack_require__.e("assets_dev_js_frontend_base_handler_js-modules_slider_assets_dev_js_frontend_slider_js"), __webpack_require__.e("testimonials-slider")]).then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/testimonials-slider */ "../modules/testimonials/assets/dev/js/frontend/widgets/testimonials-slider.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/theme/assets/dev/js/frontend/frontend.js":
/*!***********************************************************!*\
  !*** ../modules/theme/assets/dev/js/frontend/frontend.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      'theme-add-to-cart': () => __webpack_require__.e(/*! import() | theme-add-to-cart */ "theme-add-to-cart").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/theme-add-to-cart */ "../modules/theme/assets/dev/js/frontend/widgets/theme-add-to-cart.js")),
      'theme-builders-slider': () => Promise.all(/*! import() | theme-builders-slider */[__webpack_require__.e("assets_dev_js_frontend_base_handler_js-modules_slider_assets_dev_js_frontend_slider_js"), __webpack_require__.e("theme-builders-slider")]).then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/theme-builders-slider */ "../modules/theme/assets/dev/js/frontend/widgets/theme-builders-slider.js")),
      'theme-multiple-search': () => __webpack_require__.e(/*! import() | theme-multiple-search */ "theme-multiple-search").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/theme-multiple-search */ "../modules/theme/assets/dev/js/frontend/widgets/theme-multiple-search.js")),
      'theme-my-account': () => __webpack_require__.e(/*! import() | theme-my-account */ "theme-my-account").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/theme-my-account */ "../modules/theme/assets/dev/js/frontend/widgets/theme-my-account.js")),
      'theme-product-chooses': () => __webpack_require__.e(/*! import() | theme-product-chooses */ "theme-product-chooses").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/theme-product-chooses */ "../modules/theme/assets/dev/js/frontend/widgets/theme-product-chooses.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/timetable/assets/dev/js/frontend/frontend.js":
/*!***************************************************************!*\
  !*** ../modules/timetable/assets/dev/js/frontend/frontend.js ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      timetable: () => __webpack_require__.e(/*! import() | timetable */ "timetable").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/timetable */ "../modules/timetable/assets/dev/js/frontend/handlers/timetable.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/toggles/assets/dev/js/frontend/frontend.js":
/*!*************************************************************!*\
  !*** ../modules/toggles/assets/dev/js/frontend/frontend.js ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      toggles: () => __webpack_require__.e(/*! import() | toggles */ "toggles").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/toggles */ "../modules/toggles/assets/dev/js/frontend/handlers/toggles.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/weather/assets/dev/js/frontend/frontend.js":
/*!*************************************************************!*\
  !*** ../modules/weather/assets/dev/js/frontend/frontend.js ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
class _default extends _module.default {
  initWidgets() {
    const widgets = {
      'weather.line': () => __webpack_require__.e(/*! import() | weather */ "weather").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/weather */ "../modules/weather/assets/dev/js/frontend/widgets/weather.js")),
      'weather.standard': () => __webpack_require__.e(/*! import() | weather */ "weather").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/weather */ "../modules/weather/assets/dev/js/frontend/widgets/weather.js"))
    };
    return widgets;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/woocommerce/assets/dev/js/frontend/entry.js":
/*!**************************************************************!*\
  !*** ../modules/woocommerce/assets/dev/js/frontend/entry.js ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _document = _interopRequireDefault(__webpack_require__(/*! cmsmasters-blog-module/frontend/document */ "../modules/blog/assets/dev/js/frontend/document.js"));
class _default extends _document.default {
  getElementMessage() {
    const $ulProducts = jQuery('<ul>', {
      class: 'products columns-1',
      html: jQuery('<li>', {
        class: 'product'
      })
    });
    this.$element.wrap($ulProducts);
    return this.$element.parent().parent();
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/woocommerce/assets/dev/js/frontend/frontend.js":
/*!*****************************************************************!*\
  !*** ../modules/woocommerce/assets/dev/js/frontend/frontend.js ***!
  \*****************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _module = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/module */ "../assets/dev/js/frontend/base/module.js"));
var _entry = _interopRequireDefault(__webpack_require__(/*! ./entry */ "../modules/woocommerce/assets/dev/js/frontend/entry.js"));
class _default extends _module.default {
  onInit() {
    super.onInit();
    elementorFrontend.hooks.addAction('elementor/frontend/documents-manager/init-classes', this.addEntryClass.bind(this));
  }
  initWidgets() {
    const widgets = {
      'woo-archive-description': () => __webpack_require__.e(/*! import() | post-excerpt */ "post-excerpt").then(__webpack_require__.bind(__webpack_require__, /*! cmsmasters-template-pages-module/frontend/handlers/post-excerpt */ "../modules/template-pages/assets/dev/js/frontend/handlers/post-excerpt.js")),
      'woo-archive-products': () => Promise.all(/*! import() | products */[__webpack_require__.e("assets_dev_js_frontend_base_handler_js-modules_ajax-widget_assets_dev_js_frontend_ajax-widget-86c5d3"), __webpack_require__.e("products")]).then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/products */ "../modules/woocommerce/assets/dev/js/frontend/widgets/products.js")),
      'woo-cart': () => __webpack_require__.e(/*! import() | cart */ "cart").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/cart */ "../modules/woocommerce/assets/dev/js/frontend/widgets/cart.js")),
      'woo-cart-page': () => __webpack_require__.e(/*! import() | cart-page */ "cart-page").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/cart-page */ "../modules/woocommerce/assets/dev/js/frontend/widgets/cart-page.js")),
      'woo-my-account': () => __webpack_require__.e(/*! import() | my-account */ "my-account").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/my-account */ "../modules/woocommerce/assets/dev/js/frontend/widgets/my-account.js")),
      'woo-notices': () => __webpack_require__.e(/*! import() | notices */ "notices").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/notices */ "../modules/woocommerce/assets/dev/js/frontend/widgets/notices.js")),
      'woo-checkout': () => __webpack_require__.e(/*! import() | checkout */ "checkout").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/checkout */ "../modules/woocommerce/assets/dev/js/frontend/widgets/checkout.js")),
      'woo-purchase-summary': () => __webpack_require__.e(/*! import() | purchase-summary */ "purchase-summary").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/purchase-summary */ "../modules/woocommerce/assets/dev/js/frontend/widgets/purchase-summary.js")),
      'woo-product-add-to-cart-button': () => __webpack_require__.e(/*! import() | add-to-cart-button */ "add-to-cart-button").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/add-to-cart-button */ "../modules/woocommerce/assets/dev/js/frontend/widgets/add-to-cart-button.js")),
      'woo-product-add-to-cart': () => __webpack_require__.e(/*! import() | add-to-cart */ "add-to-cart").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/add-to-cart */ "../modules/woocommerce/assets/dev/js/frontend/widgets/add-to-cart.js")),
      'woo-product-data-tabs': () => __webpack_require__.e(/*! import() | tabs */ "tabs").then(__webpack_require__.bind(__webpack_require__, /*! cmsmasters-tabs-module/frontend/handlers/tabs */ "../modules/tabs/assets/dev/js/frontend/handlers/tabs.js")),
      'woo-product-images.anchor': () => __webpack_require__.e(/*! import() | product-images-anchor */ "product-images-anchor").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/product-images-anchor */ "../modules/woocommerce/assets/dev/js/frontend/widgets/product-images-anchor.js")),
      'woo-product-images.grid': () => __webpack_require__.e(/*! import() | product-images-grid */ "product-images-grid").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/product-images-grid */ "../modules/woocommerce/assets/dev/js/frontend/widgets/product-images-grid.js")),
      'woo-product-images.slider': () => Promise.all(/*! import() | product-images-slider */[__webpack_require__.e("assets_dev_js_frontend_base_handler_js-modules_slider_assets_dev_js_frontend_slider_js"), __webpack_require__.e("product-images-slider")]).then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/product-images-slider */ "../modules/woocommerce/assets/dev/js/frontend/widgets/product-images-slider.js")),
      'woo-product-related': () => Promise.all(/*! import() | product-related */[__webpack_require__.e("assets_dev_js_frontend_base_handler_js-modules_slider_assets_dev_js_frontend_slider_js"), __webpack_require__.e("product-related")]).then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/product-related */ "../modules/woocommerce/assets/dev/js/frontend/widgets/product-related.js")),
      'woo-product-short-description': () => __webpack_require__.e(/*! import() | post-excerpt */ "post-excerpt").then(__webpack_require__.bind(__webpack_require__, /*! cmsmasters-template-pages-module/frontend/handlers/post-excerpt */ "../modules/template-pages/assets/dev/js/frontend/handlers/post-excerpt.js")),
      'woo-products': () => Promise.all(/*! import() | products */[__webpack_require__.e("assets_dev_js_frontend_base_handler_js-modules_ajax-widget_assets_dev_js_frontend_ajax-widget-86c5d3"), __webpack_require__.e("products")]).then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/products */ "../modules/woocommerce/assets/dev/js/frontend/widgets/products.js")),
      'wpclever-smart-wishlist-counter': () => __webpack_require__.e(/*! import() | wpclever-smart-wishlist-counter */ "wpclever-smart-wishlist-counter").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/wpclever-smart-wishlist-counter */ "../modules/woocommerce/assets/dev/js/frontend/widgets/wpclever-smart-wishlist-counter.js")),
      'wpclever-smart-compare-counter': () => __webpack_require__.e(/*! import() | wpclever-smart-compare-counter */ "wpclever-smart-compare-counter").then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/wpclever-smart-compare-counter */ "../modules/woocommerce/assets/dev/js/frontend/widgets/wpclever-smart-compare-counter.js")),
      'woo-product-categories-slider': () => Promise.all(/*! import() | product-categories-slider */[__webpack_require__.e("assets_dev_js_frontend_base_handler_js-modules_slider_assets_dev_js_frontend_slider_js"), __webpack_require__.e("product-categories-slider")]).then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/product-categories-slider */ "../modules/woocommerce/assets/dev/js/frontend/widgets/product-categories-slider.js")),
      'woo-products-slider': () => Promise.all(/*! import() | products-slider */[__webpack_require__.e("assets_dev_js_frontend_base_handler_js-modules_slider_assets_dev_js_frontend_slider_js"), __webpack_require__.e("products-slider")]).then(__webpack_require__.bind(__webpack_require__, /*! ./widgets/products-slider */ "../modules/woocommerce/assets/dev/js/frontend/widgets/products-slider.js"))
    };
    return widgets;
  }
  addEntryClass(documentsManager) {
    documentsManager.addDocumentClass('cmsmasters_product_entry', _entry.default);
  }
}
exports["default"] = _default;

/***/ }),

/***/ "@wordpress/i18n":
/*!**************************!*\
  !*** external "wp.i18n" ***!
  \**************************/
/***/ ((module) => {

"use strict";
module.exports = wp.i18n;

/***/ }),

/***/ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js":
/*!***********************************************************************!*\
  !*** ../node_modules/@babel/runtime/helpers/interopRequireDefault.js ***!
  \***********************************************************************/
/***/ ((module) => {

function _interopRequireDefault(obj) {
  return obj && obj.__esModule ? obj : {
    "default": obj
  };
}
module.exports = _interopRequireDefault, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ var __webpack_exports__ = (__webpack_exec__("../assets/dev/js/frontend/frontend.js"));
/******/ }
]);
//# sourceMappingURL=frontend.js.map