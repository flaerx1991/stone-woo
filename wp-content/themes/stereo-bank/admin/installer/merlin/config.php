<?php
namespace StereoBankSpace\Admin\Installer\Merlin;

use StereoBankSpace\Core\Utils\API_Requests;
use StereoBankSpace\Core\Utils\File_Manager;
use StereoBankSpace\Core\Utils\Utils;
use StereoBankSpace\ThemeConfig\Theme_Config;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Merlin' ) ) {
	exit;
}

/**
 * Installer config.
 *
 * Main class for installer config.
 */
class Config extends \Merlin {

	/**
	 * Demos list.
	 */
	private $demos_list = array();

	/**
	 * Config constructor.
	 *
	 * @param array $config Package-specific configuration args.
	 * @param array $strings Text for the different elements.
	 */
	public function __construct( $config = array(), $strings = array() ) {
		parent::__construct( $config, $strings );

		if ( true !== $this->dev_mode ) {
			// Has this theme been setup yet?
			$already_setup = get_option( 'merlin_' . $this->slug . '_completed' );

			// Return if Merlin has already completed it's setup.
			if ( $already_setup ) {
				return;
			}
		}

		$this->remove_plugins_activation_early_redirect();

		add_action( 'admin_init', array( $this, 'init_actions' ) );

		add_filter( $this->theme->template . '_merlin_steps', array( $this, 'change_steps' ) );

		add_filter( 'merlin_is_theme_registered', array( $this, 'check_theme_registration' ) );

		add_filter( 'merlin_import_files', array( $this, 'set_import_files' ) );

		add_action( 'wp_ajax_cmsmasters_installer', array( $this, 'run_installer' ) );
	}

	/**
	 * Init actions.
	 */
	public function init_actions() {
		// Do not proceed, if we're not on the right page.
		if ( empty( $_GET['page'] ) || $this->merlin_url !== $_GET['page'] ) {
			return;
		}

		$current_step = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( $this->steps ) );

		if (
			'child' !== $current_step &&
			'plugins' !== $current_step &&
			'content' !== $current_step
		) {
			delete_transient( 'cmsmasters_stereo-bank_installer_type' );
			delete_transient( 'cmsmasters_stereo-bank_content_import' );
		}

		if ( 'ready' === $current_step ) {
			$demo = Utils::get_demo();

			delete_transient( "cmsmasters_stereo-bank_{$demo}_content_import_files" );

			do_action( 'cmsmasters_import_ready' );

			if ( false === get_transient( "cmsmasters_stereo-bank_{$demo}_content_import_status" ) ) {
				do_action( 'cmsmasters_remove_unique_elementor_locations' );
			}
		}

		$this->enqueue_assets();

		$this->remove_plugins_activation_redirect();
	}

	/**
	 * Enqueue assets.
	 */
	protected function enqueue_assets() {
		// Styles
		wp_enqueue_style(
			'stereo-bank-installer',
			File_Manager::get_css_assets_url( 'installer', null, 'default', true ),
			array( 'merlin' ),
			'1.0.0',
			'screen'
		);

		// Scripts
		wp_enqueue_script(
			'stereo-bank-installer',
			File_Manager::get_js_assets_url( 'installer' ),
			array( 'merlin' ),
			'1.0.0',
			true
		);

		wp_localize_script(
			'stereo-bank-installer', 'installer_params', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'type' => get_transient( 'cmsmasters_stereo-bank_installer_type' ),
				'content_import' => get_transient( 'cmsmasters_stereo-bank_content_import' ),
				'wpnonce' => wp_create_nonce( 'cmsmasters_stereo-bank_installer_nonce' ),
			)
		);
	}

	/**
	 * Remove plugins redirect on activation.
	 */
	protected function remove_plugins_activation_redirect() {
		delete_transient( 'cptui_activation_redirect' );
		update_option( 'wpforms_activation_redirect', true );
	}

	/**
	 * Remove plugins redirect on activation.
	 */
	protected function remove_plugins_activation_early_redirect() {
		if ( defined( 'PMPRO_VERSION' ) ) {
			update_option( 'pmpro_dashboard_version', PMPRO_VERSION, 'no' );
		}

		delete_transient( 'elementor_activation_redirect' );
		delete_transient( '_sp_activation_redirect' );
		add_filter( 'woocommerce_enable_setup_wizard', '__return_false' );
		add_filter( 'fs_redirect_on_activation_interactive-geo-maps', '__return_false' );

		if ( class_exists( '\Give_Cache' ) ) {
			\Give_Cache::delete( \Give_Cache::get_key( '_give_activation_redirect' ) );
		}
	}

	/**
	 * Output the header.
	 */
	protected function header() {

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Get the current step.
		$current_step = strtolower( $this->steps[ $this->step ]['name'] );
		$body_classes = 'merlin__body merlin__body--' . $current_step;

		if (
			'plugins' === $current_step &&
			(
				! did_action( 'elementor/loaded' ) ||
				! class_exists( 'Cmsmasters_Elementor_Addon' )
			)
		) {
			$body_classes .= ' no_required_plugins';
		}

		if ( 'demos' === $current_step ) {
			$body_classes .= ' cmsmasters-demos-count-' . count( $this->demos_list );
		}
		?>

		<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
		<head>
			<meta name="viewport" content="width=device-width"/>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<?php printf( esc_html( $strings['title%s%s%s%s'] ), '<ti', 'tle>', esc_html( $this->theme->name ), '</title>' ); ?>
			<?php do_action( 'admin_print_styles' ); ?>
			<?php do_action( 'admin_print_scripts' ); ?>
		</head>
		<body class="<?php echo esc_attr( $body_classes ); ?>">
		<?php
	}

	/**
	 * Add the admin page.
	 */
	public function admin_page() {

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Do not proceed, if we're not on the right page.
		if ( empty( $_GET['page'] ) || $this->merlin_url !== $_GET['page'] ) {
			return;
		}

		if ( ob_get_length() ) {
			ob_end_clean();
		}

		$this->step = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( $this->steps ) );

		// Use minified libraries if dev mode is turned on.
		$suffix = ( ( true === $this->dev_mode ) ) ? '' : '.min';

		// Enqueue styles.
		wp_enqueue_style( 'merlin', trailingslashit( $this->base_url ) . $this->directory . '/assets/css/merlin' . $suffix . '.css', array( 'wp-admin' ), MERLIN_VERSION );

		// Enqueue javascript.
		wp_enqueue_script( 'merlin', trailingslashit( $this->base_url ) . $this->directory . '/assets/js/merlin' . $suffix . '.js', array( 'jquery-core' ), MERLIN_VERSION );

		$texts = array(
			'something_went_wrong' => esc_html__( 'Something went wrong. Please refresh the page and try again!', 'merlin-wp' ),
		);

		// Localize the javascript.
		if ( class_exists( 'TGM_Plugin_Activation' ) ) {
			// Check first if TMGPA is included.
			wp_localize_script(
				'merlin', 'merlin_params', array(
					'tgm_plugin_nonce' => array(
						'update'  => wp_create_nonce( 'tgmpa-update' ),
						'install' => wp_create_nonce( 'tgmpa-install' ),
					),
					'tgm_bulk_url'     => $this->tgmpa->get_tgmpa_url(),
					'ajaxurl'          => admin_url( 'admin-ajax.php' ),
					'wpnonce'          => wp_create_nonce( 'merlin_nonce' ),
					'texts'            => $texts,
				)
			);
		} else {
			// If TMGPA is not included.
			wp_localize_script(
				'merlin', 'merlin_params', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'wpnonce' => wp_create_nonce( 'merlin_nonce' ),
					'texts'   => $texts,
				)
			);
		}

		ob_start();

		if ( 'demos' === $this->step ) {
			$response = API_Requests::get_request( 'get-demos-list' );
			$response_code = wp_remote_retrieve_response_code( $response );

			if ( 200 === $response_code ) {
				$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

				$this->demos_list = $response_body['data'];
			}
		}

		/**
		 * Start the actual page content.
		 */
		$this->header(); ?>
		<div class="merlin__outer">
		<div class="merlin__wrapper">

			<div class="merlin__content merlin__content--<?php echo esc_attr( strtolower( $this->steps[ $this->step ]['name'] ) ); ?>">

				<?php
				// Content Handlers.
				$show_content = true;

				if ( ! empty( $_REQUEST['save_step'] ) && isset( $this->steps[ $this->step ]['handler'] ) ) {
					$show_content = call_user_func( $this->steps[ $this->step ]['handler'] );
				}

				if ( $show_content ) {
					$this->body();
				}
				?>

			<?php $this->step_output(); ?>

			</div>

			<?php echo sprintf( '<a class="return-to-dashboard" href="%s">%s</a>', esc_url( admin_url( '/' ) ), esc_html( $strings['return-to-dashboard'] ) ); ?>

			<?php $ignore_url = wp_nonce_url( admin_url( '?' . $this->ignore . '=true' ), 'merlinwp-ignore-nounce' ); ?>

			<?php echo sprintf( '<a class="return-to-dashboard ignore" href="%s">%s</a>', esc_url( $ignore_url ), esc_html( $strings['ignore'] ) ); ?>

		</div>
		</div>

		<?php $this->footer(); ?>

		<?php
		exit;
	}

	/**
	 * Change steps.
	 */
	public function change_steps( $steps ) {
		$steps_order = array(
			'welcome',
			'license',
			'demos',
			'child',
			'plugins',
			'content',
			'ready',
		);

		$steps_out = array();

		foreach ( $steps_order as $step_order ) {
			if ( 'demos' === $step_order ) {
				$steps_out[ $step_order ] = array(
					'name' => esc_html__( 'Demos', 'stereo-bank' ),
					'view' => array( $this, 'demos' ),
				);
			} elseif ( 'content' === $step_order && 'disabled' === get_transient( 'cmsmasters_stereo-bank_content_import' ) ) {
				continue;
			} elseif ( isset( $steps[ $step_order ] ) ) {
				$steps_out[ $step_order ] = $steps[ $step_order ];
			}
		}

		return $steps_out;
	}

	/**
	 * Activate the theme (license key) via AJAX.
	 */
	public function _ajax_activate_license() {
		if ( ! check_ajax_referer( 'merlin_nonce', 'wpnonce' ) ) {
			wp_send_json(
				array(
					'success' => false,
					'message' => esc_html__( 'Yikes! The theme activation failed. Please try again or contact support.', 'stereo-bank' ),
				)
			);
		}

		if ( empty( $_POST['license_key'] ) ) {
			wp_send_json(
				array(
					'success' => false,
					'message' => esc_html__( 'Please add your license key before attempting to activate one.', 'stereo-bank' ),
				)
			);
		}

		API_Requests::generate_token( $_POST['license_key'] );

		wp_send_json(
			array(
				'done' => 1,
				'success' => true,
				'message' => sprintf( esc_html( $this->strings['license-json-success%s'] ), $this->theme_name ),
			)
		);
	}

	/**
	 * Check, if the theme is currently registered.
	 *
	 * @return bool.
	 */
	public function check_theme_registration() {
		return API_Requests::check_token_status();
	}

	/**
	 * Demos step.
	 */
	protected function demos() {
		$parent_class = 'cmsmasters-installer-demos';

		echo $this->get_pre_installation_notice();

		echo '<div class="' . esc_attr( $parent_class ) . '">' .
			'<ul class="' . esc_attr( $parent_class ) . '__list">';

		foreach ( $this->demos_list as $demo_key => $demo_args ) {
			$name = ( isset( $demo_args['name'] ) ? $demo_args['name'] : false );
			$preview_url = ( isset( $demo_args['preview_url'] ) ? $demo_args['preview_url'] : false );
			$preview_img_url = ( isset( $demo_args['preview_img_url'] ) ? $demo_args['preview_img_url'] : false );

			echo '<li class="' . esc_attr( $parent_class ) . '__item">' .
				'<figure class="' . esc_attr( $parent_class ) . '__item-image">' .
					'<span class="dashicons dashicons-format-image"></span>' .
					( $preview_img_url ? '<img src="' . esc_url( $preview_img_url ) . '" />' : '' ) .
					( $preview_url ? '<a href="' . esc_url( $preview_url ) . '" target="_blank" class="' . esc_attr( $parent_class ) . '__item-preview"><span title="' . esc_attr( $name ) . '">' . esc_html__( 'Demo Preview', 'stereo-bank' ) . '</span></a>' : '' ) .
				'</figure>' .
				'<div class="' . esc_attr( $parent_class ) . '__item-info">' .
					( $name ? '<h3 class="' . esc_attr( $parent_class ) . '__item-title">' . esc_html( $name ) . '</h3>' : '' ) .
					'<div class="' . esc_attr( $parent_class ) . '__item-buttons">' .
						'<a href="' . esc_url( $this->step_next_link() ) . '" class="cmsmasters-install-button cmsmasters-custom" data-key="' . esc_attr( $demo_key ) . '">' . esc_html__( 'Manual', 'stereo-bank' ) . '</a>' .
						'<div class="' . esc_attr( $parent_class ) . '__item-buttons-express-wrap">' .
							'<label>' .
								esc_html__( 'Import dummy content?', 'stereo-bank' ) .
								'<input type="checkbox" checked="checked" class="cmsmasters-import-content-status" />' .
							'</label>' .
							'<a href="' . esc_url( $this->step_next_link() ) . '" class="cmsmasters-install-button cmsmasters-express" data-key="' . esc_attr( $demo_key ) . '">' . esc_html__( 'One-click Install', 'stereo-bank' ) . '</a>' .
						'</div>' .
					'</div>' .
				'</div>' .
			'</li>';
		}

			echo '</ul>' .
		'</div>';
	}

	/**
	 * Get pre installation notice.
	 *
	 * @return string Notice HTML.
	 */
	public function get_pre_installation_notice() {
		$limits_to_increase = $this->get_server_limits_to_increase();
		$php_modules_to_include = $this->get_php_modules_to_include();

		if ( empty( $limits_to_increase ) && empty( $php_modules_to_include ) ) {
			return '';
		}

		$out = '<div class="cmsmasters-pre-installation-notice">
			<span class="cmsmasters-pre-installation-notice__close"></span>
			<div class="cmsmasters-pre-installation-notice__inner">
				<p class="cmsmasters-pre-installation-notice__title">' . esc_html__( 'Your theme provides demo content for a ready website, including all pages, post types, templates and other elements, so in order for it to be installed please make sure your server has appropriate settings:', 'stereo-bank' ) . '</p>';

				if ( ! empty( $limits_to_increase ) ) {
					$out .= '<p class="cmsmasters-pre-installation-notice__subtitle">' . esc_html__( 'increase the PHP configuration limits to at least:', 'stereo-bank' ) . '</p>' .
					$limits_to_increase;
				}

				if ( ! empty( $php_modules_to_include ) ) {
					$out .= '<p class="cmsmasters-pre-installation-notice__subtitle">' . esc_html__( 'enable PHP modules:', 'stereo-bank' ) . '</p>' .
					$php_modules_to_include;
				}

				$out .= '<p class="cmsmasters-pre-installation-notice__info">' . 
					sprintf(
						esc_html__( 'You can find more information %s', 'stereo-bank' ),
						'<a href="https://docs.cmsmasters.net/requirements/" target="_blank">' . esc_html__( 'here', 'stereo-bank' ) . '</a>'
					) .
				'</p>';

			$out .= '</div>
		</div>';

		return $out;
	}

	/**
	 * Get server limits to increase for pre installation notice.
	 *
	 * @return string pre installation notice part.
	 */
	public function get_server_limits_to_increase() {
		if ( ! function_exists( 'ini_get' ) ) {
			return;
		}

		$recommended_limits = array(
			'max_execution_time' => '300',
			'max_input_time' => '300',
			'post_max_size' => '64M',
			'upload_max_filesize' => '64M',
			'memory_limit '=> '256M',
		);

		$limits = '';

		foreach ( $recommended_limits as $key => $value ) {
			$ini_limit = ini_get( $key );
			$ini_limit = ( -1 == $ini_limit || 0 == $ini_limit ? $value : $ini_limit );

			if ( wp_convert_hr_to_bytes( $value ) > wp_convert_hr_to_bytes( $ini_limit ) ) {
				$limits .= '<li>' . $key . ' ' . $value . '</li>';
			}
		}

		if ( empty( $limits ) ) {
			return '';
		}

		return '<ul class="cmsmasters-pre-installation-notice__limits">' . $limits . '</ul>';
	}

	/**
	 * Get php modules to include for pre installation notice.
	 *
	 * @return string pre installation notice part.
	 */
	public function get_php_modules_to_include() {
		$test_php_extensions = \WP_Site_Health::get_instance()->get_test_php_extensions();

		if ( 'good' === $test_php_extensions['status'] ) {
			return '';
		}

		$pattern = '/<\/span?[^>]+>\s(.*?)<\/li/';

		preg_match_all( $pattern, $test_php_extensions['description'], $matches );

		$modules = '';

		if ( ! is_array( $matches[1] ) || empty( $matches[1] ) ) {
			return '';
		}

		foreach ( $matches[1] as $match ) {
			$modules .= '<li>' . esc_html( $match ) . '</li>';
		}

		if ( empty( $modules ) ) {
			return '';
		}

		return '<ul class="cmsmasters-pre-installation-notice__modules">' . $modules . '</ul>';
	}

	/**
	 * Run installer.
	 */
	public function run_installer() {
		$type = ! isset( $_POST['type'] ) ? false : $_POST['type'];
		$content_import = ! isset( $_POST['content_import'] ) ? false : $_POST['content_import'];
		$demo_key = ! isset( $_POST['demo_key'] ) ? false : $_POST['demo_key'];

		if (
			false === $type ||
			false === $content_import ||
			false === $demo_key
		) {
			wp_send_json_error( array(
				'code' => 'invalid_demo_data',
				'message' => 'Invalid demo data.',
			), 403 );
		}

		set_transient( 'cmsmasters_stereo-bank_installer_type', $type, HOUR_IN_SECONDS );

		set_transient( 'cmsmasters_stereo-bank_content_import', $content_import, HOUR_IN_SECONDS );

		if ( 'demos' !== Theme_Config::IMPORT_TYPE ) {
			Utils::set_demo_kit( $demo_key );
		}

		if ( 'only_kit' === Theme_Config::IMPORT_TYPE ) {
			$demo_key = 'main';
		}

		Utils::set_demo( $demo_key );

		$this->set_demo_content_import_files( $demo_key );

		do_action( 'cmsmasters_set_import_status', 'pending' );

		do_action( 'cmsmasters_remove_temp_data' );
	}

	/**
	 * Set demo content import files.
	 *
	 * @param string $demo_key Demo key.
	 */
	public function set_demo_content_import_files( $demo_key ) {
		$response = API_Requests::post_request( 'get-demo-files', array( 'demo' => $demo_key ) );
		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 === $response_code ) {
			$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

			set_transient( "cmsmasters_stereo-bank_{$demo_key}_content_import_files", $response_body['data'], HOUR_IN_SECONDS );
		}
	}

	/**
	 * Set files for demo import.
	 */
	public function set_import_files( $files ) {
		$import_files = get_transient( 'cmsmasters_stereo-bank_' . Utils::get_demo() . '_content_import_files' );

		if ( false !== $import_files ) {
			$files = $import_files;
		}

		return $files;
	}

	/**
	 * Get the import steps HTML output.
	 *
	 * @param array $import_info The import info to prepare the HTML for.
	 *
	 * @return string
	 */
	public function get_import_steps_html( $import_info ) {
		ob_start();
		?>
			<?php foreach ( $import_info as $slug => $available ) : ?>
				<?php
				if ( ! $available ) {
					continue;
				}
				?>

				<li class="merlin__drawer--import-content__list-item status status--Pending" data-content="<?php echo esc_attr( $slug ); ?>">
					<input type="checkbox" name="default_content[<?php echo esc_attr( $slug ); ?>]" class="checkbox checkbox-<?php echo esc_attr( $slug ); ?>" id="default_content_<?php echo esc_attr( $slug ); ?>" value="1" checked>
					<label for="default_content_<?php echo esc_attr( $slug ); ?>">
						<i></i><span><?php 
						if ( 'content' === $slug ) {
							echo esc_html__( 'Dummy Content', 'stereo-bank' );
						} elseif ( 'widgets' === $slug ) {
							echo esc_html__( 'Sidebars Widgets', 'stereo-bank' );
						} elseif ( 'options' === $slug ) {
							echo esc_html__( 'Customizer Settings', 'stereo-bank' );
						} else {
							echo esc_html( ucfirst( str_replace( '_', ' ', $slug ) ) );
						}
						?></span>
					</label>
				</li>

			<?php endforeach; ?>
		<?php

		return ob_get_clean();
	}

	/**
	 * Do content's AJAX
	 */
	public function _ajax_content() {
		static $content = null;

		$selected_import = intval( $_POST['selected_index'] );

		if ( null === $content ) {
			$content = $this->get_import_data( $selected_import );
		}

		if ( ! check_ajax_referer( 'merlin_nonce', 'wpnonce' ) || empty( $_POST['content'] ) && isset( $content[ $_POST['content'] ] ) ) {
			$this->logger->error( __( 'The content importer AJAX call failed to start, because of incorrect data', 'merlin-wp' ) );

			wp_send_json_error(
				array(
					'error'   => 1,
					'message' => esc_html__( 'Invalid content!', 'merlin-wp' ),
				)
			);
		}

		$json         = false;
		$this_content = $content[ $_POST['content'] ];

		if ( isset( $_POST['proceed'] ) ) {
			if ( is_callable( $this_content['install_callback'] ) ) {
				$this->logger->info(
					__( 'The content import AJAX call will be executed with this import data', 'merlin-wp' ),
					array(
						'title' => $this_content['title'],
						'data'  => $this_content['data'],
					)
				);

				$logs = call_user_func( $this_content['install_callback'], $this_content['data'] );

				if ( 'content' === $_POST['content'] && class_exists( 'mp_timetable\classes\models\Import' ) ) {
					$mptt_content_url = $this->import_files[0]['import_mptt_file_url'];

					if ( ! empty( $mptt_content_url ) ) {
						$mptt_import = new \mp_timetable\classes\models\Import();

						$mptt_import->fetch_attachments = true;

						$mptt_import->process_start( $mptt_content_url );
					}
				}

				if ( $logs ) {
					$json = array(
						'done'    => 1,
						'message' => $this_content['success'],
						'debug'   => '',
						'logs'    => $logs,
						'errors'  => '',
					);

					// The content import ended, so we should mark that all posts were imported.
					if ( 'content' === $_POST['content'] ) {
						$json['num_of_imported_posts'] = 'all';
					}
				}
			}
		} else {
			$json = array(
				'url'            => admin_url( 'admin-ajax.php' ),
				'action'         => 'merlin_content',
				'proceed'        => 'true',
				'content'        => $_POST['content'],
				'_wpnonce'       => wp_create_nonce( 'merlin_nonce' ),
				'selected_index' => $selected_import,
				'message'        => $this_content['installing'],
				'logs'           => '',
				'errors'         => '',
			);
		}

		if ( $json ) {
			$json['hash'] = md5( serialize( $json ) );
			wp_send_json( $json );
		} else {
			$this->logger->error(
				__( 'The content import AJAX call failed with this passed data', 'merlin-wp' ),
				array(
					'selected_content_index' => $selected_import,
					'importing_content'      => $_POST['content'],
					'importing_data'         => $this_content['data'],
				)
			);

			wp_send_json(
				array(
					'error'   => 1,
					'message' => esc_html__( 'Error', 'merlin-wp' ),
					'logs'    => '',
					'errors'  => '',
				)
			);
		}
	}

}
