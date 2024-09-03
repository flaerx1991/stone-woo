<?php
namespace StereoBankSpace\Admin\Installer\Importer;

use StereoBankSpace\Admin\Installer\Importer\Elementor_Attachments;
use StereoBankSpace\Admin\Installer\Importer\Elementor_Templates;
use StereoBankSpace\Admin\Installer\Importer\Elementor_Widgets;
use StereoBankSpace\Admin\Installer\Importer\Forminator;
use StereoBankSpace\Admin\Installer\Importer\WPForms;
use StereoBankSpace\Core\Utils\API_Requests;
use StereoBankSpace\Core\Utils\Utils;

use Elementor\Plugin as Elementor_Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Elementor Importer handler class is responsible for different methods on importing "Elementor" plugin elements.
 */
class Elementor_Importer {

	/**
	 * Elementor Templates Import constructor.
	 */
	public function __construct() {
		if ( ! self::activation_status() || ! API_Requests::check_token_status() ) {
			return;
		}

		add_action( 'cmsmasters_import_ready', array( $this, 'end_import' ) );
	}

	/**
	 * Activation status.
	 *
	 * @return bool Activation status.
	 */
	public static function activation_status() {
		return ( did_action( 'elementor/loaded' ) && class_exists( 'Cmsmasters_Elementor_Addon' ) );
	}

	/**
	 * End import.
	 *
	 * Fires on import_end action.
	 */
	public function end_import() {
		$demo = Utils::get_demo();

		$displayed_ids = get_transient( "cmsmasters_stereo-bank_{$demo}_import_displayed_ids" );

		if ( false === $displayed_ids ) {
			$displayed_ids = array();
		}

		do_action( 'cmsmasters_replace_elementor_locations_id', $displayed_ids );

		$this->change_import_elements_ids();
		$this->change_megamenu_import_templates_ids();

		delete_transient( "cmsmasters_stereo-bank_{$demo}_elementor_import_templates_ids" );

		set_transient( "cmsmasters_stereo-bank_{$demo}_content_import_status", 'imported', HOUR_IN_SECONDS );
	}

	/**
	 * Change elements ids on import.
	 */
	protected function change_import_elements_ids() {
		$post_ids = Utils::get_elementor_post_ids();

		if ( empty( $post_ids ) ) {
			return;
		}

		$demo = Utils::get_demo();

		$templates_ids = get_transient( "cmsmasters_stereo-bank_{$demo}_elementor_import_templates_ids" );
		$attachments_ids = get_transient( "cmsmasters_stereo-bank_{$demo}_import_attachments_ids" );
		$displayed_ids = get_transient( "cmsmasters_stereo-bank_{$demo}_import_displayed_ids" );
		$forminator_forms_ids = get_transient( "cmsmasters_stereo-bank_{$demo}_forminator_import_forms_ids" );
		$wpforms_forms_ids = get_transient( "cmsmasters_stereo-bank_{$demo}_wpforms_import_forms_ids" );

		if ( empty( $templates_ids ) && empty( $attachments_ids ) && empty( $displayed_ids ) && empty( $forminator_forms_ids ) && empty( $wpforms_forms_ids ) ) {
			return;
		}

		foreach ( $post_ids as $post_id ) {
			$document = Elementor_Plugin::$instance->documents->get( $post_id );

			if ( $document ) {
				$data = $document->get_elements_data();
			}

			if ( empty( $data ) ) {
				continue;
			}

			$data = Elementor_Plugin::$instance->db->iterate_data( $data, function( $element ) use ( $templates_ids, $attachments_ids, $displayed_ids, $forminator_forms_ids, $wpforms_forms_ids, $post_id ) {
				if ( ! empty( $templates_ids ) ) {
					$element = Elementor_Templates::change_import_templates_ids( $element, $templates_ids );
				}

				if (
					'elementor_library' !== get_post_type( $post_id ) &&
					'revision' !== get_post_type( $post_id ) &&
					! empty( $attachments_ids )
				) {
					$element = Elementor_Attachments::change_import_attachments_ids( $element, $attachments_ids );
				}

				if ( ! empty( $displayed_ids ) ) {
					$element = Elementor_Widgets::change_import_displayed_ids( $element, $displayed_ids );
				}

				if ( ! empty( $forminator_forms_ids ) && Forminator::activation_status() ) {
					$element = Forminator::regenerate_content_forms_ids( $element, $forminator_forms_ids );
				}

				if ( ! empty( $wpforms_forms_ids ) && WPForms::activation_status() ) {
					$element = WPForms::regenerate_content_forms_ids( $element, $wpforms_forms_ids );
				}

				return $element;
			} );

			$document->save( array(
				'elements' => $data,
			) );
		}
	}

	/**
	 * Change templates ids in mega menu items on import.
	 *
	 * @param array $templates_ids Templates ids.
	 */
	protected function change_megamenu_import_templates_ids() {
		$menus = wp_get_nav_menus();

		if ( empty( $menus ) ) {
			return;
		}

		$demo = Utils::get_demo();

		$templates_ids = get_transient( "cmsmasters_stereo-bank_{$demo}_elementor_import_templates_ids" );

		if ( empty( $templates_ids ) ) {
			return;
		}

		foreach ( $menus as $menu ) {
			$menu_items = wp_get_nav_menu_items( $menu->term_id );

			if ( empty( $menu_items ) ) {
				continue;
			}

			foreach ( $menu_items as $menu_item ) {
				$meta_data = get_post_meta( $menu_item->ID, '_cmsmasters_megamenu', true );

				if ( empty( $meta_data['template'] ) ) {
					continue;
				}

				$old_id = $meta_data['template'];

				if ( ! isset( $templates_ids[ $old_id ] ) ) {
					continue;
				}

				$meta_data['template'] = strval( $templates_ids[ $old_id ] );

				update_post_meta( $menu_item->ID, '_cmsmasters_megamenu', $meta_data );
			}
		}
	}

}
