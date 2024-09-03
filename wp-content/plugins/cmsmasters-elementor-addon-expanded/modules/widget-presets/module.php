<?php
namespace CmsmastersElementor\Modules\WidgetPresets;

use CmsmastersElementor\Base\Base_Module;
use CmsmastersElementor\Modules\WidgetPresets\Core\Presets_Custom;
use CmsmastersElementor\Modules\WidgetPresets\Core\Presets_Native;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Module extends Base_Module {

	const PRESETS_CUSTOM = 'cmsmasters_presets_custom';
	const PRESETS_NATIVE = 'cmsmasters_presets_native';

	/**
	 * Get module name.
	 *
	 * Retrieve the Presets module name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'cmsmasters_widget_presets';
	}

	/**
	 * Add actions initialization.
	 *
	 * Register actions for the Presets module.
	 *
	 * @since 1.0.0
	 * @since 1.6.5 Fixed elementor deprecation.
	 */
	protected function init_actions() {
		add_action( 'elementor/element/common/_section_style/after_section_end', array( $this, 'register_controls_common_widget' ), 1, 1 );
		add_action( 'elementor/controls/register', array( $this, 'register_controls' ) );
	}

	/**
	 * Add filters initialization.
	 *
	 * Register filters for the Presets module.
	 *
	 * @since 1.0.0
	 */
	protected function init_filters() {
		add_filter( 'cmsmasters_elementor/editor/settings', array( $this, 'filter_editor_settings' ) );
		add_filter( 'elementor/document/config', array( $this, 'document_config' ) );
	}

	/**
	 * Presets module class constructor.
	 *
	 * Initializing the Presets module class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		new Presets_Custom();
		new Presets_Native();
	}

	public function register_controls_common_widget( Widget_Base $element ) {
		$element->start_controls_section(
			'section_cmsmasters_presets',
			array(
				'label' => __( 'Presets', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->start_controls_tabs( 'cmsmasters_tabs_presets' );

		$element->start_controls_tab(
			'cmsmasters_tab_pre_made_presets',
			array(
				'label' => __( 'Pre-Made', 'cmsmasters-elementor' ),
			)
		);

		$element->add_control(
			self::PRESETS_NATIVE,
			array(
				'type' => 'cmsmasters_presets_native',
				'render_type' => 'none',
			)
		);

		$element->end_controls_tab();

		$element->start_controls_tab(
			'cmsmasters_tabs_preset_customs',
			array(
				'label' => __( 'Custom', 'cmsmasters-elementor' ),
			)
		);

		$element->add_control(
			self::PRESETS_CUSTOM,
			array(
				'type' => 'cmsmasters_presets_custom',
				'render_type' => 'none',
			)
		);

		$element->end_controls_tab();

		$element->end_controls_tabs();

		$element->end_controls_section();
	}

	/**
	 * Register Addon control groups.
	 *
	 * This method extends a list of all the supported control groups by initializing
	 * each one of appropriate control group files.
	 *
	 * Fired by `elementor/controls/register` Elementor plugin action hook.
	 *
	 * @since 1.0.0
	 * @since 1.6.5 Fixed elementor deprecation.
	 */
	public function register_controls( $controls_manager ) {
		$classes_name = array(
			'Control_Presets_Custom',
			'Control_Presets_Native',
		);

		foreach ( $classes_name as $class_name ) {
			$class_full_name = __NAMESPACE__ . "\\Controls\\{$class_name}";

			$controls_manager->register( new $class_full_name() );
		}
	}

	/**
	 * Filter editor settings.
	 *
	 * Filters the Addon settings for elementor editor.
	 *
	 * Fired by `cmsmasters_elementor/editor/settings` Addon filter hook.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings Frontend settings.
	 *
	 * @return array Filtered editor settings.
	 */
	public function filter_editor_settings( $settings ) {
		$settings = array_replace_recursive( $settings, array(
			'i18n' => array(
				'cancel' => esc_html__( 'Cancel', 'cmsmasters-elementor' ),
				'delete' => esc_html__( 'Delete', 'cmsmasters-elementor' ),
				'deleting' => esc_html__( 'Deleting', 'cmsmasters-elementor' ),
				'preset_applied' => esc_html__( 'Preset applied.', 'cmsmasters-elementor' ),
				'preset_deleted' => esc_html__( 'Preset has been deleted.', 'cmsmasters-elementor' ),
				'preset_msg_delete_error' => esc_html__( 'Please try again, unable to delete preset.', 'cmsmasters-elementor' ),
				'preset_msg_delete' => esc_html__( 'Do you want that preset to be deleted?', 'cmsmasters-elementor' ),
			),
		) );

		return $settings;
	}

	public function document_config( $config ) {
		$config = array_replace_recursive( $config, array(
			'widget_presets' => array(
				'controls' => array(
					'native' => self::PRESETS_NATIVE,
					'custom' => self::PRESETS_CUSTOM,
				),
			),
		) );

		return $config;
	}

}
