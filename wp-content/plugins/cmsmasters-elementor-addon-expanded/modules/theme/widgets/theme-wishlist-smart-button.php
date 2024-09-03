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
 * Addon theme wishlist smart button widget.
 *
 * Addon widget that displays theme wishlist smart button.
 *
 * @since 1.0.0
 */
class Theme_Wishlist_Smart_Button extends Product_Add_To_Cart {

	/**
	 * Get widget name.
	 *
	 * Retrieve theme wishlist smart button widget name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'cmsmasters-theme-wishlist-smart-button';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve theme wishlist smart button widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Theme Wishlist Smart Button', 'cmsmasters-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve theme wishlist smart button widget icon.
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
			'wishlist',
			'count',
		);
	}

	public function get_widget_class() {
		return 'elementor-widget-cmsmasters-theme-wishlist-smart-button';
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
	}

	/**
	 * Render theme wishlist smart button widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		echo '<div class="elementor-widget-cmsmasters-theme-wishlist-smart-button__wrap">';
			// echo YITH_Request_Quote()->get_raq_item_number();
			// '<span>'. ThemeModule::action_agp_qdt_itens() .'</span>'
			// '<span>'. YITH_Request_Quote()->get_raq_item_number() .'</span>'
			echo '<a class="elementor-widget-cmsmasters-theme-wishlist-smart-button__link" href="'. YITH_Request_Quote()->get_raq_page_url() .'">';

				Icons_Manager::render_icon( array(
					'value' => 'themeicon- theme-icon-heart-empty',
					'library' => 'themeicon-',
				), array( 'class' => 'product-empty-icon' ) );

				Icons_Manager::render_icon( array(
					'value' => 'themeicon- theme-icon-heart-full',
					'library' => 'themeicon-',
				), array( 'class' => 'product-full-icon' ) );

				echo '<span class="elementor-widget-cmsmasters-theme-wishlist-smart-button__count">';
					ThemeModule::action_agp_qdt_itens();
				echo '</span>';

			echo '</a>';
			?>
			<script type="text/javascript">
				jQuery( document ).ready( function( jQuery ) {
					init_actions();

					// Init actions
					function init_actions() {
						change_quote_count();
					}

					// Get Program Title Options
					function change_quote_count() {
						jQuery('.add-request-quote-button').on( 'click', function() {
							setTimeout( () => {
								jQuery.ajax({
									type : 'POST',
									url : 'wp-admin/admin-ajax.php',
									data: {
										'action': 'action_agp_qdt_itens',
									},
									success: function( response ) {
										const newCount = response;
										// $new.html( response );
										jQuery('#yith > span').html( newCount );
										// jQuery('#yith').addClass('teste');
									}
								});
							}, 1000 );
						} );
					}

				} );
			</script>
			<?php

		echo '</div>';
	}

}
