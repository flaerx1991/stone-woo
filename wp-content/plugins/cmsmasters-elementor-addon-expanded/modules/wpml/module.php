<?php
namespace CmsmastersElementor\Modules\Wpml;

use CmsmastersElementor\Base\Base_Module;
use CmsmastersElementor\Plugin;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * WPML module.
 *
 * @since 1.3.3
 */
class Module extends Base_Module {

	/**
	 * Get name.
	 *
	 * Retrieve the module name.
	 *
	 * @since 1.3.3
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'wpml';
	}

	/**
	 * Module activation.
	 *
	 * Check if module is active.
	 *
	 * @since 1.3.3
	 *
	 * @return bool
	 */
	public static function is_active() {
		return did_action( 'wpml_loaded' );
	}

	/**
	 * Init filters.
	 *
	 * Initialize module filters.
	 *
	 * @since 1.3.3
	 */
	protected function init_filters() {
		add_filter( 'wpml_elementor_widgets_to_translate', array( $this, 'get_translatable_widgets' ) );

		add_filter( 'cmsmasters_wpml_translate_template_id', array( $this, 'get_translated_template_id' ) );
	}

	/**
	 * Get translatable widgets.
	 *
	 * @since 1.3.3
	 *
	 * @param array $widgets Translatable widgets.
	 *
	 * @return array Filtered translatable widgets.
	 */
	public function get_translatable_widgets( $widgets ) {
		foreach ( Plugin::elementor()->widgets_manager->get_widget_types() as $widget_key => $widget_obj ) {
			if ( false === strpos( $widget_key, 'cmsmasters' ) ) {
				continue;
			}

			$fields = $widget_obj::get_wpml_fields();
			$fields_in_item = $widget_obj::get_wpml_fields_in_item();

			if ( empty( $fields ) && empty( $fields_in_item ) ) {
				continue;
			}

			if ( ! empty( $fields ) ) {
				foreach ( $fields as $index => $field ) {
					$fields[ $index ]['type'] = $field['type'] . ' (' . $widget_obj->get_title() . ')';
				}
			}

			if ( ! empty( $fields_in_item ) ) {
				foreach ( $fields_in_item as $item_key => $item_fields ) {
					foreach ( $item_fields as $item_field_index => $item_field ) {
						$fields_in_item[ $item_key ][ $item_field_index ]['type'] = $item_field['type'] . ' (' . $widget_obj->get_title() . ')';
					}
				}
			}

			$widgets[ $widget_key ] = array(
				'conditions' => array(
					'widgetType' => $widget_key,
				),
				'fields' => $fields,
				'fields_in_item' => $fields_in_item,
			);
		}

		return $widgets;
	}

	/**
	 * Get translated template id.
	 *
	 * @since 1.3.3
	 *
	 * @param int $template_id Template id.
	 *
	 * @return int Translated template id.
	 */
	public function get_translated_template_id( $template_id ) {
		if ( empty( $template_id ) ) {
			return $template_id;
		}

		$post_type = get_post_type( $template_id );

		return apply_filters( 'wpml_object_id', $template_id, $post_type, true );
	}

}
