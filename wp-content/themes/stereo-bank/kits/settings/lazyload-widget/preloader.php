<?php
namespace StereoBankSpace\Kits\Settings\LazyloadWidget;

use StereoBankSpace\Kits\Settings\Base\Settings_Tab_Base;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * LazyLoad Widget Preloader settings.
 */
class Preloader extends Settings_Tab_Base {

	/**
	 * Get toggle name.
	 *
	 * Retrieve the toggle name.
	 *
	 * @return string Toggle name.
	 */
	public static function get_toggle_name() {
		return 'lazyload_widget_preloader';
	}

	/**
	 * Get title.
	 *
	 * Retrieve the toggle title.
	 */
	public function get_title() {
		return esc_html__( 'Preloader', 'stereo-bank' );
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
			'icon_heading_control',
			array(
				'label' => __( 'Icon', 'stereo-bank' ),
				'type' => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'icon',
			array(
				'label' => __( 'Icon', 'stereo-bank' ),
				'label_block' => false,
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'default' => array(
					'value' => 'fas fa-spinner',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label' => esc_html__( 'Icon Size', 'stereo-bank' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'max' => 100,
						'min' => 0,
					),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'icon_size' ) . ': {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label' => esc_html__( 'Icon Color', 'stereo-bank' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'icon_color' ) . ': {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_animation_speed',
			array(
				'label' => esc_html__( 'Icon Animation Speed', 'stereo-bank' ) . ' (ms)',
				'type' => Controls_Manager::NUMBER,
				'step' => 100,
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'icon_animation_speed' ) . ': {{VALUE}}ms;',
				),
			)
		);

		$this->add_control(
			'container_divider_control',
			array(
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			)
		);

		$this->add_control(
			'container_heading_control',
			array(
				'label' => __( 'Container', 'stereo-bank' ),
				'type' => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label' => __( 'Height', 'stereo-bank' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vw', 'vh' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
					'vw' => array(
						'min' => 0,
						'max' => 100,
					),
					'vh' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'height' ) . ': {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_var_group_control( '', self::VAR_BACKGROUND );

		$this->add_var_group_control( 'overlay', self::VAR_BACKGROUND, array(
			'fields_options' => array(
				'background' => array(
					'label' => esc_html_x( 'Overlay Background Type', 'Background Control', 'stereo-bank' ),
				),
			),
			'separator' => 'before',
		) );

		$this->add_var_group_control( '', self::VAR_BORDER, array(
			'fields_options' => array(
				'width' => array(
					'label' => esc_html__( 'Border Width', 'stereo-bank' ),
				),
				'color' => array(
					'label' => esc_html__( 'Border Color', 'stereo-bank' ),
					'condition' => array(
						'border!' => array(
							'',
							'none',
						),
					),
				),
			),
			'separator' => 'before',
		)  );

		$this->add_control(
			'bd_radius',
			array(
				'label' => esc_html__( 'Border Radius', 'stereo-bank' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range' => array(
					'px' => array(
						'max' => 100,
						'min' => 0,
					),
					'%' => array(
						'max' => 50,
						'min' => 0,
					),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'bd_radius' ) . ': {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_var_group_control( '', self::VAR_BOX_SHADOW );
	}

}
