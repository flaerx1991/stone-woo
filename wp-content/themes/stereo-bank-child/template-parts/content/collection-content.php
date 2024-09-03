<div class="cmsmasters-tab-list-mode-justify cmsmasters-list-item-alignment-center cmsmasters-tabs-type-horizontal cmsmasters-icon-position-left cmsmasters-tabs-position-start cmsmasters-list-ver-align-bottom cmsmasters-icon-view-default cmsmasters-pointer-none cmsmasters-block-default cmsmasters-sticky-default elementor-widget elementor-widget-cmsmasters-tabs cmsmasters-widget-tabs">
	<?php
	$general_tab_content = get_field('general_tab_content');
	$specifications_tab_content = get_field('specifications_list');
	?>

	<div class="cmsmasters-tabs" role="tablist">
		<div class="cmsmasters-tabs-list-wrapper">
			<ul class="cmsmasters-tabs-list">
				<?php if($general_tab_content && $specifications_tab_content) : ?>
					<li class="cmsmasters-tabs-list-item cmsmasters-animation active-tab" data-tab="1">
						<a href="" class="cmsmasters-tab-title">
							<div class="cmsmasters-tab-title__text-wrap-outer">
								<div class="cmsmasters-tab-title__text-wrap">
									<div class="cmsmasters-tab-title__text ">General</div>
								</div>
							</div>
						</a>
					</li>
				<?php endif; ?>
				
				<?php if($specifications_tab_content) : ?>
					<li class="cmsmasters-tabs-list-item cmsmasters-animation" data-tab="2">
						<a href="" class="cmsmasters-tab-title">
							<div class="cmsmasters-tab-title__text-wrap-outer">
								<div class="cmsmasters-tab-title__text-wrap">
									<div class="cmsmasters-tab-title__text ">Specifications</div>
								</div>
							</div>
						</a>
					</li>
				<?php endif; ?>
			</ul>
		</div>

		<div class="cmsmasters-tabs-wrap">
			<?php if($general_tab_content) : ?>
				<div class="cmsmasters-tab active-tab" data-tab="1" style="display: block;">
					<div data-elementor-type="section" class="elementor cmsmasters-location-cmsmasters_singular cmsmasters-header-position-absolute-">
						<div class="elementor-inner">
							<div class="collection-content-tab1">
								<?php if($column1 = $general_tab_content["column_1"]) : ?>
									<div class="collection-content-tab1--column column1">
										<div class="collection-content-tab1--ceil">
											<?php if($column1["custom_subtitle"]) : ?>
												<span><?php echo $column1["custom_subtitle"]; ?></span>
											<?php else : ?>
												<?php $collection_categories = get_the_terms(get_the_ID(), 'collection_category'); ?>
												<span><?php echo $collection_categories[0]->name; ?></span>
											<?php endif; ?>

											<?php if($column1["custom_title"]) : ?>
												<h2><?php echo $column1["custom_title"]; ?></h2>
											<?php else : ?>
												<h2><?php the_title(); ?></h2>
											<?php endif; ?>

											<div class="ceil__text">
												<?php echo $column1["text"]; ?>
											</div>									
										</div>
										<div class="collection-content-tab1--ceil">									
											<?php echo wp_get_attachment_image($column1["image"], 'medium_large'); ?>
										</div>
									</div>
								<?php endif; ?>

								<?php if($column2 = $general_tab_content["column_2"]) : ?>
									<div class="collection-content-tab1--column column2">
										<div class="collection-content-tab1--ceil">									
											<?php echo wp_get_attachment_image($column2["image"], 'medium_large'); ?>
										</div>
										<div class="collection-content-tab1--ceil">
											<div class="ceil__text">
												<?php echo $column2["text"]; ?>
											</div>									
										</div>
									</div>
								<?php endif; ?>

								<?php if($column3 = $general_tab_content["column_3"]) : ?>
									<div class="collection-content-tab1--column column3">
										<div class="collection-content-tab1--ceil" style="background: linear-gradient(180deg, rgba(0, 0, 0, 0.00) 0%, rgba(0, 0, 0, 0.60) 100%), url('<?php echo wp_get_attachment_image_url($column3["image"], 'medium_large'); ?>') center/cover, lightgray 0px -180.461px / 100% 149.982% no-repeat;">									
											<div class="ceil__text">
												<?php echo $column3["text"]; ?>
											</div>	
										</div>
										<div class="collection-content-tab1--ceil">									
											<?php echo wp_get_attachment_image($column3["image"], 'medium_large'); ?>
										</div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<?php if($specifications_tab_content) : ?>
				<div class="cmsmasters-tab" data-tab="2" style="display: none;">
					<div data-elementor-type="section" class="elementor cmsmasters-location-cmsmasters_singular cmsmasters-header-position-absolute-">
						<div class="collection-content-tab2">
							<div class="elementor-widget-cmsmasters-theme-product-tab-additional-info__wrap specifications">
								<?php while(have_rows('specifications_list')) : the_row(); ?>
									<span class="elementor-widget-cmsmasters-theme-product-tab-additional-info__attributes">
										<span class="elementor-widget-cmsmasters-theme-product-tab-additional-info__attributes-name">
											<?php the_sub_field('label'); ?>
										</span>
										<span class="elementor-widget-cmsmasters-theme-product-tab-additional-info__attributes-values">
											<?php the_sub_field('value'); ?>
										</span>
									</span>
								<?php endwhile; ?>
							</div>	

							<?php if($additional_image = get_field('additional_image')) : ?>
								<div class="collection-content-tab2--image">
									<?php echo wp_get_attachment_image($additional_image, 'medium_large'); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>