<?php
namespace StereoBankSpace\Kits\Traits\ControlsGroups;

use StereoBankSpace\Kits\Controls\Controls_Manager as CmsmastersControls;
use StereoBankSpace\Kits\Settings\Base\Settings_Tab_Base;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Burger Navigation Container trait.
 *
 * Allows to use a group of controls for burger navigation container.
 */
trait Nav_Burger_Container {

	/**
	 * Group of controls for burger navigation container.
	 *
	 * @param string $key Controls key.
	 * @param array $args Controls args.
	 */
	protected function controls_group_nav_burger_container( $key = '', $args = array() ) {
		list(
			$condition,
			$conditions
		) = $this->get_controls_group_required_args( $args, array(
			'condition' => array(), // Controls condition
			'conditions' => array(), // Controls conditions
		) );

		$default_args = array(
			'condition' => $condition,
			'conditions' => $conditions,
		);

		$this->add_var_group_control(
			$this->get_control_name_parameter( $key ),
			Settings_Tab_Base::VAR_BACKGROUND,
			$default_args
		);

		$this->add_var_group_control(
			$this->get_control_name_parameter( $key ),
			Settings_Tab_Base::VAR_BORDER,
			array_merge_recursive(
				$default_args,
				array(
					'fields_options' => array(
						'width' => array(
							'allowed_dimensions' => array( 'top', 'bottom' ),
							'selectors' => array(
								':root' => '--' . $this->get_control_prefix_parameter( $key, 'bd_width_top' ) . ': {{TOP}}{{UNIT}};' .
									'--' . $this->get_control_prefix_parameter( $key, 'bd_width_bottom' ) . ': {{BOTTOM}}{{UNIT}};',
							),
						),
					),
				)
			)
		);

		$this->add_responsive_control(
			$this->get_control_name_parameter( $key, 'padding' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => esc_html__( 'Padding', 'stereo-bank' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
					),
					'allowed_dimensions' => array( 'top', 'bottom' ),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( $key, 'padding_top' ) . ': {{TOP}}{{UNIT}};' .
							'--' . $this->get_control_prefix_parameter( $key, 'padding_bottom' ) . ': {{BOTTOM}}{{UNIT}};',
					),
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'alignment' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => esc_html__( 'Items Alignment', 'stereo-bank' ),
					'label_block' => false,
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
					'type' => CmsmastersControls::CHOOSE_TEXT,
					'options' => array(
						'wide' => esc_html__( 'Wide', 'stereo-bank' ),
						'centered' => esc_html__( 'Centered', 'stereo-bank' ),
					),
					'toggle' => false,
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'alignment' ),
						'centered'
					),
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'apply_settings' ),
			array_merge_recursive(
				$default_args,
				array(
					'label_block' => true,
					'show_label' => false,
					'type' => Controls_Manager::BUTTON,
					'text' => esc_html__( 'Save & Reload', 'stereo-bank' ),
					'event' => 'cmsmasters:theme_settings:apply_settings',
					'separator' => 'before',
				)
			)
		);
	}

}
