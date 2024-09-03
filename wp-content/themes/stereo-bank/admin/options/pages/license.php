<?php
namespace StereoBankSpace\Admin\Options\Pages;

use StereoBankSpace\Core\Utils\API_Requests;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * License handler class is responsible for different methods on license theme options page.
 */
class License extends Base\Base_Page {

	/**
	 * Page constructor.
	 */
	public function __construct() {
		if ( ! API_Requests::check_token_status() ) {
			API_Requests::regenerate_token();
		}

		add_action( 'wp_ajax_cmsmasters_activate_license', array( $this, 'ajax_activate_license' ) );

		add_action( 'wp_ajax_cmsmasters_deactivate_license', array( $this, 'ajax_deactivate_license' ) );

		add_action( 'admin_notices', array( $this, 'page_info_notice' ), 30 );
	}

	/**
	 * Get page title.
	 */
	public static function get_page_title() {
		return esc_attr__( 'License', 'stereo-bank' );
	}

	/**
	 * Get menu title.
	 */
	public static function get_menu_title() {
		return esc_attr__( 'License', 'stereo-bank' );
	}

	/**
	 * Render page content.
	 */
	public function render_content() {
		$token_data = API_Requests::get_token_data( false );
		$purchase_code = '';

		if ( is_array( $token_data ) && isset( $token_data['purchase_code'] ) ) {
			$purchase_code = $token_data['purchase_code'];

			$replacement = '';
			$visible_count = 5;
			$length = strlen( $purchase_code ) - $visible_count * 2;

			for ( $i = 0; $i < $length; $i++ ) {
				$replacement .= '*';
			}

			$purchase_code = substr_replace( $purchase_code, $replacement, $visible_count, -$visible_count );
		}

		echo '<div class="cmsmasters-options-message' . ( '' === $purchase_code ? ' cmsmasters-error' : ' cmsmasters-success' ) . '">';

		if ( '' === $purchase_code ) {
			echo '<p><strong>' . esc_html__( 'Your license is not activated.', 'stereo-bank' ) . '</strong></p>' .
			'<p><strong>' . esc_html__( 'Enter your purchase code to activate the license.', 'stereo-bank' ) . '</strong></p>';
		} else {
			echo '<p><strong>' . esc_html__( 'Your license is activated! Remote updates and theme support are enabled.', 'stereo-bank' ) . '</strong></p>';
		}

		echo '</div>';

		echo '<table class="form-table">' .
			'<tbody>';

		if ( '' === $purchase_code ) {
			echo '<tr class="nohtml">' .
				'<th scope="row">' .
					'<label>' . esc_html__( 'Activate License', 'stereo-bank' ) . '</label>' .
				'</th>' .
				'<td>' .
					'<div class="cmsmasters-options-field">' .
						'<input type="text" class="regular-text" />' .
						'<button type="button" class="button cmsmasters-button-spinner" data-license="activate">' . esc_html__( 'Activate', 'stereo-bank' ) . '</button>' .
						'<span class="cmsmasters-notice"></span>' .
					'</div>' .
				'</td>' .
			'</tr>';
		} else {
			echo '<tr class="nohtml">' .
				'<th scope="row">' .
					'<label>' . esc_html__( 'Deactivate License', 'stereo-bank' ) . '</label>' .
				'</th>' .
				'<td>' .
					'<div class="cmsmasters-options-field">' .
						'<input type="text" class="regular-text" value="' . esc_attr( $purchase_code ) . '" disabled />' .
						'<button type="button" class="button cmsmasters-button-spinner" data-license="deactivate">' . esc_html__( 'Deactivate', 'stereo-bank' ) . '</button>' .
						'<span class="cmsmasters-notice"></span>' .
					'</div>' .
				'</td>' .
			'</tr>';
		}

			echo '</tbody>' .
		'</table>';
	}

	/**
	 * Activate theme license.
	 */
	public function ajax_activate_license() {
		if ( ! check_ajax_referer( 'cmsmasters_options_nonce', 'nonce' ) ) {
			wp_send_json( array(
				'success' => false,
				'code' => 'invalid_nonce',
				'message' => esc_html__( 'Yikes! The theme activation failed. Please try again.', 'stereo-bank' ),
			) );
		}

		if ( empty( $_POST['license_key'] ) ) {
			wp_send_json( array(
				'success' => false,
				'code' => 'empty_license_key',
				'message' => esc_html__( 'Please add your license key before attempting to activate one.', 'stereo-bank' ),
			) );
		}

		API_Requests::generate_token( $_POST['license_key'] );

		wp_send_json( array(
			'success' => true,
			'message' => esc_html__( 'Your license is activated! Remote updates and theme support are enabled.', 'stereo-bank' ),
		) );
	}

	/**
	 * Deactivate theme license.
	 */
	public function ajax_deactivate_license() {
		if ( ! check_ajax_referer( 'cmsmasters_options_nonce', 'nonce' ) ) {
			wp_send_json( array(
				'success' => false,
				'code' => 'invalid_nonce',
				'message' => esc_html__( 'Yikes! The theme deactivation failed. Please try again.', 'stereo-bank' ),
			) );
		}

		API_Requests::remove_token();

		wp_send_json( array(
			'success' => true,
			'message' => esc_html__( 'Your license is deactivated!', 'stereo-bank' ),
		) );
	}

	/**
	 * Page info notice.
	 */
	public function page_info_notice() {
		echo '<div class="notice notice-info">' .
			'<p><strong>' . esc_html__( 'Before deleting the test site or reinstalling the theme please deactivate the license so that it can be reused.', 'stereo-bank' ) . '</strong></p>' .
		'</div>';
	}

}
