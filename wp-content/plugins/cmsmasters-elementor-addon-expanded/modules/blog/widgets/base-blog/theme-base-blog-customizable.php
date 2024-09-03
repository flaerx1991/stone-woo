<?php
namespace CmsmastersElementor\Modules\Blog\Widgets\Base_Blog;

use CmsmastersElementor\Controls_Manager as CmsmastersManagerControls;
use CmsmastersElementor\Controls\Groups\Group_Control_Button_Background;
use CmsmastersElementor\Modules\Blog\Widgets\Base_Blog\Theme_Base_Blog_Elements;
use CmsmastersElementor\Utils;
use CmsmastersElementor\Plugin;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Addon blog widget class.
 *
 * An abstract class to register new Blog widgets.
 *
 * @since 1.0.0
 */
abstract class Theme_Base_Blog_Customizable extends Theme_Base_Blog_Elements {

	/**
	 * @since 1.0.0
	 */
	protected function init( $data ) {
		parent::init( $data );
	}

	/**
	 * @since 1.0.0
	 */
	public function register_controls() {
		parent::register_controls();

		$this->injection_section_layout();
	}

	/**
	 * Register blog controls.
	 *
	 * Adds different input fields to allow the user to change and customize the classes settings.
	 *
	 * @since 1.0.0
	 */
	protected function register_style_section_controls() {
		$this->register_section_style_post();
		$this->register_post_featured_section_style();
		$this->register_post_title_section_style();
		$this->register_post_meta_section_style();
		$this->register_post_excerpt_section_style();
		$this->register_section_style_read_mode();

		parent::register_style_section_controls();
	}

	/**
	 * Get HTML wrapper class.
	 *
	 * Retrieve the widget container class. Can be used to override the
	 * container class for specific widgets.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_html_wrapper_class() {
		return parent::get_html_wrapper_class() . ' elementor-widget-cmsmasters-blog-similar';
	}

	/**
	 * @since 1.0.0
	 */
	protected function init_controls() {
		parent::init_controls();
	}

	/**
	 * @since 1.0.0
	 */
	public function get_blog_classes() {
		return array_merge( parent::get_blog_classes(), array( static::get_css_class() ) );
	}


	/**
	 * Blog Widget constructor.
	 *
	 * Initializing the widget blog class.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Widget data.
	 * @param array|null $args Widget default arguments.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		parent::render();
	}

	/**
	 * Register blog controls.
	 *
	 * Adds different input fields to allow the user to change and customize the classes settings.
	 *
	 * @since 1.0.0
	 */
	public function injection_section_layout() {
		$this->start_injection( array(
			'of' => 'section_layout',
			'at' => 'start',
			'type' => 'section',
		) );

		$this->add_responsive_control(
			'alignment',
			array(
				'label' => __( 'Alignment', 'cmsmasters-elementor' ),
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
				'prefix_class' => 'cmsmasters-align%s--',
			)
		);

		$this->add_control(
			'image_heading',
			array(
				'label' => __( 'Image', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'image_ratio',
			array(
				'label' => __( 'Image Ratio', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0.1,
						'max' => 2,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					$this->get_blog_selector() => '--cmsmasters-theme-blog-image-ratio: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name' => 'thumbnail',
				'default' => 'medium_large',
			)
		);

		$this->add_control(
			'popup_heading',
			array(
				'label' => __( 'Popup', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'blog_post_type' => 'product' ),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'label' => __( 'Popup Image', 'cmsmasters-elementor' ),
				'name' => 'thumbnail_popup',
				'default' => 'thumbnail',
				'condition' => array( 'blog_post_type' => 'product' ),
			)
		);

		$this->add_control(
			'title_heading',
			array(
				'label' => __( 'Title', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'post_heading_rows',
			array(
				'label' => __( 'Truncate Text', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
				'prefix_class' => 'cmsmasters-heading-line-clamp-',
			)
		);

		$this->add_control(
			'post_heading_rows_count',
			array(
				'label' => __( 'Number of Lines', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 2,
				'min' => 1,
				'max' => 5,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__post-title a' => '-webkit-line-clamp: {{SIZE}};',
				),
				'condition' => array( 'post_heading_rows!' => '' ),
			)
		);

		$this->add_control(
			'excerpt_heading',
			array(
				'label' => __( 'Excerpt', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'blog_post_type' => 'post' ),
			)
		);

		$this->add_control(
			'excerpt_length',
			array(
				'label' => __( 'Length', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'placeholder' => apply_filters( 'excerpt_length', 25 ),
				'condition' => array( 'blog_post_type' => 'post' ),
			)
		);

		$this->add_control(
			'post_excerpt_rows',
			array(
				'label' => __( 'Truncate Text', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
				'prefix_class' => 'cmsmasters-excerpt-line-clamp-',
				'condition' => array( 'blog_post_type' => 'post' ),
			)
		);

		$this->add_control(
			'post_excerpt_rows_count',
			array(
				'label' => __( 'Number of Lines', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'min' => 1,
				'max' => 6,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__post-excerpt' => '-webkit-line-clamp: {{SIZE}};',
				),
				'condition' => array(
					'blog_post_type' => 'post',
					'post_excerpt_rows!' => '',
				),
			)
		);

		$this->add_control(
			'post_read_heading',
			array(
				'label' => __( 'Button', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'blog_post_type!' => 'collections' ),
			)
		);

		$this->add_control(
			'post_read_more_show',
			array(
				'label' => __( 'Show', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => array( 'blog_post_type!' => 'collections' ),
			)
		);

		$this->add_control(
			'read_more_text',
			array(
				'label' => __( 'Text', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Read More', 'cmsmasters-elementor' ),
				'condition' => array(
					'blog_post_type!' => 'collections',
					'post_read_more_show' => 'yes',
				),
			)
		);

		$this->add_control(
			'read_more_icon',
			array(
				'label' => esc_html__( 'Icon', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => false,
				'skin' => 'inline',
				'condition' => array(
					'blog_post_type!' => 'collections',
					'post_read_more_show' => 'yes',
				),
			)
		);

		$this->add_control(
			'read_more_icon_align',
			array(
				'label' => __( 'Icon Position', 'cmsmasters-elementor' ),
				'type' => CmsmastersManagerControls::CHOOSE_TEXT,
				'label_block' => false,
				'options' => array(
					'before' => __( 'Before', 'cmsmasters-elementor' ),
					'after' => __( 'After', 'cmsmasters-elementor' ),
				),
				'selectors_dictionary' => array(
					'before' => 'row',
					'after' => 'row-reverse',
				),
				'default' => 'after',
				'prefix_class' => 'cmsmasters-read-more-align-',
				'selectors' => array(
					'{{WRAPPER}}' => '--theme-blog-button-icon-position: {{VALUE}}',
				),
				'condition' => array(
					'blog_post_type!' => 'collections',
					'post_read_more_show' => 'yes',
					'read_more_icon[value]!' => '',
				),
			)
		);

		$this->end_injection();
	}

	/**
	 * Register blog controls.
	 *
	 * Adds different input fields to allow the user to change and customize the classes settings.
	 *
	 * @since 1.0.0
	 */
	protected function register_section_style_post() {
		$this->start_controls_section(
			'section_style_post',
			array(
				'label' => __( 'Post', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'post_style_tabs' );

		foreach ( array(
			'normal' => __( 'Normal', 'cmsmasters-elementor' ),
			'hover' => __( 'Hover', 'cmsmasters-elementor' ),
		) as $main_key => $label ) {
			$this->start_controls_tab(
				"post_style_{$main_key}_tab",
				array( 'label' => $label )
			);

			$this->add_control(
				"post_{$main_key}_bg",
				array(
					'label' => esc_html__( 'Background Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}}' => "--post-{$main_key}-bg: {{VALUE}};",
					),
				)
			);

			$this->add_control(
				"post_{$main_key}_bd_color",
				array(
					'label' => esc_html__( 'Border Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}}' => "--post-{$main_key}-bd-color: {{VALUE}};",
					),
					'condition' => array( 'post_bd_type!' => 'none' ),
				)
			);

			$this->add_control(
				"post_{$main_key}_bdrs",
				array(
					'label' => __( 'Border Radius', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
						'%',
					),
					'selectors' => array(
						'{{WRAPPER}}' => "--post-{$main_key}-bdrs: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
					),
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name' => "post_{$main_key}_box_shadow",
					'label' => esc_html__( 'Box Shadow', 'cmsmasters-elementor' ),
					'fields_options' => array(
						'box_shadow' => array(
							'selectors' => array(
								'{{SELECTOR}}' => "--post-{$main_key}-box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};",
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
			'post_padding',
			array(
				'label' => __( 'Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}}' => '--post-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'post_bd_type',
			array(
				'label' => esc_html__( 'Border Type', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_custom_border_type_options(),
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--post-bd-type: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'post_bd_width',
			array(
				'label' => esc_html__( 'Border Width', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--post-bd-top-width: {{TOP}}{{UNIT}}; --post-bd-right-width: {{RIGHT}}{{UNIT}}; --post-bd-bottom-width: {{BOTTOM}}{{UNIT}}; --post-bd-left-width: {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'post_bd_type!' => array(
						'',
						'none',
					),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Post Featured controls.
	 *
	 * Adds different input fields to allow the user to change and customize the classes settings.
	 *
	 * @since 1.0.0
	 */
	protected function register_post_featured_section_style() {
		$this->start_controls_section(
			'post_featured_section_style',
			array(
				'label' => __( 'Post Featured', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'post_featured_styles' );

		foreach ( array(
			'normal' => __( 'Normal', 'cmsmasters-elementor' ),
			'hover' => __( 'Hover', 'cmsmasters-elementor' ),
			'post-hover' => __( 'Post Hover', 'cmsmasters-elementor' ),
		) as $main_key => $label ) {
			$this->start_controls_tab(
				"post_featured_{$main_key}_tab",
				array( 'label' => $label )
			);

			$this->add_control(
				"post_featured_{$main_key}_background_color",
				array(
					'label' => esc_html__( 'Background Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}}' => "--post-featured-{$main_key}-background-color: {{VALUE}};",
					),
				)
			);

			$this->add_control(
				"post_featured_{$main_key}_border_color",
				array(
					'label' => esc_html__( 'Border Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}}' => "--post-featured-{$main_key}-border-color: {{VALUE}};",
					),
					'condition' => array( 'post_featured_border_type!' => 'none' ),
				)
			);

			$this->add_control(
				"post_featured_container_{$main_key}_border_radius",
				array(
					'label' => __( 'Container Border Radius', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
						'%',
					),
					'selectors' => array(
						'{{WRAPPER}}' => "--post-featured-container-{$main_key}-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
					),
				)
			);

			$this->add_control(
				"post_featured_{$main_key}_border_radius",
				array(
					'label' => __( 'Border Radius', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
						'%',
					),
					'selectors' => array(
						'{{WRAPPER}}' => "--post-featured-{$main_key}-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
					),
				)
			);

			$this->add_group_control(
				Group_Control_Css_Filter::get_type(),
				array(
					'name' => "post_featured_{$main_key}_css_filters",
					'fields_options' => array(
						'blur' => array(
							'selectors' => array(
								'{{SELECTOR}}' => "--post-featured-{$main_key}-css-filter: brightness( {{brightness.SIZE}}% ) contrast( {{contrast.SIZE}}% ) saturate( {{saturate.SIZE}}% ) blur( {{blur.SIZE}}px ) hue-rotate( {{hue.SIZE}}deg );",
							),
						),
					),
					'selector' => '{{WRAPPER}}',
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name' => "post_featured_{$main_key}_box_shadow",
					'label' => esc_html__( 'Box Shadow', 'cmsmasters-elementor' ),
					'fields_options' => array(
						'box_shadow' => array(
							'selectors' => array(
								'{{SELECTOR}}' => "--post-featured-{$main_key}-box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};",
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
			'post_featured_margin',
			array(
				'label' => __( 'Gap', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}}' => '--post-featured-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'post_featured_padding',
			array(
				'label' => __( 'Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--post-featured-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'post_featured_border_type',
			array(
				'label' => esc_html__( 'Border Type', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_custom_border_type_options(),
				'selectors' => array(
					'{{WRAPPER}}' => '--post-featured-border-type: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'post_featured_border_width',
			array(
				'label' => esc_html__( 'Border Width', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--post-featured-border-top-width: {{TOP}}{{UNIT}}; --post-featured-border-right-width: {{RIGHT}}{{UNIT}}; --post-featured-border-bottom-width: {{BOTTOM}}{{UNIT}}; --post-featured-border-left-width: {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'post_featured_border_type!' => array(
						'',
						'none',
					),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Post Title controls.
	 *
	 * Adds different input fields to allow the user to change and customize the classes settings.
	 *
	 * @since 1.0.0
	 */
	protected function register_post_title_section_style() {
		$this->start_controls_section(
			'post_title_section_style',
			array(
				'label' => __( 'Post Title', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'post_title_typography',
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-title-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-title-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-title-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-title-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-title-font-style: {{VALUE}};',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-title-text-decoration: {{VALUE}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-title-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-title-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-title-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'post_title_normal_color',
			array(
				'label' => esc_html__( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--post-title-normal-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'post_title_hover_color',
			array(
				'label' => esc_html__( 'Hover Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--post-title-hover-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'post_title_spacing',
			array(
				'label' => __( 'Spacing', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'em',
					'%',
					'vw',
				),
				'range' => array(
					'px' => array( 'max' => 100 ),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--post-title-margin: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Post Meta controls.
	 *
	 * Adds different input fields to allow the user to change and customize the classes settings.
	 *
	 * @since 1.0.0
	 */
	protected function register_post_meta_section_style() {
		$this->start_controls_section(
			'post_meta_section_style',
			array(
				'label' => __( 'Post Meta', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'post_meta_date_heading',
			array(
				'label' => __( 'Date', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'condition' => array( 'blog_post_type' => 'projects' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'post_meta_date_typography',
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-date-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-date-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-date-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-date-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-date-font-style: {{VALUE}};',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-date-text-decoration: {{VALUE}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-date-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-date-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-date-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
				'condition' => array( 'blog_post_type' => 'projects' ),
			)
		);

		$this->add_control(
			'post_meta_date_color',
			array(
				'label' => esc_html__( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--post-meta-date-color: {{VALUE}};',
				),
				'condition' => array( 'blog_post_type' => 'projects' ),
			)
		);

		$this->add_responsive_control(
			'post_meta_date_spacing',
			array(
				'label' => __( 'Gap', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'em',
					'%',
					'vw',
				),
				'range' => array(
					'px' => array( 'max' => 100 ),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--post-meta-date-gap: {{SIZE}}{{UNIT}};',
				),
				'condition' => array( 'blog_post_type' => 'projects' ),
			)
		);

		$this->add_control(
			'post_meta_collection_heading',
			array(
				'label' => __( 'Collection', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'blog_post_type' => 'projects' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'post_meta_typography',
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-font-style: {{VALUE}};',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-text-decoration: {{VALUE}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'post_meta_normal_color',
			array(
				'label' => esc_html__( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--post-meta-normal-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'post_meta_normal_bg_color',
			array(
				'label' => esc_html__( 'Background Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--post-meta-normal-bg-color: {{VALUE}};',
				),
				'condition' => array( 'blog_post_type' => 'projects' ),
			)
		);

		$this->add_control(
			'post_meta_hover_color',
			array(
				'label' => esc_html__( 'Hover Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--post-meta-hover-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'post_meta_hover_bg_color',
			array(
				'label' => esc_html__( 'Hover Background Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--post-meta-hover-bg-color: {{VALUE}};',
				),
				'condition' => array( 'blog_post_type' => 'projects' ),
			)
		);

		$this->add_control(
			'post_meta_before_color',
			array(
				'label' => esc_html__( 'Before Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--post-meta-before-color: {{VALUE}};',
				),
				'condition' => array( 'blog_post_type!' => 'projects' ),
			)
		);

		$this->add_responsive_control(
			'post_meta_spacing',
			array(
				'label' => __( 'Spacing', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'em',
					'%',
					'vw',
				),
				'range' => array(
					'px' => array( 'max' => 100 ),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--post-meta-margin: {{SIZE}}{{UNIT}};',
				),
				'condition' => array( 'blog_post_type' => 'projects' ),
			)
		);

		$this->add_control(
			'post_meta_before_heading',
			array(
				'label' => __( 'Before', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'blog_post_type!' => 'projects' ),
			)
		);

		$this->add_responsive_control(
			'post_meta_before_size',
			array(
				'label' => __( 'Size', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'em',
					'%',
					'vw',
				),
				'range' => array(
					'px' => array( 'max' => 100 ),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--post-meta-before-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array( 'blog_post_type!' => 'projects' ),
			)
		);

		$this->add_responsive_control(
			'post_meta_before_gap',
			array(
				'label' => __( 'Gap', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'em',
					'%',
					'vw',
				),
				'range' => array(
					'px' => array( 'max' => 100 ),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--post-meta-before-gap: {{SIZE}}{{UNIT}};',
				),
				'condition' => array( 'blog_post_type!' => 'projects' ),
			)
		);

		$this->add_control(
			'post_meta_before_border_radius',
			array(
				'label' => __( 'Border Radius', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--post-meta-before-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array( 'blog_post_type!' => 'projects' ),
			)
		);

		$this->add_control(
			'post_meta_other_heading',
			array(
				'label' => __( 'Other', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'blog_post_type' => 'projects' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'post_meta_other_typography',
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-other-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-other-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-other-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-other-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-other-font-style: {{VALUE}};',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-other-text-decoration: {{VALUE}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-other-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-other-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-other-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
				'condition' => array( 'blog_post_type' => 'projects' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'post_meta_other_label_typography',
				'label' => esc_html__( 'Label Typography', 'cmsmasters-elementor' ),
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-other-label-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-other-label-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-other-label-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-other-label-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-other-label-font-style: {{VALUE}};',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-other-label-text-decoration: {{VALUE}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-other-label-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-other-label-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-meta-other-label-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
				'condition' => array( 'blog_post_type' => 'projects' ),
			)
		);

		$this->add_control(
			'post_meta_other_color',
			array(
				'label' => esc_html__( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--post-meta-other-color: {{VALUE}};',
				),
				'condition' => array( 'blog_post_type' => 'projects' ),
			)
		);

		$this->add_control(
			'post_meta_other_label_color',
			array(
				'label' => esc_html__( 'Label Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--post-meta-other-label-color: {{VALUE}};',
				),
				'condition' => array( 'blog_post_type' => 'projects' ),
			)
		);

		$this->add_control(
			'post_meta_other_hover_color',
			array(
				'label' => esc_html__( 'Link Hover Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--post-meta-other-hover-color: {{VALUE}};',
				),
				'condition' => array( 'blog_post_type' => 'projects' ),
			)
		);

		$this->add_responsive_control(
			'post_meta_other_spacing',
			array(
				'label' => __( 'Label Gap', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'em',
					'%',
					'vw',
				),
				'range' => array(
					'px' => array( 'max' => 100 ),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--post-meta-other-label-gap: {{SIZE}}{{UNIT}};',
				),
				'condition' => array( 'blog_post_type' => 'projects' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Post Excerpt controls.
	 *
	 * Adds different input fields to allow the user to change and customize the classes settings.
	 *
	 * @since 1.0.0
	 */
	protected function register_post_excerpt_section_style() {
		$this->start_controls_section(
			'post_excerpt_section_style',
			array(
				'label' => __( 'Post Excerpt', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => array( 'blog_post_type' => 'post' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'post_excerpt_typography',
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-excerpt-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-excerpt-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-excerpt-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-excerpt-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-excerpt-font-style: {{VALUE}};',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-excerpt-text-decoration: {{VALUE}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-excerpt-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-excerpt-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--post-excerpt-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
				'condition' => array( 'blog_post_type' => 'post' ),
			)
		);

		$this->add_control(
			'post_excerpt_color',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--post-excerpt-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'post_excerpt_spacing',
			array(
				'label' => __( 'Spacing', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'em',
					'%',
					'vw',
				),
				'range' => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--post-excerpt-spacing: {{SIZE}}{{UNIT}};',
				),
				'condition' => array( 'blog_post_type' => 'post' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register blog controls.
	 *
	 * Adds different input fields to allow the user to change and customize the classes settings.
	 *
	 * @since 1.0.0
	 */
	protected function register_section_style_read_mode() {
		$this->start_controls_section(
			'section_read_mode_style',
			array(
				'label' => __( 'Post: Read More', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => array( 'blog_post_type!' => 'collections' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'read_more_typography',
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--cmsmasters-theme-button-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--cmsmasters-theme-button-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--cmsmasters-theme-button-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--cmsmasters-theme-button-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--cmsmasters-theme-button-font-style: {{VALUE}};',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--cmsmasters-theme-button-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--cmsmasters-theme-button-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--cmsmasters-theme-button-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
				'condition' => array( 'blog_post_type!' => 'collections' ),
			)
		);

		$this->start_controls_tabs(
			'read_more_style_tabs',
			array(
				'condition' => array( 'blog_post_type!' => 'collections' ),
			)
		);

		foreach ( array(
			'normal' => __( 'Normal', 'cmsmasters-elementor' ),
			'hover' => __( 'Hover', 'cmsmasters-elementor' ),
		) as $main_key => $label ) {
			$this->start_controls_tab(
				"read_more_tab_{$main_key}",
				array(
					'label' => $label,
					'condition' => array( 'blog_post_type!' => 'collections' ),
				)
			);

			$this->add_control(
				"read_morer_{$main_key}_colo",
				array(
					'label' => __( 'Text Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}}' => "--theme-blog-button-{$main_key}-color: {{VALUE}};",
					),
					'condition' => array( 'blog_post_type!' => 'collections' ),
				)
			);

			$this->add_control(
				"read_more_{$main_key}_color",
				array(
					'label' => __( 'Background Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}}' => "--theme-blog-button-{$main_key}-bg-color: {{VALUE}};",
					),
					'condition' => array( 'blog_post_type!' => 'collections' ),
				)
			);

			$this->add_control(
				"read_more_{$main_key}_bd_color",
				array(
					'label' => __( 'Border Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}}' => "--theme-blog-button-{$main_key}-border-color: {{VALUE}};",
					),
					'condition' => array(
						'blog_post_type' => 'post',
						'read_more_border_type!' => 'none',
					),
				)
			);

			$this->add_control(
				"read_more_{$main_key}_border_radius",
				array(
					'label' => __( 'Border Radius', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
					),
					'selectors' => array(
						'{{WRAPPER}}' => "--theme-blog-button-{$main_key}-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
					),
					'condition' => array( 'blog_post_type' => 'post' ),
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name' => "read_more_{$main_key}_box_shadow",
					'fields_options' => array(
						'box_shadow' => array(
							'selectors' => array(
								'{{SELECTOR}}' => "--theme-blog-button-{$main_key}-box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};",
							),
						),
					),
					'selector' => '{{WRAPPER}}',
					'condition' => array( 'blog_post_type' => 'post' ),
				)
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				array(
					'name' => "read_more_{$main_key}_text_shadow",
					'label' => esc_html__( 'Text Shadow', 'cmsmasters-elementor' ),
					'fields_options' => array(
						'text_shadow' => array(
							'selectors' => array(
								'{{SELECTOR}}' => "--theme-blog-button-{$main_key}-text-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{COLOR}};",
							),
						),
					),
					'selector' => '{{WRAPPER}}',
					'condition' => array( 'blog_post_type' => 'post' ),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'read_more_padding',
			array(
				'label' => __( 'Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}}' => '--theme-blog-button-padding-top: {{TOP}}{{UNIT}}; --theme-blog-button-padding-right: {{RIGHT}}{{UNIT}}; --theme-blog-button-padding-bottom: {{BOTTOM}}{{UNIT}}; --theme-blog-button-padding-left: {{LEFT}}{{UNIT}};',
				),
				'condition' => array( 'blog_post_type!' => 'collections' ),
			)
		);

		$this->add_control(
			'read_more_border_type',
			array(
				'label' => esc_html__( 'Border Type', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_custom_border_type_options(),
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--theme-blog-button-bd-type: {{VALUE}};',
				),
				'condition' => array( 'blog_post_type' => 'post' ),
			)
		);

		$this->add_responsive_control(
			'read_more_border_width',
			array(
				'label' => esc_html__( 'Border Width', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--theme-blog-button-top-width: {{TOP}}{{UNIT}}; --theme-blog-button-right-width: {{RIGHT}}{{UNIT}}; --theme-blog-button-bottom-width: {{BOTTOM}}{{UNIT}}; --theme-blog-button-left-width: {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'blog_post_type' => 'post',
					'read_more_border_type!' => array(
						'',
						'none',
					),
				),
			)
		);

		$this->add_responsive_control(
			'read_more_icon_size',
			array(
				'label' => __( 'Icon Size', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 50,
					),
					'em' => array(
						'max' => 10,
					),
				),
				'size_units' => array(
					'px',
					'em',
				),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}}' => '--theme-blog-button-icon-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'blog_post_type!' => 'collections',
					'read_more_icon[value]!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'read_more_icon_spacing',
			array(
				'label' => __( 'Icon Spacing', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array( 'max' => 50 ),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--theme-blog-button-icon-gap: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'blog_post_type!' => 'collections',
					'read_more_icon[value]!' => '',
				),
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
	 * @since 1.0.0
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
	 * @since 1.0.0
	 */
	protected function render_post_inner( $popup = true ) {
		$settings = $this->get_settings_for_display();

		$post_id = get_the_ID();

		if ( 'post' === $settings['blog_post_type'] ) {
			$this->render_post_thumbnail();

			echo '<div class="elementor-widget-cmsmasters-theme-blog__post-title_wrap">';

				$this->render_post_title();

				$this->render_post_meta( $post_id );

			echo '</div>';

			$this->render_post_excerpt();

			$this->render_post_button();
		}

		if ( 'projects' === $settings['blog_post_type'] ) {
			echo '<div class="elementor-widget-cmsmasters-theme-blog__post-thumbnail_wrap">';

				$this->render_post_thumbnail();

				$this->render_post_button();

			echo '</div>';

			$this->render_post_title();

			$this->render_project_footer( $post_id );
		}

		if ( 'collections' === $settings['blog_post_type'] ) {
			$this->render_collection_thumbnail( $post_id );

			$this->render_post_title();

			$this->render_collection_footer( $post_id );
		}

		if ( 'product' === $settings['blog_post_type'] ) {
			global $product;

			$product_outlet = ( isset( $settings['header_filter_product_outlet'] ) ? $settings['header_filter_product_outlet'] : '' );
			$user = wp_get_current_user();
			$not_customer_role = ( is_user_logged_in() && ! in_array( 'customer', $user->roles ) );

			if ( $popup ) {
				$this->add_to_quote_popup( $product, $post_id, $product_outlet, $not_customer_role );
			}

			echo '<div class="elementor-widget-cmsmasters-theme-blog__cont">';
				$this->render_product_thumbnail( $post_id, $product, true );

				$this->render_product_footer( $post_id );

				$this->render_post_title();

				$product_uom = wc_get_product_terms( $post_id, 'product_uom', array( 'fields' => 'names' ) );
				$uoms = ( $product_uom ? strtolower( implode( ', ', $product_uom ) ) : '' );

				if ( $product_outlet || $not_customer_role ) {
					$this->render_product_price( $product, $uoms );
				}

				$this->render_post_excerpt();

				$this->render_post_availability( $post_id, $uoms );
			echo '</div>';
		}
	}

	/**
	 * Display the post acf.
	 *
	 * @since 1.0.0
	 */
	protected function render_post_acf( $field, $tag = 'div', $class, $label = false, $post_id ) {
		if ( empty( $field ) ) {
			return;
		}

		echo '<' . tag_escape( $tag ) . ' class="elementor-widget-cmsmasters-theme-blog__post-meta-acf ' . $class . '">';

			if ( ! empty( $label ) ) {
				echo '<span class="elementor-widget-cmsmasters-theme-blog__post-meta-label ' . $class . '">' .
					esc_html__( $label, 'cmsmasters-elementor' ) .
				'</span>';
			}

			echo '<span class="elementor-widget-cmsmasters-theme-blog__post-meta-value ' . $class . '">' .
				get_field( $field, $post_id ) .
			'</span>';

		echo '</' . tag_escape( '/' . $tag ) . '>';
	}

	protected function render_post_acf_category( $taxonomy, $tag = 'div', $class, $label = false, $post_id, $comma = false, $has_additional = false ) {
		if ( empty( $taxonomy ) ) {
			return;
		}

		$terms = wp_get_post_terms( $post_id, $taxonomy );
		$additional = ( false !== $this->product_additional_fields() ? $this->product_additional_fields() : '' );

		if ( ( ! empty( $terms ) && ! is_wp_error( $terms ) ) || ( $has_additional && ! empty ( $additional ) ) ) {
			echo '<' . tag_escape( $tag ) . ' class="elementor-widget-cmsmasters-theme-blog__post-meta-acf ' . $class . '">';

				if ( ! empty( $label ) ) {
					echo '<span class="elementor-widget-cmsmasters-theme-blog__post-meta-label ' . $class . '">' .
						esc_html__( $label, 'cmsmasters-elementor' ) .
					'</span>';
				}

				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					$count = count( $terms );
					$i = 1;

					foreach ( $terms as $term ) {
						echo '<a href="' . esc_url( get_term_link( $term ) ) . '" class="elementor-widget-cmsmasters-theme-blog__post-meta-value ' . $class . ' ' . strtolower( str_replace( array( ' ', '-' ), '_', $term->name ) ) . '">' .
							$term->name .
						'</a>';

						if ( $i < $count && $comma ) {
							echo ', ';
						}

						$i++;
					}
				}

				if ( $has_additional && ! empty ( $additional ) ) {
					echo '<a href="#" class="elementor-widget-cmsmasters-theme-blog__post-meta-value product_badges ' . strtolower( str_replace( array( ' ', '-' ), '_', $this->product_additional_fields() ) ) . '">' .
						$this->product_additional_fields() .
					'</a>';
				}

			echo '</' . tag_escape( '/' . $tag ) . '>';
		}
	}

	/**
	 * Display the project footer.
	 *
	 * @since 1.0.0
	 */
	protected function render_project_footer( $post_id ) {
		echo '<div class="elementor-widget-cmsmasters-theme-blog__post-footer">' .
			'<div class="elementor-widget-cmsmasters-theme-blog__post-footer-left">';

				$this->render_post_acf( 'date', 'h4', 'date', '', $post_id );

				$this->render_post_acf_category( 'collection', 'div', 'collection', '', $post_id );

			echo '</div>' .
			'<div class="elementor-widget-cmsmasters-theme-blog__post-footer-right">';

				$this->render_post_acf( 'location', 'div', 'location', 'location', $post_id );

				$this->render_post_acf_category( 'project_design_build_firms', 'div', 'builder', 'builder', $post_id );

			echo '</div>' .
		'</div>';
	}

	/**
	 * Display the collection footer.
	 *
	 * @since 1.0.0
	 */
	protected function render_collection_footer( $post_id ) {
		echo '<div class="elementor-widget-cmsmasters-theme-blog__post-footer">';

			$this->render_post_acf_category( 'collection_category', 'div', 'collection_category', '', $post_id, true );

			$terms = wp_get_post_terms( $post_id, 'collection' );

			if ( ! empty($terms) && ! is_wp_error( $terms )) {
				echo '<span class="elementor-widget-cmsmasters-theme-blog__post-footer-separator"></span>';

				$this->get_product_count_with_collection( $terms );
			}

		echo '</div>';
	}

	/**
	 * Display the collection footer.
	 *
	 * @since 1.0.0
	 */
	protected function get_product_count_with_collection( $terms ) {
		$term = array_shift( $terms );

		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1
		);

		$products_query = new \WP_Query( $args );
		$count = 0;

		if ( $products_query->have_posts() ) {
			while ( $products_query->have_posts() ) {
				$products_query->the_post();
				$product_id = get_the_ID();

				if ( has_term( $term->term_id, 'collection', $product_id ) ) {
					$count++;
				}
			}

			wp_reset_postdata();
		}

		$label = ( 1 === $count ? 'product' : 'products' );

		echo '<span class="elementor-widget-cmsmasters-theme-blog__post-footer-product-count">' .
			$count . ' ' . esc_html( $label );
		'</span>';
	}

	protected function product_additional_fields() {
		$settings = $this->get_settings_for_display();

		$additional = false;

		if ( 'product' === $settings['blog_post_type'] ) {
			global $product;

			// if ( $product->is_on_sale() ) {
			// 	echo '<a href="#" class="elementor-widget-cmsmasters-theme-blog__post-meta-value product_badges is_on_sale">Sale</a>';
			// }

			// echo '<a href="#" class="elementor-widget-cmsmasters-theme-blog__post-meta-value product_badges is_on_sale">Out of Stock</a>';
			// $product->is_in_stock() ? 'In Stock' : ( $product->is_on_sale() ? 'On Sale' : 'Out of Stock' );
			if ( $product->is_on_sale() ) {
				$additional = 'Sale';
			} elseif ( ! $product->is_in_stock() ) {
				$additional .= 'Special Order';
				// } else {
				// 	echo '<a href="#" class="elementor-widget-cmsmasters-theme-blog__post-meta-value product_badges is_on_sale">In Stock</a>';
			}
		}

		return $additional;
	}

	/**
	 * Display the product thumbnail.
	 *
	 * @since 1.0.0
	 */
	protected function render_product_thumbnail( $product_id, $product, $large = true ) {
		$settings = $this->get_settings_for_display();

		if ( ! has_post_thumbnail() ) {
			return;
		}

		echo '<div class="elementor-widget-cmsmasters-theme-blog__post-thumbnail">';

			if ( $large ) {
				$this->render_post_acf_category( 'product_badges', 'div', 'product_badges', '', $product_id, false, true );

				$this->wpclever_smart_wishlist_render( $product_id, $product );
			}

			echo '<a href="' . esc_attr( get_permalink() ) . '" class="elementor-widget-cmsmasters-theme-blog__post-thumbnail-inner">';

				$size = ( $large ? 'thumbnail' : 'thumbnail_popup' );

				$settings[$size] = array(
					'id' => get_post_thumbnail_id(),
				);

				echo Group_Control_Image_Size::get_attachment_image_html( $settings, $size );

				if ( get_field( 'product_image_overlay', $product_id ) ) {
					$image_overlay = get_field( 'product_image_overlay', $product_id );
					$image_class = " product_image_overlay attachment-medium_large size-medium_large wp-image-{medium_large['id']}";
					$image_attr = array( 'class' => trim( $image_class ) );

					echo wp_get_attachment_image( $image_overlay, 'medium_large', false, $image_attr );
				}

			echo '</a>';

			$price = $this->product_price( $product );
			$product_outlet = ( isset( $settings['header_filter_product_outlet'] ) ? $settings['header_filter_product_outlet'] : '' );
			$user = wp_get_current_user();
			$not_customer_role = ( is_user_logged_in() && ! in_array( 'customer', $user->roles ) );

			if ( $large ) {
				if ( $price && ( $product_outlet || $not_customer_role ) ) {
					echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-button cart">' .
						'<span class="elementor-widget-cmsmasters-theme-blog__post-add-to-button-trigger">' .
							'<span class="add-to-button-text">' .
								esc_html__( 'Add to Cart', 'cmsmasters-elementor' ) .
							'</span>';

							Icons_Manager::render_icon( array(
								'value' => 'themeicon- theme-icon-plus',
								'library' => 'themeicon-',
							), array( 'class' => 'add-to-button-icon' ) );

						echo '</span>' .
					'</div>';
				} else {
					echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-button quote">' .
						'<span class="elementor-widget-cmsmasters-theme-blog__post-add-to-button-trigger">' .
							'<span class="add-to-button-text">' .
								esc_html__( 'Add to Quote', 'cmsmasters-elementor' ) .
							'</span>';

							Icons_Manager::render_icon( array(
								'value' => 'themeicon- theme-icon-plus',
								'library' => 'themeicon-',
							), array( 'class' => 'add-to-button-icon' ) );

						echo '</span>' .
					'</div>';
				}
			}

		echo '</div>';
	}

	protected function get_calculator( $uom, $product_sizes, $full_packaged, $pack ) {
		$sizes = implode( ', ', $product_sizes );

		if ( $sizes ) {
			$regex = '/["x]+/';

			if ( ! empty( $sizes ) && preg_match('/^\d/', $sizes ) ) {
				$sizesArray = preg_split( $regex, $sizes );

				[ $width, $height ] = array_map( 'intval', $sizesArray );
			} else {
				$width = 144;
				$height = 1;
			}

			// list( $width, $height, $thickness ) = explode( '"x', $sizes );
		} else {
			$width = 'none';
			$height = 'none';
		}

		echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-input-item" product-uom="' . $uom . '" product-width="' . $width . '" product-height="' . $height . '">';

			$full_packaged_uom = ( 'yes' === $full_packaged && 'each' === $uom ? 'pallet' : $uom );

			echo '<span class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-input-uom">' . $full_packaged_uom . '</span>';

			Icons_Manager::render_icon( array(
				'value' => 'themeicon- theme-icon-minus',
				'library' => 'themeicon-',
			), array( 'class' => 'elementor-widget-cmsmasters-theme-blog__post-add-to-quote-input-operator decrement disable' ) );

			echo '<input class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-input" type="number" placeholder="QTY (' . $full_packaged_uom . ')" maxlength="4" full-packaged="' . $full_packaged . '" pack="' . ( $pack ? esc_html( $pack ) : '0' ) . '"></input>';

			Icons_Manager::render_icon( array(
				'value' => 'themeicon- theme-icon-plus',
				'library' => 'themeicon-',
			), array( 'class' => 'elementor-widget-cmsmasters-theme-blog__post-add-to-quote-input-operator increment' ) );

		echo '</div>';
	}

	protected function get_quote_button( $product_id ) {
		if ( ! $product_id ) {
			global $product, $post;

			if ( ! $product instanceof \WC_Product && $post instanceof \WP_Post ) {
				$product = wc_get_product( $post->ID );
			}
		} else {
			$product = wc_get_product( $product_id );
		}

		$quote_premium = ( defined( 'YITH_YWRAQ_PREMIUM' ) ? ' quote_premium' : '' );
		$style_button = get_option( 'ywraq_show_btn_link', 'button' ) === 'button' ? 'button' : 'ywraq-link';
		$style_button = $args['style'] ?? $style_button;
		$class = 'theme_add_to_quote_popup ' . $style_button;
		$wpnonce = wp_create_nonce( 'add-request-quote-' . $product_id );
		$label = ywraq_get_label( 'btn_link_text' );
		$label_browse = ywraq_get_label( 'browse_list' );
		$rqa_url = YITH_Request_Quote()->get_raq_page_url();
		$exists = false;

		if ( $product ) {
			$exists = $product->is_type( 'variable' ) ? false : YITH_Request_Quote()->exists( $product_id );
		}

		?>
		<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-button-wrap<?php echo esc_attr( $quote_premium ); ?>">
			<div class="yith-ywraq-add-button <?php echo esc_attr( ( $exists ) ? 'hide' : 'show' ); ?>" style="display:<?php echo esc_attr( ( $exists ) ? 'none' : 'block' ); ?>">
				<a href="#" class="<?php echo esc_attr( $class ); ?>" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-wp_nonce="<?php echo esc_attr( $wpnonce ); ?>" data-list_text="<?php echo wp_kses_post( $label_browse ); ?>">
					<?php echo wp_kses_post( $label ); ?>
				</a>
				<span class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-button-icon ajax-loading">
					<img src="<?php echo esc_url( ywraq_get_ajax_default_loader() ); ?>" alt="loading" width="16" height="16" />
				</span>
			</div>
			<?php if ( $exists ) : ?>
				<div class="yith_ywraq_add_item_browse-list-<?php echo esc_attr( $product_id ); ?> yith_ywraq_add_item_browse_message">
					<a href="<?php echo esc_url( $rqa_url ); ?>"><?php echo wp_kses_post( $label_browse ); ?></a>
				</div>
			<?php endif ?>
		</div>
		<?php
	}

	protected function get_labels() {
		echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-labels">' .
			'<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-label sizes">' .
				esc_html__( 'Product Name', 'cmsmasters-elementor' ) .
			'</div>' .
			'<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-label packs">' .
				esc_html__( 'Pack', 'cmsmasters-elementor' ) .
			'</div>' .
			'<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-label uom">' .
				esc_html__( 'UOM', 'cmsmasters-elementor' ) .
			'</div>' .
			'<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-label price">' .
				esc_html__( 'Price', 'cmsmasters-elementor' ) .
			'</div>' .
			'<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-label availability">' .
				esc_html__( 'Available', 'cmsmasters-elementor' ) .
			'</div>' .
			'<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-label incoming">' .
				esc_html__( 'Incoming', 'cmsmasters-elementor' ) .
			'</div>' .
		'</div>';
	}

	protected function get_product( $product_id, $this_type_names, $product_price, $product_width, $product_height, $sizes, $packs, $uoms, $current_ID ) {
		$current = ( $product_id === $current_ID ? true : false );

		$product_badge = wc_get_product_terms( $product_id, 'product_badges', array( 'fields' => 'names' ) );
		$badge = ( $product_badge ? ' - ' . ucwords( implode( ', ', $product_badge ) ) : '' );
		$product_title = get_field( 'product_short_name', $product_id );

		$full_packaged = ( get_field( 'full_packaged_products', $product_id ) ? 'yes' : 'no' );

		echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product' . ( $current ? ' current' : '' ) . '" product-id=' . $product_id . '" full-packaged="' . $full_packaged . '" pack="' . ( $packs ? esc_html( $packs ) : '0' ) . '">' .
			'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr sizes">' .
				'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr-inner">';

					$data_wp_nonce = wp_create_nonce( 'add-request-quote-' . $product_id );

					Icons_Manager::render_icon( array(
						'value' => 'themeicon- theme-icon-radio_button_unchecked',
						'library' => 'themeicon-',
					), array( 'class' => 'product-chooses-attr-icon unchecked' ) );

					Icons_Manager::render_icon( array(
						'value' => 'themeicon- theme-icon-radio-button-checked',
						'library' => 'themeicon-',
					), array( 'class' => 'product-chooses-attr-icon checked' ) );

					echo '<input class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr-checkbox" type="checkbox" id="' . $product_id . '" data-product-width="' . $product_width . '"  data-product-height="' . $product_height . '"  data-wp_nonce="' . $data_wp_nonce . '" data-uoms="' . $uoms . '" name="' . ( $sizes ? esc_html( $sizes ) : '0' ) . '" value="' . ( $sizes ? esc_html( $sizes ) : '0' ) . '">';

					echo ( ! empty( $product_title ) ? $product_title : ( ( $sizes ? esc_html( $sizes ) : '0' ) . ' ' . $this_type_names ) ) . $badge .
				'</div>' .
			'</div>' .
			'<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr packs">' .
				'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr-inner">' .
					( $packs ? esc_html( $packs ) : '0' ) .
				'</div>' .
			'</div>' .
			'<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr uoms">' .
				'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr-inner">' .
					( $uoms ? esc_html( $uoms ) : '-' ) .
				'</div>' .
			'</div>';
			
			$product_outlet = ( isset( $settings['header_filter_product_outlet'] ) ? $settings['header_filter_product_outlet'] : '' );
			$user = wp_get_current_user();
			$not_customer_role = ( is_user_logged_in() && ! in_array( 'customer', $user->roles ) );

			echo '<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr price">' .
				'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr-inner">' .
					( ( ( $product_outlet || $not_customer_role ) && $product_price ) ? $product_price : '-' ) .
				'</div>' .
			'</div>' .
			'<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr availability">' .
				'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr-inner">' .
					( ! empty( get_field( 'available', $product_id ) ) ? get_field( 'available', $product_id ) : '0' ) .
				'</div>' .
			'</div>' .
			'<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr incoming">' .
				'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr-inner">' .
					( ! empty( get_field( 'incoming', $product_id ) ) ? get_field( 'incoming', $product_id ) : '0' ) .
				'</div>' .
			'</div>' .
		'</div>';
	}

	protected function get_product_chooses( $current_ID ) {
		$product_types = wc_get_product_terms( $current_ID, 'theme_product_type', array( 'fields' => 'all' ) );
		$collections = wc_get_product_terms( $current_ID, 'collection', array( 'fields' => 'all' ) );

		$this_type_names = array();
		$this_type_ids = array();
		$this_collection_name = array();
		$this_collection_id = array();

		foreach ( $product_types as $type ) {
			$this_type_names = $type->name;
			$this_type_ids = $type->term_id;
		}

		foreach ( $collections as $collection ) {
			$this_collection_name = $collection->name;
			$this_collection_id = $collection->term_id;
		}

		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'collection',
					'field' => 'term_id',
					'terms' => $this_collection_id,
				),
				array(
					'taxonomy' => 'theme_product_type',
					'field' => 'term_id',
					'terms' => $this_type_ids,
				),
			),
			'orderby' => 'meta_value_num',
			'order' => 'ASC',
			'meta_key' => 'collection_order'
		);

		$products = get_posts( $args );
		$parent_type = array();

		if ( ! $products ) {
			return;
		}

		foreach ( $products as $product ) {
			$product_id = $product->ID;

			$product_obj = wc_get_product( $product_id );

			$product_price = $product_obj->get_price();

			if ( $product_price !== '' ) {
				$product_price = wc_price( $product_price );
			} else {
				$product_price = '-';
			}

			$sizes = wc_get_product_terms( $product_id, 'product_size', array( 'fields' => 'names' ) );
			$packs = wc_get_product_terms( $product_id, 'product_pack', array( 'fields' => 'names' ) );
			$uoms = wc_get_product_terms( $product_id, 'product_uom', array( 'fields' => 'names' ) );

			$regex = '/["x]+/';

			if ( ! empty( $sizes[0] ) && ctype_digit( substr( $sizes[0], 0, 1 ) ) ) {
				$sizesArray = preg_split( $regex, $sizes[0] );

				[ $width, $height ] = array_map( 'intval', $sizesArray );
			} else {
				$width = 144;
				$height = 1;
			}

			$parent_type[$product_id] = array(
				'product_id' => $product_id,
				'product_price' => $product_price,
				'product_width' => intval( $width ),
				'product_height' => intval( $height ),
				'product_sizes' => implode( ', ', $sizes ),
				'product_packs' => implode( ', ', $packs ),
				'product_uoms' => implode( ', ', $uoms ),
			);
		}

		if ( empty( $parent_type )) {
			return;
		}

		echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-wrapper">';
			$product_finishes = wc_get_product_terms( $current_ID, 'product_finishes', array( 'fields' => 'names' ) );
	
			if ( ! empty( $product_finishes ) ) {
				$this_product_finishes = ' - ' . $product_finishes[0];
			} else {
				$this_product_finishes = '';
			}

			echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-type_wrap">' .
				'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-type">' .
					( $this_collection_name . $this_product_finishes . ' - ' .  $this_type_names ) .
				'</div>';

				$this->get_labels();

				echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-products">';

					foreach ( $parent_type as $element ) {
						$this->get_product( $element['product_id'], $this_type_names, $element['product_price'], $element['product_width'], $element['product_height'], $element['product_sizes'], $element['product_packs'], $element['product_uoms'], $current_ID );
					}

				echo '</div>' .
			'</div>' .
		'</div>';
	}

	protected function get_popup_collections( $current_ID ) {
		$product_types = wc_get_product_terms( $current_ID, 'theme_product_type', array( 'fields' => 'all' ) );
		$collections = wc_get_product_terms( $current_ID, 'collection', array( 'fields' => 'all' ) );

		$this_type_ids = array();
		$this_collection_id = array();

		foreach ( $product_types as $type ) {
			$this_type_ids = $type->term_id;
		}

		foreach ( $collections as $collection ) {
			$this_collection_id = $collection->term_id;
		}

		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'collection',
					'field' => 'term_id',
					'terms' => $this_collection_id,
				),
				array(
					'taxonomy' => 'theme_product_type',
					'field' => 'term_id',
					'terms' => $this_type_ids,
				),
			),
			'orderby' => 'meta_value_num',
			'order' => 'ASC',
			'meta_key' => 'collection_order'
		);

		$products = get_posts( $args );

		if ( ! $products ) {
			return;
		}

		foreach ( $products as $product ) {
			$product_id = $product->ID;

			$product_obj = wc_get_product( $product_id );

			$product_price = $product_obj->get_price();

			if ( $product_price !== '' ) {
				$product_price = wc_price( $product_price );
			} else {
				$product_price = '';
			}

			echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-collection-inner-cont-wrap' . ( $product_id === $current_ID ? ' show' : '' ) . '" product-id="' . $product_id . '">';

				if ( has_post_thumbnail() ) {
					echo '<div class="elementor-widget-cmsmasters-theme-blog__post-thumbnail 2">' .
						'<a href="' . esc_attr( get_permalink( $product_id ) ) . '" class="elementor-widget-cmsmasters-theme-blog__post-thumbnail-inner">';
			
							$settings['thumbnail_popup'] = array(
								'id' => get_post_thumbnail_id( $product_id ),
							);
			
							echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail_popup' );
			
						echo '</a>' .
					'</div>';
				}

				echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-collection-cont">';
					$title = get_the_title( $product_id );

					if ( ! $title ) {
						$title = '(' . esc_html__( 'No Title', 'cmsmasters-elementor' ) . ')';
					}

					$uom = wc_get_product_terms( $product_id, 'product_uom', array( 'fields' => 'names' ) );
					$uom = ( ! empty( $uom ) ? $uom[0] : 'each' );

					echo '<div class="elementor-widget-cmsmasters-theme-blog__post-title">' .
						'<a href="' . get_permalink( $product_id ) . '">' .
							wp_kses_post( $title ) .
						'</a>' .
					'</div>';

					$product_outlet = ( isset( $settings['header_filter_product_outlet'] ) ? $settings['header_filter_product_outlet'] : '' );
					$user = wp_get_current_user();
					$not_customer_role = ( is_user_logged_in() && ! in_array( 'customer', $user->roles ) );
		
					if ( $product_outlet || $not_customer_role ) {
						echo $product_price . ( $product_price ? '/' . strtolower( $uom ) : '' );
					}

				echo '</div>' .
			'</div>';
		}
	}

	protected function add_to_quote_popup( $product, $product_id, $product_outlet, $not_customer_role ) {
		if ( ! defined( 'YITH_YWRAQ_FREE_INIT' ) && ! defined( 'YITH_YWRAQ_PREMIUM' ) ) {
			return;
		}

		$product_sizes = wc_get_product_terms( $product_id, 'product_size', array( 'fields' => 'names' ) );
		$uom = wc_get_product_terms( $product_id, 'product_uom', array( 'fields' => 'names' ) );
		$uom = ( ! empty( $uom ) ? $uom[0] : 'each' );

		echo '<div id="post-' . $product_id . '" class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup">' .
			'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-inner">' .
				'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-cont">' .
					'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-collection-wrap">' .
						'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-collection">' .
							'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-collection-inner">';

								$this->get_popup_collections( $product_id );

							echo '</div>';

							Icons_Manager::render_icon( array(
								'value' => 'themeicon- theme-icon-close',
								'library' => 'themeicon-',
							), array( 'class' => 'elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-close' ) );

						echo '</div>';

						$this->get_product_chooses( $product_id );

					echo '</div>' .
					'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote" product-uom="' . strtolower( $uom ) . '">' .
						'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-inputs">';


						$full_packaged = ( get_field( 'full_packaged_products', $product_id ) ? 'yes' : 'no' );
						$packs = wc_get_product_terms( $product_id, 'product_pack', array( 'fields' => 'names' ) );
						$pack = implode( ', ', $packs );

							// if ( ! empty( $product_sizes ) && ! empty( $uom ) && 'sqft' === strtolower( $uom ) ) {
							if ( ! empty( $product_sizes ) && ! empty( $uom ) ) {
								$this->get_calculator( 'sqft', $product_sizes, $full_packaged, $pack );
							}

							$this->get_calculator( 'each', $product_sizes, $full_packaged, $pack );

						echo '</div>';

						$price = $this->product_price( $product );
			
						if ( $price && ( $product_outlet || $not_customer_role ) ) {
							$this->add_to_cart( $product, $product_id );
						} else {
							$this->get_quote_button( $product_id );
						}

					echo '</div>' .
				'</div>' .
			'</div>' .
		'</div>';
	}

	protected function add_to_cart( $product, $product_id ) {
		if ( empty( $product ) ) {
			if ( ! empty( $product_id ) ) {
				$product = wc_get_product( $product_id );
			} else {
				return;
			}
		}

		$this->add_render_attribute( 'cmsmasters_add_to_cart', 'class', "elementor-widget-cmsmasters-theme-blog__post-add-to-cart" );
		$this->add_render_attribute( 'cmsmasters_add_to_cart', 'class', 'cmsmasters-product-' . esc_attr( $product->get_type() ) . '' );

		$attribute_cmsmasters_add_to_cart = $this->get_render_attribute_string( 'cmsmasters_add_to_cart' );

		$meta_sku = get_post_meta( $product_id, '_sku', true );
		$sku = ( $meta_sku ? $meta_sku : '' );

		echo "<div {$attribute_cmsmasters_add_to_cart}>" .
			'<form class="cart" action="' . esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ) . '" method="post" enctype="multipart/form-data">' .
				'<button type="submit" name="add-to-cart" value="' . esc_attr( $product->get_id() ) . '" class="single_add_to_cart_button button alt ' . esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) .'" data-product_id="' . $product_id . '" data-product_sku="' . $sku . '">' .
					esc_html( $product->single_add_to_cart_text() ) .
					'<span class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-button-icon ajax-loading">' .
						'<img src="' . esc_url( ywraq_get_ajax_default_loader() ) . '" alt="loading" width="16" height="16" />' .
					'</span>' .
				'</button>' .
			'</form>' .
		'</div>';
	}

	public function render_icon() {
		$prefix_class = 'elementor-widget-cmsmasters-theme-blog__post-wishlist';

		$this->add_render_attribute( 'icon-wrapper', 'class', array(
			"{$prefix_class}-button-icon-wrapper",
		) );

		$this->add_render_attribute( 'icon-normal', 'class', array(
			"{$prefix_class}-button-icon",
			"{$prefix_class}-button-normal",
		) );

		$this->add_render_attribute( 'icon-active', 'class', array(
			"{$prefix_class}-button-icon",
			"{$prefix_class}-button-active",
		) );

		ob_start();

		echo "<div {$this->get_render_attribute_string( 'icon-wrapper' )}>";

			echo "<span {$this->get_render_attribute_string( 'icon-normal' )}>";
				Icons_Manager::render_icon( array(
					'value' => 'themeicon- theme-icon-heart-empty',
					'library' => 'themeicon-',
				) );
			echo '</span>';

			echo "<span {$this->get_render_attribute_string( 'icon-active' )}>";
				Icons_Manager::render_icon( array(
					'value' => 'themeicon- theme-icon-heart-full',
					'library' => 'themeicon-',
				) );
			echo '</span>';

		echo "</div>";

		return ob_get_clean();
	}

	public function is_editor() {
		return Plugin::elementor()->editor->is_edit_mode();
	}

	public function wishlist_button_html( $output, $product ) {
		$prefix_class = 'elementor-widget-cmsmasters-theme-blog__post-wishlist';

		$this->remove_render_attribute( 'wpclever-wishlist' );

		$tag = 'a';

		$attrs = array();

		if ( is_object( $product ) ) {
			$product_image_id = $product->get_image_id();
			$attrs['product_id'] = $product->get_id();
			$attrs['product_name'] = $product->get_name();
			$attrs['product_image'] = wp_get_attachment_image_url( $product_image_id );
		} else {
			$product_image_id = '1';
			$attrs['product_id'] = '1';
			$attrs['product_name'] = 'name';
			$attrs['product_image'] = '#';
		}

		$product_id = $attrs['product_id'];
		$product_name = $attrs['product_name'];
		$product_image = $attrs['product_image'];

		$this->add_render_attribute( 'wpclever-wishlist', 'class', array(
			'woosw-btn',
			'woosw-btn-' . $product_id . '',
			"{$prefix_class}__general",
		) );

		$this->add_render_attribute( 'wpclever-wishlist', 'class', array(
			'woosw-btn-has-icon',
		) );

		$this->add_render_attribute( 'wpclever-wishlist', 'data-id', array(
			$product_id,
		) );

		$this->add_render_attribute( 'wpclever-wishlist', 'data-product_name', array(
			$product_name,
		) );

		$this->add_render_attribute( 'wpclever-wishlist', 'data-product_image', array(
			$product_image,
		) );

		$this->add_render_attribute( 'wpclever-wishlist', 'class', array(
			"{$prefix_class}-button",
		) );

		$this->add_render_attribute( 'wpclever-wishlist', 'href', array(
			'?add-to-wishlist=' . $attrs['product_id'],
		) );

		if ( $this->is_editor() ) {
			$this->add_render_attribute( 'wpclever-wishlist', 'disabled', array(
				'disabled'
			) );
		}

		$output = '<' . $tag . ' ' . $this->get_render_attribute_string( 'wpclever-wishlist' ) . '>' .
			$this->render_icon() .
		'</' . $tag . '>';

		return $output;
	}

	public function wpclever_smart_wishlist_render( $product_id, $product ) {
		if ( ! class_exists( 'WPCleverWoosw' ) ) {
			return;
		}

		$woosw = new \WPCleverWoosw;

		$attrs = array(
			'id'   => $product_id,
			'type' => $woosw::get_setting( 'button_type', 'button' ),
		);

		add_filter( 'woosw_button_html', function ( $output ) use ( $product ) {
			return $this->wishlist_button_html( $output, $product );
		}, 11, 1 );

		$shortcode = "[woosw 
					id=\"{$attrs['id']}\" 
					type=\"{$attrs['type']}\"]";

		$shortcode = do_shortcode( shortcode_unautop( $shortcode ) );

		$this->add_render_attribute( 'wpclever-wishlist-wapper', 'class', array(
			'elementor-widget-cmsmasters-theme-blog__post-wishlist',
		) );

		echo "<div {$this->get_render_attribute_string( 'wpclever-wishlist-wapper' )}>{$shortcode}</div>";
	}

	/**
	 * Display the product footer.
	 *
	 * @since 1.0.0
	 */
	protected function render_product_footer( $post_id ) {
		echo '<div class="elementor-widget-cmsmasters-theme-blog__post-footer">';
	
			$terms = wp_get_post_terms( $post_id, 'collection' );
	
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				echo '<div class="elementor-widget-cmsmasters-theme-blog__post-meta-acf collection">';
	
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
						$count = count( $terms );
						$i = 1;
	
						foreach ( $terms as $term ) {
							$term_link = str_replace( '/collection/', '/collections/', get_term_link( $term ) );

							echo '<a href="' . esc_url( $term_link ) . '" class="elementor-widget-cmsmasters-theme-blog__post-meta-value collection ' . strtolower( str_replace( array( ' ', '-' ), '_', $term->name ) ) . '">' .
								$term->name .
							'</a>';
	
							if ( $i < $count ) {
								echo ', ';
							}
	
							$i++;
						}
					}
	
				echo '</div>';
			}

		echo '</div>';
	}

	protected function product_price( $product ) {
		if ( ! $product ) {
			return false;
		}

		$has_price = false;

		if ( $product->is_type( 'variable' ) ) {
			$price = (int) $product->get_variation_price();
		} else {
			$price = (int) $product->get_price();
		}

		if ( $price ) {
			$has_price = $price;
		}

		return $has_price;
	}

	protected function get_sale_percentage( $product ) {
		$price = $this->product_price( $product );

		if ( ! $price ) {
			return;
		}

		if ( $product->is_type( 'variable' ) ) {
			$regular_price = (int) $product->get_variation_regular_price();
		} else {
			$regular_price = (int) $product->get_regular_price();
		}

		$discount = round( ( $regular_price - $price ) / $regular_price * 100, 0 );

		$discount_text = 'save ' . (string) $discount . '%';

		$epsilon = 0.000001;

		if ( abs( $discount ) > $epsilon ) {
			echo '<div class="elementor-widget-cmsmasters-theme-blog__post-price-discount">' .
				esc_html( $discount_text ) .
			'</div>';
		}
	}

	/**
	 * Display the product price.
	 *
	 * @since 1.0.0
	 */
	protected function render_product_price( $product, $uoms ) {
		$price = $this->product_price( $product );

		if ( $price ) {
			echo '<div class="elementor-widget-cmsmasters-theme-blog__post-price">' .
				'<div class="elementor-widget-cmsmasters-theme-blog__post-price-inner">';
					wc_get_template( '/single-product/price.php' );

					echo '<span class="elementor-widget-cmsmasters-theme-blog__post-price-symbol">' . ( $uoms ? '/' . $uoms : '' ) . '</span>' .
				'</div>';

				$this->get_sale_percentage( $product );

			echo '</div>';
		}
	}

	/**
	 * Display the product availability.
	 *
	 * @since 1.0.0
	 */
	protected function render_post_availability( $post_id, $uoms ) {
		$availability = get_field( 'available', $post_id );
		$incoming = get_field( 'incoming', $post_id );
		$contact_us = get_field( 'contact_us_page', 'options' );

		echo '<span class="elementor-widget-cmsmasters-theme-blog__post-availability-wrap">';

		if ( $availability || $incoming ) {
			echo '<span class="elementor-widget-cmsmasters-theme-blog__post-availability">' .
				'<span class="elementor-widget-cmsmasters-theme-blog__post-availability-label">' .
					esc_html__( 'Available', 'cmsmasters-elementor' ) .
				'</span>' .
				'<span class="elementor-widget-cmsmasters-theme-blog__post-availability-count">' .
					( ! empty( $availability ) ? $availability . ' ' . $uoms : '-' ) .
				'</span>' .
			'</span>' .
			'<span class="elementor-widget-cmsmasters-theme-blog__post-incoming">' .
				'<span class="elementor-widget-cmsmasters-theme-blog__post-incoming-label">' .
					esc_html__( 'Incoming', 'cmsmasters-elementor' ) .
				'</span>' .
				'<span class="elementor-widget-cmsmasters-theme-blog__post-incoming-count">' .
					( ! empty( $incoming ) ? $incoming . ' ' . $uoms : '-' ) .
				'</span>' .
			'</span>';
		} else {
			echo '<span class="elementor-widget-cmsmasters-theme-blog__post-contact-us">' .
				'<span class="elementor-widget-cmsmasters-theme-blog__post-contact-us-label">' .
					esc_html__( 'Special Order', 'cmsmasters-elementor' ) .
				'</span>' .
				'<span class="elementor-widget-cmsmasters-theme-blog__post-contact-us-count">' .
					'<a href="' . esc_url( $contact_us ) . '">' .
						esc_html__( 'Contact Us', 'cmsmasters-elementor' ) .
					'</a>' .
				'</span>' .
			'</span>';
		}

		echo '</span>';
	}

	/**
	 * Display the post thumbnail.
	 *
	 * @since 1.0.0
	 */
	protected function render_post_thumbnail() {
		$settings = $this->get_settings_for_display();

		if ( ! has_post_thumbnail() ) {
			return;
		}

		$settings['thumbnail'] = array(
			'id' => get_post_thumbnail_id(),
		);

		echo '<div class="elementor-widget-cmsmasters-theme-blog__post-thumbnail">' .
			'<a href="' . esc_attr( get_permalink() ) . '" class="elementor-widget-cmsmasters-theme-blog__post-thumbnail-inner">';

				echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail' );

			echo '</a>' .
		'</div>';
	}

	/**
	 * Display the post thumbnail.
	 *
	 * @since 1.0.0
	 */
	protected function render_collection_thumbnail( $post_id ) {
		$settings = $this->get_settings_for_display();

		if ( ! has_post_thumbnail() ) {
			return;
		}

		$settings['thumbnail'] = array(
			'id' => get_post_thumbnail_id(),
		);

		echo '<div class="elementor-widget-cmsmasters-theme-blog__post-thumbnail">';

			$this->render_post_acf_category( 'collection_badges', 'div', 'collection_badges', '', $post_id );

			echo '<a href="' . esc_attr( get_permalink() ) . '" class="elementor-widget-cmsmasters-theme-blog__post-thumbnail-inner">';

				echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail' );

				if ( get_field( 'collection_image_overlay', $post_id ) ) {
					$image_overlay = get_field( 'collection_image_overlay', $post_id );
					$image_class = " project_image_overlay attachment-medium_large size-medium_large wp-image-{medium_large['id']}";
					$image_attr = array( 'class' => trim( $image_class ) );

					echo wp_get_attachment_image( $image_overlay, 'medium_large', false, $image_attr );
				}

			echo '</a>' .
		'</div>';
	}

	/**
	 * Display the post title.
	 *
	 * @since 1.0.0
	 */
	protected function render_post_title() {
		$title = get_the_title();

		if ( ! $title ) {
			$title = '(' . esc_html__( 'No Title', 'cmsmasters-elementor' ) . ')';
		}

		echo '<h3 class="elementor-widget-cmsmasters-theme-blog__post-title">' .
			'<a href="' . get_permalink() . '">' .
				wp_kses_post( $title ) .
			'</a>' .
		'</h3>';
	}

	/**
	 * Display the read more.
	 *
	 * @since 1.0.0
	 */
	public function render_post_button() {
		$settings = $this->get_settings_for_display();

		if ( 'yes' !== $settings['post_read_more_show'] ) {
			return;
		}

		$read_more_text = $settings['read_more_text'];

		if ( ! $read_more_text ) {
			$read_more_text = esc_html__( 'Read More', 'cmsmasters-elementor' );
		}

		echo '<div class="elementor-widget-cmsmasters-theme-blog__button-wrap">' .
			'<a class="elementor-widget-cmsmasters-theme-blog__button cmsmasters-theme-button" href="' . get_permalink() . '">';

				Utils::render_icon(
					$settings['read_more_icon'],
					array( 'aria-hidden' => 'true' )
				);

				echo '<span>' .
					esc_html( $read_more_text ) .
				'</span>';

			echo '</a>' .
		'</div>';
	}

	/**
	 * Display the post excerpt.
	 *
	 * @since 1.0.0
	 */
	protected function render_post_excerpt() {
		if ( ! get_the_excerpt() ) {
			return;
		}

		$has_excerpt = has_excerpt();

		if ( $has_excerpt ) {
			add_filter( 'wp_trim_excerpt', array( $this, 'filter_wp_trim_excerpt' ) );
		} else {
			add_filter( 'excerpt_more', array( $this, 'filter_excerpt_more' ), 20 );
			add_filter( 'excerpt_length', array( $this, 'filter_excerpt_length' ), 20 );
		}

		echo '<div class="elementor-widget-cmsmasters-theme-blog__post-excerpt">' .
			get_the_excerpt() .
		'</div>';

		if ( $has_excerpt ) {
			remove_filter( 'wp_trim_excerpt', array( $this, 'filter_wp_trim_excerpt' ) );
		} else {
			remove_filter( 'excerpt_length', array( $this, 'filter_excerpt_length' ), 20 );
			remove_filter( 'excerpt_more', array( $this, 'filter_excerpt_more' ), 20 );
		}
	}

	/**
	 * Get text after a trimmed excerpt.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function filter_excerpt_more() {
		return '...';
	}

	/**
	 * Crop excerpt.
	 *
	 * @param string $excerpt
	 *
	 * @return string
	 */
	public function filter_wp_trim_excerpt( $excerpt ) {
		return wp_trim_words( $excerpt, $this->filter_excerpt_length(), $this->filter_excerpt_more() );
	}

	/**
	 * Get maximum number of words in a post excerpt.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function filter_excerpt_length() {
		return $this->get_settings_fallback( 'excerpt_length' );
	}

	/**
	 * Display the post meta.
	 *
	 * @since 1.0.0
	 */
	protected function render_post_meta( $post_id ) {
		$categories = get_the_category( $post_id );

		if ( empty( $categories ) ) {
			return;
		}

		$category = $categories[0];
		$category_name = esc_html( $category->name );
		$category_link = esc_url( get_category_link( $category->cat_ID ) );

		echo '<div class="elementor-widget-cmsmasters-theme-blog__post-meta">' .
			'<a href="' . $category_link . '">' .
				$category_name .
			'</a>' . 
		'</div>';
	}

	/**
	 * Get class for default styling.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_css_class() {
		return 'cmsmasters-blog--type-default';
	}

	/**
	 * Get selector for default styling.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_blog_selector() {
		return '{{WRAPPER}} .' . static::get_css_class();
	}

}
