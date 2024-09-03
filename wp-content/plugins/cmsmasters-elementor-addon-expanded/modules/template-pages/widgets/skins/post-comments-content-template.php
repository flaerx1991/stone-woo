<?php
namespace CmsmastersElementor\Modules\TemplatePages\Widgets\Skins;

use CmsmastersElementor\Utils;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Post_Comments_Content_Template extends Post_Comments_Base {

	/**
	 * Get skin id.
	 *
	 * Retrieve skin id.
	 *
	 * @since 1.0.0
	 *
	 * @return string Skin id.
	 */
	public function get_id() {
		return 'content-template';
	}

	/**
	 * Get skin title.
	 *
	 * Retrieve skin title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Skin title.
	 */
	public function get_title() {
		return __( 'Content Template', 'cmsmasters-elementor' );
	}

	/**
	 * Render skin output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		if (
			! comments_open() &&
			( Utils::is_preview_mode() || Utils::is_edit_mode() )
		) :
			?>
			<div class="elementor-alert elementor-alert-danger" role="alert">
				<span class="elementor-alert-title">
					<?php esc_html_e( 'Comments Are Closed!', 'cmsmasters-elementor' ); ?>
				</span>
				<span class="elementor-alert-description">
					<?php esc_html_e( 'Switch on comments from either the discussion box on the WordPress post edit screen or from the WordPress discussion settings.', 'cmsmasters-elementor' ); ?>
				</span>
			</div>
			<?php
		elseif ( comments_open() ) :
			add_filter( 'dsq_can_load', '__return_false' );

			comments_template();

			add_filter( 'dsq_can_load', '__return_true' );
		endif;
	}

}
