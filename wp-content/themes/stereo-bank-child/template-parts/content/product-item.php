<?php 
$pID = $args["product_id"];
echo '<article id="post-' . $pID . '" class="' . implode(' ', get_post_class( 'elementor-widget-cmsmasters-theme-blog__post' )) . '">' .
	'<div class="elementor-widget-cmsmasters-theme-blog__post-inner">';

		if(function_exists('render_post_inner')) render_post_inner($pID, 'product', true);

	echo '</div>' .
'</article>';
?>

