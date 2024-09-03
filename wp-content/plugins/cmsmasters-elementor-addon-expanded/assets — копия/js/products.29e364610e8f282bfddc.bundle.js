/*! cmsmasters-elementor-addon - v1.11.6 - 05-03-2024 */
"use strict";
(self["webpackChunkcmsmasters_elementor_addon"] = self["webpackChunkcmsmasters_elementor_addon"] || []).push([["products"],{

/***/ "../modules/woocommerce/assets/dev/js/frontend/widgets/products.js":
/*!*************************************************************************!*\
  !*** ../modules/woocommerce/assets/dev/js/frontend/widgets/products.js ***!
  \*************************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _ajaxWidget = _interopRequireDefault(__webpack_require__(/*! cmsmasters-ajax-module/frontend/ajax-widget */ "../modules/ajax-widget/assets/dev/js/frontend/ajax-widget.js"));
var _pagination = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/blog/assets/dev/js/frontend/helpers/pagination */ "../modules/blog/assets/dev/js/frontend/helpers/pagination.js"));
var _handler = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/handler */ "../assets/dev/js/frontend/base/handler.js"));
var _borderColumns = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/blog/assets/dev/js/frontend/helpers/border-columns */ "../modules/blog/assets/dev/js/frontend/helpers/border-columns.js"));
const utils = __webpack_require__(/*! cmsmasters-helpers/utils */ "../assets/dev/js/helpers/utils.js");
class Products extends _handler.default {
  __construct() {
    super.__construct(...arguments);
    this.reLayoutDebounce = utils.debounce(this.reLayoutDebounce.bind(this));
    this.onResize = this.onResize.bind(this);
  }
  getDefaultSettings() {
    const settings = super.getDefaultSettings(...arguments);
    return Object.assign(settings, {
      selectors: {
        wrapper: '.woocommerce',
        product: 'li.product',
        products: 'ul.products',
        orderby: 'select.orderby',
        resultCount: '.woocommerce-result-count',
        layoutColumns: '.cmsmasters-woo-products__header-layout-column',
        wrapperColumns: '.cmsmasters-woo-products__header-layout'
      }
    });
  }
  getDefaultElements() {
    const {
      selectors
    } = this.getSettings();
    const self = this;
    return {
      $variable: this.findElement('.woocommerce'),
      get $wrapper() {
        return self.findElement(selectors.wrapper);
      },
      get $product() {
        return self.findElement(selectors.product);
      },
      get $products() {
        return self.findElement(selectors.products);
      },
      get $resultCount() {
        return self.findElement(selectors.resultCount);
      },
      get $orderby() {
        return self.findElement(selectors.orderby);
      },
      get $layoutColumns() {
        return self.findElement(selectors.layoutColumns);
      },
      get $WrapperColumns() {
        return self.findElement(selectors.layoutColumns);
      }
    };
  }
  bindEvents() {
    const {
      selectors
    } = this.getSettings();
    const queryControlPrefix = this.getElementSettings('query_control_prefix');
    if ('current_query' !== this.getElementSettings(`${queryControlPrefix}_post_type`)) {
      this.findElement('.woocommerce-ordering').off('change');
      this.$element.off('change');
      this.$element.on('change', selectors.orderby, this.onOrderby.bind(this));
    }
    jQuery('body').on('change', '.wpfFilterWrapper select, .wpfFilterWrapper input:not(.passiveFilter)', this.onWBWFilter.bind(this));
    jQuery('body').on('mousedown', '.wpfFilterButton, .js-wpfFilterButtonSearch', this.onWBWFilter.bind(this));
    jQuery('input.js-passiveFilterSearch').on('keydown', this.onWBWFilter.bind(this));
    jQuery('body').on('click', '.wpfClearButton', this.onWBWFilter.bind(this));
    jQuery('.wpfFilterWrapper[data-filter-type="wpfPriceRange"] .wpfFilterContent input').on('change', this.onWBWFilter.bind(this));
    jQuery('.wpfFilterWrapper[data-filter-type="wpfPrice"]').on('wpfPriceChange', this.onWBWFilter.bind(this));
    elementorFrontend.elements.$window.on('resize', this.onResize);
    this.on('ajaxInsertHTML:after', () => {
      this.$element.find('.elementor-element').each((index, element) => {
        elementorFrontend.elementsHandler.runReadyTrigger(element);
      });
    });
    this.on('ajaxInsertHTML:after', this.reLayout.bind(this));
    this.bindElementChange(['columns_gap', 'masonry', 'rows_gap'], this.initMasonry.bind(this));
    this.bindElementChange(['border_columns_type', 'border_vertical_width', 'columns', 'columns_gap'], this.initBorderColumn.bind(this));
    this.bindElementChange(['border_columns_type', 'border_horizontal_width'], this.initBorderRow.bind(this));
  }
  onInit() {
    super.onInit();
    if (!this.elements.$variable.length) {
      return;
    }
    this.initPagination();
    this.reLayout();
    this.initAjaxWidget();
    this.setCacheDefault();
    if ('yes' === this.getElementSettings('show_layout')) {
      this.setCustomLayout();
    }
    this.runFunctionOnAjax();
    this.on('ajaxInsertHTML:after', () => {
      if ('yes' === this.getElementSettings('show_layout')) {
        this.elements.$layoutColumns.removeClass('active');
        this.hideCustomLayoutVariations();
      }
    });
  }
  runFunctionOnAjax() {
    if (null === localStorage.getItem(`cmsmasters-custom-layout-${this.getID()}`) || 'NaN' === localStorage.getItem(`cmsmasters-custom-layout-${this.getID()}`) || undefined === localStorage.getItem(`cmsmasters-custom-layout-${this.getID()}`) || 'undefined' === localStorage.getItem(`cmsmasters-custom-layout-${this.getID()}`)) {
      localStorage.setItem(`cmsmasters-custom-layout-${this.getID()}`, false);
    }
    let savedData = JSON.parse(localStorage.getItem(`cmsmasters-custom-layout-${this.getID()}`));
    jQuery(document).on('click', this.elements.$layoutColumns, event => {
      const clickedElement = jQuery(event.target).closest(this.elements.$layoutColumns);
      if (clickedElement.length) {
        savedData = JSON.parse(localStorage.getItem(`cmsmasters-custom-layout-${this.getID()}`));
      }
    });
    jQuery(document).ajaxStop(() => {
      if (savedData) {
        localStorage.setItem(`cmsmasters-custom-layout-${this.getID()}`, parseInt(savedData));
      }
      this.$element.find('.elementor-element').each((index, element) => {
        elementorFrontend.elementsHandler.runReadyTrigger(element);
      });
      this.elements.$layoutColumns.removeClass('active');
      this.reLayout();
    });
  }
  getDeviceSetting(setting) {
    let device = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'desktop';
    const settings = this.getElementSettings();
    return elementorFrontend.getDeviceSetting(device, settings, setting);
  }
  setCustomLayout() {
    jQuery(document).on('click touch', this.elements.$layoutColumns, event => {
      const clickedElement = jQuery(event.target).closest(this.elements.$layoutColumns);
      if (clickedElement.length) {
        const $this = jQuery(event.target);
        const currentBreakpoint = window.elementorFrontend.getCurrentDeviceMode();
        const oldLayout = this.getDeviceSetting('columns', currentBreakpoint);
        const $products = this.elements.$products;
        if (!$this.hasClass('active')) {
          const newLayout = $this.data('layout');
          if (newLayout) {
            $products.css('grid-template-columns', 'repeat(' + newLayout + ', minmax(0, 1fr))');
            this.initMasonry(newLayout);
            this.initBorderColumn(newLayout);
            this.initBorderRow(newLayout);
          }
          this.elements.$layoutColumns.removeClass('active');
          $this.addClass('active');
          localStorage.setItem(`cmsmasters-custom-layout-${this.getID()}`, newLayout);
        } else {
          $products.css('grid-template-columns', 'repeat(' + oldLayout + ', minmax(0, 1fr))');
          $this.removeClass('active');
          localStorage.setItem(`cmsmasters-custom-layout-${this.getID()}`, false);
          this.initMasonry(oldLayout);
          this.initBorderColumn(oldLayout);
          this.initBorderRow(oldLayout);
        }
      }
    });
  }
  hideCustomLayoutVariations() {
    const $wrapper = this.elements.$wrapper;
    const widgetWidth = $wrapper.get(0).offsetWidth;
    const currentBreakpoint = window.elementorFrontend.getCurrentDeviceMode();
    const oldLayout = this.getDeviceSetting('columns', currentBreakpoint);
    this.elements.$layoutColumns.each((index, element) => {
      const $this = jQuery(element);
      const dataLayout = $this.data('layout');
      const customLayout = parseInt(localStorage.getItem(`cmsmasters-custom-layout-${this.getID()}`));
      let newDataLayout = '';
      let newCustomLayout = '';
      if (widgetWidth <= 600) {
        newDataLayout = 2 >= dataLayout;
        newCustomLayout = 2 >= customLayout;
      } else if (widgetWidth > 600 && widgetWidth <= 800) {
        newDataLayout = 3 >= dataLayout;
        newCustomLayout = 3 >= customLayout;
      } else if (widgetWidth > 800 && widgetWidth <= 1100) {
        newDataLayout = 4 >= dataLayout && 2 <= dataLayout;
        newCustomLayout = 4 >= customLayout && 2 <= customLayout;
      } else if (widgetWidth > 1100 && widgetWidth <= 1400) {
        newDataLayout = 5 >= dataLayout && 2 <= dataLayout;
        newCustomLayout = 5 >= customLayout && 2 <= customLayout;
      } else if (1400 < widgetWidth) {
        newDataLayout = 3 <= dataLayout;
        newCustomLayout = 3 <= customLayout;
      }
      if (newDataLayout) {
        $this.show();
      } else {
        $this.hide();
        $this.removeClass('active');
        if (null === customLayout || undefined === customLayout || null !== customLayout && undefined !== customLayout && !newCustomLayout) {
          this.elements.$products.css('grid-template-columns', 'repeat(' + oldLayout + ', minmax(0, 1fr))');
          setTimeout(() => {
            this.initMasonry(oldLayout);
            this.initBorderColumn(oldLayout);
            this.initBorderRow(oldLayout);
          }, 300);
        }
      }
      if (null !== customLayout && undefined !== customLayout) {
        const $activeCustomLayout = this.elements.$layoutColumns.filter('[data-layout="' + customLayout + '"]');
        if ($activeCustomLayout.length > 0) {
          if (newCustomLayout && dataLayout === customLayout) {
            $this.addClass('active');
            this.elements.$products.css('grid-template-columns', 'repeat(' + customLayout + ', minmax(0, 1fr))');
            setTimeout(() => {
              this.initMasonry(customLayout);
              this.initBorderColumn(customLayout);
              this.initBorderRow(customLayout);
            }, 300);
          }
        }
      }
    });
  }
  initPagination() {
    this.pagination = new _pagination.default(this);
    this.pagination.on('updatePage', this.onUpdatePage.bind(this));
    this.pagination.on('click', this.onPagination.bind(this));
  }
  reLayout() {
    if ('yes' === this.getElementSettings('show_layout')) {
      this.hideCustomLayoutVariations();
    } else {
      this.initMasonry(this.getColumns());
      this.initBorderColumn(this.getColumns());
      this.initBorderRow(this.getColumns());
    }
  }
  initMasonry(columnsCount) {
    this.removeMasonry();
    if (!this.isMasonry()) {
      return;
    }
    const verticalSpaceBetween = parseInt(getComputedStyle(this.$element.get(0)).getPropertyValue('--cmsmasters-gap-row'));
    if (!columnsCount) {
      columnsCount = this.getColumns();
    }
    new elementorModules.utils.Masonry({
      container: elementorFrontend.elements.$body,
      items: this.elements.$product,
      columnsCount: columnsCount,
      verticalSpaceBetween
    }).run();
  }
  removeMasonry() {
    this.elements.$product.css({
      marginTop: ''
    });
  }
  isMasonry() {
    const columns = this.getColumns();
    return 1 < columns && Boolean(this.getElementSettings('masonry'));
  }
  getColumns() {
    return Number(this.getCurrentDeviceSetting('columns'));
  }
  initBorderColumn(columnsCount) {
    if (this.borderColumns) {
      this.borderColumns.update();
      return;
    }
    if (!columnsCount) {
      columnsCount = this.getColumns();
    }
    this.borderColumns = new _borderColumns.default({
      $container: () => this.elements.$products,
      $items: () => this.elements.$product,
      columns: () => columnsCount,
      widget: this
    });
  }
  initBorderRow(columnsCount) {
    if (!columnsCount) {
      columnsCount = this.getColumns();
    }
    const size = this.getCurrentDeviceSetting('border_horizontal_width');
    const type = this.getElementSettings('border_columns_type');
    this.elements.$product.removeClass('separator-vertical');
    if (!type || !size) {
      return;
    }

    /* Row Posts */
    this.elements.$product.filter(`:not(:nth-last-of-type(-n+${columnsCount}))`).addClass('separator-vertical');
  }
  initAjaxWidget() {
    this.ajaxWidget = new _ajaxWidget.default({
      ajaxVarsDefault: {
        query_vars: {
          paged: this.pagination.getPagedCurrent()
        },
        attributes: {
          orderby: this.elements.$orderby.val()
        }
      },
      cacheAllow: true,
      widget: this
    });
    this.ajaxWidget.on('response/success', this.responseSuccess.bind(this));
    this.ajaxWidget.on('response/fail', this.responseFail.bind(this));
  }
  setCacheDefault() {
    this.ajaxWidget.setCache(this.elements.$variable.get(0).outerHTML);
  }
  onOrderby(event) {
    event.stopImmediatePropagation();
    event.stopPropagation();
    event.preventDefault();
    if (!this.ajaxWidget.isRequestFree()) {
      return;
    }
    this.ajaxMethod = 'orderby';
    this.pagination.setPage(1);
    this.ajaxWidget.setAjaxVars('attributes.orderby', this.elements.$orderby.val());
    this.ajaxWidget.request();
  }
  onPagination() {
    if (!this.ajaxWidget.isRequestFree()) {
      return;
    }
    if (this.pagination.isLoadMore() || this.pagination.isInfiniteScroll()) {
      this.ajaxMethod = 'load-more';
    } else {
      this.ajaxMethod = 'pagination';
    }
    const urlParameters = new URLSearchParams(location.search);
    urlParameters.forEach(function (value, key) {
      this.ajaxWidget.setAjaxVars(`attributes.${key}`, value);
    }.bind(this));
    this.ajaxWidget.request();
  }
  onUpdatePage(paged) {
    if (!this.ajaxWidget.isRequestFree()) {
      return;
    }
    this.ajaxWidget.setAjaxVars('query_vars.paged', paged);
  }
  onWBWFilter() {
    utils.saveParameters({
      [this.pagination.getPagedName()]: 1
    });
  }
  responseSuccess() {
    this.trigger('ajaxInsertHTML:before');
    this.ajaxInsertHTML();
    this.trigger('ajaxInsertHTML:after');
    this.pagination.onSuccess();
    this.saveState();
  }
  saveState() {
    if (!this.pagination.isSaveState()) {
      return;
    }
    const parameters = {};
    switch (this.ajaxMethod) {
      case 'orderby':
        const parameterName = `cmsmasters-orderby-${this.getID()}`;
        const {
          default_orderby: defaultOrderby
        } = elementorCmsmastersFrontendConfig.woocommerce;
        const orderby = this.elements.$orderby.val();
        parameters[parameterName] = defaultOrderby !== orderby ? orderby : false;
        break;
    }
    utils.saveParameters(parameters);
  }
  responseFail() {
    this.pagination.onFail();
  }
  ajaxInsertHTML() {
    const html = this.ajaxWidget.getResponseData();
    const $html = jQuery(html);
    const $animatedItems = $html.find('.elementor-invisible');
    const {
      selectors
    } = this.getSettings();
    const {
      selectors: paginationSelectors
    } = this.pagination.getSettings();
    if (1 <= $animatedItems.length) {
      const animatedItemsSettings = $animatedItems.data('settings');
      const animation = animatedItemsSettings.animation || 'none';
      if ('none' !== animation) {
        const animationDelay = animatedItemsSettings._animation_delay || animatedItemsSettings.animation_delay || 0;
        setTimeout(() => {
          $animatedItems.removeClass('elementor-invisible').addClass(`animated ${animation}`);
        }, animationDelay);
      } else {
        $animatedItems.removeClass('elementor-invisible');
      }
    }
    if ('load-more' === this.ajaxMethod) {
      this.elements.$products.append($html.find(selectors.products).contents());
      const $pagination = $html.find(paginationSelectors.root);
      if ($pagination.find(paginationSelectors.linkLoadMore).length) {
        this.pagination.elements.$root.replaceWith($pagination);
      } else {
        this.pagination.elements.$root.remove();
      }
      this.elements.$resultCount.replaceWith($html.find(selectors.resultCount));
    } else {
      this.elements.$variable.html($html.contents());
    }
  }
  onResize() {
    this.resetLayout();
    this.reLayoutDebounce();
  }
  resetLayout() {
    this.borderColumns.clear();
    this.removeMasonry();
  }
  reLayoutDebounce() {
    this.reLayout();
  }
  unbindEvents() {
    elementorFrontend.elements.$window.off('resize', this.onResize);
  }
}
exports["default"] = Products;

/***/ })

}]);
//# sourceMappingURL=products.29e364610e8f282bfddc.bundle.js.map