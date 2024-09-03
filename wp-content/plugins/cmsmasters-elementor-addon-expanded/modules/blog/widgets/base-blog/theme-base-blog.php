<?php
namespace CmsmastersElementor\Modules\Blog\Widgets\Base_Blog;

use CmsmastersElementor\Base\Base_Widget;
use CmsmastersElementor\Controls_Manager as CmsmastersControls;
use CmsmastersElementor\Modules\Wordpress\Module as WordpressModule;
use CmsmastersElementor\Modules\Woocommerce\Classes\Products_Renderer;
use CmsmastersElementor\Modules\Wordpress\Managers\Query_Manager;
use CmsmastersElementor\Utils;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;


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
abstract class Theme_Base_Blog extends Base_Widget {

	const QUERY_CONTROL_PREFIX = 'blog';

	/**
	 * Query variables for setting up the WordPress query loop.
	 *
	 * @var array
	 */
	private $query_vars = array();

	/**
	 * The WordPress query instance.
	 *
	* @var \WP_Query
	*/
	private $query;

	/**
	 * The WordPress query instance.
	 *
	* @var \WP_Query
	*/
	protected $posts_popup_id = array();

	/**
	 * Get group name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Group name.
	 */
	public function get_group_name() {
		return 'cmsmasters-blog';
	}

	/**
	 * LazyLoad widget use control.
	 *
	 * @since 1.0.0
	 *
	 * @return bool true - with control, false - without control.
	 */
	public function lazyload_widget_use_control() {
		return true;
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
			'blog',
			'posts',
			'query',
			'loop',
			'cpt',
			'custom post type',
		);
	}

	/**
	 * @since 1.0.0
	 */
	public function register_controls() {
		$this->register_query_section_controls();
	}

	/**
	 * @since 1.0.0
	 */
	protected function init_controls() {
		parent::init_controls();

		$this->register_advanced_section_controls();
		$this->register_advanced_style_section_controls();
	}

	/**
	 * Register query controls.
	 *
	 * Adds different input fields to allow the user to change and customize the classes settings.
	 *
	 * @since 1.0.0
	 */
	protected function register_query_section_controls() {
		$this->start_controls_section(
			'section_query',
			array(
				'label' => __( 'Query', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_group_control(
			CmsmastersControls::QUERY_RELATED_GROUP,
			array(
				'name' => static::QUERY_CONTROL_PREFIX,
				'presets' => array( 'full' ),
				'exclude' => array( 'posts_per_page' ), // use this setting from Layout section
			)
		);

		$this->update_control(
			static::QUERY_CONTROL_PREFIX . '_post_type',
			array(
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'post' => __( 'Posts', 'cmsmasters-elementor' ),
					'projects' => __( 'Projects', 'cmsmasters-elementor' ),
					'collections' => __( 'Collections', 'cmsmasters-elementor' ),
					'product' => __( 'Product', 'cmsmasters-elementor' ),
				),
				'prefix_class' => 'cmsmasters-post-type-',
				'render_type' => 'template',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'query_include_cross_sell_show',
			array(
				'label' => __( 'Cross Sell', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'condition' => array(
					'blog_post_type' => 'product',
					'header_filter_product_outlet!' => 'yes',
				),
			)
		);

		$this->add_control(
			'header_filter_product_outlet',
			array(
				'label' => __( 'Only Outlet', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'condition' => array(
					'blog_post_type' => 'product',
					'query_include_cross_sell_show!' => 'yes',
				),
			)
		);

		$this->start_injection(
			array(
				'of' => static::QUERY_CONTROL_PREFIX . '_post_type',
				'at' => 'after',
			)
		);

		$this->add_control(
			'query_include_term_ids_show',
			array(
				'label' => __( 'Post Type Terms', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'current' => __( 'Current', 'cmsmasters-elementor' ),
					'custom' => __( 'Custom', 'cmsmasters-elementor' ),
				),
				'label_block' => false,
				'toggle' => false,
				'default' => 'current',
				'condition' => array( 'header_filter_show' => 'none' ),
			)
		);

		$this->add_control(
			'custom_pos_type_include',
			array(
				'label' => __( 'Custom Post Type', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'post' => __( 'Posts', 'cmsmasters-elementor' ),
					'projects' => __( 'Projects', 'cmsmasters-elementor' ),
					'collections' => __( 'Collections', 'cmsmasters-elementor' ),
					'product' => __( 'Product', 'cmsmasters-elementor' ),
				),
				'label_block' => true,
				'toggle' => false,
				'default' => 'projects',
				'condition' => array(
					'header_filter_show' => 'none',
					'query_include_term_ids_show' => 'custom',
				),
			)
		);

		$this->add_control(
			'custom_post_taxonamy',
			array(
				'label' => __( 'Taxonamy', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_post_taxonomies( 'post' ),
				'label_block' => false,
				'toggle' => false,
				'condition' => array(
					'header_filter_show' => 'none',
					'query_include_term_ids_show' => 'custom',
					'custom_pos_type_include' => 'post',
				),
			)
		);

		$this->add_control(
			'custom_project_taxonamy',
			array(
				'label' => __( 'Taxonamy', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_post_taxonomies( 'projects' ),
				'label_block' => false,
				'toggle' => false,
				'condition' => array(
					'header_filter_show' => 'none',
					'query_include_term_ids_show' => 'custom',
					'custom_pos_type_include' => 'projects',
				),
			)
		);

		$this->add_control(
			'custom_collections_taxonamy',
			array(
				'label' => __( 'Taxonamy', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_post_taxonomies( 'collections' ),
				'label_block' => false,
				'toggle' => false,
				'condition' => array(
					'header_filter_show' => 'none',
					'query_include_term_ids_show' => 'custom',
					'custom_pos_type_include' => 'collections',
				),
			)
		);

		$this->add_control(
			'custom_product_taxonamy',
			array(
				'label' => __( 'Taxonamy', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_post_taxonomies( 'product' ),
				'label_block' => false,
				'toggle' => false,
				'condition' => array(
					'header_filter_show' => 'none',
					'query_include_term_ids_show' => 'custom',
					'custom_pos_type_include' => 'product',
				),
			)
		);

		$this->end_injection();

		$this->start_injection(
			array(
				'of' => static::QUERY_CONTROL_PREFIX . '_include_term_ids',
				'at' => 'before',
			)
		);

		$this->add_control(
			'query_include_type',
			array(
				'label' => __( 'Selection Type', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'query_include_terms' => __( 'Terns', 'cmsmasters-elementor' ),
					'query_include_product' => __( 'Product', 'cmsmasters-elementor' ),
				),
				'label_block' => false,
				'toggle' => false,
				'default' => 'query_include_terms',
				'condition' => array(
					'blog_post_type' => 'product',
					'query_include_term_ids_show!' => 'custom',
					'header_filter_show' => 'none',
				),
			)
		);

		$this->add_control(
			'query_posts_in',
			array(
				'label' => __( 'Manual Selection', 'cmsmasters-elementor' ),
				'label_block' => true,
				'show_label' => false,
				'type' => CmsmastersControls::QUERY,
				'description' => __( 'Search & select entries to show.', 'cmsmasters-elementor' ),
				'options' => array(),
				'multiple' => true,
				'autocomplete' => array(
					'object' => Query_Manager::POST_OBJECT,
					'query' => array( 'post_type' => 'product' ),
					'display' => 'detailed',
				),
				'export' => false,
				'condition' => array(
					'blog_post_type' => 'product',
					'query_include_type' => 'query_include_product',
					'query_include_term_ids_show!' => 'custom',
					'header_filter_show' => 'none',
				),
			)
		);

		$this->update_control(
			static::QUERY_CONTROL_PREFIX . '_include_term_ids',
			array(
				'conditions' => array(
					'relation' => 'or',
					'terms' => array(
						array(
							'relation' => 'and',
							'terms' => array(
								array(
									'name' => 'blog_post_type',
									'operator' => '!=',
									'value' => 'product',
								),
								array(
									'name' => 'query_include_term_ids_show',
									'operator' => '!==',
									'value' => 'custom',
								),
								array(
									'name' => 'header_filter_show',
									'operator' => '===',
									'value' => 'none',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms' => array(
								array(
									'name' => 'blog_post_type',
									'operator' => '===',
									'value' => 'product',
								),
								array(
									'name' => 'query_include_type',
									'operator' => '===',
									'value' => 'query_include_terms',
								),
								array(
									'name' => 'query_include_term_ids_show',
									'operator' => '!==',
									'value' => 'custom',
								),
								array(
									'name' => 'header_filter_show',
									'operator' => '===',
									'value' => 'none',
								),
							),
						),
					),
				),
			)
		);

		$this->end_injection();

		$query_condicions = array(
			'relation' => 'and',
			'terms' => array(
				array(
					'name' => 'query_include_term_ids_show',
					'operator' => '!==',
					'value' => 'custom',
				),
				array(
					'name' => 'header_filter_show',
					'operator' => '===',
					'value' => 'none',
				),
			),
		);

		$this->update_control(
			static::QUERY_CONTROL_PREFIX . '_query_args',
			array( 'conditions' => $query_condicions )
		);

		$this->update_control(
			static::QUERY_CONTROL_PREFIX . '_author_query',
			array( 'conditions' => $query_condicions )
		);

		$this->update_control(
			static::QUERY_CONTROL_PREFIX . '_select_date',
			array( 'conditions' => $query_condicions )
		);

		$this->update_control(
			static::QUERY_CONTROL_PREFIX . '_filter_id',
			array( 'conditions' => $query_condicions )
		);

		$this->update_control(
			static::QUERY_CONTROL_PREFIX . '_orderby',
			array(
				'conditions' => array(
					'relation' => 'and',
					'terms' => array(
						array(
							'name' => 'query_include_term_ids_show',
							'operator' => '!==',
							'value' => 'custom',
						),
						array(
							'name' => 'header_filter_show',
							'operator' => '!==',
							'value' => 'multiple',
						),
						array(
							'name' => 'query_include_type',
							'operator' => '!==',
							'value' => 'query_include_product',
						),
					),
				),
			)
		);

		$this->update_control(
			static::QUERY_CONTROL_PREFIX . '_order',
			array(
				'conditions' => array(
					'relation' => 'and',
					'terms' => array(
						array(
							'name' => 'query_include_term_ids_show',
							'operator' => '!==',
							'value' => 'custom',
						),
						array(
							'name' => 'header_filter_show',
							'operator' => '!==',
							'value' => 'multiple',
						),
						array(
							'name' => 'query_include_type',
							'operator' => '!==',
							'value' => 'query_include_product',
						),
					),
				),
			)
		);

		$this->update_control(
			static::QUERY_CONTROL_PREFIX . '_selected_authors',
			array( 'conditions' => $query_condicions )
		);

		$this->end_controls_section();
	}

	/**
	 * Register advanced controls.
	 *
	 * Adds different input fields to allow the user to change and customize the classes settings.
	 *
	 * @since 1.0.0
	 */
	protected function register_advanced_section_controls() {
		$this->start_controls_section(
			'section_advanced',
			array(
				'label' => __( 'Advanced', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'blog_post_type' => 'current_query',
				),
			)
		);

		$this->add_control(
			'nothing_found_message',
			array(
				'label' => __( 'Nothing Found Message', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'It seems we can\'t find what you\'re looking for.', 'cmsmasters-elementor' ),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Register advanced controls.
	 *
	 * Adds different input fields to allow the user to change and customize the classes settings.
	 *
	 * @since 1.0.0
	 */
	protected function register_advanced_style_section_controls() {
		$this->start_controls_section(
			'section_nothing_found_style',
			array(
				'tab' => Controls_Manager::TAB_STYLE,
				'label' => __( 'Nothing Found Message', 'cmsmasters-elementor' ),
				'condition' => array(
					'nothing_found_message!' => '',
					'blog_post_type' => 'current_query',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'nothing_found_typography',
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__nothing-found',
			)
		);

		$this->add_control(
			'nothing_found_color',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__nothing-found' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name' => 'nothing_found_text_shadow',
				'selector' => '{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__nothing-found',
			)
		);

		$this->add_responsive_control(
			'nothing_found_text_shadow_text_align',
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
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-cmsmasters-theme-blog__nothing-found' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	public function get_post_taxonomies( $post_type ) {
		$taxonomies = get_object_taxonomies( $post_type );
		$taxonomy_labels = [];

		foreach ( $taxonomies as $taxonomy ) {
			$taxonomy_object = get_taxonomy( $taxonomy );

			if ( $taxonomy_object ) {
				$taxonomy_labels[ $taxonomy_object->name ] = $taxonomy_object->label;
			}
		}

		return $taxonomy_labels;
	}

	/**
	 * Prepare the WordPress Query.
	 *
	 * @since 1.0.0
	 */
	public function init_query() {
		/** @var WordpressModule $wordpress_module */
		$wordpress_module = WordpressModule::instance();

		$this->query = $wordpress_module->get_query_manager()->get_query(
			$this,
			static::QUERY_CONTROL_PREFIX/* 'posts' */,
			$this->get_query_vars()
		);
	}

	/**
	 * Get query variables for setting up the WordPress query loop.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_query_vars() {
		$posts_per_page = $this->get_posts_per_page();
		$query_vars = array();

		if ( $posts_per_page ) {
			$query_vars[ static::QUERY_CONTROL_PREFIX . '_posts_per_page' ] = $posts_per_page;
		}

		return array_merge( $this->query_vars, $query_vars );
	}

	/**
	 * Check if current query is archive.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_current_query() {
		return 'current_query' === $this->get_settings_for_display( 'blog_post_type' );
	}

	/**
	 * Get number of posts per page.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	protected function get_posts_per_page() {
		return (int) $this->get_settings_for_display( 'posts_per_page' );
	}

	/**
	 * Get blog css classes.
	 *
	 * @since 1.0.0
	 *
	 * @return string[]
	 */
	protected function get_blog_classes() {
		return array( 'cmsmasters-blog', $this->get_name() );
	}

	/**
	 * Render blog.
	 *
	 * @since 1.0.0
	 */
	abstract protected function render_blog();

	/**
	 * Get the WordPress query.
	 *
	 * @since 1.0.0
	 *
	 * @return \WP_Query
	 */
	public function get_query() {
		return $this->query;
	}

	/**
	 * Prepares for post display.
	 *
	 * @since 1.0.0
	 */
	public function prepare_the_post() {
		$query = $this->get_query();

		$query->the_post();

		$this->add_render_attribute(
			'post',
			array(
				'id' => 'post-' . get_the_ID(),
				'class' => get_post_class( 'elementor-widget-cmsmasters-theme-blog__post' ),
			),
			null,
			true
		);
	}

	/**
	 * Render the post.
	 *
	 * @since 1.0.0
	 */
	protected function render_post() {
		$this->render_post_open();

		$this->render_post_inner();

		$this->render_post_close();
	}

	/**
	 * Start post rendering.
	 *
	 * @since 1.0.0
	 */
	public function render_post_open() {
		echo '<article ' . $this->get_render_attribute_string( 'post' ) . '>' .
			'<div class="elementor-widget-cmsmasters-theme-blog__post-inner">';
	}

	/**
	 * Render all post insides.
	 *
	 * @since 1.0.0
	 */
	abstract protected function render_post_inner();

	/**
	 * End post rendering.
	 *
	 * @since 1.0.0
	 */
	public function render_post_close() {
			echo '</div>' .
		'</article>';
	}

	/**
	 * Wrapper for posts.
	 *
	 * @since 1.0.0
	 */
	protected function render_posts() {
		echo '<div class="elementor-widget-cmsmasters-theme-blog__posts-wrap">' .
			'<div class="elementor-widget-cmsmasters-theme-blog__posts">';

				$this->render_posts_inner();

			echo '</div>' .
		'</div>';
	}

	protected function render_custom_post( $taxonamy ) {
		$settings = $this->get_settings_for_display();

		if ( empty( $taxonamy ) ) {
			return;
		}

		$current_post_categories = wp_get_post_terms( get_the_ID(), $taxonamy, array( 'fields' => 'ids' ) );

		if ( ! empty( $current_post_categories ) ) {
			$args = array(
				'post_type' => $settings[ 'blog_post_type' ],
				'posts_per_page' => $settings['posts_per_page'],
				'tax_query' => array(
					array(
						'taxonomy' => $taxonamy,
						'field' => 'id',
						'terms' => $current_post_categories,
					),
				),
			);

			$query = new \WP_Query( $args );

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();

					$post_id = get_the_ID();

					$this->add_render_attribute(
						'post',
						array(
							'id' => 'post-' . $post_id,
							'class' => get_post_class( 'elementor-widget-cmsmasters-theme-blog__post' ),
						),
						null,
						true
					);

					$this->render_post();

					$this->posts_popup_id[] = $post_id;
				}
			}
		}
	}

	protected function render_manual_product() {
		$settings = $this->get_settings_for_display();

		$query_posts_in = ( isset( $settings['query_posts_in'] ) ? $settings['query_posts_in'] : '' );

		if ( ! $query_posts_in ) {
			return;
		}

		$args = array(
			'post_type'      => $settings['blog_post_type'],
			'posts_per_page' => $settings['posts_per_page'],
			'post__in'       => $query_posts_in,
		);

		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				$post_id = get_the_ID();

				$this->add_render_attribute(
					'post',
					array(
						'id' => 'post-' . $post_id,
						'class' => get_post_class( 'elementor-widget-cmsmasters-theme-blog__post' ),
					),
					null,
					true
				);

				$this->render_post();

				$this->posts_popup_id[] = $post_id;
			}

			wp_reset_postdata();
		}
	}

	/**
	 * Wrapper insides for posts.
	 *
	 * @since 1.0.0
	 */
	protected function render_posts_inner() {
		$settings = $this->get_settings_for_display();

		global $product;

		$product_cross_sell = ( isset( $settings['query_include_cross_sell_show'] ) ? $settings['query_include_cross_sell_show'] : '' );
		$query_include_type = ( isset( $settings['query_include_type'] ) ? $settings['query_include_type'] : '' );

		if ( 'custom' === $settings['query_include_term_ids_show'] ) {
			if ( 'post' === $settings['custom_pos_type_include'] ) {
				$this->render_custom_post( $settings['custom_post_taxonamy'] );
			} else if ( 'projects' === $settings['custom_pos_type_include'] ) {
				$this->render_custom_post( $settings['custom_project_taxonamy'] );
			} else if ( 'collections' === $settings['custom_pos_type_include'] ) {
				$this->render_custom_post( $settings['custom_collections_taxonamy'] );
			} else if ( 'product' === $settings['custom_pos_type_include'] ) {
				$this->render_custom_post( $settings['custom_product_taxonamy'] );
			}
		} elseif ( 'product' === $settings['blog_post_type'] && $product_cross_sell && ! empty( $product->get_cross_sell_ids() ) ) {
			$args = array(
				'post_type'      => $settings['blog_post_type'],
				'posts_per_page' => $settings['posts_per_page'],
				'post__in'       => $product->get_cross_sell_ids(),
			);

			$query = new \WP_Query( $args );

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();

					$post_id = get_the_ID();

					$this->add_render_attribute(
						'post',
						array(
							'id' => 'post-' . $post_id,
							'class' => get_post_class( 'elementor-widget-cmsmasters-theme-blog__post' ),
						),
						null,
						true
					);

					$this->render_post();

					$this->posts_popup_id[] = $post_id;
				}

				wp_reset_postdata();
			}
		} elseif ( 'product' === $settings['blog_post_type'] && $query_include_type && 'query_include_product' === $query_include_type ) {
			$this->render_manual_product();
		} else {
			while ( $this->get_query()->have_posts() ) {
				$this->prepare_the_post();

				$post_id = get_the_ID();
				$product_outlet = ( isset( $settings['header_filter_product_outlet'] ) ? $settings['header_filter_product_outlet'] : '' );

				if ( 'product' === $settings['blog_post_type'] ) {
					if ( $product_outlet ) {
						if ( has_term( 'outlet', 'product_tag', get_the_ID() ) ) {
							$this->render_post();

							$this->posts_popup_id[] = $post_id;
						}
					} elseif ( ! $product_outlet ) {
						if ( ! has_term( 'outlet', 'product_tag', get_the_ID() ) ) {
							$this->render_post();
						}
					}

					// if ( $product->is_type( 'variable' ) ) {
					// 	$price = (int) $product->get_variation_price();
					// } else {
					// 	$price = (int) $product->get_price();
					// }

					// if ( $product_outlet ) {
					// 	if ( $price ) {
					// 		$this->render_post();
					// 	}
					// } else {
					// 	if ( ! $price ) {
					// 		$this->render_post();
					// 	}
					// }
				} else {
					$this->render_post();
				}
			}
		}
	}

	/**
	 * Sets up custom WordPress query.
	 *
	 * @param array $query_vars
	 *
	 * @since 1.0.0
	 */
	protected function set_query_vars( array $query_vars = array() ) {
		if ( empty( $query_vars ) ) {
			return;
		}

		$this->query_vars = array_merge( $query_vars, $this->query_vars );
	}

	/**
	 * Render.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		$this->init_query();

		$wp_query = $this->get_query();

		if ( ! $wp_query->found_posts ) {
			if ( $this->is_current_query() ) {
				echo '<h4 class="elementor-widget-cmsmasters-theme-blog__nothing-found">' .
					esc_html( $this->get_settings_fallback( 'nothing_found_message' ) ) .
				'</h4>';
			} else {
				Utils::render_alert( esc_html__( 'Posts not found!', 'cmsmasters-elementor' ) );
			}

			return;
		}

		$this->add_render_attribute(
			array(
				'blog' => array(
					'class' => $this->get_blog_classes(),
				),
			)
		);

		echo '<div ' . $this->get_render_attribute_string( 'blog' ) . '>';

		$this->render_blog();

		echo '</div>';

		wp_reset_postdata();
	}

	/**
	 * Render widget plain content.
	 *
	 * Save generated HTML to the database as plain content.
	 *
	 * @since 1.0.0
	 */
	public function render_plain_content() {}

}
