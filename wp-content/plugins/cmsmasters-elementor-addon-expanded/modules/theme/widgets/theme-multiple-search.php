<?php
namespace CmsmastersElementor\Modules\Theme\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CmsmastersElementor\Base\Base_Widget;
use CmsmastersElementor\Controls_Manager as CmsmastersControls;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Addon theme multiple search widget.
 *
 * Addon widget that displays theme multiple search.
 *
 * @since 1.0.0
 */
class Theme_Multiple_Search extends Base_Widget {

	/**
	 * Get widget name.
	 *
	 * Retrieve theme multiple search widget name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'cmsmasters-theme-multiple-search';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve theme multiple search widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Theme Multiple Search', 'cmsmasters-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve theme multiple search widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'cmsicon-search';
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
			'theme',
			'multiple',
			'search',
		);
	}

	public function get_widget_class() {
		return 'elementor-widget-cmsmasters-theme-multiple-search';
	}

	public function get_widget_selector() {
		return '.' . $this->get_widget_class();
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 */
	protected function register_controls() {
		$widget_selector = $this->get_widget_selector();

		$this->start_controls_section(
			'section_theme_multiple_search',
			array( 'label' => __( 'Theme Multiple Search', 'cmsmasters-elementor' ) )
		);

		$this->add_control(
			'post_type',
			array(
				'label' => __( 'Post Types', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_post_types(),
				'label_block' => false,
				'toggle' => false,
			)
		);

		$this->add_control(
			'post_multiple_items',
			array(
				'label' => __( 'Filter Item', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::SELECTIZE,
				'options' => $this->get_post_taxonomies( 'post' ),
				'multiple' => true,
				'condition' => array( 'post_type' => 'post' ),
			)
		);

		$this->add_control(
			'projects_multiple_items',
			array(
				'label' => __( 'Filter Item', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::SELECTIZE,
				'options' => $this->get_post_taxonomies( 'projects' ),
				'multiple' => true,
				'condition' => array( 'post_type' => 'projects' ),
			)
		);

		$this->add_control(
			'collections_multiple_items',
			array(
				'label' => __( 'Filter Item', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::SELECTIZE,
				'options' => $this->get_post_taxonomies( 'collections' ),
				'multiple' => true,
				'condition' => array( 'post_type' => 'collections' ),
			)
		);

		$this->add_control(
			'item_column',
			array(
				'label' => __( 'Column', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'selectors' => array(
					'{{WRAPPER}}' => '--item-column: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'select_icon',
			array(
				'label' => esc_html__( 'Select Icon', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => false,
				'skin' => 'inline',
			)
		);

		$this->add_control(
			'button_icon',
			array(
				'label' => esc_html__( 'Button Icon', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => false,
				'skin' => 'inline',
			)
		);

		foreach ( $this->get_post_taxonomies( 'post' ) as $key => $value ) {
			$new_value = ucwords( str_replace( array( '_', '-' ), array( ' ', ' ' ), $key ) );

			$this->add_control(
				"custom_{$key}_labels_heading",
				array(
					'label' => esc_html( $new_value, 'cmsmasters-elementor' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => array( 'post_type' => 'post' ),
				)
			);

			$this->add_control(
				"{$key}_custom_label",
				array(
					'label' => __( 'New Label', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => esc_html( $new_value ),
					'label_block' => true,
					'ai' => array( 'active' => false ),
					'condition' => array( 'post_type' => 'post' ),
				)
			);

			$this->add_control(
				"{$key}_custom_select",
				array(
					'label' => __( 'New Select Label', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => esc_html( 'All ' . $new_value ),
					'label_block' => true,
					'ai' => array( 'active' => false ),
					'condition' => array( 'post_type' => 'post' ),
				)
			);
		}

		$existing_controls = $this->get_controls();

		foreach ( $this->get_post_taxonomies( 'projects' ) as $key => $value ) {
			$new_value = ucwords( str_replace( array( '_', '-' ), array( ' ', ' ' ), $key ) );

			$heading_control_key = "custom_{$key}_labels_heading";
			$text_control_key = "{$key}_custom_label";
			$select_control_key = "{$key}_custom_select";

			if ( ! array_key_exists( $text_control_key, $existing_controls ) ) {
				$this->add_control(
					"custom_{$key}_labels_heading",
					array(
						'label' => esc_html( $new_value, 'cmsmasters-elementor' ),
						'type' => Controls_Manager::HEADING,
						'separator' => 'before',
						'condition' => array( 'post_type' => 'projects' ),
					)
				);
			}

			if ( ! array_key_exists( $text_control_key, $existing_controls ) ) {
				$this->add_control(
					"{$key}_custom_label",
					array(
						'label' => __( 'New Label', 'cmsmasters-elementor' ),
						'type' => Controls_Manager::TEXT,
						'placeholder' => esc_html( $new_value ),
						'label_block' => true,
						'ai' => array( 'active' => false ),
						'condition' => array( 'post_type' => 'projects' ),
					)
				);
			}

			if ( ! array_key_exists( $text_control_key, $existing_controls ) ) {
				$this->add_control(
					"{$key}_custom_select",
					array(
						'label' => __( 'New Select Label', 'cmsmasters-elementor' ),
						'type' => Controls_Manager::TEXT,
						'placeholder' => esc_html( 'All ' . $new_value ),
						'label_block' => true,
						'ai' => array( 'active' => false ),
						'condition' => array( 'post_type' => 'projects' ),
					)
				);
			}
		}

		$existing_controls = $this->get_controls();

		foreach ( $this->get_post_taxonomies( 'collections' ) as $key => $value ) {
			$new_value = ucwords( str_replace( array( '_', '-' ), array( ' ', ' ' ), $key ) );

			$heading_control_key = "custom_{$key}_labels_heading";
			$text_control_key = "{$key}_custom_label";
			$select_control_key = "{$key}_custom_select";

			if ( ! array_key_exists( $heading_control_key, $existing_controls ) ) {
				$this->add_control(
					$heading_control_key,
					array(
						'label' => esc_html( $new_value, 'cmsmasters-elementor' ),
						'type' => Controls_Manager::HEADING,
						'separator' => 'before',
						'condition' => array( 'post_type' => 'collections' ),
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
						'condition' => array( 'post_type' => 'collections' ),
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
						'condition' => array( 'post_type' => 'collections' ),
					)
				);
			}
		}

		$this->end_controls_section();
	}

	public function get_post_types() {
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		$post_type_labels = [ '' => 'All' ];

		foreach ( $post_types as $post_type ) {
			$post_type_labels[ $post_type->name ] = $post_type->name;
		}

		return $post_type_labels;
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

	// public function get_collection_terms( $taxonomy = 'collection_floors' ) {
	// 	$terms = get_terms( $taxonomy, array( 'hide_empty' => true ) );

	// 	$terms_labels = [];

	// 	foreach ( $terms as $term ) {
	// 		$terms_labels[ $term->slug ] = $term->name;
	// 	}

	// 	return $terms_labels;
	// }

	// public function get_post_taxonomies() {
	// 	$taxonomies = get_object_taxonomies( 'collections' );
	// 	$taxonomy_labels = [];
	
	// 	foreach ( $taxonomies as $taxonomy ) {
	// 		$taxonomy_object = get_taxonomy( $taxonomy );
	
	// 		if ( $taxonomy_object ) {
	// 			$taxonomy_labels[ $taxonomy_object->name ] = $taxonomy_object->label;
	// 		}
	// 	}
	
	// 	return $taxonomy_labels;
	// }

	// public function get_collection_floors() {
	// 	$settings = $this->get_settings_for_display();

	// 	echo '<div class="' . $this->get_widget_class() . '__floors">';

	// 	foreach ( $this->get_collection_terms() as $term ) {
	// 		$slug = strtolower( str_replace( ' ', '-', trim($term) ) );

	// 		echo '<div class="' . $this->get_widget_class() . '__floors-item">';
	// 			$icon = ( isset( $settings["theme_collection_data_floors_{$slug}_icon"]['value'] ) ? $settings["theme_collection_data_floors_{$slug}_icon"]['value'] : '' );

	// 			if ( ! empty( $icon ) ) {
	// 				Icons_Manager::render_icon( $settings["theme_collection_data_floors_{$slug}_icon"] );
	// 			}

	// 			echo '<div class="' . $this->get_widget_class() . '__floors-item-text">' .
	// 				esc_html( $term ) .
	// 			'</div>' .
	// 		'</div>';
	// 	}

	// 	echo '</div>';
	// }

	// public function get_collection_list() {
	// 	$settings = $this->get_settings_for_display();

	// 	if ( empty( $settings['theme_collection_data_list_taxonamy'] ) ) {
	// 		return;
	// 	}

	// 	echo '<div class="' . $this->get_widget_class() . '__list">';

	// 	$taxonamy = $settings['theme_collection_data_list_taxonamy'];

	// 	foreach ( $this->get_collection_terms( $taxonamy ) as $term ) {
	// 		$slug = strtolower( str_replace( ' ', '-', trim($term) ) );
	// 		$term_link = get_term_link( $slug, $taxonamy );

	// 		echo '<a href="' . $term_link . '" class="' . $this->get_widget_class() . '__list-item">' .
	// 			'<div class="' . $this->get_widget_class() . '__list-item-text">' .
	// 				esc_html( $term ) .
	// 			'</div>' .
	// 		'</a>';
	// 	}

	// 	echo '</div>';
	// }

	// public function get_collection_product_count() {
	// 	$terms = wp_get_post_terms( get_the_ID(), 'collection', array( 'fields' => 'ids' ) );

	// 	$args = array(
	// 		'post_type' => 'product',
	// 		'tax_query' => array(
	// 			array(
	// 				'taxonomy' => 'collection',
	// 				'terms' => $terms,
	// 				'field' => 'id',
	// 				'operator' => 'IN',
	// 				'hide_empty' => false,
	// 			),
	// 		),
	// 	);

	// 	$query = new \WP_Query( $args );

	// 	$count = $query->found_posts;

	// 	$label = ( 1 === $count ? 'product' : 'products' );

	// 	echo $count . ' ' . $label;
	// }

	// public function get_collection_post() {
	// 	$terms = wp_get_post_terms( get_the_ID(), 'collection' );
	// 	$selected_term = ! empty( $terms ) ? $terms[0] : null;

	// 	echo '<div class="' . $this->get_widget_class() . '__post">';

	// 	if ( ! empty( $selected_term ) ) {
	// 		$args = array(
	// 			'post_type' => 'collections',
	// 			'posts_per_page' => 1,
	// 			'tax_query' => array(
	// 				array(
	// 					'taxonomy' => 'collection',
	// 					'field' => 'name',
	// 					'terms' => $selected_term->name,
	// 				),
	// 			),
	// 		);
			
	// 		$query = new \WP_Query( $args );
			
	// 		if ( $query->have_posts() ) {
	// 			while ( $query->have_posts() ) {
	// 				$query->the_post();

	// 				$image_overlay = get_field( 'collection_image_overlay', get_the_ID() );

	// 				if ( $image_overlay ) {
	// 					echo '<figure class="' . $this->get_widget_class() . '__post-image">' .
	// 						wp_get_attachment_image( $image_overlay, 'thumbnail' ) .
	// 					'</figure>';
	// 				}

	// 				echo '<div class="' . $this->get_widget_class() . '__post-inner">' .
	// 					'<h4 class="' . $this->get_widget_class() . '__post-title">' .
	// 						'<a href="' . esc_url( get_permalink() ) . '">' .
	// 							esc_html( get_the_title() ) .
	// 						'</a>' .
	// 					'</h4>' .
	// 					'<div class="' . $this->get_widget_class() . '__post-categories-wrap">';
	// 						$terms = wp_get_post_terms( get_the_ID(), 'collection_category' );
	// 						$category_term = ! empty( $terms ) ? $terms[0] : null;
	// 						$category_name = ! empty( $category_term ) ? $category_term->name : '';
	// 						$category_link = ! empty( $category_term ) ? get_term_link( $category_term ) : '';

	// 						if ( $category_name ) {
	// 							echo '<div class="' . $this->get_widget_class() . '__post-categories">' .
	// 								'<a href="' . esc_url( $category_link ) . '">' .
	// 									esc_html( $category_name ) .
	// 								'</a>' .
	// 							'</div>' .
	// 							'<span class="' . $this->get_widget_class() . '__post-categories-point"></span>';
	// 						}

	// 						echo '<div class="' . $this->get_widget_class() . '__post-product-count">';
	// 							$this->get_collection_product_count();
	// 						echo '</div>' .
	// 					'</div>' .
	// 				'</div>';
	// 			}

	// 			wp_reset_postdata();
	// 		}
	// 	}

	// 	echo '</div>';
	// }

	/**
	 * Render theme multiple search widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$post_type = ( isset( $settings['post_type'] ) ? $settings['post_type'] : 'post' );

		if ( empty( $post_type ) ) {
			return;
		}

		$post_taxonomy = $settings["{$post_type}_multiple_items"];

		if ( empty( $post_taxonomy ) ) {
			return;
		}

		echo '<div class="elementor-widget-cmsmasters-theme-multiple-search__header cmsmasters-filter-nav-multiple">' .
			'<ul class="elementor-widget-cmsmasters-theme-multiple-search__multiple-taxonomy-list">';

				foreach ( $post_taxonomy as $index => $taxonomy ) {
					$is_last_element = ( $index === count( $post_taxonomy ) - 1 );

					echo '<li class="elementor-widget-cmsmasters-theme-multiple-search__multiple-taxonomy-list-item' . ( $is_last_element ? ' last-taxonomy' : '' ) . '" data-taxonomy-id="' . $taxonomy . '">' .
						'<span class="elementor-widget-cmsmasters-theme-multiple-search__multiple-taxonomy-list-item-trigger-wrap">' .
							'<span class="elementor-widget-cmsmasters-theme-multiple-search__multiple-taxonomy-list-item-label">';

							$default_taxonomy_value = esc_html( ucwords( str_replace( array( '_', '-' ), array( ' ', ' ' ), $taxonomy ) ) );

							if ( ! empty( $settings["{$taxonomy}_custom_label"] ) ) {
								echo esc_html( $settings["{$taxonomy}_custom_label"] );
							} else {
								echo $default_taxonomy_value;
							}

							echo '</span>';

							if ( ! empty( $settings["{$taxonomy}_custom_select"] ) ) {
								$select_value = esc_html( $settings["{$taxonomy}_custom_select"] );
							} else {
								$select_value = esc_html__( 'ALL ', 'cmsmasters-elementor' ) . $default_taxonomy_value;
							}

							echo '<span class="elementor-widget-cmsmasters-theme-multiple-search__multiple-taxonomy-list-item-trigger default-value">' .
								'<span class="elementor-widget-cmsmasters-theme-multiple-search__multiple-taxonomy-list-item-trigger-value" data-default="' . $select_value . '">' .
									$select_value .
								'</span>';

								Icons_Manager::render_icon( $settings['select_icon'] );

							echo '</span>' .
						'</span>';

						if ( ! empty( $this->get_post_categories( $taxonomy ) ) ) {
							echo '<ul class="elementor-widget-cmsmasters-theme-multiple-search__multiple-category-list">';

							foreach ( $this->get_post_categories( $taxonomy ) as $key => $category ) {
								echo '<li class="elementor-widget-cmsmasters-theme-multiple-search__multiple-category-list-item" data-category-id="' . $key . '">' .
									'<input class="elementor-widget-cmsmasters-theme-multiple-search__multiple-category-list-item-checkbox" type="checkbox" id="' . esc_attr( $key ) . '" name="' . esc_attr( $category ) . '" value="' . esc_attr( $category ) . '">' .
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

							echo '</ul>';
						}

					echo '</li>';
				}

				echo '<li class="elementor-widget-cmsmasters-theme-multiple-search__multiple-taxonomy-list-item item-button">' .
					'<div class="elementor-widget-cmsmasters-theme-multiple-search__multiple-category-list-button">' .
						'<span class="elementor-widget-cmsmasters-theme-multiple-search__multiple-category-list-button-label">' .
							esc_html( 'Advance search', 'cmsmasters-elementor' ) .
						'</span>' .
						'<span class="elementor-widget-cmsmasters-theme-multiple-search__multiple-category-list-button-icon">';
							Icons_Manager::render_icon( $settings['button_icon'] );
						echo '</span>';
					'</div>' .
				'</li>' .
			'</ul>' .
		'</div>';
	}

}
