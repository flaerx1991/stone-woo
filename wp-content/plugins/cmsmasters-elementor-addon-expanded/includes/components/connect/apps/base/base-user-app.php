<?php
namespace CmsmastersElementor\Components\Connect\Apps\Base;

use CmsmastersElementor\Components\Connect\Apps\Base\Base_App;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


abstract class Base_User_App extends Base_App {

	/**
	 * @since 1.0.0
	 */
	protected function update_settings() {
		update_user_option( get_current_user_id(), $this->get_option_name(), $this->data );
	}

	/**
	 * @since 1.0.0
	 */
	protected function init_data() {
		$this->data = get_user_option( $this->get_option_name() );

		if ( ! $this->data ) {
			$this->data = array();
		}
	}

}
