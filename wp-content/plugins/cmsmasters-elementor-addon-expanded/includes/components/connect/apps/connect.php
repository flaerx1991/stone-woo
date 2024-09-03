<?php
namespace CmsmastersElementor\Components\Connect\Apps;

use CmsmastersElementor\Components\Connect\Apps\Common_App;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Connect extends Common_App {

	/**
	 * @since 1.0.0
	 */
	protected function get_slug() {
		return 'connect';
	}

	/**
	 * @since 1.0.0
	 */
	public function get_title() {
		return __( 'Connect', 'cmsmasters-elementor' );
	}

	/**
	 * @since 1.0.0
	 */
	public function render_admin_widget() {}

}
