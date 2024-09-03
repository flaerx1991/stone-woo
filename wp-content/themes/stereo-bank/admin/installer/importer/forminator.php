<?php
namespace StereoBankSpace\Admin\Installer\Importer;

use StereoBankSpace\Core\Utils\API_Requests;
use StereoBankSpace\Core\Utils\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Forminator handler class is responsible for different methods on importing "Forminator" plugin forms.
 */
class Forminator {

	/**
	 * Forminator Import constructor.
	 */
	public function __construct() {
		add_action( 'cmsmasters_set_import_status', array( get_called_class(), 'set_import_status' ) );

		if ( self::activation_status() && API_Requests::check_token_status() ) {
			$this->remove_from_wp_export();

			add_action( 'admin_init', array( $this, 'admin_init_actions' ) );
		}

		add_filter( 'forminator_form_model_to_exportable_data', array( $this, 'filter_export_data' ), 10, 3 );
	}

	/**
	 * Remove custom post types from WP export.
	 */
	private function remove_from_wp_export() {
		global $wp_post_types;

		$wp_post_types['forminator_forms']->can_export = false;
		$wp_post_types['forminator_polls']->can_export = false;
		$wp_post_types['forminator_quizzes']->can_export = false;
	}

	/**
	 * Activation status.
	 *
	 * @return bool Activation status.
	 */
	public static function activation_status() {
		return class_exists( 'Forminator' );
	}

	/**
	 * Get import status.
	 *
	 * @param string $default Import status by default, may be pending or done.
	 *
	 * @return string Import status.
	 */
	public static function get_import_status( $default = 'done' ) {
		return get_option( 'cmsmasters_stereo-bank_' . Utils::get_demo() . '_forminator_import', $default );
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

		update_option( 'cmsmasters_stereo-bank_' . Utils::get_demo() . '_forminator_import', $status );
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
		$response = API_Requests::post_request( 'get-forminator', array( 'demo' => Utils::get_demo() ) );
		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			return;
		}

		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
		$data = $response_body['data'];

		if ( empty( $data ) || ! is_array( $data ) ) {
			return;
		}

		foreach ( $data as $type => $forms ) {
			if ( empty( $forms ) ) {
				continue;
			}

			$class_type = ucfirst( $type );
			$class_name = "Forminator_{$class_type}_Model";

			foreach ( $forms as $form ) {
				$original_form = json_decode( $form, true );
				$new_form = $class_name::create_from_import_data( $original_form );
				$new_id = $new_form->id;

				if ( $new_id && isset( $original_form['post_id'] ) ) {
					$demo = Utils::get_demo();
					$original_id = $original_form['post_id'];

					$forms_ids = get_transient( "cmsmasters_stereo-bank_{$demo}_forminator_import_forms_ids" );

					if ( false === $forms_ids ) {
						$forms_ids = array();
					}

					$forms_ids[ $original_id ] = $new_id;

					set_transient( "cmsmasters_stereo-bank_{$demo}_forminator_import_forms_ids", $forms_ids, HOUR_IN_SECONDS );
				}
			}
		}
	}

	/**
	 * Regenerate forms ids in elementor widgets.
	 */
	public static function regenerate_content_forms_ids( $element, $forms_ids = array() ) {
		if (
			empty( $element['widgetType'] ) ||
			'cmsmasters-forminator' !== $element['widgetType'] ||
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

	/**
	 * Filter export data.
	 *
	 * @param array $exportable_data Exportable data.
	 * @param string $module_type Module type.
	 * @param int $model_id Model ID.
	 *
	 * @return array Filtered exportable data.
	 */
	public function filter_export_data( $exportable_data, $module_type, $model_id ) {
		$exportable_data['post_id'] = $model_id;

		return $exportable_data;
	}

}
