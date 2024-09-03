<?php $pID = $args["product_id"]; ?>
<article id="post-<?php echo $pID; ?>" class="elementor-widget-cmsmasters-theme-blog-grid__post post-<?php echo $pID; ?> product type-product status-publish has-post-thumbnail product_cat-tumbled-paver product_badges-premium theme_product_type-coping theme_product_type-double-bullnose theme_product_type-single-bullnose product_size-24x36x2 product_pack-140 product_uom-sqft collection-ivory-travertine instock downloadable taxable shipping-taxable product-type-simple">
    <div class="elementor-widget-cmsmasters-theme-blog-grid__post-inner">
        <div class="elementor-widget-cmsmasters-theme-blog-grid__post-thumbnail">
			
            <?php $badges = wp_get_object_terms($pID, 'product_badges'); ?>
            <?php if($badges) : ?>
                <div class="elementor-widget-cmsmasters-theme-blog-grid__post-meta-acf product_badges">
                    <?php foreach($badges as $badge) : ?>
                        <a href="<?php echo get_term_link($badge); ?>" class="elementor-widget-cmsmasters-theme-blog-grid__post-meta-value product_badges <?php echo $badge->slug; ?>"><?php echo $badge->name; ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
			
            <div class="elementor-widget-cmsmasters-theme-blog-grid__post-wishlist">
                <a class="woosw-btn woosw-btn-2656 elementor-widget-cmsmasters-theme-blog-grid__post-wishlist__general woosw-btn-has-icon elementor-widget-cmsmasters-theme-blog-grid__post-wishlist-button" data-id="<?php echo $pID; ?>" data-product_name="<?php echo get_the_title($pID); ?>" data-product_image="<?php echo wp_get_attachment_image_url(get_post_thumbnail_id($pID), 'thumbnail'); ?>" href="?add-to-wishlist=<?php echo $pID; ?>">
                    <div class="elementor-widget-cmsmasters-theme-blog-grid__post-wishlist-button-icon-wrapper"><span class="elementor-widget-cmsmasters-theme-blog-grid__post-wishlist-button-icon elementor-widget-cmsmasters-theme-blog-grid__post-wishlist-button-normal">
                        <i class="themeicon- theme-icon-heart-empty"></i></span>
                        <span class="elementor-widget-cmsmasters-theme-blog-grid__post-wishlist-button-icon elementor-widget-cmsmasters-theme-blog-grid__post-wishlist-button-active">
                            <i class="themeicon- theme-icon-heart-full"></i>
                        </span>
                    </div>
                </a>
            </div>
            <a href="<?php echo get_the_permalink($pID); ?>" class="elementor-widget-cmsmasters-theme-blog-grid__post-thumbnail-inner">
                <?php echo wp_get_attachment_image(get_post_thumbnail_id($pID), 'medium_large', '', array('class' => 'cmsmasters_img')); ?>
            </a>
        </div>
        
        <div class="elementor-widget-cmsmasters-theme-blog-grid__post-footer">
            <?php $category = wp_get_object_terms($pID, 'product_cat'); ?>
            <?php if($category) : ?>
                <?php $categories = []; ?>
                <div class="elementor-widget-cmsmasters-theme-blog-grid__post-meta-acf product_category">
                    <?php foreach($category as $cat) : ?>
                        <?php 
                        $categories[] = '<a href="'.get_term_link($cat).'" class="elementor-widget-cmsmasters-theme-blog-grid__post-meta-value collection_category '.$cat->slug.'">'.$cat->name.'</a>'; 
                        ?>
                    <?php endforeach; ?>
                    <?php if(!empty($categories)) echo implode(',', $categories); ?>
                </div>
            <?php endif; ?>
        </div>
         
        <h3 class="elementor-widget-cmsmasters-theme-blog-grid__post-title">
        	<a href="<?php get_the_permalink($pID); ?>"><?php echo get_the_title($pID); ?></a>
        </h3>
        <div class="elementor-widget-cmsmasters-theme-blog-grid__post-excerpt"></div>
        <span class="elementor-widget-cmsmasters-theme-blog-grid__post-availability-wrap">
        	<span class="elementor-widget-cmsmasters-theme-blog-grid__post-availability">
        		<span class="elementor-widget-cmsmasters-theme-blog-grid__post-availability-label"><?php _e('Available', 'stone'); ?></span>
        		<span class="elementor-widget-cmsmasters-theme-blog-grid__post-availability-count"><?php the_field('available', $pID); ?></span>
            </span>
            <span class="elementor-widget-cmsmasters-theme-blog-grid__post-incoming">
            	<span class="elementor-widget-cmsmasters-theme-blog-grid__post-incoming-label"><?php _e('Incoming', 'stone'); ?></span>
            	<span class="elementor-widget-cmsmasters-theme-blog-grid__post-incoming-count"><?php the_field('incoming', $pID); ?></span>
         	</span>
        </span>
   </div>
</article>