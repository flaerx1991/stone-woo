<?php
namespace CmsmastersElementor\Modules\Theme\Widgets;

use CmsmastersElementor\Controls_Manager as CmsmastersControls;
use CmsmastersElementor\Base\Base_Document;
use CmsmastersElementor\Base\Base_Widget;
use CmsmastersElementor\Modules\Wordpress\Managers\Query_Manager;

use Elementor\Controls_Manager;
use Elementor\Core\Base\Document;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Theme_My_Account extends Base_Widget {

	/**
	 * Get widget name.
	 *
	 * Retrieve theme my account widget name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'cmsmasters-theme-my-account';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve widget title.
	 *
	 * @since 1.8.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Theme My Account', 'cmsmasters-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @since 1.8.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'cmsicon-my-account';
	}

	/**
	 * Get widget unique keywords.
	 *
	 * Retrieve the list of unique keywords the widget belongs to.
	 *
	 * @since 1.8.0
	 *
	 * @return array Widget unique keywords.
	 */
	public function get_unique_keywords() {
		return array(
			'woocommerce',
			'account',
			'page',
		);
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the widget categories.
	 *
	 * @since 1.8.0
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( Base_Document::WOO_WIDGETS_CATEGORY );
	}

	public function get_widget_class() {
		return 'elementor-widget-cmsmasters-theme-my-account';
	}

	public function get_widget_selector() {
		return '.' . $this->get_widget_class();
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.8.0
	 * @since 1.10.1 Added `Tab Name` control for wishlist and compare.
	 * @since 1.11.4 Added new `Login Form` tab controls for login form. Fixed password reset.
	 */
	protected function register_controls() {
		$this->register_tabs_controls_content();

		$this->register_tabs_controls_style();

		$this->register_sections_controls_style();

		$this->register_typography_controls_style();

		$this->register_tables_controls_style();

		$this->register_forms_controls_style();

		$this->register_buttons_controls_style();

		$this->register_login_form_controls_style();
	}

	protected function register_tabs_controls_content() {
		$this->start_controls_section(
			'tabs_section_content',
			array( 'label' => esc_html__( 'Tabs', 'cmsmasters-elementor' ) )
		);

		$this->add_control(
			'tabs_layout',
			array(
				'label' => esc_html__( 'Layout', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'vertical' => esc_html__( 'Vertical', 'cmsmasters-elementor' ),
					'horizontal' => esc_html__( 'Horizontal', 'cmsmasters-elementor' ),
				),
				'default' => 'vertical',
				'render_type' => 'template',
				'prefix_class' => 'cmsmasters-my-account-tabs-',
			)
		);

		$this->add_responsive_control(
			'tabs_alignment',
			array(
				'label' => esc_html__( 'Alignment', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'start' => array(
						'title' => esc_html__( 'Start', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-center',
					),
					'end' => array(
						'title' => esc_html__( 'End', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--tabs-alignment: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'label' => __( 'Image', 'cmsmasters-elementor' ),
				'name' => 'thumbnail',
				'default' => 'thumbnail',
			)
		);

		$this->add_control(
			'tabs_position',
			array(
				'label' => esc_html__( 'Tabs Position', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'start' => array(
						'title' => esc_html__( 'Start', 'cmsmasters-elementor' ),
						'icon' => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'cmsmasters-elementor' ),
						'icon' => 'eicon-h-align-center',
					),
					'end' => array(
						'title' => esc_html__( 'End', 'cmsmasters-elementor' ),
						'icon' => 'eicon-h-align-right',
					),
					'stretch' => array(
						'title' => esc_html__( 'Stretch', 'cmsmasters-elementor' ),
						'icon' => 'eicon-h-align-stretch',
					),
				),
				'selectors_dictionary' => array(
					'start' => '--tabs-container-justify-content: flex-start; --tab-width: auto',
					'center' => '--tabs-container-justify-content: center; --tab-width: auto',
					'end' => '--tabs-container-justify-content: flex-end; --tab-width: auto',
					'stretch' => '--tabs-container-justify-content: space-between; --tab-width: 100%',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '{{VALUE}}',
				),
				'condition' => array( 'tabs_layout' => 'horizontal' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'tab_name',
			array(
				'label' => esc_html__( 'Tab Name', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'order_display_description',
			array(
				'raw' => esc_html__( 'Note: By default, only your last order is displayed while editing the orders section. You can see other orders on your live site or in the WooCommerce orders section', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'condition' => array( 'field_key' => 'orders' ),
			)
		);

		$tabs_list = array(
			array(
				'field_key' => 'dashboard',
				'field_label' => esc_html__( 'Dashboard', 'cmsmasters-elementor' ),
				'tab_name' => esc_html__( 'Dashboard', 'cmsmasters-elementor' ),
			),
		);

		// Quotes.
		if ( defined( 'YITH_YWRAQ_FREE_INIT' ) || defined( 'YITH_YWRAQ_PREMIUM' ) ) {
			$tabs_list = array_merge( $tabs_list, array(
				array(
					'field_key' => 'quotes',
					'field_label' => esc_html__( 'Quotes', 'cmsmasters-elementor' ),
					'tab_name' => esc_html__( 'Quotes', 'cmsmasters-elementor' ),
				),
			) );
		}

		$tabs_list = array_merge( $tabs_list, array(
			array(
				'field_key' => 'orders',
				'field_label' => esc_html__( 'Orders', 'cmsmasters-elementor' ),
				'tab_name' => esc_html__( 'Orders', 'cmsmasters-elementor' ),
			),
			array(
				'field_key' => 'downloads',
				'field_label' => esc_html__( 'Downloads', 'cmsmasters-elementor' ),
				'tab_name' => esc_html__( 'Downloads', 'cmsmasters-elementor' ),
			),
			array(
				'field_key' => 'edit-address',
				'field_label' => esc_html__( 'Addresses', 'cmsmasters-elementor' ),
				'tab_name' => esc_html__( 'Addresses', 'cmsmasters-elementor' ),
			),
			array(
				'field_key' => 'edit-account',
				'field_label' => esc_html__( 'Account Details', 'cmsmasters-elementor' ),
				'tab_name' => esc_html__( 'Account Details', 'cmsmasters-elementor' ),
			),
		) );

		// Wishlist.
		if ( class_exists( 'WPCleverWoosw' ) ) {
			$tabs_list = array_merge( $tabs_list, array(
				array(
					'field_key' => 'wishlist',
					'field_label' => esc_html__( 'Wishlist', 'cmsmasters-elementor' ),
					'tab_name' => esc_html__( 'Wishlist', 'cmsmasters-elementor' ),
				),
			) );
		}

		// Compare.
		if ( class_exists( 'WPCleverWoosc' ) ) {
			$tabs_list = array_merge( $tabs_list, array(
				array(
					'field_key' => 'compare',
					'field_label' => esc_html__( 'Compare', 'cmsmasters-elementor' ),
					'tab_name' => esc_html__( 'Compare', 'cmsmasters-elementor' ),
				),
			) );
		}

		$tabs_list = array_merge( $tabs_list, array(
			array(
				'field_key' => 'customer-logout',
				'field_label' => esc_html__( 'Logout', 'cmsmasters-elementor' ),
				'tab_name' => esc_html__( 'Logout', 'cmsmasters-elementor' ),
			),
		) );

		$this->add_control(
			'tabs',
			array(
				'label' => '',
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'item_actions' => array(
					'add' => false,
					'duplicate' => false,
					'remove' => false,
					'sort' => false,
				),
				'default' => $tabs_list,
				'show_label' => false,
				'title_field' => '{{{ tab_name }}}',
			)
		);

		$this->add_control(
			'tabs_responsive_view',
			array(
				'label' => __( 'Responsive View', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'tablet' => __( 'Tablet', 'cmsmasters-elementor' ),
					'mobile' => __( 'Mobile', 'cmsmasters-elementor' ),
				),
				'label_block' => false,
				'toggle' => false,
				'default' => 'tablet',
				'render_type' => 'template',
				'prefix_class' => 'cmsmasters-my-account-tabs-responsive-view-',
			)
		);

		$this->add_control(
			'general_default_my_account_page',
			array(
				'raw' => __( 'You can set the default Theme My Account page by going to the ', 'cmsmasters-elementor' ) . '<a href="admin.php?page=go_theme_settings" target="_blank">' . __( 'Theme Settings', 'cmsmasters-elementor' ) . '</a>' . __( ' -> WooCommerce -> WooCommerce Pages', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'render_type' => 'ui',
			)
		);

		$this->end_controls_section();
	}

	protected function register_tabs_controls_style() {
		$this->start_controls_section(
			'tabs_section_style',
			array(
				'label' => esc_html__( 'Tabs', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'tabs_typography',
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tabs-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tabs-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tabs-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tabs-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tabs-font-style: {{VALUE}};',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tabs-text-decoration: {{VALUE}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tabs-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tabs-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tabs-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_responsive_control(
			'tabs_content_spacing',
			array(
				'label' => esc_html__( 'Spacing', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--tab-content-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_nav_tabs' );

		foreach ( array(
			'normal' => __( 'Normal', 'cmsmasters-elementor' ),
			'hover' => __( 'Hover', 'cmsmasters-elementor' ),
			'active' => __( 'Active', 'cmsmasters-elementor' ),
		) as $main_key => $label ) {
			$this->start_controls_tab(
				"tabs_nav_{$main_key}_tab",
				array( 'label' => $label )
			);

			$this->add_control(
				"tabs_nav_{$main_key}_color",
				array(
					'label' => esc_html__( 'Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}}' => "--tabs-nav-{$main_key}-color: {{VALUE}};",
					),
				)
			);

			$this->add_control(
				"tabs_nav_{$main_key}_bg_color",
				array(
					'label' => __( 'Background Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}}' => "--tabs-nav-{$main_key}-bg-color: {{VALUE}};",
					),
				)
			);

			$this->add_control(
				"tabs_nav_{$main_key}_border_color",
				array(
					'label' => esc_html__( 'Border Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}}' => "--tabs-nav-{$main_key}-border-color: {{VALUE}};",
					),
					'condition' => array( 'tabs_nav_border_type!' => 'none' ),
				)
			);

			$this->add_responsive_control(
				"tabs_nav_{$main_key}_border_radius",
				array(
					'label' => esc_html__( 'Border Radius', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
						'%',
					),
					'selectors' => array(
						'{{WRAPPER}}' => "--tabs-nav-{$main_key}-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
					),
				)
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				array(
					'name' => "tabs_nav_{$main_key}_text_shadow",
					'label' => esc_html__( 'Text Shadow', 'cmsmasters-elementor' ),
					'fields_options' => array(
						'text_shadow' => array(
							'selectors' => array(
								'{{SELECTOR}}' => "--tabs-nav-{$main_key}-text-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{COLOR}};",
							),
						),
					),
					'selector' => '{{WRAPPER}}',
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name' => "tabs_nav_{$main_key}_box_shadow",
					'label' => esc_html__( 'Box Shadow', 'cmsmasters-elementor' ),
					'fields_options' => array(
						'box_shadow' => array(
							'selectors' => array(
								'{{SELECTOR}}' => "--tabs-nav-{$main_key}-box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};",
							),
						),
					),
					'selector' => '{{WRAPPER}}',
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'tabs_nav_spacing',
			array(
				'label' => esc_html__( 'Spacing', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default' => array( 'px' => 0 ),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}}' => '--tabs-nav-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tabs_nav_padding',
			array(
				'label' => esc_html__( 'Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--tabs-nav-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'tabs_nav_border_type',
			array(
				'label' => esc_html__( 'Border Type', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_custom_border_type_options(),
				'selectors' => array(
					'{{WRAPPER}}' => '--tabs-nav-border-type: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'tabs_nav_border_width',
			array(
				'label' => esc_html__( 'Border Width', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--tabs-nav-border-top-width: {{TOP}}{{UNIT}}; --tabs-nav-border-right-width: {{RIGHT}}{{UNIT}}; --tabs-nav-border-bottom-width: {{BOTTOM}}{{UNIT}}; --tabs-nav-border-left-width: {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'tabs_nav_border_type!' => array(
						'',
						'none',
					),
				),
			)
		);

		$this->add_control(
			'tabs_divider_heading',
			array(
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Dividers', 'cmsmasters-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'tabs_divider_weight',
			array(
				'label' => esc_html__( 'Width', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'em',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--tabs-divider-weight: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'tabs_divider_color',
			array(
				'label' => esc_html__( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--tabs-divider-color: {{VALUE}};',
				),
				'condition' => array(
					'tabs_divider_weight!' => array(
						'',
						'0',
					),
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_sections_controls_style() {
		$this->start_controls_section(
			'sections_section_style',
			array(
				'label' => esc_html__( 'Sections', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'sections_background_color',
			array(
				'label' => esc_html__( 'Background Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--sections-background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sections_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--sections-border-color: {{VALUE}};',
				),
				'condition' => array( 'sections_border_type!' => 'none' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'sections_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'cmsmasters-elementor' ),
				'fields_options' => array(
					'box_shadow' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--sections-box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_responsive_control(
			'sections_padding',
			array(
				'label' => esc_html__( 'Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--sections-padding-top: {{TOP}}{{UNIT}}; --sections-padding-right: {{RIGHT}}{{UNIT}}; --sections-padding-bottom: {{BOTTOM}}{{UNIT}}; --sections-padding-left: {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'sections_border_radius',
			array(
				'label' => esc_html__( 'Border Radius', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--sections-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'sections_border_type',
			array(
				'label' => esc_html__( 'Border Type', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_custom_border_type_options(),
				'selectors' => array(
					'{{WRAPPER}}' => '--sections-border-type: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'sections_border_width',
			array(
				'label' => esc_html__( 'Border Width', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--sections-border-top-width: {{TOP}}{{UNIT}}; --sections-border-right-width: {{RIGHT}}{{UNIT}}; --sections-border-bottom-width: {{BOTTOM}}{{UNIT}}; --sections-border-left-width: {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'sections_border_type!' => array(
						'',
						'none',
					),
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_typography_controls_style() {
		$this->start_controls_section(
			'typography_section_style',
			array(
				'label' => esc_html__( 'Typography', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'typography_titles_heading',
			array(
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Section Titles', 'cmsmasters-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'typography_titles_typography',
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-titles-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-titles-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-titles-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-titles-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-titles-font-style: {{VALUE}};',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-titles-text-decoration: {{VALUE}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-titles-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-titles-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-titles-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'typography_titles_color',
			array(
				'label' => esc_html__( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--typography-titles-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name' => 'typography_titles_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'cmsmasters-elementor' ),
				'fields_options' => array(
					'text_shadow' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-titles-text-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{COLOR}};',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_responsive_control(
			'typography_titles_spacing',
			array(
				'label' => esc_html__( 'Spacing', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'%',
					'vw',
				),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--typography-titles-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'typography_general_heading',
			array(
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'General Text', 'cmsmasters-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'typography_general_typography',
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-general-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-general-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-general-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-general-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-general-font-style: {{VALUE}};',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-general-text-decoration: {{VALUE}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-general-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-general-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-general-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'typography_general_color',
			array(
				'label' => esc_html__( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--typography-general-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'typography_login_messages_heading',
			array(
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Login Messages', 'cmsmasters-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'typography_login_messages_typography',
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-login-messages-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-login-messages-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-login-messages-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-login-messages-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-login-messages-font-style: {{VALUE}};',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-login-messages-text-decoration: {{VALUE}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-login-messages-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-login-messages-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-login-messages-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'typography_login_messages_color',
			array(
				'label' => esc_html__( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--typography-login-messages-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'typography_checkboxes_heading',
			array(
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Checkboxes', 'cmsmasters-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'typography_checkboxes_typography',
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-checkboxes-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-checkboxes-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-checkboxes-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-checkboxes-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-checkboxes-font-style: {{VALUE}};',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-checkboxes-text-decoration: {{VALUE}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-checkboxes-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-checkboxes-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-checkboxes-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'typography_checkboxes_color',
			array(
				'label' => esc_html__( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--typography-checkboxes-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'typography_radio_buttons_heading',
			array(
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Radio Buttons', 'cmsmasters-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'typography_radio_buttons_typography',
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-radio-buttons-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-radio-buttons-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-radio-buttons-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-radio-buttons-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-radio-buttons-font-style: {{VALUE}};',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-radio-buttons-text-decoration: {{VALUE}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-radio-buttons-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-radio-buttons-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--typography-radio-buttons-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'typography_radio_buttons_color',
			array(
				'label' => esc_html__( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--typography-radio-buttons-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'typography_links_heading',
			array(
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Links', 'cmsmasters-elementor' ),
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'typography_links_tabs' );

		foreach ( array(
			'normal' => __( 'Normal', 'cmsmasters-elementor' ),
			'hover' => __( 'Hover', 'cmsmasters-elementor' ),
		) as $main_key => $label ) {
			$this->start_controls_tab(
				"typography_links_{$main_key}_tab",
				array( 'label' => $label )
			);

			$this->add_control(
				"typography_links_{$main_key}_color",
				array(
					'label' => esc_html__( 'Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}}' => "--typography-links-{$main_key}-color: {{VALUE}};",
					),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_tables_controls_style() {
		$this->start_controls_section(
			'tables_section',
			array(
				'label' => esc_html__( 'Tables', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'tables_rows_gap',
			array(
				'label' => esc_html__( 'Rows Gap', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default' => array( 'px' => 0 ),
				'selectors' => array(
					'{{WRAPPER}}' => '--order-summary-rows-gap: calc({{SIZE}}{{UNIT}} / 2);',
				),
			)
		);

		$this->add_control(
			'tables_titles_heading',
			array(
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'label' => esc_html__( 'Titles', 'cmsmasters-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'tables_titles_typography',
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-titles-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-titles-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-titles-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-titles-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-titles-font-style: {{VALUE}};',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-titles-text-decoration: {{VALUE}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-titles-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-titles-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-titles-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'tables_titles_color',
			array(
				'label' => esc_html__( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--tables-titles-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name' => 'tables_titles_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'cmsmasters-elementor' ),
				'fields_options' => array(
					'text_shadow' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-titles-text-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{COLOR}};',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'tables_totals_heading',
			array(
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'label' => esc_html__( 'Totals', 'cmsmasters-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'tables_totals_typography',
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-totals-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-totals-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-totals-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-totals-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-totals-font-style: {{VALUE}};',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-totals-text-decoration: {{VALUE}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-totals-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-totals-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-totals-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'tables_totals_color',
			array(
				'label' => esc_html__( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--tables-totals-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name' => 'tables_totals_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'cmsmasters-elementor' ),
				'fields_options' => array(
					'text_shadow' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-totals-text-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{COLOR}};',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'tables_items_heading',
			array(
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Items', 'cmsmasters-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'tables_items_typography',
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-items-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-items-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-items-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-items-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-items-font-style: {{VALUE}};',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-items-text-decoration: {{VALUE}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-items-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-items-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-items-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'tables_items_color',
			array(
				'label' => esc_html__( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--tables-items-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tables_variations_heading',
			array(
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Variations', 'cmsmasters-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'tables_variations_typography',
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-variations-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-variations-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-variations-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-variations-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-variations-font-style: {{VALUE}};',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-variations-text-decoration: {{VALUE}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-variations-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-variations-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--tables-variations-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'tables_variations_color',
			array(
				'label' => esc_html__( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--tables-variations-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tables_links_heading',
			array(
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Product Link', 'cmsmasters-elementor' ),
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'tables_links_colors' );

		foreach ( array(
			'normal' => __( 'Normal', 'cmsmasters-elementor' ),
			'hover' => __( 'Hover', 'cmsmasters-elementor' ),
		) as $main_key => $label ) {
			$this->start_controls_tab(
				"tables_links_{$main_key}_tab",
				array( 'label' => $label )
			);

			$this->add_control(
				"tables_links_{$main_key}_color",
				array(
					'label' => esc_html__( 'Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}}' => "--tables-links-{$main_key}-color: {{VALUE}};",
					),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_forms_controls_style() {
		$this->start_controls_section(
			'forms_section_style',
			array(
				'label' => esc_html__( 'Forms', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'forms_columns_gap',
			array(
				'label' => esc_html__( 'Columns Gap', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default' => array( 'px' => 0 ),
				'selectors' => array(
					'{{WRAPPER}}' => '--forms-columns-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'forms_rows_gap',
			array(
				'label' => esc_html__( 'Rows Gap', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default' => array( 'px' => 0 ),
				'selectors' => array(
					'{{WRAPPER}}' => '--forms-rows-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'forms_label_heading',
			array(
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Labels', 'cmsmasters-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'forms_label_typography',
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--forms-label-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--forms-label-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--forms-label-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--forms-label-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--forms-label-font-style: {{VALUE}};',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--forms-label-text-decoration: {{VALUE}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--forms-label-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--forms-label-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--forms-label-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'forms_label_color',
			array(
				'label' => esc_html__( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--forms-labels-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'forms_label_spacing',
			array(
				'label' => esc_html__( 'Spacing', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default' => array( 'px' => 0 ),
				'selectors' => array(
					'{{WRAPPER}}' => '--forms-label-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'forms_field_heading',
			array(
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Fields', 'cmsmasters-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'forms_field_typography',
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--forms-field-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--forms-field-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--forms-field-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--forms-field-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--forms-field-font-style: {{VALUE}};',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--forms-field-text-decoration: {{VALUE}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--forms-field-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--forms-field-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--forms-field-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}, .e-woo-select2-wrapper',
			)
		);

		$this->start_controls_tabs( 'forms_fields_styles' );

		foreach ( array(
			'normal' => __( 'Normal', 'cmsmasters-elementor' ),
			'focus' => __( 'Focus', 'cmsmasters-elementor' ),
		) as $main_key => $label ) {
			$this->start_controls_tab(
				"forms_fields_{$main_key}_tab",
				array( 'label' => $label )
			);

			$this->add_control(
				"forms_fields_{$main_key}_color",
				array(
					'label' => esc_html__( 'Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}}, .e-woo-select2-wrapper' => "--forms-fields-{$main_key}-color: {{VALUE}};",
					),
				)
			);

			$this->add_control(
				"forms_fields_{$main_key}_bg_color",
				array(
					'label' => __( 'Background Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}}' => "--forms-fields-{$main_key}-bg-color: {{VALUE}};",
					),
				)
			);

			$this->add_control(
				"forms_fields_{$main_key}_border_color",
				array(
					'label' => esc_html__( 'Border Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}}' => "--forms-fields-{$main_key}-border-color: {{VALUE}};",
					),
					'condition' => array( 'forms_fields_border_type!' => 'none' ),
				)
			);

			$this->add_responsive_control(
				"forms_fields_{$main_key}_border_radius",
				array(
					'label' => esc_html__( 'Border Radius', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
						'%',
					),
					'selectors' => array(
						'{{WRAPPER}}' => "--forms-fields-{$main_key}-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
					),
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name' => "forms_fields_{$main_key}_box_shadow",
					'label' => esc_html__( 'Box Shadow', 'cmsmasters-elementor' ),
					'fields_options' => array(
						'box_shadow' => array(
							'selectors' => array(
								'{{SELECTOR}}' => "--forms-fields-{$main_key}-box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};",
							),
						),
					),
					'selector' => '{{WRAPPER}}',
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'forms_fields_padding',
			array(
				'label' => esc_html__( 'Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}}' => '--forms-fields-padding-top: {{TOP}}{{UNIT}}; --forms-fields-padding-right: {{RIGHT}}{{UNIT}}; --forms-fields-padding-bottom: {{BOTTOM}}{{UNIT}}; --forms-fields-padding-left: {{LEFT}}{{UNIT}};',
					// style select2
					'{{WRAPPER}} .e-my-account-tab:not(.e-my-account-tab__dashboard--custom) .select2-container--default .select2-selection--single .select2-selection__rendered' => 'line-height: calc( ({{TOP}}{{UNIT}}*2) + 16px ); padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} .e-my-account-tab:not(.e-my-account-tab__dashboard--custom) .select2-container--default .select2-selection--single .select2-selection__arrow' => 'height: calc( ({{TOP}}{{UNIT}}*2) + 16px ); right: {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} .e-my-account-tab:not(.e-my-account-tab__dashboard--custom) .select2-container--default .select2-selection--single' => 'height: auto;',
				),
			)
		);

		$this->add_control(
			'forms_fields_border_type',
			array(
				'label' => esc_html__( 'Border Type', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_custom_border_type_options(),
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--forms-fields-border-type: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'forms_fields_border_width',
			array(
				'label' => esc_html__( 'Border Width', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--forms-fields-border-top-width: {{TOP}}{{UNIT}}; --forms-fields-border-right-width: {{RIGHT}}{{UNIT}}; --forms-fields-border-bottom-width: {{BOTTOM}}{{UNIT}}; --forms-fields-border-left-width: {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'forms_fields_border_type!' => array(
						'',
						'none',
					),
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_buttons_controls_style() {
		$this->start_controls_section(
			'buttons_section_style',
			array(
				'label' => esc_html__( 'Buttons', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'buttons_typography',
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--buttons-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--buttons-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--buttons-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--buttons-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--buttons-font-style: {{VALUE}};',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--buttons-text-decoration: {{VALUE}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--buttons-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--buttons-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--buttons-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->start_controls_tabs( 'buttons_tabs' );

		foreach ( array(
			'normal' => __( 'Normal', 'cmsmasters-elementor' ),
			'hover' => __( 'Hover', 'cmsmasters-elementor' ),
		) as $main_key => $label ) {
			$state = ( 'normal' === $main_key ? ':before' : ':after' );
			$buttons_bg_selector = "{{WRAPPER}} .button{$state}, {{WRAPPER}} #place_order{$state}";

			$this->start_controls_tab(
				"buttons_{$main_key}_tab",
				array( 'label' => $label )
			);

			$this->add_control(
				"buttons_{$main_key}_color",
				array(
					'label' => esc_html__( 'Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}}' => "--buttons-{$main_key}-color: {{VALUE}};",
					),
				)
			);

			$this->add_group_control(
				CmsmastersControls::BUTTON_BACKGROUND_GROUP,
				array(
					'name' => "buttons_{$main_key}_bg_group",
					'exclude' => array( 'color' ),
					'selector' => $buttons_bg_selector,
				)
			);

			$this->start_injection( array( 'of' => "buttons_{$main_key}_bg_group_background" ) );

			$this->add_control(
				"buttons_{$main_key}_bg_color",
				array(
					'label' => __( 'Background Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$buttons_bg_selector => '--button-bg-color: {{VALUE}}; ' .
						'background: var( --button-bg-color );',
					),
					'condition' => array(
						"buttons_{$main_key}_bg_group_background" => array(
							'color',
							'gradient',
						),
					),
				)
			);

			$this->end_injection();

			$this->add_control(
				"buttons_{$main_key}_border_color",
				array(
					'label' => esc_html__( 'Border Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}}' => "--buttons-{$main_key}-border-color: {{VALUE}};",
					),
					'condition' => array( 'buttons_border_type!' => 'none' ),
				)
			);

			$this->add_responsive_control(
				"buttons_{$main_key}_border_radius",
				array(
					'label' => esc_html__( 'Border Radius', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
						'%',
					),
					'selectors' => array(
						'{{WRAPPER}}' => "--buttons-{$main_key}-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
					),
				)
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				array(
					'name' => "buttons_{$main_key}_text_shadow",
					'label' => esc_html__( 'Text Shadow', 'cmsmasters-elementor' ),
					'fields_options' => array(
						'text_shadow' => array(
							'selectors' => array(
								'{{SELECTOR}}' => "--buttons-{$main_key}-text-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{COLOR}};",
							),
						),
					),
					'selector' => '{{WRAPPER}}',
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name' => "buttons_{$main_key}_box_shadow",
					'label' => esc_html__( 'Box Shadow', 'cmsmasters-elementor' ),
					'fields_options' => array(
						'box_shadow' => array(
							'selectors' => array(
								'{{SELECTOR}}' => "--buttons-{$main_key}-box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};",
							),
						),
					),
					'selector' => '{{WRAPPER}}',
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'buttons_padding',
			array(
				'label' => esc_html__( 'Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
				),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}}' => '--buttons-padding-top: {{TOP}}{{UNIT}}; --buttons-padding-right: {{RIGHT}}{{UNIT}}; --buttons-padding-bottom: {{BOTTOM}}{{UNIT}}; --buttons-padding-left: {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'buttons_border_type',
			array(
				'label' => esc_html__( 'Border Type', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_custom_border_type_options(),
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--buttons-border-type: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'buttons_border_width',
			array(
				'label' => esc_html__( 'Border Width', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--buttons-border-top-width: {{TOP}}{{UNIT}}; --buttons-border-right-width: {{RIGHT}}{{UNIT}}; --buttons-border-bottom-width: {{BOTTOM}}{{UNIT}}; --buttons-border-left-width: {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'buttons_border_type!' => array(
						'',
						'none',
					),
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_login_form_controls_style() {
		$this->start_controls_section(
			'login_form_section_style',
			array(
				'label' => esc_html__( 'Login Form', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'login_form_layout',
			array(
				'label' => __( 'Layout', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'block' => __( 'Block', 'cmsmasters-elementor' ),
					'inline' => __( 'Inline', 'cmsmasters-elementor' ),
				),
				'label_block' => false,
				'toggle' => false,
				'default' => 'block',
				'render_type' => 'template',
				'prefix_class' => 'cmsmasters-my-account-login-form-layout-',
			)
		);

		$this->add_responsive_control(
			'login_form_alignment',
			array(
				'label' => esc_html__( 'Alignment', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'End', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-right',
					),
				),
				'selectors_dictionary' => array(
					'flex-start' => '--login-form-alignment: flex-start; --login-form-text-alignment: left',
					'center' => '--login-form-alignment: center; --login-form-text-alignment: center',
					'flex-end' => '--login-form-alignment: flex-end; --login-form-text-alignment: right',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'login_form_width',
			array(
				'label' => esc_html__( 'Width', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'%',
					'vw',
					'vh',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--login-form-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'login_form_input_gap',
			array(
				'label' => esc_html__( 'Spacing', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'em',
					'vw',
					'vh',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--login-form-input-gap: {{SIZE}}{{UNIT}};',
				),
				'condition' => array( 'login_form_layout' => 'inline' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get Custom Border Type Options
	 *
	 * Return a set of border options to be used in different WooCommerce widgets.
	 *
	 * This will be used in cases where the Group Border Control could not be used.
	 *
	 * @since 1.8.0
	 *
	 * @return array
	 */
	public static function get_custom_border_type_options() {
		return array(
			'' => esc_html__( 'Default', 'cmsmasters-elementor' ),
			'none' => esc_html__( 'None', 'cmsmasters-elementor' ),
			'solid' => esc_html__( 'Solid', 'cmsmasters-elementor' ),
			'double' => esc_html__( 'Double', 'cmsmasters-elementor' ),
			'dotted' => esc_html__( 'Dotted', 'cmsmasters-elementor' ),
			'dashed' => esc_html__( 'Dashed', 'cmsmasters-elementor' ),
			'groove' => esc_html__( 'Groove', 'cmsmasters-elementor' ),
		);
	}

	/**
	 * This will be used in cases where the Group Border Control could not be used.
	 *
	 * @since 1.8.0
	 * @since 1.10.1 Fixed widget in guest mode.
	 *
	 */
	protected function render() {
		// Add actions & filters before displaying our Widget.
		add_action( 'woocommerce_account_navigation', array( $this, 'woocommerce_account_navigation' ), 1 );
		add_filter( 'woocommerce_account_menu_items', array( $this, 'modify_menu_items' ), 99, 2 );
		add_action( 'woocommerce_account_content', array( $this, 'before_account_content' ), 2 );
		add_action( 'woocommerce_account_content', array( $this, 'after_account_content' ), 95 );
		add_filter( 'woocommerce_get_myaccount_page_permalink', array( $this, 'woocommerce_get_myaccount_page_permalink' ), 10, 1 );
		add_filter( 'woocommerce_logout_default_redirect_url', array( $this, 'woocommerce_logout_default_redirect_url' ), 10, 1 );
		apply_filters( 'woocommerce_billing_fields', array( $this, 'custom_address_fields_order' ), 1001, 2 );
		// add_action( 'init', array( $this, 'remove_logged_in_user' ) );
		add_action( 'template_redirect', array( $this, 'my_save_address' ), 1 );
		add_action( 'woocommerce_after_save_address_validation', array( $this, 'custom_validation' ), 1, 2 );

		// Display our Widget.
		global $wp;

		// Check cart class is loaded or abort.
		if ( is_null( WC()->cart ) ) {
			return;
		}

		if ( ! is_user_logged_in() || isset( $wp->query_vars['lost-password'] ) ) {
			$message = apply_filters( 'woocommerce_my_account_message', '' );

			if ( ! empty( $message ) ) {
				wc_add_notice( $message );
			}

			// After password reset, add confirmation message.
			if ( ! empty( $_GET['password-reset'] ) ) { // WPCS: input var ok, CSRF ok.
				wc_add_notice( esc_html__( 'Your password has been reset successfully.', 'cmsmasters-elementor' ) );
			}

			if ( isset( $wp->query_vars['lost-password'] ) ) {
				self::lost_password();
			} else {
				wc_get_template( 'myaccount/form-login.php' );
			}
		} else {
			$this->render_html_editor();
		}

		// Remove actions & filters after displaying our Widget.
		remove_action( 'woocommerce_account_navigation', array( $this, 'woocommerce_account_navigation' ), 2 );
		remove_action( 'woocommerce_account_menu_items', array( $this, 'modify_menu_items' ), 99 );
		remove_action( 'woocommerce_account_content', array( $this, 'before_account_content' ), 5 );
		remove_action( 'woocommerce_account_content', array( $this, 'after_account_content' ), 95 );
		remove_filter( 'woocommerce_get_myaccount_page_permalink', array( $this, 'woocommerce_get_myaccount_page_permalink' ), 10, 1 );
		remove_filter( 'woocommerce_logout_default_redirect_url', array( $this, 'woocommerce_logout_default_redirect_url' ), 10, 1 );
		remove_filter( 'woocommerce_billing_fields', array( $this, 'custom_address_fields_order' ), 1001, 2 );
		// remove_action( 'init', array( $this, 'remove_logged_in_user' ) );
		add_action( 'template_redirect', array( $this, 'my_save_address' ), 1 );
		add_action( 'woocommerce_after_save_address_validation', array( $this, 'custom_validation' ), 1, 2 );
	}

	/**
	 * Retrieves a user row based on password reset key and login.
	 *
	 * @since 1.11.4
	 */
	public static function check_password_reset_key( $key, $login ) {
		$user = check_password_reset_key( $key, $login );

		if ( is_wp_error( $user ) ) {
			wc_add_notice( __( 'This key is invalid or has already been used. Please reset your password again if needed.', 'cmsmasters-elementor' ), 'error' );

			return false;
		}

		return $user;
	}

	/**
	 * Lost password page handling.
	 *
	 * @since 1.11.4
	 */
	public static function lost_password() {
		if ( ! empty( $_GET['reset-link-sent'] ) ) {
			return wc_get_template( 'myaccount/lost-password-confirmation.php' );
		} elseif ( ! empty( $_GET['show-reset-form'] ) ) {
			if ( isset( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ) && 0 < strpos( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ], ':' ) ) {
				list( $rp_id, $rp_key ) = array_map( 'wc_clean', explode( ':', wp_unslash( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ), 2 ) );
				$userdata = get_userdata( absint( $rp_id ) );
				$rp_login = $userdata ? $userdata->user_login : '';
				$user = self::check_password_reset_key( $rp_key, $rp_login );

				if ( is_object( $user ) ) {
					return wc_get_template(
						'myaccount/form-reset-password.php',
						array(
							'key' => $rp_key,
							'login' => $rp_login,
						)
					);
				}
			}
		}

		wc_get_template(
			'myaccount/form-lost-password.php',
			array( 'form' => 'lost_password' )
		);
	}

	/**
	 * Woocommerce Account Navigation
	 *
	 * Output a horizontal menu if the setting was selected. The default vertical menu will be hidden with CSS
	 * and this menu will show. We wrap this menu with a class '.cmsmasters-wc-account-tabs-nav' so that we
	 * can manipulate the display for this menu with CSS (make it horizontal).
	 *
	 * Callback function for the woocommerce_account_navigation hook.
	 *
	 * This eliminates the need for template overrides.
	 *
	 * @since 1.8.0
	 */
	public function woocommerce_account_navigation() {
		$settings = $this->get_settings_for_display();

		if ( 'horizontal' === $settings['tabs_layout'] ) {
			?>
			<div class="cmsmasters-wc-account-tabs-nav">
				<?php wc_get_template( 'myaccount/navigation.php' ); ?>
			</div>
			<?php
		}
	}

	public function modify_menu_items( $items, $endpoints ) {
		$settings = $this->get_settings_for_display();

		if ( ! empty( $settings['tabs'] ) ) {
			foreach ( $settings['tabs'] as $tab ) {
				if ( isset( $tab['tab_name'] ) && isset( $tab['field_key'] ) ) {
					$items[ $tab['field_key'] ] = $tab['tab_name'];
				}
			}
		}

		if ( isset( $items['dashboard'] ) && isset( $items['quotes'] ) ) {
			$quotes_value = $items['quotes'];

			unset( $items['quotes'] );

			$dashboard_key = array_search( 'dashboard', array_keys( $items ) );

			$items = array_merge(
				array_slice( $items, 0, $dashboard_key + 1, true ),
				array( 'quotes' => $quotes_value ),
				array_slice( $items, $dashboard_key + 1, null, true )
			);
		}

		return $items;
	}

	/**
	 * Before Account Content
	 *
	 * Output containing elements. Callback function for the woocommerce_account_content hook.
	 *
	 * This eliminates the need for template overrides.
	 *
	 * @since 1.8.0
	 */
	public function before_account_content() {
		$wrapper_class = $this->get_account_content_wrapper( array( 'context' => 'frontend' ) );

		echo '<div class="' . sanitize_html_class( $wrapper_class ) . '">';
	}

	/**
	 * Get Account Content Wrapper
	 *
	 * This function will determine the wrapper class around the main content.
	 * There are different wrappers depending on the following scenarios:
	 * 1. Are there orders/downloads or not.
	 * 2. A custom template been selected for the dashboard intro or not
	 *
	 * @since 1.8.0
	 *
	 * @return string
	 */
	private function get_account_content_wrapper( $args ) {
		$user_id = get_current_user_id();
		$num_orders = wc_get_customer_order_count( $user_id );
		$num_downloads = count( wc_get_customer_available_downloads( $user_id ) );
		$class = 'woocommerce-MyAccount-content-wrapper';
		$current_endpoint = $this->get_current_endpoint();

		/* we need to render a different css class if there are no orders/downloads to display
		 * as the no orders/downloads screen should not have the default padding and border
		 * around it but show the 'no orders/downloads' notification only
		 */
		if ( 'frontend' === $args['context'] ) { // Front-end display
			global $wp_query;

			if ( ( 0 === $num_orders && isset( $wp_query->query_vars['orders'] ) ) || ( 0 === $num_downloads && isset( $wp_query->query_vars['downloads'] ) ) ) {
				$class .= '-no-data';
			}
		} else { // Editor display
			if ( ( 0 === $num_orders && 'orders' === $args['page'] ) || ( 0 === $num_downloads && 'downloads' === $args['page'] ) ) {
				$class .= '-no-data';
			}
		}

		return $class;
	}

	/**
	 * Get Current Endpoint
	 *
	 * Used to determine which page Account Page the user is on currently.
	 * This is used so we can add a unique wrapper class around the page's content.
	 *
	 * @since 1.8.0
	 *
	 * @return string
	 */
	private function get_current_endpoint() {
		global $wp_query;

		$current = '';
		$pages = $this->get_account_pages();

		foreach ( $pages as $page => $val ) {
			if ( isset( $wp_query->query[ $page ] ) ) {
				$current = $page;

				break;
			}
		}

		if ( '' === $current && isset( $wp_query->query_vars['page'] ) ) {
			$current = 'dashboard'; // Dashboard is not an endpoint so it needs a custom check.
		}

		return $current;
	}

	/**
	 * After Account Content
	 *
	 * Output containing elements. Callback function for the woocommerce_account_content hook.
	 *
	 * This eliminates the need for template overrides.
	 *
	 * @since 1.8.0
	 */
	public function after_account_content() {
		echo '</div>';
	}

	/**
	 * WooCommerce Get Theme My Account Page Permalink
	 *
	 * Modify the permalinks of the Theme My Account menu items. By default the permalinks will go to the
	 * set WooCommerce Theme My Account Page, even if the widget is on a different page. This function will override
	 * the permalinks to use the widget page URL as the base URL instead.
	 *
	 * This is a callback function for the woocommerce_get_myaccount_page_permalink filter.
	 *
	 * @since 1.8.0
	 *
	 * @return string
	 */
	public function woocommerce_get_myaccount_page_permalink( $bool ) {
		return get_permalink();
	}

	/**
	 * WooCommerce Logout Default Redirect URL
	 *
	 * Modify the permalink of the Theme My Account Logout menu item. We add this so that we can add custom
	 * parameters to the URL, which we can later access to log the user out and redirect back to the widget
	 * page. Without this WooCommerce would have always just redirect back to the set Theme My Account Page
	 * after log out.
	 *
	 * This is a callback function for the woocommerce_logout_default_redirect_url filter.
	 *
	 * @since 1.8.0
	 *
	 * @return string
	 */
	public function woocommerce_logout_default_redirect_url( $redirect ) {
		return $redirect . '?elementor_wc_logout=true&elementor_my_account_redirect=' . esc_url( get_permalink() );
	}

	/**
	 * Get Account Pages
	 *
	 * Get all the pages that would render on the Theme My Account page.
	 * We will use this array to be able to render all these pages' content when the editor loads.
	 * We will then switch between the pages via JS as all the content is already on the page.
	 *
	 * @since 1.8.0
	 *
	 * @return array
	 */
	private function get_account_pages() {
		$pages = array(
			'dashboard' => '',
		);

		// Edit Quotes.
		if ( defined( 'YITH_YWRAQ_FREE_INIT' ) || defined( 'YITH_YWRAQ_PREMIUM' ) ) {
			$pages['quotes'] = '';
		}

		// Check if payment gateways support add new payment methods.
		$support_payment_methods = false;

		foreach ( WC()->payment_gateways->get_available_payment_gateways() as $gateway ) {
			if ( $gateway->supports( 'add_payment_method' ) || $gateway->supports( 'tokenization' ) ) {
				$support_payment_methods = true;

				break;
			}
		}

		$pages['orders'] = '';
		$pages['downloads'] = '';
		$pages['edit-address'] = '';

		if ( $support_payment_methods ) {
			$pages['payment-methods'] = '';
			$pages['add-payment-method'] = '';
		}

		// Edit wishlist.
		if ( class_exists( 'WPCleverWoosw' ) ) {
			$pages['wishlist'] = '';
		}

		// Edit compare.
		if ( class_exists( 'WPCleverWoosc' ) ) {
			$pages['compare'] = '';
		}

		// Edit account.
		$pages['edit-account'] = '';

		return $pages;
	}

	// public function remove_logged_in_user() {
	// 	require_once( ABSPATH.'wp-admin/includes/user.php' );

	// 	if ( isset( $_POST['delete_account'] ) ) {
	// 		$delete_user_id = isset( $_POST['user_id'] ) ? intval( $_POST['user_id'] ) : 0;

	// 		wp_delete_user( $delete_user_id );

	// 		wp_redirect( home_url() );

	// 		exit();
	// 	}
	// }

	public function quote_order_actions( $order ) {
		if ( ! is_object( $order ) ) {
			$order_id = absint( $order );
			$order    = wc_get_order( $order_id );
		}
	
		$actions = array(
			'view'   => array(
				'url'  => $order->get_view_order_url(),
				'name' => __( 'View', 'cmsmasters-elementor' ),
			),
		);

		return apply_filters( 'woocommerce_my_account_my_orders_actions', $actions, $order );
	}

	public function quote_total_wrap( $order, $type ) {
		$settings = $this->get_settings_for_display();

		$order_products = $this->get_order_products( $order );
		$user = wp_get_current_user();
		$not_customer_role = ( is_user_logged_in() && ! in_array( 'customer', $user->roles ) );

		if ( ! empty( $order_products ) ) {
			echo '<div class="' . $this->get_widget_class() . '__order-products' . ( ( ! $not_customer_role || 'orders' !== $type ) ? ' only_products' : '' ) . '">';

				foreach ( $order_products as $product ) {
					$product_id = $product['id'];
					$product_image_id = get_post_thumbnail_id( $product_id );
					$product_name = $product['name'];
					$product_permalink = get_permalink( $product_id );
					$product_quantity = $product['quantity'];
					$product_price = wc_price( $product['price'] );
					$product_category = $this->get_product_category( $product_id );

					echo '<div class="' . $this->get_widget_class() . '__order-product">';

						if ( $product_image_id ) {
							echo '<div class="' . $this->get_widget_class() . '__order-product-details featured">';

								$settings['thumbnail'] = array( 'id' => $product_image_id );

								echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail' );

							echo '</div>';
						}

						echo '<div class="' . $this->get_widget_class() . '__order-product-details-cont">' .
							'<div class="' . $this->get_widget_class() . '__order-product-details title">';

								if ( $product_category ) {
									echo '<div class="' . $this->get_widget_class() . '__order-product-category">' .
										$product_category .
									'</div>';
								}

								echo '<h6 class="' . $this->get_widget_class() . '__order-product-title">' .
									'<a href="' . esc_url( $product_permalink ) . '">' .
										esc_html( $product_name ) .
									'</a>' .
								'</h6>' .
							'</div>' .
							'<div class="' . $this->get_widget_class() . '__order-product-details-price-wrap">' .
								'<div class="' . $this->get_widget_class() . '__order-product-details quantity">' .
									esc_html( $product_quantity ) .
									'<span class="' . $this->get_widget_class() . '__order-product-details-unit">' .
										esc_html__( '(uom)', 'cmsmasters-elementor' ) .
									'</span>' .
								'</div>';
					
								if ( $not_customer_role && 'orders' === $type ) {
									echo '<div class="' . $this->get_widget_class() . '__order-product-details price">' .
										$product_price .
										'<span class="' . $this->get_widget_class() . '__order-product-details-unit">' .
											esc_html( '/uom' ) .
										'</span>' .
									'</div>';
								}

							echo '</div>' .
						'</div>' .
					'</div>';
				}

			echo '</div>';
		}

		$subtotal = wc_price( $order->get_subtotal() );
		// $shipping_method = $order->get_shipping_method();
		// $payment_method = $order->get_payment_method();
		$shipping_total = wc_price( $order->get_shipping_total() );
		// $payment_total = wc_price( $order->get_total() - $order->get_shipping_total() );
		$order_total = wc_price( $order->get_total() );

		if ( ( $not_customer_role && 'orders' === $type ) ) {
			echo '<div class="' . $this->get_widget_class() . '__order-totals">' .
				'<div class="' . $this->get_widget_class() . '__order-total subtotal">' .
					'<span class="' . $this->get_widget_class() . '__order-total-label">' .
						esc_html( 'Subtotal', 'cmsmasters-elementor' ) .
					'</span>' .
					'<span class="' . $this->get_widget_class() . '__order-total-value">' .
						$subtotal .
					'</span>' .
				'</div>';

				if ( $shipping_total ) {
					echo '<div class="' . $this->get_widget_class() . '__order-total shipping">' .
						'<span class="' . $this->get_widget_class() . '__order-total-label">' .
							esc_html( 'Shipping', 'cmsmasters-elementor' ) .
						'</span>' .
						'<span class="' . $this->get_widget_class() . '__order-total-value">' .
							$shipping_total .
						'</span>' .
					'</div>';
				}

				// if ( $payment_total ) {
				// 	echo '<div class="' . $this->get_widget_class() . '__order-total payment">' .
				// 		'<span class="' . $this->get_widget_class() . '__order-total-label">' .
				// 			esc_html( 'Payment', 'cmsmasters-elementor' ) .
				// 		'</span>' .
				// 		'<span class="' . $this->get_widget_class() . '__order-total-value">' .
				// 			$payment_total .
				// 		'</span>' .
				// 	'</div>';
				// }

				echo '<div class="' . $this->get_widget_class() . '__order-total order">' .
					'<span class="' . $this->get_widget_class() . '__order-total-label">' .
						esc_html( 'Total', 'cmsmasters-elementor' ) .
					'</span>' .
					'<span class="' . $this->get_widget_class() . '__order-total-value">' .
						$order_total .
					'</span>' .
				'</div>' .
			'</div>';
		}
	}

	public function order_items( $type ) {
		$current_user = wp_get_current_user();

		if ( 0 == $current_user->ID ) {
			return;
		}

		$quote_status = array(
			'ywraq-new',
			'ywraq-pending',
			'ywraq-expired',
			'ywraq-accepted',
			'ywraq-rejected',
		);

		$order_query = new \WC_Order_Query( array(
			'limit' => -1,
			'orderby' => 'date',
			'order' => 'DESC',
			'status'  => ( 'quote' === $type ? $quote_status : '' ),
			'customer' => $current_user->ID,
		) );

		echo '<div class="' . $this->get_widget_class() . '__order-wrap">' .
			'<h2 class="' . $this->get_widget_class() . '__order-title">';
				$title = ( 'orders' === $type ? 'Order History' : 'Quotes History' );

				echo esc_html( $title ) .
			'</h2>' .
			'<div class="' . $this->get_widget_class() . '__order-inner">';

				$orders = $order_query->get_orders();

				if ( 'orders' === $type ) {
					$orders = array_filter( $orders, function( $order ) use ( $quote_status ) {
						return ! in_array( $order->get_status(), $quote_status );
					} );
				}
			
				if ( $orders ) {
					echo '<div class="' . $this->get_widget_class() . '__order-labels">' .
						'<span class="' . $this->get_widget_class() . '__order-label order">' .
							esc_html__( 'Order', 'cmsmasters-elementor' ) .
						'</span>' .
						'<span class="' . $this->get_widget_class() . '__order-label po-name">' .
							esc_html__( 'PO Name', 'cmsmasters-elementor' ) .
						'</span>' .
						'<span class="' . $this->get_widget_class() . '__order-label date">' .
							esc_html__( 'Date', 'cmsmasters-elementor' ) .
						'</span>' .
						'<span class="' . $this->get_widget_class() . '__order-label status">' .
							esc_html__( 'Status', 'cmsmasters-elementor' ) .
						'</span>' .
						'<span class="' . $this->get_widget_class() . '__order-label type">' .
							esc_html__( 'Type', 'cmsmasters-elementor' ) .
						'</span>' .
					'</div>' .
					'<div class="' . $this->get_widget_class() . '__orders">';

						foreach ( $orders as $order ) {
							$order_id = $order->get_id();
							$order_date = $order->get_date_created()->format( 'd/m/Y' );
							$order_status = $order->get_status();
							$billing_address = $order->get_formatted_billing_address();
							$shipping_address = $order->get_formatted_shipping_address();

							$is_quote = in_array( $order_status, $quote_status );
							$orders_condition = ( 'orders' === $type ? ! $is_quote : $is_quote );

							if ( $orders_condition ) {
								echo '<div class="' . $this->get_widget_class() . '__order">' .
									'<div class="' . $this->get_widget_class() . '__order-details-wrap">' .
										'<span class="' . $this->get_widget_class() . '__order-details id">' .
											'<span class="' . $this->get_widget_class() . '__order-details-mobile-label">' .
												esc_html__( 'Order', 'cmsmasters-elementor' ) .
											'</span>' .
											esc_html( '#' . $order_id ) .
										'</span>' .
										'<span class="' . $this->get_widget_class() . '__order-details po-name">' .
											'<span class="' . $this->get_widget_class() . '__order-details-mobile-label">' .
												esc_html__( 'PO Name', 'cmsmasters-elementor' ) .
											'</span>' .
											esc_html( 'PO' . $order_id ) .
										'</span>' .
										'<span class="' . $this->get_widget_class() . '__order-details date">' .
											'<span class="' . $this->get_widget_class() . '__order-details-mobile-label">' .
												esc_html__( 'Date', 'cmsmasters-elementor' ) .
											'</span>' .
											esc_html( $order_date ) .
										'</span>' .
										'<span class="' . $this->get_widget_class() . '__order-details status ' .  $order_status . '">' .
											'<span class="' . $this->get_widget_class() . '__order-details-mobile-label">' .
												esc_html__( 'Status', 'cmsmasters-elementor' ) .
											'</span>' .
											'<span class="status-badge">' .
												esc_html( wc_get_order_status_name( $order_status ) ) .
											'</span>' .
										'</span>' .
										'<span class="' . $this->get_widget_class() . '__order-details type">' .
											'<span class="' . $this->get_widget_class() . '__order-details-mobile-label">' .
												esc_html__( 'Type', 'cmsmasters-elementor' ) .
											'</span>' .
											'<span class="' . $this->get_widget_class() . '__order-details-button view">';
												$button_label = ( 'orders' === $type ? 'Buy' : 'Quote' );

												echo esc_html( $button_label );
					
												Icons_Manager::render_icon( array(
													'value' => 'themeicon- theme-icon-arrow-back',
													'library' => 'themeicon-',
												), array( 'class' => 'order-details-icon' ) );

											echo '</span>' .
										'</span>' .
									'</div>' .
									'<div class="' . $this->get_widget_class() . '__order-totals-wrap">';

										$this->quote_total_wrap( $order, $type );

									echo '</div>' .
								'</div>';
							}
						}

					echo '</div>';
				} else {
					echo 'No ' . ( 'orders' === $type ? 'orders' : 'quotes' ) . ' found.';
				}

			echo '</div>' .
		'</div>';
	}

	public function get_order_products( $order ) {
		$products = array();

		foreach ( $order->get_items() as $item_id => $item ) {
			$product = $item->get_product();
			$product_name = $product->get_name();
			$product_quantity = $item->get_quantity();
			$product_price = $product->get_price();
			$product_id = $product->get_id();

			$products[] = array(
				'name' => $product_name,
				'quantity' => $product_quantity,
				'price' => $product_price,
				'id' => $product_id,
			);
		}
	
		return $products;
	}

	public function get_product_category( $product_id ) {
		$terms = wp_get_post_terms( $product_id, 'collection' );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$category_links = array();

			foreach ( $terms as $term ) {
				$term_id = $term->term_id;
				$category_links[] = '<a href="' . esc_url( get_term_link( $term_id, 'collection' ) ) . '">' .
					esc_html( $term->name ) .
				'</a>';
			}

			return implode( ', ', $category_links );
		}
	}

	public function get_dashboard() {
		$current_user_id = get_user_by( 'id', get_current_user_id() );

		$allowed_html = array(
			'a' => array(
				'href' => array(),
			),
		);

		echo '<p class="dashboard-hello">';

			printf(
				wp_kses( __( '%1$s (not %2$s? <a href="%3$s">Log out</a>)', 'cmsmasters-elementor' ), $allowed_html ),
				'<strong>Hello, ' . esc_html( $current_user_id->display_name ) . '!</strong>',
				esc_html( $current_user_id->display_name ),
				esc_url( wc_logout_url() )
			);

		echo '</p>' .
		'<p class="dashboard-text">';
			$dashboard_address = 'billing address';

			if ( wc_shipping_enabled() ) {
				$dashboard_address = 'shipping and billing addresses';
			}

			echo __( 'From your account dashboard you can view your recent orders, manage your ' . $dashboard_address . ', and edit your password and account details.', 'cmsmasters-elementor' );

		echo '</p>';

		do_action( 'woocommerce_account_dashboard' );

		do_action( 'woocommerce_before_my_account' );

		do_action( 'woocommerce_after_my_account' );
	}

	/**
	 * Render HTML Editor
	 *
	 * This function will output the content in the Editor.
	 * One navigation will be rendered and the content for all pages will be rendered.
	 * Only the dashboard page's content will show on page load as the other pages' content
	 * will be hidden with CSS and toggled via JS when the user clicks on the menu items.
	 *
	 * @since 1.8.0
	 */
	private function render_html_editor() {
		$settings = $this->get_settings_for_display();

		echo '<div class="e-my-account-tab e-my-account-tab__dashboard">';
		?>
			<span class="elementor-hidden">[[woocommerce_my_account]]</span>
			<div class="woocommerce">
				<?php $this->get_edit_address_form( 'billing' ); ?>
				<?php $this->get_edit_address_form( 'shipping' ); ?>

				<?php
				if ( 'horizontal' === $settings['tabs_layout'] ) {
					?>
					<div class="cmsmasters-wc-account-tabs-nav">
						<?php wc_get_template( 'myaccount/navigation.php' ); ?>
					</div>
					<?php
				} else {
					wc_get_template( 'myaccount/navigation.php' );
				}

				// In the editor, output all the tabs in order to allow for switching between them via JS.
				$pages = $this->get_account_pages();

				global $wp_query;

				foreach ( $pages as $page => $page_value ) {
					foreach ( $pages as $unset_tab => $unset_tab_value ) {
						unset( $wp_query->query_vars[ $unset_tab ] );
					}

					$wp_query->query_vars[ $page ] = $page_value;

					$wrapper_class = $this->get_account_content_wrapper( array(
						'context' => 'editor',
						'page' => $page,
					) );

					?>
					<div class="woocommerce-MyAccount-content" <?php echo $page ? 'e-my-account-page="' . esc_attr( $page ) . '"' : ''; ?>>
						<div class="<?php echo sanitize_html_class( $wrapper_class ); ?>">
							<?php
							if ( 'dashboard' === $page ) {
								$this->get_dashboard();
								

								// $user_id = get_current_user_id();

								// echo '<h4>' .
								// 	esc_html__( 'Delete your account', 'cmsmasters-elementor' ) .
								// '</h4>' .
								// '<p>' .
								// 	esc_html__( 'This will permanently delete your account and associated details from our system. It cannot be undone.', 'cmsmasters-elementor' ) .
								// '</p>' .
								// '<form method="post">' .
								// 	'<input type="hidden" name="' . esc_attr( $user_id ) . '" value="' . esc_attr( $user_id ) . '">' .
								// 	'<button type="submit" name="delete_account">' .
								// 		esc_html__( 'Delete account', 'cmsmasters-elementor' ) .
								// 	'</button>' .
								// '</form>';

								// if( is_user_logged_in() ) {
								// 	$this->remove_logged_in_user();
								// }
							} elseif ( 'quotes' === $page ) {
								$this->order_items( 'quote' );
							} elseif ( 'orders' === $page ) {
								$this->order_items( 'orders' );
							} else {
								do_action( 'woocommerce_account_' . $page . '_endpoint', $page_value );
							}
							?>
						</div>
					</div>
				<?php
				}
				?>
			</div>
		</div>
		<?php
	}

	public function get_edit_address_form( $load_address ) {
		$this->my_save_address( $load_address );

		$current_user = wp_get_current_user();
		$country = get_user_meta( get_current_user_id(), $load_address . '_country', true );
		$address = WC()->countries->get_address_fields( $country, $load_address . '_' );

		foreach ( $address as $key => $field ) {
			$value = get_user_meta( get_current_user_id(), $key, true );

			if ( ! $value ) {
				switch ( $key ) {
					case 'billing_email':
					case 'shipping_email':
						$value = $current_user->user_email;
						break;
				}
			}

			$address[ $key ]['value'] = apply_filters( 'woocommerce_my_account_edit_address_field_value', $value, $key, $load_address );
		}

		$address = apply_filters( 'woocommerce_address_to_edit', $address, $load_address );

		$page_title = ( 'billing' === $load_address ) ? esc_html__( 'Billing address', 'cmsmasters-elementor' ) : esc_html__( 'Shipping address', 'cmsmasters-elementor' );

		do_action( 'woocommerce_before_edit_account_address_form' );

		if ( $load_address ) {
			echo '<div class="' . $this->get_widget_class() . '__popup-edit-address" type="' . $load_address . '">' .
				'<div class="' . $this->get_widget_class() . '__popup-edit-address-inner">' .
					'<form class="edit-address" method="post">' .
						'<div class="' . $this->get_widget_class() . '__popup-edit-address-header">' .
							'<h2>' .
								apply_filters( 'woocommerce_my_account_edit_address_title', $page_title, $load_address ) .
							'</h2>';

							Icons_Manager::render_icon( array(
								'value' => 'themeicon- theme-icon-close',
								'library' => 'themeicon-',
							), array( 'class' => 'my-account-popup-edit-address-close' ) );

						echo '</div>' .
						'<div class="woocommerce-address-fields">';
								do_action( 'woocommerce_before_edit_address_form_' . $load_address );

								echo '<div class="woocommerce-address-fields__field-wrapper">';

								foreach ( $address as $key => $field ) {
									woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) );
								}

							echo '</div>';

							do_action( 'woocommerce_after_edit_address_form_' . $load_address );

							$button_class = ( wc_wp_theme_get_element_class_name( 'button' ) ? wc_wp_theme_get_element_class_name( 'button' ) : '' );

							echo '<p>';
								echo '<button type="submit" class="button ' . esc_attr( $button_class ) . '" name="my_save_address" value="' . esc_attr__( 'Save address', 'cmsmasters-elementor' ) . '">' .
									esc_html__( 'Save address', 'cmsmasters-elementor' ) .
								'</button>';

								wp_nonce_field( 'my_woocommerce-edit_address', 'my_woocommerce-edit-address-nonce' );

								echo '<input type="hidden" name="action" value="edit_address" />' .
							'</p>' .
						'</div>' .
					'</form>' .
				'</div>' .
			'</div>';
		}

		do_action( 'woocommerce_after_edit_account_address_form' );
	}

	public function custom_address_fields_order( $fields ) {
		$new_order = array(
			'billing_first_name',
			'billing_last_name',
			'billing_company',
			'billing_phone',
			'billing_email',
			'billing_country',
			'billing_address_1',
			'billing_address_2',
			'billing_state',
			'billing_city',
			'billing_postcode',
		);
	
		$ordered_fields = array();

		foreach ( $new_order as $field_key ) {
			if ( isset( $fields[$field_key] ) ) {
				$ordered_fields[$field_key] = $fields[$field_key];

				unset( $fields[$field_key] );
			}
		}

		$fields = $ordered_fields + $fields;

		return $fields;
	}

	public function my_save_address( $load_address ) {
		global $wp;
	
		$nonce_value = wc_get_var( $_REQUEST['my_woocommerce-edit-address-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ); // @codingStandardsIgnoreLine.
	
	
		if ( ! wp_verify_nonce( $nonce_value, 'my_woocommerce-edit_address' ) ) {
			return;
		}
	
		if ( empty( $_POST['action'] ) || 'edit_address' !== $_POST['action'] ) {
			return;
		}
	
		wc_nocache_headers();
	
		$user_id = get_current_user_id();
	
		if ( $user_id <= 0 ) {
			return;
		}
	
		$customer = new \WC_Customer( $user_id );
	
		if ( ! $customer ) {
			return;
		}
	
		$address_type = $load_address;
	
		if ( ! isset( $_POST[ $address_type . '_country' ] ) ) {
			return;
		}
	
		$address = WC()->countries->get_address_fields( wc_clean( wp_unslash( $_POST[ $address_type . '_country' ] ) ), $address_type . '_' );
	
		foreach ( $address as $key => $field ) {
			if ( ! isset( $field['type'] ) ) {
				$field['type'] = 'text';
			}
	
			// Get Value.
			if ( 'checkbox' === $field['type'] ) {
				$value = (int) isset( $_POST[ $key ] );
			} else {
				$value = isset( $_POST[ $key ] ) ? wc_clean( wp_unslash( $_POST[ $key ] ) ) : '';
			}
	
			// Hook to allow modification of value.
			$value = apply_filters( 'woocommerce_process_myaccount_field_' . $key, $value );
	
			// Validation: Required fields.
			if ( ! empty( $field['required'] ) && empty( $value ) ) {
				/* translators: %s: Field name. */
				wc_add_notice( sprintf( __( '%s is a required field.', 'cmsmasters-elementor' ), $field['label'] ), 'error', array( 'id' => $key ) );
			}

			$WC_Validation = new \WC_Validation( $user_id );
	
			if ( ! empty( $value ) ) {
				// Validation and formatting rules.
				if ( ! empty( $field['validate'] ) && is_array( $field['validate'] ) ) {
					foreach ( $field['validate'] as $rule ) {
						switch ( $rule ) {
							case 'postcode':
								$country = wc_clean( wp_unslash( $_POST[ $address_type . '_country' ] ) );
								$value   = wc_format_postcode( $value, $country );
	
								if ( '' !== $value && ! $WC_Validation::is_postcode( $value, $country ) ) {
									switch ( $country ) {
										case 'IE':
											$postcode_validation_notice = __( 'Please enter a valid Eircode.', 'cmsmasters-elementor' );
											break;
										default:
											$postcode_validation_notice = __( 'Please enter a valid postcode / ZIP.', 'cmsmasters-elementor' );
									}
									wc_add_notice( $postcode_validation_notice, 'error' );
								}
								break;
							case 'phone':
								if ( '' !== $value && ! $WC_Validation::is_phone( $value ) ) {
									/* translators: %s: Phone number. */
									wc_add_notice( sprintf( __( '%s is not a valid phone number.', 'cmsmasters-elementor' ), '<strong>' . $field['label'] . '</strong>' ), 'error' );
								}
								break;
							case 'email':
								$value = strtolower( $value );
	
								if ( ! is_email( $value ) ) {
									/* translators: %s: Email address. */
									wc_add_notice( sprintf( __( '%s is not a valid email address.', 'cmsmasters-elementor' ), '<strong>' . $field['label'] . '</strong>' ), 'error' );
								}
								break;
						}
					}
				}
			}

			try {
				// Set prop in customer object.
				if ( is_callable( array( $customer, "set_$key" ) ) ) {
					$customer->{"set_$key"}( $value );
				} else {
					$customer->update_meta_data( $key, $value );
				}
			} catch ( WC_Data_Exception $e ) {
				// Set notices. Ignore invalid billing email, since is already validated.
				if ( 'customer_invalid_billing_email' !== $e->getErrorCode() ) {
					wc_add_notice( $e->getMessage(), 'error' );
				}
			}
		}
	
		/**
		 * Hook: woocommerce_after_save_address_validation.
		 *
		 * Allow developers to add custom validation logic and throw an error to prevent save.
		 *
		 * @since 3.6.0
		 * @param int         $user_id User ID being saved.
		 * @param string      $address_type Type of address; 'billing' or 'shipping'.
		 * @param array       $address The address fields.
		 * @param WC_Customer $customer The customer object being saved.
		 */
		do_action( 'woocommerce_after_save_address_validation', $user_id, $address_type, $address, $customer );
	
		if ( 0 < wc_notice_count( 'error' ) ) {
			return;
		}
	
		$customer->save();
	
		wc_add_notice( __( 'Address changed successfully.', 'cmsmasters-elementor' ) );
	
		/**
		 * Hook: woocommerce_customer_save_address.
		 *
		 * Fires after a customer address has been saved.
		 *
		 * @since 3.6.0
		 * @param int    $user_id User ID being saved.
		 * @param string $address_type Type of address; 'billing' or 'shipping'.
		 */
		do_action( 'woocommerce_customer_save_address', $user_id, $address_type );
	
		wp_safe_redirect( wc_get_endpoint_url( 'edit-address', '', wc_get_page_permalink( 'myaccount' ) ) );

		exit;
	}

	public function custom_validation( $user_id, $load_address ) {
		$fields = array(
			'billing_first_name' => 'billing_first_name',
			'billing_last_name' => 'billing_last_name',
			'billing_company' => 'billing_company',
			'billing_phone' => 'billing_phone',
			'billing_email' => 'billing_email',
			'billing_country' => 'billing_country',
			'billing_address_1' => 'billing_address_1',
			'billing_address_2' => 'billing_address_2',
			'billing_city' => 'billing_city',
			'billing_state' => 'billing_state',
			'billing_postcode' => 'billing_postcode',
			'shipping_first_name' => 'shipping_first_name',
			'shipping_last_name' => 'shipping_last_name',
			'shipping_company' => 'shipping_company',
			'shipping_address_1' => 'shipping_address_1',
			'shipping_address_2' => 'shipping_address_2',
			'shipping_city' => 'shipping_city',
			'shipping_state' => 'shipping_state',
			'shipping_postcode' => 'shipping_postcode',
		);
	
		foreach ( $fields as $woo_key => $user_key ) {
			if ( isset( $_POST[$woo_key] ) ) {
				update_user_meta( $user_id, $user_key, sanitize_text_field( $_POST[$woo_key] ) );
			}
		}
	}

}
