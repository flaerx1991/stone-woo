<?php
namespace CmsmastersElementor\Modules\Blog\Widgets;

use CmsmastersElementor\Modules\Blog\Widgets\Base_Blog\Theme_Base_Blog_Customizable;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Addon blog grid widget.
 *
 * Addon widget that displays blog grid.
 *
 * @since 1.0.0
 */
class Theme_Blog_Grid extends Theme_Base_Blog_Customizable {

	/**
	 * @since 1.0.0
	 */
	public function get_title() {
		return __( 'Theme Blog Grid', 'cmsmasters-elementor' );
	}

	/**
	 * @since 1.0.0
	 */
	public function get_icon() {
		return 'cmsicon-posts-grid';
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
		return array_unique(
			array_merge(
				parent::get_unique_keywords(),
				array(
					'grig',
				)
			)
		);
	}

	/**
	 * @since 1.0.0
	 */
	public function register_controls() {
		$this->register_template_section_controls();

		parent::register_controls();

		$this->injection_section_content_layout();

		$this->injection_section_style_layout();
	}

	/**
	 * Register blog controls.
	 *
	 * Adds different input fields to allow the user to change and customize the classes settings.
	 *
	 * @since 1.0.0
	 */
	protected function register_template_section_controls() {
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Blog Grid Widget constructor.
	 *
	 * Initializing the widget blog grid class.
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
	 * Register blog grid controls.
	 *
	 * Adds different input fields to allow the user to change and customize the classes settings.
	 *
	 * @since 1.0.0
	 */
	protected function injection_section_content_layout() {
		$this->start_injection(
			array(
				'of' => 'alignment',
				'at' => 'before',
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label' => __( 'Posts', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'default' => 9,
				'condition' => array(
					'blog_post_type!' => 'current_query',
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label' => __( 'Columns', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'' => __( 'Default', 'cmsmasters-elementor' ),
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'default' => '',
				'selectors' => array(
					$this->get_blog_selector() => '--cmsmasters-theme-blog-columns: {{VALUE}}',
				),
				'frontend_available' => true,
			)
		);

		$this->end_injection();

		$this->start_injection(
			array(
				'of' => 'alignment',
				'at' => 'after',
			)
		);

		$this->add_control(
			'post_inner_position_v',
			array(
				'label' => __( 'Vertical Alignment', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => array(
					'top' => array(
						'title' => __( 'Top', 'cmsmasters-elementor' ),
						'icon' => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'cmsmasters-elementor' ),
						'icon' => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'cmsmasters-elementor' ),
						'icon' => 'eicon-v-align-bottom',
					),
				),
				'prefix_class' => 'cmsmasters-blog-grid-inner__align-v-',
			)
		);

		$this->end_injection();
	}

	/**
	 * Register blog grid controls.
	 *
	 * Adds different input fields to allow the user to change and customize the classes settings.
	 *
	 * @since 1.0.0
	 */
	protected function injection_section_style_layout() {
		$this->start_injection(
			array(
				'at' => 'before',
				'of' => 'section_style_post',
			)
		);

		$this->start_controls_section(
			'section_style_layout',
			array(
				'label' => __( 'Layout', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'post_gap_column',
			array(
				'label' => __( 'Columns Gap', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 150,
					),
					'%' => array(
						'min' => 0,
						'max' => 25,
						'step' => 0.5,
					),
				),
				'size_units' => array(
					'px',
					'%',
					'vw',
					'vh',
				),
				'frontend_available' => true,
				'selectors' => array(
					$this->get_blog_selector() => '--cmsmasters-theme-blog-gap-column: {{SIZE}}{{UNIT}};',
				),
				'condition' => array( 'columns!' => '1' ),
			)
		);

		$this->add_responsive_control(
			'post_gap_row',
			array(
				'label' => __( 'Rows Gap', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 150,
					),
				),
				'size_units' => array(
					'px',
					'vw',
					'vh',
				),
				'frontend_available' => true,
				'selectors' => array(
					$this->get_blog_selector() => '--cmsmasters-theme-blog-gap-row: {{SIZE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'layout_post_space',
			array(
				'label' => __( 'Posts Container Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__posts-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		$this->end_injection();
	}

	/**
	 * Get fields config for WPML.
	 *
	 * @since 1.0.0
	 *
	 * @return array Fields config.
	 */
	public static function get_wpml_fields() {
		return array(
			array(
				'field' => 'read_more_text',
				'type' => esc_html__( 'Read More Text', 'cmsmasters-elementor' ),
				'editor_type' => 'LINE',
			),
			array(
				'field' => 'filter_default_text',
				'type' => esc_html__( 'All Items Text', 'cmsmasters-elementor' ),
				'editor_type' => 'LINE',
			),
			array(
				'field' => 'nothing_found_message',
				'type' => esc_html__( 'Nothing Found Message', 'cmsmasters-elementor' ),
				'editor_type' => 'AREA',
			),
			array(
				'field' => 'pagination_load_more_text_normal',
				'type' => esc_html__( 'Load More Text (Normal state)', 'cmsmasters-elementor' ),
				'editor_type' => 'LINE',
			),
			array(
				'field' => 'pagination_load_more_text_loading',
				'type' => esc_html__( 'Load More Text (Loading state)', 'cmsmasters-elementor' ),
				'editor_type' => 'LINE',
			),
			array(
				'field' => 'pagination_infinite_scroll_text',
				'type' => esc_html__( 'Infinite Text', 'cmsmasters-elementor' ),
				'editor_type' => 'LINE',
			),
			array(
				'field' => 'pagination_text_prev',
				'type' => esc_html__( 'Pagination Previous Text', 'cmsmasters-elementor' ),
				'editor_type' => 'LINE',
			),
			array(
				'field' => 'pagination_text_next',
				'type' => esc_html__( 'Pagination Next Text', 'cmsmasters-elementor' ),
				'editor_type' => 'LINE',
			),
		);
	}

}
