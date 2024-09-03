<?php
namespace StereoBankSpace\Admin\Installer\Merlin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Installer filters.
 *
 * Available filters for extending Merlin WP.
 */
class Filters {

	/**
	 * Filters constructor.
	 */
	public function __construct() {
		if ( ! class_exists( 'Merlin' ) ) {
			return;
		}

		add_filter( 'merlin_unset_default_widgets_args', array( $this, 'unset_default_widgets_args' ) );

		add_filter( 'merlin_generate_child_functions_php', array( $this, 'generate_child_functions_php' ), 10, 2 );

		add_filter( 'merlin_generate_child_style_css', array( $this, 'generate_child_style_css' ), 10, 4 );
	}

	/**
	 * Add your widget area to unset the default widgets from.
	 * If your theme's first widget area is "sidebar-1", you don't need this.
	 *
	 * @see https://stackoverflow.com/questions/11757461/how-to-populate-widgets-on-sidebar-on-theme-activation
	 *
	 * @param array $widget_areas Arguments for the sidebars_widgets widget areas.
	 *
	 * @return array of arguments to update the sidebars_widgets option.
	 */
	public function unset_default_widgets_args( $widget_areas ) {
		$widget_areas = array(
			'sidebar' => array(),
			'sidebar_default' => array(),
			'sidebar_archive' => array(),
			'sidebar_search' => array(),
			'footer-1' => array(),
			'footer-2' => array(),
			'footer-3' => array(),
			'footer-4' => array(),
			'footer-5' => array(),
		);

		return $widget_areas;
	}

	/**
	 * Custom content for the generated child theme's functions.php file.
	 *
	 * @param string $output Generated content.
	 * @param string $slug Parent theme slug.
	 *
	 * @return string
	 */
	public function generate_child_functions_php( $output, $slug ) {
		$slug_no_hyphens = strtolower( preg_replace( '#[^a-zA-Z]#', '', $slug ) );

		$output = "
			<?php
			/**
			 * Theme functions and definitions.
			 */
			function {$slug_no_hyphens}_child_enqueue_styles() {
				wp_enqueue_style( '{$slug}-child-style',
					get_stylesheet_directory_uri() . '/style.css',
					array(),
					wp_get_theme()->get('Version')
				);
			}

			add_action( 'wp_enqueue_scripts', '{$slug_no_hyphens}_child_enqueue_styles', 11 );

		";

		// Let's remove the tabs so that it displays nicely.
		$output = trim( preg_replace( '/\t+/', '', $output ) );

		// Filterable return.
		return $output;
	}

	/**
	 * Content template for the child theme style.css file.
	 *
	 * @param string $content File content.
	 * @param string $slug Parent theme slug.
	 * @param string $parent Parent theme name.
	 * @param string $version Parent theme version.
	 *
	 * @return string
	 */
	public function generate_child_style_css( $content, $slug, $parent, $version ) {
		$output = "
			/**
			* Theme Name: {$parent} Child
			* Description: This is a child theme of {$parent}.
			* Author: cmsmasters
			* Author URI: https://cmsmasters.net/
			* Template: {$slug}
			* Version: {$version}
			*/\n
		";

		// Let's remove the tabs so that it displays nicely.
		$output = trim( preg_replace( '/\t+/', '', $output ) );

		// Filterable return.
		return $output;
	}

}
