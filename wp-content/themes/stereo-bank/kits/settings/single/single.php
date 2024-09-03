<?php
namespace StereoBankSpace\Kits\Settings\Single;

use StereoBankSpace\Kits\Controls\Controls_Manager as CmsmastersControls;
use StereoBankSpace\Kits\Settings\Base\Settings_Tab_Base;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Single settings.
 */
class Single extends Settings_Tab_Base {

	/**
	 * Get toggle name.
	 *
	 * Retrieve the toggle name.
	 *
	 * @return string Toggle name.
	 */
	public static function get_toggle_name() {
		return 'single';
	}

	/**
	 * Get title.
	 *
	 * Retrieve the toggle title.
	 */
	public function get_title() {
		return esc_html__( 'Single', 'stereo-bank' );
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
				'raw' => esc_html__( "If you use an 'Singular' template, then the settings will not be applied, if you set the template to 'All Singular', then these settings will be hidden.", 'stereo-bank' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'render_type' => 'ui',
			)
		);

		$this->add_control(
			'layout',
			array(
				'label' => esc_html__( 'Layout', 'stereo-bank' ),
				'label_block' => false,
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'l-sidebar' => array(
						'title' => esc_html__( 'Left', 'stereo-bank' ),
						'description' => esc_html__( 'Left Sidebar', 'stereo-bank' ),
					),
					'fullwidth' => array(
						'title' => esc_html__( 'Full', 'stereo-bank' ),
						'description' => esc_html__( 'Full Width', 'stereo-bank' ),
					),
					'r-sidebar' => array(
						'title' => esc_html__( 'Right', 'stereo-bank' ),
						'description' => esc_html__( 'Right Sidebar', 'stereo-bank' ),
					),
				),
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'layout' ),
					'r-sidebar'
				),
				'toggle' => false,
			)
		);

		$this->add_control(
			'elements_heading_control',
			array(
				'label' => esc_html__( 'Elements Order', 'stereo-bank' ),
				'type' => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'elements',
			array(
				'label_block' => true,
				'show_label' => false,
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
				'type' => CmsmastersControls::SELECTIZE,
				'options' => array(
					'media' => esc_html__( 'Media', 'stereo-bank' ),
					'title' => esc_html__( 'Title', 'stereo-bank' ),
					'meta_first' => esc_html__( 'Meta Data 1', 'stereo-bank' ),
					'meta_second' => esc_html__( 'Meta Data 2', 'stereo-bank' ),
					'content' => esc_html__( 'Content', 'stereo-bank' ),
				),
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'elements' ),
					array(
						'media',
						'title',
						'meta_first',
						'content',
						'meta_second',
					)
				),
				'multiple' => true,
			)
		);

		$this->add_control(
			'heading_visibility',
			array(
				'label' => esc_html__( 'Heading Visibility', 'stereo-bank' ),
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'stereo-bank' ),
				'label_on' => esc_html__( 'Show', 'stereo-bank' ),
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'heading_visibility' ),
					'yes'
				),
			)
		);

		$this->add_control(
			'blocks_heading_control',
			array(
				'label' => esc_html__( 'Blocks Order', 'stereo-bank' ),
				'type' => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'blocks',
			array(
				'label_block' => true,
				'show_label' => false,
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
				'type' => CmsmastersControls::SELECTIZE,
				'options' => array(
					'nav' => esc_html__( 'Posts Navigation', 'stereo-bank' ),
					'author' => esc_html__( 'Author Box', 'stereo-bank' ),
					'more_posts' => esc_html__( 'More Posts', 'stereo-bank' ),
				),
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'blocks' ),
					array(
						'nav',
						'author',
						'more_posts',
					)
				),
				'multiple' => true,
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
