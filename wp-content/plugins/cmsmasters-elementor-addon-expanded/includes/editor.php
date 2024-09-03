<?php
namespace CmsmastersElementor;

use CmsmastersElementor\Base\Base_App;
use CmsmastersElementor\Editor as EditorFolder;
use CmsmastersElementor\Plugin;

use Elementor\Core\Settings\Manager as SettingsManager;
use Elementor\Settings;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Addon `Editor` class.
 *
 * Addon `Editor` class is responsible for loading scripts and
 * styles needed for the plugin editor.
 *
 * @since 1.0.0
 */
class Editor extends Base_App {

	/**
	 * Get app name.
	 *
	 * Retrieve the name of the application.
	 *
	 * @since 1.0.0
	 *
	 * @return string App name.
	 */
	public function get_name() {
		return 'cmsmasters-editor';
	}

	/**
	 * Ensure editor settings.
	 *
	 * Ensures that the editor `$settings` member is initialized.
	 *
	 * @since 1.0.0
	 *
	 * @return array Editor settings.
	 */
	protected function get_init_settings() {
		$settings = array(
			'debug' => defined( 'WP_DEBUG' ) && WP_DEBUG,
			'i18n' => array(
				'reload' => __( 'Reload Widget', 'cmsmasters-elementor' ),
			),
		);

		$settings = array_replace_recursive( parent::get_init_settings(), $settings );

		/**
		 * Editor settings.
		 *
		 * Filters the editor settings.
		 *
		 * @since 1.0.0
		 *
		 * @param array $settings Editor settings.
		 */
		$settings = apply_filters( 'cmsmasters_elementor/editor/settings', $settings );

		return $settings;
	}

	/**
	 * Add actions initialization.
	 *
	 * Register actions for the Editor app.
	 *
	 * @since 1.0.0
	 */
	protected function init_actions() {
		// Editor
		add_action( 'elementor/init', array( $this, 'on_elementor_init' ) );
		add_action( 'elementor/editor/init', array( $this, 'add_editor_templates' ) );

		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_editor_scripts' ) );

		add_action( 'elementor/editor/before_enqueue_styles', array( $this, 'register_editor_styles' ) );
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'enqueue_editor_styles' ) );

		add_action( 'elementor/element/after_add_attributes', array( $this, 'after_add_attributes' ) );

		// Admin
		if ( is_admin() ) {
			add_action( 'elementor/admin/after_create_settings/' . Settings::PAGE_ID, array( $this, 'add_tab_settings' ), 100 );
		}
	}

	/**
	 * On elementor init.
	 *
	 * Change editor notice bar.
	 *
	 * Fired by `elementor/init` Elementor action hook.
	 *
	 * @since 1.0.0
	 */
	public function on_elementor_init() {
		Plugin::elementor()->editor->notice_bar = new EditorFolder\Notice_Bar();
	}

	/**
	 * Add editor templates.
	 *
	 * Add editor script templates.
	 *
	 * Fired by `elementor/editor/init` Elementor action hook.
	 *
	 * @since 1.0.0
	 */
	public function add_editor_templates() {
		Plugin::elementor()->common->add_template( __DIR__ . '/editor/templates/templates.php' );
		Plugin::elementor()->common->add_template( __DIR__ . '/editor/templates/library-templates.php' );
	}

	/**
	 * Enqueue editor scripts.
	 *
	 * Load all required editor scripts.
	 *
	 * Fired by `elementor/editor/before_enqueue_scripts` Elementor action hook.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_editor_scripts() {
		wp_register_script(
			'selectize',
			$this->get_js_assets_url( 'selectize', self::get_lib_src( 'selectize/js' ) ),
			array( 'jquery' ),
			'0.12.6',
			true
		);

		wp_enqueue_script(
			'cmsmasters-elementor',
			$this->get_js_assets_url( 'editor' ),
			array(
				'backbone-marionette',
				'elementor-common',
				'elementor-editor-modules',
				'elementor-editor-document',
				'selectize',
			),
			CMSMASTERS_ELEMENTOR_VERSION,
			true
		);

		$this->print_config( 'cmsmasters-elementor' );
	}

	/**
	 * Register editor styles.
	 *
	 * Register all required editor styles.
	 *
	 * Fired by `elementor/editor/before_enqueue_styles` Elementor action hook.
	 *
	 * @since 1.0.0
	 */
	public function register_editor_styles() {
		wp_register_style(
			'cmsmasters-icons',
			$this->get_css_assets_url( 'cmsmasters-icons', self::get_lib_src( 'cmsicons/css' ) ),
			array(),
			'1.0.0'
		);
	}

	/**
	 * Enqueue editor styles.
	 *
	 * Load all required editor styles.
	 *
	 * Fired by `elementor/editor/after_enqueue_styles` Elementor action hook.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_editor_styles() {
		wp_register_style(
			'selectize',
			$this->get_css_assets_url( 'selectize', self::get_lib_src( 'selectize/css' ) ),
			array(),
			'0.12.6'
		);

		wp_enqueue_style(
			'cmsmasters-elementor',
			$this->get_css_assets_url( 'editor', null, 'default', true ),
			array(
				'elementor-editor',
				'selectize',
				'cmsmasters-icons',
			),
			CMSMASTERS_ELEMENTOR_VERSION
		);

		$ui_theme = SettingsManager::get_settings_managers( 'editorPreferences' )->get_model()->get_settings( 'ui_theme' );

		if ( 'light' !== $ui_theme ) {
			$ui_theme_media_queries = 'all';

			if ( 'auto' === $ui_theme ) {
				$ui_theme_media_queries = '(prefers-color-scheme: dark)';
			}

			wp_enqueue_style(
				'cmsmasters-elementor-dark-mode',
				$this->get_css_assets_url( 'editor-dark-mode', null, 'default' ),
				array( 'elementor-editor' ),
				CMSMASTERS_ELEMENTOR_VERSION,
				$ui_theme_media_queries
			);
		}
	}

	/**
	 * After element attribute rendered.
	 *
	 * Fires after the attributes of the element HTML tag are rendered.
	 *
	 * Fired by `elementor/element/after_add_attributes` Elementor action hook.
	 *
	 * @since 1.0.0
	 *
	 * @param Element_Base $this The element.
	 */
	public function after_add_attributes( $element ) {
		$settings = $element->get_settings_for_display();
		$controls = $element->get_controls();

		$class_settings = array();

		foreach ( $settings as $setting_key => $setting ) {
			if ( isset( $controls[ $setting_key ]['wrapper_class'] ) ) {
				$class_settings[ $setting_key ] = $setting;
			}
		}

		foreach ( $class_settings as $setting_key => $setting ) {
			if ( empty( $setting ) || '0' === $setting ) {
				continue;
			}

			$element->add_render_attribute( '_wrapper', 'class', $controls[ $setting_key ]['wrapper_class'] );
		}
	}

	/**
	 * Register CMSMasters tab in dashboard.
	 *
	 * Fired by `elementor/admin/after_create_settings/elementor` Elementor action hook.
	 *
	 * @since 1.0.0
	 *
	 * @param Settings $settings Elementor "Settings" page in WordPress dashboard.
	 *
	 * @return void
	 */
	public function add_tab_settings( Settings $settings ) {
		$settings->add_tab( 'cmsmasters', array( 'label' => __( 'CMSMasters', 'cmsmasters-elementor' ) ) );
	}

}
