<?php
namespace StereoBankSpace\Admin\Installer\Importer;

use StereoBankSpace\Core\Utils\API_Requests;
use StereoBankSpace\Core\Utils\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WPForms handler class is responsible for different methods on importing "WPForms" plugin forms.
 */
class WPForms {

	/**
	 * WPForms Import constructor.
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
		return function_exists( 'wpforms' );
	}

	/**
	 * Get import status.
	 *
	 * @param string $default Import status by default, may be pending or done.
	 *
	 * @return string Import status.
	 */
	public static function get_import_status( $default = 'done' ) {
		return get_option( 'cmsmasters_stereo-bank_' . Utils::get_demo() . '_wpforms_import', $default );
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

		update_option( 'cmsmasters_stereo-bank_' . Utils::get_demo() . '_wpforms_import', $status );
	}

	/**
	 * Actions on admin_init hook.
	 */
	public function admin_init_actions() {
		if ( 'pending' !== self::get_import_status( 'done' ) ) {
			return;
		}

		$this->import_forms();

		self::set_import_status( 'done' );
	}

	/**
	 * Import forms.
	 */
	protected function import_forms() {
		$response = API_Requests::post_request( 'get-wpforms', array( 'demo' => Utils::get_demo() ) );
		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			return;
		}

		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
		$data = $response_body['data'];

		if ( empty( $data ) ) {
			return;
		}

		$forms = json_decode( $data, true );

		if ( empty( $forms ) || ! is_array( $forms ) ) {
			return;
		}

		foreach ( $forms as $form ) {
			$title  = ! empty( $form['settings']['form_title'] ) ? $form['settings']['form_title'] : '';
			$desc   = ! empty( $form['settings']['form_desc'] ) ? $form['settings']['form_desc'] : '';

			$new_id = wp_insert_post( array(
				'post_title' => $title,
				'post_status' => 'publish',
				'post_type' => 'wpforms',
				'post_excerpt' => $desc,
			) );

			if ( $new_id ) {
				$demo = Utils::get_demo();

				$forms_ids = get_transient( "cmsmasters_stereo-bank_{$demo}_wpforms_import_forms_ids" );

				if ( false === $forms_ids ) {
					$forms_ids = array();
				}

				$forms_ids[ $form['id'] ] = $new_id;

				set_transient( "cmsmasters_stereo-bank_{$demo}_wpforms_import_forms_ids", $forms_ids, HOUR_IN_SECONDS );

				$form['id'] = $new_id;

				wp_update_post( array(
					'ID' => $new_id,
					'post_content' => wpforms_encode( $form ),
				) );
			}
		}
	}

	/**
	 * Regenerate forms ids in elementor widgets.
	 */
	public static function regenerate_content_forms_ids( $element, $forms_ids = array() ) {
		if (
			empty( $element['widgetType'] ) ||
			'cmsmasters-wp-form' !== $element['widgetType'] ||
			! isset( $element['settings']['form_list'] )
		) {
			return $element;
		}

		$old_id = $element['settings']['form_list'];

		if ( isset( $forms_ids[ $old_id ] ) ) {
			$element['settings']['form_list'] = strval( $forms_ids[ $old_id ] );
		}

		return $element;
	}

}
