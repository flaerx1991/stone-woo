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
 * Addon theme product chooses widget.
 *
 * Addon widget that displays theme product chooses.
 *
 * @since 1.0.0
 */
class Theme_Product_Chooses extends Base_Widget {

	/**
	 * Get widget name.
	 *
	 * Retrieve theme product chooses widget name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'cmsmasters-theme-product-chooses';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve theme product chooses widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Theme Product Chooses', 'cmsmasters-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve theme product chooses widget icon.
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
			'product',
			'chooses',
		);
	}

	public function get_widget_class() {
		return 'elementor-widget-cmsmasters-theme-product-chooses';
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
		// $widget_selector = $this->get_widget_selector();

		// $this->start_controls_section(
		// 	'section_theme_collection_data',
		// 	array( 'label' => __( 'Theme Product Chooses', 'cmsmasters-elementor' ) )
		// );

		// $this->add_control(
		// 	'limited',
		// 	array(
		// 		'label' => __( 'Limited to display', 'cmsmasters-elementor' ),
		// 		'type' => Controls_Manager::NUMBER,
		// 		'default' => 2,
		// 	)
		// );

		// $this->end_controls_section();
	}

	protected function get_labels() {
		echo '<div class="elementor-widget-cmsmasters-theme-product-chooses__labels">' .
			'<div class="elementor-widget-cmsmasters-theme-product-chooses__label sizes">' .
				esc_html__( 'Size', 'cmsmasters-elementor' ) .
			'</div>' .
			'<div class="elementor-widget-cmsmasters-theme-product-chooses__label packs">' .
				esc_html__( 'Pack', 'cmsmasters-elementor' ) .
			'</div>' .
			'<div class="elementor-widget-cmsmasters-theme-product-chooses__label uom">' .
				esc_html__( 'UOM', 'cmsmasters-elementor' ) .
			'</div>' .
			'<div class="elementor-widget-cmsmasters-theme-product-chooses__label price">' .
				esc_html__( 'Price', 'cmsmasters-elementor' ) .
			'</div>' .
			'<div class="elementor-widget-cmsmasters-theme-product-chooses__label availability">' .
				esc_html__( 'Available', 'cmsmasters-elementor' ) .
			'</div>' .
			'<div class="elementor-widget-cmsmasters-theme-product-chooses__label incoming">' .
				esc_html__( 'Incoming', 'cmsmasters-elementor' ) .
			'</div>' .
		'</div>';
	}

	protected function get_product( $product_id, $this_type_names, $product_price, $sizes, $packs, $uoms, $current_ID ) {
		$current = ( $product_id === $current_ID ? true : false );

		$product_badge = wc_get_product_terms( $product_id, 'product_badges', array( 'fields' => 'names' ) );
		$badge = ( $product_badge ? ' - ' . ucwords( implode( ', ', $product_badge ) ) : '' );
		$product_title = get_field( 'product_short_name', $product_id );

		$post_link = get_permalink( $product_id );

		echo '<div class="elementor-widget-cmsmasters-theme-product-chooses__product' . ( $current ? ' current' : '' ) . '">' .
			'<div class="elementor-widget-cmsmasters-theme-product-chooses__product-attr sizes">' .
				'<div class="elementor-widget-cmsmasters-theme-product-chooses__product-attr-inner">' .
					( ! $current ? '<a href="' . esc_url( $post_link ) . '">' : '' );

					Icons_Manager::render_icon( array(
						'value' => 'themeicon- ' . ( $current ? 'theme-icon-radio-button-checked' : 'theme-icon-radio_button_unchecked' ),
						'library' => 'themeicon-',
					), array( 'class' => 'product-chooses-attr-icon' ) );

					echo ( ! $current ? '</a>' : '' ) .
					// ( ! empty( $product_title ) ? $product_title : ( ( $sizes ? esc_html( $sizes ) : '0' ) . ' ' . $this_type_names ) ) . $badge .
					( $sizes ? esc_html( $sizes ) : '0' ) .
				'</div>' .
			'</div>' .
			'<div class="elementor-widget-cmsmasters-theme-product-chooses__product-attr packs">' .
				'<div class="elementor-widget-cmsmasters-theme-product-chooses__product-attr-inner">' .
					( $packs ? esc_html( $packs ) : '0' ) .
				'</div>' .
			'</div>' .
			'<div class="elementor-widget-cmsmasters-theme-product-chooses__product-attr uoms">' .
				'<div class="elementor-widget-cmsmasters-theme-product-chooses__product-attr-inner">' .
					( $uoms ? esc_html( $uoms ) : '-' ) .
				'</div>' .
			'</div>';
			
			$product_outlet = ( has_term( 'outlet', 'product_tag', $product_id ) );
			$user = wp_get_current_user();
			$not_customer_role = ( is_user_logged_in() && ! in_array( 'customer', $user->roles ) );

			echo '<div class="elementor-widget-cmsmasters-theme-product-chooses__product-attr price">' .
				'<div class="elementor-widget-cmsmasters-theme-product-chooses__product-attr-inner">' .
					( ( ( $product_outlet || $not_customer_role ) && $product_price ) ? $product_price : '-' ) .
				'</div>' .
			'</div>' .
			'<div class="elementor-widget-cmsmasters-theme-product-chooses__product-attr availability">' .
				'<div class="elementor-widget-cmsmasters-theme-product-chooses__product-attr-inner">' .
					( ! empty( get_field( 'available', $product_id ) ) ? get_field( 'available', $product_id ) : '0' ) .
				'</div>' .
			'</div>' .
			'<div class="elementor-widget-cmsmasters-theme-product-chooses__product-attr incoming">' .
				'<div class="elementor-widget-cmsmasters-theme-product-chooses__product-attr-inner">' .
					( ! empty( get_field( 'incoming', $product_id ) ) ? get_field( 'incoming', $product_id ) : '0' ) .
				'</div>' .
			'</div>' .
		'</div>';
	}

	public function get_product_chooses() {
		$current_ID = get_the_ID();
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

			$parent_type[$product_id] = array(
				'product_id' => $product_id,
				'product_price' => $product_price,
				'product_sizes' => implode( ', ', $sizes ),
				'product_packs' => implode( ', ', $packs ),
				'product_uoms' => implode( ', ', $uoms ),
			);
		}

		if ( empty( $parent_type )) {
			return;
		}

		echo '<div class="elementor-widget-cmsmasters-theme-product-chooses__wrapper frontend">';
			$product_finishes = wc_get_product_terms( $current_ID, 'product_finishes', array( 'fields' => 'names' ) );
	
			if ( ! empty( $product_finishes ) ) {
				$this_product_finishes = $product_finishes[0];
			}

			echo '<div class="elementor-widget-cmsmasters-theme-product-chooses__type_wrap">' .
				'<div class="elementor-widget-cmsmasters-theme-product-chooses__type">' .
					// ( $this_collection_name . ' - ' . $this_product_finishes . ' - ' .  $this_type_names ) .
					$this_type_names .
				'</div>';

				$this->get_labels();

				echo '<div class="elementor-widget-cmsmasters-theme-product-chooses__products">';
					foreach ( $parent_type as $element ) {
						$this->get_product( $element['product_id'], $this_type_names, $element['product_price'], $element['product_sizes'], $element['product_packs'], $element['product_uoms'], $current_ID );
					}

				echo '</div>' .
			'</div>' .
		'</div>';
	}

	/**
	 * Render theme product chooses widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		$this->get_product_chooses( 'frontend' );
	}

}
