<?php
namespace CmsmastersElementor\Modules\Instagram;

use CmsmastersElementor\Base\Base_Module;
use CmsmastersElementor\Modules\AjaxWidget\Classes\Ajax_Action_Handler;
use CmsmastersElementor\Modules\AjaxWidget\Module as AjaxWidgetModule;
use CmsmastersElementor\Modules\Instagram\Classes\Instagram_Handler;
use CmsmastersElementor\Modules\Instagram\Widgets\Instagram as InstagramWidget;
use CmsmastersElementor\Modules\Settings\Settings_Page;
use CmsmastersElementor\Utils;

use Elementor\Core\Common\Modules\Ajax\Module as AjaxModule;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Addon elementor instagram module.
 *
 * Addon elementor instagram module handler class is responsible for
 * registering and managing group.
 *
 * @since 1.0.0
 */
class Module extends Base_Module {

	const OPTION_NAME_USER_ID = 'cmsmasters_instagram_user_id';

	/**
	 * Get module name.
	 *
	 * Retrieve the instagram module name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'cmsmasters-instagram';
	}

	/**
	 * Get widget names.
	 *
	 * Retrieve the instagram widget names.
	 *
	 * @since 1.0.0
	 *
	 * @return array Widget names.
	 */
	public function get_widgets() {
		return array(
			'Instagram',
		);
	}

	/**
	 * Add actions initialization.
	 *
	 * Register actions for the Instagram module.
	 *
	 * @since 1.0.0
	 */
	protected function init_actions() {
		add_action( 'cmsmasters_elementor/ajax_widget/register', array( $this, 'register_ajax_widget' ) );
		add_action( 'elementor/ajax/register_actions', array( $this, 'register_ajax_actions' ) );

		if ( is_admin() ) {
			add_action( 'elementor/admin/after_create_settings/' . Settings_Page::PAGE_ID, array( $this, 'register_admin_fields' ), 100 );
		}

		add_action( 'wp_ajax_' . self::OPTION_NAME_USER_ID . '_validate', array( $this, 'ajax_validate_api_user_id' ) );
	}

	/**
	 * Add filters initialization.
	 *
	 * Register filters for the Instagram module.
	 *
	 * @since 1.0.0
	 */
	protected function init_filters() {
		add_filter( 'cmsmasters_elementor/frontend/settings', array( $this, 'filter_frontend_settings' ) );
		add_filter( 'cmsmasters_elementor/editor/settings', array( $this, 'filter_editor_settings' ) );
	}

	/**
	 * Add handler for ajax widget.
	 *
	 * Register handler for the instagram widget.
	 *
	 * Fired by `cmsmasters_elementor/ajax_widget/register` Addon action hook.
	 *
	 * @since 1.0.0
	 */
	public function register_ajax_widget( AjaxWidgetModule $ajax_widget ) {
		$ajax_widget->add_handler( 'cmsmasters-instagram', array( $this, 'render_ajax_load_more' ) );
	}

	/**
	 * Register ajax actions and used to register new ajax action handles.
	 *
	 * Fired by `elementor/ajax/register_actions` Elementor plugin action hook, that
	 * fires when an ajax request is received and verified.
	 *
	 * @since 1.0.0
	 *
	 * @param Ajax $ajax_manager
	 */
	public function register_ajax_actions( AjaxModule $ajax_manager ) {
		$ajax_manager->register_ajax_action( 'cmsmasters_instagram_remove_cache', array( $this, 'ajax_instagram_remove_cache' ) );
	}

	/**
	 * Removes the cache of the instagram widget.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 */
	public function ajax_instagram_remove_cache( $request ) {
		global $wpdb;

		$user_id = Utils::get_if_isset( $request, 'user_id' );

		if ( ! $user_id ) {
			$user_id = self::get_user_id();
		}

		if ( ! $user_id ) {
			return;
		}

		$prefix = InstagramWidget::CACHE_PREFIX;

		$wpdb->query(
			$wpdb->prepare( "DELETE FROM `{$wpdb->options}` WHERE `option_name` LIKE %s", "_transient_timeout_{$prefix}%" )
		);

		$wpdb->query(
			$wpdb->prepare( "DELETE FROM `{$wpdb->options}` WHERE `option_name` LIKE %s", "_transient_{$prefix}%" )
		);
	}

	/**
	 * Register CMSMasters fields in dashboard.
	 *
	 * Fired by `elementor/admin/after_create_settings/cmsmasters` Cmsmasters action hook.
	 *
	 * @since 1.0.0
	 * @since 1.0.3 Added anchor link for instagram settings.
	 *
	 * @param Settings_Page $settings Cmsmasters "Settings" page in WordPress dashboard.
	 */
	public function register_admin_fields( Settings_Page $settings ) {
		$settings->add_section( 'general', 'instagram', array(
			'callback' => function() {
				echo '<br><hr><br>' .
				'<h2 id="cmsmasters-instagram-settings">' . esc_html__( 'Instagram', 'cmsmasters-elementor' ) . '</h2>';
			},
			'fields' => array(
				'instagram_url' => array(
					'label' => __( 'Profile URL', 'cmsmasters-elementor' ),
					'field_args' => array(
						'type' => 'text',
					),
				),
				'instagram_account_type' => array(
					'label' => __( 'Profile Type', 'cmsmasters-elementor' ),
					'field_args' => array(
						'type' => 'select',
						'options' => array(
							'personal' => __( 'Personal', 'cmsmasters-elementor' ),
							'business' => __( 'Business', 'cmsmasters-elementor' ),
						),
						'default' => 'business',
					),
				),
				'cmsmasters_instagram_access_token' => array(
					'label' => __( 'Access Token', 'cmsmasters-elementor' ),
					'field_args' => array(
						'type' => 'text',
					),
				),
				self::OPTION_NAME_USER_ID => array(
					'label' => __( 'User ID', 'cmsmasters-elementor' ),
					'class' => 'cmsmasters_disabled_input',
					'field_args' => array(
						'type' => 'text',
						'class' => 'cmsmasters_disabled_input',
					),
				),
				'validate_api_data' => array(
					'field_args' => array(
						'type' => 'raw_html',
						'html' => sprintf(
							'<button class="button elementor-button-spinner" id="%2$s" data-action="%3$s" data-nonce="%4$s">%1$s</button>',
							__( 'Get User ID', 'cmsmasters-elementor' ),
							'elementor_' . self::OPTION_NAME_USER_ID . '_button',
							self::OPTION_NAME_USER_ID . '_validate',
							wp_create_nonce( self::OPTION_NAME_USER_ID )
						),
					),
				),
			),
		) );
	}

	/**
	 * Handle ajax request.
	 *
	 * Sends an ajax request to Instagram userID.
	 *
	 * @since 1.0.0
	 */
	public function ajax_validate_api_user_id() {
		check_ajax_referer( self::OPTION_NAME_USER_ID, '_nonce' );

		if ( ! isset( $_POST['api_token'] ) ) {
			wp_send_json_error();
		}

		$api_token = $_POST['api_token'];
		$user_type = 'personal';

		if ( isset( $_POST['user_type'] ) ) {
			$user_type = $_POST['user_type'];
		}

		$response = array();

		try {
			$handler = new Instagram_Handler( $api_token, $user_type );

			$response = $handler->get_user_id();
		} catch ( \Exception $exception ) {
			wp_send_json_error();
		}

		wp_send_json_success( $response );
	}

	/**
	 * Render widgets on Ajax request.
	 *
	 * Sends HTML to frontend
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 *
	 * @return string
	 */
	public function render_ajax_load_more( $ajax_vars, InstagramWidget $widget_obj, Ajax_Action_Handler $handler ) {
		$page = (float) Utils::get_if_isset( $ajax_vars, 'page' );

		if ( ! $page && floatval( 0 ) !== $page ) {
			$handler->send_required_fields_json_error();
		}

		return $widget_obj->render_ajax( $page );
	}

	/**
	 * Filter frontend settings.
	 *
	 * Filters the Addon settings for elementor frontend.
	 *
	 * Fired by `cmsmasters_elementor/frontend/settings` Addon action hook.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings Frontend settings.
	 *
	 * @return array Filtered frontend settings.
	 */
	public function filter_frontend_settings( $settings ) {
		return array_replace_recursive( array(
			'instagram_access_token' => self::get_access_token(),
			'instagram_user_id' => self::get_user_id(),
			'instagram_account_type' => self::get_account_type(),
			'i18n' => array(
				'instagram' => array(
					'img_alt_text' => __( 'Instagram Post', 'cmsmasters-elementor' ),
				),
			),
		), $settings );
	}

	/**
	 * Filter editor settings.
	 *
	 * Filters the Addon settings for elementor editor.
	 *
	 * Fired by `cmsmasters_elementor/editor/settings` Addon filter hook.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings Frontend settings.
	 *
	 * @return array Filtered editor settings.
	 */
	public function filter_editor_settings( $settings ) {
		$settings = array_replace_recursive( $settings, array(
			'i18n' => array(
				'cache_deleted' => esc_html__( 'Cache Deleted', 'cmsmasters-elementor' ),
			),
		) );

		return $settings;
	}

	/**
	 * Retrieve type of instagram account from settings.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_account_type() {
		return get_option( 'elementor_instagram_account_type', 'business' );
	}

	/**
	 * Retrieve Access Token.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_access_token() {
		return get_option( 'elementor_cmsmasters_instagram_access_token', '' );
	}

	/**
	 * Retrieve User ID.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_user_id() {
		return get_option( 'elementor_' . self::OPTION_NAME_USER_ID, '' );
	}

	/**
	 * Check if account type is business.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_account_type_business() {
		return 'business' === self::get_account_type();
	}
}
