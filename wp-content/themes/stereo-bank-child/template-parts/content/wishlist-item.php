<?php
$pID = $args["product_id"];
$key = $args["key"];
$_product = new WC_Product($pID);
$oum_term = wp_get_object_terms($pID, 'product_uom');
if($oum_term) {
	$uom = array_pop($oum_term);
	$uom = $uom->name;
}
else $uom = 'uom';
$uom = strtolower($uom);
//$views = get_post_meta($pID, 'cmsmasters_pm_view', true);

echo '<article id="post-' . $pID . '" class="' . implode(' ', get_post_class( 'elementor-widget-cmsmasters-theme-blog__post' )) . '">' .
	'<div class="elementor-widget-cmsmasters-theme-blog__post-inner">';
		stone_add_to_quote_popup( $_product, $pID);
        echo '<div class="elementor-widget-cmsmasters-theme-blog__cont">';
            render_wishlist_thumbnail( $pID, $_product, $key, true );

			render_product_footer( $pID );

			render_post_title($pID);

			$_product_uom = wc_get_product_terms( $pID, 'product_uom', array( 'fields' => 'names' ) );
			$uoms = ( $_product_uom ? strtolower( implode( ', ', $_product_uom ) ) : '' );

			if ( check_role($pID) ) {
				render_product_price( $_product, $uoms );
			}

			render_post_excerpt($pID);

			render_post_availability( $pID, $uoms );
		echo '</div>';
		// if(function_exists('render_post_inner')) render_post_inner( $pID, 'product', true );

	echo '</div>' .
'</article>';
?>
