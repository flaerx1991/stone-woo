<?php
namespace StereoBankSpace\Kits\Traits\VarGroupControls;

use Elementor\Group_Control_Border;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Border trait.
 *
 * Allows to use a group control border with css vars.
 */
trait Border {

	/**
	 * Add border group control with css vars.
	 *
	 * @param string $key Control key.
	 * @param array $args Control arguments.
	 */
	protected function var_group_control_border( $key = '', $args = array() ) {
		list( $name, $prefix ) = $this->get_control_parameters( $key, 'border', 'bd' );

		$default_args = array(
			'name' => $name,
			'fields_options' => array(
				'border' => array(
					'options' => array(
						'' => _x( 'Default', 'Border Control', 'stereo-bank' ),
						'none' => _x( 'None', 'Border Control', 'stereo-bank' ),
						'solid' => _x( 'Solid', 'Border Control', 'stereo-bank' ),
						'double' => _x( 'Double', 'Border Control', 'stereo-bank' ),
						'dotted' => _x( 'Dotted', 'Border Control', 'stereo-bank' ),
						'dashed' => _x( 'Dashed', 'Border Control', 'stereo-bank' ),
						'groove' => _x( 'Groove', 'Border Control', 'stereo-bank' ),
					),
					'selectors' => array(
						':root' => "--{$prefix}-style: {{VALUE}};",
					),
				),
				'width' => array(
					'selectors' => array(
						':root' => "--{$prefix}-width-top: {{TOP}}{{UNIT}};" .
							"--{$prefix}-width-right: {{RIGHT}}{{UNIT}};" .
							"--{$prefix}-width-bottom: {{BOTTOM}}{{UNIT}};" .
							"--{$prefix}-width-left: {{LEFT}}{{UNIT}};",
					),
					'condition' => array(
						'border!' => array(
							'',
							'none',
						),
					),
				),
				'color' => array(
					'dynamic' => array(),
					'selectors' => array(
						':root' => "--{$prefix}-color: {{VALUE}};",
					),
					'condition' => array(
						'border!' => 'none',
					),
				),
			),
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array_replace_recursive( $default_args, $args )
		);
	}

}
