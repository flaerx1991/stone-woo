<?php $pID = $args["collections_id"]; ?>
<article id="post-<?php echo $pID; ?>" class="elementor-widget-cmsmasters-theme-blog-grid__post post-1294 collections type-collections status-publish has-post-thumbnail hentry 
collection_category-tumbled">
    <div class="elementor-widget-cmsmasters-theme-blog-grid__post-inner">
        <div class="elementor-widget-cmsmasters-theme-blog-grid__post-thumbnail">

            <?php $badges = wp_get_object_terms($pID, 'collection_badges'); ?>
            <?php if($badges) : ?>
                <div class="elementor-widget-cmsmasters-theme-blog-grid__post-meta-acf collection_badges">
                    <?php foreach($badges as $badge) : ?>
                        <a href="<?php echo get_term_link($badge); ?>" class="elementor-widget-cmsmasters-theme-blog-grid__post-meta-value collection_badges <?php echo $badge->slug; ?>"><?php echo $badge->name; ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <a href="<?php get_the_permalink($pID); ?>" class="elementor-widget-cmsmasters-theme-blog-grid__post-thumbnail-inner">
                <?php echo wp_get_attachment_image(get_post_thumbnail_id($pID), 'medium_large', '', array('class' => 'cmsmasters_img')); ?>

                <?php if($collection_image_overlay = get_field('collection_image_overlay', $pID)) : ?>
                    <?php echo wp_get_attachment_image($collection_image_overlay, 'medium_large', '', array('class' => 'project_image_overlay cmsmasters_img')); ?>
                <?php endif ;?>
            </a>
        </div>
        <h3 class="elementor-widget-cmsmasters-theme-blog-grid__post-title">
            <a href="<?php get_the_permalink($pID); ?>"><?php echo get_the_title($pID); ?></a>
        </h3>
        <div class="elementor-widget-cmsmasters-theme-blog-grid__post-footer">
            <?php $collection_category = wp_get_object_terms($pID, 'collection_category'); ?>
            <?php if($collection_category) : ?>
                <?php $collection_categories = []; ?>
                <div class="elementor-widget-cmsmasters-theme-blog-grid__post-meta-acf collection_category">
                    <?php foreach($collection_category as $category) : ?>
                        <?php 
                        $collection_categories[] = '<a href="'.get_term_link($category).'" class="elementor-widget-cmsmasters-theme-blog-grid__post-meta-value collection_category '.$category->slug.'">'.$category->name.'</a>'; 
                        ?>
                    <?php endforeach; ?>
                    <?php if(!empty($collection_categories)) echo implode(',', $collection_categories); ?>
                </div>
            <?php endif; ?>

            <?php
            $terms = wp_get_post_terms($pID, 'collection' ); //hidden taxonomy for collections post type
            ?>
            <?php if ( ! empty($terms) && ! is_wp_error( $terms )) : ?>
                <span class="elementor-widget-cmsmasters-theme-blog-grid__post-footer-separator"></span>
                <?php if(function_exists('get_product_count_with_collection')) get_product_count_with_collection( $terms ); ?>
            <?php endif; ?>
        </div>
    </div>
</article>