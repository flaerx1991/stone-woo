<?php get_header(); ?>

<?php 
global $post;
$collection_slug = $post->post_name;
$args = array(
	'post_type' => 'product',
	'posts_per_page' => -1,
	'tax_query' => [
		[
			'taxonomy' => 'collection',
			'field' => 'slug',
			'terms' => $collection_slug
		]
	],
	'fields' => 'ids',
	'orderby' => 'meta_value_num',
	'order' => 'ASC',
	'meta_key' => 'collection_order'
);
$products = get_posts($args);
if($products) $products_count = count($products);
?>

<div class="custom-page">

	<div class="collections__menu_wrapper">
		<div class="container">
			<?php 
			wp_nav_menu( [
				'menu' => 132,
				'container' => '',
				'container_class' => '',
				'menu_class' => '',
			] ); 
			?>	
		</div>
	</div>

	<div class="collections__breadcrumbs">
		<div class="container">
			<?php if(function_exists('stone_breadcrumbs')) echo stone_breadcrumbs(); ?>
		</div>
	</div>
	
	<div class="collections__body">
		<div class="collections__info_wrapper">
			<div class="container">
				<div class="collections__info">
					
					<?php $add_images = get_field('additional_images'); ?>
					<div class="collections__info_right collections__images <?php echo(!empty($add_images)) ? 'swiper':''; ?>">
						<?php echo(!empty($add_images)) ? '<div class="swiper-wrapper">':''; ?>
						
							<div class="swiper-slide">
								<?php the_post_thumbnail('medium_large'); ?>
							</div>
							<?php if($add_images) : ?>
								<?php foreach($add_images as $add_image) : ?>
									<div class="swiper-slide">
										<?php echo wp_get_attachment_image($add_image, 'medium_large'); ?>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php echo(!empty($add_images)) ? '</div>':''; ?>
						
						<?php if($add_images) : ?>
							<div class="swiper-button-prev"></div>
							<div class="swiper-button-next"></div>
						<?php endif; ?>
					</div>
					
					
					<div class="collections__info_left">
						<ul class="collections__info_subtitle">
							<?php $collection_categories = get_the_terms($post->ID, 'collection_category'); ?>
							<?php if($collection_categories) : ?>
								<?php foreach($collection_categories as $collection_category) : ?>
									<li><?php echo $collection_category->name; ?></li>
								<?php endforeach; ?>
							<?php endif; ?>
							<li>
								<?php 
								echo $products_count.'&nbsp;'; 
								echo ($products_count == 1) ? 'stone':'stones';
								?>
							</li>
						</ul>
						<h1><?php the_title(); ?></h1>

						<?php if(has_excerpt()) : ?>
						<div class="collections__info_description">
							<?php the_excerpt(); ?>
						</div>
						<?php endif; ?>

						<?php $floors_terms = get_the_terms($post->ID, 'collection_floors'); ?>
						<?php if($floors_terms) : ?>
						<div class="collections__info_floors">
							<?php foreach($floors_terms as $floors_term) : ?>
							<div class="collection__floor">
								<?php $icon = get_field('icon', $floors_term); ?>
								<?php if($icon) : ?>
									<img src="<?php echo $icon; ?>">
								<?php endif; ?>
								<?php echo $floors_term->name; ?>
							</div>
							<?php endforeach; ?>
						</div>
						<?php endif; ?>
					</div>
					
					
				</div>
			</div>
		</div>

		<div class="container">
			<?php get_template_part('template-parts/collections-filter', '', array('products' => $products)); ?>

			<?php
			//grouping products
			$products_types = [];
			foreach($products as $pID) {
				$product_types = get_the_terms($pID, 'theme_product_type');
				if($product_types) {
					foreach($product_types as $product_type) {

						if(isset($products_types[$product_type->term_id]) && is_array($products_types[$product_type->term_id])) {
							if(!in_array($pID, $products_types[$product_type->term_id])) $products_types[$product_type->term_id][] = $pID;
						} 
						else $products_types[$product_type->term_id][] = $pID;

						if($product_type->parent != 0 ) {
							if(is_array($products_types[$product_type->parent])) {
								$key = array_search($pID, $products_types[$product_type->parent]);
								if($key !== NULL) {
									unset($products_types[$product_type->parent][$key]);
								}
							}
							

						}
					}
				}

			} 

			$sort = get_field('product_type_order');
			
			if($sort && !empty($products_types)) {
				$products_types = array_replace(array_flip($sort), $products_types);
			}
			?>
			<div class="collection__products_list stone__products_list" id="products">

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
							
							
							<h2 class="group__title"><?php echo $group__title; ?></h2>
							<?php if($group->description) : ?>
								<div class="group__description">
									<?php echo $group->description; ?>
								</div>	
							<?php endif; ?>


							<div class="group__products_list cmsmasters-blog cmsmasters-theme-blog-grid">
								<div class="elementor-widget-cmsmasters-theme-blog-grid cmsmasters-post-type-product cmsmasters-block-default cmsmasters-sticky-default elementor-widget elementor-widget-cmsmasters-blog-similar">
									<div class="elementor-widget-cmsmasters-theme-blog-grid__posts">
										<?php foreach($productsID as $productID) :  ?>
											<?php get_template_part('template-parts/content/product-item', '', array('product_id' => $productID)); ?>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>

			<div class="collection__content">
				<?php //the_content(); ?>	
				<?php get_template_part('/template-parts/content/collection-content'); ?>
			</div>

		</div>
		
		
		<?php
		$related__projects_args = array(
			'post_type' => 'projects',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'tax_query' => [
				[
					'taxonomy' => 'collection',
					'field' => 'slug',
					'terms' => $collection_slug
				]
			]
		);
		$related__projects = new WP_Query($related__projects_args);
		?>
		<?php if($related__projects->have_posts()) : ?>
			<section class="related__projects">
				<div class="container">
					<div class="related__projects_title">
						<h2>related projects</h2>
						<a href="/all-projects/" class="link-button">
							<?php _e('Show All', 'stone'); ?> 
							<svg width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.85076 1.75373H0.447762V0.5H10V10.0522H8.74628V2.64926L0.895523 10.5L0 9.60449L7.85076 1.75373Z" fill="black"/></svg>
						</a>
					</div>
					
					<div class="related__projects_items cmsmasters-blog cmsmasters-theme-blog-grid">
						<div class="cmsmasters-post-type-projects cmsmasters-read-more-align-after cmsmasters-block-default cmsmasters-sticky-default elementor-widget elementor-widget-cmsmasters-theme-blog-grid elementor-widget-cmsmasters-blog-similar ">
							<div class="swiper elementor-widget-cmsmasters-theme-blog-grid__posts">

									<div class="swiper-wrapper">
										<?php while($related__projects->have_posts()) : $related__projects->the_post(); ?>
											<div class="swiper-slide">
												<?php $project_id = get_the_ID(); ?>
												<?php get_template_part('template-parts/content/project-item', '', array('project_id' => $project_id)); ?>
											</div>
										<?php endwhile; ?>
										<?php wp_reset_postdata(); ?>
									</div>

							</div>
							<div class="swiper-scrollbar"></div>
						</div>		
					</div>

				</div>
			</section>
		<?php endif; ?>
		
		
		<?php
		$related__collections_args = array(
			'post_type' => 'collections',
			'post_status' => 'publish',
			'posts_per_page' => 4,
			'post__not_in' => array(get_the_ID()),
			'orderby' => 'relevant'
		);
		$related__collections = new WP_Query($related__collections_args);
		?>
		<?php if($related__collections->have_posts()) : ?>
			<section class="related__collections">
				<div class="container">
					<div class="related__collections_title">
						<h2>you may also like</h2>
						<a href="/all-collections/" class="link-button">
							<?php _e('Show All', 'stone'); ?> 
							<svg width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.85076 1.75373H0.447762V0.5H10V10.0522H8.74628V2.64926L0.895523 10.5L0 9.60449L7.85076 1.75373Z" fill="black"/></svg>
						</a>
					</div>
					
					<div class="related__collections_items cmsmasters-blog cmsmasters-theme-blog-grid">
						<div class="swiper cmsmasters-post-type-collections cmsmasters-block-default cmsmasters-sticky-default elementor-widget elementor-widget-cmsmasters-theme-blog-grid elementor-widget-cmsmasters-blog-similar">
							<div class="swiper-wrapper elementor-widget-cmsmasters-theme-blog-grid__posts">
								
								<?php while($related__collections->have_posts()) : $related__collections->the_post(); ?>
									<?php $collection_id = get_the_ID(); ?>
									<div class="swiper-slide">
										<?php get_template_part('template-parts/content/collections-item', '', array('collections_id' => $collection_id)); ?>
									</div>
								<?php endwhile; ?>
								<?php wp_reset_postdata(); ?>
							</div>
							<div class="related__collections_items--bottom">
								<div class="swiper-pagination"></div>
								<a href="/all-collections/" class="link-button"><?php _e('Show All', 'stone'); ?>  <svg width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.85076 1.75373H0.447762V0.5H10V10.0522H8.74628V2.64926L0.895523 10.5L0 9.60449L7.85076 1.75373Z" fill="black"/></svg></a>
							</div>
							
						</div>
					</div>
				</div>
			</section>
		<?php endif; ?>
		
		<?php get_template_part('template-parts/connect'); ?>
		
	</div>		
</div>
<?php get_footer(); ?>