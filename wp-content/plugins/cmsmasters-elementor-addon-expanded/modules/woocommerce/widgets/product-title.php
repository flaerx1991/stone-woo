<?php
namespace CmsmastersElementor\Modules\Woocommerce\Widgets;

use CmsmastersElementor\Modules\TemplatePages\Widgets\Post_Title;
use CmsmastersElementor\Modules\Woocommerce\Traits\Woo_Singular_Widget;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Product title widget.
 *
 * Addon widget that displays title of current WooCommerce product.
 *
 * @since 1.0.0
 */
class Product_Title extends Post_Title {

	use Woo_Singular_Widget;

	/**
	 * Get widget title.
	 *
	 * Retrieve widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Product Title', 'cmsmasters-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'cmsicon-product-title';
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
			'title' => 'cmsmasters-woocommerce-product-title',
			'link' => 'cmsmasters-woocommerce-product-url',
		);
	}

	protected function register_controls() {
		parent::register_controls();

		$this->update_control(
			'title_link_switcher',
			array(
				'options' => array(
					'no' => array(
						'title' => __( 'None', 'cmsmasters-elementor' ),
					),
					'yes' => array(
						'title' => __( 'Product', 'cmsmasters-elementor' ),
						'description' => __( 'Open Product', 'cmsmasters-elementor' ),
					),
					'custom' => array(
						'title' => __( 'Custom', 'cmsmasters-elementor' ),
						'description' => __( 'Custom URL', 'cmsmasters-elementor' ),
					),
				),
			)
		);
	}

}
