<?php
namespace StereoBankSpace\Admin\Installer\Importer;

use StereoBankSpace\Admin\Installer\Importer\Importer_Base;
use StereoBankSpace\Core\Utils\API_Requests;
use StereoBankSpace\Core\Utils\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * ACF handler class is responsible for different methods on importing "Advanced Custom Fields" plugin options.
 */
class ACF extends Importer_Base {

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
		return function_exists( 'acf_import_field_group' );
	}

	/**
	 * Get import status.
	 *
	 * @param string $default Import status by default, may be pending or done.
	 *
	 * @return string Import status.
	 */
	public static function get_import_status( $default = 'done' ) {
		return get_option( 'cmsmasters_stereo-bank_acf_import', $default );
	}

	/**
	 * Set import status.
	 *
	 * @param string $status Import status, may be pending or done.
	 */
	public static function set_import_status( $status = 'pending' ) {
		update_option( 'cmsmasters_stereo-bank_acf_import', $status );
	}

	/**
	 * Set exists options.
	 */
	protected function set_exists_options() {
		$this->options = get_option( 'cmsmasters_stereo-bank_' . Utils::get_demo() . '_acf', array() );
	}

	/**
	 * Set options from API.
	 */
	protected function set_api_options() {
		if ( ! empty( $this->options ) ) {
			return;
		}

		$response = API_Requests::post_request( 'get-acf-settings', array( 'demo' => Utils::get_demo() ) );
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

		foreach ( $this->options as $field_group ) {
			// Search database for existing field group.
			$import_post = acf_get_field_group_post( $field_group['key'] );

			if ( $import_post ) {
				$field_group['ID'] = $import_post->ID;
			}

			// Import field group.
			acf_import_field_group( $field_group );
		}
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

		$options = array();
		$field_groups = acf_get_field_groups();

		foreach ( $field_groups as $field_args ) {
			$field_group = acf_get_field_group( $field_args['key'] );

			if ( empty( $field_group ) ) {
				continue;
			}

			$field_group['fields'] = acf_get_fields( $field_group );

			$field_group = acf_prepare_field_group_for_export( $field_group );

			$options[] = $field_group;
		}

		if ( empty( $options ) ) {
			return;
		}

		$option_name = 'cmsmasters_stereo-bank_' . Utils::get_demo() . '_acf';

		if ( $first_install ) {
			$option_name = 'cmsmasters_stereo-bank_acf_backup';
		}

		update_option( $option_name, $options );
	}

}
