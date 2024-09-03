jQuery(function($) {

	$('.active-filter-popup').on('click', function() {
		$('.products_list.stone__products_list .elementor-widget-cmsmasters-theme-blog-grid__header-side.cmsmasters-filter-nav-multiple').fadeIn();
		$('html, body').addClass('lock');
	});

	$('.products_list.stone__products_list .elementor-widget-cmsmasters-theme-blog-grid__header-side .elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-popup-close-icon').on('click', function() {
		$('.products_list.stone__products_list .elementor-widget-cmsmasters-theme-blog-grid__header-side.cmsmasters-filter-nav-multiple').fadeOut();
		$('.product-page-filter-item.active-filter-popup .title').removeClass('active');
		$('html, body').removeClass('lock');
	});

	$('.collection__content .cmsmasters-tabs .cmsmasters-tabs-list li a').on('click', function(e) {
		e.preventDefault();
		var li = $(this).parent('li');
		var tab = li.data('tab');
		$('.collection__content .cmsmasters-tabs .cmsmasters-tabs-list li').removeClass('active-tab');
		li.addClass('active-tab');

		$('.collection__content .cmsmasters-tabs .cmsmasters-tabs-wrap .cmsmasters-tab').hide().removeClass('active-tab');
		$('.collection__content .cmsmasters-tabs .cmsmasters-tabs-wrap').find('.cmsmasters-tab[data-tab="'+tab+'"]').fadeIn(300).addClass('active-tab');

	})

	$('body').on('click', '.dealer-page-country:not(.active)', function(e) {
		e.preventDefault();
		$('.dealer-page-country').removeClass('active');
		$(this).addClass('active');
		var loc = $(this).data('locaton');
		$('.dealer-page-item--wrapper').hide();
		$('.dealer-page-item--wrapper#'+loc).fadeIn(300);
	});


	$('.product-page-filter-top .title').click(function(event) {
		var _item = $(this).closest('.product-page-filter-item');
		$(this).toggleClass('active');
		_item.find('.product-page-filter-body').slideToggle();
	});


	$('body').click(function (event) {
		if(!$(event.target).closest('.product-page-filter').length && !$(event.target).is('.product-page-filter')) {
			$(".product-page-filter-body").slideUp();
		}
	});


	$('body').on('click', '.qty-btn.plus', function() {
		var qtyInput = $(this).parents('.qty-wrapper').find('.qty')
		var cv = Number(qtyInput.val());
		qtyInput.attr('value', cv + 1);
		qtyInput.trigger('change');
	});
	$('body').on('click', '.qty-btn.minus', function() {
		var qtyInput = $(this).parents('.qty-wrapper').find('.qty')
		var cv = Number(qtyInput.val());
		var nv = cv - 1;
		if(nv == 0) nv = 1;
		qtyInput.attr('value', nv);
		qtyInput.trigger('change');
	});


	$('.button.theme_add_to_quote').on('click', function() {
		var c = Number($('.request-quote-icon--count').text());
		if(!c) c = 0;
		$('.request-quote-icon--count').text(c+1);
	})
	$('#yith-ywraq-form .yith-ywraq-item-remove').on('click', function() {
		var c = Number($('.request-quote-icon--count').text());
		c = c-1;
		if(c == 0) $('.request-quote-icon--count').text('');
		else $('.request-quote-icon--count').text(c);
	})


	//custom select for sign up page
	$(".custom-select").each(function() {
		var classes = $(this).attr("class"),
		id      = $(this).attr("id"),
		name    = $(this).attr("name");
		var template =  '<div class="' + classes + '">';
		template += '<span class="custom-select-trigger">' + $(this).attr("placeholder") + '</span>';
		template += '<div class="custom-options">';
		$(this).find("option").each(function() {
			template += '<span class="custom-option ' + $(this).attr("class") + '" data-value="' + $(this).attr("value") + '">' + $(this).html() + '</span>';
		});
		template += '</div></div>';

		$(this).wrap('<div class="custom-select-wrapper"></div>');
		$(this).hide();
		$(this).after(template);
	});
	$(".custom-option:first-of-type").hover(function() {
		$(this).parents(".custom-options").addClass("option-hover");
	}, function() {
		$(this).parents(".custom-options").removeClass("option-hover");
	});
	$(".custom-select-trigger").on("click", function(event) {
		$('html').one('click',function() {
			$(".custom-select").removeClass("opened");
		});
		$(this).parents(".custom-select").toggleClass("opened");
		event.stopPropagation();
	});
	$(".custom-option").on("click", function() {
		$(this).parents(".custom-select-wrapper").find("select").val($(this).data("value"));
		$(this).parents(".custom-options").find(".custom-option").removeClass("selection");
		$(this).addClass("selection");
		$(this).parents(".custom-select").removeClass("opened");
		$(this).parents(".custom-select").find(".custom-select-trigger").text($(this).text());
	});

	if($('.single-collections .related__projects_items').length > 0) {
		const related__projects_items = new Swiper('.single-collections .related__projects_items .swiper', {
			slidesPerView: 2,
			spaceBetween: 30,
			scrollbar: {
				el: '.swiper-scrollbar',
				draggable: true,
				dragSize: 'auto'
			},
			breakpoints: {
				640: {
					slidesPerView: 2,
					spaceBetween: 32,
				},
				320: {
					slidesPerView: 1,
					spaceBetween: 20,
				},
			},
		});
	}

	if($('.single-collections .related__collections_items').length > 0) {
		const related__collections_items = new Swiper('.single-collections .related__collections_items .swiper', {
			slidesPerView: 4,
			pagination: {
				enabled: false
			},
			breakpoints: {
				768: {
					slidesPerView: 4,
					pagination: {
						enabled: false
					},
				},
				320: {
					slidesPerView: 2,
					spaceBetween:12,
					pagination: {
						enabled: true,
						el: '.swiper-pagination',
						clickable: true
					},
				},
			},
		});
	}

	(function () {

		const breakpoint = window.matchMedia('(min-width:475px)');
		let mySwiper;
		const breakpointChecker = function () {

			if (breakpoint.matches === true) {

				if (mySwiper !== undefined) mySwiper.destroy(true, true);

				return;

			} else if (breakpoint.matches === false) {

				return enableSwiper();

			}

		};

		const enableSwiper = function () {

			mySwiper = new Swiper('.wishlist-slider', {
				loop: true,
				spaceBetween: 30,
				slidesPerView: '1',
				centeredSlides: true,
				a11y: true,

				pagination: {
					el: ".swiper-pagination",
					clickable: true,
					renderBullet: function (index, className) {
						return '<span class="' + className + '"></span>';
					},
				},
			});

		};

		breakpoint.addEventListener("change", breakpointChecker);
		breakpointChecker();
	})();

})


jQuery( document ).ready(function($) {
	const baseClass = 'elementor-widget-cmsmasters-theme-blog';
	const selectors = {
		widgetWrapper: '.stone__products_list',
		header: `.${ baseClass }__header`,

		post: `.${ baseClass }__post`,
		popupTrigger: `.${ baseClass }__post-add-to-button-trigger`,
		popupPopup: `.${ baseClass }__post-add-to-quote-popup`,
		popupClose: `.${ baseClass }__post-add-to-quote-popup-close`,
		addToQuote: `.${ baseClass }__post-add-to-quote`,
		inputs: `.${ baseClass }__post-add-to-quote-inputs`,
		inputItem: `.${ baseClass }__post-add-to-quote-input-item`,
		input: `.${ baseClass }__post-add-to-quote-input`,
		inputOperator: `.${ baseClass }__post-add-to-quote-input-operator`,
		quoteButtonWrap:  `.${ baseClass }__post-add-to-quote-button-wrap`,
		quoteButton: '.theme_add_to_quote_popup',

		wrapper: `.${ baseClass }__wrapper`,
		wrapperInner: `.${ baseClass }__wrapper-inner`,
		elementorWidgetContainer: '.elementor-widget-container',
		popup: '.popup',
		checkbox: `.${ baseClass }__post-add-to-quote-popup-choose-product-attr-checkbox`,
		popupCont: `.${ baseClass }__post-add-to-quote-popup-cont`,
		popupChooseProduct: `.${ baseClass }__post-add-to-quote-popup-choose-product`,
		popupChooseInnerContWrap: `.${ baseClass }__post-add-to-quote-popup-collection-inner-cont-wrap`,
		addToCart: '.single_add_to_cart_button',
	};

	//console.log(selectors);


	function bindEvents() {
		$(selectors.widgetWrapper).on( 'click', selectors.popupTrigger,  function( event ) {
			popupTriggerClick( event );
		} );

		$(selectors.widgetWrapper).on( 'click', selectors.popupClose, function( event ) {
			popupCloseClick( event );
		} );

		$(selectors.widgetWrapper).on( 'click', selectors.inputOperator, function( event ) {
			inputOperatorClick( event );
		} );

		$(selectors.widgetWrapper).on( 'click', selectors.quoteButton, function( event ) {
			quoteButtonClick( event );
		} );

		$(selectors.widgetWrapper).on( 'click', selectors.checkbox, function( event ) {
			checkboxClick( event );
		} );

		$(selectors.widgetWrapper).on( 'input', selectors.input, function( event ) {
			textInput( event );
		} );

		jQuery( document ).on( 'input', function( event ) {
			activeInput( event );
		} );

		$(selectors.widgetWrapper).on( 'click', selectors.addToCart, function( event ) {
			addingToCartAjax( event );
		} );

	}

	bindEvents();

	//++
	function popupTriggerClick( event ) {
		const $this = jQuery( event.currentTarget );

		$this.addClass( 'active' );

		$this
			.closest( selectors.post )
			.find( selectors.popupPopup )
			.addClass( 'visible' );

		$this.closest( 'html' ).css( 'overflow-y', 'hidden' );
	}

	//++
	function popupCloseClick( event ) {
		const $this = jQuery( event.currentTarget );

		$this
			.closest( selectors.post )
			.find( selectors.popupPopup )
			.removeClass( 'visible' );

		$this
			.closest( selectors.post )
			.find( selectors.popupTrigger )
			.removeClass( 'active' );

		$this.closest( 'html' ).css( 'overflow-y', '' );
	}

	//++
	function inputOperatorClick( event ) {

		const $this = jQuery( event.currentTarget );
		const $inputItem = $this.parent();
		const $input = $inputItem.find( selectors.input );
		const isIncrement = $this.hasClass( 'increment' );

		if ( isIncrement ) {
			incrementValue( $input );
		} else {
			decrementValue( $input );
		}

		anotherInputChange( $this, $inputItem, $input );

		activeInput();
	}

	//++
	function incrementValue( $input ) {
		let value = parseInt( $input.val(), 10 ) || 0;
		value++;
		$input.val( value );
	}

	//++
	function decrementValue( $input ) {
		let value = parseInt( $input.val(), 10) || 0;
		if ( value > 0 ) {
			value--;
			$input.val( value );
		}
	}

	//++
	function anotherInputChange( $this, $inputItem, $input ) {

		const $uom = $inputItem.attr( 'product-uom' );
		const $width = $inputItem.attr( 'product-width' );
		const $height = $inputItem.attr( 'product-height' );

		let $uom_cn = $inputItem.attr( 'product-uom-cn' );
		console.log($uom_cn);
		let roundedResult = 0;
		if ( typeof $uom_cn !== 'undefined') { roundedResult = $uom_cn }
		else { 
			let area = $width * $height / 144;
			roundedResult = Number.isInteger( area ) ? area : area.toFixed( 2 );
		}
		const inputValue = parseFloat( $input.val() );

		let newVal = '';

		if ( 'each' === $uom ) {
			newVal = Number.isInteger( inputValue * roundedResult ) ? inputValue * roundedResult : (inputValue * roundedResult).toFixed( 2 );
		} else {
			newVal = Math.ceil( inputValue / roundedResult );
		}

		if ( ! isNaN( inputValue ) ) {
			newVal = newVal;
		} else {
			newVal = '';
		}

		const anotherInputUom = ( 'each' === $uom ? 'sqft' : 'each' );
		const $anotherInput = $this
			.closest( selectors.inputs )
			.find( selectors.inputItem + '[product-uom="' + anotherInputUom + '"]' )
			.find( selectors.input );

		$anotherInput.val( newVal );
	}

	//++
	function textInput( event ) {
		const $this = jQuery( event.currentTarget );
		const $inputItem = $this.parent();
		const $input = $inputItem.find( selectors.input );

		anotherInputChange( $this, $inputItem, $input );

		activeInput();
	}

	//++
	function activeInput() {

		jQuery(selectors.inputs).find( selectors.inputItem ).each( function () {
			const $inputItem = jQuery( this );
			const $input = $inputItem.find( selectors.input );

			if ( $input.val().trim() !== '' ) {
				$inputItem.addClass( 'active' );

				$inputItem
					.find( '.decrement' )
					.removeClass( 'disable' );
			}

			$input.focus( function() {
				$inputItem.addClass( 'active' );
			} );

			$input.blur( function () {
				if ( ! $input.is( ':focus' ) && $input.val().trim() === '' ) {
					$inputItem.removeClass( 'active' );
				}
			} );
		} );
	}



	//++
	function checkboxClick( event ) {
		const $this = jQuery( event.currentTarget );
		const productID = $this.attr( 'id' );
		const dataWpNonce = $this.attr( 'data-wp_nonce' );
		const dataUoms = $this.attr( 'data-uoms' ).toLowerCase();
		const dataProductWidth = $this.attr( 'data-product-width' );
		const dataProductHeight = $this.attr( 'data-product-height' );
		const $popupCont = $this.closest( selectors.popupCont );
		const $addToQuote = $popupCont.find( selectors.addToQuote );
		const $quoteButton = $popupCont.find( selectors.quoteButton );
		const $inputItem = $popupCont.find( selectors.inputItem );

		$this.closest( selectors.popupCont ).find( selectors.checkbox ).each( function () {
			const $checkbox = jQuery( this );

			$checkbox
				.closest( selectors.popupChooseProduct )
				.removeClass( 'choose' )
				.addClass( 'not_chooses' );
		} );

		$this
			.closest( selectors.popupChooseProduct )
			.removeClass( 'not_chooses' )
			.addClass( 'choose' );

		$addToQuote.attr( 'product-uom', dataUoms );
		
		let dataUomCN = $this.attr( 'data-product-uom-cn' );
		if ( typeof dataUomCN !== 'undefined') { $inputItem.attr( 'product-uom-cn', dataUomCN );}
		else {$inputItem.removeAttr( 'product-uom-cn'); }

		$quoteButton
			.attr( 'data-product_id', productID )
			.attr( 'data-wp_nonce', dataWpNonce );

		$inputItem
			.attr( 'product-width', dataProductWidth )
			.attr( 'product-height', dataProductHeight );

		$popupCont
			.find( selectors.input )
			.val( '' );

		$popupCont
			.find( selectors.popupChooseInnerContWrap )
			.val( '' );

		if ( $this.closest( selectors.popupChooseProduct ).hasClass( 'added-product' ) ) {
			$popupCont
				.find( '.yith-ywraq-add-button' )
				.removeClass( 'choose_show' )
				.addClass( 'choose_hide' );
		} else {
			$popupCont
				.find( '.yith-ywraq-add-button' )
				.removeClass( 'choose_hide' )
				.addClass( 'choose_show' );
		}

		$popupCont
			.find( selectors.popupChooseInnerContWrap )
			.removeClass( 'show' );

		$popupCont
			.find( `${selectors.popupChooseInnerContWrap}[product-id="${productID}"]` )
			.addClass( 'show' );
	}


	function quoteButtonClick( event ) {

		event.preventDefault();

		const $this = jQuery( event.currentTarget );
		const $quoteButtonWrap = $this.closest( selectors.quoteButtonWrap );
		let $add_to_cart_el = null;
		let $product_id_el = null;

		if ( $this.parents('ul.products').length > 0 ) {
			$add_to_cart_el = $this.parents( 'li.product' ).find( 'input[name="add-to-cart"]' );
			$product_id_el = $this.parents( 'li.product' ).find( 'input[name="product_id"]' );
		} else {
			$add_to_cart_el = $this.parents('.product').find('input[name="add-to-cart"]');
			$product_id_el = $this.parents('.product').find('input[name="product_id"]');
		}

		const $addToQuote = $this.closest( selectors.addToQuote );
		const wpnonce = $this.data( 'wp_nonce' );
		const productId = $this.data( 'product_id' );
		const listText = $this.data( 'list_text' );
		const uom = $addToQuote.attr( 'product-uom' );
		const $input = $addToQuote.find( selectors.inputItem + '[product-uom="' + uom + '"] input' );
		let inputValue = $input.val();

		if ( isNaN( inputValue ) || 0 === inputValue.length ) {
			inputValue = 1;
		}

		const isPremium = $quoteButtonWrap.hasClass( 'quote_premium' );
		let add_to_cart_info;

		if ( isPremium ) {
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

			if ( $add_to_cart_el.length > 0 && $product_id_el.length > 0 ) {
				add_to_cart_info = jQuery( '.cart' ).serialize();
			} else if ( jQuery( '.cart' ).length > 0 ) {
				add_to_cart_info = jQuery( '.cart' ).serialize();
			}

			add_to_cart_info += '&action=yith_ywraq_action&ywraq_action=add_item&quantity=' + inputValue + '&product_id=' + productId + '&_wpnonce=' + wpnonce;
		}

		const ywraqUrl = ( isPremium ? ywraq_frontend.ajaxurl.toString().replace('%%endpoint%%', 'yith_ywraq_action') : ywraq_frontend.ajaxurl );

		jQuery.ajax( {
			type: 'POST',
			url: ywraqUrl,
			dataType: 'json',
			data: add_to_cart_info,
			contentType: false,
			processData: false,
			beforeSend: function () {
				$this.siblings( '.ajax-loading' ).css( {
					opacity: '1',
					visibility: 'visible',
				} );

				$this
					.parent()
					.addClass( 'choose_hide' )
					.removeClass( 'choose_show' )
					.closest( selectors.popupCont )
					.find( `${selectors.popupChooseProduct}.choose` )
					.addClass( 'added-product' );
			},
			complete: function () {
				$this.siblings( '.ajax-loading' ).css( {
					opacity: '0',
					visibility: 'hidden',
				} );
			},
			success: ( response ) => {
				if ( response.result == 'true' || response.result == 'exists' ) {
					if ( ywraq_frontend.go_to_the_list === 'yes' ) {
						window.location.href = response.rqa_url;
					} else {
						$this
							.parent()
							.hide()
							.removeClass( 'show' )
							.addClass( 'addedd' );

						const nextElement = $this
							.parent()
							.next();

						if ( ! nextElement.length > 0 ) {
							const prod_id = ( typeof $product_id_el.val() == 'undefined' ) ? '' : '-' + $product_id_el.val();

							$quoteButtonWrap.append( '<div class="yith_ywraq_add_item_browse-list' + prod_id + ' yith_ywraq_add_item_browse_message"><a href="' + response.rqa_url + '">' + listText + '</a></div>' );
						}
					}

				} else if ( response.result == 'false' ) {
					$quoteButtonWrap.append( '<div class="yith_ywraq_add_item_response-' + $product_id_el.val() + '">' + response.message + '</div>' );
				}

				// For quote count
				let count = Number( jQuery( '.request-quote-icon--count' ).text() );

				if ( ! count ) count = 0;

				jQuery( '.request-quote-icon--count' ).text( count + 1 );

				const $message = jQuery('<div class="post-add-to-button-success-message fas fa-check"><span>The product has been added to your qoute list.</span><a href="/request-quote/">Open</a></div>');

				$addToQuote.append( $message );

				setTimeout(function() {
					$message.fadeOut( 300, function() {
						jQuery( this ).remove();
					} );
				}, 3000 );
			}
		} );
	}

	function run() {
		const requestManager = this;
		const originalCallback = requestManager.requests[0].complete;

		requestManager.requests[0].complete = function() {
			if ( typeof originalCallback === 'function' ) {
				originalCallback();
			}

			requestManager.requests.shift();

			if ( requestManager.requests.length > 0 ) {
				requestManager.run();
			}
		};

		jQuery.ajax( this.requests[0] );
	}

	function addRequest( request ) {
		this.requests = [];
		this.requests.push( request );

		if ( 1 === this.requests.length ) {
			run();
		}
	}

	function addingToCartAjax( event ) {
		event.preventDefault();

		if ( this.isEdit ) {
			return;
		}

		const $this = jQuery( event.currentTarget );

		if ( ! $this.attr( 'data-product_id' ) ) {
			return true;
		}

		event.preventDefault();

		if ( false === jQuery( document.body ).triggerHandler( 'should_send_ajax_request.adding_to_cart', [ $this ] ) ) {
			jQuery( document.body ).trigger( 'ajax_request_not_sent.adding_to_cart', [ false, false, $this ] );

			return true;
		}

		const data = {};

		const $addToQuote = $this.closest( selectors.addToQuote );
		const uom = $addToQuote.attr( 'product-uom' );
		const $input = $addToQuote.find( selectors.inputItem + '[product-uom="' + uom + '"] input' );
		let inputValue = $input.val();

		if ( isNaN( inputValue ) || 0 === inputValue.length ) {
			inputValue = 1;
		}

		data['quantity'] = inputValue;

		jQuery.each( $this.data(), function( key, value ) {
			data[ key ] = value;
		} );

		jQuery.each( $this[0].dataset, function( key, value ) {
			data[ key ] = value;
		} );

		jQuery( document.body ).trigger( 'adding_to_cart', [ $this, data ] );

		addRequest( {
			type: 'POST',
			url: wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'add_to_cart' ),
			data: data,
			beforeSend: function () {
				$this.find( '.ajax-loading' ).css( {
					opacity: '1',
					visibility: 'visible',
				} );
			},
			complete: function () {
				$this.find( '.ajax-loading' ).css( {
					opacity: '0',
					visibility: 'hidden',
				} );
			},
			success: function ( response ) {
				if ( ! response ) {
					return;
				}

				if ( response.error && response.product_url ) {
					window.location = response.product_url;

					return;
				}

				if ( wc_add_to_cart_params.cart_redirect_after_add === 'yes' ) {
					window.location = wc_add_to_cart_params.cart_url;

					return;
				}

				jQuery( document.body ).trigger( 'added_to_cart', [ response.fragments, $this ] );

				const $message = jQuery('<div class="post-add-to-button-success-message fas fa-check"><span>The product has been added to your cart.</span><a href="/cart/">Open</a></div>');

				$addToQuote.append( $message );

				setTimeout(function() {
					$message.fadeOut( 300, function() {
						jQuery( this ).remove();
					} );
				}, 3000 );
			},
			dataType: 'json'
		} );
	}
});




jQuery(function($) {
	if($('.collections__images').length > 0) {
		const collection_images = new Swiper('.collections__images', {
			loop: true,
			slidesPerView: 1,
			centeredSlides: true,
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
			autoplay: {
				delay: 3000,
				disableOnInteraction: false,
			},
		});
	}
})
