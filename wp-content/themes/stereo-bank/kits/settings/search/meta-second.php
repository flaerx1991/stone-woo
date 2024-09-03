<?php
namespace StereoBankSpace\Kits\Settings\Search;

use StereoBankSpace\Kits\Settings\Base\Settings_Tab_Base;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Search Meta settings.
 */
class Meta_Second extends Settings_Tab_Base {

	/**
	 * Get toggle name.
	 *
	 * Retrieve the toggle name.
	 *
	 * @return string Toggle name.
	 */
	public static function get_toggle_name() {
		return 'search_meta_second';
	}

	/**
	 * Get title.
	 *
	 * Retrieve the toggle title.
	 */
	public function get_title() {
		return esc_html__( 'Meta Data 2', 'stereo-bank' );
	}

	/**
	 * Get control ID prefix.
	 *
	 * Retrieve the control ID prefix.
	 *
	 * @return string Control ID prefix.
	 */
	protected static function get_control_id_prefix() {
		return parent::get_control_id_prefix() . '_search';
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
			'conditions' => array(
				'relation' => 'or',
				'terms' => array(
					array(
						'relation' => 'and',
						'terms' => array(
							array(
								'name' => $this->get_control_id_parameter( '', 'type' ),
								'operator' => '===',
								'value' => 'large',
							),
							array(
								'name' => $this->get_control_id_parameter( '', 'large_elements' ),
								'operator' => 'contains',
								'value' => 'meta_second',
							),
						),
					),
					array(
						'relation' => 'and',
						'terms' => array(
							array(
								'name' => $this->get_control_id_parameter( '', 'type' ),
								'operator' => '===',
								'value' => 'grid',
							),
							array(
								'name' => $this->get_control_id_parameter( '', 'grid_elements' ),
								'operator' => 'contains',
								'value' => 'meta_second',
							),
						),
					),
					array(
						'relation' => 'and',
						'terms' => array(
							array(
								'name' => $this->get_control_id_parameter( '', 'type' ),
								'operator' => '===',
								'value' => 'compact',
							),
							array(
								'name' => $this->get_control_id_parameter( '', 'compact_elements' ),
								'operator' => 'contains',
								'value' => 'meta_second',
							),
						),
					),
				),
			),
		);
	}

	/**
	 * Register toggle controls.
	 *
	 * Registers the controls of the kit settings tab toggle.
	 */
	protected function register_toggle_controls() {
		$this->add_controls_group( 'meta_second', self::CONTROLS_POST_META, array(
			'elements_default' => array( 'comments' ),
		) );
	}

}
