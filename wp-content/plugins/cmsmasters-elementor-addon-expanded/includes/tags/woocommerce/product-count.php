<?php
namespace CmsmastersElementor\Tags\Woocommerce;

use CmsmastersElementor\Base\Traits\Base_Tag;
use CmsmastersElementor\Controls_Manager as CmsmastersControls;
use CmsmastersElementor\Tags\Woocommerce\Traits\Woo_Group;
use Elementor\Core\DynamicTags\Tag;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * CMSMasters price.
 *
 * Retrieves the price of a product.
 *
 * @since 1.0.0
 */
class Product_Count extends Tag {

	use Base_Tag, Woo_Group;

	/**
	* Get tag name.
	*
	* Returns the name of the dynamic tag.
	*
	* @since 1.0.0
	*
	* @return string Tag name.
	*/
	public static function tag_name() {
		return 'product-count';
	}

	/**
	* Get tag title.
	*
	* Returns the title of the dynamic tag.
	*
	* @since 1.0.0
	*
	* @return string Tag title.
	*/
	public static function tag_title() {
		return __( 'Product Count', 'cmsmasters-elementor' );
	}

	/**
	* Register controls.
	*
	* Registers the controls of the dynamic tag.
	*
	* @since 1.0.0
	*
	* @return void Tag controls.
	*/
	protected function register_controls() {
		$this->add_control(
			'type',
			array(
				'label' => __( 'Type', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'label_block' => false,
				'options' => array(
					'not-outlet' => __( 'Not outlet', 'cmsmasters-elementor' ),
					'outlet' => __( 'Outlet', 'cmsmasters-elementor' ),
				),
				'default' => 'not-outlet',
			)
		);
	}

	/**
	* Tag render.
	*
	* Prints out the value of the dynamic tag.
	*
	* @since 1.0.0
	*
	* @return void Tag render result.
	*/
	public function render() {
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1
		);

		$products_query = new \WP_Query( $args );

		if ( $products_query->have_posts() ) {
			$outlet_products_count = 0;
			$not_outlet_products_countcount = 0;
			$count = 0;

			while ( $products_query->have_posts() ) {
				$products_query->the_post();
				$product_id = get_the_ID();
				$product = wc_get_product( $product_id );

				if ( has_term( 'outlet', 'product_tag', $product_id ) ) {
					$outlet_products_count++;
				}

				if ( $product->get_price() === '' || !has_term( 'outlet', 'product_tag', $product_id ) ) {
					$not_outlet_products_countcount++;
				}
			}

			wp_reset_postdata();

			if ( 'outlet' === $this->get_settings( 'type' ) ) {
				$count = $outlet_products_count;
			} else {
				$count = $not_outlet_products_countcount;
			}

			$text = ( 1 === $count ? ' Product' : ' Products' );

			echo $count . esc_html( $text );
		}
	}

}
