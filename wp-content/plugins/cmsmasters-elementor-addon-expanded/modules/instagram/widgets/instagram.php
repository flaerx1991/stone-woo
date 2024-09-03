<?php
namespace CmsmastersElementor\Modules\Instagram\Widgets;

use CmsmastersElementor\Base\Base_Widget;
use CmsmastersElementor\Controls_Manager as CmsmastersControls;
use CmsmastersElementor\Controls\Groups\Group_Control_Button_Background;
use CmsmastersElementor\Controls\Groups\Group_Control_Format_Date;
use CmsmastersElementor\Modules\AjaxWidget\Module as AjaxWidgetModule;
use CmsmastersElementor\Modules\Instagram\Module as InstagramModule;
use CmsmastersElementor\Modules\Settings\Settings_Page;
use CmsmastersElementor\Modules\Slider\Classes\Slider;
use CmsmastersElementor\Modules\Social\Traits\Social_Widget;
use CmsmastersElementor\Utils;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Elementor instagram widget.
 *
 * Elementor widget lets you easily embed and promote any public
 * instagram on your website.
 *
 * @since 1.0.0
 */
class Instagram extends Base_Widget {

	use Social_Widget;

	const CACHE_PREFIX = 'cmsmasters_instagram_';
	const CACHE_EXPIRE_USER = HOUR_IN_SECONDS * 4;
	const CACHE_EXPIRE_HASHTAG = HOUR_IN_SECONDS * 24;

	protected $slider;

	/**
	 * Get group name.
	 *
	 * @since 1.6.5
	 *
	 * @return string Group name.
	 */
	public function get_group_name() {
		return $this->get_name();
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve instagram widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Instagram', 'cmsmasters-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve instagram widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'cmsicon-instagram';
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
			'instagram',
			'gallery',
			'photos',
			'images',
			'embed',
			'feed',
		);
	}

	/**
	 * Get script dependencies.
	 *
	 * Retrieve the list of script dependencies the widget requires.
	 *
	 * @since 1.0.0
	 *
	 * @return array Widget script dependencies.
	 */
	public function get_script_depends() {
		return array_merge( array(
			'perfect-scrollbar-js',
			'imagesloaded',
		), parent::get_script_depends() );
	}

	/**
	 *
	 * Initializing the Addon `instagram` widget class.
	 *
	 * @since 1.0.0
	 *
	 * @throws \Exception If arguments are missing when initializing a
	 * full widget instance.
	 *
	 * @param array $data Widget data.
	 * @param array|null $args Widget default arguments.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		$this->slider = new Slider( $this );
	}

	/**
	 * Register instagram widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @return void Widget controls.
	 */
	protected function register_controls() {
		if ( ! static::is_flag_account_controls() ) {
			$this->register_account_controls();
		}

		$this->register_controls_layout();
		$this->register_controls_items_style();
		$this->register_controls_query();
		$this->register_controls_caption_style();
		$this->register_controls_data_style();
		$this->register_controls_likes_comments_style();
		$this->register_controls_header_style();
		$this->register_controls_load_more_button_style();
		$this->register_controls_feed_title_style();
		$this->register_controls_lightbox_style_style();
		$this->register_controls_lightbox_comments_style();

		$this->slider->register_section_content();
		$this->slider->register_sections_style();

		$this->update_control_slider();

		if ( static::is_flag_account_controls() ) {
			$this->register_account_controls();
		}
	}

	/**
	 * Register instagram widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 */
	private function register_controls_layout() {
		$conditions_business = $this->get_conditions( 'business' );

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'cmsmasters-elementor' ),
			)
		);

		$this->add_control(
			'metadata_heading',
			array(
				'label' => __( 'Metadata', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'display_caption',
			array(
				'label' => __( 'Display Caption', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Show', 'cmsmasters-elementor' ),
				'label_off' => __( 'Hide', 'cmsmasters-elementor' ),
			)
		);

		$this->add_control(
			'caption_length',
			array(
				'label' => __( 'Caption Length', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 100,
				'min' => 10,
				'max' => 200,
				'step' => 5,
				'condition' => array(
					'display_caption!' => '',
				),
			)
		);

		$this->add_control(
			'display_date',
			array(
				'label' => __( 'Display Date', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'cmsmasters-elementor' ),
				'label_off' => __( 'Hide', 'cmsmasters-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Format_Date::get_type(),
			array(
				'name' => 'post_date',
				'condition' => array(
					'display_date!' => '',
				),
			)
		);

		$this->add_control(
			'display_likes',
			array(
				'label' => __( 'Display Likes', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Show', 'cmsmasters-elementor' ),
				'label_off' => __( 'Hide', 'cmsmasters-elementor' ),
				'conditions' => $conditions_business,
			)
		);

		$this->add_control(
			'display_comments',
			array(
				'label' => __( 'Display Comments', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Show', 'cmsmasters-elementor' ),
				'label_off' => __( 'Hide', 'cmsmasters-elementor' ),
				'conditions' => $conditions_business,
			)
		);

		$this->add_control(
			'skin',
			array(
				'label' => __( 'Skin', 'cmsmasters-elementor' ),
				'label_block' => false,
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'cover' => array(
						'title' => __( 'Cover', 'cmsmasters-elementor' ),
					),
					'card' => array(
						'title' => __( 'Card', 'cmsmasters-elementor' ),
					),
				),
				'prefix_class' => 'elementor-widget-cmsmasters-instagram__skin-',
				'default' => 'cover',
				'separator' => 'before',
				'conditions' => array(
					'relation' => 'or',
					'terms' => array(
						array(
							'name' => 'display_caption',
							'operator' => '!=',
							'value' => '',
						),
						array(
							'name' => 'display_date',
							'operator' => '!=',
							'value' => '',
						),
						array(
							'name' => 'display_likes',
							'operator' => '!=',
							'value' => '',
						),
						array(
							'name' => 'display_comments',
							'operator' => '!=',
							'value' => '',
						),
					),
				),
			)
		);

		$this->add_control(
			'content_visibility',
			array(
				'label' => __( 'On Content Hover', 'cmsmasters-elementor' ),
				'label_block' => false,
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'default' => 'show_on_hover',
				'options' => array(
					'show_on_hover' => array(
						'title' => __( 'Show', 'cmsmasters-elementor' ),
						'description' => __( 'Show image content on hover.', 'cmsmasters-elementor' ),
					),
					'hide_on_hover' => array(
						'title' => __( 'Hide', 'cmsmasters-elementor' ),
						'description' => __( 'Hide image content on hover.', 'cmsmasters-elementor' ),
					),
				),
				'prefix_class' => 'elementor-widget-cmsmasters-instagram__content_visibility-',
				'conditions' => array(
					'relation' => 'and',
					'terms' => array(
						array(
							'relation' => 'or',
							'terms' => array(
								array(
									'name' => 'display_caption',
									'operator' => '!=',
									'value' => '',
								),
								array(
									'name' => 'display_date',
									'operator' => '!=',
									'value' => '',
								),
								array(
									'name' => 'display_likes',
									'operator' => '!=',
									'value' => '',
								),
								array(
									'name' => 'display_comments',
									'operator' => '!=',
									'value' => '',
								),
							),
						),
						array(
							'name' => 'skin',
							'operator' => '==',
							'value' => 'cover',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label' => __( 'Columns', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
					'9' => '9',
					'10' => '10',
				),
				'default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__items' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'image_count',
			array(
				'label' => __( 'Maximum amount of images', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 6,
				),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'column_gap',
			array(
				'label' => __( 'Columns Gap', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--gap-column: {{SIZE}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'row_gap',
			array(
				'label' => __( 'Rows Gap', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--gap-row: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'masonry',
			array(
				'label' => __( 'Masonry', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'elementor-widget-cmsmasters-instagram--masonry-',
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'thumbnail_ratio',
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
					'{{WRAPPER}}' => '--thumbnail-ratio: {{SIZE}}',
				),
				'condition' => array(
					'masonry' => '',
				),
			)
		);

		$this->add_control(
			'header',
			array(
				'label' => __( 'Header', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'cmsmasters-elementor' ),
				'label_on' => __( 'Show', 'cmsmasters-elementor' ),
				'conditions' => $conditions_business,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'load_more_button_heading',
			array(
				'label' => __( 'Load More', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'load_more_button',
			array(
				'label' => __( 'Enable?', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'load_more_text',
			array(
				'label' => __( 'Text', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Load More', 'cmsmasters-elementor' ),
				'condition' => array(
					'load_more_button!' => '',
				),
			)
		);

		$this->add_control(
			'load_more_loading_text',
			array(
				'label' => __( 'Loading Text', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Loading...', 'cmsmasters-elementor' ),
				'condition' => array(
					'load_more_button!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'load_more_alignment',
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
					'stretch' => array(
						'title' => __( 'Justify', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-justify',
					),
				),
				'default' => 'center',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__load-more-button-wrapper' => 'align-items: {{VALUE}};',
				),
				'condition' => array(
					'load_more_button!' => '',
				),
			)
		);

		$this->add_control(
			'load_more_number',
			array(
				'label' => __( 'Number of Posts Loaded', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 6,
				),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'condition' => array(
					'load_more_button!' => '',
				),
			)
		);

		$this->add_control(
			'feed_title_heading',
			array(
				'label' => __( 'Feed Title', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'header' => '',
					'load_more_button' => '',
				),
			)
		);

		$this->add_control(
			'feed_title_description',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'The option is available in case `Load More` and `Header` buttons are disabled.', 'cmsmasters-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition' => array(
					'load_more_button' => '',
					'header' => '',
				),
			)
		);

		$this->add_control(
			'profile_link',
			array(
				'label' => __( 'Link to Instagram Profile', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'cmsmasters-elementor' ),
				'label_off' => __( 'Hide', 'cmsmasters-elementor' ),
				'condition' => array(
					'load_more_button' => '',
					'header' => '',
				),
			)
		);

		$this->add_control(
			'profile_link_title',
			array(
				'label' => __( 'Link Title', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '@Instagram', 'cmsmasters-elementor' ),
				'conditions' => array(
					'relation' => 'and',
					'terms' => array(
						array(
							'name' => 'profile_link',
							'operator' => '!=',
							'value' => '',
						),
						array(
							'name' => 'load_more_button',
							'operator' => '==',
							'value' => '',
						),
						array(
							'name' => 'header',
							'operator' => '==',
							'value' => '',
						),
					),
				),
			)
		);

		$this->add_control(
			'profile_url',
			array(
				'label' => __( 'Profile URL', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => get_option( 'elementor_instagram_url' ),
				'default' => array(
					'nofollow' => 'yes',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms' => array(
						array(
							'name' => 'profile_link',
							'operator' => '!=',
							'value' => '',
						),
						array(
							'name' => 'load_more_button',
							'operator' => '==',
							'value' => '',
						),
						array(
							'name' => 'header',
							'operator' => '==',
							'value' => '',
						),
					),
				),
			)
		);

		$this->add_control(
			'image_link',
			array(
				'label' => __( 'On Click', 'cmsmasters-elementor' ),
				'label_block' => false,
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'description' => __( 'Action on image click.', 'cmsmasters-elementor' ),
				'options' => array(
					'disabled' => array(
						'title' => __( 'None', 'cmsmasters-elementor' ),
						'description' => __( 'Image is not clickable.', 'cmsmasters-elementor' ),
					),
					'link' => array(
						'title' => __( 'Open Post', 'cmsmasters-elementor' ),
						'description' => __( 'Open image post in a new tab.', 'cmsmasters-elementor' ),
					),
					'lightbox' => array(
						'title' => __( 'Lightbox', 'cmsmasters-elementor' ),
						'description' => __( 'Open image post in lightbox.', 'cmsmasters-elementor' ),
					),
				),
				'default' => 'disabled',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'link_target',
			array(
				'label' => __( 'Open in a New Tab?', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => array(
					'image_link' => 'link',
				),
			)
		);

		$this->add_control(
			'link_nofollow',
			array(
				'label' => __( 'Add nofollow', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => array(
					'image_link' => 'link',
				),
			)
		);

		$this->slider->register_controls_content_per_view();

		$this->end_controls_section();
	}

	/**
	 * Register instagram widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 */
	private function register_controls_items_style() {
		$this->start_controls_section(
			'section_styles_items',
			array(
				'label' => __( 'Items', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'cover_heading',
			array(
				'label' => __( 'Cover', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'item_alignment',
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
				'default' => 'center',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__meta-inner' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'item_cover_padding',
			array(
				'label' => __( 'Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_cover' );

		$this->start_controls_tab(
			'cover_normal',
			array( 'label' => __( 'Normal', 'cmsmasters-elementor' ) )
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'label' => __( 'Filters', 'cmsmasters-elementor' ),
				'name' => 'cover_css_filters_normal',
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__item-inner img',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'cover_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__item-inner',
			)
		);

		$this->add_control(
			'cover_bg_normal',
			array(
				'label' => __( 'Background Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__inner' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cover_hover',
			array( 'label' => __( 'Hover', 'cmsmasters-elementor' ) )
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'label' => __( 'Filters', 'cmsmasters-elementor' ),
				'name' => 'cover_css_filters_hover',
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__item-inner:hover img',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'cover_box_shadow_hover',
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__item-inner:hover',
			)
		);

		$this->add_control(
			'cover_bg_hover',
			array(
				'label' => __( 'Background Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__item-inner:hover .elementor-widget-cmsmasters-instagram__inner' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'item_border_color',
			array(
				'label' => __( 'Border Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__item-inner' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'item_border_border!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name' => 'item_border',
				'label' => __( 'Border', 'cmsmasters-elementor' ),
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__item-inner',
				'exclude' => array( 'color' ),
			)
		);

		$this->add_control(
			'item_border_radius',
			array(
				'label' => __( 'Border Radius', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__item-inner' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				),
				'condition' => array(
					'item_border_border!' => '',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register instagram widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 */
	private function register_controls_query() {
		$conditions_business = $this->get_conditions( 'business' );

		$this->start_controls_section(
			'section_query',
			array(
				'label' => __( 'Query', 'cmsmasters-elementor' ),
			)
		);

		$this->add_control(
			'search_for',
			array(
				'label' => __( 'Search for', 'cmsmasters-elementor' ),
				'label_block' => false,
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'user' => array(
						'title' => __( 'User', 'cmsmasters-elementor' ),
					),
					'hashtag' => array(
						'title' => __( 'Hashtag', 'cmsmasters-elementor' ),
					),
				),
				'default' => 'user',
				'frontend_available' => true,
				'conditions' => $conditions_business,
			)
		);

		$this->add_control(
			'hashtag',
			array(
				'label' => __( 'Hashtag (enter without `#` symbol)', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'wordpress',
				'frontend_available' => true,
				'conditions' => array(
					'relation' => 'and',
					'terms' => array(
						array(
							'name' => 'search_for',
							'operator' => '==',
							'value' => 'hashtag',
						),
						$conditions_business,
					),
				),
			)
		);

		$this->add_control(
			'hashtag_order_of_posts',
			array(
				'label' => __( 'Order of Posts', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'top_media' => __( 'Popular', 'cmsmasters-elementor' ),
					'recent_media' => __( 'Recent', 'cmsmasters-elementor' ),
				),
				'default' => 'top_media',
				'label_block' => true,
				'frontend_available' => true,
				'conditions' => array(
					'relation' => 'and',
					'terms' => array(
						array(
							'name' => 'search_for',
							'operator' => '==',
							'value' => 'hashtag',
						),
						$conditions_business,
					),
				),
			)
		);

		$this->add_control(
			'hashtag_order_of_posts_recent_media_refresh_notice',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'Only returns posts published within 24 hours.', 'cmsmasters-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition' => array(
					'hashtag_order_of_posts' => 'recent_media',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms' => array(
						array(
							'name' => 'search_for',
							'operator' => '==',
							'value' => 'hashtag',
						),
						$conditions_business,
					),
				),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label' => __( 'Order By', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'date' => array(
						'title' => __( 'Date', 'cmsmasters-elementor' ),
					),
					'likes' => array(
						'title' => __( 'Likes', 'cmsmasters-elementor' ),
					),
					'comments' => array(
						'title' => __( 'Comments', 'cmsmasters-elementor' ),
					),
				),
				'default' => 'date',
				'frontend_available' => true,
				'label_block' => true,
				'separator' => 'before',
				'conditions' => $conditions_business,
			)
		);

		$this->add_control(
			'order',
			array(
				'label' => __( 'Order', 'cmsmasters-elementor' ),
				'label_block' => false,
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'asc' => array(
						'title' => __( 'ASC', 'cmsmasters-elementor' ),
						'description' => __( 'Ascending', 'cmsmasters-elementor' ),
					),
					'desc' => array(
						'title' => __( 'DESC', 'cmsmasters-elementor' ),
						'description' => __( 'Descending', 'cmsmasters-elementor' ),
					),
				),
				'default' => 'asc',
				'frontend_available' => true,
				'separator' => 'after',
				'conditions' => $conditions_business,
			)
		);

		$this->add_control(
			'orderby_date',
			array(
				'label' => __( 'Order By Date', 'cmsmasters-elementor' ),
				'label_block' => 'false',
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'desc' => array(
						'title' => __( 'DESC', 'cmsmasters-elementor' ),
						'description' => __( 'Descending', 'cmsmasters-elementor' ),
					),
					'asc' => array(
						'title' => __( 'ASC', 'cmsmasters-elementor' ),
						'description' => __( 'Ascending', 'cmsmasters-elementor' ),
					),
				),
				'default' => 'asc',
				'frontend_available' => true,
				'separator' => 'after',
				'conditions' => $this->get_conditions( 'personal' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register instagram widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 */
	private function register_controls_caption_style() {
		$this->start_controls_section(
			'caption_style',
			array(
				'label' => __( 'Caption', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'display_caption!' => '',
				),
			)
		);

		$this->add_control(
			'caption_color',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#2D2D2D',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__caption' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'caption_typography',
				'label' => __( 'Typography', 'cmsmasters-elementor' ),
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__caption',
			)
		);

		$this->add_control(
			'caption_bottom_spacing',
			array(
				'label' => __( 'Bottom Spacing', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__caption' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'label' => __( 'Shadow', 'cmsmasters-elementor' ),
				'name' => 'caption_shadow',
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__caption',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register instagram widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 */
	private function register_controls_data_style() {
		$this->start_controls_section(
			'date_style',
			array(
				'label' => __( 'Date', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'display_date!' => '',
					'search_for!' => 'hashtag',
				),
			)
		);

		$this->add_control(
			'date_bottom_spacing',
			array(
				'label' => __( 'Bottom Spacing', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__date' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'date_icon',
			array(
				'label' => __( 'Date Icon', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::ICONS,
				'default' => array(
					'value' => 'fas fa-clock',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'date_gap_between_icon_number',
			array(
				'label' => __( 'Gap between Icon and Date', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array(
					'unit' => 'px',
					'size' => 2,
				),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .elementor-widget-cmsmasters-instagram__date span' => 'margin-right: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} .elementor-widget-cmsmasters-instagram__date span' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'feed_date' );

		$this->start_controls_tab(
			'date_icon_tag',
			array(
				'label' => __( 'Icon', 'cmsmasters-elementor' ),
			)
		);

		$this->add_control(
			'date_color',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#2D2D2D',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__date i' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'date_size',
			array(
				'label' => __( 'Size', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__date i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'date_background',
			array(
				'label' => __( 'Background Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__date i' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'date_border_color',
			array(
				'label' => __( 'Border Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__date i' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'date_border_border!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name' => 'date_border',
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__date i',
				'exclude' => array( 'color' ),
			)
		);

		$this->add_control(
			'date_border_radius',
			array(
				'label' => __( 'Border Radius', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range' => array(
					'%' => array(
						'max' => 50,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 0,
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__date i' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'date_padding',
			array(
				'label' => __( 'Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__date i' => 'padding: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'label' => __( 'Shadow', 'cmsmasters-elementor' ),
				'name' => 'date_shadow',
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__date i',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'date_number',
			array(
				'label' => __( 'Date', 'cmsmasters-elementor' ),
			)
		);

		$this->add_control(
			'date_number_color',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#2D2D2D',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__date span' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'date_number_type',
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__date span',
			)
		);

		$this->add_control(
			'date_number_background',
			array(
				'label' => __( 'Background Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__date span' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'date_number_border_color',
			array(
				'label' => __( 'Border Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__date span' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'date_number_border_border!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name' => 'date_number_border',
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__date span',
				'exclude' => array( 'color' ),
			)
		);

		$this->add_control(
			'date_number_border_radius',
			array(
				'label' => __( 'Border Radius', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range' => array(
					'%' => array(
						'max' => 50,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 0,
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__date span' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'date_number_padding',
			array(
				'label' => __( 'Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__date span' => 'padding: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name' => 'date_number_shadow',
				'label' => __( 'Shadow', 'cmsmasters-elementor' ),
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__date span',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register instagram widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 */
	private function register_controls_likes_comments_style() {
		$conditions_business = $this->get_conditions( 'business' );

		$this->start_controls_section(
			'likes_comments_style',
			array(
				'label' => __( 'Likes & Comments', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'and',
					'terms' => array(
						array(
							'relation' => 'or',
							'terms' => array(
								array(
									'name' => 'display_likes',
									'operator' => '!=',
									'value' => '',
								),
								array(
									'name' => 'display_comments',
									'operator' => '!=',
									'value' => '',
								),
							),
						),
						$conditions_business,
					),
				),
			)
		);

		$this->add_control(
			'likes_comments_gap_between',
			array(
				'label' => __( 'Gap Between', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__interface' => 'padding: 0 calc({{SIZE}}{{UNIT}} / 2) 0 calc({{SIZE}}{{UNIT}} / 2);',
				),
			)
		);

		$this->add_control(
			'likes_comments_gap_between_icon_number',
			array(
				'label' => __( 'Gap Between Icon & Number', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array(
					'unit' => 'px',
					'size' => 2,
				),
				'size_units' => array( 'px', 'em', '%' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
						'step' => 1,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .elementor-widget-cmsmasters-instagram__interface__num' => 'margin-left: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} .elementor-widget-cmsmasters-instagram__interface__num' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'feed_likes_comments' );

		$this->start_controls_tab(
			'likes_comments_icon',
			array(
				'label' => __( 'Icon', 'cmsmasters-elementor' ),
			)
		);

		$this->add_control(
			'likes_icon',
			array(
				'label' => __( 'Icon Likes', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::ICONS,
				'default' => array(
					'value' => 'fas fa-heart',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'comments_icon',
			array(
				'label' => __( 'Icon Likes', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::ICONS,
				'default' => array(
					'value' => 'fas fa-comment',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'likes_color',
			array(
				'label' => __( 'Likes Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#BD2C2C',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__likes i' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'comments_color',
			array(
				'label' => __( 'Comments Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#6EC1E4',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__comments i' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'likes_comments_size',
			array(
				'label' => __( 'Size', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__interface .cmsmasters-wrap-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'likes_background',
			array(
				'label' => __( 'Background Color Likes', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__likes i' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'comments_background',
			array(
				'label' => __( 'Background Color Comments', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__comments i' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'likes_comments_border_color',
			array(
				'label' => __( 'Border Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__interface .cmsmasters-wrap-icon' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'likes_comments_border_border!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name' => 'likes_comments_border',
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__interface .cmsmasters-wrap-icon',
				'exclude' => array( 'color' ),
			)
		);

		$this->add_control(
			'likes_comments_border_radius',
			array(
				'label' => __( 'Border Radius', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range' => array(
					'%' => array(
						'max' => 50,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 0,
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__interface .cmsmasters-wrap-icon' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'likes_comments_padding',
			array(
				'label' => __( 'Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__interface .cmsmasters-wrap-icon' => 'padding: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name' => 'feed_likes_comments_shadow',
				'label' => __( 'Shadow', 'cmsmasters-elementor' ),
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__interface .cmsmasters-wrap-icon',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'likes_comments_number',
			array(
				'label' => __( 'Number', 'cmsmasters-elementor' ),
			)
		);

		$this->add_control(
			'likes_number_color',
			array(
				'label' => __( 'Likes Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#2D2D2D',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__likes .elementor-widget-cmsmasters-instagram__interface__num' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'comments_number_color',
			array(
				'label' => __( 'Comments Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#2D2D2D',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__comments .elementor-widget-cmsmasters-instagram__interface__num' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'likes_comments_number_type',
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__interface__num',
			)
		);

		$this->add_control(
			'likes_number_background',
			array(
				'label' => __( 'Background Color Likes', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__likes .elementor-widget-cmsmasters-instagram__interface__num' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'comments_number_background',
			array(
				'label' => __( 'Background Color Comments', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__comments .elementor-widget-cmsmasters-instagram__interface__num' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'likes_comments_number_border_color',
			array(
				'label' => __( 'Border Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__interface__num' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'likes_comments_number_border_border!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name' => 'likes_comments_number_border',
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__interface__num',
				'exclude' => array( 'color' ),
			)
		);

		$this->add_control(
			'likes_comments_number_border_radius',
			array(
				'label' => __( 'Border Radius', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range' => array(
					'%' => array(
						'max' => 50,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 0,
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__interface__num' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'likes_comments_number_padding',
			array(
				'label' => __( 'Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__interface__num' => 'padding: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'label' => __( 'Shadow', 'cmsmasters-elementor' ),
				'name' => 'likes_comments_number_shadow',
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__interface__num',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register instagram widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 */
	private function register_controls_header_style() {
		$conditions_business = $this->get_conditions( 'business' );

		$this->start_controls_section(
			'header_style',
			array(
				'label' => __( 'Header', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'and',
					'terms' => array(
						array(
							'name' => 'header',
							'operator' => '!=',
							'value' => '',
						),
						$conditions_business,
					),
				),
			)
		);

		$this->add_control(
			'header_bottom_spacing',
			array(
				'label' => __( 'Bottom Spacing', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
						'step' => 5,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'header_username',
			array(
				'label' => __( 'Username', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'header_username_typography',
				'label' => __( 'Typography', 'cmsmasters-elementor' ),
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__header-username a',
			)
		);

		$this->start_controls_tabs( 'tabs_header_username' );

		$this->start_controls_tab(
			'header_username_normal',
			array( 'label' => __( 'Normal', 'cmsmasters-elementor' ) )
		);

		$this->add_control(
			'header_username_color_normal',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__header-username a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'header_username_hover',
			array( 'label' => __( 'Hover', 'cmsmasters-elementor' ) )
		);

		$this->add_control(
			'header_username_color_hover',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#676767',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__header-username a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'label' => __( 'Text Shadow', 'cmsmasters-elementor' ),
				'name' => 'header_username_shadow',
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__header-username a',
			)
		);

		$this->add_control(
			'header_username_bottom_spacing',
			array(
				'label' => __( 'Bottom Spacing', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 20,
				),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
						'step' => 5,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__header-username' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'header_counts',
			array(
				'label' => __( 'Counts', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'header_typography_counts',
				'label' => __( 'Typography', 'cmsmasters-elementor' ),
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__header-counts',
			)
		);

		$this->add_control(
			'header_color_counts',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595F',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__header-counts' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'label' => __( 'Text Shadow', 'cmsmasters-elementor' ),
				'name' => 'header_shadow_counts',
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__header-counts',
			)
		);

		$this->add_control(
			'header_counts_gap_between',
			array(
				'label' => __( 'Gap Between', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 40,
				),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
						'step' => 5,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__header-counts span' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'header_counts_bottom_spacing',
			array(
				'label' => __( 'Bottom Spacing', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
						'step' => 5,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__header-counts' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$items_header = array(
			'full-name' => __( 'Full Name', 'cmsmasters-elementor' ),
			'bio' => __( 'BIO', 'cmsmasters-elementor' ),
		);

		foreach ( $items_header as $key => $value ) {
			$this->add_control(
				"header_{$key}",
				array(
					'label' => $value,
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name' => "header_typography_{$key}",
					'label' => __( 'Typography', 'cmsmasters-elementor' ),
					'selector' => "{{WRAPPER}} .elementor-widget-cmsmasters-instagram__header-{$key}",
				)
			);

			$this->add_control(
				"header_color_{$key}",
				array(
					'label' => __( 'Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#54595F',
					'selectors' => array(
						"{{WRAPPER}} .elementor-widget-cmsmasters-instagram__header-{$key}" => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				array(
					'label' => __( 'Text Shadow', 'cmsmasters-elementor' ),
					'name' => "header_shadow_{$key}",
					'selector' => "{{WRAPPER}} .elementor-widget-cmsmasters-instagram__header-{$key}",
				)
			);

			$this->add_control(
				"header_bottom_spacing_{$key}",
				array(
					'label' => __( 'Bottom Spacing', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 100,
							'step' => 5,
						),
					),
					'size_units' => array( 'px', '%' ),
					'selectors' => array(
						"{{WRAPPER}} .elementor-widget-cmsmasters-instagram__header-{$key}" => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
				)
			);
		}

		$this->add_control(
			'header_website',
			array(
				'label' => __( 'Website', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'header_typography_website',
				'label' => __( 'Typography', 'cmsmasters-elementor' ),
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__header-website',
			)
		);

		$this->start_controls_tabs( 'tabs_header_website' );

		$this->start_controls_tab(
			'header_website_normal',
			array( 'label' => __( 'Normal', 'cmsmasters-elementor' ) )
		);

		$this->add_control(
			'header_website_color_normal',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__header-website' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'header_website_hover',
			array( 'label' => __( 'Hover', 'cmsmasters-elementor' ) )
		);

		$this->add_control(
			'header_website_color_hover',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#676767',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__header-website:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'label' => __( 'Text Shadow', 'cmsmasters-elementor' ),
				'name' => 'header_shadow_website',
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__header-website',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register instagram widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 */
	private function register_controls_load_more_button_style() {
		$loadmore_selector = '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__load-more-button';
		$states = array(
			'normal' => __( 'Normal', 'cmsmasters-elementor' ),
			'hover' => __( 'Hover', 'cmsmasters-elementor' ),
		);

		$this->start_controls_section(
			'section_load_more_button_style',
			array(
				'label' => __( 'Load More Button', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'load_more_button!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'loadmore_typography',
				'label' => __( 'Typography', 'cmsmasters-elementor' ),
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--cmsmasters-button-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--cmsmasters-button-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--cmsmasters-button-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--cmsmasters-button-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--cmsmasters-button-font-style: {{VALUE}};',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--cmsmasters-button-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--cmsmasters-button-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
				),
				'selector' => $loadmore_selector,
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		foreach ( $states as $state => $state_label ) {
			$loadmore_selector_state = $loadmore_selector;
			$css_var_old_state_prefix = '';
			$control_prefix_control = '';

			if ( 'hover' === $state ) {
				$css_var_old_state_prefix = '-hover';
				$loadmore_selector_state .= ':hover';
			}

			$loadmore_selector_bg = $loadmore_selector;

			if ( 'normal' === $state ) {
				$loadmore_selector_bg .= '::before';
			} else {
				$loadmore_selector_bg .= '::after';
			}

			if ( 'normal' !== $state ) {
				$control_prefix_control = "_{$state}";
			}

			$this->start_controls_tab(
				"loadmore_{$state}",
				array(
					'label' => $state_label,
				)
			);

			$this->add_group_control(
				Group_Control_Button_Background::get_type(),
				array(
					'name' => "loadmore_bg_{$state}",
					'selector' => $loadmore_selector_bg,
					'exclude' => array( 'color' ),
				)
			);

			$this->start_injection( array( 'of' => "loadmore_bg_{$state}_background" ) );

			$this->add_control(
				"loadmore_bg_color_{$state}",
				array(
					'label' => __( 'Background Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$loadmore_selector_bg => '--button-bg-color: {{VALUE}};' .
						'background-color: var( --button-bg-color );',
					),
				)
			);

			$this->end_injection();

			$this->add_control(
				"loadmore_text_color_{$state}",
				array(
					'label' => __( 'Text Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$loadmore_selector_state => "--cmsmasters-button-{$state}-colors-color: {{VALUE}};",
					),
				)
			);

			$this->add_control(
				"loadmore_border_color_{$state}",
				array(
					'label' => __( 'Border Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$loadmore_selector_state => "--cmsmasters-button-{$state}-colors-bd: {{VALUE}};",
					),
					'condition' => array(
						'loadmore_border_border!' => array( 'none' ),
					),
				)
			);

			$this->add_control(
				"loadmore_border_radius{$control_prefix_control}",
				array(
					'label' => __( 'Border Radius', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors' => array(
						$loadmore_selector_state => "--cmsmasters-button-{$state}-bd-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
					),
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name' => "loadmore_text_shadow{$control_prefix_control}",
					'selector' => $loadmore_selector_state,
					'fields_options' => array(
						'box_shadow' => array(
							'selectors' => array(
								'{{SELECTOR}}' => "--cmsmasters-button-{$state}-box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};",
							),
						),
					),
					'condition' => array(
						'pagination_show!' => '',
						'pagination_type' => 'load_more',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				array(
					'name' => "loadmore_box_shadow{$control_prefix_control}",
					'selector' => $loadmore_selector_state,
					'fields_options' => array(
						'text_shadow' => array(
							'selectors' => array(
								'{{SELECTOR}}' => '--cmsmasters-button-text-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{COLOR}};',
							),
						),
					),
					'condition' => array(
						'pagination_show!' => '',
						'pagination_type' => 'load_more',
					),
				)
			);

			$this->add_control(
				"button_text_decoration_{$state}",
				array(
					'label' => __( 'Text Decoration', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => '',
					'options' => array(
						'' => __( 'Default', 'cmsmasters-elementor' ),
						'none' => __( 'Disable', 'cmsmasters-elementor' ),
						'underline' => __( 'Underline', 'cmsmasters-elementor' ),
						'overline' => __( 'Overline', 'cmsmasters-elementor' ),
						'line-through' => __( 'Line Through', 'cmsmasters-elementor' ),
					),
					'selectors' => array(
						$loadmore_selector_state => "--cmsmasters-button{$css_var_old_state_prefix}-text-decoration: {{VALUE}};",
					),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name' => 'loadmore_border',
				'label' => __( 'Border', 'cmsmasters-elementor' ),
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__load-more-button',
				'fields_options' => array(
					'border' => array(
						'options' => array(
							'' => __( 'Default', 'cmsmasters-elementor' ),
							'none' => __( 'Disable', 'cmsmasters-elementor' ),
							'solid' => __( 'Solid', 'cmsmasters-elementor' ),
							'double' => __( 'Double', 'cmsmasters-elementor' ),
							'dotted' => __( 'Dotted', 'cmsmasters-elementor' ),
							'dashed' => __( 'Dashed', 'cmsmasters-elementor' ),
							'groove' => __( 'Groove', 'cmsmasters-elementor' ),
						),
						'separator' => 'before',
					),
					'width' => array(
						'condition' => array(
							'border!' => array( '', 'none' ),
						),
					),
				),
				'exclude' => array( 'color' ),
			)
		);

		$this->add_responsive_control(
			'loadmore_padding',
			array(
				'label' => __( 'Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors' => array(
					$loadmore_selector => '--cmsmasters-button-padding-top: {{TOP}}{{UNIT}}; --cmsmasters-button-padding-right: {{RIGHT}}{{UNIT}}; --cmsmasters-button-padding-bottom: {{BOTTOM}}{{UNIT}}; --cmsmasters-button-padding-left: {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'load_more_top_spacing',
			array(
				'label' => __( 'Top Spacing', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array( 'size' => 20 ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
						'step' => 5,
					),
				),
				'size_units' => array( 'px' ),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__load-more-button-wrapper' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'load_more_button!' => '',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register instagram widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 */
	private function register_controls_feed_title_style() {
		$this->start_controls_section(
			'section_feed_title_style',
			array(
				'label' => __( 'Feed Title', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'header' => '',
					'load_more_button' => '',
					'profile_link!' => '',
				),
			)
		);

		$this->start_controls_tabs(
			'title_style',
			array(
				'separator' => 'before',
			)
		);

		$this->start_controls_tab(
			'feed_title_normal',
			array(
				'label' => __( 'Normal', 'cmsmasters-elementor' ),
				'condition' => array(
					'profile_link!' => '',
				),
			)
		);

		$this->add_control(
			'feed_title_color_normal',
			array(
				'label' => __( 'Text Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__feed-title-wrap a' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'profile_link!' => '',
				),
			)
		);

		$this->add_control(
			'feed_title_bg_color_normal',
			array(
				'label' => __( 'Background Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__feed-title-wrap' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'profile_link!' => '',
				),
			)
		);

		$this->add_control(
			'feed_title_border_color_normal',
			array(
				'label' => __( 'Border Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__feed-title-wrap' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'feed_title_border_normal_border!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name' => 'feed_title_border_normal',
				'label' => __( 'Border', 'cmsmasters-elementor' ),
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__feed-title-wrap',
				'exclude' => array( 'color' ),
			)
		);

		$this->add_control(
			'feed_title_border_radius_normal',
			array(
				'label' => __( 'Border Radius', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__feed-title-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'feed_title_hover',
			array(
				'label' => __( 'Hover', 'cmsmasters-elementor' ),
				'condition' => array(
					'profile_link!' => '',
				),
			)
		);

		$this->add_control(
			'feed_title_color_hover',
			array(
				'label' => __( 'Text Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__feed-title-wrap a:hover' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'profile_link!' => '',
				),
			)
		);

		$this->add_control(
			'feed_title_bg_color_hover',
			array(
				'label' => __( 'Background Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__feed-title-wrap:hover' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'profile_link!' => '',
				),
			)
		);

		$this->add_control(
			'feed_title_border_color_hover',
			array(
				'label' => __( 'Border Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__feed-title-wrap:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'feed_title_border_hover_border!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name' => 'feed_title_border_hover',
				'label' => __( 'Border', 'cmsmasters-elementor' ),
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__feed-title-wrap:hover',
				'exclude' => array( 'color' ),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label' => __( 'Typography', 'cmsmasters-elementor' ),
				'name' => 'feed_title_typography',
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__feed-title',
				'condition' => array(
					'profile_link!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'label' => __( 'Text Shadow', 'cmsmasters-elementor' ),
				'name' => 'feed_title_text_shadow',
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__feed-title',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'feed_title_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-instagram__feed-title-wrap',
			)
		);

		$this->add_control(
			'feed_title_padding',
			array(
				'label' => __( 'Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-instagram__feed-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'profile_link!' => '',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register instagram widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 */
	private function register_controls_lightbox_style_style() {
		$conditions_business = $this->get_conditions( 'business' );

		$conditions_side = array(
			'relation' => 'and',
			'terms' => array(
				$conditions_business,
				array(
					'name' => 'image_link',
					'operator' => '==',
					'value' => 'lightbox',
				),
				array(
					'name' => 'lightbox_side_style',
					'operator' => '!=',
					'value' => '',
				),
			),
		);

		$this->start_controls_section(
			'section_lightbox_style',
			array(
				'label' => __( 'Lightbox', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'image_link' => 'lightbox',
				),
			)
		);

		$this->add_control(
			'lightbox_media_headings',
			array(
				'label' => __( 'Media', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'lightbox_media_width',
			array(
				'label' => __( 'Media Width', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 400,
						'max' => 1100,
						'step' => 5,
					),
				),
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}}' => '--cmsmasters-width-media: {{SIZE}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms' => array(
						array(
							'name' => 'image_link',
							'operator' => '==',
							'value' => 'lightbox',
						),
					),
				),
			)
		);

		$this->add_control(
			'lightbox_side_style',
			array(
				'label' => __( 'Side Style', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'separator' => 'before',
				'condition' => array(
					'image_link' => 'lightbox',
				),
				'conditions' => $conditions_business,
			)
		);

		$this->add_control(
			'lightbox_sidebar_width',
			array(
				'label' => __( 'Sidebar Width', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 335,
				),
				'range' => array(
					'px' => array(
						'min' => 100,
						'max' => 500,
						'step' => 10,
					),
					'%' => array(
						'min' => 5,
						'max' => 50,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}}' => '--cmsmasters-width-sidebar: {{SIZE}}{{UNIT}};',
				),
				'conditions' => $conditions_side,
			)
		);

		$this->add_responsive_control(
			'lightbox_sidebar_padding',
			array(
				'label' => __( 'Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox__item__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'conditions' => $conditions_side,
			)
		);

		$this->add_control(
			'lightbox_sidebar_bdrs',
			array(
				'label' => __( 'Border Radius', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox__item__inner' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				),
				'conditions' => $conditions_side,
			)
		);

		$this->add_control(
			'lightbox_username',
			array(
				'label' => __( 'Username', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => array(
					'relation' => 'and',
					'terms' => array(
						$conditions_side,
						array(
							'name' => 'search_for',
							'operator' => '==',
							'value' => 'user',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'lightbox_profile_picture_size',
			array(
				'label' => __( 'Profile Picture Size', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 35,
				),
				'range' => array(
					'px' => array(
						'min' => 30,
						'max' => 75,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-profile-picture' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms' => array(
						array(
							'relation' => 'and',
							'terms' => array(
								$conditions_side,
								array(
									'name' => 'search_for',
									'operator' => '==',
									'value' => 'user',
								),
								array(
									'name' => 'account_type',
									'operator' => '==',
									'value' => 'business',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms' => array(
								array(
									'name' => 'lightbox_side_style',
									'operator' => '!=',
									'value' => '',
								),
								array(
									'name' => 'search_for',
									'operator' => '==',
									'value' => 'user',
								),
								array(
									'name' => 'account_type_hidden',
									'operator' => '==',
									'value' => 'business',
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'lightbox_profile_picture_spacing',
			array(
				'label' => __( 'Profile Picture Space', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 5,
				),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors' => array(
					'body:not(.rtl) #cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-profile-picture' => 'margin-right: {{SIZE}}{{UNIT}};',
					'body.rtl #cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-profile-picture' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms' => array(
						array(
							'relation' => 'and',
							'terms' => array(
								$conditions_side,
								array(
									'name' => 'search_for',
									'operator' => '==',
									'value' => 'user',
								),
								array(
									'name' => 'account_type',
									'operator' => '==',
									'value' => 'business',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms' => array(
								$conditions_side,
								array(
									'name' => 'search_for',
									'operator' => '==',
									'value' => 'user',
								),
								array(
									'name' => 'account_type_hidden',
									'operator' => '==',
									'value' => 'business',
								),
							),
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'lightbox_username_typography',
				'label' => __( 'Typography', 'cmsmasters-elementor' ),
				'selector' => '#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-profile a',
				'conditions' => array(
					'relation' => 'and',
					'terms' => array(
						$conditions_side,
						array(
							'name' => 'search_for',
							'operator' => '==',
							'value' => 'user',
						),
					),
				),
			)
		);

		$this->start_controls_tabs(
			'tabs_lightbox_username',
			array(
				'conditions' => array(
					'relation' => 'and',
					'terms' => array(
						array(
							'name' => 'search_for',
							'operator' => '==',
							'value' => 'user',
						),
					),
				),
			)
		);

		$this->start_controls_tab(
			'lightbox_username_normal',
			array(
				'label' => __( 'Normal', 'cmsmasters-elementor' ),
				'conditions' => $conditions_side,
			)
		);

		$this->add_control(
			'lightbox_username_color_normal',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-profile a' => 'color: {{VALUE}};',
				),
				'conditions' => $conditions_side,
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'lightbox_username_hover',
			array(
				'label' => __( 'Hover', 'cmsmasters-elementor' ),
				'conditions' => $conditions_side,
			)
		);

		$this->add_control(
			'lightbox_username_color_hover',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#676767',
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-profile a:hover' => 'color: {{VALUE}};',
				),
				'conditions' => $conditions_side,
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'label' => __( 'Text Shadow', 'cmsmasters-elementor' ),
				'name' => 'lightbox_username_shadow',
				'selector' => '#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-profile a',
				'conditions' => array(
					'relation' => 'and',
					'terms' => array(
						$conditions_side,
						array(
							'name' => 'search_for',
							'operator' => '==',
							'value' => 'user',
						),
					),
				),
			)
		);

		$this->add_control(
			'lightbox_username_bottom_spacing',
			array(
				'label' => __( 'Bottom Spacing', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 15,
				),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-profile' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms' => array(
						$conditions_side,
						array(
							'name' => 'search_for',
							'operator' => '==',
							'value' => 'user',
						),
					),
				),
			)
		);

		$this->add_control(
			'lightbox_caption',
			array(
				'label' => __( 'Caption', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'conditions' => $conditions_side,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'lightbox_caption_typography',
				'label' => __( 'Typography', 'cmsmasters-elementor' ),
				'selector' => '#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-caption',
				'conditions' => $conditions_side,
			)
		);

		$this->add_control(
			'lightbox_caption_color',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595F',
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-caption' => 'color: {{VALUE}};',
				),
				'conditions' => $conditions_side,
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'label' => __( 'Text Shadow', 'cmsmasters-elementor' ),
				'name' => 'lightbox_caption_shadow',
				'selector' => '#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-caption',
				'conditions' => $conditions_side,
			)
		);

		$this->add_responsive_control(
			'lightbox_caption_text_alignment',
			array(
				'label' => __( 'Text Alignment', 'cmsmasters-elementor' ),
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
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-caption' => 'text-align: {{VALUE}}',
				),
				'conditions' => $conditions_side,
			)
		);

		$this->add_control(
			'lightbox_caption_bottom_spacing',
			array(
				'label' => __( 'Bottom Spacing', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 20,
				),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-caption' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'separator' => 'after',
				'conditions' => $conditions_side,
			)
		);

		$this->add_control(
			'lightbox_links',
			array(
				'label' => __( 'Links in Content', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'conditions' => $conditions_side,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'lightbox_links_typography',
				'label' => __( 'Typography', 'cmsmasters-elementor' ),
				'selector' => '#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox__item .content-link',
				'conditions' => $conditions_side,
			)
		);

		$this->start_controls_tabs( 'tabs_lightbox_links' );

		$this->start_controls_tab(
			'lightbox_links_normal',
			array(
				'label' => __( 'Normal', 'cmsmasters-elementor' ),
				'conditions' => $conditions_side,
			)
		);

		$this->add_control(
			'lightbox_links_color_normal',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox__item .content-link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'lightbox_links_hover',
			array(
				'label' => __( 'Hover', 'cmsmasters-elementor' ),
				'conditions' => $conditions_side,
			)
		);

		$this->add_control(
			'lightbox_links_color_hover',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#989898',
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox__item .content-link:hover' => 'color: {{VALUE}};',
				),
				'conditions' => $conditions_side,
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'label' => __( 'Text Shadow', 'cmsmasters-elementor' ),
				'name' => 'lightbox_links_shadow',
				'selector' => '#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox__item .content-link',
				'conditions' => $conditions_side,
			)
		);

		$this->add_control(
			'lightbox_permalink',
			array(
				'label' => __( 'Instagram Link', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => $conditions_side,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'lightbox_permalink_typography',
				'label' => __( 'Typography', 'cmsmasters-elementor' ),
				'selector' => '#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-permalink a',
				'conditions' => $conditions_side,
			)
		);

		$this->start_controls_tabs( 'tabs_lightbox_permalink' );

		$this->start_controls_tab(
			'lightbox_permalink_normal',
			array(
				'label' => __( 'Normal', 'cmsmasters-elementor' ),
				'conditions' => $conditions_side,
			)
		);

		$this->add_control(
			'lightbox_permalink_color_normal',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-permalink a' => 'color: {{VALUE}};',
				),
				'conditions' => $conditions_side,
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'lightbox_permalink_hover',
			array(
				'label' => __( 'Hover', 'cmsmasters-elementor' ),
				'conditions' => $conditions_side,
			)
		);

		$this->add_control(
			'lightbox_permalink_color_hover',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#676767',
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-permalink a:hover' => 'color: {{VALUE}};',
				),
				'conditions' => $conditions_side,
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'label' => __( 'Text Shadow', 'cmsmasters-elementor' ),
				'name' => 'lightbox_permalink_shadow',
				'selector' => '#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-permalink a',
				'conditions' => $conditions_side,
			)
		);

		$this->add_control(
			'lightbox_permalink_bottom_spacing',
			array(
				'label' => __( 'Bottom Spacing', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 20,
				),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-permalink' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'conditions' => $conditions_side,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register instagram widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 */
	private function register_controls_lightbox_comments_style() {
		$conditions_business = $this->get_conditions( 'business' );

		$this->start_controls_section(
			'section_lightbox_comments_style',
			array(
				'label' => __( 'Lightbox Comments', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'and',
					'terms' => array(
						array(
							'relation' => 'and',
							'terms' => array(
								array(
									'name' => 'lightbox_side_style',
									'operator' => '!=',
									'value' => '',
								),
								array(
									'name' => 'image_link',
									'operator' => '==',
									'value' => 'lightbox',
								),
							),
						),
						$conditions_business,
					),
				),
			)
		);

		$this->add_control(
			'lightbox_comment_background',
			array(
				'label' => __( 'Background Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox__item__inner' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'lightbox_comment_padding',
			array(
				'label' => __( 'Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox__item__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'lightbox_side_style!' => '',
				),
			)
		);

		$this->add_control(
			'lightbox_comment_bottom_spacing',
			array(
				'label' => __( 'Bottom Spacing', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-comment:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'lightbox_comment_child_side_spacing',
			array(
				'label' => __( 'Child Side Spacing', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 5,
						'max' => 30,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox__comments-box .cmsmasters-instagram-lightbox__comments-box' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'lightbox_comment_username',
			array(
				'label' => __( 'Username', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'lightbox_comment_username_typography',
				'label' => __( 'Typography', 'cmsmasters-elementor' ),
				'selector' => '#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-commenter',
			)
		);

		$this->start_controls_tabs( 'tabs_lightbox_comment_username' );

		$this->start_controls_tab(
			'lightbox_comment_username_normal',
			array( 'label' => __( 'Normal', 'cmsmasters-elementor' ) )
		);

		$this->add_control(
			'lightbox_comment_username_color_normal',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595F',
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-commenter' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'lightbox_comment_username_hover',
			array( 'label' => __( 'Hover', 'cmsmasters-elementor' ) )
		);

		$this->add_control(
			'lightbox_comment_username_color_hover',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-commenter:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'label' => __( 'Text Shadow', 'cmsmasters-elementor' ),
				'name' => 'lightbox_comment_username_shadow',
				'selector' => '#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-commenter',
			)
		);

		$this->add_control(
			'lightbox_comment_text',
			array(
				'label' => __( 'Text', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'lightbox_comment_text_typography',
				'label' => __( 'Typography', 'cmsmasters-elementor' ),
				'selector' => '#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-comment-text',
			)
		);

		$this->add_control(
			'lightbox_comment_text_color',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				'selectors' => array(
					'#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-comment-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'label' => __( 'Text Shadow', 'cmsmasters-elementor' ),
				'name' => 'lightbox_comment_text_shadow',
				'selector' => '#cmsmasters-instagram-{{ID}} .cmsmasters-instagram-lightbox-comment-text',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Update instagram widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @since 1.11.5 Fixed issues for Elementor 3.18.0.
	 */
	public function update_control_slider() {
		$conditions_business = $this->get_conditions( 'business' );

		$this->update_responsive_control( 'slider_per_view', array(
			'type' => Controls_Manager::HIDDEN,
			'default' => '1',
		) );

		$this->update_control( 'slider_to_scroll', array(
			'type' => Controls_Manager::HIDDEN,
			'default' => '1',
		) );

		$this->update_control( 'slider_direction', array(
			'type' => Controls_Manager::HIDDEN,
			'default' => 'horizontal',
		) );

		$this->update_control( 'slider_type', array(
			'type' => Controls_Manager::HIDDEN,
			'default' => 'carousel',
		) );

		$this->update_control( 'slider_height_type', array(
			'type' => Controls_Manager::HIDDEN,
			'default' => 'auto',
		) );

		$this->update_control(
			$this->slider->get_control_prefix( 'slider_effect' ),
			array(
				'type' => Controls_Manager::HIDDEN,
				'default' => 'slide',
				'conditions' => $conditions_business,
			),
			array(
				'recursive' => true,
			)
		);

		$this->update_control(
			$this->slider->get_control_prefix( 'slider_autoplay' ),
			array(
				'type' => Controls_Manager::HIDDEN,
				'default' => '',
				'conditions' => $conditions_business,
			),
			array(
				'recursive' => true,
			)
		);

		$this->update_control(
			$this->slider->get_control_prefix( 'slider_free_mode' ),
			array(
				'type' => Controls_Manager::HIDDEN,
				'conditions' => $conditions_business,
			),
			array(
				'recursive' => true,
			)
		);

		$this->update_control(
			$this->slider->get_control_prefix( 'slider_infinite' ),
			array(
				'type' => Controls_Manager::HIDDEN,
				'default' => '',
				'conditions' => $conditions_business,
			),
			array(
				'recursive' => true,
			)
		);

		$this->update_control(
			$this->slider->get_control_prefix( 'slider_slide_index' ),
			array(
				'type' => Controls_Manager::HIDDEN,
				'default' => '1',
				'conditions' => $conditions_business,
			),
			array(
				'recursive' => true,
			)
		);

		$this->update_control(
			$this->slider->get_control_prefix( 'slider_arrows' ),
			array(
				'default' => 'yes',
				'conditions' => $conditions_business,
			),
			array(
				'recursive' => true,
			)
		);

		$this->update_control(
			$this->slider->get_control_prefix( 'slider_navigation' ),
			array(
				'default' => 'bullets',
				'conditions' => $conditions_business,
			),
			array(
				'recursive' => true,
			)
		);

		$this->update_responsive_control(
			$this->slider->get_control_prefix( 'slider_space_between' ),
			array(
				'type' => Controls_Manager::HIDDEN,
				'default' => '',
				'selectors' => array(),
			)
		);

		$this->update_responsive_control(
			$this->slider->get_control_prefix( 'slider_arrows_bdrs' ),
			array(
				'default' => array(
					'size' => 50,
					'unit' => '%',
				),
				'conditions' => $conditions_business,
			),
			array(
				'recursive' => true,
			)
		);

		$sections_on_lightbox = array(
			'section_slider_options',
			'section_slider_style_bullets',
			'section_slider_style_scrollbar',
			'section_slider_style_arrows',
			'section_slider_layout_style',
			'section_slider_fraction',
			'section_slider_progressbar',
		);

		foreach ( $sections_on_lightbox as $section_name ) {
			$section_args = $this->get_controls( $section_name );

			$this->update_section(
				$section_name,
				array(
					'label' => sprintf(
						/* translators: Lightbox section name with slider controls. %s: Section name */
						esc_html__( 'Lightbox %s', 'cmsmasters-elementor' ),
						( isset( $section_args['label'] ) ? $section_args['label'] : '' )
					),
					'condition' => array(
						'image_link' => 'lightbox',
					),
				),
				array(
					'recursive' => true,
				)
			);
		}

		$this->update_control(
			'section_slider_layout_style',
			array(
				'type' => Controls_Manager::HIDDEN,
			),
			array(
				'recursive' => true,
			)
		);
	}

	/**
	 * Check if access token exists.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_flag_account_controls() {
		return InstagramModule::get_access_token() && InstagramModule::get_user_id();
	}

	/**
	 * Account controls section.
	 *
	 * @since 1.0.0
	 */
	public function register_account_controls() {
		$this->start_controls_section(
			'section_account',
			array(
				'label' => __( 'Account', 'cmsmasters-elementor' ),
			)
		);

		if ( ! static::is_flag_account_controls() ) {
			$this->add_control(
				'access_token_notice',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
					'raw' => sprintf(
						/* translators: %s: Link to settings */
						esc_html__( 'Please enter the access token and id in the %s, then you do not have to enter it every time in widget settings.', 'cmsmasters-elementor' ),
						static::get_render_settings_link()
					),
				)
			);
		}

		$this->add_control(
			'button_clear_cache',
			array(
				'label' => __( 'Delete Cache', 'cmsmasters-elementor' ),
				'text' => __( 'Delete Cache', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::BUTTON,
				'event' => 'cmsmasters:instagram:remove_cache',
			)
		);

		$this->add_control(
			'custom_connection',
			array(
				'label' => __( 'Custom Connection', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => sprintf(
					/* translators: %s: Link to settings */
					__( 'When "Custom Connection" is disabled settings from admin panel are used. %s.', 'cmsmasters-elementor' ),
					static::get_render_settings_link()
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'account_type',
			array(
				'label' => __( 'Instagram Profile Type', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'personal' => array(
						'title' => __( 'Personal', 'cmsmasters-elementor' ),
						'description' => __( 'For displaying user feeds', 'cmsmasters-elementor' ),
					),
					'business' => array(
						'title' => __( 'Business', 'cmsmasters-elementor' ),
						'description' => __( 'Required for hashtag feeds', 'cmsmasters-elementor' ),
					),
				),
				'default' => InstagramModule::get_account_type(),
				'label_block' => true,
				'frontend_available' => true,
				'condition' => array(
					'custom_connection!' => '',
				),
			)
		);

		$this->add_control(
			'account_type_hidden',
			array(
				'label' => __( 'Account Type', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => InstagramModule::get_account_type(),
			)
		);

		$this->add_control(
			'access_token',
			array(
				'label' => __( 'Access Token', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => InstagramModule::get_access_token(),
				'label_block' => true,
				'frontend_available' => true,
				'condition' => array(
					'custom_connection!' => '',
				),
			)
		);

		$this->add_control(
			'user_id',
			array(
				'label' => __( 'User Id', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => InstagramModule::get_user_id(),
				'frontend_available' => true,
				'condition' => array(
					'custom_connection!' => '',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get html link to settings page.
	 *
	 * @since 1.0.0
	 * @since 1.0.3 Added anchor link for instagram settings.
	 *
	 * @return string
	 */
	public static function get_render_settings_link() {
		return '<a href="' . admin_url( 'admin.php?page=' . Settings_Page::PAGE_ID ) . '#tab-general" target="_blank">' .
			esc_html__( 'Addon Settings', 'cmsmasters-elementor' ) .
		'</a>';
	}

	/**
	 * Render instagram widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @return void Widget render result.
	 */
	protected function render() {
		if ( ! $this->check_response() ) {
			/* translators: Instagram widgets invalid access token warning. %s: Link to integrations settings */
			Utils::render_alert( sprintf( esc_html__( 'Please enter a valid access token and user ID either in %s (once for the whole website) or in widget settings (for a specific widget only).', 'cmsmasters-elementor' ), static::get_render_settings_link() ) );

			return;
		}

		$data_instagram = array();

		$this->add_render_attribute( 'wrapper', 'class', 'elementor-widget-cmsmasters-instagram__wrapper' );

		if ( 'lightbox' === $this->get_settings_for_display( 'image_link' ) ) {
			$user_data = $this->get_user_data();

			if ( $user_data ) {
				$data_instagram['profile_picture_url'] = $user_data['profile_picture_url'];
				$data_instagram['username'] = $user_data['username'];
			}

			$this->add_render_attribute( 'wrapper', array(
				'data-user-data' => wp_json_encode( $data_instagram ),
			) );
		}

		echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) . '>';

		$this->render_feed_header();

		echo '<div class="elementor-widget-cmsmasters-instagram__outer">' .
		'<div class="elementor-widget-cmsmasters-instagram__items">';

		$this->render_feed_items();

		echo '</div>';

		$this->render_feed_title();

		echo '</div>';

		$this->render_load_more_button();

		echo '</div>';

		$this->render_lightbox_slider_template();
	}

	/**
	 * Check response.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Response.
	 */
	public function check_response() {
		$parameters = $this->get_api_parameters();

		if ( ! $parameters ) {
			return false;
		}

		return (bool) $this->get_instagram_data();
	}

	/**
	 * Retrieve data for instagram request set by the customer.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_api_parameters() {
		$settings = $this->get_settings();
		$parameters = array(
			'account_type' => '',
			'access_token' => '',
			'user_id' => '',
		);

		if ( $settings['custom_connection'] ) {
			$parameters['account_type'] = $settings['account_type'];
			$parameters['access_token'] = $settings['access_token'];
			$parameters['user_id'] = $settings['user_id'];
		}

		if ( empty( $parameters['account_type'] ) ) {
			$parameters['account_type'] = InstagramModule::get_account_type();
		}

		if ( empty( $parameters['access_token'] ) ) {
			$parameters['access_token'] = InstagramModule::get_access_token();
		}

		if ( empty( $parameters['user_id'] ) ) {
			$parameters['user_id'] = InstagramModule::get_user_id();
		}

		foreach ( $parameters as $parameter ) {
			if ( ! $parameter ) {
				return array();
			}
		}

		return $parameters;
	}

	/**
	 * Connect to the account and receive a response.
	 *
	 * @since 1.0.0
	 *
	 * @return array Response.
	 */
	public function get_instagram_data() {
		if ( $this->is_account_type_business() ) {
			$instagram_data = $this->get_business();
		} else {
			$instagram_data = $this->get_personal();
		}

		if ( $instagram_data && is_array( $instagram_data ) ) {
			$instagram_data = $this->get_sort_data( $instagram_data );
		}

		return $instagram_data;
	}

	/**
	 * Connect to the business account.
	 *
	 * Connect to the business account and receive a response.
	 *
	 * @since 1.0.0
	 *
	 * @return array Response API.
	 */
	public function get_business() {
		$settings = $this->get_settings_for_display();
		$parameters = $this->get_api_parameters();

		if ( 'user' === $settings['search_for'] ) {
			$graph_url = "https://graph.facebook.com/{$parameters['user_id']}/media?fields=media_url,thumbnail_url,caption,id,media_type,timestamp,username,comments_count,like_count,permalink,children{media_url,id,media_type,timestamp,permalink,thumbnail_url}&limit=20";

			return $this->get_remote_content( $graph_url );
		} else {
			if ( empty( $settings['hashtag'] ) ) {
				print esc_html__( 'Please, enter #hashtag.', 'cmsmasters-elementor' );

				return array();
			}

			$graph_hashtag_url = "https://graph.facebook.com/ig_hashtag_search?user_id={$parameters['user_id']}&q={$settings['hashtag']}";
			$hashtag_response = $this->get_remote_content( $graph_hashtag_url );

			if ( ! $hashtag_response || ! isset( $hashtag_response['data'][0]['id'] ) ) {
				return array();
			}

			$hashtag_id = $hashtag_response['data'][0]['id'];
			$graph_url = "https://graph.facebook.com/{$hashtag_id}/{$settings['hashtag_order_of_posts']}?user_id={$parameters['user_id']}&fields=media_url,caption,id,media_type,comments_count,like_count,permalink,children{media_url,id,media_type,permalink}&limit=100";

			return $this->get_remote_content( $graph_url );
		}
	}

	/**
	 * Connect to the personal account.
	 *
	 * Connect to the personal account and receive a response.
	 *
	 * @since 1.0.0
	 * @since 1.9.2 Fixed api url
	 *
	 * @return array Response API.
	 */
	public function get_personal() {
		$parameters = $this->get_api_parameters();

		if ( ! $parameters ) {
			return;
		}

		$graph_url = "https://graph.instagram.com/{$parameters['user_id']}/media?fields=media_url,thumbnail_url,caption,id,media_type,timestamp,username,permalink,children{media_url,id,media_type,timestamp,permalink,thumbnail_url}&limit=100";

		return $this->get_remote_content( $graph_url );
	}

	/**
	 * Check if this is a business account.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	protected function is_account_type_business() {
		$parameters = $this->get_api_parameters();

		return $parameters && 'business' === $parameters['account_type'];
	}

	/**
	 * Connect to the account data.
	 *
	 * @since 1.0.0
	 *
	 * @return array Response API.
	 */
	public function get_user_data() {
		if ( ! $this->is_account_type_business() ) {
			return array();
		}

		$parameters = $this->get_api_parameters();

		if ( ! $parameters ) {
			return array();
		}

		$user_data = $this->get_remote_content( "https://graph.facebook.com/{$parameters['user_id']}?fields=biography,followers_count,follows_count,media_count,name,profile_picture_url,username,website" );

		return $user_data;
	}

	/**
	 * Render items instagram on ajax.
	 *
	 * @since 1.0.0
	 *
	 * @return array.
	 */
	public function render_ajax( $page ) {
		$items_data = $this->get_items_data( $page );

		if ( ! $items_data ) {
			wp_send_json_error( array( 'message' => 'Failed to get data.' ), 404 );
		}

		ob_start();

		$this->render_feed_items( $page );

		$html = ob_get_clean();

		return array(
			'max_num_pages' => $items_data['max_num_pages'],
			'page' => $items_data['page'],
			'html' => $html,
		);
	}

	/**
	 * Get current instagram page.
	 *
	 * @since 1.0.0
	 *
	 * @return array.
	 */
	protected function get_items_data( $current_page = 1 ) {
		$instagram_data = $this->get_instagram_data();

		if ( ! $instagram_data ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		$is_ajax = AjaxWidgetModule::is_active_ajax();
		$found_posts = count( $instagram_data['data'] );

		if ( $is_ajax ) {
			$load_more_number = min( $settings['load_more_number']['size'], $found_posts );
			$fraction = round( $load_more_number / $settings['image_count']['size'], 2 );
			$post_count = (int) $load_more_number;
			$start_slice = $settings['image_count']['size'] + ( $current_page - 1 ) * $settings['image_count']['size'];
			$new_page = round( $current_page + $fraction, 2 );
		} else {
			$post_count = (int) $settings['image_count']['size'];
			$start_slice = 0;
			$new_page = $current_page;
		}

		$items = array_slice( $instagram_data['data'], $start_slice, $post_count );
		$max_num_pages = $found_posts / $settings['image_count']['size'];

		return array(
			'items' => $items,
			'max_num_pages' => $max_num_pages,
			'page' => $new_page,
		);
	}

	/**
	 * Render items instagram.
	 *
	 * Used to generate the items HTML.
	 *
	 * @since 1.0.0
	 *
	 * @return array.
	 */
	public function render_feed_items( $current_page = 1 ) {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['image_count']['size'] ) ) {
			return;
		}

		$items_data = $this->get_items_data( $current_page );

		foreach ( $items_data['items'] as $item ) {
			echo '<div class="elementor-widget-cmsmasters-instagram__item">' .
			'<div class="elementor-widget-cmsmasters-instagram__item-inner">';

			$this->render_post_trigger( $item, function() use ( $item, $settings ) {
				$has_likes = $settings['display_likes'] && isset( $item['like_count'] );
				$has_comments = $settings['display_comments'] && isset( $item['comments_count'] );

				echo '<div class="elementor-widget-cmsmasters-instagram__image">' .
					'<img src="' . $this->get_media_url( $item ) . '" alt="' . esc_attr__( 'Instagram Post', 'cmsmasters-elementor' ) . '">' .
				'</div>';

				$this->render_type_icon( $item );

				if (
					(
						$settings['display_caption'] &&
						! empty( $item['caption'] )
					) ||
					$settings['display_date'] ||
					$has_likes ||
					$has_comments
				) {
					echo '<div class="elementor-widget-cmsmasters-instagram__inner">' .
					'<div class="elementor-widget-cmsmasters-instagram__meta">' .
					'<div class="elementor-widget-cmsmasters-instagram__meta-inner">';

					if ( $settings['display_caption'] && ! empty( $item['caption'] ) ) {
						echo '<p class="elementor-widget-cmsmasters-instagram__caption">' .
							wp_trim_words( $item['caption'], $settings['caption_length'], '...' ) .
						'</p>';
					}

					if ( $settings['display_date'] ) {
						$this->render_date( $item['timestamp'] );
					}

					if ( $has_likes || $has_comments ) {
						echo '<div class="elementor-widget-cmsmasters-instagram__interfaces">';
					}

					if ( $has_likes ) {
						$this->render_likes( $item['like_count'] );
					}

					if ( $has_comments ) {
						$this->render_comments( $item['comments_count'] );
					}

					if ( $has_likes || $has_comments ) {
						echo '</div>';
					}

					echo '</div>' .
					'</div>' .
					'</div>';
				}
			} );

			echo '</div>' .
			'</div>';
		}
	}

	/**
	 * Get Lightbox Slider Template.
	 *
	 * generate lightbox slider tmpl.
	 *
	 * @since 1.0.0
	 *
	 * @return string Lightbox slider tmpl.
	 */
	public function render_lightbox_slider_template() {
		$settings = $this->get_settings_for_display();

		if ( 'lightbox' !== $settings['image_link'] ) {
			return;
		}

		echo '<script type="text/html" id="tmpl-' . esc_attr( $this->get_name() ) . '-' . esc_attr( $this->get_id() ) . '">';

		$this->slider->render( function() {
			echo esc_html( '{{SLIDES}}' );
		} );

		echo '</script>';
	}

	/**
	 * Get load_more.
	 *
	 * Used to generate the load_more HTML.
	 *
	 * @since 1.0.0
	 *
	 * @return string load_more HTML.
	 */
	public function render_load_more_button() {
		$settings = $this->get_settings_for_display();

		if ( ! $settings['load_more_button'] ) {
			return;
		}

		$items_data = $this->get_items_data();

		if ( $items_data['page'] >= $items_data['max_num_pages'] ) {
			return;
		}

		echo '<div class="elementor-widget-cmsmasters-instagram__load-more-button-wrapper">' .
			'<button type="button" class="elementor-widget-cmsmasters-instagram__load-more-button" data-page="1">' .
				'<span class="elementor-widget-cmsmasters-instagram__load-more-button--normal">' . esc_html( $this->get_settings_fallback( 'load_more_text' ) ) . '</span>' .
				'<span class="elementor-widget-cmsmasters-instagram__load-more-button--loading">' . esc_html( $this->get_settings_fallback( 'load_more_loading_text' ) ) . '</span>' .
			'</button>' .
		'</div>';
	}

	/**
	 * Get feed title.
	 *
	 * Used to generate the feed title HTML.
	 *
	 * @since 1.0.0
	 *
	 * @return string Feed title HTML.
	 */
	public function render_feed_title() {
		$settings = $this->get_settings_for_display();

		if (
			$settings['profile_link'] &&
			$settings['profile_link_title'] &&
			! $settings['load_more_button'] &&
			! $settings['header']
		) {

			if ( empty( $settings['profile_url']['url'] ) ) {
				$settings['profile_url']['url'] = get_option( 'elementor_instagram_url' );
			}

			if ( ! empty( $settings['profile_url']['url'] ) ) {
				$this->add_link_attributes( 'profile-link', $settings['profile_url'] );

				echo '<span class="elementor-widget-cmsmasters-instagram__feed-title-wrap">
					<a ' . $this->get_render_attribute_string( 'profile-link' ) . '>
						<span class="elementor-widget-cmsmasters-instagram__feed-title">
							' . esc_attr( $settings['profile_link_title'] ) . '
						</span>
					</a>
				</span>';
			}
		}
	}

	/**
	 * Get feed header.
	 *
	 * Used to generate the feed header HTML.
	 *
	 * @since 1.0.0
	 *
	 * @return string Feed header HTML.
	 */
	public function render_feed_header() {
		if ( ! $this->is_account_type_business() ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		if ( ! $settings['header'] ) {
			return;
		}

		$user_data = $this->get_user_data();

		if ( ! $user_data ) {
			return;
		}

		$url = '//www.instagram.com/' . $user_data['username'];

		?>
		<div class="elementor-widget-cmsmasters-instagram__header">
			<div class="elementor-widget-cmsmasters-instagram__header-image">
				<a href="<?php echo esc_url( $url ); ?>" target="_blank">
					<img src="<?php echo esc_url( $user_data['profile_picture_url'] ); ?>" alt="<?php echo esc_attr( $user_data['username'] ); ?>">
				</a>
			</div>
			<div class="elementor-widget-cmsmasters-instagram__header-content">
				<h3 class="elementor-widget-cmsmasters-instagram__header-username">
					<a href="<?php echo esc_url( $url ); ?>" target="_blank">
						<?php echo esc_html( $user_data['username'] ); ?>
					</a>
				</h3>
				<div class="elementor-widget-cmsmasters-instagram__header-counts">
					<span>
						<?php
						printf(
							/* translators: Post count. */
							_n(
								'%s Post',
								'%s Posts',
								$user_data['media_count'],
								'cmsmasters-elementor'
							),
							number_format_i18n( $user_data['media_count'] )
						);
						?>
					</span>
					<span>
						<?php
						printf(
							/* translators: Follower count. */
							_n(
								'%s Follower',
								'%s Followers',
								$user_data['followers_count'],
								'cmsmasters-elementor'
							),
							number_format_i18n( $user_data['followers_count'] )
						);
						?>
					</span>
					<span>
						<?php
						printf(
							/* translators: Following count. */
							_n(
								'%s Following',
								'%s Followings',
								$user_data['follows_count'],
								'cmsmasters-elementor'
							),
							number_format_i18n( $user_data['follows_count'] )
						);
						?>
					</span>
				</div>
				<?php if ( isset( $user_data['name'] ) ) : ?>
					<h4 class="elementor-widget-cmsmasters-instagram__header-full-name">
						<?php echo esc_html( $user_data['name'] ); ?>
					</h4>
				<?php endif ?>
				<p class="elementor-widget-cmsmasters-instagram__header-bio">
					<?php echo esc_html( $user_data['biography'] ); ?>
				</p>
				<a href="<?php echo esc_url( $user_data['website'] ); ?>" class="elementor-widget-cmsmasters-instagram__header-website" target="_blank">
					<?php echo esc_html( $user_data['website'] ); ?>
				</a>
			</div>
		</div>
		<?php
	}

	/**
	 * Connect to the API.
	 *
	 * Connect to the API and record the response.
	 *
	 * @since 1.0.0
	 *
	 * @return array Response API.
	 */
	public function get_remote_content( $url ) {
		$parameters = $this->get_api_parameters();
		$url_parameters = array(
			'access_token' => $parameters['access_token'],
		);
		$url = add_query_arg( $url_parameters, $url );
		$transient = self::CACHE_PREFIX . md5( $url );
		$data = get_transient( $transient );

		if ( ! $data ) {
			$response = wp_remote_get(
				$url,
				array(
					'timeout' => 120,
					'sslverify' => false,
				)
			);

			if (
				is_wp_error( $response ) ||
				200 !== wp_remote_retrieve_response_code( $response )
			) {
				return false;
			}

			$data = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( $data ) {
				set_transient( $transient, $data, $this->get_cache_expire() );
			}
		}

		return $data;
	}

	/**
	 * Get cache lifetime.
	 *
	 * @since 1.0.0
	 *
	 * @return int In seconds.
	 */
	public function get_cache_expire() {
		$settings = $this->get_settings_for_display();

		if ( 'hashtag' === $settings['search_for'] ) {
			return self::CACHE_EXPIRE_HASHTAG;
		}

		return self::CACHE_EXPIRE_USER;
	}

	/**
	 * Get redirection.
	 *
	 * Get a link to a post or lightbox.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 *
	 * @return string Redirect HTML.
	 */
	public function render_post_trigger( $post, $callback ) {
		$settings = $this->get_settings_for_display();
		$tag = 'disabled' !== $settings['image_link'] ? 'a' : 'div';
		$attribute_stack_name = "link_{$post['id']}";

		if ( 'link' === $settings['image_link'] ) {
			$this->add_render_attribute( $attribute_stack_name, 'href', $this->get_permalink( $post ) );

			if ( $settings['link_target'] ) {
				$this->add_render_attribute( $attribute_stack_name, 'target', '_blank' );
			} else {
				$this->add_render_attribute( $attribute_stack_name, 'target', '_self' );
			}

			if ( $settings['link_nofollow'] ) {
				$this->add_render_attribute( $attribute_stack_name, 'rel', 'nofollow' );
			}
		} elseif ( 'lightbox' === $settings['image_link'] ) {
			$post_data = $this->retrieve_data_post( $post );

			$this->add_render_attribute( $attribute_stack_name, 'href', $this->get_permalink( $post ) );
			$this->add_render_attribute( $attribute_stack_name, 'class', 'cmsmasters-instagram-lightbox-trigger' );
			$this->add_render_attribute( $attribute_stack_name, 'data-id', $post['id'] );
			$this->add_render_attribute( $attribute_stack_name, 'target', '_blank' );
			$this->add_render_attribute( $attribute_stack_name, 'data-post', wp_json_encode( $post_data, JSON_UNESCAPED_UNICODE ) );
		}

		$this->add_render_attribute( $attribute_stack_name, 'class', 'elementor-widget-cmsmasters-instagram__link' );

		echo '<' . tag_escape( $tag ) . ' ' . $this->get_render_attribute_string( $attribute_stack_name ) . '>';

		if ( is_callable( $callback ) ) {
			call_user_func( $callback );
		}

		echo '</' . tag_escape( $tag ) . '>';
	}

	/**
	 * Gets post data with corrected fields.
	 *
	 * @param array $post
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 */
	protected function retrieve_data_post( $post ) {
		$data = array();

		if ( isset( $post['media_type'] ) ) {
			$data['media_type'] = $post['media_type'];
		}

		if ( 'VIDEO' === $post['media_type'] ) {
			$data['video'] = $post['media_url'];
		}

		if ( isset( $post['caption'] ) ) {
			$data['caption'] = html_entity_decode( $post['caption'] );
		}

		if ( isset( $post['children']['data'] ) ) {
			$data['children'] = array_map( function( $post_child ) {
				$data_child = $this->retrieve_data_post( $post_child );

				if (
					isset( $post_child['media_url'] ) &&
					empty( $data_child['video'] )
				) {
					$data_child['media_url'] = $post_child['media_url'];
				}

				return $data_child;
			}, $post['children']['data'] );
		}

		return $data;
	}

	/**
	 * Get media url.
	 *
	 * Get the media url for post type.
	 *
	 * @since 1.0.0
	 *
	 * @return string Media url.
	 */
	public function get_media_url( $post ) {
		if ( 'CAROUSEL_ALBUM' === $post['media_type'] || 'VIDEO' === $post['media_type'] ) {
			if ( isset( $post['thumbnail_url'] ) ) {
				return $post['thumbnail_url'];
			} elseif ( 'CAROUSEL_ALBUM' === $post['media_type'] && ( isset( $post['media_url'] ) || isset( $post['children']['data']['0']['media_url'] ) ) ) {
				if ( isset( $post['media_url'] ) ) {
					return $post['media_url'];
				} elseif ( isset( $post['children']['data']['0'] ) ) {
					$child = $post['children']['data']['0'];

					if ( 'IMAGE' === $child['media_type'] ) {
						return $child['media_url'];
					} elseif ( 'VIDEO' === $child['media_type'] ) {
						return $this->get_video_thumbnail_url( $child['permalink'] );
					}
				}
			} else {
				$permalink = $this->get_permalink( $post );
				$thumbnail_url = $this->get_video_thumbnail_url( $permalink );

				if ( ! $thumbnail_url ) {
					$thumbnail_url = "{$permalink}media?size=l";
				}

				return $thumbnail_url;
			}
		} else {
			return $post['media_url'];
		}
	}

	/**
	 * Get thumbnail url.
	 *
	 * @since 1.0.0
	 *
	 * @return string Post thumbnail_url.
	 */
	public function get_video_thumbnail_url( $permalink ) {
		$post_with_data = $this->get_remote_content( "https://graph.facebook.com/v9.0/instagram_oembed?url={$permalink}&fields=thumbnail_url" );

		if ( ! empty( $post_with_data['thumbnail_url'] ) ) {
			return $post_with_data['thumbnail_url'];
		}

		return false;
	}

	/**
	 * Get permalink.
	 *
	 * Get the permalink for post.
	 *
	 * @since 1.0.0
	 *
	 * @return string Post permalink.
	 */
	public function get_permalink( $post ) {
		if ( isset( $post['permalink'] ) ) {
			return $post['permalink'];
		}

		return $post['link'];
	}

	/**
	 * Type icon.
	 *
	 * Get post type icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string Post type icon HTML.
	 */
	public function render_type_icon( $item ) {
		if ( ! in_array( $item['media_type'], array( 'CAROUSEL_ALBUM', 'VIDEO' ), true ) ) {
			return;
		}

		if ( 'CAROUSEL_ALBUM' === $item['media_type'] ) {
			$icon = array(
				'value' => 'fas fa-clone',
				'library' => 'solid',
			);
		} elseif ( 'VIDEO' === $item['media_type'] ) {
			$icon = array(
				'value' => 'fas fa-video',
				'library' => 'solid',
			);
		}

		echo '<span class="elementor-widget-cmsmasters-instagram__type-icon">';

		Utils::render_icon( $icon );

		echo '</span>';
	}

	/**
	 * Sorts data.
	 *
	 * Sorts the data array in the order we need.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 *
	 * @return array Sorted data.
	 */
	public function get_sort_data( $instagram_data ) {
		$settings = $this->get_settings_for_display();

		if ( ! $this->is_account_type_business() ) {
			if ( 'desc' === $settings['orderby_date'] ) {
				krsort( $instagram_data['data'] );
			} else {
				ksort( $instagram_data['data'] );
			}
		} else {
			switch ( $settings['orderby'] ) {
				case 'date':
					if ( 'desc' === $settings['order'] ) {
						krsort( $instagram_data['data'] );
					} else {
						ksort( $instagram_data['data'] );
					}

					break;

				case 'likes':
					if ( 'desc' === $settings['order'] ) {
						usort( $instagram_data['data'], function( $a, $b ) {
							if ( $a['like_count'] < $b['like_count'] ) {
								return 1;
							} elseif ( $a['like_count'] === $b['like_count'] ) {
								if ( $a['id'] < $b['id'] ) {
									return 1;
								}

								return -1;
							}

							return -1;
						} );
					} else {
						usort( $instagram_data['data'], function( $a, $b ) {
							if ( $a['like_count'] > $b['like_count'] ) {
								return 1;
							} elseif ( $a['like_count'] === $b['like_count'] ) {
								if ( $a['id'] > $b['id'] ) {
									return 1;
								}

								return -1;
							}

							return -1;
						} );
					}

					break;

				case 'comments':
					if ( 'desc' === $settings['order'] ) {
						usort( $instagram_data['data'], function( $a, $b ) {
							if ( $a['comments_count'] < $b['comments_count'] ) {
								return 1;
							} elseif ( $a['comments_count'] === $b['comments_count'] ) {
								if ( $a['id'] < $b['id'] ) {
									return 1;
								}

								return -1;
							}

							return -1;
						} );
					} else {
						usort( $instagram_data['data'], function( $a, $b ) {
							if ( $a['comments_count'] > $b['comments_count'] ) {
								return 1;
							} elseif ( $a['comments_count'] === $b['comments_count'] ) {
								if ( $a['id'] > $b['id'] ) {
									return 1;
								}

								return -1;
							}

							return -1;
						} );
					}

					break;
			}
		}

		return $instagram_data;
	}

	/**
	 * Get likes.
	 *
	 * Get likes HTML.
	 *
	 * @since 1.0.0
	 *
	 * @return void Likes HTML.
	 */
	public function render_likes( $count ) {
		$this->render_count( 'likes', $count, $this->get_settings_for_display( 'likes_icon' ) );
	}

	/**
	 * Get comments.
	 *
	 * Get comments HTML.
	 *
	 * @since 1.0.0
	 *
	 * @return void Comments HTML.
	 */
	public function render_comments( $count ) {
		$this->render_count( 'comments', $count, $this->get_settings_for_display( 'comments_icon' ) );
	}

	/**
	 * Get comments or likes.
	 *
	 * Get comments or likes HTML.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 *
	 * @return void Comments or likes HTML.
	 */
	public function render_count( $name, $count, $icon ) {
		if ( empty( $icon['value'] ) ) {
			return;
		}

		?>
		<span class="elementor-widget-cmsmasters-instagram__interface elementor-widget-cmsmasters-instagram__<?php echo esc_attr( $name ); ?>">
			<?php Utils::render_icon( $icon, array( 'aria-hidden' => 'true' ) ); ?>
			<span class=" elementor-widget-cmsmasters-instagram__interface__num"><?php echo esc_html( $count ); ?></span>
		</span>
		<?php
	}

	/**
	 * Get date.
	 *
	 * Get date HTML.
	 *
	 * @since 1.0.0
	 *
	 * @return void Date HTML.
	 */
	public function render_date( $date ) {
		$icon = $this->get_settings_for_display( 'date_icon' );

		if ( empty( $icon['value'] ) ) {
			return;
		}

		?>
		<div class="elementor-widget-cmsmasters-instagram__date">
			<?php
			Utils::render_icon( $icon, array(
				'aria-hidden' => 'true',
			) );

			echo Group_Control_Format_Date::get_render_format( 'post_date', $this->get_settings(), strtotime( $date ) );
			?>
		</div>
		<?php
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
	 * @since 1.0.0
	 */
	public function get_style_depends() {
		if ( Icons_Manager::is_migration_allowed() ) {
			return array(
				'elementor-icons-fa-solid',
				'elementor-icons-fa-brands',
			);
		}

		return array();
	}

	/**
	 * Get conditions.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 *
	 * @return array Conditions.
	 */
	public function get_conditions( $type ) {
		$conditions = array(
			'relation' => 'or',
			'terms' => array(
				array(
					'relation' => 'and',
					'terms' => array(
						array(
							'name' => 'custom_connection',
							'operator' => '!=',
							'value' => '',
						),
						array(
							'name' => 'account_type',
							'operator' => '==',
							'value' => $type,
						),
					),
				),
				array(
					'relation' => 'and',
					'terms' => array(
						array(
							'name' => 'custom_connection',
							'operator' => '==',
							'value' => '',
						),
						array(
							'name' => 'account_type_hidden',
							'operator' => '==',
							'value' => $type,
						),
					),
				),
			),
		);

		return $conditions;
	}

	/**
	 * Get fields config for WPML.
	 *
	 * @since 1.3.3
	 *
	 * @return array Fields config.
	 */
	public static function get_wpml_fields() {
		return array(
			array(
				'field' => 'load_more_text',
				'type' => esc_html__( 'Load More Text', 'cmsmasters-elementor' ),
				'editor_type' => 'LINE',
			),
			array(
				'field' => 'load_more_loading_text',
				'type' => esc_html__( 'Load More Loading Text', 'cmsmasters-elementor' ),
				'editor_type' => 'LINE',
			),
			array(
				'field' => 'profile_link_title',
				'type' => esc_html__( 'Profile Link Title', 'cmsmasters-elementor' ),
				'editor_type' => 'LINE',
			),
			'profile_url' => array(
				'field' => 'url',
				'type' => esc_html__( 'Profile Link', 'cmsmasters-elementor' ),
				'editor_type' => 'LINK',
			),
			array(
				'field' => 'hashtag',
				'type' => esc_html__( 'Hashtag', 'cmsmasters-elementor' ),
				'editor_type' => 'LINE',
			),
			array(
				'field' => 'access_token',
				'type' => esc_html__( 'Access Token', 'cmsmasters-elementor' ),
				'editor_type' => 'LINE',
			),
			array(
				'field' => 'user_id',
				'type' => esc_html__( 'User ID', 'cmsmasters-elementor' ),
				'editor_type' => 'LINE',
			),
		);
	}

}
