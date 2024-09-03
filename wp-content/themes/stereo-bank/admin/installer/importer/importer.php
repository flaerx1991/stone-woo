<?php
namespace StereoBankSpace\Admin\Installer\Importer;

use StereoBankSpace\Admin\Installer\Importer\ACF;
use StereoBankSpace\Admin\Installer\Importer\CPTUI;
use StereoBankSpace\Admin\Installer\Importer\Elementor_Fonts;
use StereoBankSpace\Admin\Installer\Importer\Elementor_Icons;
use StereoBankSpace\Admin\Installer\Importer\Elementor_Importer;
use StereoBankSpace\Admin\Installer\Importer\Elementor_Kit;
use StereoBankSpace\Admin\Installer\Importer\Elementor_Templates;
use StereoBankSpace\Admin\Installer\Importer\Theme_Options;
use StereoBankSpace\Admin\Installer\Importer\WPForms;
use StereoBankSpace\Admin\Installer\Importer\Forminator;
use StereoBankSpace\Admin\Installer\Importer\Give_WP;
use StereoBankSpace\Admin\Installer\Importer\WPRM_Templates;
use StereoBankSpace\Admin\Installer\Importer\Woo_Product_Filter;
use StereoBankSpace\Admin\Installer\Importer\Revslider;
use StereoBankSpace\Core\Utils\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Importer handler class is responsible for different methods on importing plugins settings.
 */
class Importer {

	/**
	 * Importer constructor.
	 */
	public function __construct() {
		new Theme_Options();

		new ACF();

		new CPTUI();

		new WPForms();

		new Forminator();

		new Give_WP();

		new Woo_Product_Filter();

		new WPRM_Templates();

		new Revslider();

		new Elementor_Fonts();

		new Elementor_Icons();

		new Elementor_Kit();

		new Elementor_Templates();

		new Elementor_Importer();

		add_action( 'cmsmasters_wp_import_insert_attachment', array( $this, 'set_import_attachments_ids' ), 10, 4 );

		add_action( 'wp_import_insert_post', array( $this, 'set_import_posts_ids' ), 10, 4 );

		add_action( 'wp_import_insert_term', array( $this, 'set_import_terms_ids' ), 10, 2 );

		add_action( 'import_end', array( $this, 'update_taxonomy_and_comments_counts' ) );
	}

	/**
	 * Set import attachments ids.
	 *
	 * @param int $post_id Post id.
	 * @param int $original_id Post original id.
	 * @param array $postdata Post data.
	 * @param array $data Data.
	 */
	public function set_import_attachments_ids( $post_id, $original_id, $postdata, $data ) {
		$demo = Utils::get_demo();

		$attachments_ids = get_transient( "cmsmasters_stereo-bank_{$demo}_import_attachments_ids" );

		if ( false === $attachments_ids ) {
			$attachments_ids = array();
		}

		if ( ! is_wp_error( $post_id ) && is_numeric( $post_id ) ) {
			$attachments_ids[ $original_id ] = $post_id;
		}

		set_transient( "cmsmasters_stereo-bank_{$demo}_import_attachments_ids", $attachments_ids, HOUR_IN_SECONDS );
	}

	/**
	 * Set import posts ids.
	 *
	 * @param int $post_id Post id.
	 * @param int $original_id Post original id.
	 * @param array $postdata Post data.
	 * @param array $post The Post.
	 */
	public function set_import_posts_ids( $post_id, $original_id, $postdata, $post ) {
		$demo = Utils::get_demo();

		$displayed_ids = get_transient( "cmsmasters_stereo-bank_{$demo}_import_displayed_ids" );

		if ( false === $displayed_ids ) {
			$displayed_ids = array();
		}

		$displayed_ids['post_id'][ $post['post_type'] ][ $original_id ] = $post_id;

		set_transient( "cmsmasters_stereo-bank_{$demo}_import_displayed_ids", $displayed_ids, HOUR_IN_SECONDS );
	}

	/**
	 * Set import terms ids.
	 *
	 * @param int $term_id Term id.
	 * @param array $data Term data.
	 */
	public function set_import_terms_ids( $term_id, $data ) {
		$demo = Utils::get_demo();

		$displayed_ids = get_transient( "cmsmasters_stereo-bank_{$demo}_import_displayed_ids" );

		if ( false === $displayed_ids ) {
			$displayed_ids = array();
		}

		if ( ! isset( $data['taxonomy'] ) || ! isset( $data['id'] ) ) {
			return;
		}

		$displayed_ids['taxonomy'][ $data['taxonomy'] ][ $data['id'] ] = $term_id;

		set_transient( "cmsmasters_stereo-bank_{$demo}_import_displayed_ids", $displayed_ids, HOUR_IN_SECONDS );
	}

	/**
	 * Update taxonomy and comments counts after import.
	 */
	public function update_taxonomy_and_comments_counts() {
		global $wpdb;

		// Update taxonomy count
		$term_taxonomy_ids = $wpdb->get_results( "SELECT term_taxonomy_id FROM {$wpdb->term_taxonomy}" );

		foreach ( $term_taxonomy_ids as $term_taxonomy_id_obj ) {
			$term_taxonomy_id = $term_taxonomy_id_obj->term_taxonomy_id;

			$count_result = $wpdb->get_var( $wpdb->prepare(
				"SELECT count(*) FROM {$wpdb->term_relationships} WHERE term_taxonomy_id = %d",
				$term_taxonomy_id
			) );

			$wpdb->update(
				$wpdb->term_taxonomy,
				[ 'count' => $count_result ],
				[ 'term_taxonomy_id' => $term_taxonomy_id ]
			);
		}

		// Update comment count
		$post_ids = $wpdb->get_results( "SELECT ID FROM {$wpdb->posts}" );

		foreach ( $post_ids as $post_id_obj ) {
			$post_id = $post_id_obj->ID;

			$count_result = $wpdb->get_var( $wpdb->prepare(
				"SELECT count(*) FROM {$wpdb->comments} WHERE comment_post_ID = %d AND comment_approved = 1",
				$post_id
			) );

			$wpdb->update(
				$wpdb->posts,
				[ 'comment_count' => $count_result ],
				[ 'ID' => $post_id ]
			);
		}
	}

}
