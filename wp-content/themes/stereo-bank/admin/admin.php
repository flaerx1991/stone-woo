<?php
namespace StereoBankSpace\Admin;

use StereoBankSpace\Admin\Installer\Installer;
use StereoBankSpace\Admin\Options\Options_Manager;
use StereoBankSpace\Admin\Upgrader\Upgrader;
use StereoBankSpace\Core\Utils\API_Requests;
use StereoBankSpace\Core\Utils\File_Manager;
use StereoBankSpace\ThemeConfig\Theme_Config;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Admin modules.
 *
 * Main class for admin modules.
 */
class Admin {

	/**
	 * Admin modules constructor.
	 *
	 * Run modules for admin.
	 */
	public function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		add_filter( 'filesystem_method', array( $this, 'filter_filesystem_method' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );

		add_action( 'wp_ajax_cmsmasters_hide_admin_notice', array( $this, 'ajax_hide_admin_notice' ) );

		add_filter( 'cmsmasters_ei_export_theme_options_name', array( $this, 'export_theme_options_name' ) );

		add_filter( 'register_post_type_args', array( $this, 'remove_export_post_types' ), 10, 2 );

		$this->add_notices();

		new Installer();

		new Upgrader();

		new Options_Manager();
	}

	/**
	 * Filter filesystem method.
	 */
	public function filter_filesystem_method( $method ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return $method;
		}

		return 'direct';
	}

	/**
	 * Enqueue admin assets.
	 */
	public function enqueue_admin_assets() {
		// Scripts
		wp_enqueue_script(
			'stereo-bank-admin',
			File_Manager::get_js_assets_url( 'admin' ),
			array( 'jquery' ),
			'1.0.0',
			true
		);

		wp_localize_script( 'stereo-bank-admin', 'cmsmasters_admin', array(
			'nonce' => wp_create_nonce( 'cmsmasters_admin_nonce' ),
		) );
	}

	/**
	 * Hide admin notice.
	 */
	public function ajax_hide_admin_notice() {
		if ( ! check_ajax_referer( 'cmsmasters_admin_nonce', 'nonce' ) ) {
			wp_send_json( array(
				'success' => false,
				'code' => 'invalid_nonce',
				'message' => esc_html__( 'Invalid nonce. Notice was not deleted.', 'stereo-bank' ),
			) );
		}

		if ( ! isset( $_POST['option_key'] ) ) {
			wp_send_json( array(
				'success' => false,
				'code' => 'empty_option_key',
				'message' => esc_html__( 'Empty option key.', 'stereo-bank' ),
			) );
		}

		update_option( $_POST['option_key'], 'hide' );
	}

	/**
	 * Add admin notices.
	 */
	protected function add_notices() {
		if ( ! did_action( 'elementor/loaded' ) && current_user_can( 'install_plugins' ) ) {
			add_action( 'admin_notices', array( $this, 'elementor_activation_notice' ) );
		}

		if ( ! API_Requests::check_token_status() ) {
			add_action( 'admin_notices', array( $this, 'license_activation_notice' ) );
		}

		add_action( 'admin_notices', array( $this, 'apply_demo_notice' ) );
	}

	/**
	 * Elementor activation notice.
	 */
	public function elementor_activation_notice() {
		$screen = get_current_screen();

		if (
			isset( $screen->parent_file ) &&
			'plugins.php' === $screen->parent_file &&
			'update' === $screen->id
		) {
			return;
		}

		$plugins = get_plugins();

		if ( isset( $plugins['elementor/elementor.php'] ) ) {
			$link_url = wp_nonce_url(
				self_admin_url( 'plugins.php?action=activate&plugin=elementor/elementor.php&plugin_status=active' ),
				'activate-plugin_elementor/elementor.php'
			);
			$link_text = esc_html__( 'Activate', 'stereo-bank' );
		} else {
			$link_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
			$link_text = esc_html__( 'Install', 'stereo-bank' );
		}

		echo '<div class="notice notice-error">' .
			'<p>' .
				sprintf(
					esc_html__( '%s requires Elementor to be activate.', 'stereo-bank' ),
					'<strong>' . esc_html__( 'The Theme', 'stereo-bank' ) . '</strong>'
				) .
				'&nbsp;&nbsp;&nbsp;<a href="' . esc_url( $link_url ) . '" class="button button-primary">' . esc_html( $link_text ) . '</a>' .
			'</p>' .
		'</div>';
	}

	/**
	 * License activation notice.
	 */
	public function license_activation_notice() {
		if ( isset( $_GET['page'] ) && 'cmsmasters-options-license' === $_GET['page'] ) {
			return;
		}

		echo '<div class="notice notice-warning is-dismissible">' .
			'<p><strong>' . esc_html__( 'Your license is not activated.', 'stereo-bank' ) . '</strong></p>' .
			'<p>' .
				sprintf(
					esc_html__(
						/* translators: %s: License activation link */
						'To use the full functionality of the theme, please %s',
						'stereo-bank'
					),
					'<strong><a href="' . esc_url( self_admin_url( 'admin.php?page=cmsmasters-options-license' ) ) . '">' . esc_html__( 'activate the license', 'stereo-bank' ) . '</a></strong>'
				) .
			'</p>' .
		'</div>';
	}

	/**
	 * Apply demo notice.
	 */
	public function apply_demo_notice() {
		if ( 'demos' !== Theme_Config::IMPORT_TYPE || 'show' !== get_option( 'cmsmasters_apply_demo_notice_visibility' ) ) {
			return;
		}

		echo '<div class="notice notice-info is-dismissible cmsmasters-dismiss-notice-permanent" data-option-key="cmsmasters_apply_demo_notice_visibility">' .
			'<p>' .
				sprintf(
					__( 'You have applied a new design concept to your website. Image sizes in design concepts may differ, this is why it is recommended to run a %1$sRegenerate Thumbnails%2$s tool to generate new image sizes.', 'stereo-bank' ),
					'<a href="' . esc_url( 'https://wordpress.org/plugins/regenerate-thumbnails/' ) . '" target="_blank">',
					'</a>'
				) .
			'</p>' .
		'</div>';
	}

	/**
	 * Filter for customizer export/import plugin.
	 *
	 * Add theme options keys to export process.
	 *
	 * @param array $keys Options keys.
	 *
	 * @return array Options keys.
	 */
	public function export_theme_options_name() {
		return 'cmsmasters_stereo-bank_options';
	}

	/**
	 * Remove export post_types from wp export.
	 */
	public function remove_export_post_types( $args, $post_type ) {
		if (
			'acf-field-group' === $post_type ||
			'acf-field' === $post_type ||
			'give_payment' === $post_type
		) {
			$args['can_export'] = false;
		}

		return $args;
	}

}
