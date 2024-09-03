<?php
namespace CmsmastersEI\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Export handler class is responsible for different methods of exporting options.
 *
 * @since 1.0.0
 */
class Export {
	/**
	 * File prefix.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $file_prefix = '';

	/**
	 * Export constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'export' ), 999999 );
	}

	/**
	 * Export theme data.
	 *
	 * @since 1.0.0
	 */
	public function export( $wp_customize ) {
		if ( ! isset( $wp_customize ) ) {
			return;
		}

		$this->file_prefix = get_stylesheet() . '-' . sanitize_title( get_bloginfo( 'name' ) ) . '-';

		$this->export_options( $wp_customize );

		$this->export_theme_options();

		$this->export_kits();

		$this->export_givewp_form_meta();
	}

	/**
	 * Export options.
	 *
	 * @since 1.0.0
	 */
	public function export_options( $wp_customize ) {
		if (
			! isset( $_REQUEST['cmsmasters-ei-export-options'] ) ||
			! wp_verify_nonce( $_REQUEST['cmsmasters-ei-export-options'], 'cmsmasters-ei-exporting' )
		) {
			return;
		}

		if ( isset( $_REQUEST['template'] ) && ! empty( $_REQUEST['template'] ) ) {
			$template = $_REQUEST['template'];
		} else {
			$template = get_template();
		}

		$mods = get_theme_mods();
		$data = array(
			'template' => $template,
			'mods' => $mods ? $mods : array(),
			'options' => array(),
		);
		$not_export_keys = apply_filters( 'cmsmasters_ei_not_export_keys', array(
			'blogname',
			'blogdescription',
			'show_on_front',
			'page_on_front',
			'page_for_posts',
		) );
		$not_export_keys_parts = apply_filters( 'cmsmasters_ei_not_export_keys_parts', array() );

		// Get options from the Customizer API.
		$settings = $wp_customize->settings();

		foreach ( $settings as $key => $setting ) {
			if ( 'option' == $setting->type ) {
				// Don't save widget data.
				if ( 'widget_' === substr( strtolower( $key ), 0, 7 ) ) {
					continue;
				}

				// Don't save sidebar data.
				if ( 'sidebars_' === substr( strtolower( $key ), 0, 9 ) ) {
					continue;
				}

				// Don't save filtered options keys.
				if ( in_array( $key, $not_export_keys ) ) {
					continue;
				}

				// Don't save options filtered parts of keys.
				if ( is_array( $not_export_keys_parts ) && ! empty( $not_export_keys_parts ) ) {
					$pos = false;

					foreach ( $not_export_keys_parts as $key_part ) {
						if ( false !== strpos( $key, $key_part ) ) {
							$pos = true;
						}
					}

					if ( true === $pos ) {
						continue;
					}
				}

				$data['options'][ $key ] = $setting->value();
			}
		}

		// Plugin developers can specify additional option keys to export.
		$option_keys = apply_filters( 'cei_export_option_keys', array() );
		$option_keys = apply_filters( 'cmsmasters_ei_export_option_keys', $option_keys );

		foreach ( $option_keys as $option_key ) {
			$data['options'][ $option_key ] = get_option( $option_key );
		}

		if ( function_exists( 'wp_get_custom_css_post' ) ) {
			$data['wp_css'] = wp_get_custom_css();
		}

		// Set the download headers.
		header( 'Content-disposition: attachment; filename=' . $this->file_prefix . 'options.dat' );
		header( 'Content-Type: application/octet-stream; charset=' . get_option( 'blog_charset' ) );

		// Serialize the export data.
		echo serialize( $data );

		// Start the download.
		die();
	}

	/**
	 * Export theme options.
	 *
	 * @since 1.0.0
	 */
	public function export_theme_options() {
		if (
			! isset( $_REQUEST['cmsmasters-ei-export-theme-options'] ) ||
			! wp_verify_nonce( $_REQUEST['cmsmasters-ei-export-theme-options'], 'cmsmasters-ei-exporting' )
		) {
			return;
		}

		$option_name = apply_filters( 'cmsmasters_ei_export_theme_options_name', '' );

		if ( empty( $option_name ) ) {
			return;
		}

		$data = get_option( $option_name );

		if ( empty( $data ) ) {
			return;
		}

		// Set the download headers.
		header( 'Content-disposition: attachment; filename=' . $this->file_prefix . 'theme-options.json' );
		header( 'Content-Type: application/octet-stream; charset=' . get_option( 'blog_charset' ) );

		// Encoded the export data.
		echo json_encode( $data );

		// Start the download.
		die();
	}

	/**
	 * Export elementor kit settings.
	 *
	 * @since 1.0.0
	 */
	public function export_kits() {
		if (
			! isset( $_REQUEST['cmsmasters-ei-export-kits'] ) ||
			! wp_verify_nonce( $_REQUEST['cmsmasters-ei-export-kits'], 'cmsmasters-ei-exporting' )
		) {
			return;
		}

		$active_kit = get_option( 'elementor_active_kit', '' );

		if ( ! $active_kit || '' === $active_kit ) {
			return;
		}

		$data = get_post_meta( $active_kit, '_elementor_page_settings', true );

		if ( ! $data && '' === $data ) {
			return;
		}

		// Set the download headers.
		header( 'Content-disposition: attachment; filename=' . $this->file_prefix . 'kits.json' );
		header( 'Content-Type: application/octet-stream; charset=' . get_option( 'blog_charset' ) );

		// Encoded the export data.
		echo json_encode( $data );

		// Start the download.
		die();
	}

	/**
	 * Export givewp form meta.
	 *
	 * @since 1.0.0
	 */
	public function export_givewp_form_meta() {
		if (
			! isset( $_REQUEST['cmsmasters-ei-export-givewp-form-meta'] ) ||
			! wp_verify_nonce( $_REQUEST['cmsmasters-ei-export-givewp-form-meta'], 'cmsmasters-ei-exporting' ) ||
			! class_exists( 'Give' )
		) {
			return;
		}

		$data = array();

		$query = new \WP_Query( array(
			'posts_per_page' => -1,
			'post_type' => 'give_forms',
		) );

		if ( ! $query->have_posts() ) {
			return;
		}

		foreach ( $query->posts as $post ) {
			$form_meta = Give()->form_meta->get_meta( $post->ID );

			foreach ( $form_meta as $meta_key => $meta_value ) {
				$data[ $post->ID ][ $meta_key ] = maybe_unserialize( $meta_value[0] );
			}
		}

		if ( empty( $data ) ) {
			return;
		}

		// Set the download headers.
		header( 'Content-disposition: attachment; filename=' . $this->file_prefix . 'givewp-form-meta.json' );
		header( 'Content-Type: application/octet-stream; charset=' . get_option( 'blog_charset' ) );

		// Encoded the export data.
		echo json_encode( $data );

		// Start the download.
		die();
	}

}
