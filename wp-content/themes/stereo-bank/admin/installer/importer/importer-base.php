<?php
namespace StereoBankSpace\Admin\Installer\Importer;

use StereoBankSpace\Core\Utils\API_Requests;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Importer Base handler class is responsible for different methods on importing plugins settings.
 */
abstract class Importer_Base {

	/**
	 * Importer constructor.
	 */
	public function __construct() {
		if ( static::activation_status() ) {
			add_action( 'admin_init', array( $this, 'admin_init_actions' ) );
		}

		add_action( 'cmsmasters_set_backup_options', array( get_called_class(), 'set_backup_options' ) );

		add_action( 'cmsmasters_set_import_status', array( get_called_class(), 'set_import_status' ) );
	}

	/**
	 * Activation status.
	 *
	 * @return bool Activation status.
	 */
	public static function activation_status() {
		return false;
	}

	/**
	 * Actions on admin_init hook.
	 */
	public function admin_init_actions() {
		if ( ! API_Requests::check_token_status() ) {
			return;
		}

		if ( 'pending' !== static::get_import_status( 'done' ) ) {
			return;
		}

		$this->set_exists_options();

		$this->set_api_options();

		$this->import_options();

		static::set_import_status( 'done' );
	}

	/**
	 * Get import status.
	 *
	 * @param string $default Import status by default, may be pending or done.
	 *
	 * @return string Import status.
	 */
	public static function get_import_status( $default = 'done' ) {
		return false;
	}

	/**
	 * Set import status.
	 *
	 * @param string $status Import status, may be pending or done.
	 */
	public static function set_import_status( $status = 'pending' ) {
		return false;
	}

	/**
	 * Set exists options.
	 */
	abstract protected function set_exists_options();

	/**
	 * Set options from API.
	 */
	abstract protected function set_api_options();

	/**
	 * Import options.
	 */
	abstract protected function import_options();

	/**
	 * Backup current options.
	 *
	 * @param bool $first_install First install trigger, if need to backup customer option from previous theme.
	 */
	public static function set_backup_options( $first_install = false ) {
		return false;
	}

}
