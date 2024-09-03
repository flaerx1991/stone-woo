<?php
namespace CmsmastersElementor\Tags\Post;

use CmsmastersElementor\Base\Traits\Base_Tag;
use CmsmastersElementor\Controls_Manager as CmsmastersControls;
use CmsmastersElementor\Tags\Post\Traits\Post_Group;
use Elementor\Controls_Manager;
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
class Count extends Tag {

	use Base_Tag, Post_Group;

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
		return 'count';
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
		return __( 'Count', 'cmsmasters-elementor' );
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
			'taxonamy',
			array(
				'label' => __( 'Taxonamy', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_post_taxonomies(),
				'label_block' => false,
				'toggle' => false,
			)
		);

		$this->add_control(
			'show',
			array(
				'label' => __( 'Show', 'cmsmasters-elementor' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'label_block' => false,
				'options' => array(
					'categories' => __( 'Categories', 'cmsmasters-elementor' ),
					'posts' => __( 'Posts', 'cmsmasters-elementor' ),
				),
				'default' => 'categories',
				'condition' => array( 'taxonamy!' => '' ),
			)
		);

		$this->add_control(
			'post_types',
			array(
				'label' => __( 'Post Types', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_post_types(),
				'label_block' => false,
				'toggle' => false,
				'condition' => array(
					'taxonamy!' => '',
					'show' => 'posts',
				),
			)
		);

		$this->add_control(
			'current_category',
			array(
				'label' => __( 'Current Category', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
				'condition' => array(
					'taxonamy!' => '',
					'show' => 'posts',
					'post_types!' => '',
				),
			)
		);

		$this->add_control(
			'category_id',
			array(
				'label' => __( 'Category ID', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( '10', 'cmsmasters-elementor' ),
				'condition' => array(
					'taxonamy!' => '',
					'show' => 'posts',
					'post_types!' => '',
					'current_category' => '',
				),
			)
		);
	}

	public function get_post_taxonomies() {
		$taxonomies = get_taxonomies();
		$taxonomy_labels = [];

		foreach ( $taxonomies as $taxonomy ) {
			$taxonomy_object = get_taxonomy( $taxonomy );

			if ( $taxonomy_object ) {
				$taxonomy_labels[ $taxonomy_object->name ] = $taxonomy_object->name;
			}
		}

		return $taxonomy_labels;
	}

	public function get_post_types() {
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		$post_type_labels = [ '' => 'All' ];

		foreach ( $post_types as $post_type ) {
			$post_type_labels[ $post_type->name ] = $post_type->name;
		}

		return $post_type_labels;
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
		$taxonomy = $this->get_settings( 'taxonamy' );
		$show = $this->get_settings( 'show' );
		$post_type = $this->get_settings( 'post_types' );
		$current_category = $this->get_settings( 'current_category' );
		$term = $this->get_settings( 'category_id' );

		if ( ! $taxonomy ) {
			return;
		}

		$terms = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) );

		if ( 'categories' === $show ) {
			$term_count = count( $terms );

			echo $term_count;
		} elseif ( 'posts' === $show ) {
			$post_type = ( '' !== $post_type ? $post_type : '' );

			$args = array(
				'post_type' => $post_type,
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

			if ( '' !== $term ) {
				$args['tax_query'][0]['field'] = 'id';
				$args['tax_query'][0]['terms'] = $term;
			} elseif ( 'yes' === $current_category ) {
				$terms = wp_get_post_terms( get_the_ID(), $taxonomy, array( 'fields' => 'ids' ) );

				$args['tax_query'][0]['field'] = 'id';
				$args['tax_query'][0]['terms'] = $terms;
			}

			$query = new \WP_Query( $args );

			echo $query->found_posts;
		}
	}

}
