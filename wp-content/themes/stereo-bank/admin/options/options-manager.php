<?php
namespace StereoBankSpace\Admin\Options;

use Elementor\Plugin as Elementor_Plugin;
use Elementor\Core\Kits\Manager as Elementor_Kits_Manager;

use StereoBankSpace\Admin\Options\Options_Utils;
use StereoBankSpace\Admin\Options\Pages\Base\Base_Page;
use StereoBankSpace\Core\Utils\File_Manager;
use StereoBankSpace\Core\Utils\Utils;
use StereoBankSpace\ThemeConfig\Theme_Config;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Options Manager handler class is responsible for different methods on theme options.
 */
class Options_Manager {

	/**
	 * Pages.
	 */
	public $pages = array();

	/**
	 * Page.
	 */
	public $page = '';

	/**
	 * Page class.
	 *
	 * @var string|Base_Page;
	 */
	public $page_class = '';

	/**
	 * Page sections.
	 */
	public $sections = array();

	/**
	 * Default section.
	 */
	public $default_section = '';

	/**
	 * Page fields.
	 */
	public $fields = array();

	/**
	 * Section prefix.
	 */
	public $section_prefix = 'cmsmasters_section_';

	/**
	 * Class prefix.
	 */
	public $class_prefix = 'cmsmasters-options';

	/**
	 * Options manager constructor.
	 */
	public function __construct() {
		$this->set_page();

		$this->set_pages_data();

		$this->set_sections();

		$this->set_default_section();

		$this->set_fields();

		add_action( 'admin_init', array( $this, 'on_admin_init' ) );

		add_action( 'admin_menu', array( $this, 'create_menu' ), 20 );

		add_action( 'admin_notices', array( $this, 'admin_messages' ) );

		add_filter( 'menu_order', array( $this, 'menu_order' ) );
	}

	/**
	 * On admin init.
	 *
	 * Preform actions on WordPress admin initialization.
	 *
	 * Fired by `admin_init` action.
	 */
	public function on_admin_init() {
		$this->register_options();

		if ( $this->req_plugins_activation() ) {
			$this->handle_external_redirects();
		}
	}

	/**
	 * Check if required plugins are active.
	 *
	 * @return bool
	 */
	private function req_plugins_activation() {
		return did_action( 'elementor/loaded' );
	}

	/**
	 * Set page.
	 */
	public function set_page() {
		$this->page = Options_Utils::get_admin_page();
	}

	/**
	 * Set pages data.
	 *
	 * Get data from pages classes and set it to $pages.
	 */
	public function set_pages_data() {
		foreach ( Options_Utils::$pages as $page ) {
			$class_name = __NAMESPACE__ . '\\Pages\\' . ucwords( str_replace( '-', '_', $page ), '_' );

			if ( ! class_exists( $class_name ) ) {
				continue;
			}

			$this->pages[ $page ]['page_title'] = $class_name::get_page_title();
			$this->pages[ $page ]['menu_title'] = $class_name::get_menu_title();

			if ( $this->page === $page ) {
				$this->page_class = new $class_name();
			}
		}
	}

	/**
	 * Set sections.
	 */
	public function set_sections() {
		if ( ! method_exists( $this->page_class, 'get_sections' ) ) {
			return false;
		}

		$this->sections = $this->page_class->get_sections();
	}

	/**
	 * Set default sections.
	 */
	public function set_default_section() {
		if ( property_exists( $this->page_class, 'default_section' ) ) {
			$this->default_section = $this->page_class->default_section;
		}

		if ( '' === $this->default_section ) {
			$keys = array_keys( $this->sections );
			reset( $keys );
			$this->default_section = current( $keys );
		}
	}

	/**
	 * Set fields.
	 */
	public function set_fields() {
		if ( ! method_exists( $this->page_class, 'get_fields' ) ) {
			return false;
		}

		foreach ( $this->sections as $section_key => $section_args ) {
			$fields = $this->page_class->get_fields( $section_key );

			foreach ( $fields as $field_key => $field_args ) {
				$id = $field_key;
				$sub_id = '';

				if ( false !== strpos( $field_key, '|' ) ) {
					$id_arr = explode( '|', $field_key );

					$id = $id_arr[0];
					$sub_id = $id_arr[1];
				}

				$std = Utils::get_theme_option( $id, '' );

				if ( '' !== $sub_id ) {
					if ( ! isset( $std[ $sub_id ] ) ) {
						$std = '';
					} else {
						$std = $std[ $sub_id ];
					}
				}

				$default_args = array(
					'id' => $id,
					'sub_id' => $sub_id,
					'section' => $this->section_prefix . $section_key,
					'title' => esc_html__( 'Default Field', 'stereo-bank' ),
					'desc' => '',
					'label' => '',
					'postfix' => '',
					'type' => 'text',
					'subtype' => '',
					'class' => '',
					'std' => $std,
					'not_empty' => false,
				);

				$parsed_args = wp_parse_args( $field_args, $default_args );

				$this->fields[ $field_key ] = $parsed_args;
			}
		}
	}

	/**
	 * Create menu.
	 */
	public function create_menu() {
		global $menu;

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$menu[] = array( '', 'read', 'separator-cmsmasters', '', 'wp-menu-separator cmsmasters' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		$current_theme = wp_get_theme();

		if ( $current_theme->parent() ) {
			$current_theme = $current_theme->parent();
		}

		$theme_name = $current_theme->get( 'Name' );

		add_menu_page(
			__( 'Designs', 'stereo-bank'),
			$theme_name,
			'manage_options',
			Options_Utils::MENU_SLUG,
			array( $this, 'render_page' ),
			'data:image/svg+xml;base64,' . Options_Utils::MENU_ICON,
			'58.1'
		);

		foreach ( $this->pages as $page => $attributes ) {
			$this->create_submenu( $page, $attributes );
		}
	}

	/**
	 * Create submenu.
	 *
	 * @param string $page
	 * @param array $attributes
	 */
	private function create_submenu( $page, $attributes ) {
		$slug = Options_Utils::MENU_SLUG;

		if ( Options_Utils::$main_page !== $page ) {
			$slug .= "-{$page}";
		}

		$hook_name = add_submenu_page(
			Options_Utils::MENU_SLUG,
			$attributes['page_title'],
			$attributes['menu_title'],
			'manage_options',
			$slug,
			array( $this, 'render_page' )
		);

		if ( $this->req_plugins_activation() && Options_Utils::$main_page === $page ) {
			add_submenu_page(
				Options_Utils::MENU_SLUG,
				'',
				__( 'Theme Settings', 'stereo-bank' ),
				'manage_options',
				'go_theme_settings',
				array( $this, 'handle_external_redirects' )
			);
		}

		add_action( "load-{$hook_name}", array( $this, 'enqueue_page_assets' ) );
	}

	/**
	 * Go Addon Settings.
	 *
	 * Redirect the Addon Settings page the clicking the menu link.
	 */
	public function handle_external_redirects() {
		if ( empty( $_GET['page'] ) ) {
			return;
		}

		if ( 'go_theme_settings' === $_GET['page'] ) {
			$kit = Elementor_Plugin::$instance->kits_manager->get_active_kit();

			if ( empty( $kit->get_id() ) ) {
				$created_default_kit = Elementor_Plugin::$instance->kits_manager->create_default();

				if ( $created_default_kit ) {
					update_option( Elementor_Kits_Manager::OPTION_ACTIVE, $created_default_kit );
				}
			}

			$active_kit_id = Elementor_Plugin::$instance->kits_manager->get_active_id();

			wp_redirect( admin_url( "post.php?post={$active_kit_id}&action=elementor" ) );

			die;
		}
	}

	public function menu_order( $menu_order ) {
		$cmsmasters_menu_order = [];

		// Get the index of our custom separator.
		$cmsmasters_separator = array_search( 'separator-cmsmasters', $menu_order, true );

		// Loop through menu order and do some rearranging.
		foreach ( $menu_order as $item ) {
			if ( Options_Utils::MENU_SLUG === $item ) {
				$cmsmasters_menu_order[] = 'separator-cmsmasters';
				$cmsmasters_menu_order[] = $item;

				unset( $menu_order[ $cmsmasters_separator ] );
			} elseif ( ! in_array( $item, [ 'separator-cmsmasters' ], true ) ) {
				$cmsmasters_menu_order[] = $item;
			}
		}

		// Return order.
		return $cmsmasters_menu_order;
	}

	/**
	 * Enqueue page assets.
	 */
	public function enqueue_page_assets() {
		// Styles
		wp_enqueue_style(
			'stereo-bank-options',
			File_Manager::get_css_assets_url( 'options', null, 'default', true ),
			array(),
			'1.0.0',
			'screen'
		);

		// Scripts
		wp_enqueue_script(
			'stereo-bank-options',
			File_Manager::get_js_assets_url( 'options' ),
			array( 'jquery' ),
			'1.0.0',
			true
		);

		wp_localize_script( 'stereo-bank-options', 'cmsmasters_options', array(
			'nonce' => wp_create_nonce( 'cmsmasters_options_nonce' ),
			'apply_demo_question' => "\n" . esc_html__( 'Apply the selected design to this website?', 'stereo-bank' ) . ( 'demos' === Theme_Config::IMPORT_TYPE ? "\n\n" . esc_html__( 'This adds a templates pack and some other data to your website.', 'stereo-bank' ) : '' ),
		) );
	}

	/**
	 * Render page.
	 */
	public function render_page() {
		echo '<div class="wrap ' . esc_attr( $this->class_prefix ) . '">' .
			'<h2 class="' . esc_attr( $this->class_prefix ) . '-header">' . esc_html( $this->pages[ $this->page ]['page_title'] ) . '</h2>';

		$this->render_content();

		echo '</div>';
	}

	/**
	 * Render page content.
	 */
	public function render_content() {
		if ( method_exists( $this->page_class, 'render_content' ) ) {
			$this->page_class->render_content();

			return;
		}

		if ( empty( $this->fields ) ) {
			return false;
		}

		$this->tabs_content();

		echo '<form action="options.php" method="post" class="' . esc_attr( $this->class_prefix ) . '-form">';

		settings_fields( 'cmsmasters_stereo-bank_options' );

		foreach ( $this->sections as $key => $args ) {
			$active_class = ( $this->default_section === $key ? ' cmsmasters-active' : '' );

			echo '<div id="cmsmasters-section-' . esc_attr( $key ) . '" class="' . esc_attr( $this->class_prefix ) . '-section' . esc_attr( $active_class ) . '">';

			if ( ! empty( $args['title'] ) ) {
				echo "<h3>{$args['title']}</h3>";
			}

			echo '<table class="form-table">';

			do_settings_fields( __FILE__, $this->section_prefix . $key );

			echo '</table>' .
			'</div>';
		}

		if ( property_exists( $this->page_class, 'submit' ) && 'hide' === $this->page_class->submit ) {
			echo '';
		} else {
			submit_button();
		}

		echo '</form>';
	}

	/**
	 * Navigation tabs content.
	 */
	public function tabs_content() {
		if ( 1 >= count( $this->sections ) ) {
			return false;
		}

		$links = '';

		foreach ( $this->sections as $key => $args ) {
			$active_class = ( $this->default_section === $key ? ' nav-tab-active' : '' );

			$links .= '<a id="cmsmasters-section-' . esc_attr( $key ) . '-link" class="nav-tab' . $active_class . '" href="#cmsmasters-section-' . esc_attr( $key ) . '">' . esc_html( $args['label'] ) . '</a>';
		}

		if ( '' !== $links ) {
			echo '<div class="' . esc_attr( $this->class_prefix ) . '-tabs-nav nav-tab-wrapper">' . wp_kses_post( $links ) . '</div>';
		}
	}

	/**
	 * Register options.
	 */
	public function register_options() {
		if ( empty( $this->fields ) ) {
			return false;
		}

		register_setting( 'cmsmasters_stereo-bank_options', 'cmsmasters_stereo-bank_options', array( $this, 'validate_options' ) );

		foreach ( $this->sections as $key => $args ) {
			add_settings_section( $this->section_prefix . $key, $args['title'], '__return_empty_string', __FILE__ );
		}

		foreach ( $this->fields as $key => $args ) {
			$this->create_field( $args );
		}
	}

	/**
	 * Validate options.
	 *
	 * @param array $input Options input data.
	 */
	public function validate_options( $input ) {
		$options = Utils::get_theme_options();

		foreach ( $this->fields as $field_id => $field_args ) {
			$class_name = __NAMESPACE__ . '\\Fields\\' . ucwords( str_replace( '-', '_', $field_args['type'] ), '_' );

			$id = $field_args['id'];
			$sub_id = $field_args['sub_id'];

			$input_val = Options_Utils::check_validate_input( $id, $sub_id, $input, $field_args );

			if ( ! class_exists( $class_name ) ) {
				if ( '' !== $sub_id ) {
					$options[ $id ][ $sub_id ] = $input_val;
				} else {
					$options[ $id ] = $input_val;
				}

				continue;
			}

			if ( '' !== $sub_id ) {
				$options[ $id ][ $sub_id ] = $class_name::validate( 'cmsmasters_' . $id . '_' . $sub_id, $input_val, $field_args );
			} else {
				$options[ $id ] = $class_name::validate( 'cmsmasters_' . $id, $input_val, $field_args );
			}
		}

		return $options;
	}

	/**
	 * Create field.
	 *
	 * @param array $args Field args.
	 */
	public function create_field( $args = array() ) {
		$class_name = __NAMESPACE__ . '\\Fields\\' . ucwords( str_replace( '-', '_', $args['type'] ), '_' );

		if ( ! class_exists( $class_name ) ) {
			return false;
		}

		$field_args = $args;

		$field_args['id'] = 'cmsmasters_' . $args['id'] . ( '' !== $args['sub_id'] ? '_' . $args['sub_id'] : '' );
		$field_args['name'] = 'cmsmasters_stereo-bank_options' . '[' . $args['id'] . ']' . ( '' !== $args['sub_id'] ? '[' . $args['sub_id'] . ']' : '' );
		$field_args['label_for'] = $field_args['id'];
		$field_args['value'] = Options_Utils::get_field_value( $args['id'], $args['sub_id'], $args['std'] );

		add_settings_field(
			$field_args['id'],
			$field_args['title'],
			array( $class_name, 'render' ),
			__FILE__,
			$field_args['section'],
			$field_args
		);
	}

	/**
	 * Show admin messages.
	 */
	public function admin_messages() {
		if ( '' === $this->page ) {
			return false;
		}

		$errors = get_settings_errors();

		if ( empty( $errors ) && ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		if ( isset( $_GET['settings-updated'] ) && 'settings_updated' === $errors[0]['code'] ) {
			echo Options_Utils::get_message_content( '<p><strong>' . $errors[0]['message'] . '</strong></p>', 'notice-success' );
		} else {
			foreach ( $errors as $error ) {
				echo Options_Utils::get_message_content( $error['message'], 'notice-error', $error['setting'] );
			}
		}
	}

}
