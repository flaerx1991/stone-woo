<?php
namespace CmsmastersElementor\Modules\SocialCounter\Widgets\Items;

use CmsmastersElementor\Modules\Instagram\Module as InstagramModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Addon Instagram social counter class.
 *
 * @since 1.0.0
 */
class Instagram extends Base {

	/**
	 * @since 1.0.0
	 */
	public static function get_name() {
		return 'instagram';
	}

	/**
	 * @since 1.0.0
	 */
	public static function get_label() {
		return __( 'Instagram', 'cmsmasters-elementor' );
	}

	/**
	 * @since 1.0.0
	 */
	public static function get_default_icon() {
		return array(
			'value' => 'fab fa-instagram',
			'library' => 'fa-brands',
		);
	}

	/**
	 * @since 1.0.0
	 */
	public static function get_types() {
		return array(
			'followers' => __( 'Followers', 'cmsmasters-elementor' ),
			'posts' => __( 'Posts', 'cmsmasters-elementor' ),
		);
	}

	/**
	 * Get numbers remote.
	 *
	 * @since 1.0.0
	 * @since 1.7.5 Fixed social counter.
	 *
	 * @return string/integer Numbers.
	 */
	protected function get_numbers_remote() {
		$user_id = InstagramModule::get_user_id();
		$access_token = InstagramModule::get_access_token();

		if ( InstagramModule::is_account_type_business() ) {
			$api_url = "https://graph.facebook.com/v16.0/{$user_id}?fields=followers_count,media_count&access_token={$access_token}";
		} else {
			$api_url = "https://graph.instagram.com/v16.0/{$user_id}?fields=media_count&access_token={$access_token}";
		}

		$result_json = self::get_result_json( $api_url );

		if ( ! $result_json ) {
			return;
		}

		switch ( $this->get_type() ) {
			case 'followers':
				if ( ! isset( $result_json['followers_count'] ) ) {
					return 0;
				}

				return $result_json['followers_count'];
			case 'posts':
				if ( ! isset( $result_json['media_count'] ) ) {
					return 0;
				}

				return $result_json['media_count'];
		}
	}
}
