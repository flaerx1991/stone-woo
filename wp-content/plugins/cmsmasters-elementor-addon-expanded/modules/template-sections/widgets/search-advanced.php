<?php
namespace CmsmastersElementor\Modules\TemplateSections\Widgets;

use CmsmastersElementor\Base\Base_Widget;
use CmsmastersElementor\Controls_Manager as CmsmastersControls;
use CmsmastersElementor\Modules\TemplateSections\Traits\Site_Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Addon advanced search widget.
 *
 * Addon widget that display advanced site search.
 *
 * @since 1.0.0
*/
class Search_Advanced extends Base_Widget {

	use Site_Widget;

	/**
	 * Get widget title.
	 *
	 * Retrieve widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Advanced Search', 'cmsmasters-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'cmsicon-advanced-search';
	}

	/**
	 * Get widget unique keywords.
	 *
	 * Retrieve the list of unique keywords the widget belongs to.
	 *
	 * @since 1.0.0
	 *
	 * @return array Widget unique keywords.
	 */
	public function get_unique_keywords() {
		return array(
			'search',
			'find',
			'advanced',
			'slide',
		);
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_general_settings',
			array( 'label' => __( 'General Settings', 'cmsmasters-elementor' ) )
		);

			$this->add_control(
				'type_of_search',
				array(
					'label' => __( 'Type of Search', 'cmsmasters-elementor' ),
					'type' => CmsmastersControls::CHOOSE_TEXT,
					'options' => array(
						'slide' => array(
							'title' => __( 'Slide', 'cmsmasters-elementor' ),
							'description' => __( 'Search outside parent block.', 'cmsmasters-elementor' ),
						),
						'slide-minimal' => array(
							'title' => __( 'Minimal Slide', 'cmsmasters-elementor' ),
							'description' => __( 'Search inside parent block.', 'cmsmasters-elementor' ),
						),
					),
					'default' => 'slide',
					'label_block' => false,
					'render_type' => 'template',
					'frontend_available' => true,
					'prefix_class' => 'cmsmasters-search-type-',
				)
			);

			$this->add_control(
				'search_placeholder',
				array(
					'label' => __( 'Search Placeholder', 'cmsmasters-elementor' ),
					'placeholder' => __( 'Search...', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::TEXT,
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_popup_trigger',
			array( 'label' => __( 'Popup Trigger', 'cmsmasters-elementor' ) )
		);

			$this->add_control(
				'popup_trigger_type',
				array(
					'label' => __( 'Type', 'cmsmasters-elementor' ),
					'type' => CmsmastersControls::CHOOSE_TEXT,
					'options' => array(
						'link' => array(
							'title' => __( 'Link', 'cmsmasters-elementor' ),
							'description' => 'Trigger is a link',
						),
						'button' => array(
							'title' => __( 'Button', 'cmsmasters-elementor' ),
							'description' => 'Trigger is a button',
						),
					),
					'default' => 'button',
					'label_block' => false,
					'toggle' => false,
				)
			);

			$this->add_control(
				'popup_trigger_content',
				array(
					'label' => __( 'View', 'cmsmasters-elementor' ),
					'type' => CmsmastersControls::CHOOSE_TEXT,
					'options' => array(
						'icon' => array(
							'title' => __( 'Icon', 'cmsmasters-elementor' ),
							'description' => 'Trigger has only icon',
						),
						'text' => array(
							'title' => __( 'Text', 'cmsmasters-elementor' ),
							'description' => 'Trigger has only text',
						),
						'both' => array(
							'title' => __( 'Both', 'cmsmasters-elementor' ),
							'description' => 'Trigger has icon and text',
						),
					),
					'default' => 'icon',
					'label_block' => false,
					'toggle' => false,
				)
			);

			$this->start_controls_tabs(
				'tabs_popup_trigger_icon',
				array( 'condition' => array( 'popup_trigger_content!' => 'text' ) )
			);

				$this->start_controls_tab(
					'tab_popup_trigger_icon',
					array( 'label' => __( 'Normal', 'cmsmasters-elementor' ) )
				);

					$this->add_control(
						'popup_trigger_icon',
						array(
							'label' => __( 'Choose Icon', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::ICONS,
							'fa4compatibility' => 'icon',
							'recommended' => array(
								'fa-solid' => array(
									'search',
									'search-dollar',
									'search-location',
									'search-minus',
									'search-plus',
								),
							),
							'default' => array(
								'value' => 'fas fa-search',
								'library' => 'fa-solid',
							),
							'label_block' => true,
							'show_label' => false,
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_popup_trigger_icon_hover',
					array( 'label' => __( 'Hover', 'cmsmasters-elementor' ) )
				);

					$this->add_control(
						'popup_trigger_icon_hover',
						array(
							'label' => __( 'Close Button Icon', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::ICONS,
							'fa4compatibility' => 'icon',
							'recommended' => array(
								'fa-regular' => array(
									'times-circle',
									'window-close',
								),
								'fa-solid' => array(
									'window-close',
								),
							),
							'default' => array(
								'value' => 'far fa-times-circle',
								'library' => 'fa-regular',
							),
							'label_block' => true,
							'show_label' => false,
							'condition' => array(
								'type_of_search' => 'slide',
							),
						)
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'popup_trigger_label',
				array(
					'label' => __( 'Text', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => 'Search',
					'separator' => 'before',
					'condition' => array( 'popup_trigger_content!' => 'icon' ),
				)
			);

			$this->add_control(
				'popup_trigger_icon_position',
				array(
					'label' => __( 'Icon Position', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => array(
						'left' => array(
							'title' => __( 'Left', 'cmsmasters-elementor' ),
							'icon' => 'eicon-h-align-left',
						),
						'right' => array(
							'title' => __( 'Right', 'cmsmasters-elementor' ),
							'icon' => 'eicon-h-align-right',
						),
					),
					'toggle' => true,
					'prefix_class' => 'cmsmasters-popup-trigger-icon-position-',
					'condition' => array(
						'popup_trigger_content' => 'both',
						'popup_trigger_label[value]!' => '',
						'popup_trigger_icon[value]!' => '',
					),
				)
			);

			$this->add_control(
				'slide_close_label',
				array(
					'label' => __( 'Close Button Label', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => 'Cancel',
					'condition' => array(
						'type_of_search' => 'slide',
						'popup_trigger_content!' => 'icon',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_search_form',
			array( 'label' => __( 'Form', 'cmsmasters-elementor' ) )
		);

		$this->add_control(
			'slide_form_relative',
			array(
				'label' => __( 'Position Relative', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'window' => array(
						'title' => __( 'Window', 'cmsmasters-elementor' ),
						'description' => 'Search form relative to window.',
					),
					'button' => array(
						'title' => __( 'Button', 'cmsmasters-elementor' ),
						'description' => 'Search form relative to trigger.',
					),
					'block' => array(
						'title' => __( 'Block', 'cmsmasters-elementor' ),
						'description' => 'Search form relative to block by ID.',
					),
				),
				'default' => 'button',
				'condition' => array( 'type_of_search' => 'slide' ),
			)
		);

		$this->add_control(
			'slide_form_relative_block',
			array(
				'label' => __( 'ID/CSS Block for Relative', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'ID or CSS block', 'cmsmasters-elementor' ),
				'description' => __( 'For example: #id or .class', 'cmsmasters-elementor' ),
				'frontend_available' => true,
				'condition' => array(
					'type_of_search' => 'slide',
					'slide_form_relative' => 'block',
				),
			)
		);

		$this->add_control(
			'slide_form_width_type',
			array(
				'label' => __( 'Type of width form', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'fixed' => array(
						'title' => __( 'Fixed', 'cmsmasters-elementor' ),
						'description' => 'Search has fixed width',
					),
					'dynamic' => array(
						'title' => __( 'Dynamic', 'cmsmasters-elementor' ),
						'description' => 'Search has dynamic width (depends on parent)',
					),
				),
				'frontend_available' => true,
				'default' => 'fixed',
				'render_type' => 'template',
			)
		);

		$this->add_responsive_control(
			'slide_position_relatively',
			array(
				'label' => __( 'Form Alignment', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => __( 'Left', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-center',
					),
					'right' => array(
						'title' => __( 'Right', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-right',
					),
				),
				'default' => 'left',
				'condition' => array(
					'type_of_search' => 'slide',
					'slide_form_width_type' => 'fixed',
					'slide_form_relative!' => 'button',
				),
			)
		);

		$this->add_responsive_control(
			'slide_position_relatively_button',
			array(
				'label' => __( 'Form Alignment', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => __( 'Left', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-center',
					),
					'right' => array(
						'title' => __( 'Right', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-right',
					),
				),
				'default' => 'left',
				'condition' => array(
					'type_of_search' => 'slide',
					'slide_form_relative' => 'button',
				),
			)
		);

		$this->add_responsive_control(
			'slide_position',
			array(
				'label' => __( 'Form Alignment', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => __( 'Left', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-left',
					),
					'right' => array(
						'title' => __( 'Right', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-right',
					),
				),
				'default' => 'right',
				'selectors' => array(
					'{{WRAPPER}}.cmsmasters-search-type-slide-minimal .elementor-widget-cmsmasters-search-advanced__form-container' => 'left: auto; {{VALUE}}: 0',
				),
				'condition' => array(
					'type_of_search' => 'slide-minimal',
					'slide_form_width_type' => 'fixed',
				),
			)
		);

		$this->add_responsive_control(
			'slide_form_width',
			array(
				'label' => __( 'Form Width', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vw' ),
				'range' => array(
					'px' => array(
						'min' => 170,
						'max' => 1920,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}.cmsmasters-search-type-slide .elementor-widget-cmsmasters-search-advanced__container .elementor-widget-cmsmasters-search-advanced__form-container' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.cmsmasters-search-type-slide-minimal .elementor-widget-cmsmasters-search-advanced__container .elementor-widget-cmsmasters-search-advanced__form-container' => 'width: {{SIZE}}{{UNIT}};',
				),
				'frontend_available' => true,
				'condition' => array( 'slide_form_width_type' => 'fixed' ),
			)
		);

		$this->add_responsive_control(
			'slide_form_height',
			array(
				'label' => __( 'Form Height', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 10,
					),
				),
				'default' => array(
					'size' => 61,
				),
				'selectors' => array(
					'{{WRAPPER}}.cmsmasters-search-type-slide .elementor-widget-cmsmasters-search-advanced__container .elementor-widget-cmsmasters-search-advanced__form-container' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.cmsmasters-search-type-slide .elementor-widget-cmsmasters-search-advanced__container .elementor-widget-cmsmasters-search-advanced__form-container' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.cmsmasters-search-type-slide.cmsmasters-search-type-button-icon .elementor-widget-cmsmasters-search-advanced__submit' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.cmsmasters-search-type-slide-minimal .elementor-widget-cmsmasters-search-advanced__container .elementor-widget-cmsmasters-search-advanced__form-container' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.cmsmasters-search-type-slide-minimal .elementor-widget-cmsmasters-search-advanced__submit' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.cmsmasters-search-type-slide-minimal.cmsmasters-search-type-button-icon .elementor-widget-cmsmasters-search-advanced__submit' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'slide_show_effect',
			array(
				'label' => __( 'Show Effect', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'none' => __( 'None', 'cmsmasters-elementor' ),
					'fade' => __( 'Fade', 'cmsmasters-elementor' ),
					'scale' => __( 'Scale', 'cmsmasters-elementor' ),
					'move-up' => __( 'Move Up', 'cmsmasters-elementor' ),
					'move-down' => __( 'Move Down', 'cmsmasters-elementor' ),
					'move-right' => __( 'Move Right', 'cmsmasters-elementor' ),
					'move-left' => __( 'Move Left', 'cmsmasters-elementor' ),
				),
				'default' => 'move-left',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_popup_close',
			array(
				'label' => __( 'Popup Close', 'cmsmasters-elementor' ),
				'condition' => array( 'type_of_search' => 'slide-minimal' ),
			)
		);

		$this->add_control(
			'slide_close_type',
			array(
				'label' => __( 'Button Type', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'link' => array( 'title' => __( 'Link', 'cmsmasters-elementor' ) ),
					'button' => array( 'title' => __( 'Button', 'cmsmasters-elementor' ) ),
				),
				'default' => 'button',
				'label_block' => false,
			)
		);

		$this->add_control(
			'slide_close_content',
			array(
				'label' => __( 'Close Content', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'icon' => array( 'title' => __( 'Icon', 'cmsmasters-elementor' ) ),
					'text' => array( 'title' => __( 'Text', 'cmsmasters-elementor' ) ),
					'both' => array( 'title' => __( 'Both', 'cmsmasters-elementor' ) ),
				),
				'default' => 'icon',
				'label_block' => false,
				'toggle' => false,
			)
		);

		$this->add_control(
			'popup_close_icon',
			array(
				'label' => __( 'Close Icon', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'recommended' => array(
					'fa-regular' => array(
						'times-circle',
						'window-close',
					),
					'fa-solid' => array( 'window-close' ),
				),
				'default' => array(
					'value' => 'far fa-times-circle',
					'library' => 'fa-regular',
				),
				'label_block' => true,
				'show_label' => false,
				'file' => '',
				'condition' => array( 'slide_close_content!' => 'text' ),
			)
		);

		$this->add_control(
			'slide_close_label_mini',
			array(
				'label' => __( 'Text', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => 'Label Cancel',
				'default' => 'Cancel',
				'condition' => array( 'slide_close_content!' => 'icon' ),
			)
		);

			$this->add_control(
				'popup_close_icon_position',
				array(
					'label' => __( 'Icon Position', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => array(
						'left' => array(
							'title' => __( 'Left', 'cmsmasters-elementor' ),
							'icon' => 'eicon-h-align-left',
						),
						'right' => array(
							'title' => __( 'Right', 'cmsmasters-elementor' ),
							'icon' => 'eicon-h-align-right',
						),
					),
					'default' => 'right',
					'toggle' => false,
					'prefix_class' => 'cmsmasters-popup-close-icon-position-',
					'condition' => array( 'slide_close_content' => 'both' ),
				)
			);

			$this->add_responsive_control(
				'popup_close_icon_size',
				array(
					'label' => __( 'Icon Size', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range' => array(
						'px' => array(
							'min' => 10,
							'max' => 100,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__popup-close-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'condition' => array( 'popup_trigger_content!' => 'text' ),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_submit_button',
			array( 'label' => __( 'Submit Button', 'cmsmasters-elementor' ) )
		);

			$this->add_control(
				'submit_button_view',
				array(
					'label' => __( 'View', 'cmsmasters-elementor' ),
					'type' => CmsmastersControls::CHOOSE_TEXT,
					'options' => array(
						'none' => array(
							'title' => __( 'None', 'cmsmasters-elementor' ),
							'description' => 'Do not show button',
						),
						'icon' => array(
							'title' => __( 'Icon', 'cmsmasters-elementor' ),
							'description' => 'Button has type icon',
						),
						'button' => array(
							'title' => __( 'Button', 'cmsmasters-elementor' ),
							'description' => 'Button looks like button',
						),
					),
					'default' => 'button',
					'toggle' => false,
					'label_block' => false,
				)
			);

			$this->add_control(
				'submit_button_type',
				array(
					'label' => __( 'Type', 'cmsmasters-elementor' ),
					'type' => CmsmastersControls::CHOOSE_TEXT,
					'options' => array(
						'icon' => array(
							'title' => __( 'Icon', 'cmsmasters-elementor' ),
							'description' => 'Button has only icon',
						),
						'text' => array(
							'title' => __( 'Text', 'cmsmasters-elementor' ),
							'description' => 'Button has only text',
						),
						'both' => array(
							'title' => __( 'Both', 'cmsmasters-elementor' ),
							'description' => 'Button has icon and text',
						),
					),
					'default' => 'icon',
					'toggle' => false,
					'label_block' => false,
					'prefix_class' => 'cmsmasters-search-type-button-',
					'condition' => array( 'submit_button_view' => 'button' ),
				)
			);

			$this->add_control(
				'submit_button_label',
				array(
					'label' => __( 'Text', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => 'Label Button',
					'default' => 'Search',
					'condition' => array(
						'submit_button_view' => 'button',
						'submit_button_type!' => 'icon',
					),
				)
			);

			$this->add_control(
				'submit_button_icon',
				array(
					'label' => __( 'Icon', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::ICONS,
					'fa4compatibility' => 'icon',
					'recommended' => array(
						'fa-solid' => array(
							'search',
							'search-dollar',
							'search-location',
							'search-minus',
							'search-plus',
						),
					),
					'default' => array(
						'value' => 'fas fa-search',
						'library' => 'fa-solid',
					),
					'condition' => array(
						'submit_button_view!' => 'none',
						'submit_button_type!' => 'text',
					),
				)
			);

			$this->add_control(
				'submit_button_icon_position',
				array(
					'label' => __( 'Icon Position', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => array(
						'left' => array(
							'title' => __( 'Left', 'cmsmasters-elementor' ),
							'icon' => 'eicon-h-align-left',
						),
						'right' => array(
							'title' => __( 'Right', 'cmsmasters-elementor' ),
							'icon' => 'eicon-h-align-right',
						),
					),
					'default' => 'right',
					'prefix_class' => 'cmsmasters-buttons-icon-position-',
					'toggle' => true,
					'condition' => array(
						'submit_button_view' => 'button',
						'submit_button_type' => 'both',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_popup_trigger_style',
			array(
				'label' => __( 'Popup Trigger', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

			$this->add_control(
				'slider_trigger_alignment',
				array(
					'label' => __( 'Trigger Alignment', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => array(
						'flex-start' => array(
							'title' => __( 'Left', 'cmsmasters-elementor' ),
							'icon' => 'eicon-text-align-left',
						),
						'center' => array(
							'title' => __( 'Center', 'cmsmasters-elementor' ),
							'icon' => 'eicon-text-align-center',
						),
						'flex-end' => array(
							'title' => __( 'Right', 'cmsmasters-elementor' ),
							'icon' => 'eicon-text-align-right',
						),
					),
					'default' => 'flex-end',
					'selectors' => array(
						'{{WRAPPER}}.cmsmasters-search-type-slide .elementor-widget-cmsmasters-search-advanced__container' => 'justify-content: {{VALUE}};',
						'{{WRAPPER}}.cmsmasters-search-type-slide-minimal .elementor-widget-cmsmasters-search-advanced__container' => 'justify-content: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'popup_trigger_alignment',
				array(
					'label' => __( 'Alignment', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => array(
						'flex-start' => array(
							'title' => __( 'Left', 'cmsmasters-elementor' ),
							'icon' => 'eicon-text-align-left',
						),
						'center' => array(
							'title' => __( 'Center', 'cmsmasters-elementor' ),
							'icon' => 'eicon-text-align-center',
						),
						'flex-end' => array(
							'title' => __( 'Right', 'cmsmasters-elementor' ),
							'icon' => 'eicon-text-align-right',
						),
					),
					'default' => 'center',
					'toggle' => false,
					'selectors' => array(
						'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__button' => 'justify-content: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'popup_trigger_padding',
				array(
					'label' => __( 'Padding', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px' ),
					'selectors' => array(
						'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__container .elementor-widget-cmsmasters-search-advanced__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition' => array(
						'popup_trigger_type' => 'button',
						'popup_trigger_content!' => 'icon',
					),
				)
			);

			$this->add_responsive_control(
				'popup_trigger_min_size',
				array(
					'label' => __( 'Button Min Size', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range' => array(
						'px' => array( 'min' => 20 ),
					),
					'selectors' => array(
						'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__button' => 'min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						'popup_trigger_content' => 'icon',
						'popup_trigger_type' => 'button',
					),
				)
			);

			$this->add_responsive_control(
				'popup_trigger_icon_size',
				array(
					'label' => __( 'Icon Size', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range' => array(
						'px' => array( 'min' => 10 ),
					),
					'selectors' => array(
						'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'condition' => array( 'popup_trigger_content!' => 'text' ),
				)
			);

			$this->add_responsive_control(
				'popup_trigger_icon_gap',
				array(
					'label' => __( 'Icon Gap', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'selectors' => array(
						'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__button-icon + .elementor-widget-cmsmasters-search-advanced__button-label,
						{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__button-icon.close + .elementor-widget-cmsmasters-search-advanced__button-label' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',
						'{{WRAPPER}}.cmsmasters-popup-trigger-icon-position-right .elementor-widget-cmsmasters-search-advanced__button-icon + .elementor-widget-cmsmasters-search-advanced__button-label,
						{{WRAPPER}}.cmsmasters-popup-trigger-icon-position-right .elementor-widget-cmsmasters-search-advanced__button-icon.close + .elementor-widget-cmsmasters-search-advanced__button-label' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: 0;',
					),
					'condition' => array( 'popup_trigger_content' => 'both' ),
				)
			);

			$this->start_controls_tabs(
				'tabs_popup_trigger_style',
				array( 'separator' => 'before' )
			);

				$this->start_controls_tab(
					'tab_popup_trigger_normal',
					array( 'label' => __( 'Normal', 'cmsmasters-elementor' ) )
				);

					$this->add_control(
						'popup_trigger_color',
						array(
							'label' => __( 'Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__button' => 'color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'popup_trigger_bg_color',
						array(
							'label' => __( 'Background Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__button' => 'background-color: {{VALUE}}',
							),
							'condition' => array( 'popup_trigger_type' => 'button' ),
						)
					);

					$this->add_control(
						'popup_trigger_border_color',
						array(
							'label' => __( 'Border Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__button' => 'border-color: {{VALUE}}',
							),
							'conditions' => array(
								'relation' => 'and',
								'terms' => array(
									array(
										'name' => 'popup_trigger_type',
										'operator' => '=',
										'value' => 'button',
									),
									array(
										'name' => 'popup_trigger_border_border',
										'operator' => '!==',
										'value' => '',
									),
									array(
										'relation' => 'or',
										'terms' => array(
											array(
												'name' => 'popup_trigger_border_width[top]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'popup_trigger_border_width[right]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'popup_trigger_border_width[bottom]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'popup_trigger_border_width[left]',
												'operator' => '>',
												'value' => '0',
											),
										),
									),
								),
							),
						)
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						array(
							'name' => 'popup_trigger_box_shadow',
							'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__button',
							'condition' => array( 'popup_trigger_type' => 'button' ),
						)
					);

					$this->add_group_control(
						Group_Control_Text_Shadow::get_type(),
						array(
							'name' => 'popup_trigger_text_shadow',
							'fields_options' => array(
								'text_shadow_type' => array( 'label' => __( 'Text Shadow', 'cmsmasters-elementor' ) ),
							),
							'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__button i,
								{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__button-label',
							'condition' => array( 'popup_trigger_type' => 'link' ),
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_popup_trigger_hover',
					array( 'label' => __( 'Hover', 'cmsmasters-elementor' ) )
				);

					$this->add_control(
						'popup_trigger_color_hover',
						array(
							'label' => __( 'Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__button:hover' => 'color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'popup_trigger_bg_color_hover',
						array(
							'label' => __( 'Background Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__button:hover' => 'background-color: {{VALUE}}',
							),
							'condition' => array( 'popup_trigger_type' => 'button' ),
						)
					);

					$this->add_control(
						'popup_trigger_border_color_hover',
						array(
							'label' => __( 'Border Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__button:hover' => 'border-color: {{VALUE}}',
							),
							'conditions' => array(
								'relation' => 'and',
								'terms' => array(
									array(
										'name' => 'popup_trigger_type',
										'operator' => '=',
										'value' => 'button',
									),
									array(
										'name' => 'popup_trigger_border_border',
										'operator' => '!==',
										'value' => '',
									),
									array(
										'relation' => 'or',
										'terms' => array(
											array(
												'name' => 'popup_trigger_border_width[top]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'popup_trigger_border_width[right]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'popup_trigger_border_width[bottom]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'popup_trigger_border_width[left]',
												'operator' => '>',
												'value' => '0',
											),
										),
									),
								),
							),
						)
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						array(
							'name' => 'popup_trigger_box_shadow_hover',
							'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__button:hover',
							'condition' => array( 'popup_trigger_type' => 'button' ),
						)
					);

					$this->add_group_control(
						Group_Control_Text_Shadow::get_type(),
						array(
							'name' => 'popup_trigger_text_shadow_hover',
							'fields_options' => array(
								'text_shadow_type' => array( 'label' => __( 'Text Shadow', 'cmsmasters-elementor' ) ),
							),
							'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__button:hover i',
							'condition' => array( 'popup_trigger_type' => 'link' ),
						)
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name' => 'popup_trigger_border',
					'label' => __( 'Border', 'cmsmasters-elementor' ),
					'exclude' => array( 'color' ),
					'separator' => 'before',
					'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__button',
					'condition' => array( 'popup_trigger_type' => 'button' ),
				)
			);

			$this->add_responsive_control(
				'popup_trigger_border_radius',
				array(
					'label' => __( 'Border Radius', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
					),
					'selectors' => array(
						'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition' => array( 'popup_trigger_type' => 'button' ),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_search_form_style',
			array(
				'label' => __( 'Form', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

			$this->add_responsive_control(
				'form_input_gap',
				array(
					'label' => __( 'Gap Form', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range' => array(
						'px' => array(
							'min' => 0,
						),
					),
					'default' => array(
						'unit' => 'px',
						'size' => 0,
					),
					'selectors' => array(
						'{{WRAPPER}}.cmsmasters-search-type-slide.elementor-widget-cmsmasters-search-advanced__form-container' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
					'condition' => array( 'submit_button_view' => 'button' ),
				)
			);

			$this->start_controls_tabs( 'tabs_form_style' );

				$this->start_controls_tab(
					'tab_form_submit_normal',
					array(
						'label' => __( 'Normal', 'cmsmasters-elementor' ),
					)
				);

					$this->add_control(
						'form_bg_color',
						array(
							'label' => __( 'Background Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}}.cmsmasters-search-type-slide .elementor-widget-cmsmasters-search-advanced__form-container-inner' => 'background-color: {{VALUE}}',
								'{{WRAPPER}}.cmsmasters-search-type-slide-minimal .elementor-widget-cmsmasters-search-advanced__form-container' => 'background-color: {{VALUE}}',
							),
						)
					);

					$this->add_group_control(
						Group_Control_Border::get_type(),
						array(
							'name' => 'form_border',
							'label' => __( 'Border', 'cmsmasters-elementor' ),
							'placeholder' => '1px',
							'selector' => '{{WRAPPER}}.cmsmasters-search-type-slide.elementor-widget-cmsmasters-search-advanced__form-container-inner, {{WRAPPER}}.cmsmasters-search-type-slide-minimal .elementor-widget-cmsmasters-search-advanced__form-container',
						)
					);

					$this->add_responsive_control(
						'form_padding',
						array(
							'label' => __( 'Button Padding', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::DIMENSIONS,
							'size_units' => array( 'px' ),
							'selectors' => array(
								'{{WRAPPER}}.cmsmasters-search-type-slide.elementor-widget-cmsmasters-search-advanced__form-container-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								'{{WRAPPER}}.cmsmasters-search-type-slide-minimal .elementor-widget-cmsmasters-search-advanced__form-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							),
						)
					);

					$this->add_responsive_control(
						'form_border_radius',
						array(
							'label' => __( 'Border Radius', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::DIMENSIONS,
							'size_units' => array( 'px', '%' ),
							'selectors' => array(
								'{{WRAPPER}}.cmsmasters-search-type-slide.elementor-widget-cmsmasters-search-advanced__form-container-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								'{{WRAPPER}}.cmsmasters-search-type-slide-minimal .elementor-widget-cmsmasters-search-advanced__form-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							),
						)
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						array(
							'name' => 'form_box_shadow',
							'selector' => '{{WRAPPER}}.cmsmasters-search-type-slide.elementor-widget-cmsmasters-search-advanced__form-container-inner,
								{{WRAPPER}}.cmsmasters-search-type-slide-minimal .elementor-widget-cmsmasters-search-advanced__form-container',
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_submit_hover',
					array(
						'label' => __( 'Hover', 'cmsmasters-elementor' ),
					)
				);

					$this->add_control(
						'form_bg_color_hover',
						array(
							'label' => __( 'Background Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}}.cmsmasters-search-type-slide.elementor-widget-cmsmasters-search-advanced__form-container-inner:hover' => 'background-color: {{VALUE}}',
								'{{WRAPPER}}.cmsmasters-search-type-slide-minimal .elementor-widget-cmsmasters-search-advanced__form-container:hover' => 'background-color: {{VALUE}}',
							),
						)
					);

					$this->add_group_control(
						Group_Control_Border::get_type(),
						array(
							'name' => 'form_border_hover',
							'label' => __( 'Border', 'cmsmasters-elementor' ),
							'placeholder' => '1px',
							'selector' => '{{WRAPPER}}.cmsmasters-search-type-slide.elementor-widget-cmsmasters-search-advanced__form-container-inner:hover,
								{{WRAPPER}}.cmsmasters-search-type-slide-minimal .elementor-widget-cmsmasters-search-advanced__form-container:hover',
						)
					);

					$this->add_responsive_control(
						'form_padding_hover',
						array(
							'label' => __( 'Button Padding', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::DIMENSIONS,
							'size_units' => array( 'px' ),
							'selectors' => array(
								'{{WRAPPER}}.cmsmasters-search-type-slide.elementor-widget-cmsmasters-search-advanced__form-container-inner:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								'{{WRAPPER}}.cmsmasters-search-type-slide-minimal .elementor-widget-cmsmasters-search-advanced__form-container:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							),
						)
					);

					$this->add_responsive_control(
						'form_border_radius_hover',
						array(
							'label' => __( 'Border Radius', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::DIMENSIONS,
							'size_units' => array( 'px', '%' ),
							'selectors' => array(
								'{{WRAPPER}}.cmsmasters-search-type-slide.elementor-widget-cmsmasters-search-advanced__form-container-inner:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								'{{WRAPPER}}.cmsmasters-search-type-slide-minimal .elementor-widget-cmsmasters-search-advanced__form-container:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							),
						)
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						array(
							'name' => 'form_box_shadow_hover',
							'selector' => '{{WRAPPER}}.cmsmasters-search-type-slide.elementor-widget-cmsmasters-search-advanced__form-container-inner:hover,
								{{WRAPPER}}.cmsmasters-search-type-slide-minimal .elementor-widget-cmsmasters-search-advanced__form-container:hover',
						)
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_input_field_style',
			array(
				'label' => __( 'Input Field', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name' => 'input_field_typography',
					'selectors' => array(
						'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field',
						'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field::-webkit-input-placeholder',
						'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field::-moz-placeholder',
						'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field:-ms-input-placeholder',
					),
				)
			);

			$this->start_controls_tabs( 'input_field_tabs' );

				$this->start_controls_tab(
					'input_field_tab_normal',
					array( 'label' => __( 'Normal', 'cmsmasters-elementor' ) )
				);

					$this->add_control(
						'input_field_color',
						array(
							'label' => __( 'Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field' => 'color: {{VALUE}}',
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field::-webkit-input-placeholder' => 'color: {{VALUE}}',
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field::-moz-placeholder' => 'color: {{VALUE}}',
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field:-ms-input-placeholder' => 'color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'input_field_bg_color',
						array(
							'label' => __( 'Background Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field' => 'background-color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'input_field_border_color',
						array(
							'label' => __( 'Border Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field' => 'border-color: {{VALUE}}',
							),
							'conditions' => array(
								'relation' => 'and',
								'terms' => array(
									array(
										'name' => 'input_field_border_border',
										'operator' => '!==',
										'value' => '',
									),
									array(
										'relation' => 'or',
										'terms' => array(
											array(
												'name' => 'input_field_border_width[top]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'input_field_border_width[right]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'input_field_border_width[bottom]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'input_field_border_width[left]',
												'operator' => '>',
												'value' => '0',
											),
										),
									),
								),
							),
						)
					);

					$this->add_responsive_control(
						'input_field_border_radius',
						array(
							'label' => __( 'Border Radius', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::DIMENSIONS,
							'size_units' => array(
								'px',
								'%',
							),
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							),
						)
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						array(
							'name' => 'input_field_box_shadow',
							'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field',
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'input_field_tab_focus',
					array( 'label' => __( 'Focus', 'cmsmasters-elementor' ) )
				);

					$this->add_control(
						'input_field_color_focus',
						array(
							'label' => __( 'Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field:focus' => 'color: {{VALUE}}',
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field:focus::-webkit-input-placeholder' => 'color: {{VALUE}}',
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field:focus::-moz-placeholder' => 'color: {{VALUE}}',
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field:focus:-ms-input-placeholder' => 'color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'input_field_bg_color_focus',
						array(
							'label' => __( 'Background Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field:focus' => 'background-color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'input_field_border_color_focus',
						array(
							'label' => __( 'Border Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field:focus' => 'border-color: {{VALUE}}',
							),
							'conditions' => array(
								'relation' => 'and',
								'terms' => array(
									array(
										'name' => 'input_field_border_border',
										'operator' => '!==',
										'value' => '',
									),
									array(
										'relation' => 'or',
										'terms' => array(
											array(
												'name' => 'input_field_border_width[top]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'input_field_border_width[right]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'input_field_border_width[bottom]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'input_field_border_width[left]',
												'operator' => '>',
												'value' => '0',
											),
										),
									),
								),
							),
						)
					);

					$this->add_responsive_control(
						'input_field_border_radius_focus',
						array(
							'label' => __( 'Border Radius', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::DIMENSIONS,
							'size_units' => array(
								'px',
								'%',
							),
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							),
						)
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						array(
							'name' => 'input_field_box_shadow_focus',
							'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field:focus',
						)
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_responsive_control(
				'input_field_padding',
				array(
					'label' => __( 'Padding', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
					),
					'default' => array(
						'top' => '20',
						'left' => '20',
						'bottom' => '20',
						'right' => '20',
					),
					'separator' => 'before',
					'selectors' => array(
						'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__input-icon' => 'padding: 0 0 0 {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name' => 'input_field_border',
					'label' => __( 'Border', 'cmsmasters-elementor' ),
					'exclude' => array( 'color' ),
					'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__field',
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_popup_close_style',
			array(
				'label' => __( 'Popup Close', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => array( 'type_of_search' => 'slide-minimal' ),
			)
		);

			$this->start_controls_tabs( 'tabs_popup_close_style' );

				$this->start_controls_tab(
					'tab_popup_close_normal',
					array( 'label' => __( 'Normal', 'cmsmasters-elementor' ) )
				);

					$this->add_control(
						'popup_close_color',
						array(
							'label' => __( 'Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__popup-close' => 'color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'popup_close_bg_color',
						array(
							'label' => __( 'Background Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__popup-close' => 'background-color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'popup_close_border_color',
						array(
							'label' => __( 'Border Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__popup-close' => 'border-color: {{VALUE}}',
							),
							'conditions' => array(
								'relation' => 'and',
								'terms' => array(
									array(
										'name' => 'popup_close_border_border',
										'operator' => '!==',
										'value' => '',
									),
									array(
										'relation' => 'or',
										'terms' => array(
											array(
												'name' => 'popup_close_border_width[top]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'popup_close_border_width[right]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'popup_close_border_width[bottom]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'popup_close_border_width[left]',
												'operator' => '>',
												'value' => '0',
											),
										),
									),
								),
							),
						)
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						array(
							'name' => 'popup_close_box_shadow',
							'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__popup-close',
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_popup_close_hover',
					array( 'label' => __( 'Hover', 'cmsmasters-elementor' ) )
				);

					$this->add_control(
						'popup_close_color_hover',
						array(
							'label' => __( 'Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__popup-close:hover' => 'color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'popup_close_bg_color_hover',
						array(
							'label' => __( 'Background Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__popup-close:hover' => 'background-color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'popup_close_border_color_hover',
						array(
							'label' => __( 'Border Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__popup-close:hover' => 'border-color: {{VALUE}}',
							),
							'conditions' => array(
								'relation' => 'and',
								'terms' => array(
									array(
										'name' => 'popup_close_border_border',
										'operator' => '!==',
										'value' => '',
									),
									array(
										'relation' => 'or',
										'terms' => array(
											array(
												'name' => 'popup_close_border_width[top]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'popup_close_border_width[right]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'popup_close_border_width[bottom]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'popup_close_border_width[left]',
												'operator' => '>',
												'value' => '0',
											),
										),
									),
								),
							),
						)
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						array(
							'name' => 'popup_close_box_shadow_hover',
							'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__popup-close:hover',
						)
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_responsive_control(
				'popup_close_padding',
				array(
					'label' => __( 'Padding', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
					),
					'separator' => 'before',
					'selectors' => array(
						'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__popup-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'popup_close_margin',
				array(
					'label' => __( 'Margin', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
					),
					'selectors' => array(
						'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__popup-close' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name' => 'popup_close_border',
					'label' => __( 'Border', 'cmsmasters-elementor' ),
					'exclude' => array( 'color' ),
					'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__popup-close',
				)
			);

			$this->add_responsive_control(
				'popup_close_border_radius',
				array(
					'label' => __( 'Border Radius', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
					),
					'selectors' => array(
						'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__popup-close' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_submit_button_style',
			array(
				'label' => __( 'Submit Button', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => array( 'submit_button_view!' => 'none' ),
			)
		);

			$this->add_responsive_control(
				'submit_button_gap',
				array(
					'label' => __( 'Gap Between', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'default' => array( 'size' => 0 ),
					'selectors' => array(
						'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__submit' => 'margin-left: {{SIZE}}{{UNIT}};',
					),
					'condition' => array( 'submit_button_view' => 'button' ),
				)
			);

			$this->start_controls_tabs( 'tabs_submit_button_style' );

				$this->start_controls_tab(
					'tabs_submit_button_normal',
					array( 'label' => __( 'Normal', 'cmsmasters-elementor' ) )
				);

					$this->add_control(
						'submit_button_color',
						array(
							'label' => __( 'Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__submit,
								{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__form-icon' => 'color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'submit_button_bg_color',
						array(
							'label' => __( 'Background Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__submit' => 'background-color: {{VALUE}}',
							),
							'condition' => array( 'submit_button_view!' => 'icon' ),
						)
					);

					$this->add_control(
						'submit_button_border_color',
						array(
							'label' => __( 'Border Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__submit' => 'border-color: {{VALUE}}',
							),
							'conditions' => array(
								'relation' => 'and',
								'terms' => array(
									array(
										'name' => 'submit_button_view',
										'operator' => '!==',
										'value' => 'icon',
									),
									array(
										'name' => 'submit_button_border_border',
										'operator' => '!==',
										'value' => '',
									),
									array(
										'relation' => 'or',
										'terms' => array(
											array(
												'name' => 'submit_button_border_width[top]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'submit_button_border_width[right]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'submit_button_border_width[bottom]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'submit_button_border_width[left]',
												'operator' => '>',
												'value' => '0',
											),
										),
									),
								),
							),
						)
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						array(
							'name' => 'submit_button_box_shadow',
							'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__submit,
								{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__form-icon',
							'condition' => array( 'submit_button_view!' => 'icon' ),
						)
					);

					$this->add_group_control(
						Group_Control_Text_Shadow::get_type(),
						array(
							'name' => 'submit_button_text_shadow',
							'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__form-icon',
							'fields_options' => array(
								'text_shadow_type' => array( 'label' => __( 'Text Shadow', 'cmsmasters-elementor' ) ),
							),
							'condition' => array( 'submit_button_view' => 'icon' ),
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tabs_submit_button_hover',
					array( 'label' => __( 'Hover', 'cmsmasters-elementor' ) )
				);

					$this->add_control(
						'submit_button_color_hover',
						array(
							'label' => __( 'Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__submit:hover,
								{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__form-icon:hover' => 'color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'submit_button_bg_color_hover',
						array(
							'label' => __( 'Background Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__submit:hover,
								{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__form-icon:hover' => 'background-color: {{VALUE}}',
							),
							'condition' => array( 'submit_button_view!' => 'icon' ),
						)
					);

					$this->add_control(
						'submit_button_border_color_hover',
						array(
							'label' => __( 'Border Color', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__submit:hover' => 'border-color: {{VALUE}}',
							),
							'conditions' => array(
								'relation' => 'and',
								'terms' => array(
									array(
										'name' => 'submit_button_view',
										'operator' => '!==',
										'value' => 'icon',
									),
									array(
										'name' => 'submit_button_border_border',
										'operator' => '!==',
										'value' => '',
									),
									array(
										'relation' => 'or',
										'terms' => array(
											array(
												'name' => 'submit_button_border_width[top]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'submit_button_border_width[right]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'submit_button_border_width[bottom]',
												'operator' => '>',
												'value' => '0',
											),
											array(
												'name' => 'submit_button_border_width[left]',
												'operator' => '>',
												'value' => '0',
											),
										),
									),
								),
							),
						)
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						array(
							'name' => 'submit_button_box_shadow_hover',
							'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__submit:hover,
								{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__form-icon:hover',
							'condition' => array( 'submit_button_view!' => 'icon' ),
						)
					);

					$this->add_group_control(
						Group_Control_Text_Shadow::get_type(),
						array(
							'name' => 'submit_button_text_shadow_hover',
							'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__form-icon:hover',
							'fields_options' => array(
								'text_shadow_type' => array( 'label' => __( 'Text Shadow', 'cmsmasters-elementor' ) ),
							),
							'condition' => array( 'submit_button_view' => 'icon' ),
						)
					);

					$this->add_control(
						'submit_button_transition',
						array(
							'label' => __( 'Transition Duration', 'cmsmasters-elementor' ),
							'type' => Controls_Manager::SLIDER,
							'range' => array(
								'px' => array(
									'max' => 3,
									'step' => 0.1,
								),
							),
							'selectors' => array(
								'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__submit' => 'transition-duration: {{SIZE}}s',
							),
						)
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_responsive_control(
				'submit_button_padding',
				array(
					'label' => __( 'Padding', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px' ),
					'default' => array(
						'top' => '10',
						'right' => '10',
						'bottom' => '10',
						'left' => '10',
					),
					'separator' => 'before',
					'selectors' => array(
						'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition' => array(
						'submit_button_view' => 'button',
						'submit_button_type!' => 'icon',
					),
				)
			);

			$this->add_responsive_control(
				'submit_button_size',
				array(
					'label' => __( 'Button Min Size', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range' => array(
						'px' => array( 'min' => 10 ),
					),
					'default' => array( 'size' => 61 ),
					'separator' => 'before',
					'selectors' => array(
						'{{WRAPPER}} .cmsmasters-buttons-type-icon .elementor-widget-cmsmasters-search-advanced__submit' => 'min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						'submit_button_view' => 'button',
						'submit_button_type' => 'icon',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name' => 'submit_button_border',
					'label' => __( 'Border', 'cmsmasters-elementor' ),
					'exclude' => array( 'color' ),
					'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__submit',
					'condition' => array( 'submit_button_view' => 'button' ),
				)
			);

			$this->add_responsive_control(
				'submit_button_border_radius',
				array(
					'label' => __( 'Border Radius', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
					),
					'selectors' => array(
						'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__submit,
						{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__form-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition' => array( 'submit_button_view!' => 'icon' ),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name' => 'submit_button_typography',
					'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__submit',
					'condition' => array( 'submit_button_type!' => 'icon' ),
				)
			);

			$this->add_responsive_control(
				'submit_button_icon_size',
				array(
					'label' => __( 'Icon Size', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range' => array(
						'px' => array(
							'min' => 10,
							'max' => 100,
						),
					),
					'default' => array( 'size' => 20 ),
					'selectors' => array(
						'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__submit-icon,
						{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__form-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'condition' => array( 'submit_button_type!' => 'text' ),
				)
			);

			$this->add_responsive_control(
				'submit_button_icon_gap',
				array(
					'label' => __( 'Icon Gap', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'selectors' => array(
						'{{WRAPPER}} .elementor-widget-cmsmasters-search-advanced__submit-icon + .elementor-widget-cmsmasters-search-advanced__submit-label' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',
						'{{WRAPPER}}.cmsmasters-buttons-icon-position-right .elementor-widget-cmsmasters-search-advanced__submit-icon + .elementor-widget-cmsmasters-search-advanced__submit-label' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: 0;',
					),
					'condition' => array(
						'submit_button_view' => 'button',
						'submit_button_type' => 'both',
					),
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Render search advanced widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		echo '<div class="elementor-widget-cmsmasters-search-advanced__container">';
			$this->get_form_search();
			$this->get_form_trigger();
		echo '</div>';
	}

	/**
	 * Render search form.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	public function get_form_search() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'search-form-container', 'class', 'elementor-widget-cmsmasters-search-advanced__form-container' );

		if ( 'none' !== $settings['submit_button_view'] ) {
			$this->add_render_attribute( 'search-form-container', 'class', 'cmsmasters-button-type-' . $settings['submit_button_view'] );
		}

		if ( isset( $settings['slide_show_effect'] ) ) {
			$this->add_render_attribute( 'search-form-container', 'class', sprintf( 'cmsmasters-search-%s-effect', $settings['slide_show_effect'] ) );
		}

		if ( isset( $settings['slide_form_width_type'] ) && 'fixed' === $settings['slide_form_width_type'] ) {
			$this->add_render_attribute( 'search-form-container', 'class', 'cmsmasters-relative-type-fixed' );
		} else {
			$this->add_render_attribute( 'search-form-container', 'class', 'cmsmasters-relative-type-dynamic' );
		}

		if ( isset( $settings['slide_position_relatively'] ) && 'button' !== $settings['slide_form_relative'] ) {
			$this->add_render_attribute( 'search-form-container', 'class', 'cmsmasters-search-container-position-' . $settings['slide_position_relatively'] );
		} elseif ( isset( $settings['slide_position_relatively_button'] ) && 'button' === $settings['slide_form_relative'] ) {
			$this->add_render_attribute( 'search-form-container', 'class', 'cmsmasters-search-container-position-' . $settings['slide_position_relatively_button'] );
		}

		if ( 'slide' === $settings['type_of_search'] ) {
			if ( 'button' === $settings['slide_form_relative'] ) {
				$this->add_render_attribute( 'search-form-container', 'class', 'cmsmasters-form-relative-to-button' );
			} elseif ( 'block' === $settings['slide_form_relative'] ) {
				$this->add_render_attribute( 'search-form-container', 'class', 'cmsmasters-form-relative-to-block' );
			}
		}

		$this->add_render_attribute( 'search-from', array(
			'role' => 'search',
			'method' => 'get',
			'class' => 'elementor-widget-cmsmasters-search-advanced__form-container-inner',
			'action' => esc_url( home_url( '/' ) ),
		) );

		echo '<div class="elementor-widget-cmsmasters-search-advanced__form">' .
			'<div ' . $this->get_render_attribute_string( 'search-form-container' ) . '>' .
				'<form ' . $this->get_render_attribute_string( 'search-from' ) . '>';

		$this->add_render_attribute( 'search-form-field', array(
			'type' => 'search',
			'class' => 'elementor-widget-cmsmasters-search-advanced__field',
			'value' => get_search_query(),
			'name' => 's',
		) );

		if ( '' !== $settings['search_placeholder'] ) {
			$this->add_render_attribute( 'search-form-field', 'placeholder', esc_attr( $settings['search_placeholder'] ) );
		} else {
			$this->add_render_attribute( 'search-form-field', 'placeholder', 'Search...' );
		}

		echo '<input ' . $this->get_render_attribute_string( 'search-form-field' ) . ' />';

		if ( 'button' === $settings['submit_button_view'] ) {
			echo '<button type="submit" class="elementor-widget-cmsmasters-search-advanced__submit">';

			if ( 'text' !== $settings['submit_button_type'] ) {
				echo '<i class="elementor-widget-cmsmasters-search-advanced__submit-icon ' . $settings['submit_button_icon']['value'] . '"></i>';
			}

			if ( 'icon' !== $settings['submit_button_type'] ) {
				echo '<div class="elementor-widget-cmsmasters-search-advanced__submit-label">' . $settings['submit_button_label'] . '</div>';
			}

			echo '</button>';
		} elseif ( 'icon' === $settings['submit_button_view'] ) {
			echo '<button type="submit" class="elementor-widget-cmsmasters-search-advanced__form-icon">' .
				'<i class="' . $settings['submit_button_icon']['value'] . '" aria-hidden="true"></i>' .
			'</button>';
		}

				echo '</form>';

		if ( 'slide-minimal' === $settings['type_of_search'] ) {
			$this->get_form_close();
		}

		if ( isset( $settings['is_product_search'] ) && 'true' === isset( $settings['is_product_search'] ) ) {
			echo '<input type="hidden" name="post_type" value="product" />';
		}

			echo '</div>' .
		'</div>';
	}

	/**
	 * Render search close button.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	public function get_form_close() {
		$settings = $this->get_settings_for_display();

		$slide_close_content = $settings['slide_close_content'];

		$this->add_render_attribute( 'close-class', 'class', array(
			'elementor-widget-cmsmasters-search-advanced__popup-close',
			'cmsmasters-close-type-' . $settings['slide_close_type'],
			'cmsmasters-close-content-' . $slide_close_content,
		) );

		echo '<button ' . $this->get_render_attribute_string( 'close-class' ) . '>';

		if ( 'text' !== $slide_close_content ) {
			echo '<i class="elementor-widget-cmsmasters-search-advanced__popup-close-icon ' . $settings['popup_close_icon']['value'] . '"></i>';
		}

		if ( 'icon' !== $slide_close_content ) {
			$slide_close_label_mini = $settings['slide_close_label_mini'];

			echo '<span class="elementor-widget-cmsmasters-search-advanced__popup-close-label">' .
				( '' !== $slide_close_label_mini ? $slide_close_label_mini : __( 'Cancel', 'cmsmasters-elementor' ) ) .
			'</span>';
		}

		echo '</button>';
	}

	/**
	 * Render search trigger.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	public function get_form_trigger() {
		$settings = $this->get_settings_for_display();

		$trigger_tag = 'button';

		if ( 'link' === $settings['popup_trigger_type'] ) {
			$trigger_tag = 'div';
		}

		$this->add_render_attribute( 'trigger-class', 'class', 'elementor-widget-cmsmasters-search-advanced__button' );

		$type_of_search = $settings['type_of_search'];

		if ( 'slide' === $type_of_search ) {
			$this->add_render_attribute( 'trigger-class', 'class', 'cmsmasters-button-full' );
		}

		echo '<' . $trigger_tag . ' ' . $this->get_render_attribute_string( 'trigger-class' ) . '>';

		$popup_trigger_content = $settings['popup_trigger_content'];

		if ( 'text' !== $popup_trigger_content ) {
			echo '<i class="elementor-widget-cmsmasters-search-advanced__button-icon ' . $settings['submit_button_icon']['value'] . '"></i>';

			if ( 'slide' === $type_of_search ) {
				echo '<i class="elementor-widget-cmsmasters-search-advanced__button-icon close ' . $settings['popup_trigger_icon_hover']['value'] . '"></i>';
			}
		}

		if ( 'icon' !== $popup_trigger_content ) {
			$popup_trigger_label = $settings['popup_trigger_label'];

			echo '<span class="elementor-widget-cmsmasters-search-advanced__button-label">';

			if ( '' !== $popup_trigger_label ) {
				echo esc_attr( $popup_trigger_label );
			} else {
				echo esc_attr__( 'Search', 'cmsmasters-elementor' );
			}

			echo '</span>';

			$slide_close_label = $settings['slide_close_label'];

			if ( 'slide' === $type_of_search ) {
				echo '<span class="elementor-widget-cmsmasters-search-advanced__button-label close">';

				if ( '' !== $slide_close_label ) {
					echo esc_attr( $slide_close_label );
				} else {
					echo esc_attr__( 'Cancel', 'cmsmasters-elementor' );
				}

				echo '</span>';
			}
		}

		echo '</' . $trigger_tag . '>';
	}

	/**
	 * Render widget plain content.
	 *
	 * Save generated HTML to the database as plain content.
	 *
	 * @since 1.0.0
	 */
	public function render_plain_content() {}

	/**
	 * Render search advanced widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 */
	protected function content_template() {
		?><#
		var search_html = '<div class="elementor-widget-cmsmasters-search-advanced__container">';

			view.addRenderAttribute( 'search-form-container', 'class', 'elementor-widget-cmsmasters-search-advanced__form-container' );

			if ( 'none' !== settings.submit_button_view ) {
				view.addRenderAttribute( 'search-form-container', 'class', 'cmsmasters-button-type-' + settings.submit_button_view );
			}

			if ( undefined !== settings.slide_show_effect ) {
				view.addRenderAttribute( 'search-form-container', 'class', 'cmsmasters-search-' + settings.slide_show_effect  + '-effect' );
			}

			if ( undefined !== settings.slide_form_width_type && 'fixed' === settings.slide_form_width_type ) {
				view.addRenderAttribute( 'search-form-container', 'class', 'cmsmasters-relative-type-fixed' );
			} else {
				view.addRenderAttribute( 'search-form-container', 'class', 'cmsmasters-relative-type-dynamic' );
			}

			if ( undefined !== settings.slide_position_relatively && 'button' !== settings.slide_form_relative ) {
				view.addRenderAttribute( 'search-form-container', 'class', 'cmsmasters-search-container-position-' + settings.slide_position_relatively );
			} else if ( undefined !== settings.slide_position_relatively_button && 'button' === settings.slide_form_relative ) {
				view.addRenderAttribute( 'search-form-container', 'class', 'cmsmasters-search-container-position-' + settings.slide_position_relatively_button );
			}

			if ( 'slide' === settings.type_of_search ) {
				if ( 'button' === settings.slide_form_relative ) {
					view.addRenderAttribute( 'search-form-container', 'class', 'cmsmasters-form-relative-to-button' );
				} else if ( 'block' === settings.slide_form_relative ) {
					view.addRenderAttribute( 'search-form-container', 'class', 'cmsmasters-form-relative-to-block' );
				}
			}

			if ( obj.view.$el.data( 'active' ) && obj.view.$el.hasClass( 'cmsmasters-search-type-slide' ) ) {
				view.addRenderAttribute('search-form-container', 'class', 'active');
			}

			view.addRenderAttribute( 'search-from', {
				'role' : 'search',
				'method' : 'get',
				'class' : 'elementor-widget-cmsmasters-search-advanced__form-container-inner',
				'action' : '<?php echo esc_url( home_url( '/' ) ) ?>',
			} );

			search_html += '<div class="elementor-widget-cmsmasters-search-advanced__form">';

			search_html += '<div ' + view.getRenderAttributeString( 'search-form-container' ) + '>';
			search_html += '<form ' + view.getRenderAttributeString( 'search-from' ) + '>';

			view.addRenderAttribute( 'search-form-field', {
				'type' : 'search',
				'class' : 'elementor-widget-cmsmasters-search-advanced__field',
				'value' : '<?php echo get_search_query() ?>',
				'name' : 's',
			} );

			if ( '' !== settings.search_placeholder ) {
				view.addRenderAttribute( 'search-form-field', 'placeholder', settings.search_placeholder );
			} else {
				view.addRenderAttribute( 'search-form-field', 'placeholder', 'Search...' );
			}

			search_html += '<input ' + view.getRenderAttributeString( 'search-form-field' ) + ' />';

			if ( 'button' === settings.submit_button_view ) {
				search_html += '<button type="submit" class="elementor-widget-cmsmasters-search-advanced__submit">';

				if ( 'text' !== settings.submit_button_type ) 
				{
					search_html += '<i class="elementor-widget-cmsmasters-search-advanced__submit-icon ' + settings.submit_button_icon.value + '"></i>';
				}

				if ( 'icon' !== settings.submit_button_type ) {
					search_html += '<div class="elementor-widget-cmsmasters-search-advanced__submit-label">' + settings.submit_button_label + '</div>';
				}

				search_html += '</button>';
			} else if ( 'icon' === settings.submit_button_view ) {
				search_html += '<button type="submit" class="elementor-widget-cmsmasters-search-advanced__form-icon">' +
					'<i class="' + settings.submit_button_icon.value + '" aria-hidden="true"></i>' +
				'</button>';
			}

			search_html += '</form>';

			if ( 'slide-minimal' === settings.type_of_search ) {
				view.addRenderAttribute( 'close-class', 'class', 'elementor-widget-cmsmasters-search-advanced__popup-close' );

				if ( undefined !== settings.slide_close_type ) {
					view.addRenderAttribute( 'close-class', 'class', 'cmsmasters-close-type-' + settings.slide_close_type );

					view.addRenderAttribute( 'close-class', 'class', 'cmsmasters-close-content-' + settings.slide_close_content );
				}

				search_html += '<button ' + view.getRenderAttributeString( 'close-class' ) + '>';

				if ( 'text' !== settings.slide_close_content ) {
					search_html += '<i class="elementor-widget-cmsmasters-search-advanced__popup-close-icon ' + settings.popup_close_icon.value + '"></i>';
				}

				if ( 'icon' !== settings.slide_close_content ) {
					search_html += '<span class="elementor-widget-cmsmasters-search-advanced__popup-close-label">' + settings.slide_close_label_mini + '</span>';
				}

				search_html += '</button>';
			}

			if ( undefined !== settings.is_product_search && undefined !== settings.is_product_search ) {
				search_html += '<input type="hidden" name="post_type" value="product" />';
			}

			search_html += '</div>' +
			'</div>';

			var trigger_tag = 'button';

			if ( 'link' === settings.popup_trigger_type ) {
				trigger_tag = 'div';
			}

			view.addRenderAttribute( 'trigger-class', 'class', 'elementor-widget-cmsmasters-search-advanced__button' );

			if ( 'slide' === settings.type_of_search ) {
				view.addRenderAttribute( 'trigger-class', 'class', 'cmsmasters-button-full' );
			}

			if ( obj.view.$el.data( 'active' ) ) {
				view.addRenderAttribute('trigger-class', 'class', 'active');
			}

			search_html += '<' + trigger_tag + ' ' + view.getRenderAttributeString( 'trigger-class' ) + '>';

			if ( 'text' !== settings.popup_trigger_content ) {
				search_html += '<i class="elementor-widget-cmsmasters-search-advanced__button-icon ' + settings.submit_button_icon.value + '"></i>';

				if ( 'slide' === settings.type_of_search ) {
					search_html += '<i class="elementor-widget-cmsmasters-search-advanced__button-icon close ' + settings.popup_trigger_icon_hover.value + '"></i>';
				}
			}

			if ( 'icon' !== settings.popup_trigger_content ) {
				search_html += '<span class="elementor-widget-cmsmasters-search-advanced__button-label">' + settings.popup_trigger_label + '</span>';

				if ( 'slide' === settings.type_of_search ) {
					search_html += '<span class="elementor-widget-cmsmasters-search-advanced__button-label close">' + settings.slide_close_label + '</span>';
				}
			}

			search_html += '</' + trigger_tag + '>';

		search_html += '</div>';

		print(search_html);
		#><?php
	}

}
