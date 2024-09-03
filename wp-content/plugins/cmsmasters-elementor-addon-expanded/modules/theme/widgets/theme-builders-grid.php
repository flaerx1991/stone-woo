<?php
namespace CmsmastersElementor\Modules\Theme\Widgets;

use CmsmastersElementor\Base\Base_Widget;
use CmsmastersElementor\Controls_Manager as CmsmastersControls;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Theme Builders Grid widget.
 *
 * @since 1.0.0
 */
class Theme_Builders_Grid extends Base_Widget {

	/**
	 * Get widget name.
	 *
	 * Retrieve theme builders grid widget name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'cmsmasters-theme-builders-grid';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Theme Builders Grid', 'cmsmasters-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'cmsicon-products';
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
			'builder',
			'firm',
			'grid',
		);
	}

	public function get_widget_class() {
		return 'elementor-widget-cmsmasters-theme-builders-grid';
	}

	public function get_widget_selector() {
		return '.' . $this->get_widget_class();
	}

	protected function register_controls() {
		$this->register_theme_builders_slider_content_controls();

		$this->register_theme_builders_slider_style_controls();
	}

	protected function register_theme_builders_slider_content_controls() {
		$this->start_controls_section(
			'theme_builders_slider_content',
			array(
				'label' => esc_html__( 'Theme Builders Grid', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'build_firms_type',
			array(
				'label' => __( 'Type', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'random' => __( 'Random', 'cmsmasters-elementor' ),
					'manual_selection' => __( 'Manual Selection', 'cmsmasters-elementor' ),
				),
				'label_block' => false,
				'toggle' => false,
				'default' => 'random',
			)
		);

		$this->add_control(
			'build_firms_quantity',
			array(
				'label' => __( 'Quantity', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 6,
				'min' => 1,
				'condition' => array( 'build_firms_type' => 'random' ),
			)
		);

		$project_build_terms = get_terms( array(
			'taxonomy' => 'project_design_build_firms',
			'hide_empty' => false,
		) );
		
		$project_builds = array();
		
		if ( ! empty( $project_build_terms ) && ! is_wp_error( $project_build_terms ) ) {
			foreach ( $project_build_terms as $term ) {
				$project_builds[ $term->term_id ] = $term->name;
			}
		}

		$this->add_control(
			'build_firms',
			array(
				'label' => __( 'Build Firms', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::SELECTIZE,
				'label_block' => true,
				'options' => $project_builds,
				'multiple' => true,
				'control_options' => array(
					'plugins' => array(
						'remove_button',
						'drag_drop',
					),
				),
				'condition' => array( 'build_firms_type' => 'manual_selection' ),
			)
		);

		$this->add_responsive_control(
			'build_firms_columns',
			array(
				'label' => esc_html__( 'Columns', 'cmsmasters-elementor' ),
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
				),
				'default' => '6',
				'tablet_default' => '3',
				'mobile_default' => '2',
				'render_type' => 'template',
				'selectors' => array(
					'{{WRAPPER}}' => '--build-firms-columns: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name' => 'build_firms_logo',
				'label' => __( 'Logo Size', 'cmsmasters-elementor' ),
				'default' => 'full',
			)
		);

		$this->end_controls_section();
	}

	protected function register_theme_builders_slider_style_controls() {
		$this->start_controls_section(
			'theme_builders_slider_style',
			array(
				'label' => __( 'General', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'build_firms_item_bd_color',
			array(
				'label' => __( 'Builder Border Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--build-firms-builder-border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'build_firms_image_heading',
			array(
				'label' => __( 'Image', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'build_firms_image_height',
			array(
				'label' => __( 'Height', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 60,
						'max' => 150,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--build-firms-image-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'build_firms_image_padding',
			array(
				'label' => __( 'Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors' => array(
					'{{WRAPPER}}' => '--build-firms-image-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'build_firms_count_heading',
			array(
				'label' => __( 'Count', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'build_firms_count_typography',
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--build-firms-count-font-family: {{VALUE}};',
						),
					),
					'font_size' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--build-firms-count-font-size: {{SIZE}}{{UNIT}};',
						),
					),
					'font_weight' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--build-firms-count-font-weight: {{VALUE}};',
						),
					),
					'text_transform' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--build-firms-count-text-transform: {{VALUE}};',
						),
					),
					'font_style' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--build-firms-count-font-style: {{VALUE}};',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--build-firms-count-text-decoration: {{VALUE}}',
						),
					),
					'line_height' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--build-firms-count-line-height: {{SIZE}}{{UNIT}};',
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--build-firms-count-letter-spacing: {{SIZE}}{{UNIT}};',
						),
					),
					'word_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--build-firms-count-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'build_firms_count_color',
			array(
				'label' => esc_html__( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--build-firms-count-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'build_firms_count_bd_color',
			array(
				'label' => __( 'Border Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--build-firms-count-border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'build_firms_count_padding',
			array(
				'label' => __( 'Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors' => array(
					'{{WRAPPER}}' => '--build-firms-count-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'build_firms_count_border_type',
			array(
				'label' => esc_html__( 'Border Type', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_custom_border_type_options(),
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--build-firms-count-bd-type: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'build_firms_count_border_width',
			array(
				'label' => esc_html__( 'Border Width', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--build-firms-count-top-width: {{TOP}}{{UNIT}}; --build-firms-count-right-width: {{RIGHT}}{{UNIT}}; --build-firms-count-bottom-width: {{BOTTOM}}{{UNIT}}; --build-firms-count-left-width: {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'build_firms_count_border_type!' => array(
						'',
						'none',
					),
				),
			)
		);

		$this->end_controls_section();
	}

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

	public function get_product_count( $taxonomy, $item ) {
		if ( ! $taxonomy ) {
			return;
		}

		$terms = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) );

		$args = array(
			'post_type' => 'projects',
			'tax_query' => array(
				array(
					'taxonomy' => $taxonomy,
					'terms' => wp_list_pluck( $terms, 'term_id' ),
					'field' => 'id',
					'operator' => 'IN',
					'hide_empty' => false,
				),
			),
		);

		$args['tax_query'][0]['field'] = 'id';
		$args['tax_query'][0]['terms'] = $item;

		$query = new \WP_Query( $args );

		echo '<span class="' . $this->get_widget_class() . '__count">' .
			$query->found_posts . ' ' .
			esc_html__( 'Projects', 'cmsmasters-elementor' );
		'</span>';
	}

	public function get_product_cont( $taxonomy, $item ) {
		$settings = $this->get_settings_for_display();

		$build_firms_columns = ( isset( $settings['build_firms_columns'] ) ? $settings['build_firms_columns'] : '' );
		
		echo '<div class="' . $this->get_widget_class() . '__wrap column_' . $build_firms_columns . '">';

			$term = get_term_by( 'id', $item, $taxonomy );

			$term_logo = get_field( 'project_builder_logo', 'project_design_build_firms_' . $item );

			echo '<figure class="' . $this->get_widget_class() . '__image">' .
				'<a href="' . home_url() . '/project-builder/' . $term->slug . '/">';

					if ( ! empty( $term_logo ) ) {
						echo wp_get_attachment_image( $term_logo['id'], 'full', false, array(
							'title' => $term->name,
							'alt' => $term->name,
						) );
					} else {
						echo '<h6 class="' . $this->get_widget_class() . '__image-title">' . $term->name . '</h6>';
					}

				echo '</a>' .
			'</figure>';

			$this->get_product_count( $taxonomy, $item );

		echo '</div>';
	}

	protected function render_items() {
		$settings = $this->get_settings_for_display();

		echo '<div class="' . $this->get_widget_class() . '__container">';

			$build_firms_type = ( isset( $settings['build_firms_type'] ) ? $settings['build_firms_type'] : '' );
			$taxonomy = 'project_design_build_firms';

			if ( 'manual_selection' === $build_firms_type ) {
				$build_firms = ( isset( $settings['build_firms'] ) ? $settings['build_firms'] : '' );
		
				if ( empty( $build_firms ) ) {
					return;
				}

				foreach( $settings['build_firms'] as $item ) {
					$this->get_product_cont( $taxonomy, $item );
				}
			} else {
				$projects = get_posts( array(
					'post_type' => 'projects',
					'posts_per_page' => -1,
				) );

				$selected_builders = array();

				foreach ( $projects as $project ) {
					$builders = wp_get_post_terms( $project->ID, 'project_design_build_firms' );

					foreach ( $builders as $builder ) {
						$selected_builders[$builder->term_id] = $builder;
					}
				}

				shuffle( $selected_builders );

				$items_to_display = min( $settings['build_firms_quantity'], count( $selected_builders ) );

				foreach ( array_slice( $selected_builders, 0, $items_to_display ) as $builder ) {
					$this->get_product_cont( $taxonomy, $builder->term_id );
				}
			}

		echo '</div>';
	}

	protected function render() {
		$this->render_items();
	}

}
