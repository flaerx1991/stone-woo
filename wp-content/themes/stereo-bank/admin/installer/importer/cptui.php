<?php
namespace StereoBankSpace\Admin\Installer\Importer;

use StereoBankSpace\Admin\Installer\Importer\Importer_Base;
use StereoBankSpace\Core\Utils\API_Requests;
use StereoBankSpace\Core\Utils\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * CPTUI handler class is responsible for different methods on importing "Custom Post Type UI" plugin options.
 */
class CPTUI extends Importer_Base {

	/**
	 * Post types options.
	 */
	protected $post_types_options = array();

	/**
	 * Taxonomies options.
	 */
	protected $tax_options = array();

	/**
	 * Activation status.
	 *
	 * @return bool Activation status.
	 */
	public static function activation_status() {
		return function_exists( 'cptui_import_types_taxes_settings' );
	}

	/**
	 * Get import status.
	 *
	 * @param string $default Import status by default, may be pending or done.
	 *
	 * @return string Import status.
	 */
	public static function get_import_status( $default = 'done' ) {
		return get_option( 'cmsmasters_stereo-bank_cptui_import', $default );
	}

	/**
	 * Set import status.
	 *
	 * @param string $status Import status, may be pending or done.
	 */
	public static function set_import_status( $status = 'pending' ) {
		update_option( 'cmsmasters_stereo-bank_cptui_import', $status );
	}

	/**
	 * Set exists options.
	 */
	protected function set_exists_options() {
		$demo = Utils::get_demo();

		$this->post_types_options = get_option( "cmsmasters_stereo-bank_{$demo}_cptui_post_types", array() );
		$this->tax_options = get_option( "cmsmasters_stereo-bank_{$demo}_cptui_taxonomies", array() );
	}

	/**
	 * Set options from API.
	 */
	protected function set_api_options() {
		if ( ! empty( $this->post_types_options ) || ! empty( $this->tax_options ) ) {
			return;
		}

		$response = API_Requests::post_request( 'get-cptui-settings', array( 'demo' => Utils::get_demo() ) );
		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			return;
		}

		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
		$data = $response_body['data'];

		if ( isset( $data['post_import'] ) && '' !== $data['post_import'] ) {
			$post_types_data = stripslashes_deep( trim( $data['post_import'] ) );
			$post_types_data = json_decode( $post_types_data, true );

			if ( is_array( $post_types_data ) ) {
				$this->post_types_options = $post_types_data;
			}
		}

		if ( isset( $data['tax_import'] ) && '' !== $data['tax_import'] ) {
			$tax_data = stripslashes_deep( trim( $data['tax_import'] ) );
			$tax_data = json_decode( $tax_data, true );

			if ( is_array( $tax_data ) ) {
				$this->tax_options = $tax_data;
			}
		}
	}

	/**
	 * Import options.
	 */
	protected function import_options() {
		if ( empty( $this->post_types_options ) && empty( $this->tax_options ) ) {
			return;
		}

		update_option( 'cptui_post_types', $this->post_types_options );
		update_option( 'cptui_taxonomies', $this->tax_options );
		set_transient( 'cptui_flush_rewrite_rules', 'true', 5 * 60 );
	}

	/**
	 * Backup current options.
	 *
	 * @param bool $first_install First install trigger, if need to backup customer option from previous theme.
	 */
	public static function set_backup_options( $first_install = false ) {
		if ( ! self::activation_status() ) {
			return;
		}

		$post_types_options = get_option( 'cptui_post_types', array() );
		$tax_options = get_option( 'cptui_taxonomies', array() );

		$demo = Utils::get_demo();
		$post_types_option_name = "cmsmasters_stereo-bank_{$demo}_cptui_post_types";
		$tax_option_name = "cmsmasters_stereo-bank_{$demo}_cptui_taxonomies";

		if ( $first_install ) {
			$post_types_option_name = 'cmsmasters_stereo-bank_cptui_post_types_backup';
			$tax_option_name = 'cmsmasters_stereo-bank_cptui_taxonomies_backup';
		}

		update_option( $post_types_option_name, $post_types_options );
		update_option( $tax_option_name, $tax_options );
	}

}
