<?php
namespace CmsmastersElementor\Modules\infiniteScroll;

use CmsmastersElementor\Base\Base_Module;
use CmsmastersElementor\Utils;
use Elementor\Icons_Manager;
use ElementorPro\Modules\ThemeBuilder\Module  as ThemeBuilderModule;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Addon elementor infinite-scroll module.
 *
 * Addon elementor infinite-scroll module handler class is responsible for
 * registering and managing group.
 *
 * @since 1.0.0
 */
class Module extends Base_Module {
	const LOCATION = 'single';

	/**
	 * Get module name.
	 *
	 * Retrieve the infinite-scroll module name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'infinite_scroll';
	}

	/**
	 * @since 1.0.0
	 */
	public static function is_active() {
		return class_exists( '\ElementorPro\Plugin' );
	}

	/**
	 * Add actions initialization.
	 *
	 * Register actions for the Infinite Scroll module.
	 *
	 * @since 1.0.0
	 */
	protected function init_actions() {
		add_action( 'wp_ajax_cmsmasters_single_infinite_scroll', array( $this, 'ajax_infinite' ) );
		add_action( 'wp_ajax_nopriv_cmsmasters_single_infinite_scroll', array( $this, 'ajax_infinite' ) );

		if ( ! is_admin() && ! Utils::is_ajax() ) {
			add_action( 'get_footer', array( $this, 'render_load_more' ), 0 );
			add_action( 'wp', function() {
				if ( ! is_singular( 'post' ) ) {
					return;
				}

				add_filter( 'post_class', array( $this, 'post_class' ) );
			} );
		}
	}

	/**
	 * Add filters initialization.
	 *
	 * Register filters for the Infinite Scroll module.
	 *
	 * @since 1.0.0
	 */
	protected function init_filters() {
		add_filter( 'cmsmasters_elementor/frontend/settings', array( $this, 'frontend_settings' ) );
	}

	public function post_class( $classes ) {
		$classes[] = 'cmsmasters-single-post';

		return $classes;
	}

	public function ajax_infinite() {
		if (
			! check_ajax_referer(
				$this->get_nonce_name(),
				false,
				false
			)
		) {
			wp_send_json_error( array( 'message' => 'Nonce code has not been installed or does not match.' ), 400 );
		}

		$post_id = Utils::get_if_isset( $_REQUEST, 'post_id' );

		if ( ! $post_id ) {
			wp_send_json_error( array( 'message' => 'Required fields have not been added' ), 400 );
		}

		global $post;

		$post = get_post( $post_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		$data = array();

		setup_postdata( $post );

		$previous_post = get_previous_post();

		if ( $previous_post ) {
			query_posts( // phpcs:ignore WordPress.WP.DiscouragedFunctions.query_posts_query_posts
				array(
					'p' => $previous_post->ID,
					'no_found_rows' => true,
					'posts_per_page' => 1,
					'post_status' => 'publish',
				)
			);

			ob_start();

			$this->render_single();

			$data['is_elementor'] = $this->is_elementor();
			$data['previous_post_id'] = $previous_post->ID;
			$data['previous_post_html'] = ob_get_clean();
			$data['previous_post_data'] = array(
				'document_title' => html_entity_decode( wp_get_document_title() ),
				'permalink' => get_permalink(),
			);
		}

		wp_send_json_success( $data );
	}

	public function get_nonce_name() {
		return $this->get_name();
	}

	public function is_elementor() {
		return (bool) $this->get_document();
	}

	protected function get_document() {
		/**
		 * @var ThemeBuilderModule
		 */
		$theme_builder_module = ThemeBuilderModule::instance();
		$conditions = $theme_builder_module->get_conditions_manager();
		$documents_for_location = $conditions->get_documents_for_location( static::LOCATION );

		if ( ! $documents_for_location ) {
			return;
		}

		return array_shift( $documents_for_location );
	}

	/**
	 * Filter frontend settings.
	 *
	 * Filters the Addon settings for elementor frontend.
	 *
	 * Fired by `cmsmasters_elementor/frontend/settings` Addon filter hook.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings Frontend settings.
	 *
	 * @return array Filtered frontend settings.
	 */
	public function frontend_settings( $settings ) {
		return array_replace_recursive( array(
			'nonces' => array(
				$this->get_name() => wp_create_nonce( $this->get_nonce_name() ),
			),
		), $settings );
	}

	public function render_single() {
		add_filter( 'post_class', array( $this, 'post_class' ) );

		if ( $this->is_elementor() ) {
			cmsmasters_template_do_location( self::LOCATION );
		} else {
			get_template_part( $this->get_single_template_path() );
		}

		remove_filter( 'post_class', array( $this, 'post_class' ) );
	}

	public static function get_single_template_path() {
		return 'template-parts/single';
	}

	public function render_load_more() {
		if ( ! is_singular( 'post' ) || ! get_previous_post() ) {
			return;
		}

		echo '<div class="cmsmasters-post-infinite-scroll">' .
			'<button type="button" data-post-id="' . get_the_ID() . '">';

				Icons_Manager::render_icon( array(
					'value' => 'fas fa-circle-notch fa-spin',
					'library' => 'fa-solid',
				) );

			echo '</button>' .
		'</div>';
	}
}
