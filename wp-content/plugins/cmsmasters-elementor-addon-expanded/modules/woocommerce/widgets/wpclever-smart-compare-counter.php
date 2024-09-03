<?php
namespace CmsmastersElementor\Modules\Woocommerce\Widgets;

use CmsmastersElementor\Modules\Woocommerce\Widgets\Wpclever\CompareWishlistBase\Compare_Wishlist_Counter_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class Wpclever_Smart_Compare_Counter extends Compare_Wishlist_Counter_Base {
	
	/**
	 * Get widget title.
	 *
	 * Retrieve the widget title.
	 *
	 * @since 1.11.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Wpclever Smart Compare Counter', 'cmsmasters-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve the widget icon.
	 *
	 * @since 1.11.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'cmsicon-compare-bounter';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the widget keywords.
	 *
	 * @since 1.11.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_unique_keywords() {
		return array(
			'compare',
		);
	}

	public function cmsmasters_class_prefix() {
		return 'elementor-widget-cmsmasters-wpclever-compare-counter';
	}

	public function individual_class() {
		$class = array(
			'wrapper' => 'site-header-compare',
			'trigger' => 'woosc-menu',
			'link' => 'header-compare',
		);

		return $class;
	}

	public function get_obj() {
		$obj = new \WPCleverWoosc;

		return $obj;
	}

	public function default_text() {
		return __( 'Compare', 'cmsmasters-elementor' );
	}

	public function default_icon() {
		return array(
			'value' => 'far fa-chart-bar',
			'library' => 'fa-regular',
		);
	}
}