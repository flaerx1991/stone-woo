<?php
namespace StereoBankSpace\Admin\Installer\Importer;

use StereoBankSpace\Core\Utils\API_Requests;
use StereoBankSpace\Core\Utils\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * GiveWP handler class is responsible for different methods on importing "GiveWP" plugin.
 */
class Give_WP {

	/**
	 * Options.
	 */
	protected $options = array();

	/**
	 * GiveWP Import constructor.
	 */
	public function __construct() {
		if ( self::activation_status() && API_Requests::check_token_status() ) {
			add_action( 'admin_init', array( $this, 'admin_init_actions' ) );

			add_action( 'import_end', array( $this, 'import_form_meta' ), 9 );
		}

		add_action( 'cmsmasters_set_backup_options', array( $this, 'set_backup_options' ) );

		add_action( 'cmsmasters_set_import_status', array( $this, 'set_import_status' ) );
	}

	/**
	 * Activation status.
	 *
	 * @return bool Activation status.
	 */
	public static function activation_status() {
		return class_exists( 'Give' );
	}

	/**
	 * Actions on admin_init hook.
	 */
	public function admin_init_actions() {
		if ( 'pending' !== static::get_import_status( 'done' ) ) {
			return;
		}

		$this->set_exists_options();

		$this->set_api_options();

		$this->import_options();

		static::set_import_status( 'done' );
	}

	/**
	 * Get import status.
	 *
	 * @param string $default Import status by default, may be pending or done.
	 *
	 * @return string Import status.
	 */
	public static function get_import_status( $default = 'done' ) {
		return get_option( 'cmsmasters_stereo-bank_' . Utils::get_demo() . '_givewp_import', $default );
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

		update_option( 'cmsmasters_stereo-bank_' . Utils::get_demo() . '_givewp_import', $status );
	}

	/**
	 * Set exists options.
	 */
	protected function set_exists_options() {
		$this->options = get_option( 'cmsmasters_stereo-bank_' . Utils::get_demo() . '_givewp', array() );
	}

	/**
	 * Set options from API.
	 */
	protected function set_api_options() {
		if ( ! empty( $this->options ) ) {
			return;
		}

		$response = API_Requests::post_request( 'get-givewp-data', array(
			'demo' => Utils::get_demo(),
			'data_type' => 'settings',
		) );
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

		update_option( 'give_settings', $this->options );
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

		$options = get_option( 'give_settings', array() );

		$option_name = 'cmsmasters_stereo-bank_' . Utils::get_demo() . '_givewp';

		if ( $first_install ) {
			$option_name = 'cmsmasters_stereo-bank_givewp_backup';
		}

		update_option( $option_name, $options );
	}

	/**
	 * Import form meta.
	 */
	public function import_form_meta() {
		$demo = Utils::get_demo();

		$response = API_Requests::post_request( 'get-givewp-data', array(
			'demo' => Utils::get_demo(),
			'data_type' => 'form_meta',
		) );
		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			return;
		}

		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
		$data = $response_body['data'];

		if ( '' === $data ) {
			return;
		}

		$form_meta = json_decode( $data, true );

		if ( ! is_array( $form_meta ) || empty( $form_meta ) ) {
			return;
		}

		$displayed_ids = get_transient( "cmsmasters_stereo-bank_{$demo}_import_displayed_ids" );

		if ( empty( $displayed_ids['post_id']['give_forms'] ) ) {
			return;
		}

		$form_ids = $displayed_ids['post_id']['give_forms'];

		$form_meta_result = array();

		foreach ( $form_ids as $old_id => $new_id ) {
			if ( empty( $form_meta[ $old_id ] ) ) {
				continue;
			}

			$form_meta_result[ $new_id ] = $form_meta[ $old_id ];
		}

		if ( empty( $form_meta_result ) ) {
			return;
		}

		$query = new \WP_Query( array(
			'posts_per_page' => -1,
			'post_type' => 'give_forms',
		) );

		if ( ! $query->have_posts() ) {
			return;
		}

		foreach ( $query->posts as $post ) {
			if (
				empty( $form_meta_result[ $post->ID ] ) ||
				! is_array( $form_meta_result[ $post->ID ] )
			) {
				continue;
			}

			foreach ( $form_meta_result[ $post->ID ] as $meta_key => $meta_value ) {
				Give()->form_meta->update_meta( $post->ID, $meta_key, $meta_value );
			}
		}
	}

}
