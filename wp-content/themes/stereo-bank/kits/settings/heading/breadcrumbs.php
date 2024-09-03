<?php
namespace StereoBankSpace\Kits\Settings\Heading;

use StereoBankSpace\Kits\Controls\Controls_Manager as CmsmastersControls;
use StereoBankSpace\Kits\Settings\Base\Settings_Tab_Base;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Heading Breadcrumbs settings.
 */
class Breadcrumbs extends Settings_Tab_Base {

	/**
	 * Get toggle name.
	 *
	 * Retrieve the toggle name.
	 *
	 * @return string Toggle name.
	 */
	public static function get_toggle_name() {
		return 'breadcrumbs';
	}

	/**
	 * Get title.
	 *
	 * Retrieve the toggle title.
	 */
	public function get_title() {
		return esc_html__( 'Breadcrumbs', 'stereo-bank' );
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
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'visibility' ),
					'yes'
				),
			)
		);

		$default_visibility_args = array(
			'condition' => array( $this->get_control_id_parameter( '', 'visibility' ) => 'yes' ),
		);

		$this->add_var_group_control( '', self::VAR_TYPOGRAPHY, $default_visibility_args );

		$this->add_control(
			'colors_heading_control',
			array_merge_recursive(
				$default_visibility_args,
				array(
					'label' => esc_html__( 'Colors', 'stereo-bank' ),
					'type' => Controls_Manager::HEADING,
				)
			)
		);

		$this->add_control(
			'colors_text',
			array_merge_recursive(
				$default_visibility_args,
				array(
					'label' => esc_html__( 'Text', 'stereo-bank' ),
					'type' => Controls_Manager::COLOR,
					'dynamic' => array(),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( '', 'colors_text' ) . ': {{VALUE}};',
					),
				)
			)
		);

		$this->add_control(
			'colors_link',
			array_merge_recursive(
				$default_visibility_args,
				array(
					'label' => esc_html__( 'Link', 'stereo-bank' ),
					'type' => Controls_Manager::COLOR,
					'dynamic' => array(),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( '', 'colors_link' ) . ': {{VALUE}};',
					),
				)
			)
		);

		$this->add_control(
			'colors_hover',
			array_merge_recursive(
				$default_visibility_args,
				array(
					'label' => esc_html__( 'Link Hover', 'stereo-bank' ),
					'type' => Controls_Manager::COLOR,
					'dynamic' => array(),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( '', 'colors_hover' ) . ': {{VALUE}};',
					),
				)
			)
		);

		$this->add_control(
			'colors_divider',
			array_merge_recursive(
				$default_visibility_args,
				array(
					'label' => esc_html__( 'Divider', 'stereo-bank' ),
					'type' => Controls_Manager::COLOR,
					'dynamic' => array(),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( '', 'colors_divider' ) . ': {{VALUE}};',
					),
				)
			)
		);

		$this->add_control(
			'type',
			array_merge_recursive(
				$default_visibility_args,
				array(
					'label' => esc_html__( 'Type', 'stereo-bank' ),
					'label_block' => false,
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
					'type' => CmsmastersControls::CHOOSE_TEXT,
					'options' => array(
						'in_title' => array(
							'title' => esc_html__( 'In Title', 'stereo-bank' ),
						),
						'new_row' => array(
							'title' => esc_html__( 'New Row', 'stereo-bank' ),
						),
					),
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( '', 'type' ),
						'in_title'
					),
					'toggle' => false,
				)
			)
		);

		$this->add_control(
			'position',
			array_merge_recursive(
				$default_visibility_args,
				array(
					'label' => esc_html__( 'Position of title', 'stereo-bank' ),
					'label_block' => false,
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
					'type' => CmsmastersControls::CHOOSE_TEXT,
					'options' => array(
						'above_title' => array(
							'title' => esc_html__( 'Above', 'stereo-bank' ),
						),
						'below_title' => array(
							'title' => esc_html__( 'Below', 'stereo-bank' ),
						),
					),
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( '', 'position' ),
						array( 'above_title' )
					),
					'toggle' => false,
					'condition' => array(
						$this->get_control_id_parameter( '', 'type' ) => 'in_title',
					),
				)
			)
		);

		$this->add_responsive_control(
			'gap',
			array_merge_recursive(
				$default_visibility_args,
				array(
					'label' => esc_html__( 'Gap Between', 'stereo-bank' ),
					'description' => esc_html__( 'Gap between breadcrumbs and title', 'stereo-bank' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
						'%' => array(
							'min' => 0,
							'max' => 100,
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
						':root' => '--' . $this->get_control_prefix_parameter( '', 'gap' ) . ': {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						$this->get_control_id_parameter( '', 'type' ) => 'in_title',
					),
				)
			)
		);

		$this->add_controls_group( 'container', self::CONTROLS_CONTAINER, array_merge_recursive(
			$default_visibility_args,
			array(
				'separator' => 'none',
				'condition' => array(
					$this->get_control_id_parameter( '', 'type' ) => 'new_row',
				),
			)
		) );

		$this->add_controls_group( 'content', self::CONTROLS_CONTENT, array_merge_recursive(
			$default_visibility_args,
			array(
				'separator' => 'none',
				'condition' => array(
					$this->get_control_id_parameter( '', 'type' ) => 'new_row',
				),
			)
		) );

		$this->add_control(
			'resp_visibility',
			array_merge_recursive(
				$default_visibility_args,
				array(
					'label' => esc_html__( 'Visibility on Devices', 'stereo-bank' ),
					'label_block' => false,
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
					'type' => Controls_Manager::SELECT,
					'options' => array(
						'show' => esc_html__( 'Show', 'stereo-bank' ),
						'hide_mobile' => esc_html__( 'Hide on mobile', 'stereo-bank' ),
						'hide_tablet' => esc_html__( 'Hide on tablet and mobile', 'stereo-bank' ),
					),
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( '', 'resp_visibility' ),
						'show'
					),
				)
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
