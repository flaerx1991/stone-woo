<?php
namespace StereoBankSpace\ThemeConfig;

use StereoBankSpace\Core\Utils\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Theme Config.
 *
 * Main class for theme config.
 */
class Theme_Config {

	/**
	 * Product key.
	 */
	const PRODUCT_KEY = '73746572656f2d62616e6b';

	/**
	 * Import type.
	 *
	 * demos - import content and all data from demo; apply all data from demo;
	 * kit - import content and all data from demo; apply only kit from demo;
	 * only_kit - import content and all data from main demo, and kit from current demo; apply only kit from demo;
	 */
	const IMPORT_TYPE = 'kit';

	/**
	 * Major versions.
	 */
	const MAJOR_VERSIONS = array();

	/**
	 * Default Colors.
	 */
	const PRIMARY_COLOR_DEFAULT = '#DD572D';
	const SECONDARY_COLOR_DEFAULT = '#303030';
	const TEXT_COLOR_DEFAULT = '#696969';
	const ACCENT_COLOR_DEFAULT = '#ECBD15';
	const TERTIARY_COLOR_DEFAULT = '#9F9F9F';
	const BACKGROUND_COLOR_DEFAULT = '#FFFFFF';
	const ALTERNATE_COLOR_DEFAULT = '#F4F4F4';
	const BORDER_COLOR_DEFAULT = '#EAEAEA';

	/**
	 * Default Typography.
	 */
	const PRIMARY_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Cabin';
	const PRIMARY_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '700';

	const SECONDARY_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Cabin';
	const SECONDARY_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '700';

	const TEXT_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Inter';
	const TEXT_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '16', 'unit' => 'px' );
	const TEXT_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = 'normal';
	const TEXT_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const TEXT_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const TEXT_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const TEXT_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.65', 'unit' => 'em' );
	const TEXT_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const TEXT_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const ACCENT_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Cabin';
	const ACCENT_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '18', 'unit' => 'px' );
	const ACCENT_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '500';
	const ACCENT_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const ACCENT_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const ACCENT_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const ACCENT_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.35', 'unit' => 'em' );
	const ACCENT_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const ACCENT_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const TERTIARY_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Cabin';
	const TERTIARY_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '14', 'unit' => 'px' );
	const TERTIARY_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = 'normal';
	const TERTIARY_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const TERTIARY_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const TERTIARY_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const TERTIARY_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.55', 'unit' => 'em' );
	const TERTIARY_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const TERTIARY_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const META_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Cabin';
	const META_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '14', 'unit' => 'px' );
	const META_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '500';
	const META_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const META_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const META_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const META_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.3', 'unit' => 'em' );
	const META_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const META_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const TAXONOMY_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Cabin';
	const TAXONOMY_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '12', 'unit' => 'px' );
	const TAXONOMY_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '700';
	const TAXONOMY_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'uppercase';
	const TAXONOMY_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const TAXONOMY_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const TAXONOMY_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.65', 'unit' => 'em' );
	const TAXONOMY_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '1', 'unit' => 'px' );
	const TAXONOMY_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const SMALL_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Inter';
	const SMALL_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '14', 'unit' => 'px' );
	const SMALL_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = 'normal';
	const SMALL_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const SMALL_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const SMALL_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const SMALL_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.55', 'unit' => 'em' );
	const SMALL_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const SMALL_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const H1_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Cabin';
	const H1_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '82', 'unit' => 'px' );
	const H1_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '700';
	const H1_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const H1_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const H1_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const H1_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.1', 'unit' => 'em' );
	const H1_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '-1', 'unit' => 'px' );
	const H1_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const H2_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Cabin';
	const H2_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '58', 'unit' => 'px' );
	const H2_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '700';
	const H2_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const H2_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const H2_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const H2_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.15', 'unit' => 'em' );
	const H2_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '-1', 'unit' => 'px' );
	const H2_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const H3_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Cabin';
	const H3_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '44', 'unit' => 'px' );
	const H3_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '700';
	const H3_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const H3_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const H3_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const H3_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.2', 'unit' => 'em' );
	const H3_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '-1', 'unit' => 'px' );
	const H3_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const H4_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Cabin';
	const H4_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '34', 'unit' => 'px' );
	const H4_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '700';
	const H4_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const H4_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const H4_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const H4_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.25', 'unit' => 'em' );
	const H4_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const H4_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const H5_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Cabin';
	const H5_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '26', 'unit' => 'px' );
	const H5_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '700';
	const H5_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const H5_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const H5_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const H5_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.3', 'unit' => 'em' );
	const H5_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const H5_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const H6_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Cabin';
	const H6_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '18', 'unit' => 'px' );
	const H6_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '700';
	const H6_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'uppercase';
	const H6_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const H6_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const H6_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.45', 'unit' => 'em' );
	const H6_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '1', 'unit' => 'px' );
	const H6_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const BUTTON_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Cabin';
	const BUTTON_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '14', 'unit' => 'px' );
	const BUTTON_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = '700';
	const BUTTON_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const BUTTON_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'normal';
	const BUTTON_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const BUTTON_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.55', 'unit' => 'em' );
	const BUTTON_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '1', 'unit' => 'px' );
	const BUTTON_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_FONT_FAMILY = 'Cabin';
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_FONT_SIZE = array( 'size' => '32', 'unit' => 'px' );
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_FONT_WEIGHT = 'normal';
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_TEXT_TRANSFORM = 'none';
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_FONT_STYLE = 'italic';
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_TEXT_DECORATION = 'none';
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_LINE_HEIGHT = array( 'size' => '1.4', 'unit' => 'em' );
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_LETTER_SPACING = array( 'size' => '0', 'unit' => 'px' );
	const BLOCKQUOTE_TYPOGRAPHY_DEFAULT_WORD_SPACING = array( 'size' => '0', 'unit' => 'px' );

	/**
	 * Theme_Config constructor.
	 */
	public function __construct() {
		add_action( 'cmsmasters_first_setup', array( $this, 'first_setup_actions' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_default_assets' ), 8 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_default_assets' ), 8 );
	}

	/**
	 * Actions on first setup.
	 */
	public function first_setup_actions() {
		$cpt_support = get_option( 'elementor_cpt_support', array( 'post', 'page', 'e-landing-page' ) );

		if ( is_array( $cpt_support ) ) {
			if ( ! in_array( 'product', $cpt_support ) ) {
				$cpt_support[] = 'product';
			}

			if ( ! in_array( 'services', $cpt_support ) ) {
				$cpt_support[] = 'services';
			}
		}

		update_option( 'elementor_cpt_support', $cpt_support );
	}

	/**
	 * Enqueue default assets.
	 */
	public function enqueue_default_assets() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			wp_enqueue_style(
				'stereo-bank-default-fonts',
				$this->get_default_fonts(),
				array(),
				'1.0.0',
				'screen'
			);
		}

		if ( '' === Utils::get_active_kit() || ! did_action( 'elementor/loaded' ) ) {
			$default_styles = '.wp-block-widget-area h2.wp-block-heading,
			.widget h2 {
				font-family: var(--cmsmasters-h5-font-family);
				font-weight: var(--cmsmasters-h5-font-weight);
				font-style: var(--cmsmasters-h5-font-style);
				text-transform: var(--cmsmasters-h5-text-transform);
				text-decoration: var(--cmsmasters-h5-text-decoration);
				font-size: var(--cmsmasters-h5-font-size);
				line-height: var(--cmsmasters-h5-line-height);
				letter-spacing: var(--cmsmasters-h5-letter-spacing);
				word-spacing: var(--cmsmasters-h5-word-spacing);
			}

			.wp-block-button .wp-block-button__link {
				border-radius: 50px;
			}

			@media only screen and (max-width: 1024px) {
				:root {
					--e-global-typography-h1-font-size: 68px;
					--e-global-typography-h2-font-size: 46px;
					--e-global-typography-h3-font-size: 34px;
					--e-global-typography-h4-font-size: 26px;
					--e-global-typography-h5-font-size: 20px;
					--e-global-typography-h6-font-size: 16px;
					--e-global-typography-text-font-size: 15px;
					--e-global-typography-small-font-size: 13px;
					--e-global-typography-meta-font-size: 13px;
					--e-global-typography-taxonomy-font-size: 11px;
					--e-global-typography-button-font-size: 13px;
					--e-global-typography-accent-font-size: 17px;
					--e-global-typography-tertiary-font-size: 13px;
					--e-global-typography-blockquote-font-size: 26px;
				}

				body {
					--cmsmasters-main-content-padding-top: 80px !important;
					--cmsmasters-main-content-padding-bottom: 80px !important;
					--cmsmasters-single-meta-second-box-margin-bottom: 50px !important;
					--cmsmasters-single-media-box-margin-bottom: 80px !important;
					--cmsmasters-single-comments-box-margin-top: 80px !important;
					--cmsmasters-single-comments-items-hor-gap: 30px !important;
					--cmsmasters-single-nav-box-margin-top: 80px !important;
				}
			}

			@media only screen and (max-width: 767px) {
				:root {
					--e-global-typography-h1-font-size: 56px;
					--e-global-typography-h2-font-size: 40px;
					--e-global-typography-h3-font-size: 30px;
					--e-global-typography-h3-letter-spacing: 0px;
					--e-global-typography-h4-font-size: 22px;
					--e-global-typography-h5-font-size: 18px;
					--e-global-typography-h6-font-size: 15px;
					--e-global-typography-text-font-size: 14px;
					--e-global-typography-small-font-size: 12px;
					--e-global-typography-meta-font-size: 12px;
					--e-global-typography-taxonomy-font-size: 10px;
					--e-global-typography-button-font-size: 12px;
					--e-global-typography-accent-font-size: 16px;
					--e-global-typography-tertiary-font-size: 12px;
					--e-global-typography-blockquote-font-size: 20px;
				}

				body {
					--cmsmasters-archive-compact-media-width: 100%;
					--cmsmasters-archive-media-box-margin-right: 0;
					--cmsmasters-archive-media-box-margin-bottom: 40px;
					--cmsmasters-search-compact-media-width: 100%;
					--cmsmasters-search-media-box-margin-right: 0;
					--cmsmasters-search-media-box-margin-bottom: 40px;
					--cmsmasters-main-content-padding-top: 60px !important;
					--cmsmasters-main-content-padding-bottom: 60px !important;
					--cmsmasters-single-meta-second-box-margin-bottom: 40px !important;
					--cmsmasters-single-media-box-margin-bottom: 60px !important;
					--cmsmasters-single-comments-box-margin-top: 60px !important;
					--cmsmasters-single-comments-items-hor-gap: 20px !important;
					--cmsmasters-single-nav-box-margin-top: 60px !important;
					--cmsmasters-single-nav-box-padding-top: 20px !important;
					--cmsmasters-single-nav-box-padding-right: 20px !important;
					--cmsmasters-single-nav-box-padding-bottom: 20px !important;
					--cmsmasters-single-nav-box-padding-left: 20px !important;
				}
			}';

			wp_add_inline_style( 'stereo-bank-default-fonts', $default_styles );
		}
	}

	/**
	 * Get default fonts.
	 */
	public function get_default_fonts() {
		$fonts = array(
			'Jost' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'400',
				'400italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
			'Lora' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'400',
				'400italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
		);

		$families = array();

		foreach ( $fonts as $font => $weights ) {
			$families[] = str_replace( ' ', '+', $font ) . '%3A' . implode( '%2C', $weights );
		}

		return 'https://fonts.googleapis.com/css?family=' . implode( '%7C', $families );
	}

}
