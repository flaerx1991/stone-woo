<?php
namespace StereoBankSpace\Admin\Installer\Importer;

use StereoBankSpace\Core\Utils\API_Requests;
use StereoBankSpace\Core\Utils\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Revslider handler class is responsible for different methods on importing "Slider Revolution" slides.
 */
class Revslider {

	/**
	 * Revslider Import constructor.
	 */
	public function __construct() {
		add_action( 'cmsmasters_set_import_status', array( get_called_class(), 'set_import_status' ) );

		if ( self::activation_status() && API_Requests::check_token_status() ) {
			add_action( 'admin_init', array( $this, 'admin_init_actions' ) );
		}
	}

	/**
	 * Activation status.
	 *
	 * @return bool Activation status.
	 */
	public static function activation_status() {
		return ( class_exists( 'RevSliderSlider' ) && class_exists( 'RevSliderSliderImport' ) );
	}

	/**
	 * Get import status.
	 *
	 * @param string $default Import status by default, may be pending or done.
	 *
	 * @return string Import status.
	 */
	public static function get_import_status( $default = 'done' ) {
		return get_option( 'cmsmasters_stereo-bank_' . Utils::get_demo() . '_revslider_import', $default );
	}

	/**
	 * Set import status.
	 *
	 * @param string $status Import status, may be pending or done.
	 */
	public static function set_import_status( $status = 'pending' ) {
		if ( 'done' === self::get_import_status( false ) ) {
			return;
		}

		update_option( 'cmsmasters_stereo-bank_' . Utils::get_demo() . '_revslider_import', $status );
	}

	/**
	 * Actions on admin_init hook.
	 */
	public function admin_init_actions() {
		if ( 'pending' !== self::get_import_status( 'done' ) ) {
			return;
		}

		$this->import_slides();

		self::set_import_status( 'done' );
	}

	/**
	 * Import slides.
	 */
	protected function import_slides() {
		$response = API_Requests::post_request( 'get-revslider', array( 'demo' => Utils::get_demo() ) );
		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			return;
		}

		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
		$data = $response_body['data'];

		if ( empty( $data ) || ! is_array( $data ) ) {
			return;
		}

		$revslider = new RevSliderSliderImport();

		foreach ( $data as $file_url ) {
			$revslider->import_slider(true, $file_url, false);
		}
	}

}
