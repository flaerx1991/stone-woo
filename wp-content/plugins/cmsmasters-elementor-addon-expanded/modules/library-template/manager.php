<?php
namespace CmsmastersElementor\Modules\LibraryTemplate;

use CmsmastersElementor\Editor\Api;
use CmsmastersElementor\Plugin;

use Elementor\Core\Common\Modules\Ajax\Module as Ajax;
use Elementor\Core\Settings\Manager as SettingsManager;
// use Elementor\TemplateLibrary\Classes\Import_Images;
use Elementor\TemplateLibrary\Source_Base;
use Elementor\TemplateLibrary\Source_Local;
use Elementor\User;
use Elementor\TemplateLibrary\Manager as ElementorTemplatesManager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Addon templates library manager.
 *
 * Addon templates library manager handler class is responsible for
 * initializing the templates library.
 *
 * @since 1.0.0
 */
class Manager extends ElementorTemplatesManager {

	// /**
	//  * Registered template sources.
	//  *
	//  * Holds a list of all the supported sources with their instances.
	//  *
	//  * @var Source_Base[]
	//  */
	// protected $registered_sources = array();

	// /**
	//  * Imported template images.
	//  *
	//  * Holds an instance of `Import_Images` class.
	//  *
	//  * @var Import_Images
	//  */
	// private $import_images = null;

	/**
	 * Template library manager constructor.
	 *
	 * Initializing the template library manager by registering default template
	 * sources and initializing ajax calls.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->register_default_sources();

		$this->add_actions();
	}

	/**
	 * Register default template sources.
	 *
	 * Register the 'local' and 'remote' template sources that Addon use by
	 * default.
	 *
	 * @since 1.0.0
	 */
	private function register_default_sources() {
		$class_name = str_replace( '-', '_', ucwords( 'cmsmasters-remote', '-' ) );

		$this->register_source( __NAMESPACE__ . "\Sources\{$class_name}" );
	}

	// /**
	//  * Register template source.
	//  *
	//  * Used to register new template sources displayed in the template library.
	//  *
	//  * @since 1.0.0
	//  *
	//  * @param string $source_class The name of source class.
	//  * @param array $args Class arguments.
	//  *
	//  * @return \WP_Error|true True if the source was registered, `WP_Error` otherwise.
	//  */
	// public function register_source( $source_class, $args = [] ) {
	// 	if ( ! class_exists( $source_class ) ) {
	// 		return new \WP_Error( 'source_class_name_not_exists' );
	// 	}

	// 	$source_instance = new $source_class( $args );

	// 	if ( ! $source_instance instanceof Source_Base ) {
	// 		return new \WP_Error( 'wrong_instance_source' );
	// 	}

	// 	$source_id = $source_instance->get_id();

	// 	if ( isset( $this->registered_sources[ $source_id ] ) ) {
	// 		return new \WP_Error( 'source_exists' );
	// 	}

	// 	$this->registered_sources[ $source_id ] = $source_instance;

	// 	return true;
	// }

	/**
	 * Add actions initialization.
	 *
	 * Register template library actions.
	 *
	 * @since 1.0.0
	 */
	public function add_actions() {
		add_action( 'elementor/ajax/register_actions', array( $this, 'register_ajax_actions' ) );

		add_action( 'wp_ajax_elementor_library_direct_actions', array( $this, 'handle_direct_actions' ) );

		// TODO: bc since 2.3.0
		// add_action( 'wp_ajax_elementor_update_templates', function() {
		// 	if ( ! isset( $_POST['templates'] ) ) {
		// 		return;
		// 	}

		// 	foreach ( $_POST['templates'] as & $template ) {
		// 		if ( ! isset( $template['content'] ) ) {
		// 			return;
		// 		}

		// 		$template['content'] = stripslashes( $template['content'] );
		// 	}

		// 	wp_send_json_success( $this->handle_ajax_request( 'update_templates', $_POST ) );
		// } );
	}

	/**
	 * Init ajax calls.
	 *
	 * Initialize template library ajax calls for allowed ajax requests.
	 *
	 * @since 1.0.0
	 *
	 * @param Ajax $ajax Elementor Ajax module.
	 */
	public function register_ajax_actions( Ajax $ajax ) {
		$library_ajax_requests = array(
			'cmsmasters_get_library_data',
			'cmsmasters_get_template_data',
			'cmsmasters_save_template',
			'cmsmasters_update_templates',
			'cmsmasters_delete_template',
			'cmsmasters_import_template',
			'cmsmasters_mark_template_as_favorite',
		);

		foreach ( $library_ajax_requests as $ajax_request ) {
			$ajax->register_ajax_action( $ajax_request, function( $data ) use ( $ajax_request ) {
				$method_name = str_replace( 'cmsmasters_', '', $ajax_request );

				return $this->handle_ajax_request( $method_name, $data );
			} );
		}
	}

	/**
	 * Get library data.
	 *
	 * Retrieve the library data.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 *
	 * @param array $args Library arguments.
	 *
	 * @return array Library data.
	 */
	public function get_library_data( $args ) {
		$force_update = ! empty( $args['sync'] );
		$library_data = Api::get_library_data( $force_update );

		// Ensure all document are registered.
		Plugin::elementor()->documents->get_document_types();

		return array(
			'templates' => $this->get_templates(),
			'config' => $library_data['types_data'],
		);
	}

	/**
	 * Get templates.
	 *
	 * Retrieve all the templates from all the registered sources.
	 *
	 * @since 1.0.0
	 *
	 * @return array Templates array.
	 */
	public function get_templates() {
		$templates = array();

		foreach ( $this->get_registered_sources() as $source ) {
			$templates = array_merge( $templates, $source->get_items() );
		}

		return $templates;
	}

	/**
	 * Get registered template sources.
	 *
	 * Retrieve registered template sources.
	 *
	 * @since 1.0.0
	 *
	 * @return Source_Base[] Registered template sources.
	 */
	public function get_registered_sources() {
		$sources = parent::get_registered_sources();

		unset( $sources['remote'] );

		return $sources;
	}

	/**
	 * Get template data.
	 *
	 * Retrieve the template data.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 *
	 * @param array $args Template arguments.
	 *
	 * @return \WP_Error|bool|array Template data for current preview, 'WP_Error' otherwise.
	 */
	public function get_template_data( $args ) {
		$validate_args = $this->ensure_args( [ 'source', 'template_id' ], $args );

		if ( is_wp_error( $validate_args ) ) {
			return $validate_args;
		}

		if ( isset( $args['edit_mode'] ) ) {
			Plugin::elementor()->editor->set_edit_mode( $args['edit_mode'] );
		}

		$source = $this->get_source( $args['source'] );

		if ( ! $source ) {
			return new \WP_Error( 'template_error', 'Template source not found.' );
		}

		do_action( 'cmsmasters_elementor/templates-library/before_get_source_data', $args, $source );

		$data = $source->get_data( $args );

		do_action( 'cmsmasters_elementor/templates-library/after_get_source_data', $args, $source );

		return $data;
	}

	/**
	 * Ensure arguments exist.
	 *
	 * Checks whether the required arguments exist in the
	 * specified arguments.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 *
	 * @param array $required_args Required arguments to check whether
	 * they exist.
	 * @param array $specified_args The list of all the specified
	 * arguments to check against.
	 *
	 * @return \WP_Error|true True on success, 'WP_Error' otherwise.
	 */
	private function ensure_args( $required_args, $specified_args ) {
		$specified_args_keys = array_keys( array_filter( $specified_args ) );
		$not_specified_args = array_diff( $required_args, $specified_args_keys );

		if ( $not_specified_args ) {
			return new \WP_Error(
				'arguments_not_specified',
				sprintf( 'The required argument(s) "%s" not specified.', implode( ', ', $not_specified_args ) )
			);
		}

		return true;
	}

	/**
	 * Get template source.
	 *
	 * Retrieve single template sources for a given template ID.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id The source ID.
	 *
	 * @return false|Source_Base Template sources if one exist, False otherwise.
	 */
	public function get_source( $id ) {
		$registered_sources = $this->get_registered_sources();

		if ( ! isset( $registered_sources[ $id ] ) ) {
			return false;
		}

		return $registered_sources[ $id ];
	}

	/**
	 * Save template.
	 *
	 * Save new or update existing template on the database.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 *
	 * @param array $args Template arguments.
	 *
	 * @return \WP_Error|int The ID of the saved/updated template, 'WP_Error' otherwise.
	 */
	public function save_template( $args ) {
		$validate_args = $this->ensure_args( array(
			'post_id',
			'source',
			'content',
			'type'
		), $args );

		if ( is_wp_error( $validate_args ) ) {
			return $validate_args;
		}

		$source = $this->get_source( $args['source'] );

		if ( ! $source ) {
			return new \WP_Error( 'template_error', 'Template source not found.' );
		}

		$args['content'] = json_decode( $args['content'], true );

		$page = SettingsManager::get_settings_managers( 'page' )->get_model( $args['post_id'] );

		$args['page_settings'] = $page->get_data( 'settings' );

		$template_id = $source->save_item( $args );

		if ( is_wp_error( $template_id ) ) {
			return $template_id;
		}

		return $source->get_item( $template_id );
	}

	/**
	 * Update templates.
	 *
	 * Update template on the database.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 *
	 * @param array $args Template arguments.
	 *
	 * @return \WP_Error|true True if templates updated, `WP_Error` otherwise.
	 */
	public function update_templates( $args ) {
		foreach ( $args['templates'] as $template_data ) {
			$result = $this->update_template( $template_data );

			if ( is_wp_error( $result ) ) {
				return $result;
			}
		}

		return true;
	}

	/**
	 * Update template.
	 *
	 * Update template on the database.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 *
	 * @param array $template_data New template data.
	 *
	 * @return \WP_Error|Source_Base Template sources instance if
	 * the templates was updated, `WP_Error` otherwise.
	 */
	public function update_template( $template_data ) {
		$validate_args = $this->ensure_args( array(
			'source',
			'content',
			'type'
		), $template_data );

		if ( is_wp_error( $validate_args ) ) {
			return $validate_args;
		}

		$source = $this->get_source( $template_data['source'] );

		if ( ! $source ) {
			return new \WP_Error( 'template_error', 'Template source not found.' );
		}

		$template_data['content'] = json_decode( $template_data['content'], true );

		$update = $source->update_item( $template_data );

		if ( is_wp_error( $update ) ) {
			return $update;
		}

		return $source->get_item( $template_data['id'] );
	}

	/**
	 * Delete template.
	 *
	 * Delete template from the database.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 *
	 * @param array $args Template arguments.
	 *
	 * @return \WP_Post|\WP_Error|false|null Post data on success,
	 * false or null or 'WP_Error' on failure.
	 */
	public function delete_template( $args ) {
		$validate_args = $this->ensure_args( array(
			'source',
			'template_id'
		), $args );

		if ( is_wp_error( $validate_args ) ) {
			return $validate_args;
		}

		$source = $this->get_source( $args['source'] );

		if ( ! $source ) {
			return new \WP_Error( 'template_error', 'Template source not found.' );
		}

		return $source->delete_template( $args['template_id'] );
	}

	/**
	 * Import template.
	 *
	 * Import template from a file.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 *
	 * @param array $data Import file data.
	 *
	 * @return mixed Whether the import succeeded or failed.
	 */
	public function import_template( $data ) {
		$file_content = base64_decode( $data['fileData'] );

		$tmp_file = tmpfile();

		fwrite( $tmp_file, $file_content );

		/** @var Source_Local $source */
		$source = $this->get_source( 'local' );

		$result = $source->import_template( $data['fileName'], stream_get_meta_data( $tmp_file )['uri'] );

		fclose( $tmp_file );

		return $result;
	}

	/**
	 * Mark template as favorite.
	 *
	 * Add the template to the user favorite templates.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Template arguments.
	 *
	 * @return mixed Whether the template marked as favorite.
	 */
	public function mark_template_as_favorite( $args ) {
		$validate_args = $this->ensure_args( array(
			'source',
			'template_id',
			'favorite'
		), $args );

		if ( is_wp_error( $validate_args ) ) {
			return $validate_args;
		}

		$source = $this->get_source( $args['source'] );

		return $source->mark_as_favorite(
			$args['template_id'],
			filter_var( $args['favorite'], FILTER_VALIDATE_BOOLEAN )
		);
	}

	/**
	 * Handle ajax request.
	 *
	 * Fire authenticated ajax actions for any given ajax request.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 *
	 * @param string $ajax_request Ajax request.
	 * @param array $data Request data.
	 *
	 * @return mixed Ajax request result.
	 * @throws \Exception
	 */
	private function handle_ajax_request( $ajax_request, $data ) {
		if ( ! User::is_current_user_can_edit_post_type( Source_Local::CPT ) ) {
			throw new \Exception( 'Access Denied' );
		}

		if ( ! empty( $data['editor_post_id'] ) ) {
			$editor_post_id = absint( $data['editor_post_id'] );

			if ( ! get_post( $editor_post_id ) ) {
				throw new \Exception( 'Post not found.' );
			}

			Plugin::elementor()->db->switch_to_post( $editor_post_id );
		}

		$result = call_user_func( array( $this, $ajax_request ), $data );

		if ( is_wp_error( $result ) ) {
			throw new \Exception( $result->get_error_message() );
		}

		return $result;
	}

	/**
	 * Handle direct action.
	 *
	 * Handle templates library direct action.
	 *
	 * @since 1.0.0
	 */
	public function handle_direct_actions() {
		if ( ! User::is_current_user_can_edit_post_type( Source_Local::CPT ) ) {
			return;
		}

		/** @var Ajax $ajax */
		$ajax = Plugin::elementor()->common->get_component( 'ajax' );

		if ( ! $ajax->verify_request_nonce() ) {
			$this->handle_direct_action_error( 'Access Denied' );
		}

		$action = $_REQUEST['library_action'];

		$result = $this->$action( $_REQUEST );

		if ( is_wp_error( $result ) ) {
			/** @var \WP_Error $result */
			$this->handle_direct_action_error( $result->get_error_message() . '.' );
		}

		$callback = "on_{$action}_success";

		if ( method_exists( $this, $callback ) ) {
			$this->$callback( $result );
		}

		die;
	}

	/**
	 * On template import error.
	 *
	 * Kills WordPress execution and displays HTML page with
	 * an error message.
	 *
	 * @since 1.0.0
	 */
	private function handle_direct_action_error( $message ) {
		_default_wp_die_handler( $message, 'CMSMasters Elementor Addon Templates Library' );
	}

	/**
	 * Direct template import.
	 *
	 * Handle templates library direct import for local template.
	 *
	 * @since 1.0.0
	 */
	public function direct_import_template() {
		/** @var Source_Local $source */
		$source = $this->get_source( 'local' );

		return $source->import_template( $_FILES['file']['name'], $_FILES['file']['tmp_name'] );
	}

	/**
	 * On successful template import.
	 *
	 * Redirect the user to the template library after template import was
	 * successful finished.
	 *
	 * @since 1.0.0
	 */
	private function on_direct_import_template_success() {
		wp_safe_redirect( admin_url( Source_Local::ADMIN_MENU_SLUG ) );
	}

	// /**
	//  * Get `Import_Images` instance.
	//  *
	//  * Retrieve the instance of the `Import_Images` class.
	//  *
	//  * @since 1.0.0
	//  * @access public
	//  *
	//  * @return Import_Images Imported images instance.
	//  */
	// public function get_import_images_instance() {
	// 	if ( null === $this->import_images ) {
	// 		$this->import_images = new Import_Images();
	// 	}

	// 	return $this->import_images;
	// }

	// /**
	//  * Unregister template source.
	//  *
	//  * Remove an existing template sources from the list of registered template
	//  * sources.
	//  *
	//  * @deprecated 2.7.0
	//  *
	//  * @since 1.0.0
	//  * @access public
	//  *
	//  * @param string $id The source ID.
	//  *
	//  * @return bool Whether the source was unregistered.
	//  */
	// public function unregister_source( $id ) {
	// 	return true;
	// }

	/**
	 * Export template.
	 *
	 * Export template to a file.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 *
	 * @param array $args Template arguments.
	 *
	 * @return mixed Whether the export succeeded or failed.
	 */
	public function export_template( $args ) {
		$validate_args = $this->ensure_args( array(
			'source',
			'template_id'
		), $args );

		if ( is_wp_error( $validate_args ) ) {
			return $validate_args;
		}

		$source = $this->get_source( $args['source'] );

		if ( ! $source ) {
			return new \WP_Error( 'template_error', 'Template source not found' );
		}

		return $source->export_template( $args['template_id'] );
	}

}
