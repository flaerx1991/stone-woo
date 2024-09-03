/*! cmsmasters-elementor-addon - v1.11.6 - 05-03-2024 */
"use strict";
(self["webpackChunkcmsmasters_elementor_addon"] = self["webpackChunkcmsmasters_elementor_addon"] || []).push([["theme-multiple-search"],{

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

/***/ "../modules/theme/assets/dev/js/frontend/widgets/theme-multiple-search.js":
/*!********************************************************************************!*\
  !*** ../modules/theme/assets/dev/js/frontend/widgets/theme-multiple-search.js ***!
  \********************************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _handler = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/handler */ "../assets/dev/js/frontend/base/handler.js"));
class ThemeMultipleSearch extends _handler.default {
  __construct(settings) {
    super.__construct(settings);
    this.scrollPerfect = null;
  }
  getDefaultSettings() {
    const baseClass = 'elementor-widget-cmsmasters-theme-multiple-search';
    const classes = {
      filter__secondaryVisible: `${baseClass}__filter--nav-secondary-visible`,
      filter__secondaryHasItem: `${baseClass}__filter--nav-secondary-has-item`,
      filterMultiple: 'cmsmasters-filter-nav-multiple',
      termLink: 'term-link',
      termLinkActive: 'term-link-active'
    };
    const selectors = {
      header: `.${baseClass}__header`,
      filter: `.${baseClass}__filter`,
      filterSingle: '.cmsmasters-filter-nav-single',
      filterMultiple: '.cmsmasters-filter-nav-multiple',
      filterMultipleTaxonomyList: `.${baseClass}__multiple-taxonomy-list`,
      filterMultipleTaxonomyListItem: `.${baseClass}__multiple-taxonomy-list-item`,
      filterMultipleTaxonomyListTrigger: `.${baseClass}__multiple-taxonomy-list-item-trigger`,
      filterMultipleTaxonomyListTriggerValue: `.${baseClass}__multiple-taxonomy-list-item-trigger-value`,
      filterMultipleCategoryList: `.${baseClass}__multiple-category-list`,
      filterMultipleCategoryListCheckbox: `.${baseClass}__multiple-category-list-item-checkbox`,
      filterMultipleCategoryListButton: `.${baseClass}__multiple-category-list-button`,
      filterMinimizeTrigger: `.${baseClass}__filter-minimize_trigger`,
      filterPrimary: `.${baseClass}__filter-nav-primary`,
      filterSecondary: `.${baseClass}__filter-nav-secondary`,
      filterSecondaryTrigger: `.${baseClass}__filter-nav-secondary-trigger`,
      termLinkActive: `.${classes.termLinkActive}`
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
      $header: this.findElement(selectors.header),
      $filter: this.findElement(selectors.filter),
      $filterSingle: this.findElement(selectors.filterSingle),
      $filterMultiple: this.findElement(selectors.filterMultiple),
      $filterMultipleTaxonomyList: this.findElement(selectors.filterMultipleTaxonomyList),
      $filterMultipleTaxonomyListItem: this.findElement(selectors.filterMultipleTaxonomyListItem),
      $filterMultipleTaxonomyListTrigger: this.findElement(selectors.filterMultipleTaxonomyListTrigger),
      $filterMultipleCategoryList: this.findElement(selectors.filterMultipleCategoryList),
      $filterMultipleCategoryListCheckbox: this.findElement(selectors.filterMultipleCategoryListCheckbox),
      $filterMultipleCategoryListButton: this.findElement(selectors.filterMultipleCategoryListButton),
      $filterMinimizeTrigger: this.findElement(selectors.filterMinimizeTrigger),
      $filterPrimary: this.findElement(selectors.filterPrimary),
      $filterPrimaryTermLinks: this.findElement(`${selectors.filterPrimary} .${classes.termLink}`),
      $filterSecondary: this.findElement(selectors.filterSecondary),
      $filterSecondaryTrigger: this.findElement(selectors.filterSecondaryTrigger)
    };
    return elements;
  }
  bindEvents() {
    this.elements.$filterMultipleTaxonomyListTrigger.on('click', this.filterMultipleOpenList.bind(this));
    this.elements.$filterMultipleCategoryListCheckbox.on('change', this.filterMultipleCategoryListCheck.bind(this));
    this.elements.$filterMultipleCategoryListButton.on('click', this.filterMultipleClick.bind(this));
  }
  onInit() {
    super.onInit();
    this.updateMultipleCategoryListFilterScrollbar();
  }
  reLayout() {
    this.updateMultipleCategoryListFilterScrollbar();
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
  filterMultipleOpenList(event) {
    const {
      selectors
    } = this.getSettings();
    const $this = jQuery(event.currentTarget);
    const $filterMultipleCategoryList = $this.parent().parent().find(selectors.filterMultipleCategoryList);
    if (!$this.hasClass('active')) {
      $filterMultipleCategoryList.slideDown(200, 'linear');
    } else {
      $filterMultipleCategoryList.slideUp(200, 'linear');
    }
    $this.toggleClass('active');
    jQuery(document).on('click', function (event) {
      const $target = jQuery(event.target);
      if (!$target.closest(selectors.filterMultiple).length) {
        $filterMultipleCategoryList.slideUp(200, 'linear');
        $this.removeClass('active');
        jQuery(document).off('click');
      }
    });
    event.stopPropagation();
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
  filterMultipleClick(event) {
    const {
      selectors
    } = this.getSettings();
    event.preventDefault();
    const selectedValues = {};
    this.elements.$filterMultipleTaxonomyList.find(selectors.filterMultipleTaxonomyListItem).each(function () {
      const $listItem = jQuery(this);
      const taxonomyValue = $listItem.attr('data-taxonomy-id');
      const $checkboxes = $listItem.find(selectors.filterMultipleCategoryListCheckbox + ':checked');
      const categories = [];
      $checkboxes.each(function () {
        categories.push(jQuery(this).parent().text().trim().replace(/\s+/g, '-').replace(/[^a-zA-Z0-9-]/g, '').replace(/-{2,}/g, '-').toLowerCase());
      });
      selectedValues[taxonomyValue] = categories;
    });
    this.elements.$filterMultipleCategoryList.slideUp(200, 'linear');
    this.elements.$filterMultipleTaxonomyListTrigger.removeClass('active');
    const baseUrl = window.location.origin;
    let url = '';
    const currentPath = window.location.pathname;
    if (currentPath !== '/') {
      const subdomain = currentPath.split('/')[1];
      url = baseUrl + '/' + subdomain;
    } else {
      url = baseUrl;
    }
    const selectedTaxonomies = Object.keys(selectedValues);
    const queryParams = [];
    for (let i = 0; i < selectedTaxonomies.length; i++) {
      const taxonomyValue = selectedTaxonomies[i];
      const categories = selectedValues[taxonomyValue];
      if (categories.length > 0) {
        queryParams.push(`${categories.map(cat => encodeURIComponent(cat)).join('&')}`);
      }
    }
    url += '/?s=' + queryParams.join('&');
    if (selectedTaxonomies.length > 0) {
      window.location.href = url;
    }
  }
}
exports["default"] = ThemeMultipleSearch;

/***/ })

}]);
//# sourceMappingURL=theme-multiple-search.d5cd29e1e014a2280c03.bundle.js.map