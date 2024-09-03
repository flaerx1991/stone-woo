/*! cmsmasters-elementor-addon - v1.11.6 - 05-03-2024 */
"use strict";
(self["webpackChunkcmsmasters_elementor_addon"] = self["webpackChunkcmsmasters_elementor_addon"] || []).push([["video"],{

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

/***/ "../modules/media/assets/dev/js/frontend/utils/video-minimize.js":
/*!***********************************************************************!*\
  !*** ../modules/media/assets/dev/js/frontend/utils/video-minimize.js ***!
  \***********************************************************************/
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
class _default extends elementorModules.ViewModule {
  getDefaultElements() {
    const {
      $videoContainer,
      $remove,
      $coverImage
    } = this.getSettings();
    const elements = {
      $videoContainer,
      $remove,
      $coverImage,
      $window: jQuery(window)
    };
    return elements;
  }
  onInit() {
    super.onInit();
    this.initMinimize();
  }
  initMinimize() {
    const $coverImage = this.elements.$coverImage;
    const $videoContainer = this.elements.$videoContainer;
    const $window = this.elements.$window;
    const isImageOverlay = $coverImage.length;
    if (isImageOverlay) {
      $videoContainer.addClass('image_overlay');
      $coverImage.on('click', () => {
        $videoContainer.removeClass('image_overlay').addClass('remove_image_overlay');
      });
    }
    $window.on('scroll', () => {
      const videoScrollTop = $videoContainer.outerHeight() + $videoContainer.offset().top;
      if ($window.scrollTop() <= videoScrollTop) {
        $videoContainer.removeClass('minimize');
      } else {
        const isRemoveMinimize = $videoContainer.hasClass('remove_minimize');
        if (!isRemoveMinimize) {
          const isOverlayOrMinimize = $videoContainer.hasClass('remove_image_overlay') || $videoContainer.hasClass('minimize_always');
          if (!isImageOverlay || isImageOverlay && isOverlayOrMinimize) {
            $videoContainer.addClass('minimize');
          }
        }
        this.elements.$remove.on('click', () => {
          $videoContainer.removeClass('minimize').addClass('remove_minimize');
        });
      }
    });
  }
}
exports["default"] = _default;

/***/ }),

/***/ "../modules/media/assets/dev/js/frontend/widgets/video.js":
/*!****************************************************************!*\
  !*** ../modules/media/assets/dev/js/frontend/widgets/video.js ***!
  \****************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _handler = _interopRequireDefault(__webpack_require__(/*! cmsmasters-frontend/base/handler */ "../assets/dev/js/frontend/base/handler.js"));
var _videoMinimize = _interopRequireDefault(__webpack_require__(/*! ../utils/video-minimize */ "../modules/media/assets/dev/js/frontend/utils/video-minimize.js"));
class Video extends _handler.default {
  getDefaultElements() {
    const elements = {
      $coverImage: this.findElement('.elementor-widget-cmsmasters-video__cover-image'),
      $videoHosted: this.findElement('.elementor-widget-cmsmasters-video__hosted'),
      $videoIframe: this.findElement('.elementor-video-iframe'),
      $videoContainer: this.findElement('.elementor-widget-cmsmasters-video__container'),
      $remove: this.findElement('.elementor-widget-cmsmasters-video__close-button')
    };
    return elements;
  }
  bindEvents() {
    this.elements.$coverImage.on('click', this.handleVideo.bind(this));
  }
  onInit() {
    super.onInit();
    this.initMinimize();
  }
  initMinimize() {
    if ('yes' !== this.getElementSettings('video_minimize')) {
      return;
    }
    new _videoMinimize.default({
      widget: this,
      $videoContainer: this.elements.$videoContainer,
      $remove: this.elements.$remove,
      $coverImage: this.elements.$coverImage
    });
  }
  handleVideo() {
    if (this.getElementSettings('lightbox')) {
      return;
    }
    this.elements.$coverImage.remove();
    this.playVideo();
  }
  playVideo() {
    const $videoHosted = this.elements.$videoHosted;
    if ($videoHosted.length) {
      const lazyLoadHosted = $videoHosted.data('lazy-load');
      if (lazyLoadHosted) {
        $videoHosted.attr('src', lazyLoadHosted);
      }
      $videoHosted[0].play();
      return;
    }
    const $videoIframe = this.elements.$videoIframe;
    const lazyLoad = $videoIframe.data('lazy-load');
    if (lazyLoad) {
      $videoIframe.attr('src', lazyLoad);
    }
    const videoSrc = $videoIframe.attr('src');
    const newSourceUrl = videoSrc.replace(/&muted*=[a-z\d]*/, '').replace(/&autoplay=[a-z\d]*/, '');
    let autoplay = '&autoplay=1';
    let mute = '&mute=true';
    let src = '';
    if (videoSrc.includes('vimeo.com')) {
      const indexOfStartTime = videoSrc.indexOf('#t=');
      const videoSrcStart = videoSrc.slice(0, indexOfStartTime);
      const videoSrcEnd = videoSrc.slice(indexOfStartTime);
      mute = '&muted=true';

      // insert the autoplay flag before the '#t=' param. Param '#t=' must be last in the URL
      src = `${videoSrcStart}${autoplay}${mute}${videoSrcEnd}`;
    } else {
      if (videoSrc.includes('twitch.tv')) {
        autoplay = '&autoplay=yes';
      } else if (videoSrc.includes('facebook.com')) {
        autoplay = '&autoplay=true';
      }
      src = `${newSourceUrl}${autoplay}${mute}`;
    }
    $videoIframe.attr('src', src);
  }
  onElementChange(propertyName) {
    const elementsData = elementorFrontend.config.elements.data[this.getModelCID()];
    const control = elementsData.options.controls[propertyName];
    if (control && 'section_lightbox_style' !== control.section) {
      this.hideLightbox();
    }
    if (0 === propertyName.indexOf('lightbox_content_animation')) {
      this.animateVideo();
      return;
    }
    if ('lightbox' === propertyName && !this.getElementSettings('lightbox')) {
      this.hideLightbox();
      return;
    }
    if ('aspect_ratio' === propertyName && this.getElementSettings('lightbox')) {
      this.handleAspectRatio();
    }
  }

  // @since 1.2.0 Fix: for Elementor 3.2.1 lightbox.
  async hideLightbox() {
    const lightbox = await elementorFrontend.utils.lightbox;
    lightbox.getModal().hide();
  }

  // @since 1.1.0 Fix: for Elementor 3.2.1 lightbox.
  async getLightbox() {
    return await elementorFrontend.utils.lightbox;
  }

  // @since 1.2.0 Fix: for Elementor 3.2.1 lightbox.
  async animateVideo() {
    const lightbox = await elementorFrontend.utils.lightbox;
    lightbox.setEntranceAnimation(this.getCurrentDeviceSetting('lightbox_animation_entrance'));
  }

  // @since 1.2.0 Fix: for Elementor 3.2.1 lightbox.
  async handleAspectRatio() {
    const lightbox = await elementorFrontend.utils.lightbox;
    lightbox.setVideoAspectRatio(this.getElementSettings('aspect_ratio'));
  }
}
exports["default"] = Video;

/***/ })

}]);
//# sourceMappingURL=video.42c90f5c3482230366b5.bundle.js.map