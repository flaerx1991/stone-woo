<?php
namespace CmsmastersEI\Modules;

use CmsmastersEI\Modules\Setting;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Import handler class is responsible for different methods of importing options.
 *
 * @since 1.0.0
 */
class Import {

	/**
	 * Error message.
	 *
	 * @since 1.0.0
	 */
	protected $error_message = '';

	/**
	 * Import constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'import' ), 999999 );

		add_action( 'customize_controls_print_scripts', array( $this, 'controls_print_scripts' ) );
	}

	/**
	 * Imports uploaded mods and calls customize_save actions.
	 *
	 * @since 1.0.0
	 *
	 * @param object $wp_customize Instance of WP_Customize_Manager.
	 */
	public function import( $wp_customize ) {
		if (
			! isset( $_REQUEST['cmsmasters-ei-import'] ) ||
			! isset( $_FILES['cmsmasters-ei-import-file'] ) ||
			! isset( $wp_customize ) ||
			! wp_verify_nonce( $_REQUEST['cmsmasters-ei-import'], 'cmsmasters-ei-importing' )
		) {
			return;
		}

		// Make sure WordPress upload support is loaded.
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		$file = wp_handle_upload( $_FILES['cmsmasters-ei-import-file'], array(
			'test_form' => false,
			'test_type' => false,
			'mimes' => array(
				'dat' => 'text/plain',
			),
		) );

		// Make sure we have an uploaded file.
		if ( isset( $file['error'] ) ) {
			$this->error_message = $file['error'];

			return;
		}

		if ( ! file_exists( $file['file'] ) ) {
			$this->error_message = esc_html__( 'Error importing settings! Please try again.', 'cmsmasters-ei' );

			return;
		}

		// Get the upload data.
		$raw = file_get_contents( $file['file'] );
		$data = @unserialize( $raw );

		// Remove the uploaded file.
		unlink( $file['file'] );

		// Data checks.
		if ( 'array' != gettype( $data ) ) {
			$this->error_message = esc_html__( 'Error importing settings! Please check that you uploaded a customizer export file.', 'cmsmasters-ei' );

			return;
		}
		if ( ! isset( $data['template'] ) || ! isset( $data['mods'] ) ) {
			$this->error_message = esc_html__( 'Error importing settings! Please check that you uploaded a customizer export file.', 'cmsmasters-ei' );

			return;
		}
		if ( $data['template'] != get_template() ) {
			$this->error_message = esc_html__( 'Error importing settings! The settings you uploaded are not for the current theme.', 'cmsmasters-ei' );

			return;
		}

		// Import images.
		if ( isset( $_REQUEST['cmsmasters-ei-import-images'] ) ) {
			$data['mods'] = $this->import_images( $data['mods'] );
		}

		// Import custom options.
		if ( isset( $data['options'] ) ) {
			foreach ( $data['options'] as $option_key => $option_value ) {
				$option = new Setting( $wp_customize, $option_key, array(
					'default'		=> '',
					'type'			=> 'option',
					'capability'	=> 'edit_theme_options'
				) );

				$option->import( $option_value );
			}
		}

		// If wp_css is set then import it.
		if( function_exists( 'wp_update_custom_css_post' ) && isset( $data['wp_css'] ) && '' !== $data['wp_css'] ) {
			wp_update_custom_css_post( $data['wp_css'] );
		}

		// Call the customize_save action.
		do_action( 'customize_save', $wp_customize );

		// Loop through the mods.
		foreach ( $data['mods'] as $key => $val ) {
			// Call the customize_save_ dynamic action.
			do_action( 'customize_save_' . $key, $wp_customize );

			// Save the mod.
			set_theme_mod( $key, $val );
		}

		// Call the customize_save_after action.
		do_action( 'customize_save_after', $wp_customize );
	}

	/**
	 * Imports images for settings saved as mods.
	 *
	 * @since 1.0.0
	 *
	 * @param array $mods Customizer mods.
	 *
	 * @return array mods array with any new import data.
	 */
	public function import_images( $mods ) {
		foreach ( $mods as $key => $val ) {
			if ( $this->is_image_url( $val ) ) {
				$data = $this->sideload_image( $val );

				if ( ! is_wp_error( $data ) ) {
					$mods[ $key ] = $data->url;

					// Handle header image controls.
					if ( isset( $mods[ $key . '_data' ] ) ) {
						$mods[ $key . '_data' ] = $data;

						update_post_meta( $data->attachment_id, '_wp_attachment_is_custom_header', get_stylesheet() );
					}
				}
			}
		}

		return $mods;
	}

	/**
	 * Taken from the core media_sideload_image function and
	 * modified to return an array of data instead of html.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file The image file path.
	 *
	 * @return array Array of image data.
	 */
	public function sideload_image( $file ) {
		$data = new stdClass();

		if ( ! function_exists( 'media_handle_sideload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
		}

		if ( ! empty( $file ) ) {
			// Set variables for storage, fix file filename for query strings.
			preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches );
			$file_array = array();
			$file_array['name'] = basename( $matches[0] );

			// Download file to temp location.
			$file_array['tmp_name'] = download_url( $file );

			// If error storing temporarily, return the error.
			if ( is_wp_error( $file_array['tmp_name'] ) ) {
				return $file_array['tmp_name'];
			}

			// Do the validation and storage stuff.
			$id = media_handle_sideload( $file_array, 0 );

			// If error storing permanently, unlink.
			if ( is_wp_error( $id ) ) {
				@unlink( $file_array['tmp_name'] );

				return $id;
			}

			// Build the object to return.
			$meta = wp_get_attachment_metadata( $id );
			$data->attachment_id = $id;
			$data->url = wp_get_attachment_url( $id );
			$data->thumbnail_url = wp_get_attachment_thumb_url( $id );
			$data->height = $meta['height'];
			$data->width = $meta['width'];
		}

		return $data;
	}

	/**
	 * Checks to see whether a string is an image url or not.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string The string to check.
	 *
	 * @return bool Whether the string is an image url or not.
	 */
	public function is_image_url( $string = '' ) {
		if ( is_string( $string ) ) {
			if ( preg_match( '/\.(jpg|jpeg|png|gif)/i', $string ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Prints scripts for the controls.
	 *
	 * @since 1.0.0
	 */
	public function controls_print_scripts() {
		if ( '' !== $this->error_message ) {
			echo "<script>
				alert({$this->error_message});
			</script>";
		}
	}

}
