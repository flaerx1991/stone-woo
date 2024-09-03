<?php 
$hide_empty = true;
$outlet = $args["outlet"];

$filters = get_field('products_filters', 'options');
$get_param_exists = false;
foreach($filters as $filter) {
	$taxonomy = $filter['products_filter_taxonomies'];
	if(isset($_GET[$taxonomy]) && !empty($_GET[$taxonomy])) $get_param_exists = true;
}

$product_ids = [];

if($get_param_exists) {
	//get all products ids	
	$args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'fields' => 'ids'
	);
	$args['tax_query'] = stone_find_get_parameters();
	$product_ids = get_posts($args);
}

?>

<div class="elementor-widget-cmsmasters-theme-blog-grid__header-side cmsmasters-filter-nav-multiple">
	<div class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-popup-close">
		<h2>Filter by</h2>
		<i class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-popup-close-icon themeicon- theme-icon-close"></i>
	</div>
	
	<a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>" class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-clear-all-button" style="<?php echo ($get_param_exists) ? 'display:block':'display:none'; ?>">Clear All</a>	
	
	<ul class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list">
		
		<li class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item" data-taxonomy-id="sorting">
			<span class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger-wrap">
				<span class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-label">Sort by</span>
				<span class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger">
					<span class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger-value" data-default="Latest">Popular</span>
					<i class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger-icon themeicon- theme-icon-arrow-forward"></i>
				</span>
			</span>
			<ul class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list sorting ps">
				<li class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item checked" data-category-id="popularity">
					<input class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item-checkbox" type="radio" id="popular" name="sorting" checked>
					<label for="popular">Popular</label>
					<i class="checkbox-icon full themeicon- theme-icon-chech-line"></i>
				</li>
				<li class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item" data-category-id="date">
					<input class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item-checkbox" type="radio" id="latest" name="sorting">
					<label for="latest">Latest</label>
					<i class="checkbox-icon full themeicon- theme-icon-chech-line"></i>
				</li>
				
			</ul>
		</li>
		
		<?php	
		foreach($filters as $filter) {
		
			$taxonomy = $filter['products_filter_taxonomies'];

			$terms = get_terms([
				'taxonomy' => $taxonomy
			]);

			//because collection taxonomy assigned for product and projects post types, for display correct count items for each term 
			//we need to subtract collections for projects post type
			if($taxonomy == 'collection') {
				$projects_ids = get_posts([
					'post_type' => 'projects',
					'fields' => 'ids',
					'posts_per_page' => -1,
					'post_status' => 'publish'
				]);
				$projects_collection_count = [];
				foreach($projects_ids as $projects_id) {
					$project_collection_terms = get_the_terms($projects_id, 'collection');
					if($project_collection_terms) {
						foreach($project_collection_terms as $project_collection_term) {
							if(isset($projects_collection_count[$project_collection_term->term_id])) {
								$projects_collection_count[$project_collection_term->term_id] = $projects_collection_count[$project_collection_term->term_id] + 1;
							}
							else $projects_collection_count[$project_collection_term->term_id] = 1;						
						}					
					}
				}
			}	
			
			if(isset($_GET[$taxonomy])) {
				$GET_param = $_GET[$taxonomy];
				if($GET_param || (is_array($GET_param) && !empty($GET_param))) $active = true;
				else $active = false;
			}
			else {
				$active = false;
				$GET_param = '';
			}
			?>
		
			<?php if($terms) : ?>
				<li class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item" data-taxonomy-id="<?php echo $taxonomy; ?>">
					<span class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger-wrap">
						<span class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger <?php echo($active) ? 'active suptitle-active':'default-value'; ?>">
							<span class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger-value" data-default="<?php echo $filter['filter_title']; ?>" data-more="0">
								<?php 
								if(!isset($GET_param) || empty($GET_param))  echo $filter['filter_title'];
								elseif(is_array($GET_param)) {
									$param_term = get_term_by('slug', $GET_param[0], $taxonomy);
									if($param_term) {
										$param_term_name = $param_term->name;
									}
									else $param_term_name = $GET_param[0];
									if(count($GET_param) == 1) echo $param_term_name;
									else echo $param_term_name . ' + 1';
								}
								?>								
							</span>
							<span class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger-clear-wrap">
								<span class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger-clear">
									<?php _e('Clear', 'stone'); ?>
								</span>
								<i class="elementor-widget-cmsmasters-theme-blog-grid__multiple-taxonomy-list-item-trigger-icon themeicon- theme-icon-arrow-forward"></i>
							</span>
						</span>
					</span>
					<ul class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list ps" <?php echo($active) ? 'style="display:block;"':''; ?>>
						<?php foreach($terms as $term) : ?>
							<?php
							$checked = false;
							if((is_array($GET_param) && in_array($term->slug, $GET_param)) || $GET_param == $term->slug){
								$checked = true;
							}							
							?>
							
							<?php 						
							//get count products for each assigned term in taxonomy
							$post_list_args = [
								'post_type' => 'product',
								'post_status' => 'publish',
								'posts_per_page' => -1,
								'fields' => 'ids',
								'post__in' => $product_ids,
								'tax_query' => [
									[
										'taxonomy' => $taxonomy,
										'field'    => 'term_id',
										'terms'    => $term->term_id
									]
								]
							];
							if($outlet == 0) {
								$post_list_args['tax_query'][] = [
									'taxonomy' => 'product_tag',
									'field'    => 'slug',
									'terms'    => ['outlet'],
									'operator' => 'NOT IN'
								];
							}
							elseif($outlet == 1) {
								$post_list_args['tax_query'][] = [
									'taxonomy' => 'product_tag',
									'field'    => 'slug',
									'terms'    => 'outlet'
								];
							}
			
							$posts_list = get_posts($post_list_args);
							if($posts_list) $posts_count = count($posts_list);
							else $posts_count = 0;
							
							?>
						
							
								<li class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item <?php echo($checked) ? 'checked':''; ?> <?php echo($posts_count == 0) ? 'disabled':''; ?>" data-category-id="<?php echo $term->term_id; ?>">
									<input class="elementor-widget-cmsmasters-theme-blog-grid__multiple-category-list-item-checkbox" type="checkbox" id="<?php echo $term->term_id; ?>" name="<?php echo $term->name; ?>" value="<?php echo $term->name; ?>" <?php echo($checked) ? 'checked':''; ?>>
									<label for="<?php echo $term->term_id; ?>" data-count="<?php echo $posts_count; ?>" <?php echo($term->parent > 0) ? 'class="childness"':''; ?>>
										<?php echo $term->name; ?>
									</label>
									<i class="checkbox-icon full themeicon- theme-icon-chech-line"></i>
								</li>
							
						<?php endforeach; ?>
					</ul>
				</li>
			<?php endif; ?>				
		<?php } ?>
	</ul>
</div>
