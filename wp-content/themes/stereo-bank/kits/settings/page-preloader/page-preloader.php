<?php
namespace StereoBankSpace\Kits\Settings\PagePreloader;

use StereoBankSpace\Kits\Settings\Base\Settings_Tab_Base;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Page Preloader settings.
 */
class Page_Preloader extends Settings_Tab_Base {

	/**
	 * Get toggle name.
	 *
	 * Retrieve the toggle name.
	 *
	 * @return string Toggle name.
	 */
	public static function get_toggle_name() {
		return 'page_preloader';
	}

	/**
	 * Get title.
	 *
	 * Retrieve the toggle title.
	 */
	public function get_title() {
		return esc_html__( 'Page Preloader', 'stereo-bank' );
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
			'visibility',
			array(
				'label' => esc_html__( 'Visibility', 'stereo-bank' ),
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'stereo-bank' ),
				'label_on' => esc_html__( 'Show', 'stereo-bank' ),
				'default' => 'no',
			)
		);

		$this->add_control(
			'container_heading_control',
			array(
				'label' => __( 'Container', 'stereo-bank' ),
				'type' => Controls_Manager::HEADING,
				'condition' => array(
					$this->get_control_id_parameter( '', 'visibility' ) => 'yes',
				),
			)
		);

		$this->add_var_group_control( '', self::VAR_BACKGROUND, array(
			'condition' => array(
				$this->get_control_id_parameter( '', 'visibility' ) => 'yes',
			),
		) );

		$this->add_responsive_control(
			'entrance_animation',
			array(
				'label' => esc_html__( 'Entrance Animation', 'stereo-bank' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'options' => array(
					'fade-out' => esc_html__( 'Fade In', 'stereo-bank' ),
					'fade-out-down' => esc_html__( 'Fade In Down', 'stereo-bank' ),
					'fade-out-right' => esc_html__( 'Fade In Right', 'stereo-bank' ),
					'fade-out-up' => esc_html__( 'Fade In Up', 'stereo-bank' ),
					'fade-out-left' => esc_html__( 'Fade In Left', 'stereo-bank' ),
					'zoom-out' => esc_html__( 'Zoom In', 'stereo-bank' ),
					'slide-out-down' => esc_html__( 'Slide In Down', 'stereo-bank' ),
					'slide-out-right' => esc_html__( 'Slide In Right', 'stereo-bank' ),
					'slide-out-up' => esc_html__( 'Slide In Up', 'stereo-bank' ),
					'slide-out-left' => esc_html__( 'Slide In Left', 'stereo-bank' ),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'entrance_animation' ) . ': cmsmasters-page-preloader-transition-{{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'visibility' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'exit_animation',
			array(
				'label' => esc_html__( 'Exit Animation', 'stereo-bank' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'options' => array(
					'fade-in' => esc_html__( 'Fade Out', 'stereo-bank' ),
					'fade-in-down' => esc_html__( 'Fade Out Down', 'stereo-bank' ),
					'fade-in-right' => esc_html__( 'Fade Out Right', 'stereo-bank' ),
					'fade-in-up' => esc_html__( 'Fade Out Up', 'stereo-bank' ),
					'fade-in-left' => esc_html__( 'Fade Out Left', 'stereo-bank' ),
					'zoom-in' => esc_html__( 'Zoom Out', 'stereo-bank' ),
					'slide-in-down' => esc_html__( 'Slide Out Down', 'stereo-bank' ),
					'slide-in-right' => esc_html__( 'Slide Out Right', 'stereo-bank' ),
					'slide-in-up' => esc_html__( 'Slide Out Up', 'stereo-bank' ),
					'slide-in-left' => esc_html__( 'Slide Out Left', 'stereo-bank' ),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'exit_animation' ) . ': cmsmasters-page-preloader-transition-{{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'visibility' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'animation_duration',
			array(
				'label' => esc_html__( 'Animation Duration', 'stereo-bank' ) . ' (ms)',
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 5000,
						'step' => 50,
					),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'animation_duration' ) . ': {{SIZE}}ms;',
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'visibility' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'preloader_divider_control',
			array(
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
				'condition' => array(
					$this->get_control_id_parameter( '', 'visibility' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'preloader_heading_control',
			array(
				'label' => __( 'Preloader', 'stereo-bank' ),
				'type' => Controls_Manager::HEADING,
				'condition' => array(
					$this->get_control_id_parameter( '', 'visibility' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'preloader_type',
			array(
				'label' => esc_html__( 'Type', 'stereo-bank' ),
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'none' => esc_html__( 'None', 'stereo-bank' ),
					'animation' => esc_html__( 'Animation', 'stereo-bank' ),
					'icon' => esc_html__( 'Icon', 'stereo-bank' ),
					'image' => esc_html__( 'Image', 'stereo-bank' ),
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'visibility' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'preloader_icon',
			array(
				'label' => esc_html__( 'Icon', 'stereo-bank' ),
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
				'type' => Controls_Manager::ICONS,
				'default' => array(
					'value' => 'fas fa-spinner',
					'library' => 'fa-solid',
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'visibility' ) => 'yes',
					$this->get_control_id_parameter( '', 'preloader_type' ) => 'icon',
				),
			)
		);

		$this->add_control(
			'preloader_image',
			array(
				'label' => esc_html__( 'Image', 'stereo-bank' ),
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => array(
					$this->get_control_id_parameter( '', 'visibility' ) => 'yes',
					$this->get_control_id_parameter( '', 'preloader_type' ) => 'image',
				),
			)
		);

		$this->add_control(
			'preloader_animation_type',
			array(
				'label' => esc_html__( 'Animation', 'stereo-bank' ),
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'circle' => esc_html__( 'Circle', 'stereo-bank' ),
					'circle-dashed' => esc_html__( 'Circle Dashed', 'stereo-bank' ),
					'bouncing-dots' => esc_html__( 'Bouncing Dots', 'stereo-bank' ),
					'pulsing-dots' => esc_html__( 'Pulsing Dots', 'stereo-bank' ),
					'pulse' => esc_html__( 'Pulse', 'stereo-bank' ),
					'overlap' => esc_html__( 'Overlap', 'stereo-bank' ),
					'spinners' => esc_html__( 'Spinners', 'stereo-bank' ),
					'nested-spinners' => esc_html__( 'Nested Spinners', 'stereo-bank' ),
					'opposing-nested-spinners' => esc_html__( 'Opposing Nested Spinners', 'stereo-bank' ),
					'opposing-nested-rings' => esc_html__( 'Opposing Nested Rings', 'stereo-bank' ),
					'progress-bar' => esc_html__( 'Progress Bar', 'stereo-bank' ),
					'two-way-progress-bar' => esc_html__( 'Two Way Progress Bar', 'stereo-bank' ),
					'repeating-bar' => esc_html__( 'Repeating Bar', 'stereo-bank' ),
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'visibility' ) => 'yes',
					$this->get_control_id_parameter( '', 'preloader_type' ) => 'animation',
				),
			)
		);

		$this->add_control(
			'preloader_animation',
			array(
				'label' => esc_html__( 'Animation', 'stereo-bank' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					'' => esc_html__( 'None', 'stereo-bank' ),
					'eicon-spin' => esc_html__( 'Spinning', 'stereo-bank' ),
					'bounce' => esc_html__( 'Bounce', 'stereo-bank' ),
					'flash' => esc_html__( 'Flash', 'stereo-bank' ),
					'pulse' => esc_html__( 'Pulse', 'stereo-bank' ),
					'rubberBand' => esc_html__( 'Rubber Band', 'stereo-bank' ),
					'shake' => esc_html__( 'Shake', 'stereo-bank' ),
					'headShake' => esc_html__( 'Head Shake', 'stereo-bank' ),
					'swing' => esc_html__( 'Swing', 'stereo-bank' ),
					'tada' => esc_html__( 'Tada', 'stereo-bank' ),
					'wobble' => esc_html__( 'Wobble', 'stereo-bank' ),
					'jello' => esc_html__( 'Jello', 'stereo-bank' ),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'preloader_animation' ) . ': {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'visibility' ) => 'yes',
					$this->get_control_id_parameter( '', 'preloader_type' ) => array( 'icon', 'image' ),
				),
			)
		);

		$this->add_control(
			'preloader_animation_duration',
			array(
				'label' => esc_html__( 'Animation Duration', 'stereo-bank' ) . ' (ms)',
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 5000,
						'step' => 50,
					),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'preloader_animation_duration' ) . ': {{SIZE}}ms;',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms' => array(
						array(
							'name' => $this->get_control_id_parameter( '', 'visibility' ),
							'operator' => '=',
							'value' => 'yes',
						),
						array(
							'relation' => 'or',
							'terms' => array(
								array(
									'name' => $this->get_control_id_parameter( '', 'preloader_type' ),
									'operator' => 'in',
									'value' => array(
										'image',
										'icon',
									),
								),
								array(
									'relation' => 'and',
									'terms' => array(
										array(
											'name' => $this->get_control_id_parameter( '', 'preloader_type' ),
											'operator' => '=',
											'value' => 'animation',
										),
										array(
											'name' => $this->get_control_id_parameter( '', 'preloader_animation_type' ),
											'operator' => 'in',
											'value' => array(
												'circle',
												'circle-dashed',
												'bouncing-dots',
												'pulsing-dots',
												'spinners',
												'nested-spinners',
												'opposing-nested-spinners',
												'opposing-nested-rings',
												'progress-bar',
												'two-way-progress-bar',
												'repeating-bar',
											),
										),
									),
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'preloader_color',
			array(
				'label' => esc_html__( 'Color', 'stereo-bank' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'preloader_color' ) . ': {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'visibility' ) => 'yes',
					$this->get_control_id_parameter( '', 'preloader_type' ) => array( 'icon', 'animation' ),
				),
			)
		);

		$this->add_responsive_control(
			'preloader_size',
			array(
				'label' => esc_html__( 'Size', 'stereo-bank' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
						'step' => 1,
					),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'preloader_size' ) . ': {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'visibility' ) => 'yes',
					$this->get_control_id_parameter( '', 'preloader_type' ) => array( 'icon', 'animation' ),
				),
			)
		);

		$this->add_responsive_control(
			'preloader_rotate',
			array(
				'label' => esc_html__( 'Rotate', 'stereo-bank' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'deg', 'grad', 'rad', 'turn' ),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'preloader_rotate' ) . ': {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'visibility' ) => 'yes',
					$this->get_control_id_parameter( '', 'preloader_type' ) => 'icon',
					$this->get_control_id_parameter( '', 'preloader_animation' ) . '!' => array(
						'eicon-spin',
						'bounce',
						'pulse',
						'rubberBand',
						'shake',
						'headShake',
						'swing',
						'tada',
						'wobble',
						'jello',
					),
				),
			)
		);

		$this->add_responsive_control(
			'preloader_width',
			array(
				'label' => esc_html__( 'Width', 'stereo-bank' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range' => array(
					'%' => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'preloader_width' ) . ': {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'visibility' ) => 'yes',
					$this->get_control_id_parameter( '', 'preloader_type' ) => 'image',
				),
			)
		);

		$this->add_responsive_control(
			'preloader_max_width',
			array(
				'label' => esc_html__( 'Max Width', 'stereo-bank' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range' => array(
					'%' => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'preloader_max_width' ) . ': {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'visibility' ) => 'yes',
					$this->get_control_id_parameter( '', 'preloader_type' ) => 'image',
				),
			)
		);

		$this->add_responsive_control(
			'preloader_opacity',
			array(
				'label' => esc_html__( 'Opacity', 'stereo-bank' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 1,
						'step' => .1,
					),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'preloader_opacity' ) . ': {{SIZE}};',
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'visibility' ) => 'yes',
					$this->get_control_id_parameter( '', 'preloader_type' ) => 'image',
				),
			)
		);

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
