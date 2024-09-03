<?php
namespace CmsmastersElementor\Modules\Theme\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CmsmastersElementor\Base\Base_Widget;

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
 * Addon theme add to cart widget.
 *
 * Addon widget that displays theme add to cart.
 *
 * @since 1.0.0
 */
class Theme_Add_To_Cart extends Base_Widget {

	/**
	 * Get widget name.
	 *
	 * Retrieve theme add to cart widget name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'cmsmasters-theme-add-to-cart';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve theme add to cart widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Theme Add To Cart', 'cmsmasters-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve theme add to cart widget icon.
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
			'button',
			'add',
		);
	}

	public function get_widget_class() {
		return 'elementor-widget-cmsmasters-theme-add-to-cart';
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
		$this->start_controls_section(
			'section_theme_add_to_cart',
			array( 'label' => __( 'Theme Add To Cart', 'cmsmasters-elementor' ) )
		);

		$this->add_responsive_control(
			'theme_add_to_cart_ver_gap',
			array(
				'label' => __( 'Vertical Gap', 'cmsmasters-elementor' ),
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
					'{{WRAPPER}}' => '--add-to-cart-ver-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'theme_add_to_cart_hor_gap',
			array(
				'label' => __( 'Horizontal Gap', 'cmsmasters-elementor' ),
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
					'{{WRAPPER}}' => '--add-to-cart-hor-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'theme_add_to_cart_button_margin',
			array(
				'label' => __( 'Button Gap', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'allowed_dimensions' => 'horizontal',
				'placeholder' => array(
					'top' => 'auto',
					'right' => '',
					'bottom' => 'auto',
					'left' => '',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--add-to-cart-button-margin-right: {{RIGHT}}{{UNIT}}; --add-to-cart-button-margin-left: {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function get_calculator( $uom, $product_sizes, $full_packaged, $pack ) {
		$sizes = implode( ', ', $product_sizes );

		if ( $sizes ) {
			$regex = '/["x]+/';
			$sizesArray = preg_split( $regex, $sizes );
			[ $width, $height ] = array_map( 'intval', $sizesArray );

			// list( $width, $height, $thickness ) = explode( '"x', $sizes );
		} else {
			$width = 'none';
			$height = 'none';
		}

		echo '<div class="elementor-widget-cmsmasters-theme-add-to-cart__add-to-quote-input-item" product-uom="' . $uom . '" product-width="' . $width . '" product-height="' . $height . '">';

		$full_packaged_uom = ( 'yes' === $full_packaged && 'each' === $uom ? 'pallet' : $uom );

		echo '<span class="elementor-widget-cmsmasters-theme-add-to-cart__add-to-quote-input-uom">' . $full_packaged_uom . '</span>';

			Icons_Manager::render_icon( array(
				'value' => 'themeicon- theme-icon-minus',
				'library' => 'themeicon-',
			), array( 'class' => 'elementor-widget-cmsmasters-theme-add-to-cart__add-to-quote-input-operator decrement disable' ) );

			echo '<input class="elementor-widget-cmsmasters-theme-add-to-cart__add-to-quote-input" type="number" placeholder="QTY (' . $full_packaged_uom . ')" maxlength="4" full-packaged="' . $full_packaged . '" pack="' . ( $pack ? esc_html( $pack ) : '0' ) . '"></input>';

			Icons_Manager::render_icon( array(
				'value' => 'themeicon- theme-icon-plus',
				'library' => 'themeicon-',
			), array( 'class' => 'elementor-widget-cmsmasters-theme-add-to-cart__add-to-quote-input-operator increment' ) );

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

		$style_button = get_option( 'ywraq_show_btn_link', 'button' ) === 'button' ? 'button' : 'ywraq-link';
		$style_button = $args['style'] ?? $style_button;
		$class = 'theme_add_to_quote ' . $style_button;
		$wpnonce = wp_create_nonce( 'add-request-quote-' . $product_id );
		$label = ywraq_get_label( 'btn_link_text' );
		$label_browse = ywraq_get_label( 'browse_list' );
		$rqa_url = YITH_Request_Quote()->get_raq_page_url();
		$exists = $product->is_type( 'variable' ) ? false : YITH_Request_Quote()->exists( $product_id );

		?>
		<div class="yith-ywraq-add-button <?php echo esc_attr( ( $exists ) ? 'hide' : 'show' ); ?>" style="display:<?php echo esc_attr( ( $exists ) ? 'none' : 'block' ); ?>">
			<a href="#" class="<?php echo esc_attr( $class ); ?>" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-wp_nonce="<?php echo esc_attr( $wpnonce ); ?>" data-list_text="<?php echo wp_kses_post( $label_browse ); ?>">
				<?php echo wp_kses_post( $label ); ?>
			</a>
			<span class="elementor-widget-cmsmasters-theme-add-to-cart__add-to-quote-button-icon ajax-loading">
				<img src="<?php echo esc_url( ywraq_get_ajax_default_loader() ); ?>" alt="loading" width="16" height="16" />
			</span>
		</div>
		<?php if ( $exists ) : ?>
			<div class="yith_ywraq_add_item_browse-list-<?php echo esc_attr( $product_id ); ?> yith_ywraq_add_item_browse_message">
				<a href="<?php echo esc_url( $rqa_url ); ?>"><?php echo wp_kses_post( $label_browse ); ?></a>
			</div>
		<?php endif ?>
		<?php
	}

	protected function product_price( $product ) {
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

	protected function add_to_cart( $product, $product_id ) {
		if ( empty( $product ) ) {
			if ( ! empty( $product_id ) ) {
				$product = wc_get_product( $product_id );
			} else {
				return;
			}
		}

		$meta_sku = get_post_meta( $product_id, '_sku', true );
		$sku = ( $meta_sku ? $meta_sku : '' );

		echo '<form class="cart" action="' . esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ) . '" method="post" enctype="multipart/form-data">' .
			'<button type="submit" name="add-to-cart" value="' . esc_attr( $product->get_id() ) . '" class="add_to_cart_button button alt ' . esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) .'" data-product_id="' . $product_id . '" data-product_sku="' . $sku . '">' .
				esc_html( $product->single_add_to_cart_text() ) .
				'<span class="elementor-widget-cmsmasters-theme-add-to-cart__post-add-to-quote-button-icon ajax-loading">' .
					'<img src="' . esc_url( ywraq_get_ajax_default_loader() ) . '" alt="loading" width="16" height="16" />' .
				'</span>' .
			'</button>' .
		'</form>';
	}

	/**
	 * Render theme add to cart widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		global $product;

		$product_id = get_the_ID();

		if ( ! wc_get_product( $product_id ) ) {
			return;
		}

		$product_sizes = wc_get_product_terms( $product_id, 'product_size', array( 'fields' => 'names' ) );
		$uom = wc_get_product_terms( $product_id, 'product_uom', array( 'fields' => 'names' ) );
		$uom = ( ! empty( $uom ) ? $uom[0] : 'each' );

		echo '<div class="elementor-widget-cmsmasters-theme-add-to-cart__add-to-quote" product-uom="' . strtolower( $uom ) . '">' .
			'<div class="elementor-widget-cmsmasters-theme-add-to-cart__add-to-quote-inputs">';

				$full_packaged = ( get_field( 'full_packaged_products', $product_id ) ? 'yes' : 'no' );
				$packs = wc_get_product_terms( $product_id, 'product_pack', array( 'fields' => 'names' ) );
				$pack = implode( ', ', $packs );

				if ( ! empty( $product_sizes ) && ! empty( $uom ) && 'sqft' === strtolower( $uom ) ) {
					$this->get_calculator( 'sqft', $product_sizes, $full_packaged, $pack );
				}

				$this->get_calculator( 'each', $product_sizes, $full_packaged, $pack );

			echo '</div>';

			$price = $this->product_price( $product );
			$product_outlet = ( has_term( 'outlet', 'product_tag', $product_id ) );
			$user = wp_get_current_user();
			$not_customer_role = ( is_user_logged_in() && ! in_array( 'customer', $user->roles ) );

			$quote_premium = ( defined( 'YITH_YWRAQ_PREMIUM' ) ? ' quote_premium' : '' );

			echo '<div class="elementor-widget-cmsmasters-theme-add-to-cart__add-to-quote-button-wrap' . esc_attr( $quote_premium ) . '">';

				if ( $price && ( $product_outlet || $not_customer_role ) ) {
					$this->add_to_cart( $product, $product_id );
				} else {
					$this->get_quote_button( $product_id );
				}

			echo '</div>' .
		'</div>';
	}

}
