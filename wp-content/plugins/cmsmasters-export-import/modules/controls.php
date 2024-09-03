<?php
namespace CmsmastersEI\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Render export/import controls in customizer.
 *
 * @since 1.0.0
 */
final class Controls extends \WP_Customize_Control {

	/**
	 * Renders controls.
	 *
	 * @since 1.0.0
	 */
	protected function render_content() {
		echo '<span class="customize-control-title">' .
			esc_html__( 'Export', 'cmsmasters-ei' ) .
		'</span>' .
		'<span class="description customize-control-description">' .
			esc_html__( 'Click the button below to export the customization settings for this theme.', 'cmsmasters-ei' ) . '<br /><br />' .
			'<label for="cmsmasters-ei-export-template-name">' . esc_html__( 'Export Template Name', 'cmsmasters-ei' ) . '</label>' .
			'<input type="text" id="cmsmasters-ei-export-template-name" name="cmsmasters-ei-export-template-name" value="' . get_template() . '" /> ' .
		'</span>' .
		'<input type="button" class="button cmsmasters-ei-button" name="cmsmasters-ei-export-theme-options" data-ei-type="export-theme-options" value="' . esc_attr__( 'Theme Options', 'cmsmasters-ei' ) . '" /> ' .
		'<input type="button" class="button cmsmasters-ei-button" name="cmsmasters-ei-export-options" data-ei-type="export-options" value="' . esc_attr__( 'Options', 'cmsmasters-ei' ) . '" /> ' .
		'<input type="button" class="button cmsmasters-ei-button" name="cmsmasters-ei-export-kits" data-ei-type="export-kits" value="' . esc_attr__( 'Kits', 'cmsmasters-ei' ) . '" />';

		if ( class_exists( 'Give' ) ) {
			echo '<input type="button" class="button cmsmasters-ei-button" name="cmsmasters-ei-export-givewp-form-meta" data-ei-type="export-givewp-form-meta" value="' . esc_attr__( 'GiveWP Form Meta', 'cmsmasters-ei' ) . '" />';
		}
		// '<hr class="cmsmasters-ei-hr" />' .
		// '<span class="customize-control-title">' .
		// 	esc_html__( 'Import', 'cmsmasters-ei' ) .
		// '</span>' .
		// '<span class="description customize-control-description">' .
		// 	esc_html__( 'Upload a file to import customization settings for this theme.', 'cmsmasters-ei' ) .
		// '</span>' .
		// '<div class="cmsmasters-ei-import-controls">' .
		// 	'<input type="file" name="cmsmasters-ei-import-file" class="cmsmasters-ei-import-file" />' .
		// 	'<label class="cmsmasters-ei-import-images">' .
		// 		'<input type="checkbox" name="cmsmasters-ei-import-images" value="1" /> ' .
		// 		esc_html__( 'Download and import image files?', 'cmsmasters-ei' ) .
		// 	'</label>';

		// 	wp_nonce_field( 'cmsmasters-ei-importing', 'cmsmasters-ei-import' );

		// echo '</div>' .
		// '<div class="cmsmasters-ei-uploading">' . esc_html__( 'Uploading...', 'cmsmasters-ei' ) . '</div>' .
		// '<input type="button" class="button cmsmasters-ei-button" name="cmsmasters-ei-import-button" data-ei-type="import" value="' . esc_attr__( 'Import', 'cmsmasters-ei' ) . '" />';
	}

}
