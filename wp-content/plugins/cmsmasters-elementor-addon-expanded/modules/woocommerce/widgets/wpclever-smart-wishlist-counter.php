<?php
namespace CmsmastersElementor\Modules\Woocommerce\Widgets;

use CmsmastersElementor\Modules\Woocommerce\Widgets\Wpclever\CompareWishlistBase\Compare_Wishlist_Counter_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class Wpclever_Smart_Wishlist_Counter extends Compare_Wishlist_Counter_Base {

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
		return __( 'Wpclever Smart Wishlist Counter', 'cmsmasters-elementor' );
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
		return 'cmsicon-wishlist-counter';
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
			'wishlist',
		);
	}

	public function cmsmasters_class_prefix() {
		return 'elementor-widget-cmsmasters-wpclever-wishlist-counter';
	}

	public function individual_class() {
		$class = array(
			'wrapper' => 'site-header-wishlist',
			'trigger' => 'woosw-menu',
			'link' => 'header-wishlist',
		);

		return $class;
	}

	public function get_obj() {
		$obj = new \WPCleverWoosw;

		return $obj;
	}

	public function default_text() {
		return __( 'Wishlist', 'cmsmasters-elementor' );
	}

	public function default_icon() {
		return array(
			'value' => 'far fa-heart',
			'library' => 'fa-regular',
		);
	}
}
