/*! cmsmasters-elementor-addon - v1.11.6 - 05-03-2024 */
"use strict";
(self["webpackChunkcmsmasters_elementor_addon"] = self["webpackChunkcmsmasters_elementor_addon"] || []).push([["table-of-contents"],{

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

/***/ "../modules/table-of-contents/assets/dev/js/frontend/widgets/table-of-contents.js":
/*!****************************************************************************************!*\
  !*** ../modules/table-of-contents/assets/dev/js/frontend/widgets/table-of-contents.js ***!
  \****************************************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

/* provided dependency */ var __ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n")["__"];


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _handler = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/handler */ "../assets/dev/js/frontend/base/handler.js"));
class TableOfContents extends _handler.default {
  getDefaultSettings() {
    const settings = this.getElementSettings();
    const widgetSelector = 'elementor-widget-cmsmasters-table-of-contents';
    const listWrapperTag = 'numbers' === settings.marker_view ? 'ol' : 'ul';
    const classes = {
      anchor: 'elementor-menu-anchor',
      listWrapper: `${widgetSelector}__list`,
      listItem: `${widgetSelector}__list-item`,
      listTextWrapper: `${widgetSelector}__list-item-inner`,
      firstLevelListItem: 'elementor-toc__top-level',
      listItemText: `${widgetSelector}__list-item-inner-text`,
      activeItem: 'item-active',
      headingAnchor: 'elementor-toc__heading-anchor',
      collapsed: 'elementor-toc--collapsed'
    };
    const selectors = {
      headerTitle: `.${widgetSelector}__header-title`,
      body: `.${widgetSelector}__body`,
      widgetContainer: '.elementor-widget-container',
      postContentContainer: '.elementor:not([data-elementor-type="header"]):not([data-elementor-type="footer"]):not([data-elementor-type="popup"])',
      expandButton: '.cmsmasters-toggle-button-expand',
      collapseButton: '.cmsmasters-toggle-button-collapse'
    };
    return {
      classes,
      selectors,
      listWrapperTag
    };
  }
  getDefaultElements() {
    const {
      selectors
    } = this.getSettings();
    const elements = {
      $pageContainer: this.getContainer(),
      $widgetContainer: this.findElement(selectors.widgetContainer),
      $expandButton: this.findElement(selectors.expandButton),
      $collapseButton: this.findElement(selectors.collapseButton),
      $tocBody: this.findElement(selectors.body),
      $listItems: this.findElement(selectors.listItem)
    };
    return elements;
  }
  getContainer() {
    const {
      selectors
    } = this.getSettings();
    const settings = this.getElementSettings();
    if (settings.container) {
      return jQuery(settings.container);
    }
    const $documentWrapper = this.$element.parents('.elementor');
    if ('popup' === $documentWrapper.attr('data-elementor-type')) {
      return $documentWrapper;
    }
    return jQuery(selectors.postContentContainer);
  }
  bindEvents() {
    const settings = this.getElementSettings();
    if (settings.minimize_box) {
      this.elements.$expandButton.on('click', () => this.expandBox()).on('keyup', event => this.triggerClickOnEnterSpace(event));
      this.elements.$collapseButton.on('click', () => this.collapseBox()).on('keyup', event => this.triggerClickOnEnterSpace(event));
    }
    jQuery(window).on('resize', () => {
      setTimeout(() => {
        this.collapseBodyListener();
      }, 250);
    });
    if (settings.collapse_subitems) {
      this.elements.$listItems.on('hover', event => jQuery(event.target).slideToggle());
    }
  }
  getHeadings() {
    const settings = this.getElementSettings();
    const tags = settings.headings_by_tags.join(',');
    const selectors = this.getSettings('selectors');
    const excludedSelectors = settings.exclude_headings_by_selector;
    return this.elements.$pageContainer.find(tags).not(selectors.headerTitle).filter((index, heading) => {
      return !jQuery(heading).closest(excludedSelectors).length;
    });
  }
  addAnchorsBeforeHeadings() {
    const classes = this.getSettings('classes');
    this.elements.$headings.before(index => {
      if (jQuery(this.elements.$headings[index]).data('hasOwnID')) {
        return;
      }
      return `<span id="${classes.headingAnchor}-${index}" class="${classes.anchor} "></span>`;
    });
  }
  activateItem($listItem) {
    const classes = this.getSettings('classes');
    this.deactivateActiveItem($listItem);
    $listItem.parent().addClass(classes.activeItem);
    this.$activeItem = $listItem;
    if (!this.getElementSettings('collapse_subitems')) {
      return;
    }
    let $activeList;
    if ($listItem.hasClass(classes.firstLevelListItem)) {
      $activeList = $listItem.parent().next();
    } else {
      $activeList = $listItem.parents('.' + classes.listWrapper).eq(-2);
    }
    if (!$activeList.length) {
      delete this.$activeList;
      return;
    }
    this.$activeList = $activeList;
    this.$activeList.stop().slideDown();
  }
  deactivateActiveItem($activeToBe) {
    if (!this.$activeItem || this.$activeItem.is($activeToBe)) {
      return;
    }
    const {
      classes
    } = this.getSettings();
    this.$activeItem.parent().removeClass(classes.activeItem);
    if (this.$activeList && (!$activeToBe || !this.$activeList[0].contains($activeToBe[0]))) {
      this.$activeList.slideUp();
    }
  }
  followAnchor($element, index) {
    const anchorSelector = $element[0].hash;
    let $anchor;
    try {
      $anchor = jQuery(decodeURIComponent(anchorSelector));
    } catch (e) {
      return;
    }
    elementorFrontend.waypoint($anchor, direction => {
      if (this.itemClicked) {
        return;
      }
      const id = $anchor.attr('id');
      if ('down' === direction) {
        this.viewportItems[id] = true;
        this.activateItem($element);
      } else {
        delete this.viewportItems[id];
        this.activateItem(this.$listItemTexts.eq(index - 1));
      }
    }, {
      offset: 'bottom-in-view',
      triggerOnce: false
    });
    elementorFrontend.waypoint($anchor, direction => {
      if (this.itemClicked) {
        return;
      }
      const id = $anchor.attr('id');
      if ('down' === direction) {
        delete this.viewportItems[id];
        if (Object.keys(this.viewportItems).length) {
          this.activateItem(this.$listItemTexts.eq(index + 1));
        }
      } else {
        this.viewportItems[id] = true;
        this.activateItem($element);
      }
    }, {
      offset: 0,
      triggerOnce: false
    });
  }
  followAnchors() {
    this.$listItemTexts.each((index, element) => this.followAnchor(jQuery(element), index));
  }
  populateTOC() {
    this.listItemPointer = 0;
    if (this.getElementSettings('hierarchical_view')) {
      this.createNestedList();
    } else {
      this.createFlatList();
    }
    this.$listItemTexts = this.$element.find('.elementor-widget-cmsmasters-table-of-contents__list-item-inner-text');
    this.$listItemTexts.on('click', this.onListItemClick.bind(this));
    if (!elementorFrontend.isEditMode()) {
      this.followAnchors();
    }
  }
  createNestedList() {
    this.headingsData.forEach((heading, index) => {
      heading.level = 0;
      for (let i = index - 1; i >= 0; i--) {
        const currentOrderedItem = this.headingsData[i];
        if (currentOrderedItem.tag <= heading.tag) {
          heading.level = currentOrderedItem.level;
          if (currentOrderedItem.tag < heading.tag) {
            heading.level++;
          }
          break;
        }
      }
    });
    this.elements.$tocBody.html(this.getNestedLevel(0));
  }
  createFlatList() {
    this.elements.$tocBody.html(this.getNestedLevel());
  }
  getNestedLevel(level) {
    const settings = this.getSettings();
    const elementSettings = this.getElementSettings();
    const icon = this.getElementSettings('icon');
    let renderedIcon;
    if (icon) {
      if (elementorFrontend.config.experimentalFeatures.e_font_icon_svg && !elementorFrontend.isEditMode()) {
        renderedIcon = typeof icon.rendered_tag !== 'undefined' ? icon.rendered_tag : '';
      } else {
        renderedIcon = icon.value ? `<i class="${icon.value}"></i>` : '';
      }
    }
    let html = `<${settings.listWrapperTag} class="${settings.classes.listWrapper}">`;
    while (this.listItemPointer < this.headingsData.length) {
      const currentItem = this.headingsData[this.listItemPointer];
      let listItemTextClasses = settings.classes.listItemText;
      if (0 === currentItem.level) {
        listItemTextClasses += ' ' + settings.classes.firstLevelListItem;
      }
      if (level > currentItem.level) {
        break;
      }
      if (level === currentItem.level) {
        html += `<li class="${settings.classes.listItem}">`;
        html += `<div class="${settings.classes.listTextWrapper}">`;
        let liContent = `<a href="#${currentItem.anchorLink}" class="${listItemTextClasses}">${currentItem.text}</a>`;
        if ('icon' === elementSettings.marker_view && icon) {
          liContent = `${renderedIcon}${liContent}`;
        }
        html += liContent;
        html += '</div>';
        this.listItemPointer++;
        const nextItem = this.headingsData[this.listItemPointer];
        if (nextItem && level < nextItem.level) {
          html += this.getNestedLevel(nextItem.level);
        }
        html += '</li>';
      }
    }
    html += `</${settings.listWrapperTag}>`;
    return html;
  }
  handleNoHeadingsFound() {
    const noHeadingsText = __('No headings were found on this page.', 'elementor-pro');
    return this.elements.$tocBody.html(noHeadingsText);
  }
  collapseBodyListener() {
    const activeBreakpoints = elementorFrontend.breakpoints.getActiveBreakpointsList({
      withDesktop: true
    });
    const minimizedOn = this.getElementSettings('minimized_on');
    const currentDeviceMode = elementorFrontend.getCurrentDeviceMode();
    const isCollapsed = this.$element.hasClass(this.getSettings('classes.collapsed'));
    if ('desktop' === minimizedOn || activeBreakpoints.indexOf(minimizedOn) >= activeBreakpoints.indexOf(currentDeviceMode)) {
      if (!isCollapsed) {
        this.collapseBox(false);
      }
    } else if (isCollapsed) {
      this.expandBox(false);
    }
  }
  onElementChange(settings) {
    if ('minimized_on' === settings) {
      this.collapseBodyListener();
    }
  }
  getHeadingAnchorLink(index, classes) {
    const headingID = this.elements.$headings[index].id;
    const wrapperID = this.elements.$headings[index].closest('.elementor-widget').id;
    let anchorLink = '';
    if (headingID) {
      anchorLink = headingID;
    } else if (wrapperID) {
      anchorLink = wrapperID;
    }
    if (headingID || wrapperID) {
      jQuery(this.elements.$headings[index]).data('hasOwnID', true);
    } else {
      anchorLink = `${classes.headingAnchor}-${index}`;
    }
    return anchorLink;
  }
  setHeadingsData() {
    this.headingsData = [];
    const classes = this.getSettings('classes');
    this.elements.$headings.each((index, element) => {
      const anchorLink = this.getHeadingAnchorLink(index, classes);
      this.headingsData.push({
        tag: +element.nodeName.slice(1),
        text: element.textContent,
        anchorLink
      });
    });
  }
  run() {
    this.elements.$headings = this.getHeadings();
    if (!this.elements.$headings.length) {
      return this.handleNoHeadingsFound();
    }
    this.setHeadingsData();
    if (!elementorFrontend.isEditMode()) {
      this.addAnchorsBeforeHeadings();
    }
    this.populateTOC();
    if (this.getElementSettings('minimize_box')) {
      this.collapseBodyListener();
    }
  }
  expandBox() {
    let changeFocus = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
    const boxHeight = this.getCurrentDeviceSetting('box_min_height');
    this.$element.removeClass(this.getSettings('classes.collapsed'));
    this.elements.$tocBody.slideDown();
    this.elements.$expandButton.attr('aria-expanded', 'true');
    this.elements.$collapseButton.attr('aria-expanded', 'true');
    this.elements.$widgetContainer.css('min-height', boxHeight.size + boxHeight.unit);
    if (changeFocus) {
      this.elements.$collapseButton.trigger('focus');
    }
  }
  collapseBox() {
    let changeFocus = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
    this.$element.addClass(this.getSettings('classes.collapsed'));
    this.elements.$tocBody.slideUp();
    this.elements.$expandButton.attr('aria-expanded', 'false');
    this.elements.$collapseButton.attr('aria-expanded', 'false');
    this.elements.$widgetContainer.css('min-height', '0px');
    if (changeFocus) {
      this.elements.$expandButton.trigger('focus');
    }
  }
  triggerClickOnEnterSpace(event) {
    const ENTER_KEY = 13;
    const SPACE_KEY = 32;
    if (ENTER_KEY === event.keyCode || SPACE_KEY === event.keyCode) {
      event.currentTarget.click();
      event.stopPropagation();
    }
  }
  onInit() {
    super.onInit(...arguments);
    this.viewportItems = [];
    jQuery(() => this.run());
  }
  onListItemClick(event) {
    this.itemClicked = true;
    setTimeout(() => this.itemClicked = false, 2000);
    const $clickedItem = jQuery(event.target);
    const $list = $clickedItem.parent().next();
    const collapseNestedList = this.getElementSettings('collapse_subitems');
    let listIsActive;
    if (collapseNestedList && $clickedItem.hasClass(this.getSettings('classes.firstLevelListItem'))) {
      if ($list.is(':visible')) {
        listIsActive = true;
      }
    }
    this.activateItem($clickedItem);
    if (collapseNestedList && listIsActive) {
      $list.slideUp();
    }
  }
}
exports["default"] = TableOfContents;

/***/ })

}]);
//# sourceMappingURL=table-of-contents.6e8c3b229d97059fc4b1.bundle.js.map