<?php
namespace StereoBankSpace\Admin\Installer\Importer;

use Elementor\Utils as Elementor_Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Elementor Attachments handler class is responsible for different methods on importing attachments ids for "Elementor" plugin.
 */
class Elementor_Attachments {

	/**
	 * Change attachments ids in posts _elementor_data on import.
	 *
	 * @param array $element Elementor element.
	 * @param array $attachments_ids Attachments ids.
	 *
	 * @return array Elementor element.
	 */
	public static function change_import_attachments_ids( $element, $attachments_ids = array() ) {
		if ( empty( $element['elType'] ) || empty( $element['settings'] ) ) {
			return $element;
		}

		foreach ( $element['settings'] as $setting_name => $setting ) {
			if ( ! is_array( $setting ) ) {
				continue;
			}

			$element['settings'][ $setting_name ] = self::generate_new_settings( $setting, $attachments_ids );
		}

		return $element;
	}

	/**
	 * Generate new settings.
	 *
	 * @param array $settings Widget settings.
	 * @param array $attachments_ids Attachments ids.
	 *
	 * @return array New settings.
	 */
	private static function generate_new_settings( $settings, $attachments_ids ) {
		if ( isset( $settings[0] ) && is_array( $settings[0] ) ) {
			$new_settings = array();
			$is_repeater = isset( $settings[0]['_id'] );

			if ( $is_repeater ) {
				foreach ( $settings as $item_key => $item ) {
					foreach ( $item as $repeater_item_key => $repeater_setting ) {
						if ( ! is_array( $repeater_setting ) ) {
							$new_settings_value = $repeater_setting;
						} else {
							if ( isset( $repeater_setting[0] ) && is_array( $repeater_setting[0] ) ) {
								$new_settings_value = array();

								foreach ( $repeater_setting as $repeater_setting_multiple ) {
									$new_settings_value[] = self::replace_attachment_data( $repeater_setting_multiple, $attachments_ids );
								}
							} else {
								$new_settings_value = self::replace_attachment_data( $repeater_setting, $attachments_ids );
							}
						}

						$new_settings[ $item_key ][ $repeater_item_key ] = $new_settings_value;
					}
				}
			} else {
				foreach ( $settings as $item ) {
					$new_settings[] = self::replace_attachment_data( $item, $attachments_ids );
				}
			}
		} else {
			$new_settings = self::replace_attachment_data( $settings, $attachments_ids );
		}

		return $new_settings;
	}

	/**
	 * Replace attachment data.
	 *
	 * @param array $settings Widget settings.
	 * @param array $attachments_ids Attachments ids.
	 *
	 * @return array New settings.
	 */
	private static function replace_attachment_data( $settings, $attachments_ids ) {
		if (
			empty( $settings['url'] ) ||
			! isset( $settings['id'] ) ||
			empty( $settings['id'] )
		) {
			return $settings;
		}

		$new_settings = array();

		if ( isset( $attachments_ids[ $settings['id'] ] ) ) {
			$attachment = $attachments_ids[ $settings['id'] ];

			$new_settings = array(
				'id' => $attachment,
				'url' => wp_get_attachment_url( $attachment ),
			);
		}

		if ( empty( $new_settings ) ) {
			$img_formats = array( '.jpg', '.jpeg', '.jpe', '.gif', '.png', '.bmp', '.tiff', '.tif', '.ico', '.heic', '.svg' );

			$placeholder_url = $settings['url'];

			if ( in_array( strrchr( $placeholder_url, '.' ), $img_formats, true ) ) {
				$placeholder_url = Elementor_Utils::get_placeholder_image_src();
			}

			$new_settings = array(
				'id' => '',
				'url' => $placeholder_url,
			);
		}

		return $new_settings;
	}

}
