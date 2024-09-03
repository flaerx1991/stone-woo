<?php
namespace CmsmastersElementor\Modules\WidgetPresets\Core;

use CmsmastersElementor\Plugin;
use CmsmastersElementor\Utils;

use Elementor\Core\Common\Modules\Ajax\Module as Ajax;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Presets_Custom {

	const OPTION_NAME = '_cmsmasters_presets_custom';

	/**
	 * Presets Custom class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->init_actions();
	}

	/**
	 * Add actions initialization.
	 *
	 * Register filters for the Presets Custom.
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
		$ajax->register_ajax_action( 'cmsmasters_presets_custom_get', array( $this, 'get_presets_by_user' ) );
		$ajax->register_ajax_action( 'cmsmasters_preset_custom_add', array( $this, 'add_preset' ) );
		$ajax->register_ajax_action( 'cmsmasters_preset_custom_delete', array( $this, 'delete_preset' ) );
	}


	/**
	 * Save new preset data using ajax.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 *
	 * @param $data.
	 *
	 * @return array new preset data.
	 */
	public function add_preset( $data ) {
		$this->check_data( $data, array( 'title', 'settings' ) );

		$widget = sanitize_text_field( wp_unslash( $data['widget'] ) );
		$title = sanitize_text_field( wp_unslash( $data['title'] ) );

		$presets = $this->get_widget_presets( $widget );
		$preset_id = $this->generate_preset_id();
		$presets[ $preset_id ] = array(
			'settings' => $data['settings'],
			'title' => $title,
			'user_id' => get_current_user_id(),
		);

		$this->set_widget_presets( $widget, $presets );

		return array(
			'preset_id' => $preset_id,
			'presets' => $this->get_presets_by_user( $data ),
		);
	}

	/**
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 */
	private function check_data( $data, array $fields = array() ) {
		// widget is default field
		$fields[] = 'widget';

		foreach ( $fields as $field ) {
			if ( empty( $data[ $field ] ) ) {
				throw new \Exception( "'{$field}' field is missing." );
			}

			if (
				'widget' === $field &&
				! Plugin::elementor()->widgets_manager->get_widget_types( $data['widget'] )
			) {
				throw new \Exception( 'Can\'t find widget' );
			}
		}
	}

	public function get_widget_presets( $widget ) {
		return Utils::get_if_isset( $this->get_presets_option(), $widget, array() );
	}

	public function get_presets_option() {
		return get_option( self::OPTION_NAME, array() );
	}

	protected function generate_preset_id() {
		return uniqid( time() );
	}

	public function set_widget_presets( $widget, $presets ) {
		$presets_option = $this->get_presets_option();

		$presets_option[ $widget ] = $presets;

		$this->set_presets_option( $presets_option );

		return $presets;
	}

	public function set_presets_option( $presets_option ) {
		return update_option( self::OPTION_NAME, $presets_option );
	}

	/**
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 */
	public function get_presets_by_user( $data ) {
		$user_id = get_current_user_id();

		return array_filter(
			$this->get_presets( $data ),
			function( $preset ) use ( $user_id ) {
				return isset( $preset['user_id'] ) && $preset['user_id'] === $user_id;
			}
		);
	}

	/**
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 */
	public function get_presets( $data ) {
		$this->check_data( $data );

		$widget = sanitize_text_field( wp_unslash( $data['widget'] ) );

		return $this->get_widget_presets( $widget );
	}

	/**
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 */
	public function delete_preset( $data ) {
		$this->check_data( $data, array( 'preset_id' ) );

		$widget = sanitize_text_field( wp_unslash( $data['widget'] ) );
		$preset_id = sanitize_text_field( wp_unslash( $data['preset_id'] ) );

		$presets = $this->get_widget_presets( $widget );

		if ( isset( $presets[ $preset_id ] ) ) {
			unset( $presets[ $preset_id ] );

			$this->set_widget_presets( $widget, $presets );
		}

		return $this->get_presets_by_user( $data );
	}

}
