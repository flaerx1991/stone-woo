<?php
namespace CmsmastersElementor\Editor;

use CmsmastersElementor\Plugin;

use Elementor\Core\Common\Modules\Connect\Apps\Library;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Addon API.
 *
 * Addon API handler class is responsible for communicating with
 * CMSMasters remote servers retrieving templates data and
 * to send uninstall feedback.
 *
 * @since 1.0.0
 */
class Api {

	/**
	 * Addon library option key.
	 */
	const LIBRARY_OPTION_KEY = 'cmsmasters_remote_info_library';

	// /**
	//  * Addon feed option key.
	//  */
	// const FEED_OPTION_KEY = 'cmsmasters_remote_info_feed_data';

	/**
	 * API info URL.
	 *
	 * Holds the URL of the info API.
	 *
	 * @since 1.0.0
	 *
	 * @var string API info URL.
	 */
	public static $api_info_url = 'https://api.cmsmasters.net/api/v1/info/';

	// /**
	//  * API feedback URL.
	//  *
	//  * Holds the URL of the feedback API.
	//  *
	//  * @since 1.0.0
	//  *
	//  * @var string API feedback URL.
	//  */
	// private static $api_feedback_url = 'https://api.cmsmasters.net/api/v1/feedback/';

	/**
	 * Init.
	 *
	 * Initialize Addon API.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		add_action( 'wp_ajax_cmsmasters_reset_library', array( __CLASS__, 'ajax_reset_api_data' ) );
	}

	/**
	 * Ajax reset API data.
	 *
	 * Reset Addon library API data using an ajax call.
	 *
	 * @since 1.0.0
	 */
	public static function ajax_reset_api_data() {
		check_ajax_referer( 'cmsmasters_reset_library', '_nonce' );

		self::get_info_data( true );

		wp_send_json_success();
	}

	/**
	 * Get info data.
	 *
	 * This function notifies the user of upgrade notices,
	 * new templates and contributors.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $force_update Whether to force the data retrieval or not.
	 *
	 * @return array|false Info data, or false.
	 */
	private static function get_info_data( $force_update = false ) {
		$cache_key = 'cmsmasters_remote_info_api_data_' . CMSMASTERS_ELEMENTOR_VERSION;

		$info_data = get_transient( $cache_key );

		if ( $force_update || false === $info_data ) {
			$timeout = ( $force_update ) ? 25 : 8;

			$response = wp_remote_get( self::$api_info_url, array(
				'timeout' => $timeout,
				'body' => array(
					'api_version' => CMSMASTERS_ELEMENTOR_VERSION, // Which API version is used.
					'site_lang' => get_bloginfo( 'language' ), // Which language to return.
				),
			) );

			if (
				is_wp_error( $response ) ||
				200 !== (int) wp_remote_retrieve_response_code( $response )
			) {
				set_transient( $cache_key, array(), 2 * HOUR_IN_SECONDS );

				return false;
			}

			$info_data = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( empty( $info_data ) || ! is_array( $info_data ) ) {
				set_transient( $cache_key, array(), 2 * HOUR_IN_SECONDS );

				return false;
			}

			if ( isset( $info_data['library'] ) ) {
				update_option( self::LIBRARY_OPTION_KEY, $info_data['library'], 'no' );

				unset( $info_data['library'] );
			}

			// if ( isset( $info_data['feed'] ) ) {
			// 	update_option( self::FEED_OPTION_KEY, $info_data['feed'], 'no' );

			// 	unset( $info_data['feed'] );
			// }

			set_transient( $cache_key, $info_data, 12 * HOUR_IN_SECONDS );
		}

		return $info_data;
	}

	/**
	 * Get templates data.
	 *
	 * Retrieve the templates data from a remote server.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $force_update Whether to force the data update or not.
	 *
	 * @return array The templates data.
	 */
	public static function get_library_data( $force_update = false ) {
		self::get_info_data( $force_update );

		$library_data = get_option( self::LIBRARY_OPTION_KEY );

		if ( empty( $library_data ) ) {
			return array();
		}

		return $library_data;
	}

	/**
	 * Get template content.
	 *
	 * Retrieve the templates content received from a remote server.
	 *
	 * @since 1.0.0
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return object|\WP_Error The template content.
	 */
	public static function get_template_content( $template_id ) {
		/** @var Library $library */
		$library = Plugin::elementor()->common->get_component( 'connect' )->get_app( 'library' );

		return $library->get_template_content( $template_id );
	}

	// /**
	//  * Get upgrade notice.
	//  *
	//  * Retrieve the upgrade notice if one exists, or false otherwise.
	//  *
	//  * @since 1.0.0
	//  *
	//  * @return array|false Upgrade notice, or false none exist.
	//  */
	// public static function get_upgrade_notice() {
	// 	$data = self::get_info_data();

	// 	if ( empty( $data['upgrade_notice'] ) ) {
	// 		return false;
	// 	}

	// 	return $data['upgrade_notice'];
	// }

	// public static function get_admin_notice() {
	// 	$data = self::get_info_data();

	// 	if ( empty( $data['admin_notice'] ) ) {
	// 		return false;
	// 	}

	// 	return $data['admin_notice'];
	// }

	// public static function get_canary_deployment_info( $force = false ) {
	// 	$data = self::get_info_data( $force );

	// 	if ( empty( $data['canary_deployment'] ) ) {
	// 		return false;
	// 	}

	// 	return $data['canary_deployment'];
	// }

	// public static function get_promotion_widgets() {
	// 	$data = self::get_info_data();

	// 	return $data['pro_widgets'];
	// }

	// /**
	//  * Get feed data.
	//  *
	//  * Retrieve the feed info data from remote server.
	//  *
	//  * @since 1.0.0
	//  *
	//  * @param bool $force_update Whether to force the data update or not.
	//  *
	//  * @return array Feed data.
	//  */
	// public static function get_feed_data( $force_update = false ) {
	// 	self::get_info_data( $force_update );

	// 	$feed = get_option( self::FEED_OPTION_KEY );

	// 	if ( empty( $feed ) ) {
	// 		return array();
	// 	}

	// 	return $feed;
	// }

	// /**
	//  * Send Feedback.
	//  *
	//  * Fires a request to Elementor server with the feedback data.
	//  *
	//  * @since 1.0.0
	//  *
	//  * @param string $feedback_key Feedback key.
	//  * @param string $feedback_text Feedback text.
	//  *
	//  * @return array The response of the request.
	//  */
	// public static function send_feedback( $feedback_key, $feedback_text ) {
	// 	return wp_remote_post( self::$api_feedback_url, array(
	// 		'timeout' => 30,
	// 		'body' => array(
	// 			'api_version' => CMSMASTERS_ELEMENTOR_VERSION,
	// 			'site_lang' => get_bloginfo( 'language' ),
	// 			'feedback_key' => $feedback_key,
	// 			'feedback' => $feedback_text,
	// 		),
	// 	) );
	// }

}
