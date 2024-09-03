<?php
/**
 * Theme functions and definitions.
 */
function stereobank_child_enqueue_styles() {
    wp_enqueue_style( 'stereo-bank-child-style',
        get_stylesheet_directory_uri() . '/style.css?v='.time(),
        array(),
        wp_get_theme()->get('Version')
    );

    if(is_page_template('page-templates/sing-up.php') || is_page_template('page-templates/sing-in.php') || is_page_template('page-templates/contact-us.php') || is_page_template('page-templates/dealer.php')  || is_page_template('page-templates/sheet.php') || is_tax('project_design_build_firms') || is_singular('collections')) {
        wp_enqueue_style('custom_pages_css', get_stylesheet_directory_uri() . '/assets/additional-styles.css?v='.time());
    }

    if(is_singular('collections')) {
        wp_enqueue_script('collections_swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), false, false);
    } else {
        wp_enqueue_script('wishlist_swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), false, false);
    }

    wp_enqueue_style('cmsmasters-blog_css_new', site_url() . '/wp-content/uploads/elementor/css/cmsmasters-custom-widget-cmsmasters-blog.min.css');
    wp_enqueue_style('cmsmasters-blog_css_old', get_stylesheet_directory_uri() . '/assets/cmsmasters-theme-blog.css');

    if ( !is_user_logged_in() ) {
        wp_enqueue_style('wishlist', get_stylesheet_directory_uri() . '/assets/wishlist.css');
    }

    wp_enqueue_script('stone_custom_js', get_stylesheet_directory_uri() . '/assets/stone-custom-js.js?v='.time(), array(), false, true);
    wp_enqueue_script('stone_ajax_js', get_stylesheet_directory_uri() . '/assets/ajax.js?v='.time(), array(), false, true);
}

add_action( 'wp_enqueue_scripts', 'stereobank_child_enqueue_styles', 11 );



function create_project_collection_term( $post_id ) {
    if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
        return;
    }

    $post_type = get_post_type( $post_id );

    if ( 'collections' !== $post_type ) {
        return;
    }

    $term_name = get_the_title( $post_id );
    $term_slug = sanitize_title( $term_name );

    $post_id_prefixed = 'collection-id-' . absint( $post_id );

    $terms = get_terms( array(
        'taxonomy' => 'collection',
        'hide_empty' => false,
    ) );

    $existing_term = false;

    foreach ( $terms as $term ) {
        if ( isset( $term->description ) && $term->description === $post_id_prefixed ) {
            $existing_term = $term;

            break;
        }
    }

    if ( $existing_term ) {
        wp_update_term( $existing_term->term_id, 'collection', array( 'name' => $term_name ) );

        return;
    } else {
        $term = wp_insert_term( $term_name, 'collection', array( 'slug' => $term_slug, 'description' => $post_id_prefixed ) );
    }

    if ( ! is_wp_error( $term ) ) {
        $term_id = $term['term_id'];

        wp_set_object_terms( $post_id, $term_id, 'collection' );
    }
}

add_action( 'save_post', 'create_project_collection_term', 20 );

function hide_collection_term_form() {
    global $post_type;

    if ( in_array( $post_type, array( 'product', 'projects' ) ) ) {
        ?>
        <style type="text/css">
            #collection-adder,
            #collection-tabs .hide-if-no-js {
                display: none;
            }

            #collection-tabs .tabs {
                border-top: 0;
                border-left: 0;
                border-right: 0;
                display: block;
                margin: 0;
                padding: 0;
            }

            .taxonomy-collection #col-container #col-left {
                display: none;
            }

            .taxonomy-collection #col-container #col-right {
                float: none !important;
                width: 100% !important;
            }
        </style>
        <?php
    }
}

add_action('admin_head', 'hide_collection_term_form');

function add_user_phone( $user_contactmethods ) {
    $user_contactmethods['phone'] = 'Phone';

    return $user_contactmethods;
}

add_filter( 'user_contactmethods', 'add_user_phone' );

function save_user_phone( $user_id ) {
    if ( isset( $_POST['account_phone'] ) ) {
        update_user_meta( $user_id, 'user_phone', sanitize_text_field( $_POST['account_phone'] ) );
    }
}

add_action( 'woocommerce_save_account_details', 'save_user_phone' );




function stone_setup_theme_supports() {
    if(function_exists('acf_add_options_page')){
        acf_add_options_page(array(
            'page_title'    => 'Site Options',
            'menu_title'    => 'Site Options',
            'menu_slug'     => 'theme-general-settings',
            'capability'    => 'edit_posts',
            'redirect'      => false
        ));
    }
}
add_action('after_setup_theme', 'stone_setup_theme_supports');

function stone_acf_init() {
    acf_update_setting('google_api_key', 'AIzaSyBrAblkEW9CXOzIOCoiozu_i2ymHOwKytc');
}
add_action('acf/init', 'stone_acf_init');

function wpb_change_search_url() {
    if ( is_search() && ! empty( $_GET['s'] ) ) {
        wp_redirect( home_url( "/search/" ) . urlencode( get_query_var( 's' ) ) );
        exit();
    }
}
add_action( 'template_redirect', 'wpb_change_search_url' );

add_filter('wpcf7_autop_or_not', '__return_false');


require_once locate_template( 'inc/custom_post_types.php' );
require_once locate_template( 'inc/get_product_info.php' );
require_once locate_template( 'inc/woocommerce.php' );
require_once locate_template( 'inc/shortcodes.php' );
require_once locate_template( 'inc/ajax.php' );
require_once locate_template( 'inc/breadcrumbs.php' );
require_once locate_template( 'inc/stone_func.php' );

function request_qoute_filter_plugin_updates( $value ) {
    if( isset( $value->response['yith-woocommerce-request-a-quote/yith-woocommerce-request-a-quote.php'] ) ) {
        unset( $value->response['yith-woocommerce-request-a-quote/yith-woocommerce-request-a-quote.php'] );
    }
    return $value;
}
add_filter( 'site_transient_update_plugins', 'request_qoute_filter_plugin_updates' );


function acf_load_product_type_order_field_choices( $field ) {
    // Reset choices
    $field['choices'] = array();

    global $post;
    $collection_slug = $post->post_name;
    if(!$collection_slug) return;

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => [
            [
                'taxonomy' => 'collection',
                'field' => 'slug',
                'terms' => $collection_slug
            ]
        ],
        'fields' => 'ids'
    );

    $products = get_posts($args);
    $products_types = [];
    foreach($products as $pID) {
        $product_types = get_the_terms($pID, 'theme_product_type');


        foreach($product_types as $product_type) {
            if($product_type->parent != 0) {
                $parent_product_type = get_term_by('term_id', $product_type->parent, 'theme_product_type');
                if($parent_product_type) {
                    $products_types[$product_type->term_id] = $parent_product_type->name . ' &mdash; '.$product_type->name;
                }
                else $products_types[$product_type->term_id] = $product_type->name;
            }
            else $products_types[$product_type->term_id] = $product_type->name;


        }
    }

    asort($products_types);


    if( !empty($products_types) ) {
        foreach( $products_types as $type_term_id => $type_name ) {
            $field['choices'][ $type_term_id ] = $type_name;
        }
    }

    return $field;
}
add_filter('acf/load_field/name=product_type_order', 'acf_load_product_type_order_field_choices');

function overwrite_shortcode() {

    function new_wishlist($atts) {
        $key = isset( $_COOKIE['woosw_key'] ) ? sanitize_text_field( $_COOKIE['woosw_key'] ) : '#';
        $instance = WPCleverWoosw::instance();
        $products = array_keys($instance::get_ids( $key ));
        //var_dump($products);
        ?>
        <div class="elementor-widget-container">
			<div class="products_list stone__products_list">
				<div class="cmsmasters-blog cmsmasters-theme-blog-grid cmsmasters-blog--type-default">
					<div class="elementor-widget-cmsmasters-theme-blog__posts-variable">
						<div class="elementor-widget-cmsmasters-theme-blog__posts-wrap">
							<div class="group__products_list cmsmasters-blog cmsmasters-theme-blog-grid">
								<div class="elementor-widget-cmsmasters-theme-blog-grid cmsmasters-post-type-product cmsmasters-block-default cmsmasters-sticky-default elementor-widget elementor-widget-cmsmasters-blog-similar">
									<div class="elementor-widget-cmsmasters-theme-blog-grid__posts wish-list ">
										<?php foreach($products as $productID) : ?>
											<?php get_template_part('template-parts/content/wishlist-item', '', array('product_id' => $productID, 'key'=>$key)); ?>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						</div>	
					</div>
				</div>		
        	</div>
		</div>

        <script>
            function woosw_refresh_buttons(ids) {
                jQuery('.woosw-btn').removeClass('woosw-btn-added woosw-added');
                jQuery('.woosw-btn:not(.woosw-btn-has-icon)').html(woosw_vars.button_text);
                jQuery('.woosw-btn.woosw-btn-has-icon').
                find('.woosw-btn-icon').
                removeClass(woosw_vars.button_added_icon).
                addClass(woosw_vars.button_normal_icon);
                jQuery('.woosw-btn.woosw-btn-has-icon').
                find('.woosw-btn-text').
                html(woosw_vars.button_text);

                jQuery.each(ids, function(key, value) {
                    jQuery('.woosw-btn-' + key).addClass('woosw-btn-added woosw-added');
                    jQuery('.woosw-btn-' + key + ':not(.woosw-btn-has-icon)').
                    html(woosw_vars.button_text_added);
                    jQuery('.woosw-btn-has-icon.woosw-btn-' + key).
                    find('.woosw-btn-icon').
                    removeClass(woosw_vars.button_normal_icon).
                    addClass(woosw_vars.button_added_icon);
                    jQuery('.woosw-btn-has-icon.woosw-btn-' + key).
                    find('.woosw-btn-text').
                    html(woosw_vars.button_text_added);

                    if (value.parent !== undefined) {
                        jQuery('.woosw-btn-' + value.parent).addClass('woosw-btn-added woosw-added');
                        jQuery('.woosw-btn-' + value.parent + ':not(.woosw-btn-has-icon)').
                        html(woosw_vars.button_text_added);
                        jQuery('.woosw-btn-has-icon.woosw-btn-' + value.parent).
                        find('.woosw-btn-icon').
                        removeClass(woosw_vars.button_normal_icon).
                        addClass(woosw_vars.button_added_icon);
                        jQuery('.woosw-btn-has-icon.woosw-btn-' + value.parent).
                        find('.woosw-btn-text').
                        html(woosw_vars.button_text_added);
                    }
                });

                jQuery(document.body).trigger('woosw_buttons_refreshed', [ids]);
            }

            jQuery(document).ready(function() {
                var data = {
                    action: 'woosw_get_data', nonce: woosw_vars.nonce,
                };
                jQuery.post(woosw_vars.ajax_url, data, function(response) {
                    if (response) {

                        sessionStorage.setItem('woosw_data_' + response.key,JSON.stringify(response));

                        if (response.ids) {
                            woosw_refresh_buttons(response.ids);
                        }

                        jQuery(document.body).trigger('woosw_data_refreshed', [response]);
                    }
                });
                jQuery('.elementor-element-5c0675c').find('.woosw-btn').each(function() {
                    jQuery(this).removeClass('woosw-btn');
                    
                   
                 //  console.log(jQuery(this).find('i.theme-icon-heart-full'));
                //    console.log(jQuery(this).children('span.elementor-widget-cmsmasters-theme-blog__post-wishlist-button-normal'));
                });
                jQuery('.elementor-element-5c0675c').find('.elementor-widget-cmsmasters-theme-blog__post-wishlist-button').each(function() {
                    console.log(jQuery(this).hasClass('woosw-btn-added'));
                    if (jQuery(this).hasClass('woosw-btn-added')) jQuery(this).addClass('woosw-item--wishlist-remove');
                    else jQuery(this).addClass('woosw-item--wishlist-add');
                });
                jQuery(document).on('click touch', '.woosw-item--wishlist-add', function(e) {
                    var $this = jQuery(this);
                    var id = $this.attr('data-id');
                    var data = {
                        action: 'wishlist_add', product_id: id, nonce: woosw_vars.nonce,
                    };
                    jQuery.post(woosw_vars.ajax_url, data, function(response) {
                        $this.removeClass('woosw-adding').
                        find('.woosw-btn-icon').
                        removeClass(woosw_vars.button_loading_icon);
                        if (response.data) {
                            sessionStorage.setItem('woosw_data_' + response.data.key,
                                JSON.stringify(response.data));
                        }

                        if (response.data.ids) {
                            woosw_refresh_buttons(response.data.ids);
                        }

                        window.location.reload();
                    });
                    e.preventDefault();    
                });
                jQuery(document).on('click touch', '.woosw-item--wishlist-remove', function(e) {
                    var $this = jQuery(this);
                    var key = $this.data('key');
                    var product_id = $this.data('id');
                    var data = {
                        action: 'wishlist_remove',
                        product_id: product_id,
                        key: key,
                        nonce: woosw_vars.nonce,
                    };

                    $this.addClass('woosw-removing');

                    jQuery.post(woosw_vars.ajax_url, data, function(response) {

                        $this.removeClass('woosw-removing');

                        if (response.data) {
                            sessionStorage.setItem('woosw_data_' + response.data.key,
                                JSON.stringify(response.data));
                        }

                        if (response.data.ids) {
                            woosw_refresh_buttons(response.data.ids);
                        }

                        window.location.reload();
                    });

                e.preventDefault();
                });
            });
        </script>

        <?php
    }
    remove_shortcode('woosw_list');
    add_shortcode('woosw_list', 'new_wishlist');
}

add_action('wp_loaded', 'overwrite_shortcode');

add_shortcode( 'wishlist_add', 'wishlist_add_shortcode' );

function wishlist_add_shortcode( $atts ){

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 4,
        'post_status' => 'publish',
        'orderby' => 'rand',
        'fields' => 'ids'
    );
    $products = get_posts($args);
    //var_dump($products);
    ?>

    <div class="elementor-widget-container">
		<div class="products_list stone__products_list">
				<div class="cmsmasters-blog cmsmasters-theme-blog-grid cmsmasters-blog--type-default">
					<div class="elementor-widget-cmsmasters-theme-blog__posts-variable">
						<div class="elementor-widget-cmsmasters-theme-blog__posts-wrap">
							<div class="group__products_list cmsmasters-blog cmsmasters-theme-blog-grid">
								<div class="swiper wishlist-slider elementor-widget-cmsmasters-theme-blog-grid cmsmasters-post-type-product cmsmasters-block-default cmsmasters-sticky-default elementor-widget elementor-widget-cmsmasters-blog-similar">
									<div  class="swiper-wrapper elementor-widget-cmsmasters-theme-blog-grid__posts wish-list wish-list-sl ">
										<?php foreach($products as $productID) : ?>
											<div class="swiper-slide">
											<?php get_template_part('template-parts/content/why-not-add-item', '', array('product_id' => $productID)); ?>
											</div>
											
										<?php endforeach; ?> 
									</div>
								</div>
								<div class="swiper-pagination wish-list-pagination"></div>
							</div>
						</div>	
					</div>
				</div>		
		</div>
	</div> 


    <?php
}


?>
