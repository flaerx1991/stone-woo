<?php $pID = $args["project_id"]; ?>
<article id="post-<?php echo $pID; ?>" class="elementor-widget-cmsmasters-theme-blog-grid__post post-<?php echo $pID; ?> projects type-projects status-publish has-post-thumbnail hentry">
	<div class="elementor-widget-cmsmasters-theme-blog-grid__post-inner">
		<div class="elementor-widget-cmsmasters-theme-blog-grid__post-thumbnail_wrap">
			<div class="elementor-widget-cmsmasters-theme-blog-grid__post-thumbnail">
				<a href="<?php echo get_the_permalink($pID); ?>" class="elementor-widget-cmsmasters-theme-blog-grid__post-thumbnail-inner">
					<?php echo wp_get_attachment_image(get_post_thumbnail_id($pID), 'medium_large', '', array('class' => 'cmsmasters_img')); ?>
				</a>
			</div>
			<div class="elementor-widget-cmsmasters-theme-blog-grid__button-wrap">
				<a class="elementor-widget-cmsmasters-theme-blog-grid__button cmsmasters-theme-button" href="<?php echo get_the_permalink($pID); ?>">
					<span class="cmsmasters-wrap-icon"><i aria-hidden="true" class="themeicon- theme-icon-arrow-back"></i></span>
					<span><?php _e('Explore', 'stone'); ?></span>
				</a>
			</div>
		</div>
		<h3 class="elementor-widget-cmsmasters-theme-blog-grid__post-title">
			<a href="<?php echo get_the_permalink($pID); ?>">
				<?php echo get_the_title($pID); ?>
			</a>
		</h3>
		
		<div class="elementor-widget-cmsmasters-theme-blog-grid__post-footer">
			<div class="elementor-widget-cmsmasters-theme-blog-grid__post-footer-left">
				<h4 class="elementor-widget-cmsmasters-theme-blog-grid__post-meta-acf date">
					<span class="elementor-widget-cmsmasters-theme-blog-grid__post-meta-value date">2022</span>
				</h4>
				<?php $collections = wp_get_object_terms($pID, 'collection'); ?>
				<?php if($collections) : ?>
					<div class="elementor-widget-cmsmasters-theme-blog-grid__post-meta-acf collection">
						<?php foreach($collections as $collection) : ?>
							<a href="<?php echo get_term_link($collection); ?>" class="elementor-widget-cmsmasters-theme-blog-grid__post-meta-value collection <?php echo $badge->slug; ?>">
								<?php echo $collection->name; ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
			
			<div class="elementor-widget-cmsmasters-theme-blog-grid__post-footer-right">
				<?php if($location = get_field('location', $pID)) : ?>
					<div class="elementor-widget-cmsmasters-theme-blog-grid__post-meta-acf location">
						<span class="elementor-widget-cmsmasters-theme-blog-grid__post-meta-label location"><?php _e('location', 'stone'); ?></span>
						<span class="elementor-widget-cmsmasters-theme-blog-grid__post-meta-value location"><?php echo $location; ?></span>
					</div>
				<?php endif; ?>
				
				<?php $builder = wp_get_object_terms($pID, 'project_design_build_firms'); ?>
				<?php if($builder) : ?>
					<div class="elementor-widget-cmsmasters-theme-blog-grid__post-meta-acf builder">
						<span class="elementor-widget-cmsmasters-theme-blog-grid__post-meta-label builder"><?php _e('builder', 'stone'); ?></span>				
						<a href="<?php echo get_term_link($builder[0]); ?>" class="elementor-widget-cmsmasters-theme-blog-grid__post-meta-value builder <?php echo $builder[0]->slug; ?>">
							<?php echo $builder[0]->name; ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</article>