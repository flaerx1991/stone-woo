<?php
/*
Template Name: Dealer locator
*/
?>
<?php get_header(); ?>
<div class="custom-page">
	<div class="dealer-page">
		<div class="container">
			<h1 class="h1-title"><?php the_title(); ?></h1>
			<div class="dealer-page-top">
				<?php echo wp_get_attachment_image(get_field('bg_image'), 'full'); ?>
				<div class="desc"><?php the_field('top_text'); ?></div>
			</div>
			
			<?php
			$locations = get_terms([
				'taxonomy' => 'dealer_location',
				//'hide_empty' => false,
				'orderby' => 'name',
				'order' => 'ASC'
			]);
			?>
			<?php if($locations) : $i = 0; ?>
				<div class="dealer-page-countries">
					<?php foreach($locations as $location) : ?>
						<a href="#" class="dealer-page-country <?php echo($i == 0) ? 'active':''; ?>" data-locaton="<?php echo $location->slug; ?>">
							<?php echo $location->name; ?>
						</a>
						<?php $i++; ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			
			

			<?php 
			$args = array(
				'post_type' => 'dealers',
				'post_status' => 'publish',
				'posts_per_page' => -1
			);
			$dealers = new WP_Query($args);
			$dealers_data = [];
			if($dealers->have_posts()) {
				while($dealers->have_posts()) { $dealers->the_post();
					$dealer_ID = $post->ID;
					$dealer_loc = get_the_terms($dealer_ID, 'dealer_location');
					if($dealer_loc) $dealer_loc = $dealer_loc[0];
					$dealers_data[$dealer_loc->slug][] = $dealer_ID;					   
				}
			}
			if(!empty($dealers_data)) ksort($dealers_data);
			?>
			
			<?php if(!empty($dealers_data)) : $i = 0; ?>
				<div class="dealer-page-items">
					<div class="dealer-page-top-titl">
						<div class="dealer-page-titl"><?php _e('Company', 'stone'); ?></div>
						<div class="dealer-page-titl"><?php _e('Phone', 'stone'); ?></div>
						<div class="dealer-page-titl"><?php _e('Address', 'stone'); ?></div>
						<div class="dealer-page-titl"><?php _e('Distance', 'stone'); ?></div>
						<div class="dealer-page-titl"></div>
					</div>
					<?php foreach($dealers_data as $key => $dealerIDs) : ?>
						<?php if(!empty($dealerIDs)) : ?>
							<div class="dealer-page-item--wrapper <?php echo($i == 0) ? 'active':''; ?>" id="<?php echo $key; ?>">
								<?php foreach($dealerIDs as $dealerID) : ?>
									<div class="dealer-page-item">
										<div class="dealer-page-col dealer-name"><?php echo get_the_title($dealerID); ?></div>
										<div class="dealer-page-col">
											<?php $tel = get_field('telephone', $dealerID); ?>
											<a href="tel:<?php echo str_replace(array(' ', '-', '(', ')'),'',$tel); ?>"><?php echo $tel; ?></a>
										</div>
										<?php $location = get_field('location', $dealerID); ?>
										
										<div class="dealer-page-col">
											<?php if(!empty($location)) : ?>
												<a href="https://www.google.com/maps/place/?q=place_id:<?php echo $location["place_id"]; ?>" target="_blank">
													<?php echo $location["address"]; ?>
												</a>
											<?php endif; ?>
										</div>
										<div class="dealer-page-col">4.93 mile</div>
										<?php if($website = get_field('website', $dealerID)) : ?>
										<div class="dealer-page-col">
											<a href="<?php echo $website; ?>" class="link">
												<?php _e('Contact', 'stone'); ?> 
												<svg width="8" height="9" viewBox="0 0 8 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.28157 1.50299H0.359185V0.5H8.00098V8.14179H6.99799V2.2194L0.717394 8.5L0.000976562 7.78358L6.28157 1.50299Z" fill="black"/></svg>
											</a>
										</div>	
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
						<?php $i++; ?>
					<?php endforeach; ?>
				</div>	
			<?php endif; ?>
			
			<div class="dealer-page-map" id="map"></div>

			<?php if($bottom_text = get_field('bottom_text')) : ?>
				<div class="dealer-page-content">
					<?php echo $bottom_text; ?>
				</div>
			<?php endif; ?>
		</div>
	</div> 
</div>  

<?php
$map_data = [];
foreach($dealers_data as $key => $dealerIDs) {
	if(!empty($dealerIDs)) {
		 foreach($dealerIDs as $dealerID) {
			 $location = get_field('location', $dealerID);
			 $tel = get_field('telephone', $dealerID);
			 $website = get_field('website', $dealerID);
			 
			 
			 $point_html = '<div class="map_point">';
				 $point_html .= '<div class="map_point__title">';
			 	 $point_html .= get_the_title($dealerID);
			  		$point_html .= '<div class="map_point__links">';
						if($tel) {
							$point_html .= '<a href="'.str_replace(array(' ', '-', '(', ')'),'',$tel).'">';
							$point_html .= '<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17.7869 18.7913C16.1275 18.7913 14.4317 18.384 12.6997 17.5694C10.9676 16.7547 9.36015 15.6045 7.87722 14.1187C6.3943 12.6328 5.24553 11.0253 4.43092 9.29619C3.6163 7.56706 3.20898 5.8728 3.20898 4.21341C3.20898 3.92616 3.3039 3.68678 3.49372 3.49527C3.68355 3.30376 3.92084 3.20801 4.20558 3.20801H6.94568C7.1822 3.20801 7.38477 3.28508 7.55341 3.43924C7.72206 3.59339 7.83674 3.78976 7.89746 4.02833L8.43696 6.5648C8.47021 6.80232 8.46254 7.01255 8.41396 7.19549C8.36539 7.37842 8.27491 7.53215 8.14253 7.65668L5.9948 9.74803C6.38106 10.4238 6.79874 11.0538 7.24786 11.6381C7.697 12.2224 8.18286 12.774 8.70543 13.2931C9.25387 13.8517 9.83717 14.3643 10.4553 14.8309C11.0735 15.2974 11.7369 15.7246 12.4456 16.1124L14.5181 13.9941C14.6677 13.8323 14.8396 13.7221 15.0337 13.6633C15.2278 13.6045 15.4301 13.5904 15.6404 13.621L17.9719 14.1204C18.2137 14.1776 18.4108 14.2998 18.5634 14.487C18.716 14.6743 18.7923 14.8864 18.7923 15.1235V17.7947C18.7923 18.0794 18.6965 18.3167 18.505 18.5066C18.3135 18.6964 18.0741 18.7913 17.7869 18.7913ZM5.43128 8.68569L7.2376 6.94049C7.26306 6.91698 7.28021 6.88465 7.28902 6.84351C7.29784 6.80238 7.29833 6.76419 7.29049 6.72892L6.82393 4.50074C6.81609 4.45371 6.7965 4.41845 6.76517 4.39495C6.73383 4.37144 6.69465 4.35968 6.64763 4.35968H4.48877C4.45352 4.35968 4.42414 4.37144 4.40063 4.39495C4.37712 4.41845 4.36536 4.44783 4.36536 4.48309C4.38965 5.11576 4.48964 5.77956 4.66534 6.4745C4.84103 7.16944 5.09635 7.9065 5.43128 8.68569ZM13.5332 16.6607C14.1533 16.9603 14.8217 17.1923 15.5384 17.3567C16.2551 17.521 16.9147 17.6141 17.5172 17.6361C17.5524 17.6361 17.5818 17.6243 17.6053 17.6008C17.6288 17.5773 17.6406 17.5479 17.6406 17.5127V15.3703C17.6406 15.3233 17.6288 15.2841 17.6053 15.2527C17.5818 15.2214 17.5466 15.2018 17.4995 15.194L15.4723 14.7779C15.4371 14.7701 15.4062 14.7706 15.3798 14.7794C15.3533 14.7882 15.3254 14.8054 15.296 14.8308L13.5332 16.6607Z" fill="black"></path></svg>';
							$point_html .= '</a>';
						}
						if($website) {
							$point_html .= '<a href="'.$website.'">';
							$point_html .= '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 256 256" enable-background="new 0 0 256 256" xml:space="preserve"><g><g><path fill="#000000" d="M246,147.1c0-31.7-9.4-57.7-28-77.2c-14.1-14.7-31.5-24-52.2-27.6c33.4-21.7,55.3-24.6,58.8,5.6c0.8,6.9-3.3,17.2-4.6,23.9c5.3-12.5,9.4-28.2,7.9-39.2c-5.5-42.2-49.2-37-105.2,9.4C99,45.4,79.4,54.7,63.9,70.2C47.1,87,37.5,108.8,34.9,135.5C13.1,167.7,2,199.4,16.6,221.1c11.3,16.9,39.5,10.7,45.7,4.1c-13.8,0.2-28.5,5.7-34.6-9.9c-3.4-8.7,0.4-22.5,8.9-38.5c3.9,19.5,12.4,35.7,25.7,48.4c18.8,18,44.7,27,77.6,27c27.4,0,49.8-6.3,67.2-17.8s29-27,34.8-50.8h-38.7c-2.5,15.9-9.3,21-20.4,28.2c-11.2,7.2-24.2,11.1-39.1,11.1c-23.8,0-41.1-5.5-52.1-18.3c-9-10.5-14.3-29-15.9-44.9h169.9L246,147.1z M75.7,128c0.5,0,1.2-6.4,2.2-10.1c15-17.1,31.7-32.8,48-46.5c5.3-1.1,10.9-1.3,16.8-1.3c20.4,0,35.9,4.4,46.5,15.9c9.6,10.4,14.9,26.2,16.1,42.1H75.7z"/></g></g></svg>';
							$point_html .= '</a>';
						}
			 		$point_html .= '</div>';
				 $point_html .= '</div>';
				 $point_html .= '<div class="map_point__address">' . $location["address"] . '</div>';
			 $point_html .= '</div>';
			 $map_data[] = array('lat' => $location['lat'], 'lng' => $location['lng'], 'html' => $point_html);
		 }
	}
}
?>

<script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
<script id="googlemap-script" type="text/javascript">		
	function initMap() {
		var markerArray = [];
		var locations = [
			<?php foreach($map_data as $map_point) : ?>	
				[
					<?php echo $map_point['lat']; ?>, 
					<?php echo $map_point['lng']; ?>,
					'<?php echo $map_point['html']; ?>'

				],	
			<?php endforeach; ?>
		];
		console.log(locations);
		
		var map = new google.maps.Map(document.getElementById('map'), {
			zoom: 4,
			center: new google.maps.LatLng(<?php echo $map_data[0]['lat']; ?>, <?php echo $map_data[0]['lng']; ?>),	
			disableDefaultUI: true,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});
		

		var infowindow = new google.maps.InfoWindow(),
			marker,
			i,
			iconBase = '<?php echo get_stylesheet_directory_uri(); ?>';
		
		
		
		for (i = 0; i < locations.length; i++) {
			marker = new google.maps.Marker({
				position: new google.maps.LatLng(locations[i][0], locations[i][1]),
				icon:  iconBase + '/assets/img/map_marker.svg',
				map: map
			});
			
			markerArray.push(marker); //push local var marker into global array

			google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
				return function() {
					infowindow.setContent(locations[i][2]);
					infowindow.open(map, marker);
				}
			})(marker, i));
			
		}
		var svg = window.btoa('<svg fill="#000000" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 240"><circle cx="120" cy="120" opacity=".6" r="70" /><circle cx="120" cy="120" opacity=".3" r="90" /><circle cx="120" cy="120" opacity=".2" r="110" /><circle cx="120" cy="120" opacity=".1" r="130" /></svg>');
		const renderer = {
			render({ count, position }) {
				return new google.maps.Marker({
					icon: {
						url: 'data:image/svg+xml;base64,'+ svg,
						scaledSize: new google.maps.Size(45, 45),
					},
					label: {
						text: String(count),
						color: "rgb(255,255,255)",
						fontSize: "12px",
					},
					position,
					zIndex: Number(google.maps.Marker.MAX_ZINDEX) + count,
				});
			}
		}
		const markerCluster = new markerClusterer.MarkerClusterer({map: map, markers: markerArray, renderer});
	}

</script>

<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrAblkEW9CXOzIOCoiozu_i2ymHOwKytc&callback=initMap&libraries=geometry"></script>

<?php get_footer(); ?>