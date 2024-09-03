<?php
namespace CmsmastersElementor\Libraries\Lazyload;

use CmsmastersElementor\Plugin;
use CmsmastersElementor\Utils;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Lazyload handler class is responsible for lazyload methods.
 *
 * @since 1.0.0
 */
final class Lazyload {

	const IMAGE_SIZE_NAME_PREFIX = 'cmsmasters-lazyload';

	/**
	 * Placeholder by default.
	 *
	 * @var string Placeholder by default.
	 */
	private static $placeholder;

	/**
	 * Placeholder width.
	 *
	 * @var string Placeholder width.
	 */
	private static $lazyload_width;

	/**
	 * Image sizes.
	 *
	 * @var array Image sizes.
	 */
	private static $image_sizes;

	/**
	 * Lazyload constructor.
	 *
	 * Run lazyload methods.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( false !== strpos( $_SERVER['HTTP_USER_AGENT'], 'OS 16' ) ) {
			return;
		}

		// Iframes lazyload
		// add_filter( 'embed_oembed_html', array( $this, 'embed_oembed_html_filter' ) ); // todo check this

		// Images Lazyload
		self::$placeholder = self::get_image_placeholder( 1, 1, true );
		self::$lazyload_width = apply_filters( 'cmsmasters_placeholder_lazyload_width', 30 );

		add_filter( 'init', array( $this, 'add_sizes' ) );
		add_filter( 'image_size_names_choose', array( $this, 'image_size_names_choose' ) );
		add_filter( 'wp_update_attachment_metadata', array( $this, 'generate_attachment_placeholder' ) );
		add_filter( 'wp_generate_attachment_metadata', array( $this, 'generate_attachment_placeholder' ) );
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'add_image_placeholders' ), 10, 3 );

		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

		add_filter( 'the_content', array( $this, 'content_process_images' ), 200, 1 );
		add_filter( 'get_avatar', array( $this, 'content_process_images' ), 200, 1 );
		add_filter( 'elementor/widget/render_content', array( $this, 'content_process_images' ), 200, 1 );
		add_filter( 'elementor/image_size/get_attachment_image_html', array( $this, 'elementor_custom_size_attachment' ), 9999, 4 );
	}

	/**
	 * Filter embed html.
	 *
	 * @since 1.0.0
	 *
	 * @param string $html Embed HTML.
	 *
	 * @return string Filtered embed html.
	 */
	public function embed_oembed_html_filter( $html ) {
		if ( false !== strpos( $html, 'instagram-media' ) ) {
			return $html;
		} elseif ( false !== strpos( $html, 'class="' ) ) {
			$new_html = str_replace( 'class="', 'class="cmsmasters-iframe cmsmasters-lazyload lazyload ', $html );
		} else {
			$new_html = str_replace( '<iframe', '<iframe class="cmsmasters-iframe cmsmasters-lazyload lazyload"', $html );
		}

		$new_html = str_replace( 'src="', 'data-src="', $new_html );

		return $new_html;
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.0.0
	 */
	public function wp_enqueue_scripts() {
		$is_test_mode = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || defined( 'ELEMENTOR_TESTS' ) && ELEMENTOR_TESTS;
		$maybe_min = ( ! $is_test_mode ) ? '.min' : '';

		wp_enqueue_script(
			'lazysizes',
			CMSMASTERS_ELEMENTOR_ASSETS_LIB_URL . "lazysizes/lazysizes{$maybe_min}.js",
			array(),
			'5.3.0',
			true
		);
	}

	/**
	 * Add placeholder image sizes.
	 *
	 * @since 1.0.0
	 */
	public function add_sizes() {
		self::$image_sizes = Utils::get_available_image_sizes();

		add_image_size( self::IMAGE_SIZE_NAME_PREFIX . '-full', self::$lazyload_width, 9999 );

		foreach ( self::$image_sizes as $size => $data ) {
			$divider = $data['width'] / $data['height'];

			add_image_size( $this->get_lazyload_slug( $size ), self::$lazyload_width, intval( self::$lazyload_width / $divider ), $data['crop'] );
		}
	}

	/**
	 * Filter for image_size_names_choose, don't show lazyload placeholder in size names for images.
	 *
	 * @since 1.0.0
	 *
	 * @param array $sizes Array of image sizes.
	 *
	 * @return array Filtered image sizes.
	 */
	public function image_size_names_choose( $sizes ) {
		$pattern = '/^' . self::IMAGE_SIZE_NAME_PREFIX . '/';

		return array_filter( $sizes, function( $size_name ) use ( $pattern ) {
			return ! preg_match( $pattern, $size_name );
		}, ARRAY_FILTER_USE_KEY );
	}

	/**
	 * Get image placeholder by default.
	 *
	 * @since 1.0.0
	 * @since 1.3.2 Fixed image placeholder size.
	 *
	 * @param array $width Placeholder width.
	 * @param array $height Placeholder height.
	 *
	 * @return string Placeholder image src.
	 */
	protected static function get_image_placeholder( $width = 1, $height = 1 ) {
		$cache_name = sprintf( 'cmsmasters_image_placeholder_%s_%s', $width, $height );
		$placeholder_image = get_transient( $cache_name );

		if ( ! $placeholder_image ) {
			$placeholder_code = ob_start();

			$width = ( ( empty( $width ) || 1 > $width ) ? 1 : $width );
			$height = ( ( empty( $height ) || 1 > $height ) ? 1 : $height );

			$image = imagecreate( $width, $height );
			$background = imagecolorallocatealpha( $image, 0, 0, 255, 127 );

			imagepng( $image, null, 9 );
			imagecolordeallocate( $image, $background );
			imagedestroy( $image );

			$placeholder_code = ob_get_clean();

			$placeholder_image = 'data:image/png;base64,' . base64_encode( $placeholder_code );

			set_transient( $cache_name, $placeholder_image );
		}

		return $placeholder_image;
	}

	/**
	 * Generate attachment placeholder.
	 *
	 * @since 1.0.0
	 *
	 * @param array $metadata Placeholder data.
	 *
	 * @return array Metadata.
	 */
	public function generate_attachment_placeholder( $metadata ) {
		if ( isset( $metadata['width'] ) && isset( $metadata['height'] ) ) {
			$metadata['placeholder'] = self::get_image_placeholder( $metadata['width'], $metadata['height'] );
		}

		if ( isset( $metadata['sizes'] ) ) {
			foreach ( $metadata['sizes'] as $slug => & $size ) {
				if ( preg_match( '/cmsmasters-lazyload/', $slug ) ) {
					continue;
				}

				if ( isset( $size['width'] ) && isset( $size['height'] ) ) {
					$size['placeholder'] = self::get_image_placeholder( $size['width'], $size['height'] );
				}
			}
		}

		return $metadata;
	}

	/**
	 * Get image proportion from src.
	 *
	 * @since 1.0.0
	 *
	 * @param string $src Image src.
	 *
	 * @return array Image width and height.
	 */
	public static function get_proportion_from_src( $src ) {
		preg_match( '/-(\d*)x(\d*)\.(.*)$/i', $src, $matches );

		$width_index_group = 1;
		$height_index_group = 2;

		if (
			empty( $matches[ $width_index_group ] ) ||
			empty( $matches[ $height_index_group ] )
		) {
			return false;
		}

		return array(
			'width' => (int) $matches[ $width_index_group ],
			'height' => (int) $matches[ $height_index_group ],
		);
	}

	/**
	 * Get size name from src.
	 *
	 * @since 1.0.0
	 *
	 * @param string $src Image src.
	 *
	 * @return mixed Size name.
	 */
	public static function get_size_name_from_src( $src ) {
		$size = self::get_proportion_from_src( $src );

		if (
			empty( $size['width'] ) ||
			empty( $size['height'] )
		) {
			if ( ! self::is_src_by_bfi_thumb( $src ) ) {
				return 'full';
			}

			return false;
		}

		$width = (int) $size['width'];
		$height = (int) $size['height'];

		foreach ( self::$image_sizes as $size_name => $size_param ) {
			if (
				(int) $size_param['width'] !== $width ||
				(int) $size_param['height'] !== $height
			) {
				continue;
			}

			return $size_name;
		}
	}

	/**
	 * Filter for wp_get_attachment_image_attributes, change image attributes.
	 *
	 * @since 1.0.0
	 * @since 1.11.6 Fixed lazyload image sizes.
	 *
	 * @param array $attr Attributes.
	 * @param mixed $attachment Current image (WP_Post).
	 * @param mixed $size Image size.
	 *
	 * @return array Filtered image sizes.
	 */
	public function add_image_placeholders( $attr, $attachment, $size ) {
		if ( ! self::is_enabled( $attr ) ) {
			return $attr;
		}

		if ( ! $size ) {
			$size = self::get_size_name_from_src( $attr['src'] );
			$proportion = self::get_proportion_from_src( $attr['src'] );

			if ( $proportion ) {
				if ( ! isset( $attr['width'] ) ) {
					$attr['width'] = $proportion['width'];
				}

				if ( ! isset( $attr['height'] ) ) {
					$attr['height'] = $proportion['height'];
				}
			}
		}

		// Init class of image.
		if ( ! isset( $attr['class'] ) ) {
			$attr['class'] = null;
		}

		// Init src of image.
		if ( ! isset( $attr['src'] ) ) {
			$attr['src'] = null;
		}

		$placeholder = self::$placeholder;

		// Is string.
		if ( is_string( $size ) ) {
			if ( isset( $attachment->ID ) ) {
				$attachment_id = $attachment->ID;
			} elseif ( isset( $attachment['ID'] ) ) {
				$attachment_id = $attachment['ID'];
			} else {
				$attachment_id = null;
			}

			$metadata = get_post_meta( $attachment_id, '_wp_attachment_metadata', true );

			if ( '' !== $metadata ) {
				if ( 'full' === $size || ! isset( $metadata['sizes'][ $size ] ) ) {
					$metadata_size = $metadata;
				} else {
					$metadata_size = $metadata['sizes'][ $size ];
				}

				if ( isset( $metadata_size['placeholder'] ) ) {
					$placeholder = $metadata_size['placeholder'];
				} elseif ( isset( $metadata['placeholder'] ) ) {
					$placeholder = $metadata['placeholder'];
				}

				// if ( ! isset( $attr['width'] ) ) {
				// 	$attr['width'] = $metadata_size['width'];
				// }

				// if ( ! isset( $attr['height'] ) ) {
				// 	$attr['height'] = $metadata_size['height'];
				// }
			}

			$lazyload_size = $this->get_lazyload_slug( $size );
			$placeholder_image = wp_get_attachment_image_url( $attachment_id, $lazyload_size );

			if ( preg_match( '/-\d*x\d*\.\w*$/', $placeholder_image ) ) {
				$placeholder = $placeholder_image;
			}
		}

		// Lazy Sizes.
		$attr['class'] .= ' cmsmasters_img cmsmasters-lazyload lazyload';

		// Set data-sizes.
		if ( isset( $attr['sizes'] ) ) {
			$attr['data-sizes'] = $attr['sizes'];

			unset( $attr['sizes'] );
		} else {
			$attr['data-sizes'] = 'auto';
		}

		// Set data-src.
		if ( ! isset( $attr['data-src'] ) ) {
			$attr['data-src'] = $attr['src'];
		}

		if ( isset( $attr['srcset'] ) ) {
			$attr['data-srcset'] = $attr['srcset'];

			unset( $attr['srcset'] );
		}

		$attr['src'] = $placeholder;

		return $attr;
	}

	/**
	 * Check if is enabled lazyload.
	 *
	 * @since 1.0.0
	 * @since 1.6.0 Fixed lazyload for svg images.
	 * @since 1.7.1 Fixed lazyload for gif images.
	 *
	 * @param array $attr Attributes.
	 *
	 * @return bool Checked result.
	 */
	public static function is_enabled( $attr = array() ) {
		if (
			(
				is_admin() &&
				! self::has_elementor_in_page()
			) ||
			is_feed() ||
			get_query_var( 'print' ) ||
			get_query_var( 'printpage' )
		) {
			return false;
		}

		// Is image disabled ?
		if (
			(
				isset( $attr['class'] ) &&
				preg_match( '/cmsmasters-lazyload-disabled/', $attr['class'] )
			) ||
			preg_match( '/\.svg$/', $attr['src'] ) ||
			preg_match( '/\.gif$/', $attr['src'] )
		) {
			return false;
		}

		return true;
	}

	/**
	 * Get lazyload slug.
	 *
	 * @param array $size Registered size or full size.
	 *
	 * @return string Placeholder image slug.
	 */
	public function get_lazyload_slug( $size ) {
		$lazyload_slug = self::IMAGE_SIZE_NAME_PREFIX . '-full';

		$data = isset( self::$image_sizes[ $size ] ) ? self::$image_sizes[ $size ] : false;

		if ( isset( $data['width'] ) && isset( $data['height'] ) ) {
			$crop = null;

			if ( isset( $data['crop'] ) ) {
				if ( is_array( $data['crop'] ) ) {
					$crop = '-' . implode( '-', $data['crop'] );
				}

				if ( is_bool( $data['crop'] ) && $data['crop'] ) {
					$crop = '-crop';
				}
			}

			$divider = $data['width'] / $data['height'];

			$lazyload_slug = sprintf( self::IMAGE_SIZE_NAME_PREFIX . '-%s%s', round( $divider, 2 ), $crop );
		}

		return $lazyload_slug;
	}

	/**
	 * Get attachment attributes to object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $attr Attributes.
	 *
	 * @return array attachment attributes.
	 */
	public static function attachment_attr_to_object( $attr ) {
		if ( ! isset( $attr['src'] ) ) {
			return;
		}

		// Set ID by class.
		if ( isset( $attr['class'] ) && preg_match( '/wp-image-(\d*)/i', $attr['class'], $match ) ) {
			return array(
				'ID' => $match[1],
			);
		}

		// Remove the thumbnail size.
		$src = preg_replace( '~-[0-9]+x[0-9]+(?=\..{2,6})~', '', $attr['src'] );

		// Set ID by src.
		return array(
			'ID' => attachment_url_to_postid( $src ),
		);
	}

	/**
	 * Get attachment attributes to size.
	 *
	 * @since 1.0.0
	 * @since 1.3.2 Fixed lazyload image size.
	 *
	 * @param array $attr Attributes.
	 * @param string $attachment_id Attachment ID.
	 *
	 * @return array attachment attributes.
	 */
	public static function attachment_attr_to_size( $attr, $attachment_id = null ) {
		// Set ID by class.
		if ( isset( $attr['class'] ) ) {
			if ( preg_match( '/size-(\S*)/i', $attr['class'], $match ) ) {
				return $match[1];
			}
		}

		if ( $attachment_id ) {
			if ( isset( $attr['width'] ) && isset( $attr['height'] ) ) {
				$width = $attr['width'];
				$height = $attr['height'];
			} else {
				$proportion = self::get_proportion_from_src( $attr['src'] );

				if ( $proportion ) {
					$width = $proportion['width'];
					$height = $proportion['height'];
				} elseif ( $attachment_id ) {
					return 'full';
				}
			}

			if ( $width && $height ) {
				$metadata = get_post_meta( $attachment_id, '_wp_attachment_metadata', true );

				if ( ! is_array( $metadata ) ) {
					return 'full';
				}

				if ( $width === $metadata['width'] && $height === $metadata['height'] ) {
					return 'full';
				} else {
					if ( ! isset( $metadata['sizes'] ) || ! is_array( $metadata['sizes'] ) ) {
						return 'full';
					}

					foreach ( $metadata['sizes'] as $size_name => $size ) {
						if ( $width === $size['width'] && $height === $size['height'] ) {
							return $size_name;
						}
					}
				}
			}
		}
	}

	/**
	 * Get attributes from HTML.
	 *
	 * @since 1.0.0
	 *
	 * @param string $html HTML.
	 *
	 * @return array attributes.
	 */
	public static function get_array_attr_from_html( $html ) {
		preg_match_all( '/\s(.*?)="(.*?)"/', $html, $matches );

		$attr_data = array_shift( $matches );

		$attr = array();

		foreach ( $attr_data as $key => $full_data ) {
			$name = $matches[0][ $key ];
			$value = $matches[1][ $key ];

			$attr[ $name ] = $value;
		}

		return $attr;
	}

	/**
	 * Filter custom size attachment for Elementor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $html HTML.
	 * @param array $settings Settings.
	 * @param string $image_size_key Image size key.
	 * @param string $image_key Image key.
	 *
	 * @return string Image HTML.
	 */
	public function elementor_custom_size_attachment( $html, $settings, $image_size_key, $image_key ) {
		if ( ! function_exists( 'bfi_thumb' ) ) {
			return $html;
		}

		$attachment_id = isset( $settings[ $image_key ]['id'] ) ? $settings[ $image_key ]['id'] : false;

		if ( ! isset( $settings[ $image_size_key . '_custom_dimension' ] ) ) {
			return $html;
		}

		$custom_dimension = $settings[ $image_size_key . '_custom_dimension' ];

		if (
			! $attachment_id ||
			'custom' !== $settings[ $image_size_key . '_size' ] ||
			empty( $custom_dimension['width'] ) ||
			empty( $custom_dimension['height'] )
		) {
			return $html;
		}

		$attr = self::get_array_attr_from_html( $html );

		if (
			! self::is_enabled( $attr ) ||
			! self::is_src_by_bfi_thumb( $attr['src'] )
		) {
			return $html;
		}

		$bfi_thumb_lazyload_params = array(
			'width' => self::$lazyload_width,
			'crop' => true,
			//Height in Proportion
			'height' => self::$lazyload_width * (int) $custom_dimension['height'] / (int) $custom_dimension['width'],
		);

		$attr['width'] = $custom_dimension['width'];
		$attr['height'] = $custom_dimension['height'];
		$attr['class'] = 'cmsmasters_img cmsmasters-lazyload lazyload';
		$attr['data-src'] = $attr['src'];
		$attr['src'] = bfi_thumb( wp_get_attachment_url( $attachment_id ), $bfi_thumb_lazyload_params );

		return '<img ' . $this->get_render_attr( $attr ) . '>';
	}

	/**
	 * Check if src instance of BFI_THUMB.
	 *
	 * @since 1.0.0
	 *
	 * @param string $src Image src.
	 *
	 * @return mixed Check result.
	 */
	public static function is_src_by_bfi_thumb( $src ) {
		if ( ! function_exists( 'bfi_thumb' ) ) {
			return false;
		}

		return preg_match( '/' . preg_quote( BFITHUMB_UPLOAD_DIR, '/' ) . '/', $src );
	}

	/**
	 * Filter images in content.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content Content.
	 *
	 * @return string Filtered content.
	 */
	public function content_process_images( $content ) {
		// Get all images.
		preg_match_all( '/<img\s+.*?>/', $content, $matches );

		$images = array_shift( $matches );

		// Check exists images.
		if ( ! $images ) {
			return $content;
		}

		foreach ( $images as $image ) {
			// Ignore init lazyload.
			if ( preg_match( '/cmsmasters-lazyload/', $image ) ) {
				continue;
			}

			// Get Attributes for the image markup.
			$attr = self::get_array_attr_from_html( $image );

			if ( empty( $attr ) ) {
				continue;
			}

			/* Process image */
			$attachment = self::attachment_attr_to_object( $attr );

			if ( empty( $attachment['ID'] ) ) {
				continue;
			}

			$size = self::attachment_attr_to_size( $attr, $attachment['ID'] );

			$attr = $this->add_image_placeholders( $attr, $attachment, $size );

			// Variables for new image.
			$new_image = '<img [attr]>';

			// Create new image based on new attributes.
			$new_image = str_replace( '[attr]', $this->get_render_attr( $attr ), $new_image );

			// Update content.
			$content = str_replace( $image, $new_image, $content );
		}

		return $content;
	}

	/**
	 * Render attrs.
	 *
	 * @since 1.0.0
	 * @since 1.0.1 Fixed PHP 5.6 support.
	 *
	 * @param array $attr Attributes.
	 *
	 * @return string Rendered attributes.
	 */
	public function get_render_attr( $attr ) {
		return implode(
			' ',
			array_map(
				function( $key, $value ) {
					if ( true === $value ) {
						$value = 'true';
					} elseif ( false === $value ) {
						$value = 'false';
					}

					if ( is_array( $value ) ) {
						$value = implode( ' ', array_filter( $value, function( $value ) {
							return ! ! $value;
						} ) );
					}

					switch ( $key ) {
						case 'href':
							$value = esc_url( $value );

							break;
						default:
							$value = esc_attr( $value );
					}

					return $key . '="' . $value . '"';
				},
				array_keys( $attr ),
				$attr
			)
		);
	}

	/**
	 * Check if elementor is loaded.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Checked result.
	 */
	public static function elementor_loaded() {
		return did_action( 'elementor/loaded' );
	}

	/**
	 * Check if has elementor in page.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Checked result.
	 */
	public static function has_elementor_in_page() {
		if ( ! self::elementor_loaded() ) {
			return false;
		}

		$document = Plugin::elementor()->documents->get_current();

		if ( ! $document ) {
			return false;
		}

		return $document->is_built_with_elementor();
	}

}
