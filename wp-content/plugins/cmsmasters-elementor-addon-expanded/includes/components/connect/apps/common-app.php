<?php
namespace CmsmastersElementor\Components\Connect\Apps;

use CmsmastersElementor\Components\Connect\Apps\Base\Base_User_App;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


abstract class Common_App extends Base_User_App {

	protected static $common_data = null;

	/**
	 * @since 1.0.0
	 */
	public static function get_option_name() {
		return static::OPTION_NAME_PREFIX . 'common_data';
	}

	/**
	 * @since 1.0.0
	 */
	protected function init_data() {
		if ( is_null( self::$common_data ) ) {
			self::$common_data = get_user_option( static::get_option_name() );

			if ( ! self::$common_data ) {
				self::$common_data = array();
			};
		}

		$this->data = & self::$common_data;
	}

}
