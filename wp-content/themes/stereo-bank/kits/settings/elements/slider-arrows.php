<?php
namespace StereoBankSpace\Kits\Settings\Elements;

use StereoBankSpace\Core\Utils\Utils;
use StereoBankSpace\Kits\Controls\Controls_Manager as CmsmastersControls;
use StereoBankSpace\Kits\Settings\Base\Settings_Tab_Base;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Slider Arrows settings.
 */
class Slider_Arrows extends Settings_Tab_Base {

	/**
	 * Get toggle name.
	 *
	 * Retrieve the toggle name.
	 *
	 * @return string Toggle name.
	 */
	public static function get_toggle_name() {
		return 'slider_arrows';
	}

	/**
	 * Get title.
	 *
	 * Retrieve the toggle title.
	 */
	public function get_title() {
		return esc_html__( 'Slider Arrows', 'stereo-bank' );
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
				'raw' => esc_html__( 'Used in: more posts, single post gallery, archive post gallery.', 'stereo-bank' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'render_type' => 'ui',
			)
		);

		$this->add_control(
			'visibility',
			array(
				'label' => esc_html__( 'Visibility', 'stereo-bank' ),
				'label_block' => false,
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'always' => esc_html__( 'Always', 'stereo-bank' ),
					'hover' => esc_html__( 'On Hover', 'stereo-bank' ),
				),
				'toggle' => false,
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'visibility' ),
					'always'
				),
			)
		);

		$this->start_controls_tabs( 'tabs' );

		foreach ( array(
			'prev' => esc_html__( 'Previous', 'stereo-bank' ),
			'next' => esc_html__( 'Next', 'stereo-bank' ),
		) as $key => $label ) {
			$is_prev = 'prev' === $key;

			$this->start_controls_tab(
				"{$key}_tab",
				array( 'label' => $label )
			);

			$this->add_control(
				"{$key}_text",
				array(
					'label' => esc_html__( 'Text', 'stereo-bank' ),
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
					'type' => Controls_Manager::TEXT,
				)
			);

			$this->add_control(
				"{$key}_icon",
				array(
					'label' => esc_html__( 'Icon', 'stereo-bank' ),
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
					'type' => Controls_Manager::ICONS,
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( '', "{$key}_icon" ),
						array(
							'value' => $is_prev ? 'fas fa-chevron-left' : 'fas fa-chevron-right',
							'library' => 'fa-solid',
						)
					),
				)
			);

			$this->add_control(
				"{$key}_icon_position",
				array(
					'label' => esc_html__( 'Icon Position', 'stereo-bank' ),
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
					'type' => Controls_Manager::SELECT,
					'options' => array(
						'before' => esc_html__( 'Before', 'stereo-bank' ),
						'after' => esc_html__( 'After', 'stereo-bank' ),
					),
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( '', "{$key}_icon_position" ),
						$is_prev ? 'before' : 'after'
					),
					'condition' => array(
						$this->get_control_id_parameter( '', "{$key}_text!" ) => '',
						$this->get_control_id_parameter( '', "{$key}_icon[value]!" ) => '',
					),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$conditions_icon = array(
			'relation' => 'or',
			'terms' => array(
				array(
					'name' => $this->get_control_id_parameter( '', 'prev_icon[value]' ),
					'operator' => '!=',
					'value' => '',
				),
				array(
					'name' => $this->get_control_id_parameter( '', 'next_icon[value]' ),
					'operator' => '!=',
					'value' => '',
				),
			),
		);

		$conditions_text = array(
			'relation' => 'or',
			'terms' => array(
				array(
					'name' => $this->get_control_id_parameter( '', 'prev_text' ),
					'operator' => '!=',
					'value' => '',
				),
				array(
					'name' => $this->get_control_id_parameter( '', 'next_text' ),
					'operator' => '!=',
					'value' => '',
				),
			),
		);

		$conditions_icon_gap = array(
			'relation' => 'and',
			'terms' => array(
				$conditions_icon,
				$conditions_text,
			),
		);

		$this->add_control(
			'text_direction',
			array(
				'label' => esc_html__( 'Text Direction', 'stereo-bank' ),
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'horizontal' => esc_html__( 'Horizontal', 'stereo-bank' ),
					'vertical' => esc_html__( 'Vertical', 'stereo-bank' ),
				),
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'text_direction' ),
					'horizontal'
				),
				'conditions' => $conditions_text,
			)
		);

		$this->add_var_group_control( 'text', self::VAR_TYPOGRAPHY, array(
			'conditions' => $conditions_text,
		) );

		$this->start_controls_tabs( 'states_tabs' );

		foreach ( array(
			'normal' => esc_html__( 'Normal', 'stereo-bank' ),
			'hover' => esc_html__( 'Hover', 'stereo-bank' ),
		) as $key => $label ) {
			$this->start_controls_tab(
				"states_{$key}_tab",
				array( 'label' => $label )
			);

			$this->add_control(
				"{$key}_colors_icon",
				array(
					'label' => esc_html__( 'Icon', 'stereo-bank' ),
					'type' => Controls_Manager::COLOR,
					'dynamic' => array(),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( '', "{$key}_colors_icon" ) . ': {{VALUE}};',
					),
					'conditions' => $conditions_icon,
				)
			);

			$this->add_control(
				"{$key}_colors_bg",
				array(
					'label' => esc_html__( 'Background', 'stereo-bank' ),
					'type' => Controls_Manager::COLOR,
					'dynamic' => array(),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( '', "{$key}_colors_bg" ) . ': {{VALUE}};',
					),
				)
			);

			$this->add_control(
				"{$key}_colors_text",
				array(
					'label' => esc_html__( 'Text', 'stereo-bank' ),
					'type' => Controls_Manager::COLOR,
					'dynamic' => array(),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( '', "{$key}_colors_text" ) . ': {{VALUE}};',
					),
					'conditions' => $conditions_text,
				)
			);

			$this->add_control(
				"{$key}_colors_bd",
				array(
					'label' => esc_html__( 'Border', 'stereo-bank' ),
					'type' => Controls_Manager::COLOR,
					'dynamic' => array(),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( '', "{$key}_colors_bd" ) . ': {{VALUE}};',
					),
					'condition' => array(
						$this->get_control_id_parameter( '', 'border_border!' ) => 'none',
					),
				)
			);

			$this->add_var_group_control( $key, self::VAR_BOX_SHADOW );

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->add_var_group_control( '', self::VAR_BORDER, array(
			'fields_options' => array(
				'width' => array( 'label' => esc_html__( 'Border Width', 'stereo-bank' ) ),
			),
			'separator' => 'before',
			'exclude' => array( 'color' ),
		) );

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

		$this->add_responsive_control(
			'icon_size',
			array(
				'label' => esc_html__( 'Icon Size', 'stereo-bank' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range' => array(
					'px' => array(
						'max' => 50,
						'min' => 1,
					),
					'em' => array(
						'max' => 5,
						'min' => 0.1,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'icon_size' ) . ': {{SIZE}}{{UNIT}};',
				),
				'conditions' => $conditions_icon,
			)
		);

		$this->add_responsive_control(
			'spacing',
			array(
				'label' => esc_html__( 'Arrows Spacing', 'stereo-bank' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'spacing' ) . ': {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'container_jc_horizontal!' ) => 'space-between',
				),
			)
		);

		$this->add_responsive_control(
			'box_width',
			array(
				'label' => esc_html__( 'Arrows Box Width', 'stereo-bank' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range' => array(
					'%' => array(
						'max' => 100,
						'min' => 5,
					),
					'px' => array(
						'max' => 250,
						'min' => 10,
					),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'box_width' ) . ': {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_height',
			array(
				'label' => esc_html__( 'Arrows Box Height', 'stereo-bank' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range' => array(
					'%' => array(
						'max' => 100,
						'min' => 5,
					),
					'px' => array(
						'max' => 250,
						'min' => 10,
					),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'box_height' ) . ': {{SIZE}}{{UNIT}};',
				),
			)
		);

		$devices = Utils::get_devices();

		$this->add_responsive_control(
			'icon_gap',
			array(
				'label' => esc_html__( 'Icon Gap', 'stereo-bank' ),
				'description' => esc_html__( 'Gap Between Icon and Text', 'stereo-bank' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 50,
						'min' => 0,
					),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'icon_gap' ) . ': {{SIZE}}{{UNIT}};',
				),
				'conditions' => $conditions_icon_gap,
				'device_args' => array(
					$devices['tablet'] => array(
						'condition' => array(
							$this->get_control_id_parameter( '', 'hide_tablet_mobile!' ) => 'yes',
						),
					),
					$devices['mobile'] => array(
						'condition' => array(
							$this->get_control_id_parameter( '', 'hide_tablet_mobile!' ) => 'yes',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'padding',
			array(
				'label' => esc_html__( 'Padding', 'stereo-bank' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'padding_top' ) . ': {{TOP}}{{UNIT}};' .
						'--' . $this->get_control_prefix_parameter( '', 'padding_right' ) . ': {{RIGHT}}{{UNIT}};' .
						'--' . $this->get_control_prefix_parameter( '', 'padding_bottom' ) . ': {{BOTTOM}}{{UNIT}};' .
						'--' . $this->get_control_prefix_parameter( '', 'padding_left' ) . ': {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'responsive_text_visibility',
			array(
				'label' => esc_html__( 'Text On Tablet/Mobile', 'stereo-bank' ),
				'label_block' => false,
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'flex' => array(
						'title' => esc_html__( 'Show', 'stereo-bank' ),
					),
					'none' => array(
						'title' => esc_html__( 'Hide', 'stereo-bank' ),
					),
				),
				'toggle' => true,
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'responsive_text_visibility' ) . ': {{VALUE}};',
				),
				'conditions' => $conditions_icon_gap,
			)
		);

		$this->add_responsive_control(
			'container_heading_control',
			array(
				'label' => esc_html__( 'Container', 'stereo-bank' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'container_position',
			array(
				'label' => esc_html__( 'Position', 'stereo-bank' ),
				'label_block' => false,
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'row' => array(
						'title' => esc_html__( 'Horizontal', 'stereo-bank' ),
						'icon' => 'eicon-navigation-horizontal',
					),
					'column' => array(
						'title' => esc_html__( 'Vertical', 'stereo-bank' ),
						'icon' => 'eicon-navigation-vertical',
					),
				),
				'toggle' => true,
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'container_position' ) . ': {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'container_jc_horizontal',
			array(
				'label' => esc_html__( 'Horizontal Alignment', 'stereo-bank' ),
				'label_block' => false,
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'stereo-bank' ),
						'icon' => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'stereo-bank' ),
						'icon' => 'eicon-h-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'End', 'stereo-bank' ),
						'icon' => 'eicon-h-align-right',
					),
					'space-between' => array(
						'title' => esc_html__( 'Space Between', 'stereo-bank' ),
						'icon' => 'eicon-h-align-stretch',
					),
				),
				'toggle' => true,
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'container_jc' ) . ': {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'container_position' ) => 'row',
				),
			)
		);

		$this->add_responsive_control(
			'container_jc_vertical',
			array(
				'label' => esc_html__( 'Vertical Alignment', 'stereo-bank' ),
				'label_block' => false,
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'stereo-bank' ),
						'icon' => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => esc_html__( 'Middle', 'stereo-bank' ),
						'icon' => 'eicon-v-align-middle',
					),
					'flex-end' => array(
						'title' => esc_html__( 'End', 'stereo-bank' ),
						'icon' => 'eicon-v-align-bottom',
					),
					'space-between' => array(
						'title' => esc_html__( 'Space Between', 'stereo-bank' ),
						'icon' => 'eicon-v-align-stretch',
					),
				),
				'toggle' => true,
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'container_jc' ) . ': {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'container_position' ) => 'column',
				),
			)
		);

		$this->add_responsive_control(
			'container_ai_horizontal',
			array(
				'label' => esc_html__( 'Horizontal Alignment', 'stereo-bank' ),
				'label_block' => false,
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'stereo-bank' ),
						'icon' => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'stereo-bank' ),
						'icon' => ' eicon-h-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'End', 'stereo-bank' ),
						'icon' => 'eicon-h-align-right',
					),
				),
				'toggle' => true,
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'container_ai' ) . ': {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'container_position' ) => 'column',
				),
			)
		);

		$this->add_responsive_control(
			'container_ai_vertical',
			array(
				'label' => esc_html__( 'Vertical Alignment', 'stereo-bank' ),
				'label_block' => false,
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'stereo-bank' ),
						'icon' => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'stereo-bank' ),
						'icon' => ' eicon-v-align-middle',
					),
					'flex-end' => array(
						'title' => esc_html__( 'End', 'stereo-bank' ),
						'icon' => 'eicon-v-align-bottom',
					),
				),
				'toggle' => true,
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'container_ai' ) . ': {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id_parameter( '', 'container_position' ) => 'row',
				),
			)
		);

		$this->add_responsive_control(
			'container_margin',
			array(
				'label' => esc_html__( 'Margin', 'stereo-bank' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'container_margin_top' ) . ': {{TOP}}{{UNIT}};' .
						'--' . $this->get_control_prefix_parameter( '', 'container_margin_right' ) . ': {{RIGHT}}{{UNIT}};' .
						'--' . $this->get_control_prefix_parameter( '', 'container_margin_bottom' ) . ': {{BOTTOM}}{{UNIT}};' .
						'--' . $this->get_control_prefix_parameter( '', 'container_margin_left' ) . ': {{LEFT}}{{UNIT}};',
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
