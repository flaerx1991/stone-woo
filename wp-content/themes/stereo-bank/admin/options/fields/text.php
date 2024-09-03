<?php
namespace StereoBankSpace\Admin\Options\Fields;

use StereoBankSpace\Admin\Options\Options_Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Handler class is responsible for options field.
 */
class Text {

	/**
	 * Render field.
	 *
	 * @param array $field_args Field args.
	 */
	public static function render( $field_args = array() ) {
		foreach ( $field_args as $field_key => $field_value ) {
			$$field_key = $field_value;
		}

		echo '<div class="cmsmasters-options-field-' . esc_attr( $type ) . '">' .
			Options_Utils::get_field_label( $label, $id ) .
			'<input' .
				' class="regular-text"' .
				' type="text"' .
				' id="' . esc_attr( $id ) . '"' .
				' name="' . esc_attr( $name ) . '"' .
				' value="' . esc_attr( $value ) . '"' .
			'/>' .
			Options_Utils::get_field_postfix( $postfix ) .
			Options_Utils::get_field_desc( $desc ) .
		'</div>';
	}

	/**
	 * Validate field value.
	 *
	 * @param string $id Field id.
	 * @param string $value Field value.
	 * @param array $args Field args.
	 *
	 * @return string Validated value.
	 */
	public static function validate( $id, $value, $args ) {
		$out = '';

		switch ( $args['subtype'] ) {
			case 'numeric':
				$value = trim( $value );

				$out = ( is_numeric( $value ) ? $value : '' );

				if ( false === is_numeric( $value ) ) {
					add_settings_error(
						$id,
						'cmsmasters_txt_numeric_error',
						esc_html__( 'Expecting a Numeric value! Please fix.', 'stereo-bank' ),
						'error'
					);
				}

				break;
			case 'multinumeric':
				$value = trim( $value );

				if ( '' !== $value ) {
					$out = ( 1 === preg_match( '/^-?\d+(?:,\s?-?\d+)*$/', $value ) ? $value : esc_html__( 'Expecting comma separated numeric values', 'stereo-bank' ) );
				} else {
					$out = $value;
				}

				if ( '' !== $value && 1 !== preg_match( '/^-?\d+(?:,\s?-?\d+)*$/', $value ) ) {
					add_settings_error(
						$id,
						'cmsmasters_txt_multinumeric_error',
						esc_html__( 'Expecting comma separated numeric values! Please fix.', 'stereo-bank' ),
						'error'
					);
				}

				break;
			case 'nohtml':
				$value = sanitize_text_field( $value );

				$out = addslashes( $value );

				break;
			case 'url':
				$value = trim( $value );

				$out = esc_url_raw( $value );

				break;
			case 'email':
				$value = trim( $value );

				if ( '' !== $value ) {
					$out = ( false !== is_email( $value ) ? $value : esc_html__( 'Invalid email! Please re-enter!', 'stereo-bank' ) );
				} elseif ( '' === $value ) {
					$out = esc_html__( 'This setting field cannot be empty! Please enter a valid email address.', 'stereo-bank' );
				}

				if ( false === is_email( $value ) || '' === $value ) {
					add_settings_error(
						$id,
						'cmsmasters_txt_email_error',
						esc_html__( 'Please enter a valid email address.', 'stereo-bank' ),
						'error'
					);
				}

				break;
			default:
				$out = addslashes( wp_kses_post( force_balance_tags( trim( $value ) ) ) );

				break;
		}

		return $out;
	}

}
