<?php
namespace StereoBankSpace\Kits\Settings\Single;

use StereoBankSpace\Kits\Settings\Base\Settings_Tab_Base;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Single Nav settings.
 */
class Nav extends Settings_Tab_Base {

	/**
	 * Get toggle name.
	 *
	 * Retrieve the toggle name.
	 *
	 * @return string Toggle name.
	 */
	public static function get_toggle_name() {
		return 'single_nav';
	}

	/**
	 * Get title.
	 *
	 * Retrieve the toggle title.
	 */
	public function get_title() {
		return esc_html__( 'Posts Navigation', 'stereo-bank' );
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

		return parent::get_control_id_prefix() . '_single';
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
				$this->get_control_id_parameter( '', 'blocks' ) => 'nav',
			),
		);
	}

	/**
	 * Register toggle controls.
	 *
	 * Registers the controls of the kit settings tab toggle.
	 */
	protected function register_toggle_controls() {
		$this->add_control(
			'nav_tax',
			array(
				'label' => esc_html__( 'Order by Taxonomy', 'stereo-bank' ),
				'label_block' => false,
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'none' => esc_html__( 'None', 'stereo-bank' ),
					'category' => esc_html__( 'Category', 'stereo-bank' ),
					'post_tag' => esc_html__( 'Tag', 'stereo-bank' ),
				),
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'nav_tax' ),
					'none'
				),
			)
		);

		$this->add_control(
			'nav_text_above_prev',
			array(
				'label' => esc_html__( 'Text Above Previous Post Title', 'stereo-bank' ),
				'label_block' => true,
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Previous', 'stereo-bank' ),
			)
		);

		$this->add_control(
			'nav_text_above_next',
			array(
				'label' => esc_html__( 'Text Above Next Post Title', 'stereo-bank' ),
				'label_block' => true,
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Next', 'stereo-bank' ),
			)
		);

		$this->add_controls_group( 'nav_box', self::CONTROLS_CONTAINER_BOX, array(
			'excludes' => array(
				'alignment',
				'bg_color',
				'box_shadow',
			),
		) );

		$this->add_control(
			'nav_apply_settings',
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
