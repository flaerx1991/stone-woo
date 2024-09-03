<?php
namespace StereoBankSpace\Kits\Settings\HeaderMid;

use StereoBankSpace\Kits\Controls\Controls_Manager as CmsmastersControls;
use StereoBankSpace\Kits\Settings\Base\Settings_Tab_Base;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Header Mid settings.
 */
class Header_Mid extends Settings_Tab_Base {

	/**
	 * Get toggle name.
	 *
	 * Retrieve the toggle name.
	 *
	 * @return string Toggle name.
	 */
	public static function get_toggle_name() {
		return 'header_mid';
	}

	/**
	 * Get title.
	 *
	 * Retrieve the toggle title.
	 */
	public function get_title() {
		return esc_html__( 'Header Middle', 'stereo-bank' );
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
			'notice',
			array(
				'raw' => esc_html__( "If you use a 'Header' template, then the settings will not be applied, if you set the template to sitewide, then these settings will be hidden.", 'stereo-bank' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'render_type' => 'ui',
			)
		);

		$this->add_control(
			'type',
			array(
				'label' => esc_html__( 'Type', 'stereo-bank' ),
				'label_block' => false,
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'wide' => array(
						'title' => esc_html__( 'Wide', 'stereo-bank' ),
					),
					'centered' => array(
						'title' => esc_html__( 'Centered', 'stereo-bank' ),
					),
				),
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'type' ),
					'centered'
				),
				'toggle' => false,
			)
		);

		$this->add_control(
			'height',
			array(
				'label' => esc_html__( 'Height', 'stereo-bank' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
				),
				'size_units' => array(
					'px',
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'height' ) . ': {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'content_element_heading_control',
			array(
				'label' => esc_html__( 'Content Element', 'stereo-bank' ),
				'type' => Controls_Manager::HEADING,
				'condition' => array(
					$this->get_control_id_parameter( '', 'type' ) => 'wide',
				),
			)
		);

		$this->add_control(
			'content_element',
			array(
				'label_block' => true,
				'show_label' => false,
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'none' => esc_html__( 'None', 'stereo-bank' ),
					'info' => esc_html__( 'Short Info', 'stereo-bank' ),
					'html' => esc_html__( 'Custom HTML', 'stereo-bank' ),
					'nav' => esc_html__( 'Navigation', 'stereo-bank' ),
				),
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'content_element' ),
					'none'
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'type' ) => 'wide',
				),
			)
		);

		$this->add_responsive_control(
			'content_element_gap',
			array(
				'label' => esc_html__( 'Gap Between', 'stereo-bank' ),
				'description' => esc_html__( 'Gap between content and additional content', 'stereo-bank' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => -10,
						'max' => 100,
					),
					'%' => array(
						'min' => -1,
						'max' => 100,
					),
					'vw' => array(
						'min' => -1,
						'max' => 10,
					),
				),
				'size_units' => array(
					'px',
					'%',
					'vw',
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'content_element_gap' ) . ': {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'type' ) => 'wide',
					$this->get_control_id_parameter( '', 'content_element!' ) => '',
				),
			)
		);

		$this->add_control(
			'add_content_elements_heading_control',
			array(
				'label' => esc_html__( 'Additional Content Elements Order', 'stereo-bank' ),
				'type' => Controls_Manager::HEADING,
				'condition' => array(
					$this->get_control_id_parameter( '', 'type' ) => 'wide',
				),
			)
		);

		$this->add_control(
			'add_content_elements',
			array(
				'label_block' => true,
				'show_label' => false,
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
				'type' => CmsmastersControls::SELECTIZE,
				'options' => array(
					'social' => esc_html__( 'Social Icons', 'stereo-bank' ),
					'button' => esc_html__( 'Button', 'stereo-bank' ),
					'search_button' => esc_html__( 'Search Button', 'stereo-bank' ),
				),
				'multiple' => true,
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'add_content_elements' ),
					array()
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'type' ) => 'wide',
				),
			)
		);

		$this->add_responsive_control(
			'add_content_elements_gap',
			array(
				'label' => esc_html__( 'Gap Between', 'stereo-bank' ),
				'description' => esc_html__( 'Gap between additional content items', 'stereo-bank' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => -10,
						'max' => 100,
					),
					'%' => array(
						'min' => -1,
						'max' => 100,
					),
					'vw' => array(
						'min' => -1,
						'max' => 10,
					),
				),
				'size_units' => array(
					'px',
					'%',
					'vw',
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'add_content_elements_gap' ) . ': {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'type' ) => 'wide',
					$this->get_control_id_parameter( '', 'add_content_elements!' ) => '',
				),
			)
		);

		$this->add_responsive_control(
			'z_index',
			array(
				'label' => esc_html__( 'Z-Index', 'stereo-bank' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'z_index' ) . ': {{VALUE}};',
				),
			)
		);

		$this->add_controls_group( 'container', self::CONTROLS_CONTAINER );

		$this->add_controls_group( 'content', self::CONTROLS_CONTENT );

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
