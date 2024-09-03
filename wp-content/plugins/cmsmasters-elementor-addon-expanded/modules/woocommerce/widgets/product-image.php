<?php
namespace CmsmastersElementor\Modules\Woocommerce\Widgets;

use CmsmastersElementor\Modules\TemplatePages\Widgets\Post_Featured_Image;
use CmsmastersElementor\Modules\Woocommerce\Traits\Woo_Singular_Widget;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Addon WooCommerce Product Image widget.
 *
 * Addon widget that displays image of current product.
 *
 * @since 1.0.0
 */
class Product_Image extends Post_Featured_Image {

	use Woo_Singular_Widget;

	/**
	 * Get group name.
	 *
	 * @since 1.6.5
	 *
	 * @return string Group name.
	 */
	public function get_group_name() {
		return 'cmsmasters-post-featured-image';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Product Image', 'cmsmasters-elementor' );
	}

	/**
	 * Get tag names.
	 *
	 * Retrieve widget dynamic controls tag names.
	 *
	 * @since 1.0.0
	 *
	 * @return array Widget dynamic controls tag names.
	 */
	protected function get_tag_names() {
		return array(
			'image_id' => 'cmsmasters-woocommerce-product-image-id',
			'image_url' => 'cmsmasters-woocommerce-product-image-url',
			'post_url' => 'cmsmasters-woocommerce-product-url',
		);
	}

}
