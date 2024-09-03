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

		if(function_exists('render_post_inner')) render_post_inner( $pID, 'product', true );

	echo '</div>' .
'</article>';
?>
