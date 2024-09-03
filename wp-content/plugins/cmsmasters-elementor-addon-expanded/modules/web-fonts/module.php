<?php
namespace CmsmastersElementor\Modules\WebFonts;

use CmsmastersElementor\Base\Base_Module;
use CmsmastersElementor\Modules\WebFonts\Types\Local;

use Elementor\Core\Common\Modules\Ajax\Module as Ajax;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Module extends Base_Module {

	const MODULE_DIR = __DIR__;

	const MODULE_NAMESPACE = __NAMESPACE__;

	public static $font_preview_phrase = '';

	public function get_name() {
		return 'web-fonts';
	}

	public function __construct() {
		self::$font_preview_phrase = __( 'Almost before we knew it, we had left the ground.', 'cmsmasters-elementor' );

		$this->add_component( 'local', new Local() );

		parent::__construct();

		/**
		 * Addon web fonts module loaded.
		 *
		 * Fires after the web fonts module was fully loaded and instantiated.
		 *
		 * @since 1.0.0
		 *
		 * @param Module $this An instance of web fonts module.
		 */
		do_action( 'cmsmasters_elementor/web_fonts/loaded', $this );
	}

	protected function init_actions() {
		// Ajax
		add_action( 'elementor/ajax/register_actions', array( $this, 'register_ajax_actions' ) );
	}

	protected function init_filters() {
		// Admin
		add_filter( 'cmsmasters_elementor/admin/settings', array( $this, 'filter_admin_settings' ) );
	}

	public function register_ajax_actions( Ajax $ajax ) {
		$ajax->register_ajax_action( 'cmsmasters_editor_local_font_styles_load', array( $this, 'editor_local_font_styles_load' ) );
	}

	/**
	 * Get local web fonts.
	 *
	 * Retrieve the local web fonts module component.
	 *
	 * @return Local
	 */
	public function get_local_fonts() {
		return $this->get_component( 'local' );
	}

	/**
	 * Editor local font styles load.
	 *
	 * Handle editor request to embed/link local font CSS per font family.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 *
	 * @param array $data
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function editor_local_font_styles_load( $data ) {
		if ( empty( $data['type'] ) ) {
			throw new \Exception( 'font_type_is_required' );
		}

		if ( empty( $data['font'] ) ) {
			throw new \Exception( 'font_family_is_required' );
		}

		try {
			return $this->handle_panel_requested_font( $data );
		} catch ( \Exception $exception ) {
			throw $exception;
		}
	}

	/**
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 *
	 * @param array $data
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function handle_panel_requested_font( $data ) {
		$font_family = sanitize_text_field( $data['font'] );
		$font_id = Local::check_font_family( $font_family, 'id' );
		$font_styles = Local::get_local_font_styles( $font_id );

		if ( empty( $font_styles ) ) {
			throw new \Exception(
				/* translators: Local fonts post type editor ajax call error message. %s: Font family */
				sprintf( __( 'Local font with name \'%s\' was not found.', 'cmsmasters-elementor' ), $font_family )
			);
		}

		return array( 'font_styles' => $font_styles );
	}

	public function filter_admin_settings( $settings ) {
		$settings = array_replace_recursive( $settings, array(
			'i18n' => array(
				'fontPreviewPhrase' => self::$font_preview_phrase,
				'fontWeightArray' => self::get_font_weight_labels(),
				'fontWeight' => __( 'Font Weight', 'cmsmasters-elementor' ),
				'fontStyle' => __( 'Font Style', 'cmsmasters-elementor' ),
				'localFontEmptyUploadNotice' => __( 'You need to upload font archive to publish.', 'cmsmasters-elementor' ),
			),
		) );

		return $settings;
	}

	public static function get_font_weight_labels() {
		return array(
			'100' => __( 'Thin (Hairline)', 'cmsmasters-elementor' ),
			'200' => __( 'Extra Light (Ultra Light)', 'cmsmasters-elementor' ),
			'300' => __( 'Light', 'cmsmasters-elementor' ),
			'400' => __( 'Normal (Regular)', 'cmsmasters-elementor' ),
			'500' => __( 'Medium', 'cmsmasters-elementor' ),
			'600' => __( 'Semi Bold (Demi Bold)', 'cmsmasters-elementor' ),
			'700' => __( 'Bold', 'cmsmasters-elementor' ),
			'800' => __( 'Extra Bold (Ultra Bold)', 'cmsmasters-elementor' ),
			'900' => __( 'Black (Heavy)', 'cmsmasters-elementor' ),
			'950' => __( 'Extra Black (Ultra Black)', 'cmsmasters-elementor' ),
		);
	}

}
