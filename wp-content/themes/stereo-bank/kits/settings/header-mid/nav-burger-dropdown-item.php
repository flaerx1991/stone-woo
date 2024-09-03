<?php
namespace StereoBankSpace\Kits\Settings\HeaderMid;

use StereoBankSpace\Kits\Settings\Base\Settings_Tab_Base;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Header Mid Navigation Burger Dropdown Item settings.
 */
class Nav_Burger_Dropdown_Item extends Settings_Tab_Base {

	/**
	 * Get toggle name.
	 *
	 * Retrieve the toggle name.
	 *
	 * @return string Toggle name.
	 */
	public static function get_toggle_name() {
		return 'header_mid_nav_burger_dropdown_item';
	}

	/**
	 * Get title.
	 *
	 * Retrieve the toggle title.
	 */
	public function get_title() {
		return esc_html__( 'Hamburger Navigation Dropdown Item', 'stereo-bank' );
	}

	/**
	 * Get control ID prefix.
	 *
	 * Retrieve the control ID prefix.
	 *
	 * @return string Control ID prefix.
	 */
	protected static function get_control_id_prefix() {
		return parent::get_control_id_prefix() . '_header_mid';
	}

	/**
	 * Get toggle conditions.
	 *
	 * Retrieve the settings toggle conditions.
	 *
	 * @return array Toggle conditions.
	 */
	protected function get_toggle_conditions() {
		return array(
			'condition' => array(
				$this->get_control_id_parameter( '', 'type' ) => 'wide',
				$this->get_control_id_parameter( '', 'content_element' ) => 'nav',
			),
		);
	}

	/**
	 * Register toggle controls.
	 *
	 * Registers the controls of the kit settings tab toggle.
	 */
	protected function register_toggle_controls() {
		$this->add_controls_group( 'nav_burger_dropdown_item', self::CONTROLS_NAV_ITEM, array(
			'states' => array(
				'normal' => esc_html__( 'Normal', 'stereo-bank' ),
				'current' => esc_html__( 'Current', 'stereo-bank' ),
			),
		) );
	}

}
