<?php
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );

function acf_load_user_role_field_choices( $field ) {
    $field['choices'] = array();
    
    global $wp_roles;

    $all_roles = $wp_roles->roles;
    $editable_roles = apply_filters('editable_roles', $all_roles);

    if( is_array($editable_roles) ) {     
        foreach( $editable_roles as $role => $role_data ) {
            $field['choices'][ $role ] = $role_data["name"]; 
        }       
    }
    return $field;   
}
add_filter('acf/load_field/name=user_role', 'acf_load_user_role_field_choices');


/**
 *  Alter Product Pricing Part 1 - Alter Product Pricing Part 1 - WooCommerce Product
 */
add_filter( 'woocommerce_get_price_html', 'stone_alter_price_display', 9999, 2 );
function stone_alter_price_display( $price, $product ) {
    
    // ONLY ON FRONTEND
    if ( is_admin() ) return $price;
    
    // ONLY IF PRICE NOT NULL
    if ( '' === $product->get_price() ) return $price;

    $current_user_role = get_user_role();
    $role_discounts = get_field('user_role_discounts', 'options');
    if(!$role_discounts) return $price;


    $role_discount = 0;
    foreach($role_discounts as  $discount) {
        if( $current_user_role == $discount["user_role"]["value"]) $role_discount = (int) $discount["discount_value"];
    }

      
    if ( wc_current_user_has_role( $current_user_role ) && $role_discount != 0 ) {
        $discount = (100 - $role_discount) / 100;
        $discount = round($discount, 2);


        if ( $product->is_type( 'simple' ) || $product->is_type( 'variation' ) ) {        
         if ( $product->is_on_sale() ) {
            $price = wc_format_sale_price( wc_get_price_to_display( $product, array( 'price' => $product->get_regular_price() ) ) * $discount, wc_get_price_to_display( $product ) * $discount ) . $product->get_price_suffix();
         } else {
            $price = wc_price( wc_get_price_to_display( $product ) * $discount ) . $product->get_price_suffix();
         }        
      } elseif ( $product->is_type( 'variable' ) ) {
         $prices = $product->get_variation_prices( true );
         if ( empty( $prices['price'] ) ) {
            $price = apply_filters( 'woocommerce_variable_empty_price_html', '', $product );
         } else {
            $min_price = current( $prices['price'] );
            $max_price = end( $prices['price'] );
            $min_reg_price = current( $prices['regular_price'] );
            $max_reg_price = end( $prices['regular_price'] );
            if ( $min_price !== $max_price ) {
               $price = wc_format_price_range( $min_price * $discount, $max_price * $discount );
            } elseif ( $product->is_on_sale() && $min_reg_price === $max_reg_price ) {
               $price = wc_format_sale_price( wc_price( $max_reg_price * $discount ), wc_price( $min_price * $discount ) );
            } else {
               $price = wc_price( $min_price * $discount );
            }
            $price = apply_filters( 'woocommerce_variable_price_html', $price . $product->get_price_suffix(), $product );
         }
      }     
    }
    
    return $price;
 
}
 
/**
 *  Alter Product Pricing Part 2 - WooCommerce Cart/Checkout
 */
add_action( 'woocommerce_before_calculate_totals', 'stone_alter_price_cart', 9999 );
function stone_alter_price_cart( $cart ) {
 
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
 
    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) return;

    $current_user_role = get_user_role();

    $role_discounts = get_field('user_role_discounts', 'options');
    if(!$role_discounts) return;

    $role_discount = 0;
    foreach($role_discounts as  $discount) {
        if( $current_user_role == $discount["user_role"]["value"]) $role_discount = (int) $discount["discount_value"];
    }

    if($role_discount == 0) return;

    if($role_discount != 0 ) {
        $discount = (100 - $role_discount) / 100;
        $discount = round($discount, 2);

        // LOOP THROUGH CART ITEMS & APPLY DISCOUNT
        foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
            $product = $cart_item['data'];
            $price = $product->get_price();
            $cart_item['data']->set_price( $price * $discount );
        }
    }
 
}


function get_user_role() {
    global $current_user;

    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);

    return $user_role;
}

/*Change logout URL*/
add_filter( 'woocommerce_logout_default_redirect_url', 'stone_redirect_after_woocommerce_logout' );
function stone_redirect_after_woocommerce_logout() {
	$stone_logout_url = get_field('sign_in_page', 'options');
	if($stone_logout_url) return $stone_logout_url;
}


function sign_in_page_template_redirect(){
	if( is_account_page() && !is_lost_password_page() && ! is_user_logged_in() ){
		wp_redirect( get_field('sign_in_page', 'options') );
		exit();
	}
}
add_action( 'template_redirect', 'sign_in_page_template_redirect' );


//Save custom fields on Customer Created action 
add_action( 'woocommerce_created_customer', 'stone_save_extra_register_select_field' );   
function stone_save_extra_register_select_field( $customer_id ) {
	if ( isset( $_POST['first_name'] ) && isset( $_POST['last_name'] ) ) {
		$first_name = sanitize_text_field($_POST['first_name']);
		$last_name = sanitize_text_field($_POST['last_name']);
		wp_update_user([
			'ID' => $customer_id, 
			'first_name' => $first_name,
			'last_name' => $last_name,
			'display_name' => $first_name. ' ' .$last_name
		]);
	}
	
	if ( isset( $_POST['billing_phone'] ) ) {
		update_user_meta( $customer_id, 'billing_phone', sanitize_text_field($_POST['billing_phone']));
	}
	
	if ( isset( $_POST['account_type'] ) ) {
		update_user_meta( $customer_id, 'account_type', sanitize_text_field($_POST['account_type']));
	}
}


add_filter( 'woocommerce_registration_redirect', 'stone_redirection_after_registration', 10, 1 );
function stone_redirection_after_registration( $redirection_url ){
    // Change the redirection Url
    $redirection_url = get_permalink( get_option('woocommerce_myaccount_page_id') );
    return $redirection_url;
}


// Check the password and confirm password fields 
function stone_registration_errors_validation($reg_errors, $sanitized_user_login, $user_email) {
	global $woocommerce;
	extract( $_POST );
	if ( strcmp( $password, $password2 ) !== 0 ) {
		return new WP_Error( 'registration-error', __( 'Passwords do not match.', 'stone' ) );
	}
	return $reg_errors;
}
add_filter('woocommerce_registration_errors', 'stone_registration_errors_validation', 10, 3);


function stone_user_receive_updates_checkbox( $user_id ) {    
    $user_receive_updates = $_POST['user_receive_updates'];
    if( !$user_receive_updates ) {
        return;
    }
    update_user_meta( $user_id, 'user_receive_updates', $user_receive_updates );
}
add_action('user_register', 'stone_user_receive_updates_checkbox');


/*CHANGE ORDER SUBMIT BUTTON TEXT*/
add_filter( 'woocommerce_order_button_text', 'wc_custom_order_button_text' ); 
function wc_custom_order_button_text() {
    return __( 'Submit Order', 'stone' ); 
}


function stone_products_filters($query) {
    if( !is_admin() && $query->is_main_query() ) {

        if(is_shop() || is_post_type_archive('product')) {
   
			$tax_query = $query->get( 'tax_query' );
			if ( ! $tax_query ) {
				$tax_query = [];
			}
			
			//find GET parametrs from main page filters
			$tax_query = stone_find_get_parameters($tax_query);
			
			//for shop page not display outlet products
			$tax_query[] = [
				'taxonomy' => 'product_tag',
				'field' => 'slug',
				'terms' => 'outlet',
				'operator' => 'NOT IN'
			];

			if(!empty($tax_query)) $query->set( 'tax_query', $tax_query );
			
			
			if($per_page = get_field('products_per_page', 'options')) {
				$query->set( 'posts_per_page', $per_page );
			}
			else $query->set( 'posts_per_page', 12 );
			
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'order', 'DESC' );
			$query->set( 'meta_key', 'cmsmasters_pm_view' );
			
			
			//print_r($query);
        }
    }
}
add_action('pre_get_posts', 'stone_products_filters');


function stone_find_get_parameters($tax_query = array()) {
	
	$filters = get_field('products_filters', 'options');
	
	if($filters) {
		foreach($filters as $filter) {
			$taxonomy = $filter['products_filter_taxonomies'];
			if(isset($_GET[$taxonomy])) {
				$tax_query[] = array(
					'taxonomy' => $taxonomy,
					'field' => 'slug',
					'terms' => $_GET[$taxonomy]
				);
			}
		}
	}
	
	return $tax_query;
}

//Disallow direct add product to cart
add_filter( 'woocommerce_add_to_cart_validation', 'logged_in_customers_validation', 10, 3 );
function logged_in_customers_validation( $passed, $product_id, $quantity) {
	$user = wp_get_current_user();
	
	
    if( ! is_user_logged_in() || in_array( 'customer', $user->roles )) {
        $passed = false;
    }
	
	$product_tags = wp_get_object_terms($product_id, 'product_tag', array('fields' => 'slugs'));
	if($product_tags) {
		if(in_array('outlet', $product_tags))  $passed = true;
	}
	
    return $passed;
}


//Lock archive pages
function archive_pages_template_redirect(){
	if( is_product_category() || is_tax('product_tag') || is_post_type_archive('collection_category') ){
		wp_redirect( wc_get_page_permalink( 'shop' ) );
		exit();
	}
}
add_action( 'template_redirect', 'archive_pages_template_redirect' );
?>