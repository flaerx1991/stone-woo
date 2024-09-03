<?php
namespace CmsmastersElementor;

use CmsmastersElementor\Base\Base_App;
use CmsmastersElementor\Plugin;
use CmsmastersElementor\Classes\Loop_Dynamic_CSS;

use Elementor\Core\Files\CSS\Post as PostCSS;
use Elementor\Core\Files\CSS\Post_Preview;
use Elementor\Core\Responsive\Files\Frontend as ResponsiveFrontendFile;
use Elementor\Core\Responsive\Responsive;
use Elementor\Fonts;
use Elementor\Utils;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Addon `Frontend` class.
 *
 * Addon `Frontend` class is responsible for loading scripts and
 * styles needed for the plugin frontend.
 *
 * @since 1.0.0
 *
 * @see \CmsmastersElementor\Base\Base_App
 */
class Frontend extends Base_App {

	/**
	 * Whether the excerpt is being called.
	 *
	 * Used to determine whether the call to `the_content()` came from `get_the_excerpt()`.
	 *
	 * @since 1.0.0
	 *
	 * @var bool Whether the excerpt is being used. Default is false.
	 */
	private $is_excerpt = false;

	private $template_document;

	private static $css_printed = array();

	/**
	 * Get app name.
	 *
	 * Retrieve the name of the application.
	 *
	 * @since 1.0.0
	 *
	 * @return string App name.
	 */
	public function get_name() {
		return 'cmsmasters-frontend';
	}

	/**
	 * Ensure frontend settings.
	 *
	 * Ensures that the frontend `$settings` member is initialized.
	 *
	 * @since 1.0.0
	 *
	 * @return array Frontend settings.
	 */
	protected function get_init_settings() {
		$settings = array(
			'multisite_current_blog_id' => ( is_multisite() ? get_current_blog_id() : '' ),
			'cmsmasters_version' => CMSMASTERS_ELEMENTOR_VERSION,
			'urls' => array(
				'cmsmasters_assets' => CMSMASTERS_ELEMENTOR_ASSETS_URL,
			),
			'i18n' => array(
				'edit_element' => __( 'Edit %s', 'cmsmasters-elementor' ), // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
			),
		);

		$settings = array_replace_recursive( parent::get_init_settings(), $settings );

		/**
		 * Frontend settings.
		 *
		 * Filters the frontend settings.
		 *
		 * @since 1.0.0
		 *
		 * @param array $settings Frontend settings.
		 */
		$settings = apply_filters( 'cmsmasters_elementor/frontend/settings', $settings );

		return $settings;
	}

	/**
	 * Add actions initialization.
	 *
	 * Register actions for the Frontend app.
	 *
	 * @since 1.0.0
	 * @since 1.2.0 Added Elementor Kit preview redirect.
	 */
	protected function init_actions() {
		// add_action( 'template_redirect', array( $this, 'kit_preview_redirect' ) );

		add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_frontend_scripts' ) );
		add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );

		add_action( 'elementor/frontend/after_register_styles', array( $this, 'register_frontend_styles' ) );
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_frontend_styles' ) );

		// add_action( 'elementor/preview/enqueue_scripts', array( $this, 'enqueue_motion_effect_preview_scripts' ) );
		// add_action( 'elementor/widget/before_render_content', array( $this, 'enqueue_motion_effect_frontend_scripts' ) );
	}

	/**
	 * Add filters initialization.
	 *
	 * Register filters for the Frontend app.
	 *
	 * @since 1.0.0
	 * @since 1.1.0 Update for new Elementor responsive mode breakpoints.
	 */
	protected function init_filters() {
		// BC for Elementor version < 3.2.0
		$responsive_templates_filter = version_compare( ELEMENTOR_VERSION, '3.2.0', '>=' ) ?
			'breakpoints/get_stylesheet_template' :
			'responsive/get_stylesheet_templates';

		add_filter( "elementor/core/{$responsive_templates_filter}", array( $this, 'get_responsive_stylesheet_templates' ) );

		// Hack to avoid enqueue post CSS while it's a `the_excerpt` call.
		add_filter( 'get_the_excerpt', array( $this, 'start_excerpt_flag' ), 1 );
		add_filter( 'get_the_excerpt', array( $this, 'end_excerpt_flag' ), 20 );
	}

	/**
	 * Kit preview redirect.
	 *
	 * Elementor kit preview redirect to home page.
	 *
	 * Fired by `template_redirect` WordPress action hook.
	 *
	 * @since 1.2.0
	 */
	public function kit_preview_redirect() {
		if ( ! is_admin() ) {
			$elementor = Plugin::elementor();
			$kit = $elementor->kits_manager->get_active_kit();

			if ( ! $kit ) {
				return;
			}

			if ( $elementor->preview->is_preview_mode( $kit->get_post()->ID ) ) {
				wp_safe_redirect( home_url() );

				exit();
			}
		}
	}

	/**
	 * Enqueue frontend js libraries.
	 *
	 * Load all required frontend javascript libraries.
	 *
	 * Fired by `elementor/frontend/before_register_scripts` Elementor action hook.
	 *
	 * @since 1.0.0
	 */
	public function register_frontend_scripts() {
		wp_register_script(
			'cmsmasters-webpack-runtime',
			$this->get_js_assets_url( 'webpack.runtime', 'assets/js/' ),
			array(),
			CMSMASTERS_ELEMENTOR_VERSION,
			true
		);

		// wp_register_script(
		// 	'cmsmasters-theme-add-to-cart-override',
		// 	$this->get_js_assets_url( 'theme-add-to-cart', 'assets/overides/', false ),
		// 	array(),
		// 	CMSMASTERS_ELEMENTOR_VERSION,
		// 	true
		// );
		
		wp_register_script(
			'perfect-scrollbar-js',
			$this->get_js_assets_url( 'perfect-scrollbar', self::get_lib_src( 'perfect-scrollbar' ) ),
			array(),
			'1.4.0',
			true
		);

		$google_api_key = get_option( 'elementor_google_api_key' );

		if ( $google_api_key ) {
			wp_register_script(
				'google-maps-api',
				"https://maps.googleapis.com/maps/api/js?key={$google_api_key}",
				array(),
				'1.0.0',
				true
			);
		}

		wp_register_script(
			'typed',
			$this->get_js_assets_url( 'typed', self::get_lib_src( 'typed' ) ),
			array(),
			'2.0.11',
			true
		);

		wp_register_script(
			'vticker',
			$this->get_js_assets_url( 'jquery.vticker', self::get_lib_src( 'vticker' ) ),
			array( 'jquery' ),
			'1.21.0',
			true
		);

		wp_register_script(
			'morphext',
			$this->get_js_assets_url( 'morphext', self::get_lib_src( 'morphext' ) ),
			array( 'jquery' ),
			'2.4.7',
			true
		);

		wp_register_script(
			'lettering',
			$this->get_js_assets_url( 'jquery.lettering', self::get_lib_src( 'lettering/js' ) ),
			array( 'jquery' ),
			'0.7.0',
			true
		);

		wp_register_script(
			'textillate',
			$this->get_js_assets_url( 'jquery.textillate', self::get_lib_src( 'textillate' ) ),
			array(
				'jquery',
				'lettering',
			),
			'0.4.1',
			true
		);

		wp_register_script(
			'youtube-iframe-api',
			'https://www.youtube.com/iframe_api',
			array(),
			'1.0.0',
			true
		);

		wp_register_script(
			'vimeo-iframe-api',
			'https://player.vimeo.com/api/player.js',
			array(),
			'1.0.0',
			true
		);

		wp_register_script(
			'basicScroll',
			$this->get_js_assets_url( 'basicScroll', self::get_lib_src( 'basicScroll' ) ),
			array(),
			'3.0.3',
			true
		);

		wp_register_script(
			'vanilla-tilt',
			$this->get_js_assets_url( 'vanilla-tilt', self::get_lib_src( 'vanilla-tilt' ) ),
			array(),
			'1.7.0',
			true
		);

		wp_register_script(
			'anime',
			$this->get_js_assets_url( 'anime', self::get_lib_src( 'anime' ) ),
			array(),
			'3.2.1',
			true
		);

		wp_register_script(
			'hc-sticky',
			$this->get_js_assets_url( 'hc-sticky', self::get_lib_src( 'hc-sticky' ) ),
			array(),
			'2.2.6',
			true
		);

		wp_register_script(
			'headroom',
			$this->get_js_assets_url( 'headroom', self::get_lib_src( 'headroom' ) ),
			array(),
			'0.12.0',
			true
		);

		wp_register_script(
			'move',
			$this->get_js_assets_url( 'move', self::get_lib_src( 'move' ) ),
			array(),
			'2.0.0',
			true
		);
	}

	/**
	 * Enqueue frontend scripts.
	 *
	 * Load all required frontend scripts.
	 *
	 * Fired by `elementor/frontend/before_enqueue_scripts` Elementor action hook.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_frontend_scripts() {
		wp_enqueue_script(
			'cmsmasters-frontend',
			$this->get_js_assets_url( 'frontend' ),
			array(
				'cmsmasters-webpack-runtime',
				//'cmsmasters-theme-add-to-cart-override',
				'jquery',
				'elementor-frontend-modules',
				'basicScroll',
				'vanilla-tilt',
				'anime',
				'hc-sticky',
				'headroom',
			),
			CMSMASTERS_ELEMENTOR_VERSION,
			true
		);

		$this->print_config( 'cmsmasters-frontend' );
	}

	/**
	 * Register frontend styles.
	 *
	 * Register all required frontend styles.
	 *
	 * Fired by `elementor/frontend/after_register_styles` Elementor action hook.
	 *
	 * @since 1.0.0
	 */
	public function register_frontend_styles() {
		wp_register_style(
			'cmsmasters-icons',
			$this->get_css_assets_url( 'cmsmasters-icons', self::get_lib_src( 'cmsicons/css' ) ),
			array(),
			'1.0.0'
		);

		$min_suffix = Utils::is_script_debug() ? '' : '.min';
		$direction_suffix = is_rtl() ? '-rtl' : '';
		$frontend_base_file_name = $this->is_optimized_css_mode() ? 'frontend-lite' : 'frontend';
		$frontend_file_name = $frontend_base_file_name . $direction_suffix . $min_suffix . '.css';
		$has_custom_breakpoints = Plugin::elementor()->breakpoints->has_custom_breakpoints();

		wp_register_style(
			'cmsmasters-frontend',
			$this->get_frontend_file_url( $frontend_file_name, $has_custom_breakpoints ),
			array(
				'cmsmasters-icons',
			),
			$has_custom_breakpoints ? null : CMSMASTERS_ELEMENTOR_VERSION
		);
	}

	/**
	 * Enqueue frontend styles.
	 *
	 * Load all required frontend styles.
	 *
	 * Fired by `elementor/frontend/after_enqueue_styles` Elementor action hook.
	 *
	 * @since 1.0.0
	 * @since 1.1.0 Update for new Elementor responsive mode breakpoints.
	 */
	public function enqueue_frontend_styles() {
		wp_enqueue_style( 'cmsmasters-frontend' );

		// Do not disable these styles
		wp_register_style(
			'animate',
			$this->get_css_assets_url( 'animate', self::get_lib_src( 'lettering/css' ) ),
			array(),
			CMSMASTERS_ELEMENTOR_VERSION
		);
	}

	/**
	 * Get responsive stylesheets path.
	 *
	 * Retrieve the responsive stylesheet templates path.
	 *
	 * @since 1.0.0
	 * @since 1.3.1 Fixed for PHP 8.
	 *
	 * @return string Responsive stylesheet templates path.
	 */
	private static function get_responsive_css_path() {
		return CMSMASTERS_ELEMENTOR_ASSETS_PATH . 'css/templates/';
	}

	/**
	 * Extend stylesheets templates.
	 *
	 * Extend templates array with responsive stylesheets.
	 *
	 * Fired by `elementor/core/breakpoints/get_stylesheet_template` Elementor filter hook.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $templates Templates array.
	 *
	 * @return string[] Filtered templates array.
	 */
	public function get_responsive_stylesheet_templates( $templates ) {
		$templates_paths = glob( self::get_responsive_css_path() . '*.css' );

		foreach ( $templates_paths as $template_path ) {
			$file_name = 'cmsmasters-custom-' . basename( $template_path );

			$templates[ $file_name ] = $template_path;
		}

		return $templates;
	}

	/**
	 * Get Frontend File URL
	 *
	 * Returns the URL for the CSS file to be loaded in the front end. If requested via the second parameter, a custom
	 * file is generated based on a passed template file name. Otherwise, the URL for the default CSS file is returned.
	 *
	 * @since 1.6.5
	 *
	 * @param string $frontend_file_name
	 * @param boolean $custom_file
	 *
	 * @return string frontend file URL
	 */
	public function get_frontend_file_url( $frontend_file_name, $custom_file ) {
		if ( $custom_file ) {
			$frontend_file = $this->get_frontend_file( $frontend_file_name );

			$frontend_file_url = $frontend_file->get_url();
		} else {
			$frontend_file_url = CMSMASTERS_ELEMENTOR_ASSETS_URL . 'css/' . $frontend_file_name;
		}

		return $frontend_file_url;
	}

	/**
	 * Get Frontend File Path
	 *
	 * Returns the path for the CSS file to be loaded in the front end. If requested via the second parameter, a custom
	 * file is generated based on a passed template file name. Otherwise, the path for the default CSS file is returned.
	 *
	 * @since 1.6.5
	 *
	 * @param string $frontend_file_name
	 * @param boolean $custom_file
	 *
	 * @return string frontend file path
	 */
	public function get_frontend_file_path( $frontend_file_name, $custom_file ) {
		if ( $custom_file ) {
			$frontend_file = $this->get_frontend_file( $frontend_file_name );

			$frontend_file_path = $frontend_file->get_path();
		} else {
			$frontend_file_path = CMSMASTERS_ELEMENTOR_ASSETS_PATH . 'css/' . $frontend_file_name;
		}

		return $frontend_file_path;
	}

	/**
	 * Get Frontend File
	 *
	 * Returns a frontend file instance.
	 *
	 * @since 1.6.5
	 *
	 * @param string $frontend_file_name
	 * @param string $file_prefix
	 * @param string $template_file_path
	 *
	 * @return FrontendFile
	 */
	public function get_frontend_file( $frontend_file_name, $file_prefix = 'cmsmasters-custom-', $template_file_path = '' ) {
		static $cached_frontend_files = [];

		$file_name = $file_prefix . $frontend_file_name;

		if ( isset( $cached_frontend_files[ $file_name ] ) ) {
			return $cached_frontend_files[ $file_name ];
		}

		if ( ! $template_file_path ) {
			$template_file_path = self::get_responsive_css_path() . $frontend_file_name;
		}

		$frontend_file = new ResponsiveFrontendFile( $file_name, $template_file_path );

		$time = $frontend_file->get_meta( 'time' );

		if ( ! $time ) {
			$frontend_file->update();
		}

		$cached_frontend_files[ $file_name ] = $frontend_file;

		return $frontend_file;
	}

	/**
	 * Start excerpt flag.
	 *
	 * Flags when `the_excerpt` is called. Used to avoid enqueueing CSS in the excerpt.
	 *
	 * @since 1.0.0
	 *
	 * @param string $excerpt The post excerpt.
	 *
	 * @return string The post excerpt.
	 */
	public function start_excerpt_flag( $excerpt ) {
		$this->is_excerpt = true;

		return $excerpt;
	}

	/**
	 * End excerpt flag.
	 *
	 * Flags when `the_excerpt` call ended.
	 *
	 * @since 1.0.0
	 *
	 * @param string $excerpt The post excerpt.
	 *
	 * @return string The post excerpt.
	 */
	public function end_excerpt_flag( $excerpt ) {
		$this->is_excerpt = false;

		return $excerpt;
	}

	/**
	 * Retrieve builder widget content template.
	 *
	 * Used to render and return the post content with all the Elementor elements.
	 *
	 * @since 1.0.0
	 * @since 1.11.4 Fixed loop dynamic css for post template.
	 *
	 * @param int $post_id The post ID.
	 * @param bool $with_css Whether to retrieve the content with CSS or not.
	 * @param bool $with_loop_dynamic_css Whether to retrieve the content with loop dynamic CSS or not.
	 *
	 * @return string The post content.
	 */
	public function get_widget_template( $post_id, $with_css = false, $with_loop_dynamic_css = false ) {
		if ( ! get_post( $post_id ) ) {
			return '';
		}

		$editor = Plugin::elementor()->editor;
		$is_edit_mode = $editor->is_edit_mode();

		// Avoid recursion
		if ( get_the_ID() === (int) $post_id ) {
			$content = '';

			if ( $is_edit_mode ) {
				$content = '<div class="elementor-alert elementor-alert-danger">' .
					__( 'Invalid Data: The Template ID cannot be the same as the currently edited template. Please choose a different one.', 'cmsmasters-elementor' ) .
				'</div>';
			}

			return $content;
		}

		// Set edit mode as false, so don't render settings and etc.
		$editor->set_edit_mode( false );

		$content = $this->get_builder_template_content( $post_id, $with_css, $with_loop_dynamic_css );

		// Restore edit mode state
		$editor->set_edit_mode( $is_edit_mode );

		return $content;
	}

	/**
	 * Retrieve builder template content.
	 *
	 * Used to render and return the post content with all the Elementor elements.
	 *
	 * @since 1.0.0
	 * @since 1.1.0 Update for new Elementor responsive mode breakpoints.
	 * @since 1.6.5 The display of templates has been fixed.
	 * @since 1.11.4 Fixed loop dynamic css for post template.
	 *
	 * @param int $post_id The post ID.
	 * @param bool $with_css Whether to retrieve the content with CSS or not.
	 * @param bool $with_loop_dynamic_css Whether to retrieve the content with loop dynamic CSS or not.
	 *
	 * @return string The post content.
	 */
	public function get_builder_template_content( $post_id, $with_css = false, $with_loop_dynamic_css = false ) {
		if ( post_password_required( $post_id ) ) {
			return '';
		}

		$elementor = Plugin::elementor();

		// BC for Elementor version < 3.2.0
		$is_build_with_elementor = version_compare( ELEMENTOR_VERSION, '3.2.0', '>=' ) ?
			$elementor->documents->get( $post_id )->is_built_with_elementor() :
			$elementor->db->is_built_with_elementor( $post_id );

		if ( ! $is_build_with_elementor ) {
			return '';
		}

		$this->set_template_document( $post_id );

		$elementor_documents = $elementor->documents;

		// Change the current post, so widgets can use `documents->get_current`.
		$elementor_documents->switch_to_document( $this->template_document );

		$data = $this->template_document->get_elements_data();

		/**
		 * Frontend builder content data.
		 *
		 * Filters the builder content in the frontend.
		 *
		 * @since 1.0.0
		 *
		 * @param array $data The builder content.
		 * @param int $post_id The post ID.
		 */
		$data = apply_filters( 'cmsmasters_elementor/frontend/builder_content_data', $data, $post_id );

		do_action( 'elementor/frontend/before_get_builder_content', $this->template_document, $this->is_excerpt );

		if ( empty( $data ) ) {
			return '';
		}

		if ( ! $this->is_excerpt ) {
			if ( $with_css ) {
				$css_file = $this->get_enqueued_template_css( $post_id );
			}

			if ( $with_loop_dynamic_css ) {
				$current_post_id = get_the_ID();

				if ( ! $this->is_css_printed( 'template-' . $post_id, $current_post_id ) ) {
					$loop_dynamic_css_file = Loop_Dynamic_CSS::create( $current_post_id, $post_id );

					$loop_dynamic_css = $loop_dynamic_css_file->get_content();

					$loop_dynamic_css = str_replace( '.elementor-' . $current_post_id, '.post-' . $current_post_id, $loop_dynamic_css );

					$this->set_css_status_printed( 'template-' . $post_id, $current_post_id );
				} else {
					$with_loop_dynamic_css = false;
				}
			}
		}

		ob_start();

		// if ( ! empty( $css_file ) && $with_css ) {
		// 	echo $this->print_css( $css_file );
		// }

		if ( $with_loop_dynamic_css && ! empty( $loop_dynamic_css ) ) {
			$this->print_styles( $loop_dynamic_css );
		}

		$this->template_document->print_elements_with_wrapper( $data );

		$content = ob_get_clean();
		$content = $this->process_more_tag( $content );

		/**
		 * Frontend content.
		 *
		 * Filters the content in the frontend.
		 *
		 * @since 1.0.0
		 *
		 * @param string $content The content.
		 */
		$content = apply_filters( 'cmsmasters_elementor/frontend/the_content', $content );

		$elementor_documents->restore_document();

		return $content;
	}

	public function set_template_document( $post_id ) {
		$post_id = apply_filters( 'cmsmasters_wpml_translate_template_id', $post_id );

		$this->template_document = Plugin::elementor()->documents->get_doc_for_frontend( $post_id );
	}

	public function get_enqueued_template_css( $post_id ) {
		if ( ! $this->template_document ) {
			return;
		}

		if ( $this->template_document->is_autosave() ) {
			$css_file = Post_Preview::create( $this->template_document->get_post()->ID );
		} else {
			$css_file = PostCSS::create( $post_id );
		}

		$css_file->enqueue();

		return $css_file;
	}

	/**
	 * Lazyload Widget enqueue template css.
	 *
	 * @since 1.11.1
	 *
	 * @param array $template_ids Template IDs.
	 * @param string $widget_id Widget ID.
	 */
	public function lazyload_widget_enqueue_template_css( $template_ids, $widget_id ) {
		if ( empty( $template_ids ) || empty( $widget_id ) ) {
			return;
		}

		foreach ( $template_ids as $template_id ) {
			if ( $this->is_css_printed( $widget_id, $template_id ) ) {
				continue;
			}

			$this->set_template_document( $template_id );

			do_action( 'elementor/frontend/before_get_builder_content', $this->template_document, $this->is_excerpt );

			if ( ! $this->is_excerpt ) {
				$this->get_enqueued_template_css( $template_id );
			}

			$this->set_css_status_printed( $widget_id, $template_id );
		}
	}

	/**
	 * Process More Tag
	 *
	 * Respect the native WP (<!--more-->) tag.
	 *
	 * @since 1.0.0
	 *
	 * @param $content The post content.
	 *
	 * @return string Processed post content.
	 */
	private function process_more_tag( $content ) {
		$content = str_replace( '&lt;!--more--&gt;', '<!--more-->', $content );
		$parts = get_extended( $content );

		if ( empty( $parts['extended'] ) ) {
			return $content;
		}

		$post_id = get_post()->ID;

		if ( is_singular() ) {
			return $parts['main'] . '<div id="more-' . $post_id . '"></div>' . $parts['extended'];
		}

		if ( empty( $parts['more_text'] ) ) {
			$parts['more_text'] = __( '(more&hellip;)', 'cmsmasters-elementor' );
		}

		/* translators: %s: Current post title */
		$more_link_label = sprintf( __( 'Continue reading %s', 'cmsmasters-elementor' ), the_title_attribute( array( 'echo' => false ) ) );

		$more_link_text = sprintf( '<span aria-label="%1$s">%2$s</span>', $more_link_label, $parts['more_text'] );

		$more_link_element = sprintf(
			' <a href="%1$s#more-%2$s" class="more-link elementor-more-link">%3$s</a>',
			get_permalink(),
			$post_id,
			$more_link_text
		);

		$more_link = apply_filters( 'the_content_more_link', $more_link_element, $more_link_text );

		return force_balance_tags( $parts['main'] ) . $more_link;
	}

	/**
	 * Print CSS.
	 *
	 * Output the final CSS inside the `<style>` tags and all the frontend fonts in
	 * use.
	 *
	 * @since 1.0.0
	 */
	public function print_css( $css_file ) {
		$css_content = $css_file->get_content();

		if ( empty( $css_content ) ) {
			return;
		}

		$style = sprintf(
			'<style id="cmsmasters-template-styles-%1$s">%2$s</style>',
			$css_file->get_post_ID(),
			$css_content
		); // XSS ok.

		Plugin::elementor()->frontend->print_fonts_links();

		return $style;
	}

	public function print_template_css( $templates, $widget_id ) {
		$elementor = Plugin::elementor();

		$styles = '';

		foreach ( $templates as $template_id ) {
			if ( ! $this->is_css_printed( $widget_id, $template_id ) ) {
				$this->set_template_document( $template_id );

				$css_file = $this->get_enqueued_template_css( $template_id );

				if ( ! empty( $css_file ) ) {
					$this->enqueue_css_file_fonts( $css_file );

					$styles .= $this->print_css( $css_file );
				}

				$this->set_css_status_printed( $widget_id, $template_id );
			}
		}

		if ( $elementor->editor->is_edit_mode() || is_admin() ) {
			echo $styles;
		} else {
			return $styles;
		}
	}

	public function is_css_printed( $widget_id, $template_id ) {
		if ( ! isset( self::$css_printed[ $widget_id ] ) ) {
			return false;
		}

		return in_array( $template_id, self::$css_printed[ $widget_id ], true );
	}

	public function set_css_status_printed( $widget_id, $template_id ) {
		if ( ! isset( self::$css_printed[ $widget_id ] ) ) {
			self::$css_printed[ $widget_id ] = array();
		}

		self::$css_printed[ $widget_id ][] = $template_id;
	}

	public function enqueue_css_file_fonts( $css_file ) {
		$template_meta = $css_file->get_meta();
		$template_fonts = $template_meta['fonts'];

		$this->enqueue_fonts( $template_fonts );
	}

	public function enqueue_fonts( $fonts ) {
		$google_fonts = array(
			'google' => array(),
			'early' => array(),
		);

		foreach ( $fonts as $font ) {
			$font_type = Fonts::get_font_type( $font );

			switch ( $font_type ) {
				case Fonts::GOOGLE:
					$google_fonts['google'][] = $font;

					break;
				case Fonts::EARLYACCESS:
					$google_fonts['early'][] = $font;

					break;
			}
		}

		$this->enqueue_google_fonts( $google_fonts );
	}

	public function enqueue_google_fonts( $google_fonts = array() ) {
		if ( ! empty( $google_fonts['google'] ) ) {
			$sizes = $this->generate_google_fonts_sizes();

			foreach ( $google_fonts['google'] as &$font ) {
				$font = sprintf( 'family=%1$s:ital,wght@%2$s', str_replace( ' ', '+', $font ), $sizes );
			}

			$fonts_url = sprintf( 'https://fonts.googleapis.com/css2?%s&display=swap', implode( '&', $google_fonts['google'] ) );

			$subsets = $this->get_google_fonts_subsets();
			$locale = get_locale();

			if ( isset( $subsets[ $locale ] ) ) {
				$fonts_url .= '&subset=' . $subsets[ $locale ];
			}

			$this->print_styles( "@import url('{$fonts_url}');" );
		}

		if ( ! empty( $google_fonts['early'] ) ) {
			foreach ( $google_fonts['early'] as $current_font ) {
				$font_url = sprintf( 'https://fonts.googleapis.com/earlyaccess/%s.css', strtolower( str_replace( ' ', '', $current_font ) ) );

				$this->print_styles( "@import url('{$font_url}');" );
			}
		}
	}

	public function generate_google_fonts_sizes() {
		$sizes = '';

		foreach ( array( 0, 1 ) as $ital ) {
			for ( $wght = 100; 900 >= $wght; $wght += 100 ) {
				$sizes .= "{$ital},{$wght};";
			}
		}

		return rtrim( $sizes, ';' );
	}

	public function get_google_fonts_subsets() {
		return array(
			'ru_RU' => 'cyrillic',
			'bg_BG' => 'cyrillic',
			'he_IL' => 'hebrew',
			'el' => 'greek',
			'vi' => 'vietnamese',
			'uk' => 'cyrillic',
			'cs_CZ' => 'latin-ext',
			'ro_RO' => 'latin-ext',
			'pl_PL' => 'latin-ext',
		);
	}

	public function print_styles( $styles ) {
		printf( '<style>%s</style>', $styles );
	}

	/**
	 * Is optimized css mode
	 *
	 * Returns optimized css mode status.
	 *
	 * @since 1.6.5
	 *
	 * @return bool optimized css mode status.
	 */
	private function is_optimized_css_mode() {
		$is_optimized_css_loading = Plugin::elementor()->experiments->is_feature_active( 'e_optimized_css_loading' );

		return ! Utils::is_script_debug() && $is_optimized_css_loading && ! Plugin::elementor()->preview->is_preview_mode();
	}

}
