<?php
namespace StereoBankSpace\Core\Utils;

use StereoBankSpace\Core\Utils\File_Manager;
use StereoBankSpace\ThemeConfig\Theme_Config;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * API Requests handler class is responsible for different utility methods.
 */
class API_Requests {

	/**
	 * State of token regeneration.
	 */
	private static $regenerated = false;

	/**
	 * Check token status.
	 *
	 * @return bool true if token is valid and false if token is invalid.
	 */
	public static function check_token_status() {
		return get_option( 'cmsmasters_stereo-bank_token_status', 'invalid' ) === 'valid';
	}

	/**
	 * CMSMasters API GET request.
	 *
	 * @param string $route API route.
	 * @param array $args request args.
	 *
	 * @return object API response.
	 */
	public static function get_request( $route, $args = array() ) {
		$args = wp_parse_args( $args, array(
			'product_key' => Theme_Config::PRODUCT_KEY,
		) );

		$response = wp_remote_get(
			CMSMASTERS_API_ROUTES_URL . $route,
			array(
				'body' => $args,
			)
		);

		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			return new \WP_Error( 'bad_request', $response, $response_code );
		}

		return $response;
	}

	/**
	 * CMSMasters API POST request.
	 *
	 * @param string $route API route.
	 * @param array $args request args.
	 *
	 * @return object API response.
	 */
	public static function post_request( $route, $args = array() ) {
		$args = wp_parse_args( $args, array(
			'product_key' => Theme_Config::PRODUCT_KEY,
		) );

		$response = wp_remote_post(
			CMSMASTERS_API_ROUTES_URL . $route,
			array(
				'headers' => array(
					'Authorization' => 'Bearer ' . get_option( 'cmsmasters_stereo-bank_token', 'invalid' ),
				),
				'body' => $args,
			)
		);

		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			if ( ! self::$regenerated ) {
				self::regenerate_token();

				return self::post_request( $route, $args );
			} else {
				return new \WP_Error( 'bad_request', $response, $response_code );
			}
		}

		return $response;
	}

	/**
	 * Regenerate token.
	 *
	 * @param bool $die Run wp_send_json or return false if invalid data.
	 */
	public static function regenerate_token( $die = false ) {
		$token_data = self::get_token_data( $die );

		$response = wp_remote_post(
			CMSMASTERS_API_ROUTES_URL . 'regenerate-token',
			array(
				'body' => $token_data,
			)
		);

		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
		$response_code = wp_remote_retrieve_response_code( $response );

		self::$regenerated = true;

		if ( 200 !== $response_code ) {
			update_option( 'cmsmasters_stereo-bank_token_status', 'invalid' );

			if ( ! $die ) {
				return false;
			}

			wp_send_json_error( $response_body, $response_code );
		}

		update_option( 'cmsmasters_stereo-bank_token', $response_body['data']['token'] );
		update_option( 'cmsmasters_stereo-bank_token_status', 'valid' );

		do_action( 'cmsmasters_remove_temp_data' );
	}

	/**
	 * Generate token.
	 *
	 * @param string $purchase_code Purchase code.
	 */
	public static function generate_token( $purchase_code ) {
		$current_user = wp_get_current_user();

		$response = wp_remote_post(
			CMSMASTERS_API_ROUTES_URL . 'generate-token',
			array(
				'body' => array(
					'purchase_code' => $purchase_code,
					'domain' => home_url(),
					'email' => $current_user->user_email,
					'product_key' => Theme_Config::PRODUCT_KEY,
				),
				'timeout' => 30,
			)
		);

		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			wp_send_json(
				array(
					'success' => false,
					'message' => $response_body['data']['message'],
				)
			);
		}

		$token_data = array(
			'user' => $response_body['data']['user'],
			'purchase_code' => $purchase_code,
		);

		update_option( 'cmsmasters_stereo-bank_token_data', $token_data );
		update_option( 'cmsmasters_stereo-bank_token', $response_body['data']['token'] );

		File_Manager::write_file( wp_json_encode( $token_data ), 'token-data', 'token-data', 'json' );

		update_option( 'cmsmasters_stereo-bank_token_status', 'valid' );

		do_action( 'cmsmasters_remove_temp_data' );
	}

	/**
	 * Remove token.
	 */
	public static function remove_token() {
		do_action( 'cmsmasters_remove_temp_data' );

		$response = self::post_request( 'remove-token' );

		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			wp_send_json(
				array(
					'success' => false,
					'message' => $response_body['data']['message'],
				)
			);
		}

		self::delete_token_data();
	}

	/**
	 * Get token data.
	 *
	 * @param bool $die Send json or return empty array if invalid data.
	 *
	 * @return array Token data.
	 */
	public static function get_token_data( $die = true ) {
		$data = get_option( 'cmsmasters_stereo-bank_token_data', array() );

		if (
			is_array( $data ) &&
			isset( $data['user'] ) &&
			isset( $data['purchase_code'] )
		) {
			return $data;
		}

		$file = File_Manager::get_upload_path( 'token-data', 'token-data.json' );
		$data = File_Manager::get_file_contents( $file );
		$data = json_decode( $data, true );

		if (
			! is_array( $data ) ||
			! isset( $data['user'] ) ||
			! isset( $data['purchase_code'] )
		) {
			if ( ! $die ) {
				return array();
			}

			wp_send_json( array(
				'success' => false,
				'code' => 'invalid_token_data',
				'message' => esc_html__( 'Your token data is invalid.', 'stereo-bank' ),
			) );
		}

		update_option( 'cmsmasters_stereo-bank_token_data', $data );

		return $data;
	}

	/**
	 * Delete token data.
	 */
	protected static function delete_token_data() {
		File_Manager::delete_uploaded_dir( 'token-data' );
		delete_option( 'cmsmasters_stereo-bank_token_data' );
		delete_option( 'cmsmasters_stereo-bank_token' );
		update_option( 'cmsmasters_stereo-bank_token_status', 'invalid' );
	}

}
