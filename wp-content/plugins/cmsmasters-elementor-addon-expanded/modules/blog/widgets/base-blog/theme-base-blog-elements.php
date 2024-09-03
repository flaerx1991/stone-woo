<?php
namespace CmsmastersElementor\Modules\Blog\Widgets\Base_Blog;

use CmsmastersElementor\Controls_Manager as CmsmastersControls;
use CmsmastersElementor\Modules\AjaxWidget\Module as AjaxWidgetModule;
use CmsmastersElementor\Modules\Blog\Classes\Pagination;
use CmsmastersElementor\Modules\Blog\Module as BlogModule;
use CmsmastersElementor\Modules\Blog\Widgets\Base_Blog\Theme_Base_Blog;
use CmsmastersElementor\Plugin;
use CmsmastersElementor\Utils;
use CmsmastersElementor\Acf_Utils;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Utils as ElementorUtils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Addon blog widget class.
 *
 * An abstract class to register new Blog widgets.
 *
 * @since 1.0.0
 */
abstract class Theme_Base_Blog_Elements extends Theme_Base_Blog {

	const FILTER_URL_SEPARATOR = '|';

	/**
	 * Pagination instance.
	 *
	 * @since 1.0.0
	 *
	 * @var Pagination
	 */
	protected $pagination;

	/**
	 * Whether blog header needed.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	protected $has_header = true;

	/**
	 * Whether blog pagination needed.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	protected $has_pagination = true;

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
		return parent::get_unique_keywords() + array(
			'template',
			'custom',
		);
	}

	/**
	 * @since 1.0.0
	 */
	public function get_script_depends() {
		return array_merge( array(
			'perfect-scrollbar-js',
			'imagesloaded',
		), parent::get_script_depends() );
	}

	/**
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the widget requires.
	 *
	 * @since 1.0.0
	 *
	 * @return array Widget styles dependencies.
	 */
	public function get_style_depends() {
		if ( ! Icons_Manager::is_migration_allowed() ) {
			return array();
		}

		return array(
			'elementor-icons-fa-solid',
			'elementor-icons-fa-brands',
			'elementor-icons-fa-regular',
		);
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
		if ( $this->has_pagination ) {
			$this->pagination = new Pagination( $this, static::QUERY_CONTROL_PREFIX );
		}

		parent::__construct( $data, $args );
	}

	/**
	 * @since 1.0.0
	 */
	public function register_controls() {
		parent::register_controls();

		$this->register_header_section_controls();

		$this->register_style_section_controls();

		if ( $this->has_pagination ) {
			$this->pagination->register_controls_content();
		}

		$this->update_control(
			'section_pagination',
			array(
				'condition' => array(
					'blog_post_type' => 'post',
					'header_filter_show!' => 'multiple',
				),
			)
		);

		$this->update_control(
			'pagination_show',
			array(
				'prefix_class' => 'cmsmasters-pagination--with-button',
				'render_type' => 'template',
				'condition' => array(
					'blog_post_type' => 'post',
					'header_filter_show!' => 'multiple',
				),
			)
		);

		$this->update_control(
			'pagination_type',
			array(
				'type' => Controls_Manager::HIDDEN,
				'default' => 'pagination',
				'prefix_class' => 'cmsmasters-pagination--',
				'options' => array( 'pagination' => __( 'Pagination', 'cmsmasters-elementor' ) ),
				'condition' => array(
					'blog_post_type' => 'post',
					'header_filter_show!' => 'multiple',
					'pagination_show!' => '',
				),
			)
		);

		$this->update_control(
			'pagination_view_type',
			array(
				'type' => Controls_Manager::HIDDEN,
				'condition' => array(
					'blog_post_type' => 'post',
					'header_filter_show!' => 'multiple',
					'pagination_show!' => '',
				),
			)
		);

		$this->update_control(
			'pagination_item_page_range',
			array(
				'default' => 1,
				'condition' => array(
					'pagination_show!' => '',
					'pagination_type' => 'pagination',
					'pagination_view_type' => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->update_control(
			'pagination_via_ajax',
			array(
				'condition' => array(
					'pagination_show!' => '',
					'pagination_type' => 'pagination',
				),
			)
		);

		$this->update_control(
			'pagination_save_state',
			array(
				'condition' => array(
					'pagination_show!' => '',
					'pagination_via_ajax!' => '',
					'pagination_type' => 'pagination',
				),
			)
		);

		$this->update_control(
			'pagination_prev_next_icon_switcher',
			array(
				'default' => '',
				'condition' => array(
					'pagination_show!' => '',
					'pagination_type' => 'pagination',
					'pagination_view_type' => array( 'numbers_and_prev_next', 'prev_next' ),
				),
			)
		);

		$this->update_control(
			'pagination_prev_next_tabs',
			array(
				'condition' => array(
					'pagination_show!' => '',
					'pagination_type' => 'pagination',
					'pagination_view_type' => array( 'numbers_and_prev_next', 'prev_next' ),
				),
			)
		);

		$this->update_control(
			'pagination_text_prev',
			array(
				'default' => 'Previous',
				'condition' => array(
					'pagination_show!' => '',
					'pagination_type' => 'pagination',
					'pagination_view_type' => array( 'numbers_and_prev_next', 'prev_next' ),
				),
			)
		);

		$this->update_control(
			'pagination_icon_prev',
			array(
				'condition' => array(
					'pagination_show!' => '',
					'pagination_type' => 'pagination',
					'pagination_view_type' => array( 'numbers_and_prev_next', 'prev_next' ),
				),
			)
		);

		$this->update_control(
			'pagination_text_next',
			array(
				'default' => 'Next',
				'condition' => array(
					'pagination_show!' => '',
					'pagination_type' => 'pagination',
					'pagination_view_type' => array( 'numbers_and_prev_next', 'prev_next' ),
				),
			)
		);

		$this->update_control(
			'pagination_icon_next',
			array(
				'condition' => array(
					'pagination_show!' => '',
					'pagination_type' => 'pagination',
					'pagination_view_type' => array( 'numbers_and_prev_next', 'prev_next' ),
				),
			)
		);

		$hidden_controls = array(
			'pagination_scroll_into_view',
			'pagination_load_more_content_normal',
			'pagination_load_more_content',
			'pagination_load_more_text_normal',
			'pagination_load_more_icon_switcher_normal',
			'pagination_load_more_icon_normal',
			'pagination_load_more_icon_dir_normal',
			'pagination_load_more_content_loading',
			'pagination_load_more_text_loading',
			'pagination_load_more_icon_switcher_loading',
			'pagination_load_more_icon_loading',
			'pagination_load_more_icon_dir_loading',
			'pagination_load_more_icon_spin',
			'pagination_infinite_toggle_content',
			'pagination_infinite_scroll_text',
			'pagination_infinite_scroll_icon_switcher',
			'pagination_infinite_scroll_icon',
			'pagination_shadow_normal_box_shadow_type',
			'pagination_shadow_hover_box_shadow_type',
			'pagination_shadow_active_box_shadow_type',
			'pagination_arrows_shadow_normal_box_shadow_type',
			'pagination_arrows_shadow_hover_box_shadow_type',
			'pagination_text_shadow_normal_text_shadow_type',
			'pagination_text_shadow_hover_text_shadow_type',
			'pagination_text_shadow_active_text_shadow_type',
			'pagination_arrows_text_shadow_normal_text_shadow_type',
			'pagination_arrows_text_shadow_hover_text_shadow_type',
		);

		foreach ( $hidden_controls as $control ) {
			$this->update_control(
				$control,
				array( 'type' => Controls_Manager::HIDDEN )
			);
		}

		$this->update_control(
			'pagination_button_prefix_class',
			array(
				'type' => Controls_Manager::HIDDEN,
				'condition' => array(
					'blog_post_type' => 'post',
					'header_filter_show!' => 'multiple',
					'pagination_show!' => '',
					'pagination_type!' => 'pagination',
				),
			)
		);
	}

	/**
	 * Register blog controls.
	 *
	 * Adds different input fields to allow the user to change and customize the classes settings.
	 *
	 * @since 1.0.0
	 */
	protected function register_style_section_controls() {
		$this->register_controls_style_header_filter();

		if ( $this->has_pagination ) {
			$this->pagination->register_controls_style();
		}

		$this->update_control(
			'section_pagination_style',
			array(
				'condition' => array(
					'blog_post_type' => 'post',
					'header_filter_show!' => 'multiple',
					'pagination_show!' => '',
				),
			)
		);

		$this->update_control(
			'section_pagination_style_infinite_scroll',
			array(
				'condition' => array(
					'blog_post_type' => 'post',
					'header_filter_show!' => 'multiple',
					'pagination_show!' => '',
					'pagination_type' => 'infinite_scroll',
				),
			)
		);

		$this->update_control(
			'section_pagination_style_load_more',
			array(
				'condition' => array(
					'blog_post_type' => 'post',
					'header_filter_show!' => 'multiple',
					'pagination_show!' => '',
					'pagination_type' => 'load_more',
				),
			)
		);

		$this->update_control(
			'section_pagination_style_numbers',
			array(
				'condition' => array(
					'blog_post_type' => 'post',
					'header_filter_show!' => 'multiple',
					'pagination_show!' => '',
					'pagination_type' => 'pagination',
					'pagination_view_type' => array( 'numbers_and_prev_next', 'numbers' ),
				),
			)
		);

		$this->update_control(
			'section_pagination_style_arrows',
			array(
				'condition' => array(
					'blog_post_type' => 'post',
					'header_filter_show!' => 'multiple',
					'pagination_show!' => '',
					'pagination_type' => 'pagination',
					'pagination_view_type' => array( 'numbers_and_prev_next', 'prev_next' ),
				),
				'conditions' => array(
					'relation' => 'or',
					'terms' => array(
						array(
							'name' => 'pagination_prev_next_icon_switcher',
							'operator' => '!=',
							'value' => '',
						),
						array(
							'relation' => 'or',
							'terms' => array(
								array(
									'name' => 'pagination_text_prev',
									'operator' => '!=',
									'value' => '',
								),
								array(
									'name' => 'pagination_text_next',
									'operator' => '!=',
									'value' => '',
								),
							),
						),
					),
				),
			)
		);

		$this->update_control(
			'pagination_align',
			array(
				'type' => Controls_Manager::HIDDEN,
				'default' => 'center',
				'condition' => array(
					'blog_post_type' => 'post',
					'header_filter_show!' => 'multiple',
					'pagination_show!' => '',
					'pagination_type' => 'pagination',
				),
			)
		);

		$this->update_control(
			'pagination_fill',
			array(
				'type' => Controls_Manager::HIDDEN,
				'default' => 'no',
				'condition' => array(
					'blog_post_type' => 'post',
					'header_filter_show!' => 'multiple',
					'pagination_show!' => '',
					'pagination_type' => 'pagination',
				),
			)
		);
	}

	/**
	 * Register header section controls.
	 *
	 * Adds header section controls to widget settings.
	 *
	 * @since 1.0.0
	 */
	protected function register_header_section_controls() {
		$this->start_controls_section(
			'section_filter',
			array(
				'label' => __( 'Filter', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'header_filter_show',
			array(
				'label' => __( 'Visibility', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'none' => __( 'None', 'cmsmasters-elementor' ),
					'single' => __( 'Single', 'cmsmasters-elementor' ),
					'multiple' => __( 'Multiple', 'cmsmasters-elementor' ),
				),
				'label_block' => false,
				'toggle' => false,
				'default' => 'none',
				'render_type' => 'template',
				'frontend_available' => true,
			)
		);

		if ( ! $this->has_header ) {
			$this->end_controls_section();

			return;
		}

		$this->add_control(
			'header_filter_position',
			array(
				'label' => __( 'Filter Position', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'side' => __( 'Side', 'cmsmasters-elementor' ),
					'top' => __( 'Top', 'cmsmasters-elementor' ),
				),
				'label_block' => false,
				'toggle' => false,
				'default' => 'side',
				'render_type' => 'template',
				'prefix_class' => 'theme-header-filter-position-',
				'frontend_available' => true,
				'condition' => array(
					'blog_post_type' => 'product',
					'header_filter_show' => 'multiple',
				),
			)
		);

		$this->add_control(
			'header_post_filter_multiple_items',
			array(
				'label' => __( 'Filter Item', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::SELECTIZE,
				'options' => $this->get_post_taxonomies( 'post' ),
				'multiple' => true,
				'condition' => array(
					'header_filter_show' => 'multiple',
					'blog_post_type' => 'post',
				),
			)
		);

		$this->add_control(
			'header_projects_filter_multiple_items',
			array(
				'label' => __( 'Filter Item', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::SELECTIZE,
				'options' => $this->get_post_taxonomies( 'projects' ),
				'multiple' => true,
				'condition' => array(
					'header_filter_show' => 'multiple',
					'blog_post_type' => 'projects',
				),
			)
		);

		$this->add_control(
			'header_collections_filter_multiple_items',
			array(
				'label' => __( 'Filter Item', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::SELECTIZE,
				'options' => $this->get_post_taxonomies( 'collections' ),
				'multiple' => true,
				'condition' => array(
					'header_filter_show' => 'multiple',
					'blog_post_type' => 'collections',
				),
			)
		);

		$this->add_control(
			'header_product_filter_multiple_items',
			array(
				'label' => __( 'Filter Item', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::SELECTIZE,
				'options' => $this->get_post_taxonomies( 'product' ),
				'multiple' => true,
				'condition' => array(
					'header_filter_show' => 'multiple',
					'blog_post_type' => 'product',
				),
			)
		);

		$this->add_control(
			'header_filter_multiple_sorting',
			array(
				'label' => __( 'Sorting', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => array( 'header_filter_show' => 'multiple' ),
			)
		);

		$this->add_control(
			'header_filter_multiple_select_icon',
			array(
				'label' => esc_html__( 'Select Icon', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => false,
				'skin' => 'inline',
				'condition' => array( 'header_filter_show' => 'multiple' ),
			)
		);

		foreach ( $this->get_post_taxonomies( 'post' ) as $key => $value ) {
			$new_value = ucwords( str_replace( array( '_', '-' ), array( ' ', ' ' ), $key ) );

			$this->add_control(
				"header_filter_multiple_custom_{$key}_labels_heading",
				array(
					'label' => esc_html( $new_value, 'cmsmasters-elementor' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => array(
						'blog_post_type' => 'post',
						'header_filter_show' => 'multiple',
					),
				)
			);

			$this->add_control(
				"header_filter_multiple_{$key}_custom_label",
				array(
					'label' => __( 'New Label', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => esc_html( $new_value ),
					'label_block' => true,
					'ai' => array( 'active' => false ),
					'condition' => array(
						'blog_post_type' => 'post',
						'header_filter_show' => 'multiple',
					),
				)
			);

			$this->add_control(
				"header_filter_multiple_{$key}_custom_select",
				array(
					'label' => __( 'New Select Label', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => esc_html( 'All ' . $new_value ),
					'label_block' => true,
					'ai' => array( 'active' => false ),
					'condition' => array(
						'blog_post_type' => 'post',
						'header_filter_show' => 'multiple',
					),
				)
			);
		}

		$existing_controls = $this->get_controls();

		foreach ( $this->get_post_taxonomies( 'projects' ) as $key => $value ) {
			$new_value = ucwords( str_replace( array( '_', '-' ), array( ' ', ' ' ), $key ) );

			$heading_control_key = "header_filter_multiple_custom_{$key}_labels_heading";
			$text_control_key = "header_filter_multiple_{$key}_custom_label";
			$select_control_key = "header_filter_multiple_{$key}_custom_select";

			if ( ! array_key_exists( $text_control_key, $existing_controls ) ) {
				$this->add_control(
					"header_filter_multiple_custom_{$key}_labels_heading",
					array(
						'label' => esc_html( $new_value, 'cmsmasters-elementor' ),
						'type' => Controls_Manager::HEADING,
						'separator' => 'before',
						'condition' => array(
							'blog_post_type' => 'projects',
							'header_filter_show' => 'multiple',
						),
					)
				);
			}

			if ( ! array_key_exists( $text_control_key, $existing_controls ) ) {
				$this->add_control(
					"header_filter_multiple_{$key}_custom_label",
					array(
						'label' => __( 'New Label', 'cmsmasters-elementor' ),
						'type' => Controls_Manager::TEXT,
						'placeholder' => esc_html( $new_value ),
						'label_block' => true,
						'ai' => array( 'active' => false ),
						'condition' => array(
							'blog_post_type' => 'projects',
							'header_filter_show' => 'multiple',
						),
					)
				);
			}

			if ( ! array_key_exists( $text_control_key, $existing_controls ) ) {
				$this->add_control(
					"header_filter_multiple_{$key}_custom_select",
					array(
						'label' => __( 'New Select Label', 'cmsmasters-elementor' ),
						'type' => Controls_Manager::TEXT,
						'placeholder' => esc_html( 'All ' . $new_value ),
						'label_block' => true,
						'ai' => array( 'active' => false ),
						'condition' => array(
							'blog_post_type' => 'projects',
							'header_filter_show' => 'multiple',
						),
					)
				);
			}
		}

		$existing_controls = $this->get_controls();

		foreach ( $this->get_post_taxonomies( 'collections' ) as $key => $value ) {
			$new_value = ucwords( str_replace( array( '_', '-' ), array( ' ', ' ' ), $key ) );

			$heading_control_key = "header_filter_multiple_custom_{$key}_labels_heading";
			$text_control_key = "header_filter_multiple_{$key}_custom_label";
			$select_control_key = "header_filter_multiple_{$key}_custom_select";

			if ( ! array_key_exists( $heading_control_key, $existing_controls ) ) {
				$this->add_control(
					$heading_control_key,
					array(
						'label' => esc_html( $new_value, 'cmsmasters-elementor' ),
						'type' => Controls_Manager::HEADING,
						'separator' => 'before',
						'condition' => array(
							'blog_post_type' => 'collections',
							'header_filter_show' => 'multiple',
						),
					)
				);
			}

			if ( ! array_key_exists( $text_control_key, $existing_controls ) ) {
				$this->add_control(
					$text_control_key,
					array(
						'label' => __( 'New Label', 'cmsmasters-elementor' ),
						'type' => Controls_Manager::TEXT,
						'placeholder' => esc_html( $new_value ),
						'label_block' => true,
						'ai' => array( 'active' => false ),
						'condition' => array(
							'blog_post_type' => 'collections',
							'header_filter_show' => 'multiple',
						),
					)
				);
			}

			if ( ! array_key_exists( $select_control_key, $existing_controls ) ) {
				$this->add_control(
					$select_control_key,
					array(
						'label' => __( 'New Select Label', 'cmsmasters-elementor' ),
						'type' => Controls_Manager::TEXT,
						'placeholder' => esc_html( 'All ' . $new_value ),
						'label_block' => true,
						'ai' => array( 'active' => false ),
						'condition' => array(
							'blog_post_type' => 'collections',
							'header_filter_show' => 'multiple',
						),
					)
				);
			}
		}

		$existing_controls = $this->get_controls();

		foreach ( $this->get_post_taxonomies( 'product' ) as $key => $value ) {
			$new_value = ucwords( str_replace( array( '_', '-' ), array( ' ', ' ' ), $key ) );

			$heading_control_key = "header_filter_multiple_custom_{$key}_labels_heading";
			$text_control_key = "header_filter_multiple_{$key}_custom_label";
			$select_control_key = "header_filter_multiple_{$key}_custom_select";

			if ( ! array_key_exists( $heading_control_key, $existing_controls ) ) {
				$this->add_control(
					$heading_control_key,
					array(
						'label' => esc_html( $new_value, 'cmsmasters-elementor' ),
						'type' => Controls_Manager::HEADING,
						'separator' => 'before',
						'condition' => array(
							'blog_post_type' => 'product',
							'header_filter_show' => 'multiple',
						),
					)
				);
			}

			if ( ! array_key_exists( $text_control_key, $existing_controls ) ) {
				$this->add_control(
					$text_control_key,
					array(
						'label' => __( 'New Label', 'cmsmasters-elementor' ),
						'type' => Controls_Manager::TEXT,
						'placeholder' => esc_html( $new_value ),
						'label_block' => true,
						'ai' => array( 'active' => false ),
						'condition' => array(
							'blog_post_type' => 'product',
							'header_filter_show' => 'multiple',
						),
					)
				);
			}

			if ( ! array_key_exists( $select_control_key, $existing_controls ) ) {
				$this->add_control(
					$select_control_key,
					array(
						'label' => __( 'New Select Label', 'cmsmasters-elementor' ),
						'type' => Controls_Manager::TEXT,
						'placeholder' => esc_html( 'All ' . $new_value ),
						'label_block' => true,
						'ai' => array( 'active' => false ),
						'condition' => array(
							'blog_post_type' => 'product',
							'header_filter_show' => 'multiple',
						),
					)
				);
			}
		}

		$this->add_control(
			"header_filter_multiple_custom_sorting_labels_heading",
			array(
				'label' => __( 'Sorting', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'header_filter_show' => 'multiple',
					'header_filter_multiple_sorting' => 'yes',
				),
			)
		);

		$this->add_control(
			'header_filter_multiple_sorting_custom_label',
			array(
				'label' => __( 'New Label', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Sort by', 'cmsmasters-elementor' ),
				'label_block' => true,
				'ai' => array( 'active' => false ),
				'condition' => array(
					'header_filter_show' => 'multiple',
					'header_filter_multiple_sorting' => 'yes',
				),
			)
		);

		$this->add_control(
			'header_filter_via_ajax',
			array(
				'label' => __( 'Load via AJAX', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'condition' => array( 'header_filter_show' => 'single' ),
			)
		);

		$this->add_control(
			'header_filter_save_state',
			array(
				'label' => __( 'Save Filter', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => __( 'Set Yes to save filter in URL.', 'cmsmasters-elementor' ),
				'default' => 'yes',
				'frontend_available' => true,
				'render_type' => 'none',
				'condition' => array(
					'header_filter_show' => 'single',
					'header_filter_via_ajax!' => '',
				),
			)
		);

		$this->add_control(
			'filter_default_text',
			array(
				'label' => __( 'All Items Text', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'All Posts', 'cmsmasters-elementor' ),
				'condition' => array( 'header_filter_show' => 'single' ),
			)
		);

		$this->add_control(
			'filter_type',
			array(
				'label' => __( 'Type', 'cmsmasters-elementor' ),
				'label_block' => false,
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'query' => __( 'Widget Query', 'cmsmasters-elementor' ),
					'custom' => __( 'Custom', 'cmsmasters-elementor' ),
				),
				'default' => 'custom',
				'condition' => array( 'header_filter_show' => 'single' ),
			)
		);

		$control_terms_args = $this->get_controls( 'blog_include_term_ids' );

		unset( $control_terms_args['section'] );
		unset( $control_terms_args['tab'] );
		unset( $control_terms_args['name'] );

		$control_terms_args['label'] = esc_html__( 'Filter Items', 'cmsmasters-elementor' );
		$control_terms_args['condition'] = array(
			'header_filter_show' => 'single',
			'filter_type' => 'custom',
		);
		$control_terms_args['conditions'] = array();

		$this->add_control( 'filter_blog_include_term_ids', $control_terms_args );

		$this->add_control(
			'filter_item_elements',
			array(
				'label' => __( 'Filter Item Elements', 'cmsmasters-elementor' ),
				'label_block' => true,
				'description' => esc_html__( 'Select the elements that will be displayed in each filter item.', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::SELECTIZE,
				'multiple' => true,
				'options' => array(
					'name' => __( 'Name', 'cmsmasters-elementor' ),
					'image' => __( 'Image', 'cmsmasters-elementor' ),
					'description' => __( 'Description', 'cmsmasters-elementor' ),
					'count' => __( 'Count', 'cmsmasters-elementor' ),
				),
				'frontend_available' => true,
				'default' => array(
					'name',
					'count',
				),
				'condition' => array( 'header_filter_show' => 'single' ),
			)
		);

		if ( class_exists( '\acf' ) ) {
			$this->add_control(
				'filter_item_image',
				array(
					'label' => __( 'Filter Item Image', 'cmsmasters-elementor' ),
					'description' => __( 'Select the ACF image field for the taxonomy you are displaying in the filter.', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SELECT,
					'groups' => Acf_Utils::get_control_options( array( 'image' ) ),
					'condition' => array(
						'header_filter_show' => 'single',
						'filter_item_elements' => 'image',
					),
				)
			);
		}

		$this->add_control(
			'filter_all_items_image',
			array(
				'label' => __( 'All Items Image', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => array(
					'header_filter_show' => 'single',
					'filter_item_elements' => 'image',
				),
			)
		);

		$this->add_control(
			'filter_all_items_description',
			array(
				'label' => __( 'All Items Description', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::TEXT,
				'condition' => array(
					'header_filter_show' => 'single',
					'filter_item_elements' => 'description',
				),
			)
		);

		$this->add_control(
			'filter_multiple_rows_layout',
			array(
				'label' => __( 'Filter Layout', 'cmsmasters-elementor' ),
				'label_block' => false,
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'flex' => __( 'Flex', 'cmsmasters-elementor' ),
					'grid' => __( 'Grid', 'cmsmasters-elementor' ),
				),
				'default' => 'grid',
				'frontend_available' => true,
				'condition' => array( 'header_filter_show' => 'single' ),
			)
		);

		$this->add_responsive_control(
			'filter_multiple_rows_grid_columns',
			array(
				'label' => __( 'Filter Columns', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'default' => array(
					'size' => 5,
				),
				'selectors' => array(
					'{{WRAPPER}} .cmsmasters-blog' => Utils::prepare_css_var( 'filter_multiple_rows_grid_columns', '{{SIZE}}' ),
				),
				'condition' => array(
					'header_filter_show' => 'single',
					'filter_multiple_rows_layout' => 'grid',
				),
			)
		);

		$this->add_responsive_control(
			'filter_multiple_rows_alignment',
			array(
				'label' => __( 'Filter Alignment', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'start' => array(
						'title' => __( 'Start', 'cmsmasters-elementor' ),
						'icon' => 'eicon-h-align-' . ( is_rtl() ? 'right' : 'left' ),
					),
					'center' => array(
						'title' => __( 'Center', 'cmsmasters-elementor' ),
						'icon' => 'eicon-h-align-center',
					),
					'end' => array(
						'title' => __( 'End', 'cmsmasters-elementor' ),
						'icon' => 'eicon-h-align-' . ( ! is_rtl() ? 'right' : 'left' ),
					),
				),
				'toggle' => true,
				'selectors' => array(
					'{{WRAPPER}} .cmsmasters-blog' => Utils::prepare_css_var( 'filter_multiple_rows_alignment', '{{VALUE}}' ),
				),
				'condition' => array(
					'header_filter_show' => 'single',
					'filter_multiple_rows_layout' => 'flex',
				),
			)
		);

		$this->add_responsive_control(
			'filter_item_layout',
			array(
				'label' => __( 'Filter Item Layout', 'cmsmasters-elementor' ),
				'label_block' => false,
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'row' => array(
						'title' => esc_html__( 'Horizontal', 'cmsmasters-elementor' ),
						'icon' => 'eicon-ellipsis-h',
					),
					'column' => array(
						'title' => esc_html__( 'Vertical', 'cmsmasters-elementor' ),
						'icon' => 'eicon-ellipsis-v',
					),
				),
				'default' => 'row',
				'selectors' => array(
					'{{WRAPPER}} .cmsmasters-blog' => Utils::prepare_css_var( 'filter_item_layout', '{{VALUE}}' ),
				),
				'condition' => array( 'header_filter_show' => 'single' ),
			)
		);

		$this->add_responsive_control(
			'filter_item_alignment',
			array(
				'label' => __( 'Alignment', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'start' => array(
						'title' => __( 'Start', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-' . ( is_rtl() ? 'right' : 'left' ),
					),
					'center' => array(
						'title' => __( 'Center', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-center',
					),
					'end' => array(
						'title' => __( 'End', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-' . ( ! is_rtl() ? 'right' : 'left' ),
					),
				),
				'toggle' => true,
				'selectors' => array(
					'{{WRAPPER}} .cmsmasters-blog' => Utils::prepare_css_var( 'filter_item_alignment', '{{VALUE}}' ),
				),
				'condition' => array(
					'header_filter_show' => 'single',
					'filter_item_layout' => 'column',
				),
			)
		);

		$breakpoints = Utils::get_breakpoints();

		$minimized_on_options = array();

		foreach ( $breakpoints as $breakpoint_key => $breakpoint ) {
			if ( 'widescreen' === $breakpoint_key ) {
				continue;
			}

			$minimized_on_options['none'] = esc_html__( 'None', 'cmsmasters-elementor' );

			$minimized_on_options[ $breakpoint_key ] = sprintf(
				esc_html__( '%1$s (%2$s %3$dpx)', 'cmsmasters-elementor' ),
				ucfirst( $breakpoint_key ),
				'<',
				$breakpoint
			);
		}

		$this->add_control(
			'filter_minimized_on',
			array(
				'label' => esc_html__( 'Minimized On', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $minimized_on_options,
				'default' => 'mobile',
				'frontend_available' => true,
				'prefix_class' => 'cmsmasters-filter-minimized-on-',
				'render_type' => 'template',
				'condition' => array(
					'header_filter_show' => 'single',
					'header_filter_via_ajax!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'filter_minimized_align',
			array(
				'label' => __( 'Minimized Alignment', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
						'title' => __( 'Left', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-' . ( is_rtl() ? 'right' : 'left' ),
					),
					'center' => array(
						'title' => __( 'Center', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-center',
					),
					'flex-end' => array(
						'title' => __( 'Right', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-' . ( ! is_rtl() ? 'right' : 'left' ),
					),
					'space-between' => array(
						'title' => __( 'Space Between', 'cmsmasters-elementor' ),
						'icon' => 'eicon-h-align-stretch',
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--cmsmasters-theme-blog-filter-minimized-align: {{VALUE}};',
				),
				'condition' => array(
					'header_filter_show' => 'single',
					'filter_item_layout' => 'row',
					'filter_minimized_on!' => 'none',
				),
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
	protected function register_controls_style_header_filter() {
		if ( ! $this->has_header ) {
			return;
		}

		$this->start_controls_section(
			'section_header_filter_style',
			array(
				'label' => __( 'Filter', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				// 'condition' => array( 'header_filter_show' => 'single' ),
			)
		);

		$this->add_control(
			'section_header_filter_container_heading',
			array(
				'label' => __( 'Container', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				// 'condition' => array( 'header_filter_show' => 'single' ),
			)
		);

		$this->add_control(
			'header_bg',
			array(
				'label' => __( 'Background Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__header' => 'background-color: {{VALUE}};',
				),
				// 'condition' => array( 'header_filter_show' => 'single' ),
			)
		);

		$selector = '{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__header';

		$this->add_responsive_control(
			'header_margin',
			array(
				'label' => __( 'Margin', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors' => array(
					'{{WRAPPER}}' => '--header-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
				'condition' => array( 'header_filter_show' => 'single' ),
			)
		);

		$this->add_responsive_control(
			'header_gap',
			array(
				'label' => __( 'Gap', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--header-margin: {{SIZE}}{{UNIT}}',
				),
				'condition' => array( 'header_filter_show' => 'multiple' ),
			)
		);

		$this->add_responsive_control(
			'header_padding',
			array(
				'label' => __( 'Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors' => array(
					$selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
				// 'condition' => array( 'header_filter_show' => 'single' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name' => 'header',
				'selector' => $selector,
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
					),
					'width' => array(
						'condition' => array(
							'border!' => array( '', 'none' ),
						),
					),
					'color' => array(
						'condition' => array(
							'border!' => array( '', 'none' ),
						),
					),
				),
				// 'condition' => array( 'header_filter_show' => 'single' ),
			)
		);

		$this->add_control(
			'section_header_filter_items_heading',
			array(
				'label' => __( 'Items', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'header_filter_show' => 'single' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'typography_header_filter',
				'exclude' => array(
					'line_height',
					'text_decoration',
				),
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__filter-nav-primary a,' .
					'{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__filter-minimize_trigger,' .
					'{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__filter-nav-secondary-trigger',
				'condition' => array( 'header_filter_show' => 'single' ),
			)
		);

		$this->add_responsive_control(
			'header_filter_columns_gap',
			array(
				'label' => __( 'Columns Gap', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .cmsmasters-blog' => Utils::prepare_css_var( 'theme_blog_filter_columns_gap', '{{SIZE}}{{UNIT}}' ),
				),
				'condition' => array( 'header_filter_show' => 'single' ),
			)
		);

		$this->add_responsive_control(
			'header_filter_rows_gap',
			array(
				'label' => __( 'Rows Gap', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .cmsmasters-blog' => Utils::prepare_css_var( 'theme_blog_header_filter_rows_gap', '{{SIZE}}{{UNIT}}' ),
				),
				'condition' => array( 'header_filter_show' => 'single' ),
			)
		);

		$this->add_responsive_control(
			'header_filter_item_elements_gap',
			array(
				'label' => __( 'Item Elements Gap', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .cmsmasters-blog' => Utils::prepare_css_var( 'header_filter_item_elements_gap', '{{SIZE}}{{UNIT}}' ),
				),
				'condition' => array( 'header_filter_show' => 'single' ),
			)
		);

		$this->add_responsive_control(
			'header_filter_padding',
			array(
				'label' => __( 'Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__filter-nav-primary a,' .
					'{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__filter-minimize_trigger,' .
					'{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__filter-nav-secondary-trigger' => '--header-filter-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array( 'header_filter_show' => 'single' ),
			)
		);

		$this->add_group_control(
			CmsmastersControls::VARS_BORDER_GROUP,
			array(
				'name' => 'header_filter_border',
				'exclude' => array( 'color' ),
				'fields_options' => array(
					'width' => array( 'label' => esc_html__( 'Border Width', 'cmsmasters-elementor' ) ),
				),
				'selector' => '{{WRAPPER}}',
				'condition' => array( 'header_filter_show' => 'single' ),
			)
		);

		$this->add_control(
			'header_filter_divider',
			array(
				'label' => __( 'Divider', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'selectors_dictionary' => array(
					'' => 'none',
					'yes' => 'block',
				),
				'default' => 'yes',
				'render_type' => 'template',
				'selectors' => array(
					'{{WRAPPER}}' => '--header-filter-divider: {{VALUE}};',
				),
				'condition' => array(
					'header_filter_show' => 'single',
					'filter_multiple_rows_layout' => 'grid',
				),
			)
		);

		$this->add_control(
			'header_filter_divider_color',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--header-filter-divider-color: {{VALUE}};',
				),
				'condition' => array(
					'header_filter_show' => 'single',
					'filter_multiple_rows_layout' => 'grid',
					'header_filter_divider' => 'yes',
				),
			)
		);

		$this->start_controls_tabs(
			'tabs_header_filter_style',
			array( 'condition' => array( 'header_filter_show' => 'single' ) )
		);

		foreach ( array(
			'normal' => __( 'Normal', 'cmsmasters-elementor' ),
			'hover' => __( 'Hover', 'cmsmasters-elementor' ),
			'active' => __( 'Active', 'cmsmasters-elementor' ),
		) as $type => $label ) {
			$selector_loop_link_normal = '{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__filter-nav-primary a,' .
				'{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__filter-minimize_trigger,' .
				'{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__filter-nav-secondary-trigger';
			$selector_loop_link = $selector_loop_link_normal;

			switch ( $type ) {
				case 'hover':
					$selector_loop_link = '{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__filter-nav-primary a:hover,' .
						'{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__filter-minimize_trigger:hover,' .
						'{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__filter-nav-secondary-trigger:hover';

					break;
				case 'active':
					$selector_loop_link = '{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__filter-nav-primary .term-link-active,' .
					'{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__filter-minimize_trigger.active';

					break;
			}

			$this->start_controls_tab(
				"header_filter_tab_{$type}",
				array(
					'label' => $label,
					'condition' => array( 'header_filter_show' => 'single' ),
				)
			);

			$this->add_control(
				"header_filter_color_{$type}",
				array(
					'label' => __( 'Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$selector_loop_link => 'color: {{VALUE}};',
					),
					'condition' => array( 'header_filter_show' => 'single' ),
				)
			);

			$this->add_control(
				"header_filter_description_color_{$type}",
				array(
					'label' => __( 'Description Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .cmsmasters-blog' => Utils::prepare_css_var( "header_filter_description_color_{$type}", '{{VALUE}}' ),
					),
					'condition' => array(
						'header_filter_show' => 'single',
						'filter_item_elements' => 'description',
					),
				)
			);

			$this->add_control(
				"header_filter_count_color_{$type}",
				array(
					'label' => __( 'Count Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .cmsmasters-blog' => Utils::prepare_css_var( "filter_count_color_{$type}", '{{VALUE}}' ),
					),
					'condition' => array(
						'header_filter_show' => 'single',
						'filter_item_elements' => 'count',
					),
				)
			);

			$this->add_control(
				"header_filter_bg_{$type}",
				array(
					'label' => __( 'Background Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$selector_loop_link => 'background-color: {{VALUE}};',
					),
					'condition' => array( 'header_filter_show' => 'single' ),
				)
			);

			$this->add_control(
				"header_filter_border_color_{$type}",
				array(
					'label' => __( 'Border Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .cmsmasters-blog' => Utils::prepare_css_var( "header_filter_border_color_{$type}", '{{VALUE}}' ),
					),
					'condition' => array(
						'header_filter_show' => 'single',
						'header_filter_border_border!' => 'none',
					),
				)
			);

			$this->add_control(
				"header_filter_border_radius_{$type}",
				array(
					'label' => __( 'Border Radius', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
					),
					'selectors' => array(
						'{{WRAPPER}} .cmsmasters-blog' => Utils::prepare_css_var( "header_filter_border_radius_{$type}", '{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}' ),
					),
					'condition' => array( 'header_filter_show' => 'single' ),
				)
			);

			$this->add_control(
				"header_filter_text_decoration_{$type}",
				array(
					'label' => __( 'Text Decoration', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => '',
					'options' => array(
						'' => __( 'Default', 'cmsmasters-elementor' ),
						'none' => __( 'None', 'cmsmasters-elementor' ),
						'underline' => __( 'Underline', 'cmsmasters-elementor' ),
						'overline' => __( 'Overline', 'cmsmasters-elementor' ),
						'line-through' => __( 'Line Through', 'cmsmasters-elementor' ),
					),
					'selectors' => array(
						$selector_loop_link => 'text-decoration: {{VALUE}};',
					),
					'condition' => array( 'header_filter_show' => 'single' ),
				)
			);

			$this->add_group_control(
				CmsmastersControls::VARS_BOX_SHADOW_GROUP,
				array(
					'name' => "header_filter_{$type}",
					'selector' => '{{WRAPPER}} .cmsmasters-blog',
					'condition' => array( 'header_filter_show' => 'single' ),
				)
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				array(
					'label' => __( 'Text Shadow', 'cmsmasters-elementor' ),
					'name' => "header_filter_text_shadow_{$type}",
					'selector' => $selector_loop_link,
					'condition' => array( 'header_filter_show' => 'single' ),
				)
			);

			if ( 'hover' === $type ) {
				$this->add_control(
					"header_filter_anim_dur_{$type}",
					array(
						'label' => __( 'Animation Duration', 'cmsmasters-elementor' ) . ' (ms)',
						'type' => Controls_Manager::SLIDER,
						'range' => array(
							'px' => array(
								'min' => 0,
								'max' => 3000,
							),
						),
						'selectors' => array(
							$selector_loop_link_normal => 'transition-duration: {{SIZE}}ms',
						),
						'condition' => array( 'header_filter_show' => 'single' ),
					)
				);
			}

			if ( 'hover' === $type && 'active' === $type ) {
				$this->add_control(
					"header_opacity_{$type}",
					array(
						'label' => __( 'Opacity', 'cmsmasters-elementor' ),
						'type' => Controls_Manager::SLIDER,
						'range' => array(
							'px' => array(
								'max' => 1,
								'min' => 0.10,
								'step' => 0.01,
							),
						),
						'selectors' => array(
							$selector_loop_link_normal => 'opacity: {{SIZE}};',
						),
						'condition' => array( 'header_filter_show' => 'single' ),
					)
				);
			}

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->add_control(
			'section_header_filter_image_heading',
			array(
				'label' => __( 'Image', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'header_filter_show' => 'single',
					'filter_item_elements' => 'image',
				),
			)
		);

		$this->add_responsive_control(
			'header_filter_image_width',
			array(
				'label' => __( 'Width', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .cmsmasters-blog' => Utils::prepare_css_var( 'header_filter_image_width', '{{SIZE}}{{UNIT}}' ),
				),
				'condition' => array(
					'header_filter_show' => 'single',
					'filter_item_elements' => 'image',
				),
			)
		);

		$this->add_responsive_control(
			'header_filter_image_margin',
			array(
				'label' => __( 'Margin', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors' => array(
					'{{WRAPPER}} .cmsmasters-blog' => Utils::prepare_css_var( 'header_filter_image_margin_top', '{{TOP}}{{UNIT}}' ) .
					Utils::prepare_css_var( 'header_filter_image_margin_right', '{{RIGHT}}{{UNIT}}' ) .
					Utils::prepare_css_var( 'header_filter_image_margin_bottom', '{{BOTTOM}}{{UNIT}}' ) .
					Utils::prepare_css_var( 'header_filter_image_margin_left', '{{LEFT}}{{UNIT}}' ),
				),
				'condition' => array(
					'header_filter_show' => 'single',
					'filter_item_elements' => 'image',
				),
			)
		);

		$this->add_control(
			'section_header_filter_description_heading',
			array(
				'label' => __( 'Description', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'header_filter_show' => 'single',
					'filter_item_elements' => 'description',
				),
			)
		);

		$this->add_group_control(
			CmsmastersControls::VARS_TYPOGRAPHY_GROUP,
			array(
				'name' => 'header_filter_description',
				'label' => __( 'Description Typography', 'cmsmasters-elementor' ),
				'exclude' => array(
					'line_height',
					'text_decoration',
				),
				'selector' => '{{WRAPPER}} .cmsmasters-blog',
				'condition' => array(
					'header_filter_show' => 'single',
					'filter_item_elements' => 'description',
				),
			)
		);

		$this->add_responsive_control(
			'header_filter_description_margin',
			array(
				'label' => __( 'Margin', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors' => array(
					'{{WRAPPER}} .cmsmasters-blog' => Utils::prepare_css_var( 'header_filter_description_margin_top', '{{TOP}}{{UNIT}}' ) .
					Utils::prepare_css_var( 'header_filter_description_margin_right', '{{RIGHT}}{{UNIT}}' ) .
					Utils::prepare_css_var( 'header_filter_description_margin_bottom', '{{BOTTOM}}{{UNIT}}' ) .
					Utils::prepare_css_var( 'header_filter_description_margin_left', '{{LEFT}}{{UNIT}}' ),
				),
				'condition' => array(
					'header_filter_show' => 'single',
					'filter_item_elements' => 'description',
				),
			)
		);

		$this->add_control(
			'section_header_filter_count_heading',
			array(
				'label' => __( 'Count', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'header_filter_show' => 'single',
					'filter_item_elements' => 'count',
				),
			)
		);

		$this->add_group_control(
			CmsmastersControls::VARS_TYPOGRAPHY_GROUP,
			array(
				'name' => 'header_filter_count',
				'label' => __( 'Count Typography', 'cmsmasters-elementor' ),
				'exclude' => array(
					'line_height',
					'text_decoration',
				),
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '---filter-count-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '---filter-count-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '---filter-count-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '---filter-count-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '---filter-count-font-style: {{VALUE}};',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '---filter-count-text-decoration: {{VALUE}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '---filter-count-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '---filter-count-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '---filter-count-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
				'condition' => array(
					'header_filter_show' => 'single',
					'filter_item_elements' => 'count',
				),
			)
		);

		$this->add_responsive_control(
			'header_filter_count_margin',
			array(
				'label' => __( 'Margin', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors' => array(
					'{{WRAPPER}} .cmsmasters-blog' => Utils::prepare_css_var( 'filter_count_margin_top', '{{TOP}}{{UNIT}}' ) .
					Utils::prepare_css_var( 'filter_count_margin_right', '{{RIGHT}}{{UNIT}}' ) .
					Utils::prepare_css_var( 'filter_count_margin_bottom', '{{BOTTOM}}{{UNIT}}' ) .
					Utils::prepare_css_var( 'filter_count_margin_left', '{{LEFT}}{{UNIT}}' ),
				),
				'condition' => array(
					'header_filter_show' => 'single',
					'filter_item_elements' => 'count',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget on ajax.
	 *
	 * @since 1.0.0
	 */
	public function render_ajax( $ajax_vars ) {
		$ajax_vars = isset( $ajax_vars['query_vars'] ) ? $ajax_vars['query_vars'] : array();
		$sorting = array();
		$taxonomies = array();
		
		foreach ( $ajax_vars['tax_query'] as $taxonomy ) {
			if ( $taxonomy['taxonomy'] === 'sorting' ) {
				$sorting[] = $taxonomy;
			} else {
				$taxonomies[] = $taxonomy;
			}
		}

		$ajax_vars = array(
			'query_vars' => array(
				'paged' => isset( $ajax_vars['paged'] ) ? $ajax_vars['paged'] : '1',
				'tax_query' => $taxonomies,
			),
		);

		if ( ! empty( $sorting ) ) {
			$ajax_vars['query_vars']['orderby'] = $sorting[0]['terms'][0];

			if ( 'meta_value_num' === $sorting[0]['terms'][0] ) {
				$ajax_vars['query_vars']['meta_key'] ='views';
			}
		}

		$query_vars = isset( $ajax_vars['query_vars'] ) ? BlogModule::get_allowed_query_vars( $ajax_vars['query_vars'] ) : array();

		if ( ! empty( $query_vars ) ) {
			$this->set_query_vars( $query_vars );
		}

		$this->init_query();

		if ( $this->get_query()->have_posts() ) {
			if ( ! $this->get_query()->found_posts ) {
				wp_die( 0, '', 404 );
			}
		} else {
			if ( $this->is_current_query() ) {
				echo '<h4 class="elementor-widget-cmsmasters-theme-blog__nothing-found">' .
					esc_html( $this->get_settings_fallback( 'nothing_found_message' ) ) .
				'</h4>';
			} else {
				$post_type = $this->get_settings_for_display( 'blog_post_type' );
				$new_post_type_name = ucwords( str_replace( array( '_', '-' ), array( ' ', ' ' ), $post_type ) );

				if ( substr( $new_post_type_name, -1 ) !== 's' ) {
					$new_post_type_name .= 's';
				}

				Utils::render_alert( $new_post_type_name . ' ' . esc_html__( 'not found!', 'cmsmasters-elementor' ) );
			}

			return;
		}

		$this->render_posts();
		$this->render_pagination();
	}

	/**
	 * Render pagination.
	 *
	 * @since 1.0.0
	 */
	protected function render_pagination() {
		if ( ! $this->has_pagination ) {
			return;
		}

		$this->pagination->set_wp_query( $this->get_query() );
		$this->pagination->render();
	}

	/**
	 * @since 1.0.0
	 */
	protected function render_blog() {
		$this->add_render_attribute( array(
			'blog_var' => array(
				'class' => 'elementor-widget-cmsmasters-theme-blog__posts-variable',
			),
		) );

		$this->render_header();

		echo '<div ' . $this->get_render_attribute_string( 'blog_var' ) . '>';

			$this->render_posts();

			$this->render_pagination();

		echo '</div>';
	}

	public function get_post_categories( $taxonomy ) {
		$terms = get_terms( array(
			'taxonomy' => $taxonomy,
			'hide_empty' => true,
		) );

		$categories = array();

		foreach ( $terms as $term ) {
			$categories[ $term->term_id ] = $term->name;
		}

		return $categories;
	}

	/**
	 * Render header filter.
	 *
	 * @since 1.0.0
	 */
	protected function render_multiple_filter( $post_type ) {
		$settings = $this->get_settings_for_display();

		$post_taxonomy = $settings["header_{$post_type}_filter_multiple_items"];

		if ( empty( $post_taxonomy ) ) {
			return;
		}

		echo '<div class="elementor-widget-cmsmasters-theme-blog__header cmsmasters-filter-nav-multiple">' .
			'<ul class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list">';

		foreach ( $post_taxonomy as $taxonomy ) {
			echo '<li class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item" data-taxonomy-id="' . $taxonomy . '">';
				echo '<span class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-trigger-wrap">' .
					'<span class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-label">';

					$default_taxonomy_value = esc_html( ucwords( str_replace( array( '_', '-' ), array( ' ', ' ' ), $taxonomy ) ) );

					if ( ! empty( $settings["header_filter_multiple_{$taxonomy}_custom_label"] ) ) {
						echo esc_html( $settings["header_filter_multiple_{$taxonomy}_custom_label"] );
					} else {
						echo $default_taxonomy_value;
					}

					echo '</span>';

					if ( ! empty( $settings["header_filter_multiple_{$taxonomy}_custom_select"] ) ) {
						$select_value = esc_html( $settings["header_filter_multiple_{$taxonomy}_custom_select"] );
					} else {
						$select_value = esc_html__( 'ALL ', 'cmsmasters-elementor' ) . $default_taxonomy_value;
					}

					echo '<span class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-trigger default-value">' .
						'<span class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-trigger-value" data-default="' . $select_value . '">' .
							$select_value .
						'</span>';

						Icons_Manager::render_icon( $settings['header_filter_multiple_select_icon'], array( 'class' => 'elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-trigger-icon' ) );

					echo '</span>' .
				'</span>';

				if ( ! empty( $this->get_post_categories( $taxonomy ) ) ) {
					echo '<ul class="elementor-widget-cmsmasters-theme-blog__multiple-category-list">';

					foreach ( $this->get_post_categories( $taxonomy ) as $key => $category ) {
						echo '<li class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item" data-category-id="' . $key . '">' .
							'<input class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item-checkbox" type="checkbox" id="' . esc_attr( $key ) . '" name="' . esc_attr( $category ) . '" value="' . esc_attr( $category ) . '">' .
							'<label for="' . esc_attr( $key ) . '">' .
								esc_html( $category ) .
							'</label>';

							Icons_Manager::render_icon( array(
								'value' => 'themeicon- theme-icon-check-box-outline',
								'library' => 'themeicon-',
							), array( 'class' => 'checkbox-icon empty' ) );

							Icons_Manager::render_icon( array(
								'value' => 'themeicon- theme-icon-check-box',
								'library' => 'themeicon-',
							), array( 'class' => 'checkbox-icon full' ) );

						echo '</li>';
					}

					echo '<div class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-button">' .
						esc_html( 'Apply', 'cmsmasters-elementor' ) .
					'</div>' .
					'</ul>';
				}

			echo '</li>';
		}

		if ( $settings['header_filter_multiple_sorting'] ) {
			echo '<li class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item" data-taxonomy-id="sorting">' .
				'<span class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-trigger-wrap">' .
					'<span class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-label">';

						if ( ! empty( $settings['header_filter_multiple_sorting_custom_label'] ) ) {
							echo esc_html( $settings['header_filter_multiple_sorting_custom_label'] );
						} else {
							echo esc_html__( 'Sort by', 'cmsmasters-elementor' );
						}

					echo '</span>' .
					'<span class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-trigger">' .
						'<span class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-trigger-value" data-default="Latest">' .
							esc_html__( 'Latest', 'cmsmasters-elementor' ) .
						'</span>';

						Icons_Manager::render_icon( $settings['header_filter_multiple_select_icon'], array( 'class' => 'elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-trigger-icon' ) );

					echo'</span>' .
				'</span>' .
				'<ul class="elementor-widget-cmsmasters-theme-blog__multiple-category-list sorting">';
				
					// if ( 'collections' === $settings['blog_post_type'] ) {
					// 	echo '<li class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item" data-category-id="price-growth">' .
					// 		'<input class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item-checkbox" type="checkbox" id="' . esc_attr__( 'price-growth', 'cmsmasters-elementor' ) . '" name="' . esc_attr__( 'price-growth', 'cmsmasters-elementor' ) . '" value="' . esc_attr__( 'Price Growth', 'cmsmasters-elementor' ) . '">' .
					// 		'<label for="' . esc_attr__( 'price-growth', 'cmsmasters-elementor' ) . '">' .
					// 			esc_html__( 'Price Growth', 'cmsmasters-elementor' ) .
					// 		'</label>';

					// 		Icons_Manager::render_icon( array(
					// 			'value' => 'themeicon- theme-icon-chech-line',
					// 			'library' => 'themeicon-',
					// 		), array( 'class' => 'checkbox-icon full' ) );

					// 	echo '</li>' .
					// 	'<li class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item" data-category-id="price-fall">' .
					// 		'<input class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item-checkbox" type="checkbox" id="' . esc_attr__( 'price-fall', 'cmsmasters-elementor' ) . '" name="' . esc_attr__( 'price-fall', 'cmsmasters-elementor' ) . '" value="' . esc_attr__( 'Price Fall', 'cmsmasters-elementor' ) . '">' .
					// 		'<label for="' . esc_attr__( 'price-fall', 'cmsmasters-elementor' ) . '">' .
					// 		esc_html__( 'Price Fall', 'cmsmasters-elementor' ) .
					// 		'</label>';

					// 		Icons_Manager::render_icon( array(
					// 			'value' => 'themeicon- theme-icon-chech-line',
					// 			'library' => 'themeicon-',
					// 		), array( 'class' => 'checkbox-icon full' ) );

					// 	echo '</li>';
					// }

					echo '<li class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item checked" data-category-id="date">' .
						'<input class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item-checkbox" type="checkbox" id="' . esc_attr__( 'latest', 'cmsmasters-elementor' ) . '" name="' . esc_attr__( 'latest', 'cmsmasters-elementor' ) . '" value="' . esc_attr__( 'Latest', 'cmsmasters-elementor' ) . '">' .
						'<label for="' . esc_attr__( 'latest', 'cmsmasters-elementor' ) . '">' .
						esc_html__( 'Latest', 'cmsmasters-elementor' ) .
						'</label>';

						Icons_Manager::render_icon( array(
							'value' => 'themeicon- theme-icon-chech-line',
							'library' => 'themeicon-',
						), array( 'class' => 'checkbox-icon full' ) );

					echo '</li>' .
					'<li class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item" data-category-id="meta_value_num">' .
						'<input class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item-checkbox" type="checkbox" id="' . esc_attr__( 'popular', 'cmsmasters-elementor' ) . '" name="' . esc_attr__( 'popular', 'cmsmasters-elementor' ) . '" value="' . esc_attr__( 'Popular', 'cmsmasters-elementor' ) . '">' .
						'<label for="' . esc_attr__( 'popular', 'cmsmasters-elementor' ) . '">' .
						esc_html__( 'Popular', 'cmsmasters-elementor' ) .
						'</label>';

						Icons_Manager::render_icon( array(
							'value' => 'themeicon- theme-icon-chech-line',
							'library' => 'themeicon-',
						), array( 'class' => 'checkbox-icon full' ) );

					echo '</li>' .
					'<div class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-button">' .
					esc_html__( 'Apply', 'cmsmasters-elementor' ) .
					'</div>' .
				'</ul>';
			'</li>';
		}

			echo '</ul>' .
		'</div>';
	}

	/**
	 * Render header filter.
	 *
	 * @since 1.0.0
	 */
	protected function render_multiple_side_filter( $post_type ) {
		$settings = $this->get_settings_for_display();

		$post_taxonomy = $settings["header_{$post_type}_filter_multiple_items"];

		if ( empty( $post_taxonomy ) ) {
			return;
		}

		echo '<div class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-popup-trigger">' .
			esc_html__( 'Filter by', 'cmsmasters-elementor' );

			Icons_Manager::render_icon( array(
				'value' => 'themeicon- theme-icon-arrow-back',
				'library' => 'themeicon-',
			), array( 'class' => 'elementor-widget-cmsmasters-theme-blog__multiple-category-list-popup-trigger-icon' ) );

		echo '</div>' .
		'<div class="elementor-widget-cmsmasters-theme-blog__header-side cmsmasters-filter-nav-multiple">' .
			'<div class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-popup-close">' .
				'<h2>' .
					esc_html__( 'Filter by', 'cmsmasters-elementor' ) .
				'</h2>';

				Icons_Manager::render_icon( array(
					'value' => 'themeicon- theme-icon-close',
					'library' => 'themeicon-',
				), array( 'class' => 'elementor-widget-cmsmasters-theme-blog__multiple-category-list-popup-close-icon' ) );

			echo '</div>' .
			'<div class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-clear-all-button">' .
				esc_html__( 'Clear All', 'cmsmasters-elementor' ) .
			'</div>' .
			'<ul class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list">';

				if ( $settings['header_filter_multiple_sorting'] ) {
					echo '<li class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item" data-taxonomy-id="sorting">' .
						'<span class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-trigger-wrap">' .
							'<span class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-label">';

								if ( ! empty( $settings['header_filter_multiple_sorting_custom_label'] ) ) {
									echo esc_html( $settings['header_filter_multiple_sorting_custom_label'] );
								} else {
									echo esc_html__( 'Sort by', 'cmsmasters-elementor' );
								}

							echo '</span>' .
							'<span class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-trigger">' .
								'<span class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-trigger-value" data-default="Latest">' .
									esc_html__( 'Latest', 'cmsmasters-elementor' ) .
								'</span>';

								Icons_Manager::render_icon( $settings['header_filter_multiple_select_icon'], array( 'class' => 'elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-trigger-icon' ) );

							echo'</span>' .
						'</span>' .
						'<ul class="elementor-widget-cmsmasters-theme-blog__multiple-category-list sorting">';
						
							// if ( 'collections' === $settings['blog_post_type'] ) {
							// 	echo '<li class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item" data-category-id="price-growth">' .
							// 		'<input class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item-checkbox" type="checkbox" id="' . esc_attr__( 'price-growth', 'cmsmasters-elementor' ) . '" name="' . esc_attr__( 'price-growth', 'cmsmasters-elementor' ) . '" value="' . esc_attr__( 'Price Growth', 'cmsmasters-elementor' ) . '">' .
							// 		'<label for="' . esc_attr__( 'price-growth', 'cmsmasters-elementor' ) . '">' .
							// 			esc_html__( 'Price Growth', 'cmsmasters-elementor' ) .
							// 		'</label>';
		
							// 		Icons_Manager::render_icon( array(
							// 			'value' => 'themeicon- theme-icon-chech-line',
							// 			'library' => 'themeicon-',
							// 		), array( 'class' => 'checkbox-icon full' ) );
		
							// 	echo '</li>' .
							// 	'<li class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item" data-category-id="price-fall">' .
							// 		'<input class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item-checkbox" type="checkbox" id="' . esc_attr__( 'price-fall', 'cmsmasters-elementor' ) . '" name="' . esc_attr__( 'price-fall', 'cmsmasters-elementor' ) . '" value="' . esc_attr__( 'Price Fall', 'cmsmasters-elementor' ) . '">' .
							// 		'<label for="' . esc_attr__( 'price-fall', 'cmsmasters-elementor' ) . '">' .
							// 		esc_html__( 'Price Fall', 'cmsmasters-elementor' ) .
							// 		'</label>';
		
							// 		Icons_Manager::render_icon( array(
							// 			'value' => 'themeicon- theme-icon-chech-line',
							// 			'library' => 'themeicon-',
							// 		), array( 'class' => 'checkbox-icon full' ) );
		
							// 	echo '</li>';
							// }
		
							echo '<li class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item checked" data-category-id="date">' .
								'<input class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item-checkbox" type="checkbox" id="' . esc_attr__( 'latest', 'cmsmasters-elementor' ) . '" name="' . esc_attr__( 'latest', 'cmsmasters-elementor' ) . '" value="' . esc_attr__( 'Latest', 'cmsmasters-elementor' ) . '">' .
								'<label for="' . esc_attr__( 'latest', 'cmsmasters-elementor' ) . '">' .
								esc_html__( 'Latest', 'cmsmasters-elementor' ) .
								'</label>';
		
								Icons_Manager::render_icon( array(
									'value' => 'themeicon- theme-icon-chech-line',
									'library' => 'themeicon-',
								), array( 'class' => 'checkbox-icon full' ) );
		
							echo '</li>' .
							'<li class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item" data-category-id="meta_value_num">' .
								'<input class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item-checkbox" type="checkbox" id="' . esc_attr__( 'popular', 'cmsmasters-elementor' ) . '" name="' . esc_attr__( 'popular', 'cmsmasters-elementor' ) . '" value="' . esc_attr__( 'Popular', 'cmsmasters-elementor' ) . '">' .
								'<label for="' . esc_attr__( 'popular', 'cmsmasters-elementor' ) . '">' .
								esc_html__( 'Popular', 'cmsmasters-elementor' ) .
								'</label>';
		
								Icons_Manager::render_icon( array(
									'value' => 'themeicon- theme-icon-chech-line',
									'library' => 'themeicon-',
								), array( 'class' => 'checkbox-icon full' ) );
		
							echo '</li>' .
							// '<div class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-button">' .
							// 	esc_html__( 'Apply', 'cmsmasters-elementor' ) .
							// '</div>' .
						'</ul>';
					'</li>';
				}

				foreach ( $post_taxonomy as $taxonomy ) {
					echo '<li class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item" data-taxonomy-id="' . $taxonomy . '">';
						echo '<span class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-trigger-wrap">';
							// '<span class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-label">';

							$default_taxonomy_value = esc_html( ucwords( str_replace( array( '_', '-' ), array( ' ', ' ' ), $taxonomy ) ) );

							// if ( ! empty( $settings["header_filter_multiple_{$taxonomy}_custom_label"] ) ) {
							// 	echo esc_html( $settings["header_filter_multiple_{$taxonomy}_custom_label"] );
							// } else {
							// 	echo $default_taxonomy_value;
							// }

							// echo '</span>';

							if ( ! empty( $settings["header_filter_multiple_{$taxonomy}_custom_select"] ) ) {
								$select_value = esc_html( $settings["header_filter_multiple_{$taxonomy}_custom_select"] );
							} else {
								$select_value = esc_html__( 'ALL ', 'cmsmasters-elementor' ) . $default_taxonomy_value;
							}

							echo '<span class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-trigger default-value">' .
								'<span class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-trigger-value" data-default="' . $select_value . '">' .
									$select_value .
								'</span>' .
								'<span class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-trigger-clear-wrap">' .
									'<span class="elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-trigger-clear">Clear</span>';

									Icons_Manager::render_icon( $settings['header_filter_multiple_select_icon'], array( 'class' => 'elementor-widget-cmsmasters-theme-blog__multiple-taxonomy-list-item-trigger-icon' ) );
								echo '</span>' .
							'</span>' .
						'</span>';

						if ( ! empty( $this->get_post_categories( $taxonomy ) ) ) {
							echo '<ul class="elementor-widget-cmsmasters-theme-blog__multiple-category-list">';
								// echo '<li class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item" data-category-id="">' .
								// 	'<input class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item-checkbox" type="checkbox" id="1" name="' . esc_attr( 'Show All' ) . '" value="' . esc_attr( 'Show All' ) . '">' .
								// 	'<label for="1">' .
								// 		esc_html__( 'Show All', 'cmsmasters-elementor' ) .
								// 	'</label>';

								// 	Icons_Manager::render_icon( array(
								// 		'value' => 'themeicon- theme-icon-chech-line',
								// 		'library' => 'themeicon-',
								// 	), array( 'class' => 'checkbox-icon full' ) );

								// echo '</li>';

							foreach ( $this->get_post_categories( $taxonomy ) as $key => $category ) {
								echo '<li class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item" data-category-id="' . $key . '">' .
									'<input class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-item-checkbox" type="checkbox" id="' . esc_attr( $key ) . '" name="' . esc_attr( $category ) . '" value="' . esc_attr( $category ) . '">' .
									'<label for="' . esc_attr( $key ) . '">' .
										esc_html( $category ) .
									'</label>';

									Icons_Manager::render_icon( array(
										'value' => 'themeicon- theme-icon-chech-line',
										'library' => 'themeicon-',
									), array( 'class' => 'checkbox-icon full' ) );

								echo '</li>';
							}

							// echo '<div class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-button">' .
							// 	esc_html( 'Apply', 'cmsmasters-elementor' ) .
							// '</div>' .
							echo '</ul>';
						}

					echo '</li>';
				}

			echo '</ul>' .
			'<div class="elementor-widget-cmsmasters-theme-blog__multiple-category-list-button">' .
				esc_html__( 'Apply', 'cmsmasters-elementor' ) .
			'</div>' .
		'</div>';
	}

	/**
	 * Render header.
	 *
	 * @since 1.0.0
	 */
	protected function render_header() {
		if ( ! $this->has_header ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		if ( 'none' === $settings['header_filter_show'] ) {
			return;
		}

		if ( 'multiple' === $settings['header_filter_show'] ) {
			if ( 'post' === $settings['blog_post_type'] ) {
				$this->render_multiple_filter( 'post' );
			}

			if ( 'projects' === $settings['blog_post_type'] ) {
				$this->render_multiple_filter( 'projects' );
			}

			if ( 'collections' === $settings['blog_post_type'] ) {
				$this->render_multiple_filter( 'collections' );
			}

			if ( 'product' === $settings['blog_post_type'] ) {
				if ( 'top' === $settings['header_filter_position'] ) {
					$this->render_multiple_filter( 'product' );
				} else {
					$this->render_multiple_side_filter( 'product' );
				}
			}
		}

		if ( 'single' === $settings['header_filter_show'] ) {
			echo "<div class='elementor-widget-cmsmasters-theme-blog__header cmsmasters-filter-nav-single cmsmasters-filter-nav-multiple-rows-{$settings['filter_multiple_rows_layout']}'>" .
				'<div class="elementor-widget-cmsmasters-theme-blog__header-inner">';

					$this->render_filter();

				echo '</div>
			</div>';
		}
	}

	/**
	 * Render header filter.
	 *
	 * @since 1.0.0
	 */
	protected function render_filter() {
		$settings = $this->get_settings_for_display();

		if ( 'none' === $settings['header_filter_show'] ) {
			return;
		}

		$filter_type = $this->get_settings_for_display( 'filter_type' );
		$control_id = 'blog_include_term_ids';

		if ( 'custom' === $filter_type ) {
			$control_id = "filter_{$control_id}";
		}

		$term_ids = $this->get_settings_for_display( $control_id );
		$default_category = get_option( 'default_category' );
		$filter_data = $this->get_filter_data();

		if ( 'custom' === $filter_type && empty( $term_ids ) && $default_category ) {
			$term_ids = array( $default_category );
		}

		if ( ! $term_ids ) {
			return;
		}

		$filter_item_elements = $settings['filter_item_elements'];

		$count = 0;

		foreach ( $term_ids as $term_id ) {
			$term = get_term( $term_id );

			if ( ! is_wp_error( $term ) && $term ) {
				$count += $term->count;
			}
		}

		$filter_item_count = '<span class="elementor-widget-cmsmasters-theme-blog__filter-item-count">' . esc_html( $count ) . '</span>';

		echo '<div class="elementor-widget-cmsmasters-theme-blog__filter">';

			if ( 'none' !== $settings['filter_minimized_on'] ) {
				echo '<span class="elementor-widget-cmsmasters-theme-blog__filter-minimize_trigger">' .
					esc_html( $this->get_settings_fallback( 'filter_default_text' ) );

					foreach ( $filter_item_elements as $filter_item_element ) {
						if ( 'count' === $filter_item_element ) {
							echo $filter_item_count;
						}
					}

				echo '</span>';
			}

			echo '<ul class="elementor-widget-cmsmasters-theme-blog__filter-nav elementor-widget-cmsmasters-theme-blog__filter-nav-primary">';

				$term_link_attrs = array(
					'class' => array( 'term-link', 'term-link-reset' ),
					'title' => esc_html__( 'All Posts', 'cmsmasters-elementor' ),
					'href' => add_query_arg(
						array(
							$this->get_filter_var_name() => null,
							$this->pagination->get_paged_name() => null,
						),
						( Utils::is_ajax() ? wp_get_referer() : false )
					),
				);

				if ( empty( $filter_data ) ) {
					$term_link_attrs['class'][] = 'term-link-active';

					echo '<li class="term-item-link-active">';
				} else {
					echo '<li>';
				}

					echo '<a ' . ElementorUtils::render_html_attributes( $term_link_attrs ) . '>';

						$filter_all_items_out = '';

						if ( ! empty( $filter_item_elements ) ) {
							foreach ( $filter_item_elements as $filter_item_element ) {
								if ( 'name' === $filter_item_element ) {
									$filter_all_items_out .= '<span class="elementor-widget-cmsmasters-theme-blog__filter-item-name">' . esc_html( $this->get_settings_fallback( 'filter_default_text' ) ) . '</span>';
								}

								if ( 'description' === $filter_item_element && ! empty( $settings['filter_all_items_description'] ) ) {
									$filter_all_items_out .= '<span class="elementor-widget-cmsmasters-theme-blog__filter-item-description">' . esc_html( $settings['filter_all_items_description'] ) . '</span>';
								}

								if ( 'image' === $filter_item_element ) {
									$filter_all_items_out .= '<span class="elementor-widget-cmsmasters-theme-blog__filter-item-image">' .
										Group_Control_Image_Size::get_attachment_image_html( array(
											'filter_all_items_image' => $settings['filter_all_items_image'],
											'filter_all_items_image_size' => 'full',
										), 'filter_all_items_image' ) .
									'</span>';
								}
		
								if ( 'count' === $filter_item_element ) {
									$filter_all_items_out .= $filter_item_count;
								}
							}
						}

						if ( empty( $filter_all_items_out ) ) {
							$filter_all_items_out = '<span class="elementor-widget-cmsmasters-theme-blog__filter-item-name">' . esc_html( $this->get_settings_fallback( 'filter_default_text' ) ) . '</span>';
						}

						echo $filter_all_items_out;

					echo '</a>' .
				'</li>';

				foreach ( $term_ids as $term_id ) {
					$term = get_term( $term_id );

					if ( ! $term || empty( $term ) ) {
						continue;
					}

					$args = array(
						'post_type' => $this->get_settings_for_display( 'blog_post_type' ),
						'tax_query' => array(
							array(
								'taxonomy' => $term->taxonomy,
								'field' => 'term_id',
								'terms' => $term->term_id,
							),
						),
						'posts_per_page' => 1,
					);

					$posts_query = new \WP_Query( $args );

					if ( $posts_query->have_posts() ) {
						$page_url = false;

						if ( is_admin() ) {
							$post_id = Utils::get_document_id();
							$page_url = Plugin::elementor()->documents->get( $post_id )->get_wp_preview_url();
						} elseif ( Utils::is_ajax() ) {
							$page_url = wp_get_referer();
						}

						$href = add_query_arg(
							array(
								$this->get_filter_var_name() => $term->taxonomy . static::FILTER_URL_SEPARATOR . $term->term_id,
								$this->pagination->get_paged_name() => null,
							),
							$page_url
						);

						$term_link_attrs = array(
							'class' => array( 'term-link' ),
							'title' => get_taxonomy( $term->taxonomy )->labels->singular_name,
							'data-taxonomy' => $term->taxonomy,
							'data-term-id' => $term->term_id,
							'href' => esc_url( $href ),
						);

						if (
							$filter_data &&
							$term->taxonomy === $filter_data['taxonomy'] &&
							$term->term_id === $filter_data['term_id']
						) {
							$term_link_attrs['class'][] = 'term-link-active';

							echo '<li class="term-item-link-active">';
						} else {
							echo '<li>';
						}

							echo '<a ' . ElementorUtils::render_html_attributes( $term_link_attrs ) . '>';
								$filter_item_out = '';

								if ( ! empty( $filter_item_elements ) ) {
									foreach ( $filter_item_elements as $filter_item_element ) {
										if ( 'name' === $filter_item_element ) {
											$filter_item_out .= '<span class="elementor-widget-cmsmasters-theme-blog__filter-item-name">' . esc_html( $term->name ) . '</span>';
										}

										if ( 'description' === $filter_item_element && ! empty( $term->description ) ) {
											$filter_item_out .= '<span class="elementor-widget-cmsmasters-theme-blog__filter-item-description">' . esc_html( $term->description ) . '</span>';
										}

										if ( 'image' === $filter_item_element ) {
											$filter_item_out .= $this->get_filter_item_image( $term, $settings['filter_item_image'] );
										}
		
										if ( 'count' === $filter_item_element ) {
											$filter_item_out .= '<span class="elementor-widget-cmsmasters-theme-blog__filter-item-count">' . esc_html( $term->count ) . '</span>';
										}
									}
								}

								if ( empty( $filter_item_out ) ) {
									$filter_item_out = '<span class="elementor-widget-cmsmasters-theme-blog__filter-item-name">' . esc_html( $term->name ) . '</span>';
								}

								echo $filter_item_out;

							echo '</a>' .
						'</li>';
					}

					wp_reset_query();
				}

			echo '</ul>' .
		'</div>';
	}

	/**
	 * Get filter item image.
	 *
	 * @since 1.0.0
	 *
	 * @param object $term Taxonomy term.
	 * @param string $key ACF key.
	 *
	 * @return string Filter item image.
	 */
	protected function get_filter_item_image( $term, $key = '' ) {
		if ( ! function_exists( 'get_field_object' ) || empty( $key ) || ! is_object( $term ) ) {
			return '';
		}

		$keys = array_reverse( explode( ':', $key ) );

		list( $meta_key, $field_key ) = array_pad( $keys, 2, false );

		$field = get_field_object( $meta_key, $term );
		
		if ( empty( $field ) ) {
			return '';
		}

		$settings = array(
			'image' => array(
				'id' => null,
				'url' => '',
			),
			'image_size' => 'full',
		);

		if ( 'array' === $field['return_format'] ) {
			$settings['image']['id'] = $field['value']['id'];
			$settings['image']['url'] = $field['value']['url'];
		} elseif ( 'url' === $field['return_format'] ) {
			$settings['image']['url'] = $field['value'];
		} elseif ( 'id' === $field['return_format'] ) {
			$settings['image']['id'] = $field['value'];
		}

		if ( empty( $settings['image']['id'] ) && empty( $settings['image']['url'] ) ) {
			return '';
		}

		return '<span class="elementor-widget-cmsmasters-theme-blog__filter-item-image">' . Group_Control_Image_Size::get_attachment_image_html( $settings, 'image' ) . '</span>';
	}

	/**
	 * Get query vars.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_query_vars() {
		$query_vars = parent::get_query_vars();
		$filter_data = $this->get_filter_data();

		if ( $this->has_pagination && ! AjaxWidgetModule::is_active_ajax() ) {
			$query_vars['paged'] = $this->pagination->get_paged();
		}

		if ( $filter_data ) {
			$query_vars['tax_query'] = array(
				array(
					'field' => 'term_id',
					'taxonomy' => $filter_data['taxonomy'],
					'terms' => array( $filter_data['term_id'] ),
				),
			);
		}

		return $query_vars;
	}

	/**
	 * Get query var for filter.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_filter_var_name() {
		return "cmsmasters-filter-{$this->get_ID()}";
	}

	/**
	 * Get current filter by query.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_filter_data() {
		list( $taxonomy, $term_id ) = array_pad(
			explode( static::FILTER_URL_SEPARATOR, Utils::get_if_isset( $_GET, $this->get_filter_var_name(), '' ) ),
			2,
			false
		);

		if (
			! ( $taxonomy && taxonomy_exists( $taxonomy ) ) ||
			! ( $term_id && term_exists( $term_id, $taxonomy ) )
		) {
			return array();
		}

		return array(
			'term_id' => $term_id,
			'taxonomy' => $taxonomy,
		);
	}
}
