<?php get_header(); ?>

<?php
$s_word = get_search_query();
$args = array(
	'post_type' => array('product', 'projects', 'collections', 'post'),
	's' => $s_word,
	'posts_per_page' => -1,
	'fields' => 'ids',
	'post_status' => 'publish',
	'orderby' => 'DATE',
	'order' => 'DESC'
);

$posts = get_posts($args);


if($posts) {
	$search_data = [];
	foreach($posts as $pID) { 
		$post_type = get_post_type($pID);
		$search_data[$post_type][] = $pID;
	}
}

?>



<div class="search__results search__results_wrapper">
	<div class="stone-container">
		<div class="search__results_header">
			<h1>search results</h1>
			<p class="search__results_subtitle"><?php echo count($posts); ?> results for “<?php echo $s_word; ?>”</p>
		</div>
		<div class="search__results_body">
			<?php if(is_array($search_data['product']) && count($search_data['product']) > 0) : ?>
				<div class="search__results_block product__block">
					<h2>Products</h2>
					
					<div class="stone__products_list cmsmasters-blog cmsmasters-theme-blog-grid">
						<div class="cmsmasters-post-type-product cmsmasters-block-default cmsmasters-sticky-default elementor-widget elementor-widget-cmsmasters-theme-blog-grid elementor-widget-cmsmasters-blog-similar">
							<div class="elementor-widget-cmsmasters-theme-blog-grid__posts">
								<?php foreach($search_data['product'] as $product_id) : ?>
									<?php get_template_part('/template-parts/content/product-item', '', array('product_id' => $product_id)); ?>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
			
			<?php if(is_array($search_data['collections']) && count($search_data['collections']) > 0) : ?>
				<div class="search__results_block collections__block">
					<h2>Collections</h2>
					
					<div class="cmsmasters-post-type-collections cmsmasters-block-default cmsmasters-sticky-default elementor-widget elementor-widget-cmsmasters-theme-blog-grid elementor-widget-cmsmasters-blog-similar">
						<div class="elementor-widget-cmsmasters-theme-blog-grid__posts">
							<?php foreach($search_data['collections'] as $collection_id) : ?>
								<?php get_template_part('/template-parts/content/collections-item', '', array('collections_id' => $collection_id)); ?>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
			
			<?php if(is_array($search_data['projects']) && count($search_data['projects']) > 0) : ?>
				<div class="search__results_block projects__block">
					<h2>Projects</h2>
					
					<div class="cmsmasters-post-type-projects cmsmasters-read-more-align-after cmsmasters-block-default cmsmasters-sticky-default elementor-widget elementor-widget-cmsmasters-theme-blog-grid elementor-widget-cmsmasters-blog-similar">
						<div class="elementor-widget-cmsmasters-theme-blog-grid__posts">
							<?php foreach($search_data['projects'] as $project_id) : ?>
								<?php get_template_part('/template-parts/content/project-item', '', array('project_id' => $project_id)); ?>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
			
			<?php if(is_array($search_data['post']) && count($search_data['post']) > 0) : ?>
				<div class="search__results_block projects__block">
					<h2>Articles</h2>
					
					<div class="cmsmasters-post-type-post cmsmasters-filter-minimized-on-mobile cmsmasters-pagination-fullwidth--no cmsmasters-pagination--with-buttonyes cmsmasters-pagination--pagination cmsmasters-pagination-pagination-type--numbers_and_prev_next cmsmasters-pagination--icon-skin-yes cmsmasters-block-default cmsmasters-sticky-default elementor-widget elementor-widget-cmsmasters-theme-blog-grid elementor-widget-cmsmasters-blog-similar">
						<div class="elementor-widget-cmsmasters-theme-blog-grid__posts">
							<?php foreach($search_data['post'] as $post_id) : ?>
								<?php get_template_part('/template-parts/content/post-item', '', array('post_id' => $post_id)); ?>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>