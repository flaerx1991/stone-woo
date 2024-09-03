<?php
namespace StereoBankSpace\Admin\Installer\Importer;

use StereoBankSpace\Admin\Installer\Importer\Importer_Base;
use StereoBankSpace\Core\Utils\API_Requests;
use StereoBankSpace\Core\Utils\File_Manager;
use StereoBankSpace\Core\Utils\Utils;
use StereoBankSpace\ThemeConfig\Theme_Config;

use Elementor\Plugin as Elementor_Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Elementor Kit handler class is responsible for different methods on importing "Elementor" plugin kit.
 */
class Elementor_Kit extends Importer_Base {

	/**
	 * Options.
	 */
	protected $options = array();

	/**
	 * Activation status.
	 *
	 * @return bool Activation status.
	 */
	public static function activation_status() {
		return did_action( 'elementor/loaded' );
	}

	/**
	 * Actions on admin_init hook.
	 */
	public function admin_init_actions() {
		$this->create_kit();

		parent::admin_init_actions();
	}

	/**
	 * Create kit.
	 *
	 * Creates a kit if the kit was not created earlier.
	 */
	protected function create_kit() {
		if ( '' !== Utils::get_active_kit() ) {
			return;
		}

		Elementor_Plugin::$instance->kits_manager->get_active_kit();

		$kits_path = get_parent_theme_file_path( '/theme-config/defaults/main-kits.json' );
		$kits = File_Manager::get_file_contents( $kits_path );

		if ( '' !== $kits ) {
			$kits = json_decode( $kits, true );

			$this->set_kit_options( $kits );
		}
	}

	/**
	 * Get import status.
	 *
	 * @param string $default Import status by default, may be pending or done.
	 *
	 * @return string Import status.
	 */
	public static function get_import_status( $default = 'done' ) {
		return get_option( 'cmsmasters_stereo-bank_elementor_kit_import', $default );
	}

	/**
	 * Set import status.
	 *
	 * @param string $status Import status, may be pending or done.
	 */
	public static function set_import_status( $status = 'pending' ) {
		update_option( 'cmsmasters_stereo-bank_elementor_kit_import', $status );
	}

	/**
	 * Set exists options.
	 */
	protected function set_exists_options() {
		if ( 'demos' === Theme_Config::IMPORT_TYPE ) {
			$demo_key = Utils::get_demo();
		} else {
			$demo_key = Utils::get_demo_kit();
		}

		$this->options = get_option( "cmsmasters_stereo-bank_{$demo_key}_elementor_kit", array() );
	}

	/**
	 * Set options from API.
	 */
	protected function set_api_options() {
		if ( ! empty( $this->options ) ) {
			return;
		}

		if ( 'demos' === Theme_Config::IMPORT_TYPE ) {
			$demo_key = Utils::get_demo();
		} else {
			$demo_key = Utils::get_demo_kit();
		}

		$response = API_Requests::post_request( 'get-elementor-kits', array( 'demo' => $demo_key ) );
		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			return;
		}

		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
		$data = $response_body['data'];

		if ( '' === $data ) {
			return;
		}

		$data = json_decode( $data, true );

		if ( is_array( $data ) && ! empty( $data ) ) {
			$this->options = $data;
		}
	}

	/**
	 * Import options.
	 */
	protected function import_options() {
		if ( empty( $this->options ) ) {
			return;
		}

		$this->set_kit_options( $this->options );

		Elementor_Plugin::$instance->files_manager->clear_cache();
	}

	/**
	 * Set kit options.
	 *
	 * @param array $options Kit options.
	 */
	protected function set_kit_options( $options ) {
		unset( $options['site_name'], $options['site_description'], $options['site_favicon'] );

		Utils::set_kit_options( $options );
	}

	/**
	 * Backup current options.
	 *
	 * @param bool $first_install First install trigger, if need to backup customer option from previous theme.
	 */
	public static function set_backup_options( $first_install = false ) {
		if (
			! self::activation_status() ||
			'' === Utils::get_active_kit()
		) {
			return;
		}

		$options = Utils::get_kit_options();

		if ( empty( $options ) ) {
			return;
		}

		if ( 'demos' === Theme_Config::IMPORT_TYPE ) {
			$demo_key = Utils::get_demo();
		} else {
			$demo_key = Utils::get_demo_kit();
		}

		$option_name = "cmsmasters_stereo-bank_{$demo_key}_elementor_kit";

		if ( $first_install ) {
			$option_name = 'cmsmasters_stereo-bank_elementor_kit_backup';
		}

		update_option( $option_name, $options );
	}

}
