<?php
/**
 * Main Theme Functions
 */

define( 'CMSMASTERS_THEME_VERSION', '1.0.0' );

// CMSMasters API
define( 'CMSMASTERS_API_ROUTES_URL', 'http://api.cmsmasters.net/wp-json/cmsmasters-api/v1/' );

// Theme options
define( 'CMSMASTERS_OPTIONS_PREFIX', 'cmsmasters_stereo-bank_' );
define( 'CMSMASTERS_OPTIONS_NAME', 'cmsmasters_stereo-bank_options' );

/*
 * Register Elementor Locations
 */
if ( ! function_exists( 'cmsmasters_register_elementor_locations' ) ) {
	function cmsmasters_register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'cmsmasters_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}

add_action( 'elementor/theme/register_locations', 'cmsmasters_register_elementor_locations' );

// require files
require_once get_parent_theme_file_path( '/core/starter.php' );
