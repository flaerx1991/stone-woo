<?php
//GET PRODUCT INFO FROM JSON
add_action( 'wp', 'get_product_json_event' );
add_action( 'products_get_json', 'get_products_data' );
function get_product_json_event() {
	if( ! wp_next_scheduled( 'products_get_json' ) ) {
		wp_schedule_event( time(), 'hourly', 'products_get_json');
	}
}
function get_products_data() {
	$api_url1 = 'http://172.105.129.216/api/stock1.json';
	$api_url2 = 'http://172.105.129.216/api/stock2.json';

	$arrContextOptions = array(
		"ssl" => array(
			"verify_peer" => false,
			"verify_peer_name" => false,
		)
	);  
	$context = stream_context_create($arrContextOptions);

	$json = $json1 = $json1 = array();

	$data1 = file_get_contents($api_url1, FALSE, $context);
	if($data1) $json1 = json_decode($data1);

	$data2 = file_get_contents($api_url2, FALSE, $context);
	if($data2) $json2 = json_decode($data2);


	$json = array_merge($json1, $json2);


	if(is_array($json) && !empty($json)) {
		foreach($json as $_p_info) {
			$_p_sku = $_p_info->code;
			$_product_id = wc_get_product_id_by_sku( $_p_sku );

			if($_product_id) {
				$_product = new WC_Product($_product_id);
				$_p_available = round($_p_info->available, 0);
				$_p_incoming = round($_p_info->incoming, 0);
				
				$_product->set_stock_quantity($_p_available);
				$_product->save();
				if($_p_available) update_post_meta($_product_id, 'available', $_p_available);
				if($_p_incoming) update_post_meta($_product_id, 'incoming', $_p_incoming);


				//get Product UOMs
				$_p_uom = $_p_info->option1;
				if($_p_uom && $_p_uom != 'null') {
					$term_id = '';
					wp_suspend_cache_invalidation( true ); //clear term_exists cache

					$term = term_exists( $_p_uom, 'product_uom' );

					if( !isset($term['term_id']) ) {
						$cat = wp_insert_term( $_p_uom, 'product_uom' );
						if( ! is_wp_error( $cat ) ) {
							$term_id = (int) $cat['term_id'];
						} 
					}
					else {
						$term_id = (int) $term['term_id'];			
					}

					if($term_id) wp_set_object_terms( $_product_id, $term_id, 'product_uom', false );

					wp_suspend_cache_invalidation( false );

				}

				
			}
		}
	}
}

?>