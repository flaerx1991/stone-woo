<?php $pID = $args["post_id"]; ?>
<article id="post-<?php echo $pID; ?>" class="elementor-widget-cmsmasters-theme-blog-grid__post post-<?php echo $pID; ?> post type-post status-publish format-standard has-post-thumbnail hentry">
	<div class="elementor-widget-cmsmasters-theme-blog-grid__post-inner">
		<div class="elementor-widget-cmsmasters-theme-blog-grid__post-thumbnail">
			<a href="<?php echo get_the_permalink($pID); ?>" class="elementor-widget-cmsmasters-theme-blog-grid__post-thumbnail-inner">
				<?php echo wp_get_attachment_image(get_post_thumbnail_id($pID), 'medium_large', '', array('class' => 'cmsmasters_img')); ?>
			</a>
		</div>
		<div class="elementor-widget-cmsmasters-theme-blog-grid__post-title_wrap">
			<h3 class="elementor-widget-cmsmasters-theme-blog-grid__post-title">
				<a href="<?php get_the_permalink($pID); ?>"><?php echo get_the_title($pID); ?></a>
			</h3>
			
			<?php $category = wp_get_object_terms($pID, 'category'); ?>
            <?php if($category) : ?>
                <?php $categories = []; ?>
                <div class="elementor-widget-cmsmasters-theme-blog-grid__post-meta">
                    <?php foreach($category as $cat) : ?>
                        <?php 
                        $categories[] = '<a href="'.get_term_link($cat).'">'.$cat->name.'</a>'; 
                        ?>
                    <?php endforeach; ?>
                    <?php if(!empty($categories)) echo implode(', ', $categories); ?>
                </div>
            <?php endif; ?>
		</div>
		<?php if(has_excerpt()) : ?>
			<div class="elementor-widget-cmsmasters-theme-blog-grid__post-excerpt"><?php echo get_the_excerpt(); ?></div>
		<?php endif; ?>
	</div>
</article>