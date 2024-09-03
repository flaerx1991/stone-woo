<?php
namespace CmsmastersElementor\Modules\Theme\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CmsmastersElementor\Base\Base_Widget;
use CmsmastersElementor\Controls_Manager as CmsmastersControls;
use CmsmastersElementor\Modules\Theme\Module as ThemeModule;

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
 * Addon theme product tab additional info widget.
 *
 * Addon widget that displays theme product tab additional info.
 *
 * @since 1.0.0
 */
class Theme_Product_Tab_Additional_Info extends Base_Widget {

	/**
	 * Get widget name.
	 *
	 * Retrieve theme product tab additional info widget name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'cmsmasters-theme-product-tab-additional-info';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve theme product tab additional info widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Theme Product Tab Additional Info', 'cmsmasters-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve theme product tab additional info widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'cmsicon-product-tabs';
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
			'product',
			'button',
			'wishlist',
			'count',
		);
	}

	public function get_widget_class() {
		return 'elementor-widget-cmsmasters-theme-product-tab-additional-info';
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
			'section_theme_product_tab_additional_info',
			array( 'label' => __( 'Theme Product Tab Additional Info', 'cmsmasters-elementor' ) )
		);

		$this->add_control(
			'theme_product_tab_additional_info_tab',
			array(
				'label' => __( 'Tab', 'cmsmasters-elementor' ),
				'label_block' => false,
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'general' => __( 'General', 'cmsmasters-elementor' ),
					'specifications' => __( 'Specifications', 'cmsmasters-elementor' ),
					'usage' => __( 'Usage', 'cmsmasters-elementor' ),
				),
				'default' => 'general',
			)
		);

		$this->end_controls_section();
	}

	protected function general_tab( $item ) {
		$image_id = ( isset( $item['image'] ) ? $item['image'] : '' );
		$text = ( isset( $item['text'] ) ? $item['text'] : '' );

		if ( $image_id || $text ) {
			echo '<div class="' . $this->get_widget_class() . '__list-item">';

			if ( $image_id ) {
				echo '<figure class="' . $this->get_widget_class() . '__list-item-image">' .
					wp_get_attachment_image( $image_id, 'thumbnail' ) .
				'</figure>';
			}
	
			if ( $text ) {
				echo '<span class="' . $this->get_widget_class() . '__text">' .
					$text .
				'</span>';
			}

			echo '</div>';
		}
	}

	protected function specifications_tab() {
		$product = wc_get_product( get_the_ID() );

		if ( $product ) {
			$attributes = $product->get_attributes();

			foreach ( $attributes as $attribute ) {
				$attribute_name = '';
				$attribute_values = array();

				if ( $attribute->is_taxonomy() && $attribute->get_taxonomy_object() ) {
					$attribute_name = $attribute->get_taxonomy_object()->attribute_label;

					$selected_terms = $attribute->get_terms();

					if ( $selected_terms ) {
						foreach ( $selected_terms as $selected_term ) {
							/**
							 * Filter the selected attribute term name.
							 *
							 * @since 3.4.0
							 * @param string  $name Name of selected term.
							 * @param array   $term The selected term object.
							 */
							$attribute_values[] = esc_html( apply_filters( 'woocommerce_product_attribute_term_name', $selected_term->name, $selected_term ) );
						}
					}
				} else {
					$attribute_name = $attribute->get_name();

					$attribute_values = $attribute->get_options();
				}

				echo '<span class="' . $this->get_widget_class() . '__attributes">' .
					'<span class="' . $this->get_widget_class() . '__attributes-name">' .
						esc_html( $attribute_name ) .
					'</span>' .
					'<span class="' . $this->get_widget_class() . '__attributes-values">' .
						implode( ', ', $attribute_values ) .
					'</span>' .
				'</span>';
			}
		}
	}

	protected function usage_tab( $fields ) {
		$text = ( ! empty( $fields['usage_text'] ) ? $fields['usage_text'] : '' );
		$recommendation = ( ! empty( $fields['usage_recommendation'] ) ? $fields['usage_recommendation'] : '' );

		if ( $text ) {
			echo '<span class="' . $this->get_widget_class() . '__text">' .
				$text .
			'</span>';
		}

		echo '<div class="' . $this->get_widget_class() . '__list">';
			$usages = array(
				'residential_exterior_floors' => array(
					'Residential Exterior floors',
					'themeicon- theme-icon-cottage',
				),
				'commercial_exterior_floors' => array(
					'Commercial Exterior floors',
					'themeicon- theme-icon-buildings',
				),
				'outdoor_floor' => array(
					'Outdoor Floor',
					'themeicon- theme-icon-cottage',
				),
				'borders' => array(
					'Borders',
					'themeicon- theme-icon-cottage',
				),
				'stairs' => array(
					'Stairs',
					'themeicon- theme-icon-stairs',
				),
				'pool' => array(
					'Pool',
					'themeicon- theme-icon-swimming-pool',
				),
			);

			foreach ( $usages as $usage => $label ) {
				$value = ( isset ( $fields[$usage][0]['value'] ) ? $fields[$usage][0]['value'] : '' );
				$checked = ( ( array_key_exists( $usage, $fields ) && $value == $usage ) ? ' checked' : '' );

				echo '<div class="' . $this->get_widget_class() . '__list-item' . $checked . '">';

					Icons_Manager::render_icon( array(
						'value' => $label[1],
						'library' => 'themeicon-',
					), array( 'class' => $this->get_widget_class() . '__list-item-image') );

					echo '<span class="' . $this->get_widget_class() . '__' . $usage . '">' .
						esc_html( $label[0] ) .
					'</span>';
				echo '</div>';
			}

		echo '</div>';

		if ( $recommendation ) {
			echo '<span class="' . $this->get_widget_class() . '__recommendation">' .
				$recommendation .
			'</span>';
		}
	}

	/**
	 * Render theme product tab additional info widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$additional_info_tab = ( $settings['theme_product_tab_additional_info_tab'] ? $settings['theme_product_tab_additional_info_tab'] : '' );

		echo '<div class="' . $this->get_widget_class() . '__wrap ' . $additional_info_tab . '">';
			$post_id = get_the_ID();

			if ( 'general' === $additional_info_tab || 'usage' === $additional_info_tab ) {
				$fields = get_field( $additional_info_tab . '_tab', $post_id );

				if ( $fields ) {
					if ( 'general' === $additional_info_tab ) {
						echo '<div class="' . $this->get_widget_class() . '__list">';

						foreach ( $fields as $item ) {
							$this->general_tab( $item );
						}

						echo '</div>';
					} else if ( 'usage' === $additional_info_tab ) {
						$this->usage_tab( $fields );
					}
				}
			}

			if ( 'specifications' === $additional_info_tab ) {
				$this->specifications_tab();
			}

		echo '</div>';
	}

}
