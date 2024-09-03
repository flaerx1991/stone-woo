<?php
namespace CmsmastersElementor\Tags\Woocommerce;

use CmsmastersElementor\Base\Traits\Base_Tag;
use CmsmastersElementor\Tags\Woocommerce\Traits\Woo_Group;

use Elementor\Core\DynamicTags\Tag;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * CMSMasters post url.
 *
 * Retrieves the full permalink for the current post.
 *
 * @since 1.0.0
 */
class Collection extends Tag {

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
		return 'collection';
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
		return __( 'Collection', 'cmsmasters-elementor' );
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
		$terms = wp_get_post_terms( get_the_ID(), 'collection' );

		$collection = '';

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$count = count( $terms );
			$i = 1;

			foreach ( $terms as $term ) {
				$term_link = str_replace( '/collection/', '/collections/', get_term_link( $term ) );

				$collection .= '<a href="' . esc_url( $term_link ) . '">' .
					$term->name .
				'</a>';

				if ( $i < $count ) {
					$collection .= ', ';
				}

				$i++;
			}
		}

		echo $collection;
	}

}
