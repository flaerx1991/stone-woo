<?php
namespace StereoBankSpace\Kits\Settings\Main;

use StereoBankSpace\Kits\Controls\Controls_Manager as CmsmastersControls;
use StereoBankSpace\Kits\Settings\Base\Settings_Tab_Base;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Main settings.
 */
class Main extends Settings_Tab_Base {

	/**
	 * Get toggle name.
	 *
	 * Retrieve the toggle name.
	 *
	 * @return string Toggle name.
	 */
	public static function get_toggle_name() {
		return 'main';
	}

	/**
	 * Get title.
	 *
	 * Retrieve the toggle title.
	 */
	public function get_title() {
		return esc_html__( 'Main', 'stereo-bank' );
	}

	/**
	 * Get control ID prefix.
	 *
	 * Retrieve the control ID prefix.
	 *
	 * @return string Control ID prefix.
	 */
	protected static function get_control_id_prefix() {
		$toggle_name = self::get_toggle_name();

		return parent::get_control_id_prefix() . "_{$toggle_name}";
	}

	/**
	 * Register toggle controls.
	 *
	 * Registers the controls of the kit settings tab toggle.
	 */
	protected function register_toggle_controls() {
		$this->add_control(
			'layout',
			array(
				'label' => esc_html__( 'Layout', 'stereo-bank' ),
				'label_block' => false,
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'l-sidebar' => array(
						'title' => esc_html__( 'Left', 'stereo-bank' ),
						'description' => esc_html__( 'Left Sidebar', 'stereo-bank' ),
					),
					'fullwidth' => array(
						'title' => esc_html__( 'Full', 'stereo-bank' ),
						'description' => esc_html__( 'Full Width', 'stereo-bank' ),
					),
					'r-sidebar' => array(
						'title' => esc_html__( 'Right', 'stereo-bank' ),
						'description' => esc_html__( 'Right Sidebar', 'stereo-bank' ),
					),
				),
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'layout' ),
					'r-sidebar'
				),
				'toggle' => false,
			)
		);

		$this->add_control(
			'content_sidebar_heading_control',
			array(
				'label' => esc_html__( 'Content', 'stereo-bank' ),
				'type' => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'content_sidebar_width',
			array(
				'label' => esc_html__( 'Width', 'stereo-bank' ),
				'description' => esc_html__( 'This value will be used for page layouts with sidebar.', 'stereo-bank' ) . '<br />' . esc_html__( 'The width of the sidebar will be equal "100% - this value".', 'stereo-bank' ) . '<br />' . esc_html__( 'For example: 74% - content width, then sidebar width will be 26%.', 'stereo-bank' ) . '<br />' . esc_html__( 'Default value is 74%.', 'stereo-bank' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range' => array(
					'%' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'content_sidebar_width' ) . ': {{SIZE}}%;',
				),
			)
		);

		$this->add_control(
			'sidebar_heading_control',
			array(
				'label' => esc_html__( 'Sidebar', 'stereo-bank' ),
				'type' => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'sidebar_gap',
			array(
				'label' => esc_html__( 'Gap', 'stereo-bank' ),
				'description' => esc_html__( 'Gap between content and sidebar.', 'stereo-bank' ) . '<br />' . esc_html__( 'Default value is 40px.', 'stereo-bank' ) . '<br />' . esc_html__( 'Note: This gap reduces the width of the sidebar.', 'stereo-bank' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
					'%' => array(
						'min' => 0,
						'max' => 10,
					),
					'vw' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'size_units' => array(
					'px',
					'%',
					'vw',
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'sidebar_gap' ) . ': {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'sidebar_divider_type',
			array(
				'label' => _x( 'Divider Type', 'Divider Control', 'stereo-bank' ),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'' => _x( 'Default', 'Divider Control', 'stereo-bank' ),
					'none' => _x( 'None', 'Divider Control', 'stereo-bank' ),
					'solid' => _x( 'Solid', 'Divider Control', 'stereo-bank' ),
					'double' => _x( 'Double', 'Divider Control', 'stereo-bank' ),
					'dotted' => _x( 'Dotted', 'Divider Control', 'stereo-bank' ),
					'dashed' => _x( 'Dashed', 'Divider Control', 'stereo-bank' ),
					'groove' => _x( 'Groove', 'Divider Control', 'stereo-bank' ),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'sidebar_divider_type' ) . ': {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'sidebar_divider_width',
			array(
				'label' => _x( 'Width', 'Divider Control', 'stereo-bank' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'size_units' => array(
					'px',
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'sidebar_divider_width' ) . ': {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'sidebar_divider_type!' ) => array(
						'',
						'none',
					),
				),
			)
		);

		$this->add_control(
			'sidebar_divider_color',
			array(
				'label' => _x( 'Color', 'Divider Control', 'stereo-bank' ),
				'type' => Controls_Manager::COLOR,
				'dynamic' => array(),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'sidebar_divider_color' ) . ': {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'sidebar_divider_type!' ) => 'none',
				),
			)
		);

		$this->add_controls_group( 'container', self::CONTROLS_CONTAINER );

		$this->add_controls_group( 'content', self::CONTROLS_CONTENT, array(
			'elementor_padding' => true,
		) );

		$this->add_control(
			'apply_settings',
			array(
				'label_block' => true,
				'show_label' => false,
				'type' => Controls_Manager::BUTTON,
				'text' => esc_html__( 'Save & Reload', 'stereo-bank' ),
				'event' => 'cmsmasters:theme_settings:apply_settings',
				'separator' => 'before',
			)
		);
	}

}
