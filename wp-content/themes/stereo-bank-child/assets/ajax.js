jQuery(function($) {
	
	/******STONE Search******/
	function start_search() {
		var s_word = $('#stone_search').val();
		s_word = s_word.trim();
		if(s_word.length > 2) {
			var data = {
				'action': 'stone_search_items',
				's_word': s_word
			};
			$.ajax({
				type: "POST",
				url: '/wp-admin/admin-ajax.php',
				data: data,
				beforeSend : function(){
					$('.search-bar svg.lupa').hide();
					$('.search-bar svg.searching').show();
				},
				success: function (response) {
					$('#search__results').html(response);
					$('.search__popup_wrapper').addClass('search-have--results');
					$('.search-bar svg.searching').hide();
					$('.search-bar svg.clear').show();
				}
			});
		}
	}
		
	function search_start_event(evt) {
		const tag = evt.target.value;
		if(evt.key =='Enter' || evt.key == 13) {
			start_search();
		} 
		else if (evt.key == "Backspace" || evt.key == 8) {
			if (tag == "") { // If empty
				$('#search__results').html('');
			}
		}
	}
	
	$('.search__popup_wrapper > i').on('click', function() {
		$('.search__popup_wrapper').toggleClass('search--open');
		$('.search__popup_wrapper').removeClass('search-have--results');
		$('#stone_search').focus();
	})
	$('.search__popup_bg, .search__popup_close').on('click', function() {
		$('.search__popup_wrapper').toggleClass('search--open');
		$('.search__popup_wrapper').toggleClass('search-have--results');
	})	
	$('#stone_search').on('keydown', search_start_event);
	$('.search-bar svg.lupa').on('click', start_search);
	$('.search-bar svg.clear').on('click', function() {
		$('#search__results').html('');
		$('#stone_search').val('');
		$('.search-bar svg.clear').hide();
		$('.search-bar svg.lupa').show();
		$('.search__popup_wrapper').removeClass('search-have--results');
	});
	$('.search-bar').on('submit', function(e) {
		e.preventDefault();
	});
	
	
	
	
	
	/******Products Sheet Filters******/
	$('#product-sheet--filter .product-page-filter .checked-item label').on('click', function() {
		var filter_body = $(this).parents('.product-page-filter-item');

		setTimeout(function() {
			var filter_items = collect_filter_data('main');

			var data = {
				'action': 'stone_filter_products',
				'filters': filter_items
			};
			$.ajax({
				type: "POST",
				url: '/wp-admin/admin-ajax.php',
				data: data,
				beforeSend : function(){
					filter_body.find('.title').toggleClass('active');
					//filter_body.find('.product-page-filter-body').slideToggle();  
					$('#products--filter-response').addClass('loading');

					//filter_body.find('.title span').text('').attr('data-count', count);
				},
				success: function (response) {
					$('#products--filter-response').removeClass('loading');
					$('#products--filter-response').html(response);
				}
			});
		}, 100)		
	})
	
	//show/hide product sheet filter items
	$('#product-sheet--filter .mobile-filter--controller .active-filter-popup').on('click', function() {	
		$('#product-sheet--filter .product-sheet--filterbody').fadeToggle();
		$('#advanced_filter__wrapper').addClass('higher_z');
	});
	$('#product-sheet--filter .product-sheet--filterbody .elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-popup-close .theme-icon-close').on('click', function() {	
		$('#product-sheet--filter .product-sheet--filterbody').fadeToggle();
		$('html, body').removeClass('lock');
		$('#product-sheet--filter .mobile-filter--controller .active-filter-popup .title').removeClass('active');
		$('#advanced_filter__wrapper').removeClass('higher_z');
	});
	
	
	/*designer projects Filters*/
	$('.designer-projects-filter .product-page-filter-item .filter-button').on('click', function() {
		var filter_body = $(this).parents('.product-page-filter-item');
		var count = filter_body.find('input:checked').length;
		var filter_items = new Object();
		
		
		$('.designer-projects-filter .product-page-filter-item').each(function() {
			var filter_by = $(this).data('filter-by');
			if(filter_by) {
				filter_items[filter_by] = new Array();
				$(this).find('input:checked').each(function() {
					filter_items[filter_by].push($(this).val());
				})	
			}			
		})
		
		var sortby = $('.designer-projects-filter .product-page-filter-item.sortby input:checked').val();
		
		var data = {
			'action': 'stone_filter_designer_projects',
			'designer_id' : Number($('#designer_id').val()),
			'filters': filter_items,
			'sortby' : sortby
		};
		

		$.ajax({
			type: "POST",
			url: '/wp-admin/admin-ajax.php',
			data: data,
			beforeSend : function(){
				filter_body.find('.title').toggleClass('active');
				filter_body.find('.product-page-filter-body').slideToggle();  
				$('#projects--filter-response').addClass('loading');
				
				//filter_body.find('.title span').attr('data-count', count);
			},
			success: function (response) {
				$('#projects--filter-response').removeClass('loading');
				$('#projects--filter-response').html(response);
			}
		});
	});
	
	
	/************Collection Filters**********/
	$('.collections-filter .product-page-filter-item .filter-button').on('click', function() {
		var filter_body = $(this).parents('.product-page-filter-item');
		var count = filter_body.find('input:checked').length;
		var filter_items = new Object();
			
		$('.collections-filter .product-page-filter-item').each(function() {
			var filter_by = $(this).data('filter-by');
			if(filter_by) {
				filter_items[filter_by] = new Array();
				$(this).find('input:checked').each(function() {
					filter_items[filter_by].push($(this).val());
				})	
			}			
		})
		
		var sortby = $('.collections-filter .product-page-filter-item.sortby input:checked').val();
		
		var data = {
			'action': 'stone_collection_filter',
			'collection_slug' : $('#collection_slug').val(),
			'collection_id' : Number($('#collection_id').val()),
			'filters': filter_items,
			'sortby' : sortby
		};
		
		$.ajax({
			type: "POST",
			url: '/wp-admin/admin-ajax.php',
			data: data,
			beforeSend : function(){
				filter_body.find('.title').toggleClass('active');
				filter_body.find('.product-page-filter-body').slideToggle();  
				$('#products').addClass('loading');
			},
			success: function (response) {
				$('#products').removeClass('loading');
				$('#products').html(response);
			}
		});
	})
		
	$('.product-page-filter-item .checked-item label').on('click', function() {
		var filter_body = $(this).parents('.product-page-filter-item');
		
		setTimeout(function() {
			var count = filter_body.find('input:checked').length;
			if(count == 0) {
				var d_t = filter_body.find('.title').data('default-title');
				filter_body.find('.title span').text(d_t);
				filter_body.find('.title span').attr('data-count', '0');
			}
			else if(count == 1) {
				filter_body.find('.title span').attr('data-count', '0');
				var f_el = filter_body.find('input:checked').next('label').text();
				filter_body.find('.title span').text(f_el);
			}
			else if(count > 1) {
				filter_body.find('.title span').attr('data-count', (count - 1));
			}
			
			var summary_count_checked_items = $('.product-page-filter-item .checked-item input:checked').length;	
			if(summary_count_checked_items >= 1) {
				$('.advanced-filters .filter-submit').prop('disabled', false);
			}
			else $('.advanced-filters .filter-submit').prop('disabled', true);
			
		},100)
	
	})
	
	
	
	/*******Product Filter*******/
	
	//collect filtering data
	function collect_filter_data(filter) {
		if(filter == 'products') {
			var container = $('.stone__products_list.products_list .elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list');
			var data = new Object();
			container.find('.elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item').each(function() {
				var filter_by = $(this).data('taxonomy-id');
				var values = new Array();
				
				$(this)
				.find('.elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list .elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item .elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item-checkbox:checked')
				.each(function() {
					values.push($(this).parent('li').data('category-id'));
				});
				
				data[filter_by] = values;
			});
		}
		else if(filter == 'main') {
			var container = $('#advanced_filter__wrapper .product-page-filter');
			var data = new Object();
			container.find('.product-page-filter-item').each(function() {
				var filter_by = $(this).data('filter-by');
				var values = new Array();
				$(this).find('.checked-item input:checked').each(function() {
					values.push($(this).attr('id'));
				});
				data[filter_by] = values;
			});
		}
	
		return data;
	}
	
	
	//main products filter function
	function products_filter(task, filterData ) {
		if(filterData) var filter_data = filterData;
		else var filter_data = collect_filter_data('products');
		
		//console.log(filter_data);
		
		var data = {
			'action': 'filter_products',
			'filters': filter_data,
			'outlet' : Number($('input#outlet').val()),
			'page' : Number($('input#page').val()),
		};
		
		
	
		$.ajax({
			type: "POST",
			url: '/wp-admin/admin-ajax.php',
			data: data,
			dataType: 'json',
			beforeSend : function(){
				$('#filter-products-response').addClass('loading');
				if(task == 'load_more') {
					$('.load_more_products--button').addClass('disabled').text($('.load_more_products--button').data('loading-text'));
				}
			},
			success: function (response) {
				$('#filter-products-response').removeClass('loading');
				if(task == 'filter') {
					//$('#filter-products-response').html(response);
					$('#filter-products-response').html(response.html);
					$('#max-pages').val(response.max_pages);
					if(response.max_pages == 1) $('.load_more_products--button').hide();
					else $('.load_more_products--button').show();
					
					$('.found_posts__wrapper span').text(response.found_posts)
				}
				else {
					$('#filter-products-response').append(response.html);
					$('.load_more_products--button').removeClass('disabled').text($('.load_more_products--button').data('default-text'));
					var page = Number($('input#page').val());
					if(page == Number($('#max-pages').val())) $('.load_more_products--button').hide();
					else $('.load_more_products--button').show();
				}
				
				
			}
		});
	}
	
	//load more products
	$('.load_more_products--button').on('click', function(e) {
		e.preventDefault();
		var page = Number($('input#page').val());
		var next_page = page + 1;
		
		$('input#page').val(next_page);
		
		setTimeout(function() {
			products_filter('load_more', false);
		}, 100)
	})
	
	//show/hide flter items
	$('.products_list.stone__products_list .elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger-value, .elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger-icon').on('click', function() {	
		$(this).parents('.elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger').toggleClass('active');
		var $trigger_p = $(this).parents('.elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item');
		$trigger_p.find('.elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list').fadeToggle();
	});
	
	
	//Clear filter
	$('.products_list.stone__products_list .elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger-clear').on('click', function() {	
		var ul = $(this).parents('.elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item').find('.elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list');
		ul.fadeToggle();
		ul.find('.elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item').removeClass('checked');
		ul.find('.elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item input').prop('checked', false);
		$(this).parents('.elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger').removeClass('active').addClass('default-value');

		var filter_v = $(this).parents('.elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger').find('.elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger-value');
		filter_v.text(filter_v.data('default'));
		
		$(this).parents('.elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger').removeClass('suptitle-active');
		
		setTimeout(function() {
			//start filtering filter
			var filter_data = collect_filter_data('products');			
			dynamic_filter('products', filter_data);
			
			//start filtering products
			products_filter('filter', filter_data);
			
		}, 100);
	});
	
	
	//Start filtering after click on filter element
	$('.products_list.stone__products_list .elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list .elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item label').on('click', function() {	
		var _this = $(this);
		var _this_text = _this.text();
		var $li = _this.parent('.elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item');
		$li.toggleClass('checked');
		
		$('input#page').val(1);
		
		setTimeout(function() {
			var parent_ul = _this.parents('.elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item');
			var ul = _this.parents('.elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list');
			var trigger = parent_ul.find('.elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger-value');
			var count = ul.find('input:checked').length;
			
			if(count == 0) {
				parent_ul.find('.elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger').addClass('default-value');
				trigger.text(trigger.data('default'));
				parent_ul.find('.elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger').removeClass('suptitle-active');
			}
			else if(count == 1) {
				parent_ul.find('.elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger').removeClass('default-value');
				trigger.data('new-value', _this_text);
				trigger.data('more', 1);
				trigger.text(ul.find('input:checked').eq(0).next('label').text());
				parent_ul.find('.elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger').addClass('suptitle-active');
			}
			else if(count > 1) {
				parent_ul.find('.elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger').removeClass('default-value');
				trigger.data('more', count);
				var first_el_name = ul.find('input:checked').eq(0).next('label').text();
				trigger.text(first_el_name + ' + ' + (count - 1));
				parent_ul.find('.elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger').addClass('suptitle-active');
				
			}
			
			//start filtering filter
			var filter_data = collect_filter_data('products');			
			dynamic_filter('products', filter_data);
			
			//start filtering products
			products_filter('filter', filter_data);	
							
			
		}, 100)				   
	});
	
		
	/*Dynamic Filter items Function*/	
	function dynamic_filter(filter, filter_data) {
		var data = {
			'action': 'dynamic_filter',
			'filters': filter_data,
			'outlet' : Number($('input#outlet').val()),
		};

		
				
		$.ajax({
			type: "POST",
			url: '/wp-admin/admin-ajax.php',
			data: data,
			dataType: 'json',
			beforeSend : function(){},
			success: function (data) {
	
				//hide non-related items in filter
				for(key in data) {
					var filter_tax = key;
					if(data.hasOwnProperty(key)) {
						var values = data[key];
						var ids = Object.keys(values).map(function(id) {
							return Number(id); 
						});
						
						if(filter == 'main') var element = '.product-page-filter-item[data-filter-by="'+filter_tax+'"] .checked-item';
						else if(filter == 'products') var element = '.elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item[data-taxonomy-id="'+filter_tax+'"] .elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item';
						
						if(element) {
							$(element).each(function() {
								var tax_item_id = Number($(this).find('input').attr('id'));

								//console.log(tax_item_id);
								if(ids.includes(tax_item_id) && values[tax_item_id] != 0) {
									$(this).find('label').attr('data-count', values[tax_item_id]);
									$(this).removeClass('disabled');
								}
								else {
									$(this).find('label').attr('data-count', '0');
									$(this).addClass('disabled');
								}
							})
						}	

					}
				}
				
				
			}
		});
	}
	
	
	$('#advanced_filter__wrapper .product-page-filter .checked-item label').on('click', function() {
		setTimeout(function() {
			var filter_data = collect_filter_data('main');			
			dynamic_filter('main', filter_data);
		}, 100)
	})
	
	
	/*Mobile sorting products*/
	$('body').on('click', '.products_list.stone__products_list .mobile-filter--controller .product-page-filter-item.sorting .elementor-widget-cmsmasters-theme-blog-grid__header-side .elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item:not(.checked) label', function() {
		$('.products_list.stone__products_list .mobile-filter--controller .product-page-filter-item.sorting .elementor-widget-cmsmasters-theme-blog-grid__header-side .elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item').removeClass('checked');
		$(this).parent('li').addClass('checked');
		$(this).parents('.elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger-value').text($(this).text());
		
		//prepare sorting value in main filter before start filter
		$('.products_list.stone__products_list .elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list .elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list.sorting input').prop('checked', false);
		var id = $(this).parent('li').find('input').val();
		$('.products_list.stone__products_list .elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list .elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list.sorting input#'+id).prop('checked', true);
		
		setTimeout(function() {
			products_filter('filter', false);
		}, 100);
		
	})
	
	
	//Fixing Product ASide filter sorting
	$('.products_list.stone__products_list .elementor-widget-cmsmasters-theme-blog-grid__header-side.cmsmasters-filter-nav-multiple .elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list.sorting .elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item label').on('click', function() {
		$('.products_list.stone__products_list .elementor-widget-cmsmasters-theme-blog-grid__header-side.cmsmasters-filter-nav-multiple .elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list.sorting .elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item').removeClass('checked');
		$(this).parent('.elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item').addClass('checked');
	})

	function getACFField(postID, fieldName) {
		return new Promise((resolve, reject) => {
		  $.ajax({
			type: 'POST',
			url: '/wp-admin/admin-ajax.php', 
			data: {
			  action: 'get_acf_field', 
			  post_id: postID,
			  field_name: fieldName,
			},
			success: function(data) {
			  resolve(data);
			},
			error: function(error) {
			  reject(error);
			}
		  });
		});
	  }
	
//available

})
