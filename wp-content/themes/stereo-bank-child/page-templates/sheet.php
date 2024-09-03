<?php
/*
Template Name: Product sheet
*/
?>
<?php get_header(); ?>
<div class="custom-page">
	<div class="product-page">
		<div class="container">
			<h1 class="h1-title"><?php the_title(); ?></h1>
			<div class="product-page-top">
				<div class="desc"><?php the_field('description'); ?></div>
				<?php echo wp_get_attachment_image(get_field('bg_image'), 'full');  ?>
			</div>

			<div class="sticky_container">
				<div id="advanced_filter__wrapper">
					<div id="product-sheet--filter">
						
						
						<div class="mobile-filter--controller">
							<div class="product-page-filter">
								<div class="product-page-filter-container">	
									<div class="product-page-filter-item active-filter-popup">
										<div class="product-page-filter-top">
											<!--div class="label">filter by</div-->
											<div class="title">
												<span>show filters</span>
												<div class="icon">
													<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.92512 5.63719L2.69562 4.87054L8.36099 10.5359L14.02 4.87054L14.7969 5.63719L8.36099 12.0731L1.92511 5.63719L1.92512 5.63719Z" fill="black"/></svg>
												</div>
											</div> 
										</div>
									</div>

								</div>
							</div>
						</div>
						
						<div class="product-sheet--filterbody">
							<div class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-popup-close">
								<h2>Filter by</h2>
								<i class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-popup-close-icon themeicon- theme-icon-close"></i>
							</div>

							<a href="#" class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-clear-all-button" style="display:none">Clear All</a>
							
							<?php get_template_part('template-parts/advanced-filters', '', array('show_button' => 0)); ?>
						</div>
						
					</div>
				</div>

				<div class="product-page-items">
					

					<?php 
					$collections_terms = get_terms([
						'taxonomy' => 'collection'
					]);

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
								'tax_query' => [
									[
										'taxonomy' => 'collection',
										'field' => 'term_id',
										'terms' => $collections_term->term_id
									]
								]
							);
							$products = get_posts($args);
							
							
							if($products) {
								foreach($products as $_product_id) {
									$products_array[$collections_term->term_id][] = $_product_id;
								}
							}
							
						}
					}
					?>

					<div id="products--filter-response">
						<?php if(!empty($products_array)) : ?>
							<?php foreach($products_array as $collection_id => $collection_products) : ?>
								<div class="collection--items-body">
									<?php 
									//get slug for connect collection post type and get link to collection page
									$collection_term = get_term_by('term_id', $collection_id, 'collection');
									$collection_slug = $collection_term->slug;
									$collection_post = get_page_by_path($collection_slug, OBJECT, 'collections');
									if($collection_post) {
										$collection_post_link = get_the_permalink($collection_post);
									}
									else {
										$collection_post = 0;
										$collection_post_link = '#';
									}
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
											<?php _e('View Collection', 'stone'); ?>
											<svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.2806 1.00299H0.358209V0H8V7.64179H6.99701V1.7194L0.716418 8L0 7.28358L6.2806 1.00299Z" fill="white"/></svg></div>
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
														<?php
														$group = get_term_by('term_id', $type_term_id, 'theme_product_type');
														if($group->parent != 0) {
															$parent_type_term = get_term_by('term_id', $group->parent, 'theme_product_type');
															$group__title = $parent_type_term->name. ' - ' . $group->name;
														}
														else $group__title = $group->name;
														?>
														<h3 class="group__title"><?php echo $group__title; ?></h3>
														
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
														
														<?php foreach($productsID as $productID) : ?>
															<?php get_template_part('template-parts/content/product-sheet-item', '', array('pID' => $productID)); ?>
														<?php endforeach; ?>
													</div>
												<?php endif; ?>
											<?php endforeach; ?>

										</div>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>

					</div>
				</div>	
				
			</div>
			
		</div>

		<div class="connect-with">
			<div class="container">
				<div class="connect-with-top">					
					<div class="subtitle"><?php the_field('bottom_block_pretitle'); ?></div>
					<div class="title"><?php the_field('bottom_block_title'); ?></div>
					<?php echo wp_get_attachment_image(get_field('bottom_bg_image'), 'full');  ?>
				</div>
				<div class="connect-with-bottom">
					<div class="desc"><?php the_field('bottom_text'); ?></div>
					<div class="buttons">
						<?php $btn1 = get_field('button_1'); ?>
						<?php if($btn1) : ?>
							<a href="<?php echo $btn1["url"]; ?>" class="btn-1"><?php echo $btn1["title"]; ?></a>
						<?php endif; ?>
						
						<?php $btn2 = get_field('button_2'); ?>
						<?php if($btn2) : ?>
							<a href="<?php echo $btn2["url"]; ?>" class="btn-2"><?php echo $btn2["title"]; ?></a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div> 
</div>  

<?php get_footer(); ?>