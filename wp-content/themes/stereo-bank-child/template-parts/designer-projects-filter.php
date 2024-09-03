<?php $term = get_queried_object(); ?>
<?php 
if($args["object_ids"]) $object_ids = $args["object_ids"];
else $object_ids = [];
?>
<div class="product-page-filter designer-projects-filter">
	<div class="product-page-filter-container">
		<?php
		$property_types = get_terms([
			'taxonomy' => 'project_property_types',
			'orderby' => 'name',
			'order' => 'ASC',
			'object_ids' => $object_ids
		]);
		?>
		<?php if($property_types) : ?>
			<div class="product-page-filter-item" data-filter-by="project_property_types">
				<div class="product-page-filter-top">
					<div class="label">SELECT PROPERTY TYPE</div>
					<div class="title" data-default-title="ALL PROPERTY TYPE">
						<span>ALL PROPERTY TYPE</span>
						<div class="icon">
							<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.92512 5.63719L2.69562 4.87054L8.36099 10.5359L14.02 4.87054L14.7969 5.63719L8.36099 12.0731L1.92511 5.63719L1.92512 5.63719Z" fill="black"/></svg>
						</div>
					</div> 
				</div>
				<div class="product-page-filter-body">
					<?php foreach($property_types as $property_type) : ?>
						<div class="checked-item">
							<input type="checkbox" id="<?php echo $property_type->slug; ?>" value="<?php echo $property_type->slug; ?>">
							<label for="<?php echo $property_type->slug; ?>"><?php echo $property_type->name; ?></label>
						</div>
					<?php endforeach; ?>
					<button class="filter-button">Apply</button>
				</div>
			</div>
		<?php endif; ?>
		
		<?php
		$types = get_terms([
			'taxonomy' => 'project_types',
			'orderby' => 'name',
			'order' => 'ASC',
			'object_ids' => $object_ids
		]);
		?>
		<?php if($types) : ?>
			<div class="product-page-filter-item" data-filter-by="project_types">
				<div class="product-page-filter-top">
					<div class="label">SELECT PROJECT TYPE</div>
					<div class="title" data-default-title="ALL PROJECT TYPE">
						<span>ALL PROJECT TYPE</span>
						<div class="icon">
							<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.92512 5.63719L2.69562 4.87054L8.36099 10.5359L14.02 4.87054L14.7969 5.63719L8.36099 12.0731L1.92511 5.63719L1.92512 5.63719Z" fill="black"/></svg>
						</div>
					</div> 
				</div>
				<div class="product-page-filter-body">
					<?php foreach($types as $type) : ?>
						<div class="checked-item">
							<input type="checkbox" id="<?php echo $type->slug; ?>" value="<?php echo $type->slug; ?>">
							<label for="<?php echo $type->slug; ?>"><?php echo $type->name; ?></label>
						</div>
					<?php endforeach; ?>
					<button class="filter-button">Apply</button>
				</div>
			</div>
		<?php endif; ?>
		
		<?php
		$collections = get_terms([
			'taxonomy' => 'collection',
			'orderby' => 'name',
			'order' => 'ASC',
			'object_ids' => $object_ids
		]);
		?>
		<?php if($collections) : ?>
			<div class="product-page-filter-item" data-filter-by="collection">
				<div class="product-page-filter-top">
					<div class="label">SELECT COLLECTION</div>
					<div class="title" data-default-title="ALL COLLECTION">
						<span>ALL COLLECTION</span>
						<div class="icon">
							<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.92512 5.63719L2.69562 4.87054L8.36099 10.5359L14.02 4.87054L14.7969 5.63719L8.36099 12.0731L1.92511 5.63719L1.92512 5.63719Z" fill="black"/></svg>
						</div>
					</div> 
				</div>
				<div class="product-page-filter-body">
					<?php foreach($collections as $collection) : ?>
						<div class="checked-item">
							<input type="checkbox" id="<?php echo $collection->slug; ?>" value="<?php echo $collection->slug; ?>">
							<label for="<?php echo $collection->slug; ?>"><?php echo $collection->name; ?></label>
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
	
	<input type="hidden" name="designer_id" id="designer_id" value="<?php echo $term->term_id; ?>">
</div>