<?php
namespace CmsmastersElementor\Modules\TemplatePages\Widgets;

use CmsmastersElementor\Base\Base_Document;
use CmsmastersElementor\Modules\TemplatePages\Traits\Archive_Widget;
use CmsmastersElementor\Modules\TemplatePages\Widgets\Post_Title;
use CmsmastersElementor\Plugin;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Archive title widget.
 *
 * Addon widget that displays title of current archive page.
 *
 * @since 1.0.0
 */
class Archive_Title extends Post_Title {

	use Archive_Widget { // phpcs:ignore WordPress.WhiteSpace.ControlStructureSpacing.NoSpaceAfterOpenParenthesis
		Archive_Widget::get_categories as archive_categories;
	}

	/**
	 * Get widget name prefix.
	 *
	 * Retrieve the widget name prefix.
	 *
	 * @since 1.0.0
	 *
	 * @return string The widget name prefix.
	 */
	public function get_name_prefix() {
		return self::WIDGET_NAME_PREFIX . 'woo-archive-';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Archive Title', 'cmsmasters-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'cmsicon-archive-title';
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
		return array( 'search' );
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the widget categories.
	 *
	 * @since 1.0.0
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array_merge( $this->archive_categories(), array( Base_Document::WOO_ARCHIVE_WIDGETS_CATEGORY ) );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to
	 * change and customize the widget settings.
	 *
	 * @since 1.0.0
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->start_injection( array( 'of' => 'title' ) );

		$dynamic_tags = Plugin::elementor()->dynamic_tags;
		$tag_names = $this->get_tag_names();

		$this->add_control(
			'title_no_context',
			array(
				'type' => Controls_Manager::HIDDEN,
				'dynamic' => array(
					'active' => true,
					'default' => $dynamic_tags->tag_data_to_tag_text( null, $tag_names['title'], array( 'context' => false ) ),
				),
			)
		);

		$this->end_injection();

		$this->remove_control( 'title_link_switcher' );

		$this->remove_control( 'custom_link' );

		$this->start_injection( array(
			'of' => 'title_tag',
			'at' => 'before',
		) );

		$this->add_control(
			'title_context',
			array(
				'label' => __( 'Include Title Context', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->end_injection();
	}

	/**
	 * Get tag names.
	 *
	 * Retrieve widget dynamic controls tag names.
	 *
	 * @since 1.0.0
	 *
	 * @return array Widget dynamic controls tag names.
	 */
	protected function get_tag_names() {
		$tag_names = parent::get_tag_names();

		$tag_names['title'] = 'cmsmasters-archive-title';

		return $tag_names;
	}

	protected function get_title_text() {
		$title = parent::get_title_text();

		$settings = $this->get_settings_for_display();

		if ( 'yes' !== $settings['title_context'] ) {
			$title = $settings['title_no_context'];
		}

		return $title;
	}

	/**
	 * Render widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to
	 * generate the live preview.
	 *
	 * @since 1.0.0
	 */
	protected function content_template() {
		?>
		<#
		var modifiedTitle = settings.title;

		if ( 'yes' !== settings.title_context ) {
			modifiedTitle = settings.title_no_context;
		}
		#>
		<?php

		parent::content_template();
	}

}
