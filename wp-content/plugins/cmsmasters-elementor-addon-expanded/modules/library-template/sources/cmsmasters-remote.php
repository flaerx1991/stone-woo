<?php
namespace CmsmastersElementor\Modules\LibraryTemplate\Sources;

use CmsmastersElementor\Editor\Api;
use CmsmastersElementor\Plugin;

use Elementor\TemplateLibrary\Source_Remote;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Addon template library remote source.
 *
 * Addon template library remote source handler class is responsible for
 * handling remote templates from cmsmasters.net servers.
 *
 * @since 1.0.0
 */
class Cmsmasters_Remote extends Source_Remote {

	/**
	 * Get remote template ID.
	 *
	 * Retrieve the remote template ID.
	 *
	 * @since 1.0.0
	 *
	 * @return string The remote template ID.
	 */
	public function get_id() {
		return 'cmsmasters_remote';
	}

	/**
	 * Get remote template title.
	 *
	 * Retrieve the remote template title.
	 *
	 * @since 1.0.0
	 *
	 * @return string The remote template title.
	 */
	public function get_title() {
		return __( 'Addon Remote', 'cmsmasters-elementor' );
	}

	/**
	 * Get Addon library user meta prefix.
	 *
	 * Retrieve user meta prefix used to save addon data.
	 *
	 * @since 1.0.0
	 *
	 * @return string User meta prefix.
	 */
	protected function get_user_meta_prefix() {
		return $this->get_id() . '_library';
	}

	// /**
	//  * Register remote template data.
	//  *
	//  * Used to register custom template data like a post type, a taxonomy or any
	//  * other data.
	//  *
	//  * @since 1.0.0
	//  */
	// public function register_data() {}

	/**
	 * Get remote templates.
	 *
	 * Retrieve remote templates from cmsmasters.net servers.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Optional. Nou used in remote source.
	 *
	 * @return array Remote templates.
	 */
	public function get_items( $args = array() ) {
		$library_data = Api::get_library_data();

		$templates = array();

		if ( ! empty( $library_data['templates'] ) ) {
			foreach ( $library_data['templates'] as $template_data ) {
				$templates[] = $this->prepare_template( $template_data );
			}
		}

		return $templates;
	}

	/**
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 */
	private function prepare_template( $template_data ) {
		$favorite_templates = $this->get_user_meta( 'cmsmasters_favorites' );

		return array(
			'template_id' => $template_data['id'],
			'source' => $this->get_id(),
			'type' => $template_data['type'],
			'subtype' => $template_data['subtype'],
			'title' => $template_data['title'],
			'thumbnail' => $template_data['thumbnail'],
			'date' => $template_data['tmpl_created'],
			'author' => $template_data['author'],
			'tags' => json_decode( $template_data['tags'] ),
			// 'isPro' => ( '1' === $template_data['is_pro'] ),
			'popularityIndex' => (int) $template_data['popularity_index'],
			'trendIndex' => (int) $template_data['trend_index'],
			'hasPageSettings' => ( '1' === $template_data['has_page_settings'] ),
			'url' => $template_data['url'],
			'favorite' => ! empty( $favorite_templates[ $template_data['id'] ] ),
		);
	}

	/**
	 * Get remote template data.
	 *
	 * Retrieve the data of a single remote template from cmsmasters.net servers.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 *
	 * @param array $args Custom template arguments.
	 * @param string $context The context.
	 *
	 * @return array|\WP_Error Remote Template data.
	 */
	public function get_data( $args, $context = 'display' ) {
		$data = Api::get_template_content( $args['template_id'] );

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		$data = (array) $data; // TODO: check if it's needed

		$data['content'] = $this->replace_elements_ids( $data['content'] );
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );

		$document = Plugin::elementor()->documents->get( $args['editor_post_id'] );

		if ( $document ) {
			$data['content'] = $document->get_elements_raw_data( $data['content'], true );
		}

		return $data;
	}

	// /**
	//  * Get remote template.
	//  *
	//  * Retrieve a single remote template from Elementor.com servers.
	//  *
	//  * @since 1.0.0
	//  *
	//  * @param int $template_id The template ID.
	//  *
	//  * @return array Remote template.
	//  */
	// public function get_item( $template_id ) {
	// 	$templates = $this->get_items();

	// 	return $templates[ $template_id ];
	// }

	// /**
	//  * Save remote template.
	//  *
	//  * Remote template from Elementor.com servers cannot be saved on the
	//  * database as they are retrieved from remote servers.
	//  *
	//  * @since 1.0.0
	//  *
	//  * @param array $template_data Remote template data.
	//  *
	//  * @return \WP_Error
	//  */
	// public function save_item( $template_data ) {
	// 	return new \WP_Error( 'invalid_request', 'Cannot save template to a remote source' );
	// }

	// /**
	//  * Update remote template.
	//  *
	//  * Remote template from Elementor.com servers cannot be updated on the
	//  * database as they are retrieved from remote servers.
	//  *
	//  * @since 1.0.0
	//  *
	//  * @param array $new_data New template data.
	//  *
	//  * @return \WP_Error
	//  */
	// public function update_item( $new_data ) {
	// 	return new \WP_Error( 'invalid_request', 'Cannot update template to a remote source' );
	// }

	// /**
	//  * Delete remote template.
	//  *
	//  * Remote template from Elementor.com servers cannot be deleted from the
	//  * database as they are retrieved from remote servers.
	//  *
	//  * @since 1.0.0
	//  *
	//  * @param int $template_id The template ID.
	//  *
	//  * @return \WP_Error
	//  */
	// public function delete_template( $template_id ) {
	// 	return new \WP_Error( 'invalid_request', 'Cannot delete template from a remote source' );
	// }

	// /**
	//  * Export remote template.
	//  *
	//  * Remote template from Elementor.com servers cannot be exported from the
	//  * database as they are retrieved from remote servers.
	//  *
	//  * @since 1.0.0
	//  *
	//  * @param int $template_id The template ID.
	//  *
	//  * @return \WP_Error
	//  */
	// public function export_template( $template_id ) {
	// 	return new \WP_Error( 'invalid_request', 'Cannot export template from a remote source' );
	// }

}
