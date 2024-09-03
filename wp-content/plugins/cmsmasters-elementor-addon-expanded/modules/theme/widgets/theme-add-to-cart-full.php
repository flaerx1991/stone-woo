<?php
namespace CmsmastersElementor\Modules\Theme\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CmsmastersElementor\Base\Base_Widget;
use CmsmastersElementor\Controls_Manager as CmsmastersControls;
use CmsmastersElementor\Modules\Woocommerce\Widgets\Product_Add_To_Cart;
use CmsmastersElementor\Modules\Theme\Module as ThemeModule;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Addon theme add to cart widget.
 *
 * Addon widget that displays theme add to cart.
 *
 * @since 1.0.0
 */
class Theme_Add_To_Cart_Full extends Product_Add_To_Cart {

	/**
	 * Get widget name.
	 *
	 * Retrieve theme add to cart widget name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'cmsmasters-theme-add-to-cart';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve theme add to cart widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Theme Add To Cart', 'cmsmasters-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve theme add to cart widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'cmsicon-products';
	}

	/**
	 * Get widget unique keywords.
	 *
	 * Retrieve the list of unique keywords the widget belongs to.
	 *
	 * @since 1.0.0
	 *
	 * @return array Widget unique keywords.
	 */
	public function get_unique_keywords() {
		return array(
			'theme',
			'product',
			'button',
			'add',
		);
	}

	public function get_widget_class() {
		return 'elementor-widget-cmsmasters-theme-add-to-cart';
	}

	public function get_widget_selector() {
		return '.' . $this->get_widget_class();
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->update_control(
			'quantity_label_width',
			array(
				'selectors' => array(
					'{{WRAPPER}} .quantity' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->update_control(
			'alignment',
			array(
				'selectors' => array(
					'{{WRAPPER}}' => '--cmsmasters-button-alignment: {{SIZE}}{{UNIT}};',
				),
			)
		);
	}

	protected function product_price( $product ) {
		$has_price = false;

		if ( $product->is_type( 'variable' ) ) {
			$price = (int) $product->get_variation_price();
		} else {
			$price = (int) $product->get_price();
		}

		if ( $price ) {
			$has_price = $price;
		}

		return $has_price;
	}

	/**
	 * Render theme add to cart widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		global $product;

		$price = $this->product_price( $product );

		if ( $price ) {
			parent::render();
		} else {
			echo '<div class="elementor-widget-cmsmasters-theme-add-to-cart__add-to-quote">';

				yith_ywraq_render_button( get_the_ID() );

			echo '</div>';
		}
	}

}
