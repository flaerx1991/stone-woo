<?php
namespace StereoBankSpace\Admin\Installer;

use StereoBankSpace\Admin\Installer\Merlin\Config;
use StereoBankSpace\Admin\Installer\Merlin\Filters;
use StereoBankSpace\Admin\Installer\Importer\Importer;
use StereoBankSpace\Admin\Installer\Plugin_Activator\Plugin_Activator;
use StereoBankSpace\Core\Utils\API_Requests;
use StereoBankSpace\Core\Utils\File_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Installer module.
 *
 * Main class for installer module.
 */
class Installer {

	/**
	 * Installer module constructor.
	 */
	public function __construct() {
		add_action( 'after_switch_theme', array( $this, 'set_token' ) );
		add_action( 'admin_init', array( $this, 'first_setup' ) );

		$this->set_first_theme_version();

		new Plugin_Activator();

		new Importer();

		$this->run_wizard();
	}

	/**
	 * Set first theme version.
	 */
	private function set_first_theme_version() {
		if ( get_option( 'cmsmasters_stereo-bank_version', false ) ) {
			return;
		}

		update_option( 'cmsmasters_stereo-bank_version', CMSMASTERS_THEME_VERSION );
	}

	/**
	 * Set token if theme has been activated earlier.
	 */
	public function set_token() {
		API_Requests::regenerate_token();
	}

	/**
	 * First setup actions.
	 */
	public function first_setup() {
		if ( 'pending' !== get_option( 'cmsmasters_stereo-bank_first_setup', 'pending' ) ) {
			return;
		}

		$this->set_settings();

		$this->set_defaults();

		do_action( 'cmsmasters_set_backup_options', true );

		do_action( 'cmsmasters_set_import_status', 'pending' );

		do_action( 'cmsmasters_first_setup' );

		update_option( 'cmsmasters_stereo-bank_first_setup', 'done' );
	}

	/**
	 * Set settings.
	 */
	private function set_settings() {
		update_option( 'default_pingback_flag', 0 );

		update_option( 'elementor_page_title_selector', '.cmsmasters-headline' );
		update_option( 'elementor_disable_color_schemes', 'yes' );
		update_option( 'elementor_disable_typography_schemes', 'yes' );
		update_option( 'elementor_unfiltered_files_upload', 1 );
	}

	/**
	 * Set defaults.
	 */
	private function set_defaults() {
		$kits_path = get_parent_theme_file_path( '/theme-config/defaults/default-kits.json' );
		$options_path = get_parent_theme_file_path( '/theme-config/defaults/default-theme-options.json' );

		$kits = File_Manager::get_file_contents( $kits_path );
		$options = File_Manager::get_file_contents( $options_path );

		if ( '' !== $kits ) {
			$kits = json_decode( $kits, true );

			update_option( 'cmsmasters_stereo-bank_default_kits', $kits );
		}

		if ( '' !== $options ) {
			$options = json_decode( $options, true );

			update_option( 'cmsmasters_stereo-bank_options', $options );
		}
	}

	/**
	 * Require Merlin Installer.
	 */
	private function run_wizard() {
		require_once get_parent_theme_file_path( '/admin/installer/merlin/vendor/autoload.php' );
		require_once get_parent_theme_file_path( '/admin/installer/merlin/class-merlin.php' );

		$config = array(
			'directory' => 'admin/installer/merlin', // Location / directory where Merlin WP is placed in your theme.
			'merlin_url' => 'merlin', // The wp-admin page slug where Merlin WP loads.
			'parent_slug' => 'themes.php', // The wp-admin parent page slug for the admin menu item.
			'capability' => 'manage_options', // The capability required for this menu to be displayed to the user.
			'child_action_btn_url' => 'https://developer.wordpress.org/themes/advanced-topics/child-themes/', // URL for the 'child-action-link'.
			'dev_mode' => false, // Enable development mode for testing.
			'license_step' => true, // EDD license activation step.
			'license_required' => true, // Require the license activation step.
			'license_help_url' => 'https://docs.cmsmasters.net/blog/how-to-find-your-envato-purchase-code/', // URL for the 'license-tooltip'.
			'edd_remote_api_url' => '', // EDD_Theme_Updater_Admin remote_api_url.
			'edd_item_name' => '', // EDD_Theme_Updater_Admin item_name.
			'edd_theme_slug' => '', // EDD_Theme_Updater_Admin item_slug.
			'ready_big_button_url' => home_url( '/' ), // Link for the big button on the ready step.
		);

		$strings = array(
			'admin-menu' => esc_html__( 'Theme Setup', 'stereo-bank' ),
			/* translators: 1: Title Tag 2: Theme Name 3: Closing Title Tag */
			'title%s%s%s%s' => esc_html__( '%1$s%2$s Themes &lsaquo; Theme Setup: %3$s%4$s', 'stereo-bank' ),
			'return-to-dashboard' => esc_html__( 'Return to the dashboard', 'stereo-bank' ),
			'ignore' => '',

			'btn-skip' => esc_html__( 'Skip', 'stereo-bank' ),
			'btn-next' => esc_html__( 'Next', 'stereo-bank' ),
			'btn-start' => esc_html__( 'Start', 'stereo-bank' ),
			'btn-no' => esc_html__( 'Cancel', 'stereo-bank' ),
			'btn-plugins-install' => esc_html__( 'Install', 'stereo-bank' ),
			'btn-child-install' => esc_html__( 'Install', 'stereo-bank' ),
			'btn-content-install' => esc_html__( 'Install', 'stereo-bank' ),
			'btn-import' => esc_html__( 'Import', 'stereo-bank' ),
			'btn-license-activate' => esc_html__( 'Activate', 'stereo-bank' ),
			'btn-license-skip' => esc_html__( 'Later', 'stereo-bank' ),

			/* translators: Theme Name */
			'license-header%s' => esc_html__( 'Activate %s', 'stereo-bank' ),
			/* translators: Theme Name */
			'license-header-success%s' => esc_html__( '%s license is Activated', 'stereo-bank' ),
			/* translators: Theme Name */
			'license%s' => esc_html__( 'Enter your license key to enable remote updates and theme support.', 'stereo-bank' ),
			'license-label' => esc_html__( 'License key', 'stereo-bank' ),
			'license-success%s' => esc_html__( 'The theme is already registered, so you can go to the next step!', 'stereo-bank' ),
			'license-json-success%s' => esc_html__( 'Your license is activated! Remote updates and theme support are enabled.', 'stereo-bank' ),
			'license-tooltip' => esc_html__( 'Need help?', 'stereo-bank' ),

			/* translators: Theme Name */
			'welcome-header%s' => esc_html__( 'Welcome to %s', 'stereo-bank' ),
			'welcome-header-success%s' => esc_html__( 'Hi. Welcome back', 'stereo-bank' ),
			'welcome%s' => esc_html__( 'This wizard will set up your theme, install plugins, and import content. It is optional & should take only a few minutes.', 'stereo-bank' ),
			'welcome-success%s' => esc_html__( 'You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.', 'stereo-bank' ),

			'child-header' => esc_html__( 'Install Child Theme', 'stereo-bank' ),
			'child-header-success' => esc_html__( 'You\'re good to go!', 'stereo-bank' ),
			'child' => esc_html__( 'Let\'s build & activate a child theme so you may easily make theme changes.', 'stereo-bank' ),
			'child-success%s' => esc_html__( 'Your child theme has already been installed and is now activated, if it wasn\'t already.', 'stereo-bank' ),
			'child-action-link' => esc_html__( 'Learn about child themes', 'stereo-bank' ),
			'child-json-success%s' => esc_html__( 'Awesome. Your child theme has already been installed and is now activated.', 'stereo-bank' ),
			'child-json-already%s' => esc_html__( 'Awesome. Your child theme has been created and is now activated.', 'stereo-bank' ),

			'plugins-header' => esc_html__( 'Install Plugins', 'stereo-bank' ),
			'plugins-header-success' => esc_html__( 'You\'re up to speed!', 'stereo-bank' ),
			'plugins' => esc_html__( 'Let\'s install some essential WordPress plugins to get your site up to speed.', 'stereo-bank' ),
			'plugins-success%s' => esc_html__( 'The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.', 'stereo-bank' ),
			'plugins-action-link' => esc_html__( 'Advanced', 'stereo-bank' ),

			'import-header' => esc_html__( 'Import Content', 'stereo-bank' ),
			'import' => esc_html__( 'Let\'s import content to your website, to help you get familiar with the theme.', 'stereo-bank' ),
			'import-action-link' => esc_html__( 'Advanced', 'stereo-bank' ),

			'ready-header' => esc_html__( 'All done. Have fun!', 'stereo-bank' ),

			/* translators: Theme Author */
			'ready%s' => esc_html__( 'Your theme has been all set up. Enjoy your new theme by %s.', 'stereo-bank' ),
			'ready-action-link' => esc_html__( 'Extras', 'stereo-bank' ),
			'ready-big-button' => esc_html__( 'View your website', 'stereo-bank' ),
			'ready-link-1' => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://cmsmasters.net/', esc_html__( 'Get Theme Support', 'stereo-bank' ) ),
			'ready-link-2' => sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=go_theme_settings' ), esc_html__( 'Theme Settings', 'stereo-bank' ) ),
		);

		new Config( $config, $strings );

		new Filters();
	}

}
