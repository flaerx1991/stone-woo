<?php
namespace StereoBankSpace\Admin\Options\Pages\Base;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Handler class is responsible for different methods on theme options pages.
 */
class Base_Page {

	/**
	 * Get page title.
	 */
	public static function get_page_title() {
		return '';
	}

	/**
	 * Get menu title.
	 */
	public static function get_menu_title() {
		return '';
	}

}
