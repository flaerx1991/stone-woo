<?php
namespace StereoBankSpace\Kits\Traits\ControlsGroups;

use StereoBankSpace\Kits\Controls\Controls_Manager as CmsmastersControls;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Archive trait.
 *
 * Allows to use a group of controls for archive.
 */
trait Archive {

	/**
	 * Group of controls for archive.
	 *
	 * @param string $key Controls key.
	 * @param array $args Controls args.
	 */
	protected function controls_group_archive( $key = '', $args = array() ) {
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

		$this->add_control(
			$this->get_control_name_parameter( $key, 'layout' ),
			array_merge_recursive(
				$default_args,
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
						$this->get_control_name_parameter( $key, 'layout' ),
						'r-sidebar'
					),
					'toggle' => false,
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'type' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => esc_html__( 'Type', 'stereo-bank' ),
					'label_block' => false,
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
					'type' => CmsmastersControls::CHOOSE_TEXT,
					'options' => array(
						'large' => esc_html__( 'Large', 'stereo-bank' ),
						'grid' => esc_html__( 'Grid', 'stereo-bank' ),
						'compact' => esc_html__( 'Compact', 'stereo-bank' ),
					),
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'type' ),
						'large'
					),
					'toggle' => false,
				)
			)
		);

		$default_large_args = array_merge_recursive(
			$default_args,
			array(
				'condition' => array( $this->get_control_id_parameter( $key, 'type' ) => 'large' ),
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'large_elements_heading_control' ),
			array_merge_recursive(
				$default_large_args,
				array(
					'label' => esc_html__( 'Elements Order', 'stereo-bank' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'large_elements' ),
			array_merge_recursive(
				$default_large_args,
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
						'more' => esc_html__( 'Read More', 'stereo-bank' ),
					),
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'large_elements' ),
						array(
							'media',
							'title',
							'meta_first',
							'content',
							'meta_second',
							'more',
						)
					),
					'multiple' => true,
				)
			)
		);

		$default_grid_args = array_merge_recursive(
			$default_args,
			array(
				'condition' => array( $this->get_control_id_parameter( $key, 'type' ) => 'grid' ),
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'grid_style' ),
			array_merge_recursive(
				$default_grid_args,
				array(
					'label' => esc_html__( 'Style', 'stereo-bank' ),
					'label_block' => false,
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
					'type' => CmsmastersControls::CHOOSE_TEXT,
					'options' => array(
						'fit-rows' => esc_html__( 'Fit Rows', 'stereo-bank' ),
						'masonry' => esc_html__( 'Masonry', 'stereo-bank' ),
					),
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'grid_style' ),
						'masonry'
					),
					'toggle' => false,
					'separator' => 'before',
				)
			)
		);

		$this->add_responsive_control(
			$this->get_control_name_parameter( $key, 'grid_columns' ),
			array_merge_recursive(
				$default_grid_args,
				array(
					'label' => esc_html__( 'Columns', 'stereo-bank' ),
					'label_block' => true,
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
					'type' => CmsmastersControls::CHOOSE_TEXT,
					'options' => array(
						'1' => array(
							'title' => '1',
							'description' => esc_html__( 'One', 'stereo-bank' ),
						),
						'2' => array(
							'title' => '2',
							'description' => esc_html__( 'Two', 'stereo-bank' ),
						),
						'3' => array(
							'title' => '3',
							'description' => esc_html__( 'Three', 'stereo-bank' ),
						),
						'4' => array(
							'title' => '4',
							'description' => esc_html__( 'Four', 'stereo-bank' ),
						),
						'5' => array(
							'title' => '5',
							'description' => esc_html__( 'Five', 'stereo-bank' ),
						),
					),
					'desktop_default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'grid_columns' ),
						'4'
					),
					'tablet_default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'grid_columns_tablet' ),
						'2'
					),
					'mobile_default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'grid_columns_mobile' ),
						'1'
					),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( $key, 'grid_columns' ) . ': {{VALUE}};',
					),
					'toggle' => true,
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'grid_elements_heading_control' ),
			array_merge_recursive(
				$default_grid_args,
				array(
					'label' => esc_html__( 'Elements Order', 'stereo-bank' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'grid_elements' ),
			array_merge_recursive(
				$default_grid_args,
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
						'more' => esc_html__( 'Read More', 'stereo-bank' ),
					),
					'multiple' => true,
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'grid_elements' ),
						array(
							'media',
							'title',
							'meta_first',
							'content',
							'meta_second',
							'more',
						)
					),
				)
			)
		);

		$default_compact_args = array_merge_recursive(
			$default_args,
			array(
				'condition' => array( $this->get_control_id_parameter( $key, 'type' ) => 'compact' ),
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'media_heading_control' ),
			array_merge_recursive(
				$default_compact_args,
				array(
					'label' => esc_html__( 'Media', 'stereo-bank' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'compact_media_visibility' ),
			array_merge_recursive(
				$default_compact_args,
				array(
					'label' => esc_html__( 'Visibility', 'stereo-bank' ),
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
					'type' => Controls_Manager::SWITCHER,
					'label_off' => esc_html__( 'Hide', 'stereo-bank' ),
					'label_on' => esc_html__( 'Show', 'stereo-bank' ),
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'compact_media_visibility' ),
						'yes'
					),
				)
			)
		);

		$default_compact_media_args = array_merge_recursive(
			$default_compact_args,
			array(
				'condition' => array( $this->get_control_id_parameter( $key, 'compact_media_visibility' ) => 'yes' ),
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'compact_media_position' ),
			array_merge_recursive(
				$default_compact_media_args,
				array(
					'label' => esc_html__( 'Position', 'stereo-bank' ),
					'label_block' => false,
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => array(
						'left' => array(
							'icon' => 'eicon-h-align-left',
							'title' => esc_html__( 'Left', 'stereo-bank' ),
						),
						'right' => array(
							'icon' => 'eicon-h-align-right',
							'title' => esc_html__( 'Right', 'stereo-bank' ),
						),
					),
					'toggle' => false,
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'compact_media_position' ),
						'left'
					),
				)
			)
		);

		$this->add_responsive_control(
			$this->get_control_name_parameter( $key, 'compact_media_width' ),
			array_merge_recursive(
				$default_compact_media_args,
				array(
					'label' => esc_html__( 'Width', 'stereo-bank' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => array( '%' ),
					'range' => array(
						'%' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( $key, 'compact_media_width' ) . ': {{SIZE}}%;',
					),
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'compact_vertical_alignment' ),
			array_merge_recursive(
				$default_compact_media_args,
				array(
					'label' => esc_html__( 'Vertical Alignment', 'stereo-bank' ),
					'label_block' => false,
					'type' => Controls_Manager::CHOOSE,
					'options' => array(
						'flex-start' => array(
							'icon' => 'eicon-v-align-top',
							'title' => esc_html__( 'Top', 'stereo-bank' ),
						),
						'center' => array(
							'icon' => 'eicon-v-align-middle',
							'title' => esc_html__( 'Center', 'stereo-bank' ),
						),
						'flex-end' => array(
							'icon' => 'eicon-v-align-bottom',
							'title' => esc_html__( 'Bottom', 'stereo-bank' ),
						),
					),
					'toggle' => false,
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( $key, 'compact_vertical_alignment' ) . ': {{VALUE}};',
					),
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'compact_elements_heading_control' ),
			array_merge_recursive(
				$default_compact_args,
				array(
					'label' => esc_html__( 'Elements Order', 'stereo-bank' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'compact_elements' ),
			array_merge_recursive(
				$default_compact_args,
				array(
					'label_block' => true,
					'show_label' => false,
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'stereo-bank' ),
					'type' => CmsmastersControls::SELECTIZE,
					'options' => array(
						'title' => esc_html__( 'Title', 'stereo-bank' ),
						'meta_first' => esc_html__( 'Meta Data 1', 'stereo-bank' ),
						'meta_second' => esc_html__( 'Meta Data 2', 'stereo-bank' ),
						'content' => esc_html__( 'Content', 'stereo-bank' ),
						'more' => esc_html__( 'Read More', 'stereo-bank' ),
					),
					'multiple' => true,
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'compact_elements' ),
						array(
							'title',
							'meta_first',
							'content',
							'meta_second',
							'more',
						)
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
