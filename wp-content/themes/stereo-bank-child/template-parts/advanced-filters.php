<?php $show_btn = $args["show_button"]; ?>
<form class="product-page-filter advanced-filters" action="/products/" method="get">
	<div class="product-page-filter-container">	
		
		<?php $filters = get_field('products_filters', 'options'); ?>
		
		<?php foreach($filters as $filter) : ?>
		
			<?php
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
			?>
		
		
			<?php if($terms) : ?>
				<div class="product-page-filter-item" data-filter-by="<?php echo $taxonomy; ?>">
					<div class="product-page-filter-top">
						<div class="label"><?php echo $filter['filter_sup_title']; ?></div>
						<div class="title" data-default-title="<?php echo $filter['filter_title']; ?>">
							<span><?php echo $filter['filter_title']; ?></span>
							<div class="icon">
								<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.92512 5.63719L2.69562 4.87054L8.36099 10.5359L14.02 4.87054L14.7969 5.63719L8.36099 12.0731L1.92511 5.63719L1.92512 5.63719Z" fill="black"/></svg>
							</div>
						</div> 
					</div>
					<div class="product-page-filter-body">
						<?php foreach($terms as $term) : ?>						
							<div class="checked-item <?php echo($term->count == 0 || $term->count - $projects_collection_count[$term->term_id] == 0) ? 'disabled':''; ?>">
								<input type="checkbox" name="<?php echo $taxonomy; ?>[]" id="<?php echo $term->term_id; ?>" value="<?php echo $term->slug; ?>">
								<?php 
								if($taxonomy == 'collection') {
									if(isset($projects_collection_count[$term->term_id])) {
										$count = $term->count - $projects_collection_count[$term->term_id];
									}
									else $count = $term->count;
								}
								else $count = $term->count;
								?>
								<label for="<?php echo $term->term_id; ?>"  data-count="<?php echo $count; //count with outlet products ?>" <?php echo($term->parent > 0) ? 'class="childness"':''; ?>>
									<?php echo $term->name; ?>
								</label>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
		
		
		<?php if(isset($show_btn) && $show_btn == 1) : ?>
			<button class="filter-submit" type="submit" disabled>
				<?php the_field('products_filters_submit_button_text', 'options'); ?>
				<svg xmlns="http://www.w3.org/2000/svg" width="10" height="11" viewBox="0 0 10 11" fill="none"><path d="M7.85076 1.75373H0.447762V0.5H10V10.0522H8.74628V2.64926L0.895523 10.5L0 9.60449L7.85076 1.75373Z" fill="#828282"/></svg>
			</button>
		<?php endif; ?>
	</div>
	<!--input type="hidden" name="outlet" id="outlet" value=""--><?php //count all products with outlet ?>
</form>