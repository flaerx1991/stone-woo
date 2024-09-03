/*! cmsmasters-elementor-addon - v1.11.6 - 05-03-2024 */
"use strict";
(self["webpackChunkcmsmasters_elementor_addon"] = self["webpackChunkcmsmasters_elementor_addon"] || []).push([["theme-blog-grid"],{

/***/ "../modules/blog/assets/dev/js/frontend/widgets/blog/base/theme-blog-grid-base-elements.js":
/*!*************************************************************************************************!*\
  !*** ../modules/blog/assets/dev/js/frontend/widgets/blog/base/theme-blog-grid-base-elements.js ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _ajaxWidget = _interopRequireDefault(__webpack_require__(/*! cmsmasters-modules/ajax-widget/assets/dev/js/frontend/ajax-widget */ "../modules/ajax-widget/assets/dev/js/frontend/ajax-widget.js"));
var _themeBlogGridBase = _interopRequireDefault(__webpack_require__(/*! ./theme-blog-grid-base */ "../modules/blog/assets/dev/js/frontend/widgets/blog/base/theme-blog-grid-base.js"));
var _pagination = _interopRequireDefault(__webpack_require__(/*! ../../../helpers/pagination */ "../modules/blog/assets/dev/js/frontend/helpers/pagination.js"));
const utils = __webpack_require__(/*! cmsmasters-helpers/utils */ "../assets/dev/js/helpers/utils.js");
class _default extends _themeBlogGridBase.default {
  __construct() {
    super.__construct(...arguments);
    this.pagination = null;
    this.ajaxWidget = null;
    this.secondaryFilterScrollbar = null;
    this.scrollPerfect = null;
    this.mutationObserver = null;
    this.onPostResize = this.onPostResize.bind(this);
    this.onPostResizeDebounce = utils.debounce(this.onPostResizeDebounce.bind(this));
    this.onPostResizeOnce = this.onPostResizeOnce.bind(this);
    this.reLayoutDebounce = utils.debounce(this.reLayoutDebounce.bind(this));
    this.toggleFilterSecondaryEvent = this.toggleFilterSecondaryEvent.bind(this);
    this.elementChangeList = ['typography_header_filter_font_size', 'header_filter_padding'];
  }
  getDefaultSettings() {
    const settings = super.getDefaultSettings(...arguments);
    const baseClass = settings.classes.base;
    settings.classes = Object.assign(settings.classes, {
      filter__secondaryVisible: `${baseClass}__filter--nav-secondary-visible`,
      filter__secondaryHasItem: `${baseClass}__filter--nav-secondary-has-item`,
      filterMultiple: 'cmsmasters-filter-nav-multiple',
      termLink: 'term-link',
      termLinkActive: 'term-link-active',
      wrapper: `${baseClass}__wrapper`,
      wrapperInner: `${baseClass}__wrapper-inner`,
      product: `${baseClass}__product`,
      // showButton: `${ baseClass }__show`,
      elementorWidgetContainer: 'elementor-widget-container',
      // headerIcon: 'product-chooses-header-icon',
      popup: 'popup',
      popupChooseProduct: `${baseClass}__post-add-to-quote-popup-choose-product`
    });
    settings.selectors = Object.assign(settings.selectors, {
      widgetWrapper: '.cmsmasters-theme-blog-grid',
      header: `.${baseClass}__header`,
      filter: `.${baseClass}__filter`,
      filterSingle: '.cmsmasters-filter-nav-single',
      filterMultiple: '.cmsmasters-filter-nav-multiple',
      filterMultipleTaxonomyListClearAllButton: `.${baseClass}__multiple-category-list-clear-all-button`,
      filterMultipleTaxonomyListPopupTrigger: `.${baseClass}__multiple-category-list-popup-trigger`,
      filterMultipleTaxonomyListPopupClose: `.${baseClass}__multiple-category-list-popup-close-icon`,
      filterMultipleTaxonomyList: `.${baseClass}__multiple-taxonomy-list`,
      filterMultipleTaxonomyListItem: `.${baseClass}__multiple-taxonomy-list-item`,
      filterMultipleTaxonomyListTrigger: `.${baseClass}__multiple-taxonomy-list-item-trigger`,
      filterMultipleTaxonomyListTriggerValue: `.${baseClass}__multiple-taxonomy-list-item-trigger-value`,
      filterMultipleTaxonomyListTriggerClear: `.${baseClass}__multiple-taxonomy-list-item-trigger-clear`,
      filterMultipleTaxonomyListTriggerIcon: `.${baseClass}__multiple-taxonomy-list-item-trigger-icon`,
      filterMultipleCategoryList: `.${baseClass}__multiple-category-list`,
      filterMultipleCategoryListCheckbox: `.${baseClass}__multiple-category-list-item-checkbox`,
      filterMultipleCategoryListButton: `.${baseClass}__multiple-category-list-button`,
      filterMinimizeTrigger: `.${baseClass}__filter-minimize_trigger`,
      filterPrimary: `.${baseClass}__filter-nav-primary`,
      filterSecondary: `.${baseClass}__filter-nav-secondary`,
      filterSecondaryTrigger: `.${baseClass}__filter-nav-secondary-trigger`,
      termLinkActive: `.${settings.classes.termLinkActive}`,
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
      addToCart: '.single_add_to_cart_button',
      inputUom: `.${baseClass}__post-add-to-quote-input-uom`
    });
    return settings;
  }
  getDefaultElements() {
    const elements = super.getDefaultElements(...arguments);
    const {
      selectors,
      classes
    } = this.getSettings();
    elements.$html = jQuery(document).find('html');
    elements.$widgetWrapper = this.findElement(selectors.widgetWrapper);
    elements.$header = this.findElement(selectors.header);
    elements.$filter = this.findElement(selectors.filter);
    elements.$filterSingle = this.findElement(selectors.filterSingle);
    elements.$filterMultiple = this.findElement(selectors.filterMultiple);
    elements.$filterMultipleTaxonomyListClearAllButton = this.findElement(selectors.filterMultipleTaxonomyListClearAllButton);
    elements.$filterMultipleTaxonomyListPopupTrigger = this.findElement(selectors.filterMultipleTaxonomyListPopupTrigger);
    elements.$filterMultipleTaxonomyListPopupClose = this.findElement(selectors.filterMultipleTaxonomyListPopupClose);
    elements.$filterMultipleTaxonomyList = this.findElement(selectors.filterMultipleTaxonomyList);
    elements.$filterMultipleTaxonomyListItem = this.findElement(selectors.filterMultipleTaxonomyListItem);
    elements.$filterMultipleTaxonomyListTrigger = this.findElement(selectors.filterMultipleTaxonomyListTrigger);
    elements.$filterMultipleTaxonomyListTriggerValue = this.findElement(selectors.filterMultipleTaxonomyListTriggerValue);
    elements.$filterMultipleTaxonomyListTriggerClear = this.findElement(selectors.filterMultipleTaxonomyListTriggerClear);
    elements.$filterMultipleTaxonomyListTriggerIcon = this.findElement(selectors.filterMultipleTaxonomyListTriggerIcon);
    elements.$filterMultipleCategoryList = this.findElement(selectors.filterMultipleCategoryList);
    elements.$filterMultipleCategoryListCheckbox = this.findElement(selectors.filterMultipleCategoryListCheckbox);
    elements.$filterMultipleCategoryListButton = this.findElement(selectors.filterMultipleCategoryListButton);
    elements.$filterMinimizeTrigger = this.findElement(selectors.filterMinimizeTrigger);
    elements.$filterPrimary = this.findElement(selectors.filterPrimary);
    elements.$filterPrimaryTermLinks = this.findElement(`${selectors.filterPrimary} .${classes.termLink}`);
    elements.$filterSecondary = this.findElement(selectors.filterSecondary);
    elements.$filterSecondaryTrigger = this.findElement(selectors.filterSecondaryTrigger);
    elements.$post = this.findElement(selectors.post);
    elements.$addToQuote = this.findElement(selectors.addToQuote);
    elements.$inputs = this.findElement(selectors.inputs);
    elements.$input = this.findElement(selectors.input);
    return elements;
  }
  bindEvents() {
    const {
      selectors,
      classes
    } = this.getSettings();
    const settings = this.getElementSettings();
    this.bindElementChange(this.elementChangeList, this.filterFitItem.bind(this));
    if (this.isFilterSingle()) {
      if (this.isFilterAjax()) {
        /* Click Filter */
        this.elements.$filter.on('click', `.${classes.termLink}`, this.filterSingleClick.bind(this));
      }

      /* Click Open Secondary Menu */
      this.elements.$filterSecondaryTrigger.on('click', this.secondaryTriggerClick.bind(this));
    }
    if (this.isFilterMultiple()) {
      /* Click Filter */
      this.elements.$filterMultipleTaxonomyListTriggerValue.on('click', this.filterMultipleOpenList.bind(this));
      this.elements.$filterMultipleTaxonomyListTriggerIcon.on('click', this.filterMultipleOpenList.bind(this));
      this.elements.$filterMultipleCategoryListCheckbox.on('change', this.filterMultipleCategoryListCheck.bind(this));
      this.elements.$filterMultipleCategoryListButton.on('click', this.filterMultipleClick.bind(this));
      if ('product' === settings.blog_post_type) {
        this.elements.$filterMultipleTaxonomyListTriggerClear.on('click', this.filterMultipleTaxonomyClearClick.bind(this));
        this.elements.$filterMultipleTaxonomyListClearAllButton.on('click', this.filterMultipleClearAll.bind(this));
        this.elements.$filterMultipleTaxonomyListPopupTrigger.on('click', this.filterMultipleTaxonomyListPopupTriggerClick.bind(this));
        this.elements.$filterMultipleTaxonomyListPopupClose.on('click', this.filterMultipleTaxonomyListPopupCloseClick.bind(this));

        // this.elements.$filterMultipleCategoryListCheckbox.on( 'change', this.filterMultipleClick.bind( this ) );
      }
    }

    if ('product' === settings.blog_post_type) {
      this.elements.$widgetWrapper.on('click', selectors.popupTrigger, this.popupTriggerClick.bind(this));
      this.elements.$widgetWrapper.on('click', selectors.popupClose, this.popupCloseClick.bind(this));
      this.elements.$widgetWrapper.on('click', selectors.inputOperator, this.inputOperatorClick.bind(this));

      // if ( 'sqft' === this.elements.$addToQuote.attr( 'product-uom' ) ) {
      this.elements.$widgetWrapper.on('input', selectors.input, this.textInput.bind(this));
      jQuery(document).on('input', this.activeInput.bind(this));
      // }

      this.elements.$widgetWrapper.on('click focus', selectors.input, this.clickActiveInput.bind(this));
      this.elements.$widgetWrapper.on('click', selectors.quoteButton, this.quoteButtonClick.bind(this));
      this.elements.$widgetWrapper.on('click', selectors.addToCart, this.addingToCartAjax.bind(this));
      this.elements.$widgetWrapper.on('change', selectors.checkbox, this.checkboxClick.bind(this));
    }

    /* Resize Debounce */
    elementorFrontend.elements.$window.on('resize', this.onPostResizeDebounce);
    elementorFrontend.elements.$window.one('resize', this.onPostResizeOnce);
    this.on('widget-resize', this.onPostResize);

    // if ( elementorFrontend.isEditMode() ) {
    this.on('ajaxInsertHTML:after', () => {
      this.elements.$posts.find('.elementor-element').each((index, element) => {
        elementorFrontend.elementsHandler.runReadyTrigger(element);
      });
    });
    // }

    this.observerConnect();
    this.elements.$filterMinimizeTrigger.on('click', this.dropdownFilter.bind(this));
    this.elements.$filterPrimaryTermLinks.on('click', this.toggleTextChange.bind(this));
  }
  initElements() {
    super.initElements(...arguments);
    this.initPagination();
  }
  onInit() {
    super.onInit();
    this.reLayout();
    this.initAjaxWidget();
    this.setCacheDefault();
    if (this.ifMinimize()) {
      this.elements.$filterPrimary.find('.term-link-reset').parent().addClass('hidden');
    }
    if (this.isFilterMultiple()) {
      this.updateMultipleFilterScrollbar();
      this.updateMultipleCategoryListFilterScrollbar();
    }
  }
  isFilterSingle() {
    return this.elements.$filterSingle.length;
  }
  isFilterAjax() {
    return Boolean(this.getElementSettings('header_filter_via_ajax'));
  }
  observerConnect() {
    if (!this.elements.$variable.length) {
      return;
    }
    this.observerDisconnect();
    if (!this.mutationObserver) {
      this.mutationObserver = new MutationObserver(this.setCacheDefault.bind(this));
    }
    this.mutationObserver.observe(this.elements.$variable.get(0), {
      childList: true,
      subtree: true
    });
  }
  observerDisconnect() {
    if (this.mutationObserver) {
      this.mutationObserver.disconnect(this.elements.$variable.get(0));
    }
  }
  initPagination() {
    this.pagination = new _pagination.default(this);
    this.pagination.on('updatePage', this.onUpdatePage.bind(this));
    this.pagination.on('click', this.onPagination.bind(this));
  }
  reLayout() {
    this.filterFitItem();
    if (this.isFilterMultiple()) {
      this.updateMultipleFilterScrollbar();
      this.updateMultipleCategoryListFilterScrollbar();
    }
  }
  filterFitItem() {
    const {
      $filterPrimaryTermLinks
    } = this.elements;
    if (!$filterPrimaryTermLinks.length) {
      return;
    }
    const {
      $filter,
      $filterSecondary
    } = this.elements;
    const positionsExample = $filterPrimaryTermLinks.get(0).getBoundingClientRect();
    const {
      classes,
      selectors
    } = this.getSettings();
    const $activeItem = this.getFilterLinks().filter(selectors.termLinkActive);
    $filter.removeClass(classes.filter__secondaryVisible);
    $filterSecondary.empty();
    $filterPrimaryTermLinks.each((index, termLink) => {
      const positions = termLink.getBoundingClientRect();
      if (positions.top > positionsExample.top) {
        const $termLink = jQuery(termLink);
        const $termLinkClone = $termLink.clone(true);
        $termLinkClone.wrap('<li />').parent().appendTo($filterSecondary);
        if ($activeItem.data('term-id') === $termLinkClone.data('term-id')) {
          $termLinkClone.addClass(classes.termLinkActive);
          $termLinkClone.parent().addClass(classes.termItemLinkActive);
        }
      }
    });
    const hasSecondaryChildren = Boolean($filterSecondary.children().length);
    if (hasSecondaryChildren) {
      this.updateSecondaryFilterScrollbar();
    }
    $filter.toggleClass(classes.filter__secondaryHasItem, hasSecondaryChildren);
  }
  updateSecondaryFilterScrollbar() {
    const {
      $filterSecondary
    } = this.elements;
    const element = $filterSecondary.get(0);
    if (undefined !== element) {
      if (!this.secondaryFilterScrollbar) {
        this.secondaryFilterScrollbar = new PerfectScrollbar(element, {
          suppressScrollX: true
        });
        return;
      }
      this.secondaryFilterScrollbar.update();
    }
  }
  updateMultipleFilterScrollbar() {
    const element = this.elements.$header.get(0);
    if (undefined !== element) {
      if (!this.scrollPerfect) {
        this.scrollPerfect = new PerfectScrollbar(element, {
          wheelSpeed: 0.5,
          suppressScrollY: true
        });
        return;
      }
      this.scrollPerfect.update();
    }
  }
  updateMultipleCategoryListFilterScrollbar() {
    const {
      selectors
    } = this.getSettings();
    this.elements.$filterMultiple.find(selectors.filterMultipleCategoryList).each(function () {
      if (undefined !== this) {
        if (!this.scrollPerfect) {
          this.scrollPerfect = new PerfectScrollbar(this, {
            wheelSpeed: 0.5,
            suppressScrollX: true
          });
          return;
        }
        this.scrollPerfect.update();
      }
    });
  }
  initAjaxWidget() {
    this.ajaxWidget = new _ajaxWidget.default({
      ajaxVarsDefault: this.getDefaultAjaxVars(),
      cacheAllow: true,
      widget: this
    });
    this.ajaxWidget.on('response/success', this.responseSuccess.bind(this));
    this.ajaxWidget.on('response/fail', this.responseFail.bind(this));
  }
  getDefaultAjaxVars() {
    const ajaxVarsDefault = {
      query_vars: {
        paged: this.pagination.getPagedCurrent(),
        tax_query: []
      }
    };
    if (this.isFilterSingle()) {
      const filterData = this.getFilterData();
      if (filterData) {
        ajaxVarsDefault.query_vars.tax_query = filterData;
      }
    }
    if (this.isFilterMultiple()) {
      const filterData = this.getFilterMultipleData();
      if (filterData) {
        ajaxVarsDefault.query_vars.tax_query = filterData;
      }
    }
    return ajaxVarsDefault;
  }
  setCacheDefault() {
    let $html = jQuery('<div />', {
      html: this.elements.$variable.html()
    });
    if (window.lazySizes) {
      $html.find(`.${lazySizes.cfg.loadingClass}`).each((index, img) => {
        jQuery(img).removeClass(lazySizes.cfg.loadingClass).addClass(lazySizes.cfg.lazyClass);
      });
    }
    this.ajaxWidget.setCache($html.html());
  }
  toggleFilterSecondaryEvent(event) {
    const {
      target
    } = event;
    const {
      classes
    } = this.getSettings();
    if (this.elements.$filterSecondary.is(target) || jQuery.contains(this.elements.$filterSecondary.get(0), target)) {
      return;
    }
    this.elements.$filter.removeClass(classes.filter__secondaryVisible);
    elementorFrontend.elements.$document.off('click', this.toggleFilterSecondaryEvent);
  }
  secondaryTriggerClick(event) {
    event.preventDefault();
    const {
      classes
    } = this.getSettings();
    this.elements.$filter.toggleClass(classes.filter__secondaryVisible);
    const isVisible = this.elements.$filter.hasClass(classes.filter__secondaryVisible);
    if (isVisible) {
      elementorFrontend.elements.$document.on('click', this.toggleFilterSecondaryEvent);
    } else {
      elementorFrontend.elements.$document.off('click', this.toggleFilterSecondaryEvent);
    }
    return false;
  }
  filterSingleClick(event) {
    event.preventDefault();
    if (!this.ajaxWidget.isRequestFree()) {
      return;
    }
    this.ajaxMethod = 'filter';
    let $el = jQuery(event.currentTarget);
    const {
      classes
    } = this.getSettings();
    if ($el.hasClass(classes.termLinkActive)) {
      return;
    }
    this.pagination.setPage(1);
    const filterData = this.getFilterData($el);
    if (filterData) {
      this.ajaxWidget.setAjaxVars('query_vars.tax_query', filterData);
    } else {
      /* As Default */
      this.ajaxWidget.setAjaxVars('query_vars.tax_query', []);
    }
    this.ajaxWidget.request().then(() => {
      /* Delay 350 for hidden dropdown */
      setTimeout(() => {
        this.elements.$filter.removeClass(classes.filter__secondaryVisible);
      }, 350);
      const $filterLinks = this.getFilterLinks();
      $el = $filterLinks.filter($el);
      $filterLinks.removeClass(classes.termLinkActive);
      $filterLinks.parent().removeClass(classes.termItemLinkActive);
      $el.addClass(classes.termLinkActive);
      $el.parent().addClass(classes.termItemLinkActive);
      if (this.isSaveState()) {
        const parameterName = `cmsmasters-filter-${this.getID()}`;
        const parameters = {
          [parameterName]: false
        };
        if (filterData) {
          const termId = $el.data('termId');
          const taxonomy = $el.data('taxonomy');
          if (termId && taxonomy) {
            parameters[parameterName] = `${taxonomy}|${termId}`;
          }
        }
        utils.saveParameters(parameters);
      }
    }).catch(() => {
      $el.parent('li').remove();
      this.filterFitItem();
    });
  }
  isSaveState() {
    return !elementorFrontend.isEditMode() && Boolean(this.getElementSettings('header_filter_save_state'));
  }
  getFilterLinks() {
    const {
      classes,
      selectors
    } = this.getSettings();
    const $termLink = this.findElement(`${selectors.filterSecondary} .${classes.termLink}`);
    return jQuery.merge($termLink, this.elements.$filterPrimaryTermLinks);
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
    this.ajaxWidget.request();
  }
  responseSuccess() {
    this.trigger('ajaxInsertHTML:before');
    this.ajaxInsertHTML();
    this.trigger('ajaxInsertHTML:after');
    this.pagination.onSuccess();
  }
  responseFail() {
    this.pagination.onFail();
  }
  ajaxInsertHTML() {
    const html = this.ajaxWidget.getResponseData();
    const {
      selectors
    } = this.getSettings();
    const {
      selectors: paginationSelectors
    } = this.pagination.getSettings();
    if ('load-more' === this.ajaxMethod) {
      const $html = jQuery(html);
      const postContent = $html.find(selectors.posts).contents();
      this.elements.$postsWrap.append(postContent);
      this.pagination.elements.$root.replaceWith($html.filter(paginationSelectors.root));
    } else {
      this.elements.$variable.html(html);
    }
  }
  reLayoutDebounce() {
    this.reLayout();
  }
  getFilterData() {
    let $el = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
    const {
      classes
    } = this.getSettings();
    if (!$el || !$el.length) {
      $el = this.getFilterLinks().filter((index, item) => {
        return jQuery(item).hasClass(classes.termLinkActive);
      });
    }
    const termId = $el.data('termId');
    const taxonomy = $el.data('taxonomy');
    if (termId && taxonomy) {
      return [{
        taxonomy,
        field: 'term_id',
        terms: [termId]
      }];
    }
    return false;
  }
  onPostResize() {
    this.reLayoutDebounce();
  }
  onPostResizeDebounce() {
    this.onPostResize();
    elementorFrontend.elements.$window.one('resize', this.onPostResizeOnce);
  }
  onPostResizeOnce() {}
  onUpdatePage(paged) {
    if (!this.ajaxWidget.isRequestFree()) {
      return;
    }
    this.ajaxWidget.setAjaxVars('query_vars.paged', paged);
  }
  ifMinimize() {
    const settings = this.getElementSettings();
    return 'single' === settings.header_filter_show && 'yes' === settings.header_filter_via_ajax && 'none' !== settings.filter_minimized_on;
  }
  dropdownFilter(event) {
    const $this = jQuery(event.currentTarget);
    const $filterPrimary = this.elements.$filterPrimary;
    if (this.ifMinimize()) {
      if (!$this.hasClass('active')) {
        $filterPrimary.slideDown(300, 'linear');
      } else {
        $filterPrimary.slideUp(300, 'linear');
      }
      $this.toggleClass('active');
      this.elements.$filterPrimaryTermLinks.on('click', event => {
        $filterPrimary.slideUp(300, 'linear');
        $this.removeClass('active');
      });
    }
  }
  toggleTextChange(event) {
    if (this.ifMinimize()) {
      this.elements.$filterMinimizeTrigger.html(jQuery(event.currentTarget).html());
      this.elements.$filterPrimary.find('li').removeClass('hidden').addClass('visible');
      jQuery(event.currentTarget).parent().removeClass('visible').addClass('hidden');

      // jQuery( event.currentTarget )
      // 	.removeClass( 'visible' )
      // 	.addClass( 'hidden' );
    }
  }

  isFilterMultiple() {
    return this.elements.$filterMultiple.length;
  }
  filterMultipleOpenList(event) {
    const {
      selectors
    } = this.getSettings();
    const settings = this.getElementSettings();
    const $this = jQuery(event.currentTarget);
    const $filterMultipleCategoryList = $this.closest(selectors.filterMultipleTaxonomyListItem).find(selectors.filterMultipleCategoryList);
    const $filterMultipleTaxonomyListTrigger = $this.closest(selectors.filterMultipleTaxonomyListTrigger);
    if (!$filterMultipleTaxonomyListTrigger.hasClass('active')) {
      $filterMultipleCategoryList.slideDown(200, 'linear');
    } else {
      $filterMultipleCategoryList.slideUp(200, 'linear');
    }
    $filterMultipleTaxonomyListTrigger.toggleClass('active');
    if ('product' !== settings.blog_post_type) {
      jQuery(document).on('click', function (event) {
        const $target = jQuery(event.target);
        if (!$target.closest(selectors.filterMultiple).length) {
          $filterMultipleCategoryList.slideUp(200, 'linear');
          $filterMultipleTaxonomyListTrigger.removeClass('active');
          jQuery(document).off('click');
        }
      });
    }
    event.stopPropagation();
  }
  filterMultipleClearAll(event) {
    const {
      selectors
    } = this.getSettings();
    this.elements.$filterMultipleTaxonomyList.find(selectors.filterMultipleTaxonomyListItem).each(function () {
      const $listItem = jQuery(this);
      const $filterMultipleTaxonomyListTrigger = $listItem.find(selectors.filterMultipleTaxonomyListTrigger);
      const filterMultipleTaxonomyListTriggerValue = $listItem.find(selectors.filterMultipleTaxonomyListTriggerValue);
      const filterMultipleCategoryListTriggerDefaultValue = filterMultipleTaxonomyListTriggerValue.attr('data-default');
      $filterMultipleTaxonomyListTrigger.addClass('default-value');
      filterMultipleTaxonomyListTriggerValue.html(filterMultipleCategoryListTriggerDefaultValue);
      $listItem.find('input').prop('checked', false).parent().removeClass('checked');
    });
    this.ajaxWidget.setAjaxVars('query_vars.tax_query', []);
    this.ajaxWidget.request();
  }
  filterMultipleTaxonomyClearClick(event) {
    const {
      selectors
    } = this.getSettings();
    const $this = jQuery(event.currentTarget);
    const $filterMultipleTaxonomyListItem = $this.parent().parent().parent().parent();
    const $filterMultipleTaxonomyListTrigger = $filterMultipleTaxonomyListItem.find(selectors.filterMultipleTaxonomyListTrigger);
    const filterMultipleTaxonomyListTriggerValue = $filterMultipleTaxonomyListItem.find(selectors.filterMultipleTaxonomyListTriggerValue);
    const filterMultipleCategoryListTriggerDefaultValue = filterMultipleTaxonomyListTriggerValue.attr('data-default');
    $filterMultipleTaxonomyListTrigger.addClass('default-value');
    filterMultipleTaxonomyListTriggerValue.html(filterMultipleCategoryListTriggerDefaultValue);
    jQuery(event.currentTarget).parent().parent().parent().parent().find('input').prop('checked', false).parent().removeClass('checked');

    // this.ajaxWidget.setAjaxVars( 'query_vars.tax_query', [] );

    // this.ajaxWidget.request();

    this.filterMultipleClick();
  }
  filterMultipleCategoryListCheck(event) {
    const {
      selectors
    } = this.getSettings();
    const $this = jQuery(event.currentTarget);
    const $filterMultipleTaxonomyListItem = $this.parent().parent().parent();
    const $filterMultipleTaxonomyListTrigger = $filterMultipleTaxonomyListItem.find(selectors.filterMultipleTaxonomyListTrigger);
    const filterMultipleTaxonomyListTriggerValue = $filterMultipleTaxonomyListItem.find(selectors.filterMultipleTaxonomyListTriggerValue);
    const filterMultipleCategoryListTriggerDefaultValue = filterMultipleTaxonomyListTriggerValue.attr('data-default');
    const $filterMultipleCategoryListItemLabel = $this.parent().find('label');
    const firstCheckedLabel = $filterMultipleTaxonomyListItem.find('input[type="checkbox"]:checked:first').siblings('label').text();
    if ($this.prop('checked')) {
      if (!$this.parent().parent().hasClass('sorting')) {
        jQuery(event.currentTarget).parent().addClass('checked');
      } else {
        jQuery(event.currentTarget).parent().parent().find('li').removeClass('checked').find('input').prop('checked', false);
        jQuery(event.currentTarget).attr('checked', 'checked').prop('checked', true).parent().addClass('checked');
      }
      const labelText = $filterMultipleCategoryListItemLabel.text();
      if ($filterMultipleTaxonomyListTrigger.hasClass('default-value')) {
        $filterMultipleTaxonomyListTrigger.removeClass('default-value');
        filterMultipleTaxonomyListTriggerValue.html(labelText).attr('data-new-value', labelText).attr('data-more', 0);
      } else {
        const newMore = parseInt(filterMultipleTaxonomyListTriggerValue.attr('data-more')) || 0;
        if (!$this.parent().parent().hasClass('sorting')) {
          filterMultipleTaxonomyListTriggerValue.text(firstCheckedLabel + ' +' + (newMore + 1)).attr('data-new-value', firstCheckedLabel).attr('data-more', newMore + 1);
        } else {
          filterMultipleTaxonomyListTriggerValue.text(labelText).attr('data-new-value', labelText);
        }
      }
    } else {
      if (!$this.parent().parent().hasClass('sorting')) {
        jQuery(event.currentTarget).parent().removeClass('checked');
      } else {
        jQuery(event.currentTarget).prop('checked', false).parent().removeClass('checked');
      }
      let totalChecked = $filterMultipleTaxonomyListItem.find('input[type="checkbox"]:checked').length;
      if (totalChecked === 0) {
        $filterMultipleTaxonomyListTrigger.addClass('default-value');
        filterMultipleTaxonomyListTriggerValue.html(filterMultipleCategoryListTriggerDefaultValue);
      } else {
        let newMore = parseInt(filterMultipleTaxonomyListTriggerValue.attr('data-more')) || 0;
        if (firstCheckedLabel === $this.val()) {
          filterMultipleTaxonomyListTriggerValue.text(firstCheckedLabel + (newMore > 1 ? ' +' + (newMore - 1) : '')).attr('data-new-value', firstCheckedLabel).attr('data-more', newMore - 1);
        } else {
          if (totalChecked === 1) {
            filterMultipleTaxonomyListTriggerValue.text(firstCheckedLabel).attr('data-new-value', firstCheckedLabel).attr('data-more', 0);
          } else {
            filterMultipleTaxonomyListTriggerValue.text(firstCheckedLabel + ' +' + (newMore - 1)).attr('data-more', newMore - 1);
          }
        }
      }
    }
  }
  getFilterMultipleData(selectedValues) {
    if (!selectedValues || !Object.keys(selectedValues).length === 0) {
      return false;
    }
    const returnArray = [];
    for (const taxonomyId in selectedValues) {
      if (selectedValues.hasOwnProperty(taxonomyId)) {
        const categories = selectedValues[taxonomyId];
        if (categories.length > 0) {
          returnArray.push({
            taxonomy: taxonomyId,
            field: 'term_id',
            terms: categories
          });
        }
      }
    }
    return returnArray;
  }
  filterMultipleClick(event) {
    const {
      selectors
    } = this.getSettings();
    const settings = this.getElementSettings();

    // event.preventDefault();

    const selectedValues = {};
    this.elements.$filterMultipleTaxonomyList.find(selectors.filterMultipleTaxonomyListItem).each(function () {
      const $listItem = jQuery(this);
      const taxonomyId = $listItem.attr('data-taxonomy-id');
      const $checkboxes = $listItem.find(selectors.filterMultipleCategoryListCheckbox + ':checked');
      const categories = [];
      $checkboxes.each(function () {
        categories.push(jQuery(this).parent().attr('data-category-id'));
      });
      selectedValues[taxonomyId] = categories;
    });
    if ('product' !== settings.blog_post_type) {
      this.elements.$filterMultipleCategoryList.slideUp(200, 'linear');
      this.elements.$filterMultipleTaxonomyListTrigger.removeClass('active');
    }
    if (!this.ajaxWidget.isRequestFree()) {
      return;
    }
    this.ajaxMethod = 'filter';
    let $el = selectedValues;
    this.pagination.setPage(1);
    const filterData = this.getFilterMultipleData($el);
    if (filterData && filterData.length > 0) {
      this.ajaxWidget.setAjaxVars('query_vars.tax_query', filterData);
    } else {
      this.ajaxWidget.setAjaxVars('query_vars.tax_query', []);
    }
    this.ajaxWidget.request();
  }
  filterMultipleTaxonomyListPopupTriggerClick(event) {
    this.elements.$html.css('overflow', 'hidden');
    jQuery(event.currentTarget).addClass('active');
  }
  filterMultipleTaxonomyListPopupCloseClick(event) {
    const {
      selectors
    } = this.getSettings();
    this.elements.$html.css('overflow', 'inherit');
    jQuery(event.currentTarget).closest(selectors.widgetWrapper).find(selectors.filterMultipleTaxonomyListPopupTrigger).removeClass('active');
  }

  // Add To Quote
  popupTriggerClick(event) {
    const {
      selectors
    } = this.getSettings();
    const $this = jQuery(event.currentTarget);
    $this.addClass('active');
    $this.closest(selectors.post).find(selectors.popupPopup).addClass('visible');
    $this.closest('html').css('overflow-y', 'hidden');
  }
  popupCloseClick(event) {
    const {
      selectors
    } = this.getSettings();
    const $this = jQuery(event.currentTarget);
    $this.closest(selectors.post).find(selectors.popupPopup).removeClass('visible');
    $this.closest(selectors.post).find(selectors.popupTrigger).removeClass('active');
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
    const fullPackaged = $this.closest(selectors.popupChooseProduct).attr('full-packaged');
    const pack = $this.closest(selectors.popupChooseProduct).attr('pack');
    const $popupCont = $this.closest(selectors.popupCont);
    const $addToQuote = $popupCont.find(selectors.addToQuote);
    const $quoteButton = $popupCont.find(selectors.quoteButton);
    const $inputItem = $popupCont.find(selectors.inputItem);
    const $input = $popupCont.find(selectors.input);
    const $inputEach = $popupCont.find(selectors.inputItem + '[product-uom="each"]');
    $this.closest(selectors.popupCont).find(selectors.checkbox).each(function () {
      const $checkbox = jQuery(this);
      $checkbox.closest(selectors.popupChooseProduct).removeClass('choose').addClass('not_chooses');
    });
    $this.closest(selectors.popupChooseProduct).removeClass('not_chooses').addClass('choose');
    $addToQuote.attr('product-uom', dataUoms);
    $quoteButton.attr('data-product_id', productID).attr('data-wp_nonce', dataWpNonce);
    $inputItem.attr('product-width', dataProductWidth).attr('product-height', dataProductHeight);
    $input.attr('full-packaged', fullPackaged).attr('pack', pack);
    if ('yes' === fullPackaged) {
      $inputEach.find(selectors.input).attr('placeholder', 'QTY (pallet)');
      $inputEach.find(selectors.inputUom).text('pallet');
    } else {
      $inputEach.find(selectors.input).attr('placeholder', 'QTY (each)');
      $inputEach.find(selectors.inputUom).text('each');
    }
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
  unbindEvents() {
    elementorFrontend.elements.$window.off('resize', this.onPostResizeDebounce);
    elementorFrontend.elements.$window.off('resize', this.onPostResizeOnce);
    this.observerDisconnect();
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/blog/assets/dev/js/frontend/widgets/blog/base/theme-blog-grid-base.js":
/*!****************************************************************************************!*\
  !*** ../modules/blog/assets/dev/js/frontend/widgets/blog/base/theme-blog-grid-base.js ***!
  \****************************************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _handler = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/handler */ "../assets/dev/js/frontend/base/handler.js"));
class _default extends _handler.default {
  getDefaultSettings() {
    const base = 'elementor-widget-cmsmasters-theme-blog';
    const classes = {
      base,
      variable: `${base}__posts-variable`,
      posts: `${base}__posts`,
      post: `${base}__post`
    };
    const selectors = {
      variable: `.${classes.variable}`,
      base: `.${classes.base}`,
      posts: `.${classes.posts}`,
      post: `.${classes.post}`
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
      $base: this.findElement(selectors.base),
      $variable: this.findElement(selectors.variable)
    };
    Object.defineProperty(elements, '$postsWrap', {
      get: () => this.findElement(selectors.posts)
    });
    Object.defineProperty(elements, '$posts', {
      get: () => this.findElement(selectors.post)
    });
    return elements;
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/blog/assets/dev/js/frontend/widgets/blog/theme-blog-grid.js":
/*!******************************************************************************!*\
  !*** ../modules/blog/assets/dev/js/frontend/widgets/blog/theme-blog-grid.js ***!
  \******************************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _themeBlogGridBaseElements = _interopRequireDefault(__webpack_require__(/*! ./base/theme-blog-grid-base-elements */ "../modules/blog/assets/dev/js/frontend/widgets/blog/base/theme-blog-grid-base-elements.js"));
var _borderColumns = _interopRequireDefault(__webpack_require__(/*! ../../helpers/border-columns */ "../modules/blog/assets/dev/js/frontend/helpers/border-columns.js"));
class ThemeBlogGrid extends _themeBlogGridBaseElements.default {
  __construct() {
    super.__construct(...arguments);
    this.handles = null;
  }
  bindEvents() {
    if (!this.elements.$posts.length) {
      return;
    }
    super.bindEvents();
    this.bindElementChange(['columns', 'meta_data_top_space_between', 'meta_data_bottom_space_between', 'taxonomy_meta_data_top_space_between', 'taxonomy_meta_data_bottom_space_between']);
    this.bindElementChange('columns post_gap_column layout_post_space', () => {
      this.initBorderColumn();
      this.trigger('widget-resize');
    });
    this.bindElementChange('border_vertical_width border_columns_type', this.initBorderColumn.bind(this));
    this.bindElementChange('columns border_columns_type border_horizontal_width', this.initBorderRow.bind(this));
    this.on('ajaxInsertHTML:after', this.reLayout.bind(this));
    const intersectionObserver = new IntersectionObserver(() => {
      setTimeout(function () {
        this.reLayout.bind(this);
      }.bind(this), 300);
    });
    intersectionObserver.observe(this.$element.get(0), {
      rootMargin: '500px 0px 500px 0px'
    });
  }
  onPostResizeOnce() {
    super.onPostResizeOnce(...arguments);
    this.removeBorderColumns();
  }
  reLayout() {
    super.reLayout(...arguments);
    this.initBorderColumn();
    this.initBorderRow();
  }
  onPostResize() {
    this.resetLayout();
    super.onPostResize(...arguments);
  }
  onInit() {
    super.onInit();
    this.initBorderColumn();
  }
  getColumns() {
    return Number(this.getCurrentDeviceSetting('columns'));
  }
  initBorderColumn() {
    if (this.borderColumns) {
      this.borderColumns.update();
      return;
    }
    this.borderColumns = new _borderColumns.default({
      widget: this,
      $container: () => this.elements.$postsWrap,
      $items: () => this.elements.$posts,
      columns: () => this.getColumns()
    });
  }
  initBorderRow() {
    const size = this.getCurrentDeviceSettingSize('border_horizontal_width');
    const columns = this.getColumns();
    this.elements.$posts.removeClass('separator-vertical');
    if (!size) {
      return;
    }

    /* Row Posts */
    this.elements.$posts.filter(`:not(:nth-last-of-type(-n+${columns}))`).addClass('separator-vertical');
  }
  resetLayout() {
    this.removeBorderColumns();
  }
  removeBorderColumns() {
    if (this.borderColumns) {
      this.borderColumns.clear();
    }
  }
}
exports["default"] = ThemeBlogGrid;

/***/ })

}]);
//# sourceMappingURL=theme-blog-grid.1768cd56a950a72e8b0a.bundle.js.map