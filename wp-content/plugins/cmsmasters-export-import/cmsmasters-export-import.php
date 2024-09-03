<?php
/**
 * Plugin Name: CMSMasters Export/Import
 * Description: CMSMasters Export/Import uses for export and import cmsmasters themes options.
 * Plugin URI: http://cmsmasters.net/
 * Author: CMSMasters
 * Version: 1.0.0
 * Author URI: http://cmsmasters.net/
 *
 * Text Domain: cmsmasters-ei
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'CMSMASTERS_EI_VERSION', '1.0.0' );
define( 'CMSMASTERS_EI_MIN_PHP_VER', '5.4' );

define( 'CMSMASTERS_EI__FILE__', __FILE__ );
define( 'CMSMASTERS_EI_PLUGIN_BASE', plugin_basename( CMSMASTERS_EI__FILE__ ) );
define( 'CMSMASTERS_EI_PATH', plugin_dir_path( CMSMASTERS_EI__FILE__ ) );
define( 'CMSMASTERS_EI_URL', plugins_url( '/', CMSMASTERS_EI__FILE__ ) );

define( 'CMSMASTERS_EI_CORE_PATH', CMSMASTERS_EI_PATH . 'core/' );

/**
 * CMSMasters EI initial class.
 *
 * The plugin file that checks all the plugin requirements and
 * run main plugin class.
 *
 * @since 1.0.0
 */
final class Cmsmasters_EI {

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
	 * Export/Import initial class constructor.
	 *
	 * Initializing the Export/Import initial file class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->register_autoloader();

		add_action( 'init', array( $this, 'i18n' ) );
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Register autoloader.
	 *
	 * Autoloader loads all the plugin files.
	 *
	 * @since 1.0.0
	 */
	private function register_autoloader() {
		require_once CMSMASTERS_EI_CORE_PATH . 'autoloader.php';

		CmsmastersEI\Autoloader::run();
	}

	/**
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 */
	public function i18n() {
		load_plugin_textdomain( 'cmsmasters-ei' );
	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after other plugins are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @return void Or require main Plugin class
	 */
	public function init() {
		// Check for required PHP version
		if ( version_compare( PHP_VERSION, CMSMASTERS_EI_MIN_PHP_VER, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );

			return;
		}

		$this->register_plugin();
	}

	/**
	 * Register plugin.
	 *
	 * Initialize main plugin handler class.
	 *
	 * @since 1.0.0
	 */
	private function register_plugin() {
		/**
		 * The main handler class.
		 */
		require CMSMASTERS_EI_CORE_PATH . 'plugin.php';

		CmsmastersEI\Plugin::instance();
	}

	/**
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		/* translators: 'minimum PHP version' admin notice. 1: Plugin name - CMSMasters EI, 2: PHP, 3: Required PHP version */
		$message = sprintf( esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'cmsmasters-ei' ),
			'<strong>' . esc_html__( 'CMSMasters EI', 'cmsmasters-ei' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'cmsmasters-ei' ) . '</strong>',
			CMSMASTERS_EI_MIN_PHP_VER
		);

		printf( '<div class="notice notice-warning is-dismissible">
			<p>%s</p>
		</div>', esc_html( $message ) );
	}

}

new Cmsmasters_EI();
