<?php
namespace CmsmastersElementor\Controls\Groups\Base;

use CmsmastersElementor\Utils;

use Elementor\Group_Control_Base;
use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


abstract class Group_Control_Format_Date_Time extends Group_Control_Base {

	protected function init_fields() {
		$fields = array();
		$format_general = static::get_field_format_option();

		$fields[ static::get_name_field() ] = array(
			'label' => static::get_format_label(),
			'type' => Controls_Manager::SELECT,
			'options' => $this->get_format_options(),
		);

		$fields[ static::get_name_field_custom() ] = array(
			'label' => static::get_custom_format_label(),
			'type' => Controls_Manager::TEXT,
			'description' => sprintf(
				'%1$s: <a href="https://wordpress.org/support/article/formatting-date-and-time/" target="_blank">%2$s</a>',
				__( 'Link', 'cmsmasters-elementor' ),
				__( 'Documentation on date and time formatting', 'cmsmasters-elementor' )
			),
			'default' => $format_general,
			'placeholder' => $format_general,
			'condition' => array(
				static::get_name_field() => 'custom',
			),
		);

		return $fields;
	}

	protected function filter_fields() {
		$fields = parent::filter_fields();
		$args = $this->get_args();

		if ( ! empty( $args['human_readable'] ) ) {
			$fields[ static::get_name_field() ]['options']['human_readable'] = __( 'Human Readable', 'cmsmasters-elementor' );
		}

		return $fields;
	}

	/**
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 * @since 1.3.2 Fixed date translations.
	 * @since 1.4.0 Added display of the date and time of the post update.
	 *
	 * @return string
	 */
	public static function get_render_format( $prefix, $settings, $timestamp, $modified = false ) {
		$format = static::get_format( $prefix, $settings );

		if ( 'human_readable' === $format ) {
			/* translators: %s: time ago */
			return sprintf( __( '%s ago', 'cmsmasters-elementor' ), human_time_diff( $timestamp ) );
		} else {
			if ( false === $modified ) {
				return date_i18n( $format, $timestamp );
			} else {
				return the_modified_date( $format );
			}
		}
	}

	protected function get_default_options() {
		return array(
			'popover' => false,
		);
	}

	protected function get_child_default_args() {
		return array(
			'human_readable' => true,
		);
	}

	private static function get_name_field() {
		return static::get_field_type() . '_format';
	}

	private static function get_name_field_custom() {
		return static::get_name_field() . '_custom';
	}

	/**
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 *
	 * @return string
	 */
	public static function get_format( $prefix, $settings ) {
		$format_name = $prefix . '_' . static::get_name_field();
		$format_name_custom = $prefix . '_' . static::get_name_field_custom();
		$format = Utils::get_if_isset( $settings, $format_name );

		if ( $format && 'custom' === $format && isset( $settings[ $format_name_custom ] ) ) {
			$format = $settings[ $format_name_custom ];
		}

		if ( ! $format ) {
			$format = static::get_field_format_option();
		}

		return $format;
	}

	protected static function get_format_options() {
		$format_general = static::get_field_format_option();
		$formats_general = static::get_main_formats();

		/* Translate Date Formats */
		$format_i18n = array(
			'' => __( 'Default', 'cmsmasters-elementor' ) . ' - ' . date_i18n( $format_general ),
		);

		foreach ( $formats_general as $format ) {
			$format_i18n[ $format ] = date_i18n( $format ) . "  ({$format})";
		}

		$format_i18n['custom'] = __( 'Custom', 'cmsmasters-elementor' );

		return $format_i18n;
	}

	/**
	 * @return string
	 */
	protected static function get_custom_format_label() {
		return __( 'Custom Format', 'cmsmasters-elementor' );
	}

	/**
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP Strict Standards.
	 *
	 * @return string
	 */
	protected static function get_field_type() {}

	/**
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP Strict Standards.
	 *
	 * @return string
	 */
	protected static function get_format_label() {}

	/**
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP Strict Standards.
	 *
	 * @return array
	 */
	protected static function get_main_formats() {
		return array();
	}

	/**
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP Strict Standards.
	 *
	 * @return string
	 */
	protected static function get_field_format_option() {}

}
