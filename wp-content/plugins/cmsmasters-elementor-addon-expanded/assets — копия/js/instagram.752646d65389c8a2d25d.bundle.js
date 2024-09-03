/*! cmsmasters-elementor-addon - v1.11.6 - 05-03-2024 */
"use strict";
(self["webpackChunkcmsmasters_elementor_addon"] = self["webpackChunkcmsmasters_elementor_addon"] || []).push([["instagram"],{

/***/ "../modules/ajax-widget/assets/dev/js/frontend/ajax-caching-manager.js":
/*!*****************************************************************************!*\
  !*** ../modules/ajax-widget/assets/dev/js/frontend/ajax-caching-manager.js ***!
  \*****************************************************************************/
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
/**
 * Object cache manager.
 *
 * @since 1.0.0
 */
class _default {
  constructor() {
    this.buffer = {};
  }

  /**
   * @param {object} params
   * @param {*} value
   * @param {number} expiresMs
   */
  set(params, value) {
    let expiresMs = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
    const id = this.toID(params);
    this.buffer[id] = JSON.stringify(value, null, '');
    if (expiresMs) {
      setTimeout(() => {
        return this.remove(id);
      }, expiresMs);
    }
  }

  /**
   * @param {object} params Any data.
   *
   * @since 1.0.0
   *
   * @returns {string} Serialized object
   */
  toID(params) {
    if ('string' === typeof params) {
      return params;
    }
    return jQuery.param(params);
  }

  /**
   * @param {object} params
   * @returns {boolean}
   */
  remove(params) {
    const id = this.toID(params);
    if (!this.buffer[id]) {
      return false;
    }
    this.buffer[id] = null;
    delete this.buffer[id];
    return true;
  }

  /**
   * @param {object} params
   *
   * @returns {*}
   */
  get(params) {
    const id = this.toID(params);
    try {
      return JSON.parse(this.buffer[id]);
    } catch (err) {
      return this.buffer[id] || null;
    }
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/ajax-widget/assets/dev/js/frontend/ajax-widget.js":
/*!********************************************************************!*\
  !*** ../modules/ajax-widget/assets/dev/js/frontend/ajax-widget.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _ajaxCachingManager = _interopRequireDefault(__webpack_require__(/*! ./ajax-caching-manager */ "../modules/ajax-widget/assets/dev/js/frontend/ajax-caching-manager.js"));
class _default extends elementorModules.ViewModule {
  __construct() {
    let instanceParams = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    this.widget = instanceParams.widget;
    this.ajaxVarsDefault = instanceParams.ajaxVarsDefault || {};
    super.__construct(instanceParams);
  }
  getDefaultSettings() {
    this.cachingManager = new _ajaxCachingManager.default();
    const settings = super.getDefaultSettings(...arguments);
    return Object.assign(settings, {
      classes: {
        loading: '--loading',
        animationLoading: '--loading-animation'
      },
      response: null,
      responseData: null,
      ajaxVars: this.cloneObj(this.ajaxVarsDefault),
      ajaxVarsSafe: this.cloneObj(this.ajaxVarsDefault),
      requestFree: true,
      cacheAllow: false,
      animationLoading: true
    });
  }
  cloneObj(obj) {
    return JSON.parse(JSON.stringify(obj));
  }

  /**
   * @since 1.0.0
   *
   * @returns {Promise.<object>}
   */
  request() {
    return new Promise((resolve, reject) => {
      if (!this.isRequestFree()) {
        return;
      }
      const isCacheAllow = this.isCacheAllow();
      this.setSettings('requestFree', false);
      const parameters = this.getAjaxParameters();
      if (isCacheAllow) {
        const cache = this.getCache(parameters);
        if (cache) {
          this.setSettings('responseData', cache);
          this.responseSuccess();
          resolve(cache);
          this.ajaxReset();
          return;
        }
      }
      const jqXHR = jQuery.ajax(parameters);
      this.ajaxStart();
      jqXHR.done(response => {
        this.setSettings('response', response);
        this.setSettings('responseData', response.data);
        if (this.isValidResponse()) {
          if (isCacheAllow) {
            this.setCache(response.data);
          }
          resolve(response.data);
          this.responseSuccess();
        } else {
          this.responseFail();
        }
      });
      jqXHR.fail(() => {
        this.setSettings('response', jqXHR.responseJSON);
        if (jqXHR.responseJSON && jqXHR.responseJSON.data) {
          this.setSettings('responseData', jqXHR.responseJSON.data);
        }
        reject(jqXHR);
        this.responseFail();
      });
      jqXHR.always(() => {
        this.ajaxReset();
        this.ajaxFinish();
      });
    });
  }
  isRequestFree() {
    return this.getSettings('requestFree');
  }
  getAjaxParameters() {
    let documentId = this.widget.$element.data().documentId;
    if (!documentId) {
      documentId = elementorFrontendConfig.post.id;
    }
    if (!documentId) {
      documentId = this.widget.$element.parents('.elementor[data-elementor-id]').data('elementor-id');
    }
    const parameters = {
      url: elementorCmsmastersFrontendConfig.ajaxurl,
      type: 'POST',
      dataType: 'json',
      data: {
        _ajax_nonce: elementorCmsmastersFrontendConfig.nonces.ajax_widget,
        action: `ajax_widget_${this.widget.getWidgetType()}`,
        ajax_vars: this.getAjaxVars(),
        document_id: documentId,
        widget_id: this.widget.getID()
      }
    };
    if (elementorFrontend.isEditMode()) {
      const elementData = elementorFrontend.config.elements.data[this.widget.getModelCID()];
      if (elementData) {
        const settings = elementData.toJSON({
          remove: ['default', 'editSettings', 'defaultEditSettings']
        });
        parameters.data.element_data = {
          id: this.widget.getID(),
          elType: this.widget.getElementType(),
          widgetType: this.widget.getWidgetType(),
          elements: [],
          isInner: false,
          settings
        };
      }
    }
    this.trigger('parameters', parameters);
    return parameters;
  }
  getAjaxVars() {
    return this.getSettings('ajaxVars');
  }
  getCache() {
    let parameters = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
    return this.cachingManager.get(this.getCacheParameters(parameters));
  }
  getCacheParameters() {
    let parameters = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
    if (!parameters) {
      parameters = this.getAjaxParameters();
    }
    parameters = this.cloneObj(parameters);
    delete parameters.data.element_data;
    this.trigger('parameters/cache', parameters);
    return parameters;
  }
  responseSuccess() {
    this.successQueryArgs();
    this.trigger('response/success');
  }
  successQueryArgs() {
    this.setSettings('ajaxVarsSafe', this.cloneObj(this.getSettings('ajaxVars')));
  }
  ajaxReset() {
    this.setSettings('requestFree', true);
    this.trigger('ajaxReset');
  }
  ajaxStart() {
    const {
      classes
    } = this.getSettings();
    this.widget.$element.addClass(classes.loading);
    if (this.isAnimationLoading()) {
      this.widget.$element.addClass(classes.animationLoading);
    }
    this.trigger('ajaxReset');
  }
  isValidResponse() {
    const response = this.getResponse();
    return response && response.success;
  }
  getResponse() {
    return this.getSettings('response');
  }
  setCache(data) {
    let parameters = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
    this.cachingManager.set(this.getCacheParameters(parameters), data);
  }
  responseFail() {
    this.failureQueryArgs();
    this.trigger('response/fail');
  }
  failureQueryArgs() {
    this.setSettings('ajaxVars', this.cloneObj(this.getSettings('ajaxVarsSafe')));
  }
  ajaxFinish() {
    const {
      classes
    } = this.getSettings();
    this.widget.$element.removeClass(classes.loading);
    if (this.isAnimationLoading()) {
      this.widget.$element.removeClass(classes.animationLoading);
    }
  }
  setAjaxVars(key, data) {
    key = `.${key}`;
    return this.setSettings(`ajaxVars${key}`, data);
  }
  getResponseData() {
    return this.getSettings('responseData');
  }
  isCacheAllow() {
    return Boolean(this.getSettings('cacheAllow'));
  }
  isAnimationLoading() {
    return Boolean(this.getSettings('animationLoading'));
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/instagram/assets/dev/js/frontend/widgets/instagram.js":
/*!************************************************************************!*\
  !*** ../modules/instagram/assets/dev/js/frontend/widgets/instagram.js ***!
  \************************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _ajaxWidget = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/ajax-widget/assets/dev/js/frontend/ajax-widget */ "../modules/ajax-widget/assets/dev/js/frontend/ajax-widget.js"));
var _handler = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/handler */ "../assets/dev/js/frontend/base/handler.js"));
var _slider = _interopRequireDefault(__webpack_require__(/*! cmsmasters-slider-module/frontend/slider */ "../modules/slider/assets/dev/js/frontend/slider.js"));
const utils = __webpack_require__(/*! cmsmasters-helpers/utils */ "../assets/dev/js/helpers/utils.js");
class Instagram extends _handler.default {
  __construct() {
    this.sectionActivated = this.sectionActivated.bind(this);
    this.onResize = utils.debounce(this.onResize.bind(this));
    super.__construct(...arguments);
  }
  getDefaultSettings() {
    const widgetSelector = '.elementor-widget-cmsmasters-instagram';
    const itemClass = 'cmsmasters-instagram-lightbox__item';
    const classes = {
      lightbox: {
        root: 'cmsmasters-instagram-lightbox',
        loader: 'cmsmasters-instagram-lightbox--loader',
        loaded: 'cmsmasters-instagram-lightbox--loaded',
        item: itemClass,
        itemMedia: `${itemClass}__media`
      }
    };
    return {
      classes,
      selectors: {
        widget: widgetSelector,
        links: `${widgetSelector}__link`
      }
    };
  }
  getDefaultElements() {
    const settings = this.getSettings();
    const $lightboxRoot = jQuery(`#${this.getLightBoxID()}`);
    const $lightboxSwiperContainer = $lightboxRoot.find('.dialog-message > .cmsmasters-swiper-container');
    return {
      $item: this.findElement(`${settings.selectors.widget}__item`),
      $loadMore: this.findElement(`${settings.selectors.widget}__load-more-button`),
      $loadMoreWrap: this.findElement(`${settings.selectors.widget}__load-more-button-wrapper`),
      $templateSlider: this.findElement(`#tmpl-${this.getWidgetType()}-${this.getID()}`),
      $wrapItems: this.findElement(`${settings.selectors.widget}__items`),
      $wrapper: this.findElement(`${settings.selectors.widget}__wrapper`),
      $links: this.findElement(settings.selectors.links),
      $linksImages: this.findElement(`${settings.selectors.widget}__image img`),
      lightbox: {
        $root: $lightboxRoot,
        $swiperContainer: $lightboxSwiperContainer,
        $slides: $lightboxSwiperContainer.find('> .swiper-wrapper > .swiper-slide'),
        $item: $lightboxRoot.find(`.${settings.classes.lightbox.item}`),
        $itemContent: $lightboxRoot.find(`.${settings.classes.lightbox.item}__content`)
      }
    };
  }
  bindEvents() {
    super.bindEvents(...arguments);
    elementorFrontend.elements.$window.on('resize', this.onResize);
    this.bindElementChange('lightbox_sidebar_width', utils.debounce(this.updatePerfectScroll.bind(this)));
    this.elements.$loadMore.on('click', this.onLoadMoreClick.bind(this));
    this.elements.$wrapItems.on('click', '.cmsmasters-instagram-lightbox-trigger', event => {
      event.preventDefault();
      this.showLightbox(event.currentTarget.getAttribute('data-id'));
    });
    this.bindElementChange(['skin', 'columns', 'masonry'], this.initMasonry.bind(this));
  }
  onInit() {
    super.onInit();
    this.initAjaxWidget();
    this.initMasonry();
    this.onEdit();
  }
  onEdit() {
    if (!elementorFrontend.isEditMode()) {
      return;
    }
    elementor.channels.editor.on('section:activated', this.sectionActivated);
  }
  sectionActivated(sectionName, editor) {
    const elementsData = elementorFrontend.config.elements.data[this.getModelCID()];
    const editedElement = editor.getOption('editedElementView');
    if (!elementsData || this.lightbox && this.lightbox.isVisible() || elementsData.get('widgetType') !== editedElement.model.get('widgetType') || -1 === ['section_lightbox_style', 'section_lightbox_comments_style'].indexOf(sectionName)) {
      return;
    }
    this.elements.$links.eq(0).trigger('click');
  }
  updatePerfectScroll() {
    this.elements.lightbox.$itemContent.each((index, item) => {
      new PerfectScrollbar(item);
    });
  }
  getColumns() {
    return Number(this.getCurrentDeviceSetting('columns'));
  }
  initAjaxWidget() {
    this.ajaxWidget = new _ajaxWidget.default({
      widget: this,
      page: 1
    });
  }
  getImgPlaceholder() {
    const settings = this.getSettings();
    return jQuery('<div />', {
      class: `${settings.classes.lightbox.item}__placeholder`
    });
  }
  getUrlByName(username) {
    return `https://www.instagram.com/${username}`;
  }
  getSlide(post) {
    const settings = this.getSettings();
    const userData = this.getUserData();
    const $slide = jQuery('<div />', {
      class: 'swiper-slide',
      'data-id': post.id,
      'data-media-type': post.media_type,
      html: jQuery('<div />', {
        class: settings.classes.lightbox.item,
        html: jQuery('<div />', {
          class: `${settings.classes.lightbox.item}__inner`,
          html: [jQuery('<div />', {
            class: settings.classes.lightbox.itemMedia,
            html: this.getImgPlaceholder()
          }), (() => {
            if (!this.getElementSettings('lightbox_side_style')) {
              return;
            }
            return jQuery('<div />', {
              class: `${settings.classes.lightbox.item}__content`,
              html: jQuery('<div />', {
                class: `${settings.classes.lightbox.item}__content__header`,
                html: [jQuery('<div />', {
                  class: 'cmsmasters-instagram-lightbox-profile',
                  html: jQuery('<a />', {
                    href: this.getUrlByName(userData.username),
                    title: userData.name || userData.username,
                    html: [(() => {
                      if ('user' === this.getElementSettings('search_for')) {
                        return jQuery('<img />', {
                          src: userData.profile_picture_url,
                          class: 'cmsmasters-instagram-lightbox-profile-picture',
                          alt: userData.name || userData.username
                        });
                      }
                    })(), jQuery('<span />', {
                      class: 'cmsmasters-instagram-lightbox-profile-username',
                      text: `@${userData.username}`
                    })]
                  })
                }), (() => {
                  if (!post.permalink) {
                    return;
                  }
                  return jQuery('<div />', {
                    class: 'cmsmasters-instagram-lightbox-permalink',
                    html: jQuery('<a />', {
                      href: this.addLinks(post.permalink),
                      target: '_blank',
                      text: 'Instagram'
                    })
                  });
                })(), (() => {
                  if (!post.caption) {
                    return;
                  }
                  return jQuery('<p />', {
                    class: 'cmsmasters-instagram-lightbox-caption',
                    html: this.addLinks(this.checkLineBreaks(post.caption))
                  });
                })()]
              })
            });
          })()]
        })
      })
    });
    $slide.on('click', event => {
      event.stopPropagation();
    });
    return $slide;
  }
  getUserData() {
    return this.elements.$wrapper.data('user-data');
  }
  async initSlider() {
    if (this.swiper) {
      return;
    }
    if (!window.Swiper) {
      await elementorFrontend.utils.assetsLoader.load('script', 'swiper');
    }
    this.swiper = await new Swiper(this.elements.lightbox.$swiperContainer.get(0), {
      slidesPerView: 1,
      allowTouchMove: false,
      observer: true,
      observeParents: true,
      navigation: {
        nextEl: '.elementor-swiper-button-next',
        prevEl: '.elementor-swiper-button-prev'
      }
    });
    setTimeout(() => {
      this.swiper.on('slideChangeTransitionEnd', this.onSlideChange.bind(this));
      this.swiper.on('slideChange', this.pauseMedia.bind(this));
    }, 250);
  }

  /**
   * @since 1.0.3 Added slider object check to move the slider to the first slide on lightbox open.
   */
  showLightbox(id) {
    this.initLightbox().then(() => {
      setTimeout(async () => {
        this.lightbox.show();
        if (!this.swiper) {
          await this.initSlider();
        }
        const $slide = jQuery(this.swiper.slides).filter(`[data-id="${id}"]`);
        const cmsmastersSlider = $slide.find(this.elements.lightbox.$item).data('cmsmastersSlider');
        const index = $slide.index();
        this.swiper.slideToLoop(index, 0);
        this.onSlideChange();
        if (cmsmastersSlider) {
          cmsmastersSlider.swiper.slideToLoop(0, 0);
        }
      });
    });
  }
  getCurrentSlider() {
    return jQuery(this.swiper.slides).filter('.swiper-slide-active');
  }
  getCurrentSliderID() {
    return this.getCurrentSlider().data('id');
  }
  onSlideChange() {
    const settings = this.getSettings();
    const $sliderItem = this.getCurrentSliderItem();
    const $slide = this.getCurrentSlider();
    if (!$sliderItem.hasClass(`${settings.classes.lightbox.root}-image--loading`) && !$sliderItem.hasClass(`${settings.classes.lightbox.root}-image--loaded`)) {
      const onLoaded = () => {
        $sliderItem.addClass(`${settings.classes.lightbox.root}-image--loaded`);
        $sliderItem.removeClass(`${settings.classes.lightbox.root}-image--loading`);
        $sliderItem.find(`.${settings.classes.lightbox.item}__placeholder`).remove();
      };
      const post = this.getPostById(this.getCurrentSliderID());
      const $view = this.getSlideViewHTML(post);
      const {
        mediaType
      } = $slide.data();
      $sliderItem.addClass(`${settings.classes.lightbox.root}-image--loading`);
      $sliderItem.find(`.${settings.classes.lightbox.itemMedia}`).append($view);
      if ('CAROUSEL_ALBUM' === mediaType) {
        this.initAlbumSliders($sliderItem);
        $sliderItem.find('img').eq(0).on('load', onLoaded);
      } else if ('IMAGE' === mediaType) {
        $view.find('img').on('load', onLoaded);
      } else {
        onLoaded();
      }
    }
    if (this.isComments()) {
      if ($sliderItem.hasClass(settings.classes.lightbox.loader) || $sliderItem.hasClass(settings.classes.lightbox.loaded)) {
        return;
      }
      $sliderItem.addClass(settings.classes.lightbox.loader);
      this.getComments(this.getCurrentSliderID(), (error, $comments) => {
        $sliderItem.removeClass(settings.classes.lightbox.loader);
        if (error) {
          return;
        }
        $sliderItem.addClass(settings.classes.lightbox.loaded);
        if ($comments && $comments.length) {
          $sliderItem.find(this.elements.lightbox.$itemContent).append($comments);
        }
      });
    }
  }
  initAlbumSliders($slide) {
    const slider = new _slider.default({
      widget: this,
      $wrap: $slide
    });
    slider.on('options', option => {
      option.observer = true;
      option.observeParents = true;
    });
    slider.init();
    setTimeout(() => {
      slider.swiper.on('slideChange', this.pauseMedia.bind(this));
    }, 250);
  }
  getCurrentSliderItem() {
    const $slide = this.getCurrentSlider();
    return $slide.find(this.elements.lightbox.$item);
  }
  getSlideViewHTML(post) {
    const settings = this.getSettings();
    const userData = this.getUserData();
    const $view = jQuery('<div />', {
      class: `${settings.classes.lightbox.item}__view`
    });
    if ('VIDEO' === post.media_type) {
      $view.append(jQuery('<video />', {
        src: post.video,
        controls: 'controls'
      }));
    } else if ('CAROUSEL_ALBUM' === post.media_type) {
      let slides = '';
      post.children.forEach(child => {
        slides += '<div class="swiper-slide">' + this.getSlideViewHTML(child)[0].outerHTML + '</div>';
      });
      $view.append(this.elements.$templateSlider.html().replace(/{{SLIDES}}/g, slides));
    } else {
      const attrs = {
        src: post.media_url
      };
      if (userData && userData.name) {
        attrs.alt = userData.name;
      } else {
        attrs.alt = cmsmastersElementorFrontend.config.i18n.instagram.img_alt_text;
      }
      const $img = jQuery('<img />', attrs);
      $view.append($img);
    }
    return $view;
  }
  getPost($link) {
    const {
      post
    } = $link.data();
    post.id = $link.data('id');
    post.permalink = $link.attr('href');
    post.media_url = $link.find(this.elements.$linksImages).attr('src');
    return post;
  }
  getPostById(id) {
    const $link = this.elements.$links.filter(`[data-id="${id}"]`);
    return this.getPost($link);
  }
  updateLightboxSlider() {
    if (!this.swiper) {
      return;
    }
    this.elements.$links.each((index, link) => {
      const $link = jQuery(link);
      const $slide = this.elements.lightbox.$slides.filter(`[data-id="${$link.data('id')}"]`);
      const post = this.getPost($link);
      if ($slide.length) {
        return;
      }
      this.swiper.appendSlide(this.getSlide(post));
    });
  }
  appendLightboxSlider() {
    const $wrapper = jQuery('<div />', {
      class: 'swiper-wrapper'
    });
    this.elements.$links.each((index, item) => {
      const $item = jQuery(item);
      const post = this.getPost($item);
      $wrapper.append(this.getSlide(post));
    });
    const $container = jQuery('<div />', {
      class: 'swiper swiper-container cmsmasters-swiper-container'
    });
    $container.append($wrapper);
    const $prevArrow = jQuery('<div />', {
      class: 'elementor-swiper-button elementor-swiper-button-prev elementor-lightbox-prevent-close',
      html: '<i class="eicon-chevron-left"></i>'
    });
    const $nextArrow = jQuery('<div />', {
      class: 'elementor-swiper-button elementor-swiper-button-next elementor-lightbox-prevent-close',
      html: '<i class="eicon-chevron-right"></i>'
    });
    jQuery.merge($prevArrow, $nextArrow).on('click', event => {
      event.stopPropagation();
    });
    $container.append($prevArrow).append($nextArrow);
    this.lightbox.setMessage($container);
  }
  getLightBoxID() {
    return `cmsmasters-instagram-${this.getID()}`;
  }
  async initLightbox() {
    if (this.lightbox) {
      return;
    }
    const settings = this.getSettings();
    if (!window.DialogsManager) {
      await elementorFrontend.utils.assetsLoader.load('script', 'dialog');
    }
    this.lightbox = elementorFrontend.getDialogsManager().createWidget('lightbox', {
      id: this.getLightBoxID(),
      className: `${settings.classes.lightbox.root} elementor-lightbox`,
      closeButton: true,
      closeButtonClass: 'eicon-close',
      hide: {
        onClick: true
      }
    });
    const onShow = async () => {
      this.appendLightboxSlider();
      this.initElements();
      await this.initSlider();
      this.updatePerfectScroll();
      this.lightbox.off('show', onShow);
    };
    this.lightbox.on('show', onShow);
    this.lightbox.on('hide', this.pauseMedia.bind(this));
  }
  pauseMedia() {
    this.lightbox.getElements().widget.find('video, audio').each((index, item) => {
      item.pause();
    });
  }
  onLoadMoreClick(event) {
    event.preventDefault();
    if (!this.ajaxWidget.isRequestFree()) {
      return;
    }
    const page = Number(this.elements.$loadMore.attr('data-page'));
    this.ajaxWidget.setAjaxVars('page', page);
    this.ajaxWidget.request().then(() => {
      const response = this.ajaxWidget.getResponseData();
      const $html = jQuery(response.html);
      this.elements.$wrapItems.append($html);
      this.elements.$loadMore.attr('data-page', response.page);

      // update load more button
      if (response.page >= response.max_num_pages) {
        this.elements.$loadMoreWrap.remove();
      }
      this.initElements();
      this.updateLightboxSlider();
      this.initElements();
      this.updatePerfectScroll();
      this.initMasonry();
    });
  }
  getAccessToken() {
    if (!!this.getElementSettings('custom_connection')) {
      return this.getElementSettings('access_token');
    }
    return elementorCmsmastersFrontendConfig.instagram_access_token;
  }
  getAccountType() {
    if (this.getElementSettings('custom_connection') && this.getElementSettings('account_type')) {
      return this.getElementSettings('account_type');
    }
    return elementorCmsmastersFrontendConfig.instagram_account_type;
  }
  addLinks(text) {
    const tagRegex = /(#[^\s,\.#]+)/g;
    const mentionRegex = /[@]+[A-Za-z0-9-_\."<]+/g;
    let fixText = text.replace(tagRegex, this.replaceHashtags.bind(this));
    fixText = fixText.replace(mentionRegex, this.replaceMention.bind(this));
    return fixText;
  }
  checkLineBreaks(text) {
    return text.replace(/\n/g, '<br>');
  }
  replaceHashtags(hash) {
    return this.getContentLink(hash, `https://www.instagram.com/explore/tags/${hash.trim().substring(1)}`);
  }
  replaceMention(mention) {
    return this.getContentLink(mention, `https://www.instagram.com/${mention.trim().substring(1)}`);
  }
  getContentLink(text, href) {
    const attrs = {
      class: 'content-link',
      target: '_blank',
      rel: 'nofollow noopener',
      text,
      href
    };
    return jQuery('<a />', attrs).get(0).outerHTML;
  }
  removeMasonry() {
    this.elements.$item.css({
      marginTop: ''
    });
  }
  initMasonry() {
    this.removeMasonry();
    if (!this.isMasonry()) {
      return;
    }
    const masonry = new elementorModules.utils.Masonry({
      container: this.elements.$wrapItems,
      items: this.elements.$item,
      columnsCount: this.getColumns(),
      verticalSpaceBetween: this.getRowGap()
    });
    this.$element.imagesLoaded().always(() => {
      this.removeMasonry();
      masonry.run();
    });
  }
  getRowGap() {
    return parseFloat(getComputedStyle(this.$element.get(0)).getPropertyValue('--gap-row'));
  }
  getCommentsHtml(comments) {
    const $comments = jQuery('<ul />', {
      class: 'cmsmasters-instagram-lightbox__comments-box'
    });
    comments.forEach(comment => {
      $comments.append(this.getCommentHtml(comment));
    });
    return $comments;
  }
  getComments(id, callback) {
    const url = `https://graph.facebook.com/${id}/comments?fields=text,username,replies{username,text}&access_token=${this.getAccessToken()}`;
    jQuery.ajax({
      url: url,
      data: 'json',
      success: response => {
        if (!response || !response.data.length) {
          callback(null, []);
          return;
        }
        callback(null, this.getCommentsHtml(response.data));
      },
      error: response => {
        callback(response);
      }
    });
  }
  getCommentHtml(comment) {
    const $comment = jQuery('<li />', {
      class: 'cmsmasters-instagram-lightbox-comment'
    });
    const $header = jQuery('<div />', {
      class: 'cmsmasters-instagram-lightbox-comment__header'
    });
    $header.append(jQuery('<a />', {
      class: 'cmsmasters-instagram-lightbox-commenter',
      href: this.getUrlByName(comment.username),
      target: '_blank',
      rel: 'noopener',
      text: comment.username
    }));
    $header.append(jQuery('<span />', {
      class: 'cmsmasters-instagram-lightbox-comment-text',
      html: this.addLinks(this.checkLineBreaks(comment.text))
    }));
    $comment.append($header);
    if (comment.replies && comment.replies.data.length) {
      comment.replies.data.reverse();
      $comment.append(this.getCommentsHtml(comment.replies.data));
    }
    return $comment;
  }
  isComments() {
    return 'business' === this.getAccountType() && 'user' === this.getElementSettings('search_for');
  }
  isMasonry() {
    return Boolean(this.getElementSettings('masonry')) && 1 < this.getColumns() && this.elements.$item.length;
  }
  onResize() {
    this.initMasonry();
  }
  onDestroy() {
    super.onDestroy(...arguments);
    if (this.swiper) {
      this.swiper.destroy();
    }
    if (this.lightbox) {
      this.lightbox.destroy();
    }
    elementorFrontend.elements.$window.off('resize', this.onResize);
    elementor.channels.editor.off('section:activated', this.sectionActivated);
  }
}
exports["default"] = Instagram;

/***/ })

}]);
//# sourceMappingURL=instagram.752646d65389c8a2d25d.bundle.js.map