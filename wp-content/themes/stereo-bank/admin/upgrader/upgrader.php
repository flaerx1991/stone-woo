<?php
namespace StereoBankSpace\Admin\Upgrader;

use StereoBankSpace\Admin\Upgrader\Upgrader_Utils;
use StereoBankSpace\ThemeConfig\Theme_Config;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Upgrader.
 *
 * Main class for upgrader.
 */
class Upgrader {

	/**
	 * Upgrader constructor.
	 */
	public function __construct() {
		if ( CMSMASTERS_THEME_VERSION === Upgrader_Utils::get_current_version() ) {
			return;
		}

		$this->run_upgrades();

		$this->set_version();
	}

	/**
	 * Run upgrades.
	 *
	 * Runs upgrades from the current version to the latest.
	 */
	public function run_upgrades() {
		if ( empty( Theme_Config::MAJOR_VERSIONS ) ) {
			return;
		}

		$current_major_version = Upgrader_Utils::get_major_version();

		foreach ( Theme_Config::MAJOR_VERSIONS as $major_version ) {
			$compare_result = version_compare( $current_major_version, $major_version );

			if ( 0 < $compare_result ) {
				continue;
			}

			$class_name = 'StereoBankSpace\\ThemeConfig\\UpgraderVersions\\Version_' . str_replace( array( '.', '-' ), '', $major_version );

			if ( ! class_exists( $class_name ) ) {
				continue;
			}

			new $class_name( 0 === $compare_result );
		}
	}

	/**
	 * Set latest version.
	 */
	protected function set_version() {
		update_option( 'cmsmasters_stereo-bank_version', CMSMASTERS_THEME_VERSION );
	}

}
