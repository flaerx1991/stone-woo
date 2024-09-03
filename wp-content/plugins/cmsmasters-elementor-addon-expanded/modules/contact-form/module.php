<?php
namespace CmsmastersElementor\Modules\ContactForm;

use CmsmastersElementor\Base\Base_Module;
use Elementor\Plugin;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * CMSMasters Elementor Contact form 7 module.
 *
 * @since 1.0.0
 */
class Module extends Base_Module {

	/**
	 * Add actions initialization.
	 *
	 * Register actions for the Preview app.
	 *
	 * @since 1.1.0
	 */
	protected function init_actions() {
		if ( class_exists( 'Forminator' ) ) {
			add_action( 'elementor/preview/enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}
	}

	/**
	 * Add filters initialization.
	 *
	 * Register filters for the Preview app.
	 *
	 * @since 1.2.1
	 */
	protected function init_filters() {
		if ( class_exists( 'Forminator' ) ) {
			add_filter( 'forminator_field_single_markup', array( $this, 'field_single_new_markup' ), 10, 2 );
			add_filter( 'forminator_field_address_markup', array( $this, 'field_group_new_markup' ), 10, 2 );
			add_filter( 'forminator_field_time_markup', array( $this, 'field_group_new_markup' ), 10, 2 );
		}
	}

	/**
	 * Get name.
	 *
	 * Retrieve the module name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'cmsmasters-contact-form';
	}

	/**
	 * Module activation.
	 *
	 * Check if module is active.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_active() {
		return function_exists( 'wpcf7' ) || function_exists( 'wpforms' ) || class_exists( 'Forminator' );
	}

	/**
	 * Get widgets.
	 *
	 * Retrieve the module widgets.
	 *
	 * @since 1.0.0
	 *
	 * @return array Module widgets.
	 */
	public function get_widgets() {
		$widgets = array();

		if ( function_exists( 'wpcf7' ) ) {
			$widgets[] = 'Contact_Form_Seven';
		}

		if ( function_exists( 'wpforms' ) ) {
			$widgets[] = 'WP_Form';
		}

		if ( class_exists( 'Forminator' ) ) {
			$widgets[] = 'CMS_Forminator';

		}

		return $widgets;
	}


	/**
	 * Enqueue scripts
	 *
	 * Connects the necessary scripts to the preview.
	 *
	 * @since 1.1.0
	 */
	public function enqueue_scripts() {
		if ( class_exists( 'Forminator' ) ) {
			$form_obj = new \Forminator_GFBlock_Forms();

			$form_obj->load_assets();
		}
	}

	/**
	 * Fields ID.
	 *
	 * Contains new ids for fields
	 *
	 * @since 1.2.1
	 *
	 * @var array
	 */
	private static $formi_used_ids_array = null;

	/**
	 * Fixed single fields
	 *
	 * Replacement of the identifier for single fields.
	 *
	 * @since 1.2.1
	 */
	public function field_single_new_markup( $html, $element_id ) {
		$key = 'selects';
		if ( false !== strpos( $element_id, 'select-' ) ) {

			$field_id = explode( '-', $element_id );
			$field_id = intval( $field_id[1] );
			if ( $field_id > 0 ) {
				if ( ! empty( self::$formi_used_ids_array[ $key ] ) ) {

					$id_exists = array_search( $field_id, self::$formi_used_ids_array[ $key ], true );

					if ( false !== $id_exists ) {
						$new_id = $this->generate_new_id( $key );
						preg_match_all( '/id="(.*?)"/', $html, $matches );
						$id_string = sprintf( 'id="%s"', preg_replace( '/\d+/', $new_id, $matches[1][0] ) );
						$html      = str_replace( $matches[0][0], $id_string, $html );
						$this->push_used_id( $new_id, $key );
					} else {
						$this->push_used_id( $field_id, $key );
					}
				} else {
					$this->push_used_id( $field_id, $key );
				}
			}
		}
		return $html;
	}

	/**
	 * Fixed group fields
	 *
	 * Replacement of the identifier for group fields.
	 *
	 * @since 1.2.1
	 */
	public function field_group_new_markup( $html, $field ) {
		$key = ( false === strpos( $field['element_id'], 'time-' ) ) ? 'addresses' : 'times';
		$field_id = (int) substr( $field['element_id'], strrpos( $field['element_id'], '-' ) + 1 );

		if ( ! empty( self::$formi_used_ids_array[ $key ] ) ) {

			$id_exists = array_search( $field_id, self::$formi_used_ids_array[ $key ], true );

			if ( false !== $id_exists ) {
				preg_match_all( '/id="(.*?)"/', $html, $matches );
				if ( ! empty( $matches[1] ) ) {
					$new_id = $this->generate_new_id( $key );

					foreach ( $matches[1] as $v ) {
						$html = str_replace( $v, preg_replace( '/\d+/', $new_id, $v ), $html );
					}

					$this->push_used_id( $new_id, $key );
				}
			} else {
				$this->push_used_id( $field_id, $key );
			}
		} else {
			$this->push_used_id( $field_id, $key );
		}

		return $html;
	}

	private function push_used_id( $id, $key ) {
		self::$formi_used_ids_array[ $key ][] = $id;
	}

	private function generate_new_id( $key ) {
		$new_ids_array = array_diff( range( 51, 100 ), self::$formi_used_ids_array[ $key ] );
		return $new_ids_array[ array_rand( $new_ids_array, 1 ) ];
	}
}
