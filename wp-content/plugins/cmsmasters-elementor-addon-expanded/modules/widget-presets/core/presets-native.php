<?php
namespace CmsmastersElementor\Modules\WidgetPresets\Core;

use CmsmastersElementor\Utils;

use Elementor\Core\Common\Modules\Ajax\Module as Ajax;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Presets_Native {

	/**
	 * Presets Native class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->init_actions();
	}

	/**
	 * Add actions initialization.
	 *
	 * Register filters for the Presets Native.
	 *
	 * @since 1.0.0
	 */
	protected function init_actions() {
		add_action( 'elementor/ajax/register_actions', array( $this, 'register_ajax_actions' ) );
	}

	/**
	 * Register ajax actions.
	 *
	 * Process ajax action handles when getting native presets.
	 *
	 * Fired by `elementor/ajax/register_actions` action.
	 *
	 * @since 1.0.0
	 *
	 * @param Ajax $ajax_manager An instance of the ajax manager.
	 */
	public function register_ajax_actions( Ajax $ajax ) {
		$ajax->register_ajax_action( 'cmsmasters_presets_native_get', array( $this, 'get_widget_presets' ) );
	}

	/**
	 * Get all presets.
	 *
	 * @since 1.0.0
	 *
	 * @param array.
	 */
	public function get_presets() {
		return apply_filters( 'cmsmasters_elementor/widget_presets/native/list', array(
			'heading' => array(
				'default' => array(
					'title' => 'Default',
					'url_image_demo' => 'https://via.placeholder.com/300x150',
					'settings' => array(
						'title_color' => '#ffff00',
					),
				),
			),
		) );
	}

	/**
	 * Get presets by widget.
	 *
	 * @since 1.0.0
	 *
	 * @param array.
	 */
	public function get_widget_presets( $data ) {
		if ( ! isset( $data['widget'] ) ) {
			throw new \Exception( "'Widget' field is missing." );
		}

		return Utils::get_if_isset( $this->get_presets(), $data['widget'], array() );
	}

}
