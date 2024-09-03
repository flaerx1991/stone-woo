<?php
namespace CmsmastersElementor\Components\Connect\Apps;

use CmsmastersElementor\Components\Connect\Apps\Common_App;

use Elementor\Core\Common\Modules\Ajax\Module as AjaxModule;
use Elementor\User;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Library extends Common_App {

	/**
	 * @since 1.0.0
	 */
	protected function get_slug() {
		return 'library';
	}

	public function get_title() {
		return __( 'Library', 'cmsmasters-elementor' );
	}

	protected function init() {
		add_action( 'elementor/ajax/register_actions', array( $this, 'register_ajax_actions' ) );

		add_filter( 'elementor/editor/localize_settings', array( $this, 'localize_settings' ) );
	}

	/**
	 * @param AjaxModule $ajax_manager
	 */
	public function register_ajax_actions( $ajax_manager ) {
		$ajax_manager->register_ajax_action( 'cmsmasters_library_connect_popup_seen', array( $this, 'addon_library_connect_popup_seen' ) );
	}

	public function addon_library_connect_popup_seen() {
		User::set_introduction_viewed( array(
			'introductionKey' => 'cmsmasters_library_connect',
		) );
	}

	public function localize_settings( $settings ) {
		return array_replace_recursive( $settings, array(
			'i18n' => array(
				// Route: cmsmasters-library/connect
				'cmsmasters-library/connect:title' => __( 'Connect to CMSMasters Templates Library', 'cmsmasters-elementor' ),
				'cmsmasters-library/connect:message' => __( 'Access this template and our entire library by creating a free personal account.', 'cmsmasters-elementor' ),
				'cmsmasters-library/connect:button' => __( 'Get Started', 'cmsmasters-elementor' ),
			),
			'cmsmasters_library_connect' => array( 'is_connected' => $this->is_connected() ),
		) );
	}

	public function get_template_content( $id ) {
		if ( ! $this->is_connected() ) {
			return new \WP_Error( '401', __( 'Connecting to the CMSMasters Library failed. Please reload the page and try again', 'cmsmasters-elementor' ) );
		}

		$body_args = array(
			'id' => $id,
			'api_version' => CMSMASTERS_ELEMENTOR_VERSION, // Which API version is used.
			'site_lang' => get_bloginfo( 'language' ), // Which language to return.
		);

		/**
		 * API: Template body args.
		 *
		 * Filters the body arguments send with the GET request when fetching the content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $body_args Body arguments.
		 */
		$body_args = apply_filters( 'cmsmasters_elementor/api/get_templates/body_args', $body_args );

		$template_content = $this->request( 'get_template_content', $body_args, true );

		return $template_content;
	}

	protected function get_app_info() {
		return array(
			'user_common_data' => array(
				'label' => __( 'User Common Data', 'cmsmasters-elementor' ),
				'value' => get_user_option( static::get_option_name(), get_current_user_id() ),
			),
			'connect_site_key' => array(
				'label' => __( 'Site Key', 'cmsmasters-elementor' ),
				'value' => get_option( 'elementor_connect_site_key' ),
			),
			'remote_info_library' => array(
				'label' => __( 'Remote Library Info', 'cmsmasters-elementor' ),
				'value' => get_option( 'elementor_remote_info_library' ),
			),
		);
	}

}
