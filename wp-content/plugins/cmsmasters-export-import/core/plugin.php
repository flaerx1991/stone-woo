<?php
namespace CmsmastersEI;

use CmsmastersEI\Core\Traits\Singleton;
use CmsmastersEI\Modules\Export;
use CmsmastersEI\Modules\Import;
use CmsmastersEI\Modules\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * CMSMasters EI plugin.
 *
 * The main plugin handler class is responsible for initializing methods for export and import options.
 * The class registers all the components required for the plugin.
 *
 * @since 1.0.0
 */
final class Plugin {

	/**
	 * Instantiate singleton trait.
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @var array $_instances Array with instance of the class.
	 * @method object instance() Single instance of the class.
	 */
	use Singleton;

	/**
	 * Disable class cloning and throw an error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object. Therefore, we don't want the object to be cloned.
	 * That's why cloning instances of the class is forbidden.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Something went wrong.', 'cmsmasters-ei' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * Unserializing instances of the class is forbidden.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Something went wrong.', 'cmsmasters-ei' ), '1.0.0' );
	}

	/**
	 * Main class constructor.
	 *
	 * Constructs the EI main Plugin class.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'controls_enqueue_assets' ) );

		add_action( 'customize_register', array( $this, 'register_controls' ) );

		if ( current_user_can( 'edit_theme_options' ) ) {
			new Export();

			new Import();
		}
	}

	/**
	 * Enqueue assets for the controls.
	 *
	 * @since 1.0.0
	 */
	public function controls_enqueue_assets() {
		// Styles
		wp_enqueue_style( 'cmsmasters-ei-customizer', CMSMASTERS_EI_URL . 'assets/css/customizer.css', array(), CMSMASTERS_EI_VERSION );

		// Scripts
		wp_enqueue_script( 'cmsmasters-ei-customizer', CMSMASTERS_EI_URL . 'assets/js/customizer.js', array( 'jquery' ), CMSMASTERS_EI_VERSION, true );

		// Localize
		wp_localize_script( 'cmsmasters-ei-customizer', 'ei_params', array(
			'customizer_url' => admin_url( 'customize.php' ),
			'export_nonce' => wp_create_nonce( 'cmsmasters-ei-exporting' ),
			'empty_import' => esc_html__( 'Please choose a file to import.', 'cmsmasters-ei' ),
		));
	}

	/**
	 * Registers customizer controls.
	 *
	 * @since 1.0.0
	 *
	 * @param object $wp_customize An instance of WP_Customize_Manager.
	 */
	public function register_controls( $wp_customize ) {
		// Add the export/import section.
		$wp_customize->add_section( 'cmsmasters-ei-section', array(
			'title'	   => __( 'CMSMasters Export/Import', 'cmsmasters-ei' ),
			'priority' => 10000000
		));

		// Add the export/import setting.
		$wp_customize->add_setting( 'cmsmasters-ei-setting', array(
			'default' => '',
			'type'	  => 'none'
		));

		// Add the export/import control.
		$wp_customize->add_control( new Controls(
			$wp_customize,
			'cmsmasters-ei-setting',
			array(
				'section'	=> 'cmsmasters-ei-section',
				'priority'	=> 1
			)
		));
	}

}
