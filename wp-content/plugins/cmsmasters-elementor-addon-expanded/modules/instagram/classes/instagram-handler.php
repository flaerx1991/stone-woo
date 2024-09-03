<?php
namespace CmsmastersElementor\Modules\Instagram\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Instagram_Handler {

	private $api_token = '';

	private $api_url_bases = array();

	private $api_request_args = array();

	/**
	 * Instagram_Handler constructor.
	 *
	 * @param $api_token
	 *
	 * @throws \Exception
	 */
	public function __construct( $api_token, $user_type = 'personal' ) {
		if ( empty( $api_token ) ) {
			throw new \Exception( 'Empty Token' );
		}

		$this->api_token = $api_token;

		$this->user_type = $user_type;

		$this->api_url_bases = array(
			'personal' => 'https://graph.instagram.com/',
			'business' => 'https://graph.facebook.com/v9.0/',
		);
	}

	public function get_user_id() {
		switch ( $this->user_type ) {
			case 'personal':
				$query_result = $this->query( 'me?fields=id&access_token=' . $this->api_token );
				$user_id = $query_result['id'];

				break;
			case 'business':
				$query_result = $this->query( 'me/accounts?fields=instagram_business_account&access_token=' . $this->api_token );
				$user_id = $query_result['data'][0]['instagram_business_account']['id'];

				break;
		}

		return $user_id;
	}

	private function query( $end_point ) {
		$response = wp_remote_get( $this->api_url_bases[ $this->user_type ] . $end_point, $this->api_request_args );

		if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			throw new \Exception( 'Instagram Error' );
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! is_array( $body ) ) {
			throw new \Exception( 'Instagram Error' );
		}

		return $body;
	}

}
