<?php 
$product_id = $args["pID"];
$_product = new WC_Product($product_id);
$allow = check_role($product_id);
?>
<a href="<?php echo get_the_permalink($product_id); ?>" class="product-page-item">
	<div class="product-page-col"><?php echo get_the_title($product_id); ?></div> 
	<div class="product-page-col" data-name="<?php _e('sku', 'stone'); ?>"><?php echo $_product->get_sku(); ?></div> 
	<div class="product-page-col" data-name="<?php _e('pack', 'stone'); ?>">
		<?php
		$product_packs = wp_get_post_terms($product_id, 'product_pack');
		if($product_packs) {
			$packs = [];
			foreach($product_packs as $product_pack) {
				$packs[] = $product_pack->name;
			}
		}
		if(!empty($packs)) echo implode(', ', $packs);
		?>
	</div> 
	<div class="product-page-col" data-name="<?php _e('uom', 'stone'); ?>">
		<?php
		$product_uoms = wp_get_post_terms($product_id, 'product_uom');
		if($product_uoms) {
			$uoms = [];
			foreach($product_uoms as $product_uom) {
				$uoms[] = $product_uom->name;
			}
		}
		if(!empty($uoms)) echo implode(', ', $uoms);
		?>
	</div>
	
	
	<div class="product-page-col" data-name="<?php _e('price', 'stone'); ?>">
		<?php if($allow && $_product->get_price()) : ?>
			<?php echo $_product->get_price_html(); ?>
		<?php endif; ?>
	</div> 
	
	
	<div class="product-page-col" data-name="<?php _e('AVAILABLE', 'stone'); ?>">
		<?php if($_product_available = get_field('available', $product_id)) : ?>
			<?php echo $_product_available; ?>
		<?php else : ?>
			<?php echo '-'; ?>
		<?php endif; ?>
	</div> 
	<div class="product-page-col" data-name="<?php _e('incoming', 'stone'); ?>">
		<?php if($_product_incoming = get_field('incoming', $product_id)) : ?>
			<?php echo $_product_incoming; ?>
		<?php else : ?>
			<?php echo '-'; ?>
		<?php endif; ?>
	</div> 
	<div class="product-page-col">
		<svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.2806 1.00299H0.358209V0H8V7.64179H6.99701V1.7194L0.716418 8L0 7.28358L6.2806 1.00299Z" fill="black"/></svg>
	</div> 
</a> 