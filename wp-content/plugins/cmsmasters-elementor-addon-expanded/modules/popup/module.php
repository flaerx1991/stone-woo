<?php
namespace CmsmastersElementor\Modules\Popup;

use CmsmastersElementor\Base\Base_Module;
use CmsmastersElementor\Plugin;
use CmsmastersElementor\Modules\Popup\Documents\Popup;

use Elementor\Controls_Manager;
use Elementor\Core\Base\Document;
use Elementor\TemplateLibrary\Source_Local;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Base_Module {

	/**
	 * Get module name.
	 *
	 * Retrieve the CMSMasters Popup module name.
	 *
	 * @since 1.9.0
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'cmsmasters-popup';
	}

	/**
	 * Add filters initialization.
	 *
	 * Register filters for the Blog module.
	 *
	 * @since 1.9.0
	 */
	protected function init_filters() {
		add_filter( 'cmsmasters_elementor/documents/set_document_types', array( $this, 'set_document_types' ) );

		$popups = $this->get_popups();

		if ( ! empty( $popups ) && ! is_wp_error( $popups ) ) {
			add_filter( 'elementor/widget/render_content', array( $this, 'add_popup_to_widgets' ), 10, 2  );
		}
	}

	/**
	 * Add actions initialization.
	 *
	 * @since 1.9.0
	 */
	protected function init_actions() {
		add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueue scripts.
	 *
	 * Enqueue all the frontend scripts.
	 *
	 * @since 1.9.0
	 * @access public
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'perfect-scrollbar-js' );
	}

	/**
	 * Set popup module document.
	 *
	 * Fired by `cmsmasters_elementor/documents/set_document_types` Addon filter hook.
	 *
	 * @since 1.9.0
	 *
	 * @return array
	 */
	public function set_document_types( $document_types ) {
		$module_document_types = array(
			'cmsmasters_popup' => Popup::get_class_full_name(),
		);

		$document_types = array_merge( $document_types, $module_document_types );

		return $document_types;
	}

	/**
	 * Add popup to widgets.
	 *
	 * Fired by `elementor/widget/render_content` Addon filter hook.
	 *
	 * @since 1.9.0
	 * @since 1.9.1 Fixed empty template id.
	 * @since 1.9.2 Fixed popup for multiple links.
	 *
	 * @return string HTML
	 */
	public function add_popup_to_widgets( $widget_content, $widget ) {
		$settings = $widget->get_settings();

		foreach ( $settings as $key => $values ) {
			if ( '__dynamic__' === $key && ! empty( $values ) ) {
				$widget_content .= $this->render_popup_template( $values );
			} elseif ( is_array( $values ) ) {
				foreach ( $values as $key_inner => $value_inner ) {
					if ( ! empty( $value_inner['__dynamic__'] ) ) {
						$widget_content .= $this->render_popup_template( $value_inner['__dynamic__'] );
					}
				}
			}
		}

		return $widget_content;
	}

	/**
	 * Render popup template.
	 *
	 * @since 1.11.1
	 *
	 * @return string HTML
	 */
	public function render_popup_template( $values = array() ) {
		if ( empty( $values ) ) {
			return '';
		}

		$popup = '';

		foreach ( $values as $key => $value ) {
			if ( false === strpos( $value, 'cmsmasters-action-popup' ) ) {
				continue;
			}

			preg_match( '/settings="(.*?)"/', $value, $matches_settings );
			$decoded_settings = urldecode( $matches_settings[1] );
			$decoded_settings = json_decode( $decoded_settings );
			$popup_id = $decoded_settings->popup_id;

			if ( empty( $popup_id ) || 'cmsmasters_popup' !== Source_Local::get_template_type( $popup_id ) ) {
				continue;
			}

			/** @var Plugin $addon */
			$addon = Plugin::instance();
			$frontend = $addon->frontend;

			$popup .= "<div class='animated cmsmasters-elementor-popup cmsmasters-elementor-popup-{$popup_id}' data-popup-id='{$popup_id}'>{$frontend->get_widget_template( $popup_id, true )}</div>";
		}

		return $popup;
	}

	public function get_popups() {
		$popups = get_posts( 
			array( 
				'post_type' => 'elementor_library',
				'meta_query' => array(
					array(
						'key' => Document::TYPE_META_KEY,
						'value' => 'cmsmasters_popup',
					),
				),
				'numberposts' => -1,
			) 
		);

		return $popups;
	}

	/**
	 * Retrieve widget classes name.
	 *
	 * @since 1.3.0
	 *
	 * @return array
	 */
	public function get_widgets() {
		return array(
			'Time_Popup',
		);
	}
}
