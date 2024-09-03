<?php
function stone_search_popup_shortcode ( $atts, $content = null ) {
	?>

	<div class="search__popup_wrapper">
		<i class="themeicon- theme-icon-search-light"></i>
		<div class="search__popup_wrapper--inner">
			<div class="search__popup_body">
				<div class="search__popup_container">
					<div class="search__popup_form--container">
						<form class="search-bar" onsubmit="return false;">
							<input type="search" name="stone_search" id="stone_search" placeholder="SEARCH" value="" autocomplete="off">
							<button>
								<svg class="lupa" width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_140_1030)"> <path d="M25.6015 23.3902L19.7074 17.4961C21.1265 15.6071 21.8925 13.3076 21.8899 10.945C21.8899 4.90995 16.98 0 10.945 0C4.90995 0 0 4.90995 0 10.945C0 16.98 4.90995 21.8899 10.945 21.8899C13.3076 21.8925 15.6071 21.1265 17.4961 19.7074L23.3902 25.6015C23.6886 25.8682 24.0777 26.0106 24.4777 25.9994C24.8778 25.9882 25.2583 25.8243 25.5413 25.5413C25.8243 25.2583 25.9882 24.8778 25.9994 24.4777C26.0106 24.0777 25.8682 23.6886 25.6015 23.3902ZM3.12713 10.945C3.12713 9.39874 3.58564 7.88724 4.44467 6.60161C5.30371 5.31597 6.52468 4.31394 7.95321 3.72223C9.38173 3.13052 10.9536 2.9757 12.4701 3.27735C13.9867 3.579 15.3797 4.32358 16.473 5.41692C17.5663 6.51026 18.3109 7.90327 18.6126 9.41978C18.9142 10.9363 18.7594 12.5082 18.1677 13.9367C17.576 15.3652 16.5739 16.5862 15.2883 17.4452C14.0027 18.3043 12.4912 18.7628 10.945 18.7628C8.87231 18.7603 6.88526 17.9358 5.41967 16.4703C3.95408 15.0047 3.12962 13.0176 3.12713 10.945Z" fill="#BDBDBD"></path> </g> <defs> <clipPath id="clip0_140_1030"> <rect width="26" height="26" fill="#BDBDBD"></rect> </clipPath> </defs> </svg>

								<svg class="searching" style="display:none" version="1.1" id="L7" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"> <path fill="#BDBDBD" d="M31.6,3.5C5.9,13.6-6.6,42.7,3.5,68.4c10.1,25.7,39.2,38.3,64.9,28.1l-3.1-7.9c-21.3,8.4-45.4-2-53.8-23.3 c-8.4-21.3,2-45.4,23.3-53.8L31.6,3.5z"> <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="2s" from="0 50 50" to="360 50 50" repeatCount="indefinite"/> </path> <path fill="#BDBDBD" d="M42.3,39.6c5.7-4.3,13.9-3.1,18.1,2.7c4.3,5.7,3.1,13.9-2.7,18.1l4.1,5.5c8.8-6.5,10.6-19,4.1-27.7 c-6.5-8.8-19-10.6-27.7-4.1L42.3,39.6z"> <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="-360 50 50" repeatCount="indefinite"/> </path> <path fill="#BDBDBD" d="M82,35.7C74.1,18,53.4,10.1,35.7,18S10.1,46.6,18,64.3l7.6-3.4c-6-13.5,0-29.3,13.5-35.3s29.3,0,35.3,13.5 L82,35.7z"> <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="2s" from="0 50 50" to="360 50 50" repeatCount="indefinite"/> </path></svg>

								<svg class="clear" style="display:none" xmlns="http://www.w3.org/2000/svg" width="32" height="33" viewBox="0 0 32 33" fill="none"><path d="M8.36351 25.5408L7.29688 24.4742L14.9328 16.8383L7.29688 9.20237L8.36351 8.13574L15.9994 15.7716L23.6353 8.13574L24.7019 9.20237L17.066 16.8383L24.7019 24.4742L23.6353 25.5408L15.9994 17.9049L8.36351 25.5408Z" fill="black"/></svg>
							</button>
						</form>
					</div>
					<div id="search__results" class="search__results"></div>
				</div>				
			</div>
		</div>	
		<div class="search__popup_bg"></div>
	</div>
	
	<?php
}
add_shortcode( 'stone_search_popup', 'stone_search_popup_shortcode' );


function stone_yith_ywraq_request_quote_shortcode( $atts, $content = null ) {	
	
	$raq_content = YITH_Request_Quote()->get_raq_return();

	$args = shortcode_atts(
		array(
			'raq_content'   => $raq_content,
			'template_part' => 'view',
			'show_form'     => 'yes',
			'form_type'     => get_option( 'ywraq_inquiry_form_type', 'default' ),
			'form_title'    => get_option( 'ywraq_title_before_form', apply_filters( 'ywraq_form_title', __( 'Send the request', 'yith-woocommerce-request-a-quote' ) ) ),
		),
		$atts
	);

	$args['args'] = apply_filters( 'ywraq_request_quote_page_args', $args, $raq_content );

	ob_start();
	/**
		 * APPLY_FILTERS: ywraq_preview_slug
		 *
		 * change the url argument preview for a different text.
		 *
		 * @param string preview
		 */
	$preview_slug = apply_filters( 'ywraq_preview_slug', 'preview' );

	if ( isset( WC()->session, $_REQUEST[ $preview_slug ], $_REQUEST['quote'] ) && sanitize_text_field(wp_unslash($_REQUEST[ $preview_slug ])) ) { //phpcs:ignore

		$session_order = WC()->session->get( 'raq_new_order' );

		if ( sanitize_text_field(wp_unslash($_REQUEST['quote'])) == $session_order ) { //phpcs:ignore
			$order = wc_get_order( $session_order );
			if ( ! $order ) {
				esc_html_e( 'This Quote doesn\'t exist.', 'yith-woocommerce-request-a-quote' );
				return;
			}
			wc_get_template( 'quote-preview.php', array( 'order' => $order ), '', YITH_YWRAQ_TEMPLATE_PATH . '/' );
		} else {
			esc_html_e( 'You do not have permission to read the quote.', 'yith-woocommerce-request-a-quote' );
			return;
		}
	} else {
		wc_get_template( 'request-quote.php', $args, '/template-parts/request-quote/', get_stylesheet_directory() );
		//wc_get_template( 'request-quote.php', $args, '', YITH_YWRAQ_TEMPLATE_PATH . '/' );
	}

	return ob_get_clean();
}
add_shortcode( 'stone_yith_ywraq_request_quote', 'stone_yith_ywraq_request_quote_shortcode' );



function stone_show_request_quote_icon_shortcode( $atts, $content = null ) {
	$raq_content = YITH_Request_Quote()->get_raq_return();
	if(!empty($raq_content)) $raq_item_numbers  = count($raq_content);
	else $raq_item_numbers = 0;
	
	$raq_url   = esc_url( YITH_Request_Quote()->get_raq_page_url() );
	echo '<div class="request-quote-icon">';
		echo '<a href="'.$raq_url.'">';
			echo '<svg xmlns="http://www.w3.org/2000/svg" width="17" height="18" viewBox="0 0 17 18" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.35435 9.46792L4.80185 11.1254C4.72666 11.3444 4.71469 11.5802 4.7673 11.8057C4.81992 12.0312 4.935 12.2373 5.09935 12.4004C5.26293 12.5641 5.46904 12.6787 5.69437 12.7312C5.91969 12.7838 6.15524 12.7723 6.37435 12.6979L8.03893 12.1454C8.11588 12.1184 8.18599 12.0749 8.24435 12.0179L15.7527 4.5025C15.9841 4.27018 16.114 3.95562 16.114 3.62771C16.114 3.2998 15.9841 2.98524 15.7527 2.75292L14.7468 1.74709C14.5145 1.51567 14.2 1.38574 13.8721 1.38574C13.5441 1.38574 13.2296 1.51567 12.9973 1.74709L5.48185 9.2625C5.42489 9.32087 5.38137 9.39097 5.35435 9.46792ZM13.7481 2.49792C13.782 2.46706 13.8262 2.44996 13.8721 2.44996C13.9179 2.44996 13.9621 2.46706 13.996 2.49792L15.0019 3.50375C15.0327 3.53766 15.0498 3.58186 15.0498 3.62771C15.0498 3.67356 15.0327 3.71776 15.0019 3.75167L13.8756 4.87792L12.6218 3.62417L13.7481 2.49792ZM5.80768 11.4654L6.32477 9.92125L11.871 4.375L13.1248 5.62875L7.57852 11.175L6.03435 11.6921C6.00272 11.7035 5.9685 11.7057 5.93568 11.6983C5.90287 11.691 5.87282 11.6745 5.84904 11.6507C5.82527 11.627 5.80875 11.5969 5.80143 11.5641C5.7941 11.5313 5.79627 11.497 5.80768 11.4654ZM16.1139 7.93083V13.9588C16.1139 14.6632 15.8341 15.3389 15.3359 15.837C14.8378 16.3351 14.1622 16.615 13.4577 16.615H3.54102C2.83653 16.615 2.16091 16.3351 1.66276 15.837C1.16462 15.3389 0.884766 14.6632 0.884766 13.9588V4.04209C0.884766 3.3376 1.16462 2.66198 1.66276 2.16383C2.16091 1.66569 2.83653 1.38583 3.54102 1.38583H9.56893V2.44834H3.54102C3.11833 2.44834 2.71295 2.61625 2.41406 2.91513C2.11518 3.21402 1.94727 3.6194 1.94727 4.04209V13.9588C1.94727 14.3814 2.11518 14.7868 2.41406 15.0857C2.71295 15.3846 3.11833 15.5525 3.54102 15.5525H13.4577C13.8804 15.5525 14.2857 15.3846 14.5846 15.0857C14.8835 14.7868 15.0514 14.3814 15.0514 13.9588V7.93083H16.1139Z" fill="#000"></path></svg>';
		echo '</a>';
		echo '<span class="request-quote-icon--count">';
			if($raq_item_numbers) echo $raq_item_numbers;
		echo '</span>';
	echo '</div>';
}
add_shortcode( 'stone_show_request_quote_icon', 'stone_show_request_quote_icon_shortcode' );



function project_design_build_firm_posts_list_shortcode( $atts, $content = null ) {
	$term = get_queried_object();
	if($term) {

		$term_slug = $term->slug;
		$args = array(
			'post_type' => 'projects',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'tax_query' => [
				[
					'taxonomy' => 'project_design_build_firms',
					'field' => 'slug',
					'terms' => $term_slug
				]
			],
			'orderby' => 'DATE',
			'order' => 'DESC'
		);

		$project_list = new WP_Query($args);
		?>
		<?php if($project_list->have_posts()) : ?>
			<div class="cmsmasters-post-type-projects cmsmasters-block-default cmsmasters-sticky-default elementor-widget elementor-widget-cmsmasters-theme-blog-grid elementor-widget-cmsmasters-blog-similar">
				<div class="cmsmasters-blog cmsmasters-theme-blog-grid cmsmasters-blog--type-default">	
					
					<div class="custom-page">
						<?php $object_ids = []; ?>
						<?php while($project_list->have_posts()) : $project_list->the_post(); ?>
							<?php $object_ids[] = get_the_ID();  ?>
						<?php endwhile; ?>
						<?php get_template_part('/template-parts/designer-projects-filter', '', array('object_ids' => $object_ids)); ?>
					</div>
					
					
					<div class="elementor-widget-cmsmasters-theme-blog-grid__posts-wrap">
						<div class="elementor-widget-cmsmasters-theme-blog-grid__posts" id="projects--filter-response">
							<?php while($project_list->have_posts()) : $project_list->the_post(); ?>
							<?php $project_id = get_the_ID();  ?>
								<?php get_template_part('/template-parts/content/project-item', '', array('project_id' => $project_id)); ?>
							<?php endwhile; ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
<?php	
	}
}
add_shortcode( 'project_design_build_firm_posts_list', 'project_design_build_firm_posts_list_shortcode' );


function advanced_filter_shortcode($atts, $content = null) {
	//wp_enqueue_style('filter_css', get_stylesheet_directory_uri() . '/assets/advanced_filter.css');
	
	echo '<div id="advanced_filter__wrapper">';
		get_template_part('/template-parts/advanced-filters', '', array('show_button' => 1));
	echo '</div>';
}
add_shortcode( 'advanced_filter', 'advanced_filter_shortcode' );


function product_list_shortcode($atts, $content = null) {	
	$params = shortcode_atts( 
		array( 
			'outlet' => 0,
		), 
		$atts 
	);
	$outlet = (int)$params["outlet"];
	?>

	
<div class="products_list stone__products_list">
	<div class="cmsmasters-blog cmsmasters-theme-blog-grid cmsmasters-blog--type-default">

		<div class="mobile-filter--controller">
			<div class="product-page-filter">
				<div class="product-page-filter-container">	
					<div class="product-page-filter-item active-filter-popup">
						<div class="product-page-filter-top">
							<div class="label">filter by</div>
							<div class="title">
								<span>show filters</span>
								<div class="icon">
									<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.92512 5.63719L2.69562 4.87054L8.36099 10.5359L14.02 4.87054L14.7969 5.63719L8.36099 12.0731L1.92511 5.63719L1.92512 5.63719Z" fill="black"/></svg>
								</div>
							</div> 
						</div>
					</div>
					
					<div class="product-page-filter-item sorting">
						<div class="product-page-filter-top">
							<div class="elementor-widget-cmsmasters-theme-blog-grid__header-side">
								<li class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item">
									<span class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger-wrap">
										<span class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-label">Sort by</span>
										<span class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger">
											<span class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger-value" data-default="Latest">Latest</span>
											<i class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger-icon themeicon- theme-icon-arrow-forward"></i>
										</span>
									</span>
									<ul class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list sorting ps">	
										<li class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item checked">
											<input class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item-checkbox" type="radio" id="mobile-popular" name="mobile-sorting" value="popular" checked>
											<label for="mobile-popular">Popular</label>
											<i class="checkbox-icon full themeicon- theme-icon-chech-line"></i>
										</li>
										<li class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item " data-category-id="date">
											<input class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item-checkbox" type="radio" id="mobile-latest" name="mobile-sorting" value="latest">
											<label for="mobile-latest">Latest</label>
											<i class="checkbox-icon full themeicon- theme-icon-chech-line"></i>
										</li>
									</ul>
								</li>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	
		<?php get_template_part('template-parts/products-filter-aside', '', array('outlet' => $outlet)); ?>
		
		<?php
		global $wp_query;
		$found_posts = $wp_query->found_posts;
		?>
				
		<div class="elementor-widget-cmsmasters-theme-blog-grid__posts-variable">
			<div class="found_posts__wrapper">
				Found <span><?php echo $found_posts; ?> <?php echo($found_posts == 1) ? 'product':'products'; ?></span>
			</div>
			<div class="elementor-widget-cmsmasters-theme-blog-grid cmsmasters-post-type-product cmsmasters-block-default cmsmasters-sticky-default elementor-widget elementor-widget-cmsmasters-blog-similar ">

				<div class="elementor-widget-cmsmasters-theme-blog-grid__posts" id="filter-products-response">
					<?php if(have_posts()) : ?>
						<?php while(have_posts()) : the_post(); ?>
							<?php
							$productID = get_the_ID();				 
							get_template_part('template-parts/content/product-item', '', array('product_id' => $productID));
							?>
						<?php endwhile; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<?php if($wp_query->max_num_pages > 1) : ?>
		<div class="load_more_products--wrapper">
			<a href="#" class="load_more_products--button" data-default-text="Load More" data-loading-text="Loading...">Load More</a>
	</div>
	<?php endif; ?>
</div>

	<input type="hidden" name="outlet" id="outlet" value="<?php echo $outlet; ?>">
	<input type="hidden" name="page" id="page" value="1">
	<input type="hidden" name="max-pages" id="max-pages" value="<?php echo $wp_query->max_num_pages; ?>">
	
<?php	
}
add_shortcode( 'product_list', 'product_list_shortcode' );



//for outlet products create different shortcode because we have issue with elementor global query
function outlet_list_shortcode($atts, $content = null) {	
	?>
	
<div class="products_list stone__products_list">
	<div class="cmsmasters-blog cmsmasters-theme-blog-grid cmsmasters-blog--type-default">

		<div class="mobile-filter--controller">
			<div class="product-page-filter">
				<div class="product-page-filter-container">	
					<div class="product-page-filter-item active-filter-popup">
						<div class="product-page-filter-top">
							<div class="label">filter by</div>
							<div class="title">
								<span>show filters</span>
								<div class="icon">
									<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.92512 5.63719L2.69562 4.87054L8.36099 10.5359L14.02 4.87054L14.7969 5.63719L8.36099 12.0731L1.92511 5.63719L1.92512 5.63719Z" fill="black"/></svg>
								</div>
							</div> 
						</div>
					</div>
					
					<div class="product-page-filter-item sorting">
						<div class="product-page-filter-top">
							<div class="elementor-widget-cmsmasters-theme-blog-grid__header-side">
								<li class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item">
									<span class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger-wrap">
										<span class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-label">Sort by</span>
										<span class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger">
											<span class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger-value" data-default="Latest">Latest</span>
											<i class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger-icon themeicon- theme-icon-arrow-forward"></i>
										</span>
									</span>
									<ul class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list sorting ps">
										<li class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item checked">
											<input class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item-checkbox" type="radio" id="mobile-popular" name="mobile-sorting" value="popular" checked>
											<label for="mobile-popular">Popular</label>
											<i class="checkbox-icon full themeicon- theme-icon-chech-line"></i>
										</li>
										<li class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item" data-category-id="date">
											<input class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item-checkbox" type="radio" id="mobile-latest" name="mobile-sorting" value="latest">
											<label for="mobile-latest">Latest</label>
											<i class="checkbox-icon full themeicon- theme-icon-chech-line"></i>
										</li>
										
									</ul>
								</li>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	
		<?php get_template_part('template-parts/products-filter-aside', '', array('outlet' => 1)); ?>
		
		<?php
		$per_page = get_field('products_per_page', 'options');
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => $per_page,
			'post_status' => 'publish',
			'orderby' => 'meta_value_num', 
			'order' => 'DESC',
			'meta_key' => 'cmsmasters_pm_view',
			'tax_query' => [
				[
					'taxonomy' => 'product_tag',
					'field' => 'slug',
					'terms' => 'outlet'
				]
			]
		);
		$products_query = new WP_Query($args);
		
		$found_posts = $products_query->found_posts;
		?>
		
		
		
		<div class="elementor-widget-cmsmasters-theme-blog-grid__posts-variable">
			<div class="found_posts__wrapper">
				Found <span><?php echo $found_posts; ?> <?php echo($found_posts == 1) ? 'product':'products'; ?></span>
			</div>
			<div class="elementor-widget-cmsmasters-theme-blog-grid cmsmasters-post-type-product cmsmasters-block-default cmsmasters-sticky-default elementor-widget elementor-widget-cmsmasters-blog-similar ">

				<div class="elementor-widget-cmsmasters-theme-blog-grid__posts" id="filter-products-response">
					<?php if($products_query->have_posts()) : ?>
						<?php while($products_query->have_posts()) : $products_query->the_post(); ?>
							<?php
							$productID = get_the_ID();				 
							get_template_part('template-parts/content/product-item', '', array('product_id' => $productID));
							?>
						<?php endwhile; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<?php if($products_query->max_num_pages > 1) : ?>
		<div class="load_more_products--wrapper">
			<a href="#" class="load_more_products--button" data-default-text="Load More" data-loading-text="Loading...">Load More</a>
	</div>
	<?php endif; ?>
</div>

	<input type="hidden" name="outlet" id="outlet" value="1">
	<input type="hidden" name="page" id="page" value="1">
	<input type="hidden" name="max-pages" id="max-pages" value="<?php echo $products_query->max_num_pages; ?>">
	
<?php	
}
add_shortcode( 'outlet_list', 'outlet_list_shortcode' );
?>