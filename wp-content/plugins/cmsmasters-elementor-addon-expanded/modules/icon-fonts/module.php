<?php
namespace CmsmastersElementor\Modules\IconFonts;

use CmsmastersElementor\Base\Base_Module;
use CmsmastersElementor\Modules\IconFonts\Types\Local;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Module extends Base_Module {

	const MODULE_DIR = __DIR__;
	const MODULE_NAMESPACE = __NAMESPACE__;

	public static $namespace = '';

	public function get_name() {
		return 'icon-fonts';
	}

	public function __construct() {
		$this->add_component( 'local', new Local() );

		parent::__construct();

		/**
		 * Addon icon fonts module loaded.
		 *
		 * Fires after the icons font module was fully loaded and instantiated.
		 *
		 * @since 1.0.0
		 *
		 * @param Module $this An instance of icon fonts module.
		 */
		do_action( 'cmsmasters_elementor/icon_fonts/loaded', $this );
	}

	protected function init_filters() {
		// Admin
		add_filter( 'cmsmasters_elementor/admin/settings', array( $this, 'filter_admin_settings' ) );
	}

	/**
	 * Get local icons.
	 *
	 * Retrieve the local icons module component.
	 *
	 * @return Local
	 */
	public function get_local_icons() {
		return $this->get_component( 'local' );
	}

	public function filter_admin_settings( $settings ) {
		$settings = array_replace_recursive( $settings, array(
			'i18n' => array(
				'iconsUploadEmptyNotice' => __( 'You need to upload an icons set to publish.', 'cmsmasters-elementor' ),
			),
		) );

		return $settings;
	}

}
