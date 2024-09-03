<?php
namespace StereoBankSpace\Woocommerce\CmsmastersFramework;

use StereoBankSpace\Core\Utils\File_Manager;
use StereoBankSpace\TemplateFunctions\General_Elements;
use StereoBankSpace\TemplateFunctions\Main_Elements;
use StereoBankSpace\Woocommerce\CmsmastersFramework\Kits\Kit as Plugin_Kit;

use Elementor\Plugin as Elementor_Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Plugin handler class is responsible for woocommerce different methods.
 */
class Plugin {

	private $css_path_prefix = 'woocommerce/cmsmasters-framework/';

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		if ( ! class_exists( 'woocommerce' ) ) {
			return;
		}

		if ( class_exists( 'Cmsmasters_Elementor_Addon' ) ) {
			new Plugin_Kit();
		}

		add_action( 'after_setup_theme', array( $this, 'add_support' ) );

		add_filter( 'cmsmasters_stylesheet_templates_paths_filter', array( $this, 'stylesheet_templates_paths_filter' ) );

		add_filter( 'cmsmasters_singular_id_filter', array( $this, 'filter_singular_id' ) );

		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		add_action( 'woocommerce_before_main_content', array( $this, 'wrapper_start' ), 10 );

		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
		add_action( 'woocommerce_after_main_content', array( $this, 'wrapper_end' ), 10 );

		remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
		add_action( 'woocommerce_after_shop_loop', array( $this, 'pagination' ), 10 );

		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

		add_filter( 'woocommerce_show_page_title', '__return_false' );

		add_filter( 'cmsmasters_page_title_filter_after', array( $this, 'filter_page_title' ) );

		add_filter( 'woocommerce_enqueue_styles', array( $this, 'enqueue_styles' ) );

		add_filter( 'wc_add_to_cart_message_html', array( $this, 'filter_wc_add_to_cart_message_html' ), 10 );

		add_filter( 'get_product_search_form', array( $this, 'filter_product_search_form' ) );

		add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'filter_woocommerce_loop_add_to_cart_link' ), 10, 3 );

		add_action( 'wp', array( $this, 'maybe_define_woocommerce_checkout' ) );

		add_filter( 'woocommerce_get_endpoint_url', array( $this, 'get_order_received_endpoint_url' ), 10, 3 );

		add_filter( 'elementor/editor/localize_settings', array( $this, 'add_localize_data' ) );

		$this->add_update_kit_settings_hooks();
	}

	/**
	 * Add support.
	 */
	public function add_support() {
		// general:
		add_theme_support( 'woocommerce' );

		// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0):
		// zoom:
		add_theme_support( 'wc-product-gallery-zoom' );
		// lightbox:
		add_theme_support( 'wc-product-gallery-lightbox' );
		// swipe:
		add_theme_support( 'wc-product-gallery-slider' );
	}

	/**
	 * Stylesheet templates paths filter.
	 *
	 * @param array $templates_paths Templates paths.
	 *
	 * @return array Filtered templates paths.
	 */
	public function stylesheet_templates_paths_filter( $templates_paths ) {
		$path = File_Manager::get_responsive_css_path( $this->css_path_prefix );

		return array_merge( $templates_paths, array(
			$path . 'woocommerce.css',
			$path . 'woocommerce.min.css',
			$path . 'woocommerce-rtl.css',
			$path . 'woocommerce-rtl.min.css',
		) );
	}

	/**
	 * Filter singular ID for shop page.
	 */
	public function filter_singular_id( $id ) {
		if ( is_shop() ) {
			$id = wc_get_page_id( 'shop' );
		}

		return $id;
	}

	/**
	 * Wrapper start HTML.
	 */
	public function wrapper_start() {
		echo Main_Elements::main_wrapper_start();
	}

	/**
	 * Wrapper end HTML.
	 */
	public function wrapper_end() {
		echo Main_Elements::main_wrapper_end();
	}

	/**
	 * Pagination HTML.
	 */
	public function pagination() {
		echo General_Elements::get_pagination( array(
			'parent_class' => 'cmsmasters-archive',
		) );
	}

	/**
	 * Filter page title.
	 *
	 * @param string title Page Title.
	 *
	 * @return string Page title.
	 */
	public function filter_page_title( $title ) {
		if ( ! is_shop() ) {
			return $title;
		}

		$id = wc_get_page_id( 'shop' );

		return get_the_title( $id );
	}

	/**
	 * Enqueue theme compatibility styles.
	 *
	 * @param array $styles Array of registered styles.
	 *
	 * @return array
	 */
	public function enqueue_styles( $styles ) {
		unset( $styles['woocommerce-general'] );

		$styles['woocommerce-general'] = array(
			'src' => File_Manager::get_css_template_assets_url( 'woocommerce', null, 'default', true, $this->css_path_prefix ),
			'deps' => '',
			'version' => '1.0.0',
			'media' => 'all',
			'has_rtl' => false,
		);

		return $styles;
	}

	/**
	 * Filter add to cart message HTML.
	 *
	 * @param string $message Message HTML.
	 *
	 * @return string Message HTML.
	 */
	public function filter_wc_add_to_cart_message_html( $message ) {
		return '<div class="cmsmasters-wc-add-to-cart-message">' . wp_kses_post( $message ) . '</div>';
	}

	/**
	 * Filter product search form.
	 *
	 * @return string Search form HTML.
	 */
	public function filter_product_search_form() {
		return General_Elements::get_search_form( 'woocommerce' );
	}

	/**
	 * Filter woocommerce_loop_add_to_cart_link.
	 *
	 * @return string Search form HTML.
	 */
	public function filter_woocommerce_loop_add_to_cart_link( $link, $product, $args ) {
		return sprintf(
			'<a href="%s" data-quantity="%s" class="%s" %s><span>%s</span></a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
			esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
			isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
			esc_html( $product->add_to_cart_text() )
		);
	}

	/**
	 * Add Update Kit Settings Hooks
	 *
	 * Add hooks that update the corresponding kit setting when the WooCommerce option is updated.
	 */
	public function add_update_kit_settings_hooks() {
		add_action( 'update_option_woocommerce_cart_page_id', function( $old_value, $value ) {
			Elementor_Plugin::$instance->kits_manager->update_kit_settings_based_on_option( 'woocommerce_cart_page_id', $value );
		}, 10, 2 );

		add_action( 'update_option_woocommerce_checkout_page_id', function( $old_value, $value ) {
			Elementor_Plugin::$instance->kits_manager->update_kit_settings_based_on_option( 'woocommerce_checkout_page_id', $value );
		}, 10, 2 );

		add_action( 'update_option_woocommerce_myaccount_page_id', function( $old_value, $value ) {
			Elementor_Plugin::$instance->kits_manager->update_kit_settings_based_on_option( 'woocommerce_myaccount_page_id', $value );
		}, 10, 2 );

		add_action( 'update_option_woocommerce_terms_page_id', function( $old_value, $value ) {
			Elementor_Plugin::$instance->kits_manager->update_kit_settings_based_on_option( 'woocommerce_terms_page_id', $value );
		}, 10, 2 );
	}

	/**
	 * Getting order received endpoint url
	 */
	public static function get_order_received_endpoint_url( $url, $endpoint, $value ) {
		$order_received_endpoint = get_option( 'woocommerce_checkout_order_received_endpoint', 'order-received' );

		if ( $order_received_endpoint === $endpoint ) {
			$purchase_summary_page_id = get_option( 'cmsmasters_pages_woocommerce_purchase_summary_page_id' );
			$order = wc_get_order( $value );

			if ( $purchase_summary_page_id && $order ) {
				$url = trailingslashit( trailingslashit( trailingslashit( get_permalink( $purchase_summary_page_id ) ) . $order_received_endpoint ) . $order->get_id() );
			}
		}

		return $url;
	}

	public function maybe_define_woocommerce_checkout() {
		$purchase_summary_page_id = get_option( 'cmsmasters_pages_woocommerce_purchase_summary_page_id' );

		if ( $purchase_summary_page_id && intval( $purchase_summary_page_id ) === get_queried_object_id() ) {
			if ( ! defined( 'WOOCOMMERCE_CHECKOUT' ) ) {
				define( 'WOOCOMMERCE_CHECKOUT', true );
			}
		}
	}

	/**
	 * Add Localize Data
	 *
	 * Makes `woocommercePages` available with the page name and the associated post ID for use with the various
	 * widgets site settings modal.
	 */
	public function add_localize_data( $settings ) {
		$settings['woocommerce']['woocommercePages'] = array(
			'checkout' => wc_get_page_id( 'checkout' ),
			'cart' => wc_get_page_id( 'cart' ),
			'myaccount' => wc_get_page_id( 'myaccount' ),
			'purchase_summary' => get_option( 'cmsmasters_pages_woocommerce_purchase_summary_page_id' ),
		);

		return $settings;
	}

}
