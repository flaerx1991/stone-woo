<?php
namespace StereoBankSpace\Kits\Traits\ControlsGroups;

use StereoBankSpace\Kits\Controls\Controls_Manager as CmsmastersControls;
use StereoBankSpace\Kits\Settings\Base\Settings_Tab_Base;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Post Meta trait.
 *
 * Allows to use a group of controls for post meta.
 */
trait Post_Meta {

	/**
	 * Group of controls for post meta.
	 *
	 * @param string $key Controls key.
	 * @param array $args Controls args.
	 */
	protected function controls_group_post_meta( $key = '', $args = array() ) {
		list(
			$color_tabs,
			$elements_default,
			$condition,
			$conditions
		) = $this->get_controls_group_required_args( $args, array(
			'color_tabs' => true, // Color controls in tabs
			'elements_default' => array(
				'categories',
				'date',
			), // Elements control default
			'condition' => array(), // Controls condition
			'conditions' => array(), // Controls conditions
		) );

		$default_args = array(
			'condition' => $condition,
			'conditions' => $conditions,
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'elements' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => esc_html__( 'Elements', 'stereo-bank' ),
					'label_block' => true,
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
					'type' => CmsmastersControls::SELECTIZE,
					'options' => array(
						'categories' => esc_html__( 'Categories', 'stereo-bank' ),
						'author' => esc_html__( 'Author', 'stereo-bank' ),
						'date' => esc_html__( 'Date', 'stereo-bank' ),
						'comments' => esc_html__( 'Comments', 'stereo-bank' ),
						'tags' => esc_html__( 'Tags', 'stereo-bank' ),
					),
					'multiple' => true,
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'elements' ),
						$elements_default
					),
				)
			)
		);

		$this->add_responsive_control(
			$this->get_control_name_parameter( $key, 'elements_gap' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => esc_html__( 'Gap Between', 'stereo-bank' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'size_units' => array( 'px' ),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( $key, 'elements_gap' ) . ': {{SIZE}}{{UNIT}};',
					),
				)
			)
		);

		$this->add_var_group_control(
			$this->get_control_name_parameter( $key ),
			Settings_Tab_Base::VAR_TYPOGRAPHY,
			$default_args
		);

		$color_controls = array(
			'colors_text' => esc_html__( 'Text', 'stereo-bank' ),
			'colors_link' => esc_html__( 'Link', 'stereo-bank' ),
			'colors_hover' => esc_html__( 'Link Hover', 'stereo-bank' ),
		);

		if ( $color_tabs ) {
			$this->start_controls_tabs(
				$this->get_control_name_parameter( $key, 'colors_tabs' )
			);

			foreach ( $color_controls as $control_id => $control_label ) {
				$this->start_controls_tab(
					$this->get_control_name_parameter( $key, "{$control_id}_tab" ),
					array( 'label' => $control_label )
				);

				$this->add_control(
					$this->get_control_name_parameter( $key, $control_id ),
					array_merge_recursive(
						$default_args,
						array(
							'label' => esc_html__( 'Color', 'stereo-bank' ),
							'type' => Controls_Manager::COLOR,
							'dynamic' => array(),
							'selectors' => array(
								':root' => '--' . $this->get_control_prefix_parameter( $key, $control_id ) . ': {{VALUE}};',
							),
						)
					)
				);

				$this->end_controls_tab();
			}

			$this->end_controls_tabs();
		} else {
			$this->add_control(
				$this->get_control_name_parameter( $key, 'colors_heading_control' ),
				array_merge_recursive(
					$default_args,
					array(
						'label' => esc_html__( 'Colors', 'stereo-bank' ),
						'type' => Controls_Manager::HEADING,
						'separator' => 'before',
					)
				)
			);

			foreach ( $color_controls as $control_id => $control_label ) {
				$this->add_control(
					$this->get_control_name_parameter( $key, $control_id ),
					array_merge_recursive(
						$default_args,
						array(
							'label' => $control_label,
							'type' => Controls_Manager::COLOR,
							'dynamic' => array(),
							'selectors' => array(
								':root' => '--' . $this->get_control_prefix_parameter( $key, $control_id ) . ': {{VALUE}};',
							),
						)
					)
				);
			}
		}

		$this->add_control(
			$this->get_control_name_parameter( $key, 'divider_toggle' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => esc_html__( 'Divider', 'stereo-bank' ),
					'type' => Controls_Manager::POPOVER_TOGGLE,
					'separator' => 'before',
				)
			)
		);

		$this->start_popover();

		$default_popover_args = array_merge_recursive(
			$default_args,
			array(
				'condition' => array( $this->get_control_id_parameter( $key, 'divider_toggle' ) => 'yes' ),
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'divider_visibility' ),
			array_merge_recursive(
				$default_popover_args,
				array(
					'label' => esc_html__( 'Visibility', 'stereo-bank' ),
					'label_block' => false,
					'type' => CmsmastersControls::CHOOSE_TEXT,
					'options' => array(
						'none' => esc_html__( 'Hide', 'stereo-bank' ),
						'block' => esc_html__( 'Show', 'stereo-bank' ),
					),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( $key, 'divider_visibility' ) . ': {{VALUE}};',
					),
					'toggle' => true,
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'divider_color' ),
			array_merge_recursive(
				$default_popover_args,
				array(
					'label' => esc_html__( 'Color', 'stereo-bank' ),
					'type' => Controls_Manager::COLOR,
					'dynamic' => array(),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( $key, 'divider_color' ) . ': {{VALUE}};',
					),
					'condition' => array(
						$this->get_control_id_parameter( $key, 'divider_visibility' ) => 'block',
					),
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'divider_width' ),
			array_merge_recursive(
				$default_popover_args,
				array(
					'label' => esc_html__( 'Width', 'stereo-bank' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'size_units' => array( 'px' ),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( $key, 'divider_width' ) . ': {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						$this->get_control_id_parameter( $key, 'divider_visibility' ) => 'block',
					),
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'divider_height' ),
			array_merge_recursive(
				$default_popover_args,
				array(
					'label' => esc_html__( 'Height', 'stereo-bank' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'size_units' => array( 'px' ),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( $key, 'divider_height' ) . ': {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						$this->get_control_id_parameter( $key, 'divider_visibility' ) => 'block',
					),
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'divider_bd_radius' ),
			array_merge_recursive(
				$default_popover_args,
				array(
					'label' => esc_html__( 'Border Radius', 'stereo-bank' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
					),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( $key, 'divider_bd_radius' ) . ': {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition' => array(
						$this->get_control_id_parameter( $key, 'divider_visibility' ) => 'block',
					),
				)
			)
		);

		$this->end_popover();

		$this->add_controls_group(
			$this->get_control_name_parameter( $key, 'box' ),
			Settings_Tab_Base::CONTROLS_CONTAINER_BOX,
			array_merge_recursive(
				$default_args,
				array(
					'excludes' => array( 'box_shadow' ),
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
