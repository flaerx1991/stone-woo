<?php
namespace CmsmastersEI\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Extends WP_Customize_Setting to access
 * the protected updated method when importing options.
 *
 * @since 1.0.0
 */
final class Setting extends \WP_Customize_Setting {

	/**
	 * Import an option value for this setting.
	 *
	 * @since 0.3
	 * @param mixed $value The option value.
	 * @return void
	 */
	public function import( $value ) {
		$this->update( $value );	
	}

}
