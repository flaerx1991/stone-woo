<?php
namespace StereoBankSpace\Admin\Options\Pages;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Image_Sizes handler class is responsible for different methods on image-sizes theme options page.
 */
class Image_Sizes extends Base\Base_Page {

	/**
	 * Get page title.
	 */
	public static function get_page_title() {
		return esc_attr__( 'Image Sizes', 'stereo-bank' );
	}

	/**
	 * Get menu title.
	 */
	public static function get_menu_title() {
		return esc_attr__( 'Image Sizes', 'stereo-bank' );
	}

	/**
	 * Get sections.
	 */
	public function get_sections() {
		return array(
			'main' => array(
				'label' => '',
				'title' => '',
			),
		);
	}

	/**
	 * Get fields.
	 *
	 * @param string $section Current section.
	 *
	 * @return array Fields.
	 */
	public function get_fields( $section = '' ) {
		$fields = array();

		switch ( $section ) {
			case 'main':
				$image_sizes_items = array(
					'width' => array(
						'label' => esc_html__( 'Width', 'stereo-bank' ),
						'type' => 'number',
						'subtype' => 'email',
						'not_empty' => true,
						'min' => '1',
						'max' => '9999',
						'step' => '1',
						'postfix' => 'px',
					),
					'height' => array(
						'label' => esc_html__( 'Height', 'stereo-bank' ),
						'type' => 'number',
						'not_empty' => true,
						'min' => '1',
						'max' => '9999',
						'step' => '1',
						'postfix' => 'px',
					),
					'crop' => array(
						'label' => esc_html__( 'Crop', 'stereo-bank' ),
						'type' => 'checkbox',
					),
				);

				$fields['image_sizes|archive'] = array(
					'title' => esc_html__( 'Archive Image Size', 'stereo-bank' ),
					'desc' => esc_html__( 'Used for the featured image in blog/archive template.', 'stereo-bank' ),
					'type' => 'constructor',
					'items' => $image_sizes_items,
				);

				$fields['image_sizes|search'] = array(
					'title' => esc_html__( 'Search Image Size', 'stereo-bank' ),
					'desc' => esc_html__( 'Used for the featured image in search template.', 'stereo-bank' ),
					'type' => 'constructor',
					'items' => $image_sizes_items,
				);

				$fields['image_sizes|single'] = array(
					'title' => esc_html__( 'Single Image Size', 'stereo-bank' ),
					'desc' => esc_html__( 'Used for the featured image in single post template.', 'stereo-bank' ),
					'type' => 'constructor',
					'items' => $image_sizes_items,
				);

				$fields['image_sizes|more-posts'] = array(
					'title' => esc_html__( 'More Posts Image Size', 'stereo-bank' ),
					'desc' => esc_html__( 'Used for the featured image in more posts block.', 'stereo-bank' ),
					'type' => 'constructor',
					'items' => $image_sizes_items,
				);

				break;
		}

		return $fields;
	}

}
