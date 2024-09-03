<?php
function check_role($product_id) {
	$allow = false;
		
	$product_tags = wp_get_object_terms($product_id, 'product_tag', array('fields' => 'slugs'));
	if($product_tags && in_array('outlet', $product_tags)) {
		$allow = true;
	}
	else {
		$user = wp_get_current_user();	
		if(is_user_logged_in() && !in_array( 'customer', $user->roles )) $allow  = true;
	}
			
	return $allow;
}


/********RENDER POSTS********/
function render_post_inner( $post_id, $post_type, $popup = true ) {

	/*if ( 'post' === $post_type ) {
		$this->render_post_thumbnail();

		echo '<div class="elementor-widget-cmsmasters-theme-blog__post-title_wrap">';

		$this->render_post_title();

		$this->render_post_meta( $post_id );

		echo '</div>';

		$this->render_post_excerpt();

		$this->render_post_button();
	}*/

	/*if ( 'projects' === $post_type ) {
		echo '<div class="elementor-widget-cmsmasters-theme-blog__post-thumbnail_wrap">';

		$this->render_post_thumbnail();

		$this->render_post_button();

		echo '</div>';

		$this->render_post_title();

		$this->render_project_footer( $post_id );
	}*/

	/*if ( 'collections' === $post_type ) {
		$this->render_collection_thumbnail( $post_id );

		$this->render_post_title();

		$this->render_collection_footer( $post_id );
	}*/

	if ( 'product' === $post_type ) {
		$product = new WC_Product($post_id);

		//$product_outlet = ( isset( $settings['header_filter_product_outlet'] ) ? $settings['header_filter_product_outlet'] : '' );
		//$user = wp_get_current_user();
		//$not_customer_role = ( is_user_logged_in() && ! in_array( 'customer', $user->roles ) );

		if ( $popup ) {
			stone_add_to_quote_popup( $product, $post_id);
		}

		echo '<div class="elementor-widget-cmsmasters-theme-blog__cont">';
			render_product_thumbnail( $post_id, $product, true );

			render_product_footer( $post_id );

			render_post_title($post_id);

			$product_uom = wc_get_product_terms( $post_id, 'product_uom', array( 'fields' => 'names' ) );
			$uoms = ( $product_uom ? strtolower( implode( ', ', $product_uom ) ) : '' );

			if ( check_role($post_id) ) {
				render_product_price( $product, $uoms );
			}

			render_post_excerpt($post_id);

			render_post_availability( $post_id, $uoms );
		echo '</div>';
	}
}


function render_product_thumbnail( $product_id, $product, $large = true ) {
	// if ( ! has_post_thumbnail() ) {
	// 	return;
	// }

	echo '<div class="elementor-widget-cmsmasters-theme-blog__post-thumbnail">';

		if ( $large ) {
			render_post_acf_category( 'product_badges', 'div', 'product_badges', '', $product_id, false, true );

			wpclever_smart_wishlist_render( $product_id, $product );
		}

		echo '<a href="' . esc_attr( get_permalink($product_id) ) . '" class="elementor-widget-cmsmasters-theme-blog__post-thumbnail-inner">';

			$size = ( $large ? 'medium_large' : 'thumbnail_popup' );

			echo wp_get_attachment_image(get_post_thumbnail_id( $product_id ), $size);

			if ( get_field( 'product_image_overlay', $product_id ) ) {
				$image_overlay = get_field( 'product_image_overlay', $product_id );
				$image_class = " product_image_overlay attachment-medium_large size-medium_large wp-image-{medium_large['id']}";
				$image_attr = array( 'class' => trim( $image_class ) );

				echo wp_get_attachment_image( $image_overlay, 'medium_large', false, $image_attr );
			}

		echo '</a>';

		$price = product_price( $product );
	
		if ( $large ) {
			if ( $price && check_role($product_id) ) {
				echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-button cart">' .
					'<span class="elementor-widget-cmsmasters-theme-blog__post-add-to-button-trigger">' .
						'<span class="add-to-button-text">' .
							esc_html__( 'Add to Cart', 'cmsmasters-elementor' ) .
						'</span>';

						/*Icons_Manager::render_icon( array(
							'value' => 'themeicon- theme-icon-plus',
							'library' => 'themeicon-',
						), array( 'class' => 'add-to-button-icon' ) );*/
						echo '<i class="theme-icon-plus themeicon- add-to-button-icon"></i>';

					echo '</span>' .
				'</div>';
			} else {
				echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-button quote">' .
					'<span class="elementor-widget-cmsmasters-theme-blog__post-add-to-button-trigger">' .
						'<span class="add-to-button-text">' .
							esc_html__( 'Add to Quote', 'cmsmasters-elementor' ) .
						'</span>';

						/*Icons_Manager::render_icon( array(
							'value' => 'themeicon- theme-icon-plus',
							'library' => 'themeicon-',
						), array( 'class' => 'add-to-button-icon' ) );*/
						echo '<i class="theme-icon-plus themeicon- add-to-button-icon"></i>';

					echo '</span>' .
				'</div>';
			}
		}

	echo '</div>';
}
function render_wishlist_thumbnail( $product_id, $product, $key, $large = true ) {
	// if ( ! has_post_thumbnail() ) {
	// 	return;
	// }
		
	echo '<div class="elementor-widget-cmsmasters-theme-blog__post-thumbnail">';

		if ( $large ) {
			render_post_acf_category( 'product_badges', 'div', 'product_badges', '', $product_id, false, true );

			custom_wishlist_render( $product_id, $product, $key );
		}

		echo '<a href="' . esc_attr( get_permalink($product_id) ) . '" class="elementor-widget-cmsmasters-theme-blog__post-thumbnail-inner">';

			$size = ( $large ? 'medium_large' : 'thumbnail_popup' );

			echo wp_get_attachment_image(get_post_thumbnail_id( $product_id ), $size);

			if ( get_field( 'product_image_overlay', $product_id ) ) {
				$image_overlay = get_field( 'product_image_overlay', $product_id );
				$image_class = " product_image_overlay attachment-medium_large size-medium_large wp-image-{medium_large['id']}";
				$image_attr = array( 'class' => trim( $image_class ) );

				echo wp_get_attachment_image( $image_overlay, 'medium_large', false, $image_attr );
			}

		echo '</a>';

		$price = product_price( $product );
	
		if ( $large ) {
			if ( $price && check_role($product_id) ) {
				echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-button cart">' .
					'<span class="elementor-widget-cmsmasters-theme-blog__post-add-to-button-trigger">' .
						'<span class="add-to-button-text">' .
							esc_html__( 'Add to Cart', 'cmsmasters-elementor' ) .
						'</span>';

						/*Icons_Manager::render_icon( array(
							'value' => 'themeicon- theme-icon-plus',
							'library' => 'themeicon-',
						), array( 'class' => 'add-to-button-icon' ) );*/
						echo '<i class="theme-icon-plus themeicon- add-to-button-icon"></i>';

					echo '</span>' .
				'</div>';
			} else {
				echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-button quote">' .
					'<span class="elementor-widget-cmsmasters-theme-blog__post-add-to-button-trigger">' .
						'<span class="add-to-button-text">' .
							esc_html__( 'Add to Quote', 'cmsmasters-elementor' ) .
						'</span>';

						/*Icons_Manager::render_icon( array(
							'value' => 'themeicon- theme-icon-plus',
							'library' => 'themeicon-',
						), array( 'class' => 'add-to-button-icon' ) );*/
						echo '<i class="theme-icon-plus themeicon- add-to-button-icon"></i>';

					echo '</span>' .
				'</div>';
			}
		}

	echo '</div>';
}
function render_post_acf_category( $taxonomy, $tag = 'div', $class, $label = false, $post_id, $comma = false, $has_additional = false ) {
	if ( empty( $taxonomy ) ) {
		return;
	}

	$terms = wp_get_post_terms( $post_id, $taxonomy );
	$additional = ( false !== product_additional_fields($post_id) ? product_additional_fields($post_id) : '' );

	if ( ( ! empty( $terms ) && ! is_wp_error( $terms ) ) || ( $has_additional && ! empty ( $additional ) ) ) {
		echo '<' . tag_escape( $tag ) . ' class="elementor-widget-cmsmasters-theme-blog__post-meta-acf ' . $class . '">';

			if ( ! empty( $label ) ) {
				echo '<span class="elementor-widget-cmsmasters-theme-blog__post-meta-label ' . $class . '">' .
					esc_html__( $label, 'cmsmasters-elementor' ) .
				'</span>';
			}

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				$count = count( $terms );
				$i = 1;

				foreach ( $terms as $term ) {
					echo '<a href="' . esc_url( get_term_link( $term ) ) . '" class="elementor-widget-cmsmasters-theme-blog__post-meta-value ' . $class . ' ' . strtolower( str_replace( array( ' ', '-' ), '_', $term->name ) ) . '">' .
						$term->name .
					'</a>';

					if ( $i < $count && $comma ) {
						echo ', ';
					}

					$i++;
				}
			}

			if ( $has_additional && ! empty ( $additional ) ) {
				echo '<a href="#" class="elementor-widget-cmsmasters-theme-blog__post-meta-value product_badges ' . strtolower( str_replace( array( ' ', '-' ), '_', product_additional_fields($post_id) ) ) . '">' .
					product_additional_fields($post_id) .
				'</a>';
			}

		echo '</' . tag_escape( '/' . $tag ) . '>';
	}
}

function product_additional_fields($post_id, $blog_post_type = 'product') {
	$additional = false;

	if ( 'product' === $blog_post_type ) {
		$product = new WC_Product($post_id);

		// if ( $product->is_on_sale() ) {
		// 	echo '<a href="#" class="elementor-widget-cmsmasters-theme-blog__post-meta-value product_badges is_on_sale">Sale</a>';
		// }

		// echo '<a href="#" class="elementor-widget-cmsmasters-theme-blog__post-meta-value product_badges is_on_sale">Out of Stock</a>';
		// $product->is_in_stock() ? 'In Stock' : ( $product->is_on_sale() ? 'On Sale' : 'Out of Stock' );
		if ( $product->is_on_sale() ) {
			$additional = 'Sale';
		} elseif ( ! $product->is_in_stock() ) {
			$additional .= 'Special Order';
			// } else {
			// 	echo '<a href="#" class="elementor-widget-cmsmasters-theme-blog__post-meta-value product_badges is_on_sale">In Stock</a>';
		}
	}

	return $additional;
}

function wpclever_smart_wishlist_render( $product_id, $product ) {
	if ( ! class_exists( 'WPCleverWoosw' ) ) {
		return;
	}

	$woosw = new \WPCleverWoosw;

	$attrs = array(
		'id'   => $product_id,
		'type' => $woosw::get_setting( 'button_type', 'button' ),
	);

	add_filter( 'woosw_button_html', function ( $output ) use ( $product ) {
		return wishlist_button_html( $output, $product );
	}, 11, 1 );

	$shortcode = "[woosw 
				id=\"{$attrs['id']}\" 
				type=\"{$attrs['type']}\"]";

	$shortcode = do_shortcode( shortcode_unautop( $shortcode ) );


	echo '<div class="elementor-widget-cmsmasters-theme-blog__post-wishlist elementor-widget-cmsmasters-theme-blog__post-wishlist">'.$shortcode.'</div>';
	

}
function custom_wishlist_render( $product_id, $product, $key ) {
	if ( ! class_exists( 'WPCleverWoosw' ) ) {
		return;
	}
	
	$woosw = new \WPCleverWoosw;

	$attrs = array(
		'id'   => $product_id,
		'type' => $woosw::get_setting( 'button_type', 'button' ),
	);

	add_filter( 'woosw_button_html', function ( $output ) use ( $product, $key ) {
		return custom_wishlist_button_html( $output, $product, $key );
	}, 11, 1 );

	$shortcode = "[woosw 
				id=\"{$attrs['id']}\" 
				type=\"{$attrs['type']}\"
				key=\"{$key}\" ]";

	$shortcode = do_shortcode( shortcode_unautop( $shortcode ) );


	echo '<div class="elementor-widget-cmsmasters-theme-blog__post-wishlist elementor-widget-cmsmasters-theme-blog__post-wishlist">'.$shortcode.'</div>';
	

}

function wishlist_button_html( $output, $product ) {
	$attrs = array();

	if ( is_object( $product ) ) {
		$product_image_id = $product->get_image_id();
		$attrs['product_id'] = $product->get_id();
		$attrs['product_name'] = $product->get_name();
		$attrs['product_image'] = wp_get_attachment_image_url( $product_image_id );
	} else {
		$product_image_id = '1';
		$attrs['product_id'] = '1';
		$attrs['product_name'] = 'name';
		$attrs['product_image'] = '#';
	}

	$product_id = $attrs['product_id'];
	$product_name = $attrs['product_name'];
	$product_image = $attrs['product_image'];

	$output = '<a class="woosw-btn woosw-btn-'.$product_id.' elementor-widget-cmsmasters-theme-blog__post-wishlist__general woosw-btn-has-icon elementor-widget-cmsmasters-theme-blog__post-wishlist-button" data-id="'.$product_id.'" data-product_name="'.$product_name.'" data-product_image="'.$product_image.'" href="?add-to-wishlist='.$product_id.'">';
		$output .= '<div class="elementor-widget-cmsmasters-theme-blog__post-wishlist-button-icon-wrapper elementor-widget-cmsmasters-theme-blog__post-wishlist-button-icon-wrapper elementor-widget-cmsmasters-theme-blog__post-wishlist-button-icon-wrapper">';
			$output .= '<span class="elementor-widget-cmsmasters-theme-blog__post-wishlist-button-icon elementor-widget-cmsmasters-theme-blog__post-wishlist-button-normal elementor-widget-cmsmasters-theme-blog__post-wishlist-button-icon elementor-widget-cmsmasters-theme-blog__post-wishlist-button-normal elementor-widget-cmsmasters-theme-blog__post-wishlist-button-icon elementor-widget-cmsmasters-theme-blog__post-wishlist-button-normal">';
				$output .= '<i class="themeicon- theme-icon-heart-empty"></i>';
			$output .= '</span>';
			$output .= '<span class="elementor-widget-cmsmasters-theme-blog__post-wishlist-button-icon elementor-widget-cmsmasters-theme-blog__post-wishlist-button-active elementor-widget-cmsmasters-theme-blog__post-wishlist-button-icon elementor-widget-cmsmasters-theme-blog__post-wishlist-button-active elementor-widget-cmsmasters-theme-blog__post-wishlist-button-icon elementor-widget-cmsmasters-theme-blog__post-wishlist-button-active">';
				$output .= '<i class="themeicon- theme-icon-heart-full"></i>';
			$output .= '</span>';
		$output .= '</div>';
	$output .= '</a>';

	return $output;
}
function custom_wishlist_button_html( $output, $product, $key ) {
	$attrs = array();
	
	if ( is_object( $product ) ) {
		$product_image_id = $product->get_image_id();
		$attrs['product_id'] = $product->get_id();
		$attrs['product_name'] = $product->get_name();
		$attrs['product_image'] = wp_get_attachment_image_url( $product_image_id );
	} else {
		$product_image_id = '1';
		$attrs['product_id'] = '1';
		$attrs['product_name'] = 'name';
		$attrs['product_image'] = '#';
	}

	$product_id = $attrs['product_id'];
	$product_name = $attrs['product_name'];
	$product_image = $attrs['product_image'];

	$output = '<a class="woosw-btn-'.$product_id.' elementor-widget-cmsmasters-theme-blog__post-wishlist__general woosw-btn-has-icon elementor-widget-cmsmasters-theme-blog__post-wishlist-button woosw-item--wishlist-remove" data-id="'.$product_id.'" data-key="'.$key.'" data-product_name="'.$product_name.'" data-product_image="'.$product_image.'" href="?add-to-wishlist='.$product_id.'">';
		$output .= '<div class="elementor-widget-cmsmasters-theme-blog__post-wishlist-button-icon-wrapper elementor-widget-cmsmasters-theme-blog__post-wishlist-button-icon-wrapper elementor-widget-cmsmasters-theme-blog__post-wishlist-button-icon-wrapper">';
			$output .= '<span class="elementor-widget-cmsmasters-theme-blog__post-wishlist-button-icon elementor-widget-cmsmasters-theme-blog__post-wishlist-button-normal elementor-widget-cmsmasters-theme-blog__post-wishlist-button-icon elementor-widget-cmsmasters-theme-blog__post-wishlist-button-normal elementor-widget-cmsmasters-theme-blog__post-wishlist-button-icon elementor-widget-cmsmasters-theme-blog__post-wishlist-button-normal">';
				$output .= '<i class="themeicon- theme-icon-heart-empty"></i>';
			$output .= '</span>';
			$output .= '<span class="elementor-widget-cmsmasters-theme-blog__post-wishlist-button-icon elementor-widget-cmsmasters-theme-blog__post-wishlist-button-active elementor-widget-cmsmasters-theme-blog__post-wishlist-button-icon elementor-widget-cmsmasters-theme-blog__post-wishlist-button-active elementor-widget-cmsmasters-theme-blog__post-wishlist-button-icon elementor-widget-cmsmasters-theme-blog__post-wishlist-button-active">';
				$output .= '<i class="themeicon- theme-icon-heart-full"></i>';
			$output .= '</span>';
		$output .= '</div>';
	$output .= '</a>';

	return $output;
}
function render_product_footer( $post_id ) {
	echo '<div class="elementor-widget-cmsmasters-theme-blog__post-footer">';

		$terms = wp_get_post_terms( $post_id, 'collection' );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			echo '<div class="elementor-widget-cmsmasters-theme-blog__post-meta-acf collection">';

				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					$count = count( $terms );
					$i = 1;

					foreach ( $terms as $term ) {
						$term_link = str_replace( '/collection/', '/collections/', get_term_link( $term ) );

						echo '<a href="' . esc_url( $term_link ) . '" class="elementor-widget-cmsmasters-theme-blog__post-meta-value collection ' . strtolower( str_replace( array( ' ', '-' ), '_', $term->name ) ) . '">' .
							$term->name .
						'</a>';

						if ( $i < $count ) {
							echo ', ';
						}

						$i++;
					}
				}

			echo '</div>';
		}

	echo '</div>';
}

function render_post_title($post_id) {
	$title = get_the_title($post_id);

	if ( ! $title ) {
		$title = '(' . esc_html__( 'No Title', 'cmsmasters-elementor' ) . ')';
	}

	echo '<h3 class="elementor-widget-cmsmasters-theme-blog__post-title">' .
		'<a href="' . get_permalink($post_id) . '">' .
			wp_kses_post( $title ) .
		'</a>' .
	'</h3>';
}

function render_product_price( $product, $uoms ) {
	$price = product_price( $product );

	if ( $price ) {
		echo '<div class="elementor-widget-cmsmasters-theme-blog__post-price">' .
			'<div class="elementor-widget-cmsmasters-theme-blog__post-price-inner">';
				//wc_get_template( '/single-product/price.php' );
				?>
				<p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>">
					<?php echo $product->get_price_html(); ?>
				</p>
				<?php
				echo '<span class="elementor-widget-cmsmasters-theme-blog__post-price-symbol">' . ( $uoms ? '/' . $uoms : '' ) . '</span>' .
			'</div>';

			get_sale_percentage( $product );

		echo '</div>';
	}
}

function get_sale_percentage( $product ) {
	$price = product_price( $product );

	if ( ! $price ) {
		return;
	}

	if ( $product->is_type( 'variable' ) ) {
		$regular_price = (int) $product->get_variation_regular_price();
	} else {
		$regular_price = (int) $product->get_regular_price();
	}

	$discount = round( ( $regular_price - $price ) / $regular_price * 100, 0 );

	$discount_text = 'save ' . (string) $discount . '%';

	$epsilon = 0.000001;

	if ( abs( $discount ) > $epsilon ) {
		echo '<div class="elementor-widget-cmsmasters-theme-blog__post-price-discount">' .
			esc_html( $discount_text ) .
		'</div>';
	}
}

function render_post_excerpt($post_id) {
	if ( ! get_the_excerpt($post_id) ) {
		return;
	}

	$has_excerpt = has_excerpt();

	if ( $has_excerpt ) {
		add_filter( 'wp_trim_excerpt', 'filter_wp_trim_excerpt' );
	} else {
		add_filter( 'excerpt_more', 'filter_excerpt_more', 20 );
		add_filter( 'excerpt_length', 'filter_excerpt_length', 20 );
	}

	echo '<div class="elementor-widget-cmsmasters-theme-blog__post-excerpt">' .
		get_the_excerpt($post_id) .
	'</div>';

	if ( $has_excerpt ) {
		remove_filter( 'wp_trim_excerpt', 'filter_wp_trim_excerpt'  );
	} else {
		remove_filter( 'excerpt_length', 'filter_excerpt_length', 20 );
		remove_filter( 'excerpt_more', 'filter_excerpt_more', 20 );
	}
}

function filter_wp_trim_excerpt( $excerpt ) {
	return wp_trim_words( $excerpt, filter_excerpt_length(), filter_excerpt_more() );
}

function filter_excerpt_length() {
	return 200;
}

function filter_excerpt_more() {
	return '...';
}


function render_post_availability( $post_id, $uoms ) {
	$availability = get_field( 'available', $post_id );
	$incoming = get_field( 'incoming', $post_id );
	$contact_us = get_field( 'contact_us_page', 'options' );

	echo '<span class="elementor-widget-cmsmasters-theme-blog__post-availability-wrap">';

	if ( $availability || $incoming ) {
		echo '<span class="elementor-widget-cmsmasters-theme-blog__post-availability">' .
			'<span class="elementor-widget-cmsmasters-theme-blog__post-availability-label">' .
				esc_html__( 'Available', 'cmsmasters-elementor' ) .
			'</span>' .
			'<span class="elementor-widget-cmsmasters-theme-blog__post-availability-count">' .
				( ! empty( $availability ) ? $availability . ' ' . $uoms : '-' ) .
			'</span>' .
		'</span>' .
		'<span class="elementor-widget-cmsmasters-theme-blog__post-incoming">' .
			'<span class="elementor-widget-cmsmasters-theme-blog__post-incoming-label">' .
				esc_html__( 'Incoming', 'cmsmasters-elementor' ) .
			'</span>' .
			'<span class="elementor-widget-cmsmasters-theme-blog__post-incoming-count">' .
				( ! empty( $incoming ) ? $incoming . ' ' . $uoms : '-' ) .
			'</span>' .
		'</span>';
	} else {
		echo '<span class="elementor-widget-cmsmasters-theme-blog__post-contact-us">' .
			'<span class="elementor-widget-cmsmasters-theme-blog__post-contact-us-label">' .
				esc_html__( 'Special Order', 'cmsmasters-elementor' ) .
			'</span>' .
			'<span class="elementor-widget-cmsmasters-theme-blog__post-contact-us-count">' .
				'<a href="' . esc_url( $contact_us ) . '">' .
					esc_html__( 'Contact Us', 'cmsmasters-elementor' ) .
				'</a>' .
			'</span>' .
		'</span>';
	}

	echo '</span>';
}


/********PRODUCTS POPUP********/
//new
function stone_add_to_quote_popup( $product, $product_id ) {
	if ( ! defined( 'YITH_YWRAQ_FREE_INIT' ) && ! defined( 'YITH_YWRAQ_PREMIUM' ) ) {
		return;
	}

	$product_sizes = wc_get_product_terms( $product_id, 'product_size', array( 'fields' => 'names' ) );
	//var_dump($product_sizes);
	//die();
	$uom = wc_get_product_terms( $product_id, 'product_uom', array( 'fields' => 'names' ) );
	$uom = ( ! empty( $uom ) ? $uom[0] : 'each' );

	$width = get_field('product_width',$product_id);
	$height = get_field('product_height',$product_id);
	$uom_cn = get_field('product_uom_cn',$product_id);

	echo '<div id="post-' . $product_id . '" class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup">' .
			'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-inner">' .
				'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-cont">' .
					'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-collection-wrap">' .
						'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-collection">' .
							'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-collection-inner">';
								get_popup_collections( $product_id );
							echo '</div>';
	
							echo '<i class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-close themeicon- theme-icon-close"></i>';

							/*Icons_Manager::render_icon( array(
								'value' => 'themeicon- theme-icon-close',
								'library' => 'themeicon-',
							), array( 'class' => 'elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-close' ) );*/

						echo '</div>';

						get_product_chooses( $product_id );

					echo '</div>' .
					'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote" product-uom="' . strtolower( $uom ) . '">' .
						'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-inputs">';

							// if ( ! empty( $product_sizes ) && ! empty( $uom ) && 'sqft' === strtolower( $uom ) ) {
							if ( ! empty( $product_sizes ) && ! empty( $uom ) ) {
								get_calculator( 'sqft', $product_sizes, $width, $height, $uom_cn);
							}

							get_calculator( 'each', $product_sizes, $width, $height, $uom_cn);

						echo '</div>';

						$price = product_price( $product );

						if ( $price && check_role($product_id) ) {
							add_to_cart( $product, $product_id );
						} else {
							get_quote_button( $product_id );
						}

				echo '</div>' .
			'</div>' .
		'</div>' .
	'</div>';
}
//new
function get_popup_collections( $current_ID ) {
	$product_types = wc_get_product_terms( $current_ID, 'theme_product_type', array( 'fields' => 'all' ) );
	$collections = wc_get_product_terms( $current_ID, 'collection', array( 'fields' => 'all' ) );

	$this_type_ids = array();
	$this_collection_id = array();

	foreach ( $product_types as $type ) {
		$this_type_ids = $type->term_id;
	}

	foreach ( $collections as $collection ) {
		$this_collection_id = $collection->term_id;
	}

	$args = array(
		'post_type' => 'product',
		'posts_per_page' => -1,
		'tax_query' => array(
			array(
				'taxonomy' => 'collection',
				'field' => 'term_id',
				'terms' => $this_collection_id,
			),
			array(
				'taxonomy' => 'theme_product_type',
				'field' => 'term_id',
				'terms' => $this_type_ids,
			),
		),
		'orderby' => 'meta_value_num',
		'order' => 'ASC',
		'meta_key' => 'collection_order'
	);

	$products = get_posts( $args );

	if ( ! $products ) {
		return;
	}

	foreach ( $products as $product ) {
		$product_id = $product->ID;

		$product_obj = wc_get_product( $product_id );

		$product_price = $product_obj->get_price();

		if ( $product_price !== '' ) {
			$product_price = wc_price( $product_price );
		} else {
			$product_price = '';
		}

		echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-collection-inner-cont-wrap' . ( $product_id === $current_ID ? ' show' : '' ) . '" product-id="' . $product_id . '">';

			if ( has_post_thumbnail() ) {
				echo '<div class="elementor-widget-cmsmasters-theme-blog__post-thumbnail 2">' .
					'<a href="' . esc_attr( get_permalink( $product_id ) ) . '" class="elementor-widget-cmsmasters-theme-blog__post-thumbnail-inner">';

						$settings['thumbnail_popup'] = array(
							'id' => get_post_thumbnail_id( $product_id ),
						);

						//echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail_popup' );
						echo wp_get_attachment_image(get_post_thumbnail_id( $product_id ), 'medium');

					echo '</a>' .
				'</div>';
			}

			echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-collection-cont">';
				$title = get_the_title( $product_id );

				if ( ! $title ) {
					$title = '(' . esc_html__( 'No Title', 'cmsmasters-elementor' ) . ')';
				}

				$uom = wc_get_product_terms( $product_id, 'product_uom', array( 'fields' => 'names' ) );
				$uom = ( ! empty( $uom ) ? $uom[0] : 'each' );

				echo '<div class="elementor-widget-cmsmasters-theme-blog__post-title">' .
					'<a href="' . get_permalink( $product_id ) . '">' .
						wp_kses_post( $title ) .
					'</a>' .
				'</div>';

				//$product_outlet = ( isset( $settings['header_filter_product_outlet'] ) ? $settings['header_filter_product_outlet'] : '' );
				//$user = wp_get_current_user();
				//$not_customer_role = ( is_user_logged_in() && ! in_array( 'customer', $user->roles ) );

				if ( check_role($product_id) ) {
					echo $product_price . ( $product_price ? '/' . strtolower( $uom ) : '' );
				}

			echo '</div>' .
		'</div>';
	}
}


//new
function get_product_chooses( $current_ID ) {
	$product_types = wc_get_product_terms( $current_ID, 'theme_product_type', array( 'fields' => 'all' ) );
	$collections = wc_get_product_terms( $current_ID, 'collection', array( 'fields' => 'all' ) );

	$this_type_names = array();
	$this_type_ids = array();
	$this_collection_name = array();
	$this_collection_id = array();

	foreach ( $product_types as $type ) {
		$this_type_names = $type->name;
		$this_type_ids = $type->term_id;
	}

	foreach ( $collections as $collection ) {
		$this_collection_name = $collection->name;
		$this_collection_id = $collection->term_id;
	}

	$args = array(
		'post_type' => 'product',
		'posts_per_page' => -1,
		'tax_query' => array(
			array(
				'taxonomy' => 'collection',
				'field' => 'term_id',
				'terms' => $this_collection_id,
			),
			array(
				'taxonomy' => 'theme_product_type',
				'field' => 'term_id',
				'terms' => $this_type_ids,
			),
		),
		'orderby' => 'meta_value_num',
		'order' => 'ASC',
		'meta_key' => 'collection_order'
	);

	$products = get_posts( $args );
	$parent_type = array();

	if ( ! $products ) {
		return;
	}

	foreach ( $products as $product ) {
		$product_id = $product->ID;

		$product_obj = wc_get_product( $product_id );

		$product_price = $product_obj->get_price();

		if ( $product_price !== '' ) {
			$product_price = wc_price( $product_price );
		} else {
			$product_price = '-';
		}

		$sizes = wc_get_product_terms( $product_id, 'product_size', array( 'fields' => 'names' ) );
		$packs = wc_get_product_terms( $product_id, 'product_pack', array( 'fields' => 'names' ) );
		$uoms = wc_get_product_terms( $product_id, 'product_uom', array( 'fields' => 'names' ) );

		$width = get_field('product_width',$product_id);
		$height = get_field('product_height',$product_id);
		$uom_cn = get_field('product_uom_cn',$product_id);

		if (!($width && $height)){

			$regex = '/["x]+/';

			if ( ! empty( $sizes[0] ) && ctype_digit( substr( $sizes[0], 0, 1 ) ) ) {
				$sizesArray = preg_split( $regex, $sizes[0] );

				[ $width, $height ] = array_map( 'intval', $sizesArray );
			} else {
				$width = 144;
				$height = 1;
			}

		}
		

		$parent_type[$product_id] = array(
			'product_id' => $product_id,
			'product_price' => $product_price,
			'product_width' => intval( $width ),
			'product_height' => intval( $height ),
			'product_sizes' => implode( ', ', $sizes ),
			'product_packs' => implode( ', ', $packs ),
			'product_uoms' => implode( ', ', $uoms ),
			'product_uom_cn' => $uom_cn,
		);
	}

	if ( empty( $parent_type )) {
		return;
	}

	echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-wrapper">';
		$product_finishes = wc_get_product_terms( $current_ID, 'product_finishes', array( 'fields' => 'names' ) );

		if ( ! empty( $product_finishes ) ) {
			$this_product_finishes = $product_finishes[0];
		}

		echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-type_wrap">' .
			'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-type">' .
				( $this_collection_name . ' - ' . $this_product_finishes . ' - ' .  $this_type_names ) .
			'</div>';

			get_labels();

			echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-products">';

				foreach ( $parent_type as $element ) {
					stone_get_product( $element['product_id'], $this_type_names, $element['product_price'], $element['product_width'], $element['product_height'], $element['product_sizes'], $element['product_packs'], $element['product_uoms'], $element['product_uom_cn'], $current_ID);
				}

			echo '</div>' .
		'</div>' .
	'</div>';
}

/*function get_product_child_chooses( $current_ID, $this_parent_type_name, $this_chlid_type_id, $type ) {
	$product_badge = wc_get_product_terms( $current_ID, 'product_badges', array( 'fields' => 'names' ) );

	foreach ( $type as $element ) {
		$this_child_type_id = $element['product_type_id'];
		$this_child_type_name = $element['product_type'];
	}

	$product_type = $this_child_type_name . ' ' . $this_parent_type_name . ( $product_badge ? ' - ' . ucwords( implode( ', ', $product_badge ) ) : '');

	echo '<div class="elementor-widget-cmsmasters-theme-blog-grid__post-add-to-quote-popup-choose-type_wrap' . ($this_child_type_id === $this_chlid_type_id ? ' current' : '') . '">' .
		'<div class="elementor-widget-cmsmasters-theme-blog-grid__post-add-to-quote-popup-choose-type">' .
			esc_html( $product_type ) .
		'</div>';

		get_labels();

		echo '<div class="elementor-widget-cmsmasters-theme-blog-grid__post-add-to-quote-popup-choose-products">';

			foreach ( $type as $element ) {
				stone_get_product( $element['product_id'], $product_type, $element['product_price'], $element['product_width'], $element['product_height'], $element['product_sizes'], $element['product_packs'], $element['product_uoms'], $current_ID );
			}

		echo '</div>' .
	'</div>';
}*/


//new
function stone_get_product( $product_id, $this_type_names, $product_price, $product_width, $product_height, $sizes, $packs, $uoms, $uom_cn, $current_ID ) {
	$current = ( $product_id === $current_ID ? true : false );

	$product_badge = wc_get_product_terms( $product_id, 'product_badges', array( 'fields' => 'names' ) );
	$badge = ( $product_badge ? ' - ' . ucwords( implode( ', ', $product_badge ) ) : '' );
	$product_title = get_field( 'product_short_name', $product_id );

	$uom_cn_string = '';
	if ($uom_cn) $uom_cn_string = ' data-product-uom-cn="' . $uom_cn . '"';

	echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product' . ( $current ? ' current' : '' ) . '" product-id=' . $product_id . '">' .
		'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr sizes">' .
			'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr-inner">';

				$data_wp_nonce = wp_create_nonce( 'add-request-quote-' . $product_id );

				echo '<i class="product-chooses-attr-icon unchecked themeicon- theme-icon-radio_button_unchecked"></i>';
				echo '<i class="product-chooses-attr-icon checked themeicon- theme-icon-radio-button-checked"></i>';

				echo '<input class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr-checkbox" type="checkbox" id="' . $product_id . '" data-product-width="' . $product_width . '"  data-product-height="' . $product_height . '"  data-wp_nonce="' . $data_wp_nonce . '" data-uoms="' . $uoms . '" '. $uom_cn_string .' name="' . ( $sizes ? esc_html( $sizes ) : '0' ) . '" value="' . ( $sizes ? esc_html( $sizes ) : '0' ) . '">';

				echo ( ! empty( $product_title ) ? $product_title : ( ( $sizes ? esc_html( $sizes ) : '0' ) . ' ' . $this_type_names ) ) . $badge .
					'</div>' .
					'</div>' .
					'<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr packs">' .
						'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr-inner">' .
							( $packs ? esc_html( $packs ) : '0' ) .
						'</div>' .
					'</div>' .
					'<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr uoms">' .
						'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr-inner">' .
							( $uoms ? esc_html( $uoms ) : '-' ) .
						'</div>' .
					'</div>';

				//$product_outlet = ( isset( $settings['header_filter_product_outlet'] ) ? $settings['header_filter_product_outlet'] : '' );
				//$user = wp_get_current_user();
				//$not_customer_role = ( is_user_logged_in() && ! in_array( 'customer', $user->roles ) );

				echo '<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr price">' .
					'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr-inner">' .
						( ( ( check_role($product_id) ) && $product_price ) ? $product_price : '-' ) .
					'</div>' .
				'</div>' .
				'<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr availability">' .
					'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr-inner">' .
						( ! empty( get_field( 'available', $product_id ) ) ? get_field( 'available', $product_id ) : '0' ) .
					'</div>' .
				'</div>' .
				'<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr incoming">' .
					'<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-product-attr-inner">' .
						( ! empty( get_field( 'incoming', $product_id ) ) ? get_field( 'incoming', $product_id ) : '0' ) .
					'</div>' .
				'</div>' .
			'</div>';
}

//new
function get_calculator( $uom, $product_sizes, $width = false, $height = false, $uom_cn = false ) {
	if (!($width && $height)) {
	
		$sizes = implode( ', ', $product_sizes );

		if ( $sizes ) {
			$regex = '/["x]+/';

			if ( ! empty( $sizes[0] ) && ctype_digit( substr( $sizes[0], 0, 1 ) ) ) {
				$sizesArray = preg_split( $regex, $sizes );

				[ $width, $height ] = array_map( 'intval', $sizesArray );
			} else {
				$width = 144;
				$height = 1;
			}

			// list( $width, $height, $thickness ) = explode( '"x', $sizes );
		} else {
			$width = 'none';
			$height = 'none';
		}
	}
	
	$uom_cn_string = '';
	if ($uom_cn) $uom_cn_string = ' product-uom-cn="' . $uom_cn . '"';
	
	echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-input-item" product-uom="' . $uom . '" product-width="' . $width . '" product-height="' . $height . '" '. $uom_cn_string .' >';

	echo '<i class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-input-operator decrement disable themeicon- theme-icon-minus"></i>';	
	echo '<input class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-input" type="number" placeholder="QTY (' . $uom . ')" maxlength="4"></input>';	
	echo '<i class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-input-operator increment themeicon- theme-icon-plus"></i>';

	echo '</div>';
}

//new
function get_quote_button( $product_id ) {
	if ( ! $product_id ) {
		global $product, $post;

		if ( ! $product instanceof \WC_Product && $post instanceof \WP_Post ) {
			$product = wc_get_product( $post->ID );
		}
	} else {
		$product = wc_get_product( $product_id );
	}

	$quote_premium = ( defined( 'YITH_YWRAQ_PREMIUM' ) ? ' quote_premium' : '' );
	$style_button = get_option( 'ywraq_show_btn_link', 'button' ) === 'button' ? 'button' : 'ywraq-link';
	$style_button = $args['style'] ?? $style_button;
	$class = 'theme_add_to_quote_popup ' . $style_button;
	$wpnonce = wp_create_nonce( 'add-request-quote-' . $product_id );
	$label = ywraq_get_label( 'btn_link_text' );
	$label_browse = ywraq_get_label( 'browse_list' );
	$rqa_url = YITH_Request_Quote()->get_raq_page_url();
	$exists = false;

	if ( $product ) {
		$exists = $product->is_type( 'variable' ) ? false : YITH_Request_Quote()->exists( $product_id );
	}

?>
<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-button-wrap<?php echo esc_attr( $quote_premium ); ?>">
	<div class="yith-ywraq-add-button <?php echo esc_attr( ( $exists ) ? 'hide' : 'show' ); ?>" style="display:<?php echo esc_attr( ( $exists ) ? 'none' : 'block' ); ?>">
		<a href="#" class="<?php echo esc_attr( $class ); ?>" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-wp_nonce="<?php echo esc_attr( $wpnonce ); ?>" data-list_text="<?php echo wp_kses_post( $label_browse ); ?>">
			<?php echo wp_kses_post( $label ); ?>
		</a>
		<span class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-button-icon ajax-loading">
			<img src="<?php echo esc_url( ywraq_get_ajax_default_loader() ); ?>" alt="loading" width="16" height="16" />
		</span>
	</div>
	<?php if ( $exists ) : ?>
	<div class="yith_ywraq_add_item_browse-list-<?php echo esc_attr( $product_id ); ?> yith_ywraq_add_item_browse_message">
		<a href="<?php echo esc_url( $rqa_url ); ?>"><?php echo wp_kses_post( $label_browse ); ?></a>
	</div>
	<?php endif ?>
</div>
<?php } ?>

<?php
//new
function product_price( $product ) {
	if ( ! $product ) {
		return false;
	}

	$has_price = false;

	if ( $product->is_type( 'variable' ) ) {
		$price = (int) $product->get_variation_price();
	} else {
		$price = (int) $product->get_price();
	}

	if ( $price ) {
		$has_price = $price;
	}

	return $has_price;
}


//new
function add_to_cart( $product, $product_id ) {
	if ( empty( $product ) ) {
		if ( ! empty( $product_id ) ) {
			$product = wc_get_product( $product_id );
		} else {
			return;
		}
	}

	//$this->add_render_attribute( 'cmsmasters_add_to_cart', 'class', "elementor-widget-cmsmasters-theme-blog__post-add-to-cart" );
	//$this->add_render_attribute( 'cmsmasters_add_to_cart', 'class', 'cmsmasters-product-' . esc_attr( $product->get_type() ) . '' );
	$attribute_cmsmasters_add_to_cart = 'elementor-widget-cmsmasters-theme-blog__post-add-to-cart cmsmasters-product-simple elementor-widget-cmsmasters-theme-blog__post-add-to-cart cmsmasters-product-simple';

	$meta_sku = get_post_meta( $product_id, '_sku', true );
	$sku = ( $meta_sku ? $meta_sku : '' );

	echo "<div {$attribute_cmsmasters_add_to_cart}>" .
		'<form class="cart" action="' . esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ) . '" method="post" enctype="multipart/form-data">' .
			'<button type="submit" name="add-to-cart" value="' . esc_attr( $product->get_id() ) . '" class="single_add_to_cart_button button alt ' . esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) .'" data-product_id="' . $product_id . '" data-product_sku="' . $sku . '">' .
				esc_html( $product->single_add_to_cart_text() ) .
				'<span class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-button-icon ajax-loading">' .
					'<img src="' . esc_url( ywraq_get_ajax_default_loader() ) . '" alt="loading" width="16" height="16" />' .
				'</span>' .
			'</button>' .
		'</form>' .
	'</div>';
}


//new
function get_labels() {
	echo '<div class="elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-labels">' .
		'<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-label sizes">' .
			esc_html__( 'Product Name', 'cmsmasters-elementor' ) .
		'</div>' .
		'<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-label packs">' .
			esc_html__( 'Pack', 'cmsmasters-elementor' ) .
		'</div>' .
		'<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-label uom">' .
			esc_html__( 'UOM', 'cmsmasters-elementor' ) .
		'</div>' .
		'<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-label price">' .
			esc_html__( 'Price', 'cmsmasters-elementor' ) .
		'</div>' .
		'<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-label availability">' .
			esc_html__( 'Available', 'cmsmasters-elementor' ) .
		'</div>' .
		'<div class=" elementor-widget-cmsmasters-theme-blog__post-add-to-quote-popup-choose-label incoming">' .
			esc_html__( 'Incoming', 'cmsmasters-elementor' ) .
		'</div>' .
	'</div>';
}







//remove price from wishlist popup when user not registered and not have role
add_filter( 'woosw_item_price', 'stone_woosw_item_price', 10, 2 );
function stone_woosw_item_price($price_html, $product) {
	$user = wp_get_current_user();
	$not_customer_role = ( is_user_logged_in() && ! in_array( 'customer', $user->roles ) );
	if($not_customer_role) {
		$price_html = $product->get_price_html();
	}
	else $price_html = '';
	
	return $price_html;
}


//add uom to minicart
function filter_woo_minicart_quantity($output, $cart_item, $cart_item_key) {
	$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
	$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
	$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
	
	$product_uom = wp_get_post_terms($product_id, 'product_uom');
	
	return '<span class="quantity">' . sprintf( '%s<span class="uom">/%s</span> &times; %s', $cart_item['quantity'], $product_uom[0]->name, $product_price ) . '</span>';
}
add_filter('woocommerce_widget_cart_item_quantity','filter_woo_minicart_quantity',10, 3);


//generate taxonomies list for product filter ACF field in Site Options
function acf_load_products_filter_taxonomies_field_choices( $field ) {
    $field['choices'] = array();
    
	$taxonomy_names = get_object_taxonomies( 'product', 'objects' );
	
    if( is_array($taxonomy_names) ) {     
        foreach( $taxonomy_names as $taxonomy_slug => $taxonomy ) {
            $field['choices'][ $taxonomy_slug ] = $taxonomy->label; 
        }       
    }
    return $field;   
}
add_filter('acf/load_field/name=products_filter_taxonomies', 'acf_load_products_filter_taxonomies_field_choices');
