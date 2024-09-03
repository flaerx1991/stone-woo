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
 * Addon theme category list widget.
 *
 * Addon widget that displays theme category list.
 *
 * @since 1.0.0
 */
class Theme_Category_List extends Base_Widget {

	/**
	 * Get widget name.
	 *
	 * Retrieve theme category list widget name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'cmsmasters-theme-category-list';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve theme category list widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Theme Category List', 'cmsmasters-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve theme category list widget icon.
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
			'badges',
		);
	}

	public function get_widget_class() {
		return 'elementor-widget-cmsmasters-theme-category-list';
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
		parent::register_controls();
	}

	protected function render_post_acf_category() {
		$post_id = get_the_ID();
		$taxonomy = 'product_badges';
		$tag = 'div';
		$class = 'product_badges';

		if ( empty( $taxonomy ) ) {
			return;
		}

		$terms = wp_get_post_terms( $post_id, $taxonomy );
		$additional = ( false !== $this->product_additional_fields() ? $this->product_additional_fields() : '' );
		
		if ( ( ! empty( $terms ) && ! is_wp_error( $terms ) ) || ( ! empty ( $additional ) ) ) {
			echo '<' . tag_escape( $tag ) . ' class="elementor-widget-cmsmasters-theme-category-list__wrap">';

				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						echo '<a href="' . esc_url( get_term_link( $term ) ) . '" class="elementor-widget-cmsmasters-theme-category-list__badge ' . strtolower( str_replace( array( ' ', '-' ), '_', $term->name ) ) . '">' .
							$term->name .
						'</a>';
					}
				}

			echo '</' . tag_escape( '/' . $tag ) . '>';
		}
	}

	/**
	 * Render theme category list widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		$this->render_post_acf_category();
	}

}
