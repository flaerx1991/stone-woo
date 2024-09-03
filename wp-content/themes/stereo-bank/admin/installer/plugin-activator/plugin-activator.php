<?php
namespace StereoBankSpace\Admin\Installer\Plugin_Activator;

use StereoBankSpace\Core\Utils\API_Requests;
use StereoBankSpace\Core\Utils\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Plugin Activator.
 *
 * Main class for plugin activator.
 */
class Plugin_Activator {

	/**
	 * Plugin_Activator constructor.
	 */
	public function __construct() {
		// Include the TGM_Plugin_Activation class.
		require_once get_template_directory() . '/admin/installer/plugin-activator/class-tgm-plugin-activation.php';

		$this->regenerate_plugins_list();

		add_action( 'tgmpa_register', array( $this, 'tgmpa_run' ) );

		add_action( 'cmsmasters_remove_temp_data', array( $this, 'remove_plugins_list' ) );

		add_filter( 'acf/settings/show_updates', '__return_false', 100 );
	}

	/**
	 * Regenerate plugins list.
	 */
	public function regenerate_plugins_list() {
		global $pagenow;

		if (
			( 'themes.php' === $pagenow && isset( $_GET['page'] ) && 'tgmpa-install-plugins' === $_GET['page'] ) ||
			'plugins.php' === $pagenow ||
			'update-core.php' === $pagenow
		) {
			$this->set_plugins_list();
		}
	}

	/**
	 * Run TGMPA.
	 */
	public function tgmpa_run() {
		$plugins_list = $this->get_plugins_list();

		if ( false === $plugins_list ) {
			$plugins_list = $this->set_plugins_list();
		}

		$config = $this->get_config();

		tgmpa( $plugins_list, $config );
	}

	/**
	 * Get plugins list.
	 */
	public function get_plugins_list() {
		return get_transient( 'cmsmasters_plugins_list' );
	}

	/**
	 * Remove plugins list.
	 */
	public function remove_plugins_list() {
		delete_transient( 'cmsmasters_plugins_list' );
	}

	/**
	 * Set plugins list.
	 */
	public function set_plugins_list() {
		$plugins_list = $this->get_api_plugins();

		set_transient( 'cmsmasters_plugins_list', $plugins_list, DAY_IN_SECONDS );

		return $plugins_list;
	}

	/**
	 * Get plugins list from API.
	 *
	 * @return array Plugins list.
	 */
	private function get_api_plugins() {
		if ( ! API_Requests::check_token_status() ) {
			API_Requests::regenerate_token();
		}

		$response = API_Requests::post_request( 'get-plugins-list', array( 'demo' => Utils::get_demo() ) );

		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			return array();
		}

		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! isset( $response_body['data'] ) || ! is_array( $response_body['data'] ) ) {
			return array();
		}

		return $response_body['data'];
	}

	/**
	 * Get configuration settings list.
	 *
	 * @return array Configuration settings list.
	 */
	private function get_config() {
		return array(
			'id' => 'stereo-bank', // Unique ID for hashing notices for multiple instances of TGMPA.
			'menu' => 'tgmpa-install-plugins', // Menu slug.
			'has_notices' => true, // Show admin notices or not.
			'dismissable' => true, // If false, a user cannot dismiss the nag message.
			'is_automatic' => false, // Automatically activate plugins after installation or not.
		);
	}

}
