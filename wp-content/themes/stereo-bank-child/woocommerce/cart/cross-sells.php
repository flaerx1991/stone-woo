<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cross-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( $cross_sells ) : ?>

	<div class="cross-sells">
		<?php
		$heading = apply_filters( 'woocommerce_product_cross_sells_products_heading', __( 'You may be interested in&hellip;', 'woocommerce' ) );

		if ( $heading ) :
			?>
			<h2><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>

		<?php woocommerce_product_loop_start(); ?>
		<div class="products_list stone__products_list">
			<div class="elementor-widget-cmsmasters-theme-blog-grid__posts-variable">
				<div class="elementor-widget-cmsmasters-theme-blog-grid cmsmasters-post-type-product cmsmasters-block-default cmsmasters-sticky-default elementor-widget elementor-widget-cmsmasters-blog-similar ">
					<div class="elementor-widget-cmsmasters-theme-blog-grid__posts" id="filter-products-response">
						<?php foreach ( $cross_sells as $cross_sell ) : ?>

							<?php
							$post_object = get_post( $cross_sell->get_id() );
							setup_postdata( $GLOBALS['post'] =& $post_object ); 
							//wc_get_template_part( 'content', 'product' );

							$productID = get_the_ID();
							get_template_part('template-parts/content/product-item', '', array('product_id' => $productID));
							?>

						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>

		<?php woocommerce_product_loop_end(); ?>

	</div>
	<?php
endif;

wp_reset_postdata();
