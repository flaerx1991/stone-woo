<?php
namespace CmsmastersElementor\Modules\Blog\Widgets;

use CmsmastersElementor\Modules\Blog\Widgets\Blog_Grid;
use CmsmastersElementor\Modules\TemplatePages\Traits\Archive_Widget;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Addon archive widget.
 *
 * Addon widget that displays archive.
 *
 * @since 1.0.0
 */
class Archive_Posts extends Blog_Grid {

	use Archive_Widget;

	/**
	 * @since 1.0.0
	 */
	public function get_title() {
		return __( 'Archive Posts', 'cmsmasters-elementor' );
	}

	/**
	 * @since 1.0.0
	 */
	public function register_controls() {
		parent::register_controls();

		$this->remove_responsive_control( 'posts_per_page' );

		$this->update_control(
			'pagination_show',
			array(
				'type' => Controls_Manager::HIDDEN,
				'default' => 'yes',
			)
		);

		$this->update_control(
			'pagination_save_state',
			array(
				'type' => Controls_Manager::HIDDEN,
				'default' => 'yes',
			)
		);

		$this->update_control(
			'section_query',
			array(
				'type' => 'hidden',
			)
		);

		$this->update_control(
			static::QUERY_CONTROL_PREFIX . '_post_type',
			array(
				'default' => 'current_query',
			)
		);
	}

}
