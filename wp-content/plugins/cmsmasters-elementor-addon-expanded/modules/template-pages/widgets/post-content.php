<?php
namespace CmsmastersElementor\Modules\TemplatePages\Widgets;

use CmsmastersElementor\Base\Base_Widget;
use CmsmastersElementor\Modules\Settings\Kit_Globals;
use CmsmastersElementor\Modules\TemplatePages\Traits\Singular_Widget;
use CmsmastersElementor\Plugin;
use CmsmastersElementor\Traits\Extendable_Widget;
use CmsmastersElementor\Utils;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Addon Post Content widget.
 *
 * Addon widget that displays the content of current post.
 *
 * @since 1.0.0
 */
class Post_Content extends Base_Widget {

	use Singular_Widget, Extendable_Widget;

	/**
	 * Get extendable widget class.
	 *
	 * Retrieve the extendable widget container class.
	 *
	 * @since 1.0.0
	 *
	 * @return string Extendable widget container class.
	 */
	public function get_extendable_widget_class() {
		return 'cmsmasters-widget-content elementor-widget-theme-post-content';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Post Content', 'cmsmasters-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve test widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'cmsicon-post-content';
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
		return array( 'content' );
	}

	/**
	 * Show in panel.
	 *
	 * Whether to show the widget in the panel or not.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Whether to show the widget in the panel or not.
	 */
	public function show_in_panel() {
		return false;
	}

	/**
	 * Register Post Excerpt widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Style', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label' => __( 'Alignment', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => __( 'Left', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-center',
					),
					'right' => array(
						'title' => __( 'Right', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'cmsmasters-elementor' ),
						'icon' => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label' => __( 'Text Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'global' => array( 'default' => Kit_Globals::COLOR_TEXT ),
				'selectors' => array(
					'{{WRAPPER}}' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'typography',
				'global' => array( 'default' => Kit_Globals::TYPOGRAPHY_TEXT ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render Post Excerpt widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @since 1.6.0 Fixed display content for editor
	 */
	protected function render() {
		$post = get_post();

		if ( ! isset( $post->ID ) ) {
			return;
		}

		if ( post_password_required( $post->ID ) ) {
			echo get_the_password_form( $post->ID );

			return;
		}

		// Avoid recursion
		static $did_posts = array();

		if ( isset( $did_posts[ $post->ID ] ) ) {
			return;
		}

		$did_posts[ $post->ID ] = true;
		// End avoid recursion

		$elementor = Plugin::elementor();

		if ( Utils::is_preview_mode( $post->ID ) ) {
			echo $elementor->preview->builder_wrapper( '' );

			return;
		}

		/**
		 * Filters preview post object rendering.
		 *
		 * @since 1.0.0
		 * @since 1.7.1 Fixed adaptive style for post content.
		 *
		 * @param string $post Current post object.
		 */
		$post = apply_filters( 'cmsmasters_elementor/widgets/post_content/render_preview_post', $post );

		if ( ! $post ) {
			return;
		}

		$editor = $elementor->editor;
		$frontend = $elementor->frontend;
		$is_edit_mode = $editor->is_edit_mode();

		$editor->set_edit_mode( false );

		$content = $frontend->get_builder_content( $post->ID );

		$this->add_render_attribute( 'wrap', 'class', 'entry-content' );

		$frontend->remove_content_filter();

		if ( empty( $content ) ) {
			setup_postdata( $post );

			echo '<div ' . $this->get_render_attribute_string( 'wrap' ) . '>';

			/**
			 * Filters the post content.
			 *
			 * @since 1.0.0
			 *
			 * @param string $content Content of the current post.
			 */
			echo apply_filters( 'the_content', get_the_content() );

			Utils::link_pages( $this );

			$frontend->add_content_filter();

			echo '</div>';

			return;
		} else {
			$frontend->remove_content_filters();
			$content = apply_filters( 'the_content', $content );
			$frontend->restore_content_filters();
		}

		$editor->set_edit_mode( $is_edit_mode );

		echo '<div ' . $this->get_render_attribute_string( 'wrap' ) . '>' .
			$content .
		'</div>';
	}

	/**
	 * Render widget plain content.
	 *
	 * Save generated HTML to the database as plain content.
	 *
	 * @since 1.0.0
	 */
	public function render_plain_content() {}

	/**
	 * Render shortcode widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.6.0
	 * @access protected
	 */
	protected function content_template() {}

}
