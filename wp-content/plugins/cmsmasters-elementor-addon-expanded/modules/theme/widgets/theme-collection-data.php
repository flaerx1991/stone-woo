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
 * Addon theme collection data widget.
 *
 * Addon widget that displays theme collection data.
 *
 * @since 1.0.0
 */
class Theme_Collection_Data extends Base_Widget {

	/**
	 * Get widget name.
	 *
	 * Retrieve theme collection data widget name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'cmsmasters-theme-collection-data';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve theme collection data widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Theme Collection Data', 'cmsmasters-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve theme collection data widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'cmsicon-meta-data';
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
			'categories',
			'collection',
		);
	}

	public function get_widget_class() {
		return 'elementor-widget-cmsmasters-theme-collection-data';
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
			'section_theme_collection_data',
			array( 'label' => __( 'Theme Collection Data', 'cmsmasters-elementor' ) )
		);

		$this->add_control(
			'theme_collection_data_type',
			array(
				'label' => __( 'Type', 'cmsmasters-elementor' ),
				'label_block' => false,
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'post' => __( 'Current Post', 'cmsmasters-elementor' ),
					'list' => __( 'List', 'cmsmasters-elementor' ),
					'floors' => __( 'Current Floors', 'cmsmasters-elementor' ),
				),
				'default' => 'post',
			)
		);

		$this->add_control(
			'theme_collection_data_floors_icon_heading',
			array(
				'label' => __( 'Icons', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'theme_collection_data_type' => 'floors' ),
			)
		);

		foreach ( $this->get_collection_terms() as $floor => $value ) {
			$this->add_control(
				"theme_collection_data_floors_{$floor}_icon",
				array(
					'label' => __( "{$value}", 'cmsmasters-elementor' ),
					'label_block' => false,
					'type' => Controls_Manager::ICONS,
					'skin' => 'inline',
					'condition' => array( 'theme_collection_data_type' => 'floors' ),
				)
			);
		}
		$this->add_control(
			'theme_collection_data_list_taxonamy',
			array(
				'label' => __( 'Taxonamy', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_post_taxonomies(),
				'label_block' => false,
				'toggle' => false,
				'condition' => array( 'theme_collection_data_type' => 'list' ),
			)
		);

		$this->end_controls_section();
	}

	public function get_collection_terms( $taxonomy = 'collection_floors' ) {
		$terms = get_terms( $taxonomy, array( 'hide_empty' => true ) );

		$terms_labels = [];

		foreach ( $terms as $term ) {
			$terms_labels[ $term->slug ] = $term->name;
		}

		return $terms_labels;
	}

	public function get_post_taxonomies() {
		$taxonomies = get_object_taxonomies( 'collections' );
		$taxonomy_labels = [];
	
		foreach ( $taxonomies as $taxonomy ) {
			$taxonomy_object = get_taxonomy( $taxonomy );
	
			if ( $taxonomy_object ) {
				$taxonomy_labels[ $taxonomy_object->name ] = $taxonomy_object->label;
			}
		}
	
		return $taxonomy_labels;
	}

	public function get_collection_floors() {
		$settings = $this->get_settings_for_display();

		echo '<div class="' . $this->get_widget_class() . '__floors">';

		foreach ( $this->get_collection_terms() as $term ) {
			$slug = strtolower( str_replace( ' ', '-', trim($term) ) );

			echo '<div class="' . $this->get_widget_class() . '__floors-item">';
				$icon = ( isset( $settings["theme_collection_data_floors_{$slug}_icon"]['value'] ) ? $settings["theme_collection_data_floors_{$slug}_icon"]['value'] : '' );

				if ( ! empty( $icon ) ) {
					Icons_Manager::render_icon( $settings["theme_collection_data_floors_{$slug}_icon"] );
				}

				echo '<div class="' . $this->get_widget_class() . '__floors-item-text">' .
					esc_html( $term ) .
				'</div>' .
			'</div>';
		}

		echo '</div>';
	}

	public function get_collection_list() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['theme_collection_data_list_taxonamy'] ) ) {
			return;
		}

		echo '<div class="' . $this->get_widget_class() . '__list">';

		$taxonamy = $settings['theme_collection_data_list_taxonamy'];

		foreach ( $this->get_collection_terms( $taxonamy ) as $term ) {
			$slug = strtolower( str_replace( ' ', '-', trim($term) ) );
			$term_link = get_term_link( $slug, $taxonamy );

			echo '<a href="' . $term_link . '" class="' . $this->get_widget_class() . '__list-item">' .
				'<div class="' . $this->get_widget_class() . '__list-item-text">' .
					esc_html( $term ) .
				'</div>' .
			'</a>';
		}

		echo '</div>';
	}

	public function get_collection_product_count() {
		$terms = wp_get_post_terms( get_the_ID(), 'collection', array( 'fields' => 'ids' ) );

		$args = array(
			'post_type' => 'product',
			'tax_query' => array(
				array(
					'taxonomy' => 'collection',
					'terms' => $terms,
					'field' => 'id',
					'operator' => 'IN',
					'hide_empty' => false,
				),
			),
		);

		$query = new \WP_Query( $args );

		$count = $query->found_posts;

		$label = ( 1 === $count ? 'product' : 'products' );

		echo $count . ' ' . $label;
	}

	public function get_collection_post() {
		$terms = wp_get_post_terms( get_the_ID(), 'collection' );
		$selected_term = ! empty( $terms ) ? $terms[0] : null;

		echo '<div class="' . $this->get_widget_class() . '__post">';

		if ( ! empty( $selected_term ) ) {
			$args = array(
				'post_type' => 'collections',
				'posts_per_page' => 1,
				'tax_query' => array(
					array(
						'taxonomy' => 'collection',
						'field' => 'name',
						'terms' => $selected_term->name,
					),
				),
			);
			
			$query = new \WP_Query( $args );
			
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();

					$image_overlay = get_field( 'collection_image_overlay', get_the_ID() );

					if ( $image_overlay ) {
						echo '<figure class="' . $this->get_widget_class() . '__post-image">' .
							wp_get_attachment_image( $image_overlay, 'thumbnail' ) .
						'</figure>';
					}

					echo '<div class="' . $this->get_widget_class() . '__post-inner">' .
						'<h4 class="' . $this->get_widget_class() . '__post-title">' .
							'<a href="' . esc_url( get_permalink() ) . '">' .
								esc_html( get_the_title() ) .
							'</a>' .
						'</h4>' .
						'<div class="' . $this->get_widget_class() . '__post-categories-wrap">';
							$terms = wp_get_post_terms( get_the_ID(), 'collection_category' );
							$category_term = ! empty( $terms ) ? $terms[0] : null;
							$category_name = ! empty( $category_term ) ? $category_term->name : '';
							$category_link = ! empty( $category_term ) ? get_term_link( $category_term ) : '';

							if ( $category_name ) {
								echo '<div class="' . $this->get_widget_class() . '__post-categories">' .
									'<a href="' . esc_url( $category_link ) . '">' .
										esc_html( $category_name ) .
									'</a>' .
								'</div>' .
								'<span class="' . $this->get_widget_class() . '__post-categories-point"></span>';
							}

							echo '<div class="' . $this->get_widget_class() . '__post-product-count">';
								$this->get_collection_product_count();
							echo '</div>' .
						'</div>' .
					'</div>';
				}

				wp_reset_postdata();
			}
		}

		echo '</div>';
	}

	/**
	 * Render theme collection data widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( 'floors' === $settings['theme_collection_data_type'] ) {
			$this->get_collection_floors();
		} elseif ( 'list' === $settings['theme_collection_data_type'] ) {
			$this->get_collection_list();
		} elseif ( 'post' === $settings['theme_collection_data_type'] ) {
			$this->get_collection_post();
		}
	}

}
