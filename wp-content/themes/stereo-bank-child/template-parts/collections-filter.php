<?php 
global $post;
$collection_slug = $post->post_name;
$post_id = get_the_ID();
?>
<?php
if($args["products"]) $object_ids = $args["products"];
else $object_ids = [];

?>
<div class="product-page-filter collections-filter">
	<div class="product-page-filter-container">
		<?php
		$product_types = get_terms([
			'taxonomy' => 'theme_product_type',
			'orderby' => 'name',
			'order' => 'ASC',
			'parent' => 0,
			'object_ids' => $object_ids
		]);
		?>
		<?php if($product_types) : ?>
			<div class="product-page-filter-item" data-filter-by="theme_product_type">
				<div class="product-page-filter-top">
					<div class="label">select type</div>
					<div class="title" data-default-title="all types">
						<span>all types</span>
						<div class="icon">
							<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.92512 5.63719L2.69562 4.87054L8.36099 10.5359L14.02 4.87054L14.7969 5.63719L8.36099 12.0731L1.92511 5.63719L1.92512 5.63719Z" fill="black"/></svg>
						</div>
					</div> 
				</div>
				<div class="product-page-filter-body">
					<?php foreach($product_types as $product_type) : ?>
						<div class="checked-item">
							<input type="checkbox" id="<?php echo $product_type->slug; ?>" value="<?php echo $product_type->slug; ?>">
							<label for="<?php echo $product_type->slug; ?>"><?php echo $product_type->name; ?></label>
						</div>
					<?php endforeach; ?>
					<button class="filter-button">Apply</button>
				</div>
			</div>
		<?php endif; ?>
		
		<?php
		$product_sizes = get_terms([
			'taxonomy' => 'product_size',
			'orderby' => 'name',
			'order' => 'ASC',
			'object_ids' => $object_ids
		]);
		?>
		<?php if($product_sizes) : ?>
			<div class="product-page-filter-item" data-filter-by="product_size">
				<div class="product-page-filter-top">
					<div class="label">select size</div>
					<div class="title" data-default-title="all sizes">
						<span>all sizes</span>
						<div class="icon">
							<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.92512 5.63719L2.69562 4.87054L8.36099 10.5359L14.02 4.87054L14.7969 5.63719L8.36099 12.0731L1.92511 5.63719L1.92512 5.63719Z" fill="black"/></svg>
						</div>
					</div> 
				</div>
				<div class="product-page-filter-body">
					<?php foreach($product_sizes as $product_size) : ?>
						<div class="checked-item">
							<input type="checkbox" id="<?php echo $product_size->slug; ?>" value="<?php echo $product_size->slug; ?>">
							<label for="<?php echo $product_size->slug; ?>"><?php echo $product_size->name; ?></label>
						</div>
					<?php endforeach; ?>
					<button class="filter-button">Apply</button>
				</div>
			</div>
		<?php endif; ?>
		
		<?php
		$product_usages = get_terms([
			'taxonomy' => 'product_usages',
			'orderby' => 'name',
			'order' => 'ASC',
			'object_ids' => $object_ids
		]);
		?>
		<?php if($product_usages) : ?>
			<div class="product-page-filter-item" data-filter-by="product_usages">
				<div class="product-page-filter-top">
					<div class="label">select usage</div>
					<div class="title" data-default-title="ALL COLLECTION">
						<span>all usages</span>
						<div class="icon">
							<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.92512 5.63719L2.69562 4.87054L8.36099 10.5359L14.02 4.87054L14.7969 5.63719L8.36099 12.0731L1.92511 5.63719L1.92512 5.63719Z" fill="black"/></svg>
						</div>
					</div> 
				</div>
				<div class="product-page-filter-body">
					<?php foreach($product_usages as $product_usage) : ?>
						<div class="checked-item">
							<input type="checkbox" id="<?php echo $product_usage->slug; ?>" value="<?php echo $product_usage->slug; ?>">
							<label for="<?php echo $product_usage->slug; ?>"><?php echo $product_usage->name; ?></label>
						</div>
					<?php endforeach; ?>
					<button class="filter-button">Apply</button>
				</div>
			</div>
		<?php endif; ?>
		
		<div class="product-page-filter-item sortby">
			<div class="product-page-filter-top">
				<div class="label">SORT BY</div>
				<div class="title" data-default-title="Latest">
					<span>Latest</span>
					<div class="icon">
						<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.92512 5.63719L2.69562 4.87054L8.36099 10.5359L14.02 4.87054L14.7969 5.63719L8.36099 12.0731L1.92511 5.63719L1.92512 5.63719Z" fill="black"/></svg>
					</div>
				</div> 
			</div>
			<div class="product-page-filter-body">
				
				<div class="checked-item">
					<input type="radio" name="sortby" id="sort-by-latest" value="latest" checked>
					<label for="sort-by-latest">Latest</label>
				</div>
				<div class="checked-item">
					<input type="radio" name="sortby" id="sort-by-popular" value="popular" >
					<label for="sort-by-popular">Popular</label>
				</div>

				<button class="filter-button">Apply</button>
			</div>
		</div>
	</div>
	
	<input type="hidden" name="collection_slug" id="collection_slug" value="<?php echo $collection_slug; ?>">
	<input type="hidden" name="collection_id" id="collection_id" value="<?php echo $post_id; ?>">
</div>