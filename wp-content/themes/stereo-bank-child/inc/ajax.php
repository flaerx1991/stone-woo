<?php
function stone_search_items_func(){
	$s_word = sanitize_text_field($_POST["s_word"]);

	$args = array(
			'post_type' => array('product', 'projects', 'collections', 'post'),
			's' => $s_word,
			'posts_per_page' => -1,
			'fields' => 'ids',
			'post_status' => 'publish',
			'orderby' => 'relevance'
	);

	$posts = get_posts($args);
	$search_data = [];
	
	if($posts) {		
		foreach($posts as $pID) { 
			$post_type = get_post_type($pID);
			$search_data[$post_type][] = $pID;
		}
	}
	
	if($search_data['product']) $product_count = count($search_data['product']);
	if($search_data['collections']) $collections_count = count($search_data['collections']);
	if($search_data['projects']) $projects_count = count($search_data['projects']);
	if($search_data['post']) $post_count = count($search_data['post']);
	

	$search_data['product'] = array_slice($search_data['product'], 0, 2);
	$search_data['collections'] = array_slice($search_data['collections'], 0, 2);
	$search_data['projects'] = array_slice($search_data['projects'], 0, 5);
	$search_data['post'] = array_slice($search_data['post'], 0, 5);

	
	?>

	<div class="search__popup_content" >
		<?php if($posts) : ?>
			<?php if($post_count > 0 || $projects_count > 0) : ?>

				<div class="search__popup_left">
					<?php if($post_count > 0) : ?>
						<div class="search__popup_content--type">
							<p><?php echo $post_count; ?> Article<?php echo($post_count > 1) ? 's':''; ?></p>
							<ul class="search__popup_content--result">
								<?php foreach($search_data['post'] as $post_id) : ?>
									<li><a href="<?php echo get_the_permalink($post_id); ?>"><?php echo get_the_title($post_id); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>

					<?php if($projects_count > 0) : ?>
						<div class="search__popup_content--type">
							<p><?php echo $projects_count; ?> project<?php echo($projects_count > 1) ? 's':''; ?></p>
							<ul class="search__popup_content--result">
								<?php foreach($search_data['projects'] as $project_id) : ?>
									<li><a href="<?php echo get_the_permalink($project_id); ?>"><?php echo get_the_title($project_id); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>

					<div class="elementor-widget-cmsmasters-button__button-container">
						<div class="elementor-widget-cmsmasters-button__button-container-inner">

							<a href="<?php echo add_query_arg('s', $s_word, home_url()); ?>" class="cmsmasters-button-link elementor-widget-cmsmasters-button__button cmsmasters-icon-view-default cmsmasters-icon-shape- cmsmasters-button-size-sm" role="button">
								<span class="elementor-widget-cmsmasters-button__content-wrapper cmsmasters-align-icon-right">			
									<span class="elementor-widget-cmsmasters-button__text">View All Results</span>
									<span class="elementor-widget-cmsmasters-button__icon"><i aria-hidden="true" class="themeicon- theme-icon-arrow-back"></i></span>
								</span>
							</a>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<?php if($product_count > 0 || $collections_count > 0) : ?>
				<div class="search__popup_right">
					<?php if($product_count > 0) : ?>
						<div class="search__popup_content--type">
							<p><?php echo $product_count; ?> product<?php echo($product_count > 1) ? 's':''; ?></p>
							<div class="cmsmasters-post-type-product cmsmasters-block-default cmsmasters-sticky-default elementor-widget elementor-widget-cmsmasters-theme-blog-grid elementor-widget-cmsmasters-blog-similar">
								<div class="elementor-widget-cmsmasters-theme-blog-grid__posts">
									<?php foreach($search_data['product'] as $product_id) : ?>
										<?php get_template_part('/template-parts/search/product-item', '', array('product_id' => $product_id)); ?>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					<?php endif; ?>

					<?php if($collections_count > 0) : ?>
						<div class="search__popup_content--type">
							<p><?php echo $collections_count; ?> collection<?php echo($collections_count > 1) ? 's':''; ?></p>
							<div class="cmsmasters-post-type-collections cmsmasters-block-default cmsmasters-sticky-default elementor-widget elementor-widget-cmsmasters-theme-blog-grid elementor-widget-cmsmasters-blog-similar">
								<div class="elementor-widget-cmsmasters-theme-blog-grid__posts">
									<?php foreach($search_data['collections'] as $collection_id) : ?>
										<?php get_template_part('/template-parts/search/collections-item', '', array('collections_id' => $collection_id)); ?>
									<?php endforeach; ?>
								</div>
							</div>

							<div class="elementor-widget-cmsmasters-button__button-container">
								<div class="elementor-widget-cmsmasters-button__button-container-inner">

									<a href="<?php echo add_query_arg('s', $s_word, home_url()); ?>" class="cmsmasters-button-link elementor-widget-cmsmasters-button__button cmsmasters-icon-view-default cmsmasters-icon-shape- cmsmasters-button-size-sm" role="button">
										<span class="elementor-widget-cmsmasters-button__content-wrapper cmsmasters-align-icon-right">			
											<span class="elementor-widget-cmsmasters-button__text">View All Results</span>
											<span class="elementor-widget-cmsmasters-button__icon"><i aria-hidden="true" class="themeicon- theme-icon-arrow-back"></i></span>
										</span>
									</a>
								</div>
							</div>

						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		<?php else : ?>
			<p class="search__nothing_found">0 results found for your search</p>
		<?php endif; ?>
	</div>

	<?php

	die();

}
add_action('wp_ajax_stone_search_items','stone_search_items_func');
add_action('wp_ajax_nopriv_stone_search_items','stone_search_items_func');

function get_product_count_with_collection( $terms ) {
    $term = array_shift( $terms );

	$args = array(
		'post_type' => 'product',
		'tax_query' => array(
			array(
				'taxonomy' => 'collection',
				'field' => 'term_id',
				'terms' => $term->term_id,
			),
		),
	);

	$query = new WP_Query( $args );

	$count = $query->post_count;

	$label = ( 1 === $count ? 'product' : 'products' );

	echo '<span class="elementor-widget-cmsmasters-theme-blog-grid__post-footer-product-count">' .
		$count . ' ' . esc_html( $label );
	'</span>';
}


/*Product  Filter*/
function stone_filter_products_func(){
	$filters = $_POST["filters"];
	$args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'orderby' => 'DATE',
		'order' => 'DESC',
		'posts_per_page' => 12
	);
	
	if($per_page = get_field('products_per_page', 'options')) {
		$args["posts_per_page"] = $per_page;
	}
	
	if($filters) {
		$args['tax_query'] = [];
		foreach($filters as $tax => $terms) {
			if($tax != 'sorting') $args['tax_query'][] = array('taxonomy' => $tax, 'field' => 'term_id', 'terms' => $terms);			
		}
	}
	
	if(isset($filters['sorting']) && $filters['sorting'][0] == 'popularity') {
		$args["orderby"] = 'meta_value_num';
		$args["order"] = 'DESC';
		$args["meta_key"] = 'cmsmasters_pm_view';
		
	}
	
	if(isset($_POST["outlet"]) && $_POST["outlet"] != 'NaN') {
		if($_POST["outlet"] == 1) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_tag',
				'field' => 'slug',
				'terms' => 'outlet'
			);
		}
		else {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_tag',
				'field' => 'slug',
				'terms' => 'outlet',
				'operator' => 'NOT IN'
			);
		}
	}
	
	//print_r($args);
	
	if($_POST["page"] > 1) {
		$args['paged'] = $_POST["page"];
	}
	
	$products = new WP_Query($args);
	
	$response_data = [];
	
	ob_start();
	if($products->have_posts()) {
		while($products->have_posts()) { 
			$products->the_post();
			$productID = get_the_ID();				 
			get_template_part('template-parts/content/product-item', '', array('product_id' => $productID));
		}
	}
	$html .= ob_get_clean();
	
	$response_data['html'] = $html;
	$response_data['max_pages'] = $products->max_num_pages;
	$found_posts = $products->found_posts;
	if($products->found_posts == 1) $found_posts = $products->found_posts . ' product';
	else $found_posts = $products->found_posts . ' products';
	$response_data['found_posts'] = $found_posts;
	
	wp_reset_postdata();
	
	echo json_encode($response_data);

	die();
}
add_action('wp_ajax_filter_products','stone_filter_products_func');
add_action('wp_ajax_nopriv_filter_products','stone_filter_products_func');


/*Product Sheet Filter*/
function stone_filter_sheet_products_func(){
	$filters = $_POST["filters"];
	
	$collections_term_args = [
		'taxonomy' => 'collection'
	];

	
	$collections_terms = get_terms($collections_term_args);

	if($collections_terms) {
		$products_array = [];
		foreach($collections_terms as $collections_term) {
			$args = array(
				'post_type' => 'product',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'orderby' => 'meta_value_num',
				'order' => 'ASC',
				'meta_key' => 'collection_order',
				'fields' => 'ids',
				//'post__in' => $products_ids,
				'tax_query' => [
					[
						'taxonomy' => 'collection',
						'field' => 'term_id',
						'terms' => $collections_term->term_id
					]
				]
			);
			
			if($filters) {
				foreach($filters as $tax => $terms) {
					if(!empty($terms)) $args['tax_query'][] = array('taxonomy' => $tax, 'field' => 'term_id', 'terms' => $terms);
				}	
			}
			
			$products = get_posts($args);


			if($products) {
				foreach($products as $_product_id) {
					$products_array[$collections_term->term_id][] = $_product_id;
				}
			}

		}
	}
	
	
	
	if(!empty($products_array)) {
		 foreach($products_array as $collection_id => $collection_products) { ?>
			<div class="collection--items-body">
				<?php 
				//get slug for connect collection post type and get link to collection page
				 $collection_term = get_term_by('term_id', $collection_id, 'collection');
				 $collection_slug = $collection_term->slug;
				 $collection_post = get_page_by_path($collection_slug, OBJECT, 'collections');
				 if($collection_post) {
					 $collection_post_link = get_the_permalink($collection_post);
				 }
				 else $collection_post_link = '';
				?>
				<a href="<?php echo $collection_post_link; ?>" class="collection-item"> 
					<div class="collection-left">
						<?php if($collection_thumb = get_field('collection_image_overlay', $collection_post)) : ?>
						<div class="img">
							<img src="<?php echo wp_get_attachment_image_url($collection_thumb); ?>" alt="">
						</div>
						<?php endif; ?>
						<div class="title"><?php echo $collection_term->name; ?></div>
					</div> 
					<div class="link-col">
						View Collection <svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.2806 1.00299H0.358209V0H8V7.64179H6.99701V1.7194L0.716418 8L0 7.28358L6.2806 1.00299Z" fill="white"/></svg>
					</div>
				</a> 

				<?php if(!empty($collection_products)) : ?>
					<div class="collection--items">
						<?php
						 //grouping products
						 $products_types = [];
						 foreach($collection_products as $pID) {
							 $product_types = get_the_terms($pID, 'theme_product_type');

							 foreach($product_types as $product_type) {

								 if(isset($products_types[$product_type->term_id]) && is_array($products_types[$product_type->term_id])) {
									 if(!in_array($pID, $products_types[$product_type->term_id])) $products_types[$product_type->term_id][] = $pID;
								 } 
								 else $products_types[$product_type->term_id][] = $pID;

								 if($product_type->parent != 0 ) {
									 $key = array_search($pID, $products_types[$product_type->parent]);
									 if($key !== NULL) {
										 unset($products_types[$product_type->parent][$key]);
									 }

								 }
							 }

						 } 

						 $sort = get_field('product_type_order', $collection_post);

						 if($sort && !empty($products_types)) {
							 $products_types = array_replace(array_flip($sort), $products_types);
						 }
						?>

						<?php foreach($products_types as $type_term_id => $productsID) : ?>
							<?php if(!empty($productsID) && is_array($productsID)) : ?>
								<div class="collection__products_group">
									<div class="product-page-top-titl"> 
										<div class="product-page-titl"><?php _e('PRODUCT NAME', 'stone'); ?></div>
										<div class="product-page-titl"><?php _e('sku', 'stone'); ?></div>
										<div class="product-page-titl"><?php _e('pack', 'stone'); ?></div>
										<div class="product-page-titl"><?php _e('uom', 'stone'); ?></div>
										<div class="product-page-titl"><?php _e('price', 'stone'); ?></div>
										<div class="product-page-titl"><?php _e('AVAILABLE', 'stone'); ?></div>
										<div class="product-page-titl"><?php _e('incoming', 'stone'); ?></div>
										<div class="product-page-titl"></div>
									</div>
									<?php
									 $group = get_term_by('term_id', $type_term_id, 'theme_product_type');
									 if($group->parent != 0) {
										 $parent_type_term = get_term_by('term_id', $group->parent, 'theme_product_type');
										 $group__title = $parent_type_term->name. ' - ' . $group->name;
									 }
									 else $group__title = $group->name;
									?>
									<h3 class="group__title"><?php echo $group__title; ?></h3>
									
									<?php foreach($productsID as $productID) : ?>
										<?php get_template_part('template-parts/content/product-sheet-item', '', array('pID' => $productID)); ?>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

		 <?php } ?>
	<?php } else { ?>
		<div class="product-page-item" style="border: 1px solid #E0E0E0;background: #F4F4F4;">
			<div class="product-page-col" style="width:100%; text-align:center;"><?php _e('Nothing found', 'stone'); ?></div>
		</div>
	<?php } ?>

	<?php
	die();
}
add_action('wp_ajax_stone_filter_products','stone_filter_sheet_products_func');
add_action('wp_ajax_nopriv_stone_filter_products','stone_filter_sheet_products_func');



function stone_filter_designer_projects_func(){
	
	$filters = $_POST["filters"];
	$args = array(
		'post_type' => 'projects',
		'posts_per_page' => -1,
		'post_status' => 'publish'
	);
	$args['tax_query'] = [];
	
	$designer_id = $_POST["designer_id"];
	$args['tax_query'][] = array('taxonomy' => 'project_design_build_firms', 'field' => 'term_id', 'terms' => $designer_id);
	
	foreach($filters as $tax => $terms) {
		$args['tax_query'][] = array('taxonomy' => $tax, 'field' => 'slug', 'terms' => $terms);
	}
	
	
	$sortby = $_POST["sortby"];
	if($sortby == 'latest') {
		$args['sortby'] = 'DATE';
		$args['sort'] = 'DESC';
	}
	elseif($sortby == 'popular') {
		$args['sortby'] = 'meta_value_num';
		$args['sort'] = 'DESC';
		$args["meta_key"] = 'cmsmasters_pm_view';
	}
	
	
	$project_list = new WP_Query($args);
	if($project_list->have_posts()) {
		while($project_list->have_posts()) { $project_list->the_post(); 
			$project_id = get_the_ID();
			get_template_part('/template-parts/content/project-item', '', array('project_id' => $project_id));
		}
	}

	die();
}
add_action('wp_ajax_stone_filter_designer_projects', 'stone_filter_designer_projects_func');
add_action('wp_ajax_nopriv_stone_filter_designer_projects', 'stone_filter_designer_projects_func');



function stone_collection_filter_func(){
	
	$collection_id =  $_POST["collection_id"];
	
	$filters = $_POST["filters"];
	$args = array(
		'post_type' => 'product',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'fields' => 'ids'
	);
	$args['tax_query'] = [];
	
	$collection_slug = $_POST["collection_slug"];
	$args['tax_query'][] = array('taxonomy' => 'collection', 'field' => 'slug', 'terms' => $collection_slug);
	
	foreach($filters as $tax => $terms) {
		$args['tax_query'][] = array('taxonomy' => $tax, 'field' => 'slug', 'terms' => $terms);
	}
	
	
	$sortby = $_POST["sortby"];
	if($sortby == 'latest') {
		$args['sortby'] = 'DATE';
		$args['sort'] = 'DESC';
	}
	elseif($sortby == 'popular') {
		$args['sortby'] = 'meta_value_num';
		$args['sort'] = 'DESC';
		$args["meta_key"] = 'cmsmasters_pm_view';
	}
	
	$products = get_posts($args);
	
	$products_types = [];
	foreach($products as $pID) {
		$product_types = get_the_terms($pID, 'theme_product_type');
		foreach($product_types as $product_type) {
			if(is_array($products_types[$product_type->term_id])) {
				if(!in_array($pID, $products_types[$product_type->term_id])) $products_types[$product_type->term_id][] = $pID;
			} 
			else $products_types[$product_type->term_id][] = $pID;

			if($product_type->parent != 0 ) {
				$key = array_search($pID, $products_types[$product_type->parent]);
				if($key !== NULL) {
					unset($products_types[$product_type->parent][$key]);
				}
			}
		}
	} 
	
	$sort = get_field('product_type_order', $collection_id);

	if($sort && !empty($products_types)) {
		$products_types = array_replace(array_flip($sort), $products_types);
	}
	
	
	foreach($products_types as $type_term_id => $productsID) {
		if(!empty($productsID) && is_array($productsID)) { 
		?>
			<div class="collection__products_group">
				<?php
				$group = get_term_by('term_id', $type_term_id, 'theme_product_type');
				if($group->parent != 0) {
					$parent_type_term = get_term_by('term_id', $group->parent, 'theme_product_type');
					$group__title = $parent_type_term->name. ' - ' . $group->name;
				}
				else $group__title = $group->name;
				?>

				<h2 class="group__title"><?php echo $group__title; ?></h2>
				<?php if($group->description) : ?>
					<div class="group__description">
						<?php echo $group->description; ?>
					</div>	
				<?php endif; ?>


				<div class="group__products_list cmsmasters-blog cmsmasters-theme-blog-grid">
					<div class="cmsmasters-post-type-product cmsmasters-block-default cmsmasters-sticky-default elementor-widget elementor-widget-cmsmasters-theme-blog-grid elementor-widget-cmsmasters-blog-similar">
						<div class="elementor-widget-cmsmasters-theme-blog-grid__posts">
							<?php foreach($productsID as $productID) : ?>
								<?php get_template_part('template-parts/content/product-item', '', array('product_id' => $productID)); ?>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		<?php
		}
	}
	

	die();
}
add_action('wp_ajax_stone_collection_filter', 'stone_collection_filter_func');
add_action('wp_ajax_nopriv_stone_collection_filter', 'stone_collection_filter_func');


function stone_add_to_cart_func(){
	
	$response = [];
	$product_id = $_POST["product_id"];
	$quantity = $_POST["quantity"];
	
	if($product_id && $quantity) {
		if(WC()->cart->add_to_cart( $product_id, $quantity )) {
			$response["result"] = true;
		}
	}
	
	
	echo json_encode($response);


	die();
}
add_action('wp_ajax_stone_add_to_cart', 'stone_add_to_cart_func');
add_action('wp_ajax_nopriv_stone_add_to_cart', 'stone_add_to_cart_func');



function dynamic_filter_func(){	
	$filters = $_POST["filters"];
	
	$args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'fields' => 'ids'
	);
	
	if(isset($_POST["outlet"]) && $_POST["outlet"] != 'NaN') {
		if((int)$_POST["outlet"] == 1) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_tag',
				'field' => 'slug',
				'terms' => 'outlet',
				'operator' => 'IN'
			);
		}
		elseif((int)$_POST["outlet"] == 0) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_tag',
				'field' => 'slug',
				'terms' => 'outlet',
				'operator' => 'NOT IN'
			);
		}
	}

	
	if($filters) {
		$args['tax_query'] = [];
		foreach($filters as $tax => $terms) {
			$args['tax_query'][] = array('taxonomy' => $tax, 'field' => 'term_id', 'terms' => $terms);			
		}
	}
	
	$product_ids = get_posts($args);
	
	$filter_taxonomies_terms = [];
	
	$filters = get_field('products_filters', 'options'); 
	foreach($filters as $filter) {
		$taxonomy = $filter['products_filter_taxonomies'];
		
		//get tax terms assigned to filtered products
		$terms = get_terms([
			'taxonomy' => $taxonomy,
			'orderby' => 'name',
			'order' => 'ASC',
			'object_ids' => $product_ids,
			'fields' => 'ids'
		]);
		

		if($terms) {
			$terms_with_count_posts = [];
			//get count products for each assigned term in taxonomy
			foreach($terms as $term_id) {
				
				$terms_posts_args = array(
					'post_type' => 'product',
					'post_status' => 'publish',
					'posts_per_page' => -1,
					'fields' => 'ids',
					'post__in' => $product_ids,
					'tax_query' => [
						[
							'taxonomy' => $taxonomy,
							'field'    => 'term_id',
							'terms'    => $term_id
						]						
					]
				);
				
				if(isset($_POST["outlet"]) && $_POST["outlet"] != 'NaN') {	
					if($_POST["outlet"] == 1) {
						$terms_posts_args['tax_query'][] = array(
							'taxonomy' => 'product_tag',
							'field'    => 'slug',
							'terms'    => ['outlet'],
							'operator' => 'IN'
						);	
					}
					elseif($_POST["outlet"] == 0) {
						$terms_posts_args['tax_query'][] = array(
							'taxonomy' => 'product_tag',
							'field'    => 'slug',
							'terms'    => ['outlet'],
							'operator' => 'NOT IN'
						);	
					}
							
				}
				
				$posts = get_posts($terms_posts_args);
				if($posts) $posts_count = count($posts);
				else $posts_count = 0;
				
				$terms_with_count_posts[$term_id] = $posts_count;
			}
			
			$filter_taxonomies_terms[$taxonomy] = $terms_with_count_posts;
		}
		else $filter_taxonomies_terms[$taxonomy] = [];
	}
	
	

	echo json_encode($filter_taxonomies_terms);

	die();
}
add_action('wp_ajax_dynamic_filter', 'dynamic_filter_func');
add_action('wp_ajax_nopriv_dynamic_filter', 'dynamic_filter_func');

// AJAX handler for ACF get_field()
add_action('wp_ajax_get_acf_field', 'get_acf_field');
add_action('wp_ajax_nopriv_get_acf_field', 'get_acf_field');

function get_acf_field() {
    if(isset($_POST['post_id']) && isset($_POST['field_name'])) {
        $post_id = $_POST['post_id'];
        $field_name = $_POST['field_name'];
        $field_value = get_field($field_name, $post_id);
		$testing = get_field("available", 3842);
        echo json_encode($field_value);
    }
    die();
}

?>