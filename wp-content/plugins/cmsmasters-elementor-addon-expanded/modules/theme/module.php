<?php
namespace CmsmastersElementor\Modules\Theme;

use CmsmastersElementor\Base\Base_Module;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * CMSMasters Elementor theme module.
 *
 * @since 1.0.0
 */
class Module extends Base_Module {

	/**
	 * Get name.
	 *
	 * Retrieve the module name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'cmsmasters-theme';
	}

	/**
	 * Get widgets.
	 *
	 * Retrieve the module widgets.
	 *
	 * @since 1.0.0
	 *
	 * @return array Module widgets.
	 */
	public function get_widgets() {
		return array(
			'Theme_Add_To_Cart',
			'Theme_Builders_Grid',
			'Theme_Builders_Slider',
			'Theme_Category_List',
			'Theme_Collection_Data',
			// 'Theme_Multiple_Search',
			'Theme_My_Account',
			'Theme_Product_Badges',
			'Theme_Product_Chooses',
			'Theme_Product_Tab_Additional_Info',
			// 'Theme_Wishlist_Smart_Button',
		);
	}

	public static function action_agp_qdt_itens() {
		do_action( 'yith_raq_updated' );

		$raq_content = YITH_Request_Quote()->raq_content;
		$products_count = 0;

		if ( $raq_content ) {
			$products_count = count( $raq_content );
		}

		echo $products_count;

		// echo YITH_Request_Quote()->get_raq_item_number();

		// wp_send_json($products_count);
	}

	/**
	 * Add actions initialization.
	 *
	 * Register actions for the theme module.
	 *
	 * @since 1.0.0
	 */
	protected function init_actions() {
		add_action( 'wp_ajax_action_agp_qdt_itens', array( $this, 'action_agp_qdt_itens' ) );
		add_action( 'wp_ajax_nopriv_action_agp_qdt_itens', array( $this, 'action_agp_qdt_itens' ) );
	}

}
