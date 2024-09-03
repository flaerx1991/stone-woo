<?php
/*
Template Name: Contact Us
*/
?>
<?php get_header(); ?>
<div class="custom-page">
	<div class="contact-page">
		<div class="container">
			<h1 class="h1-title"><?php the_title(); ?></h1>
			<div class="contact-page-top">
				<?php echo wp_get_attachment_image(get_field('bg_image_dekstop'), 'full'); ?>
			</div>
			<div class="contact-page-body">
				<div class="contact-page-body-left">
					<div class="contact-page-body-contacts">
						
						<?php $contact_item_1 = get_field('contact_item_1'); ?>
						<div class="contact-page-body-contacts-item">
							<div class="titl"><?php echo $contact_item_1["label"]; ?></div>
							<a href="mailto:<?php echo $contact_item_1["e-mail"]; ?>" class="email">
								<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3.72477 17.875C3.33079 17.875 2.99334 17.7345 2.71241 17.4536C2.43148 17.1726 2.29102 16.8352 2.29102 16.4412V5.55876C2.29102 5.16478 2.43148 4.82732 2.71241 4.54639C2.99334 4.26546 3.33079 4.125 3.72477 4.125H18.2739C18.6679 4.125 19.0053 4.26546 19.2862 4.54639C19.5672 4.82732 19.7076 5.16478 19.7076 5.55876V16.4412C19.7076 16.8352 19.5672 17.1726 19.2862 17.4536C19.0053 17.7345 18.6679 17.875 18.2739 17.875H3.72477ZM10.9993 11.2632L3.44269 6.3167V16.4412C3.44269 16.5235 3.46914 16.591 3.52203 16.6439C3.57492 16.6968 3.6425 16.7233 3.72477 16.7233H18.2739C18.3561 16.7233 18.4237 16.6968 18.4766 16.6439C18.5295 16.591 18.556 16.5235 18.556 16.4412V6.3167L10.9993 11.2632ZM10.9993 10.0586L18.3714 5.27668H3.6425L10.9993 10.0586ZM3.44269 6.3167V5.27668V16.4412C3.44269 16.5235 3.46914 16.591 3.52203 16.6439C3.57492 16.6968 3.6425 16.7233 3.72477 16.7233H3.44269V6.3167Z" fill="black"/></svg> 
								<?php echo $contact_item_1["e-mail"]; ?>
							</a>
						</div>
						
						<?php $contact_item_2 = get_field('contact_item_2'); ?>
						<div class="contact-page-body-contacts-item">
							<div class="titl"><?php echo $contact_item_2["label"]; ?></div>
							<div class="phone">
								<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17.7869 18.7913C16.1275 18.7913 14.4317 18.384 12.6997 17.5694C10.9676 16.7547 9.36015 15.6045 7.87722 14.1187C6.3943 12.6328 5.24553 11.0253 4.43092 9.29619C3.6163 7.56706 3.20898 5.8728 3.20898 4.21341C3.20898 3.92616 3.3039 3.68678 3.49372 3.49527C3.68355 3.30376 3.92084 3.20801 4.20558 3.20801H6.94568C7.1822 3.20801 7.38477 3.28508 7.55341 3.43924C7.72206 3.59339 7.83674 3.78976 7.89746 4.02833L8.43696 6.5648C8.47021 6.80232 8.46254 7.01255 8.41396 7.19549C8.36539 7.37842 8.27491 7.53215 8.14253 7.65668L5.9948 9.74803C6.38106 10.4238 6.79874 11.0538 7.24786 11.6381C7.697 12.2224 8.18286 12.774 8.70543 13.2931C9.25387 13.8517 9.83717 14.3643 10.4553 14.8309C11.0735 15.2974 11.7369 15.7246 12.4456 16.1124L14.5181 13.9941C14.6677 13.8323 14.8396 13.7221 15.0337 13.6633C15.2278 13.6045 15.4301 13.5904 15.6404 13.621L17.9719 14.1204C18.2137 14.1776 18.4108 14.2998 18.5634 14.487C18.716 14.6743 18.7923 14.8864 18.7923 15.1235V17.7947C18.7923 18.0794 18.6965 18.3167 18.505 18.5066C18.3135 18.6964 18.0741 18.7913 17.7869 18.7913ZM5.43128 8.68569L7.2376 6.94049C7.26306 6.91698 7.28021 6.88465 7.28902 6.84351C7.29784 6.80238 7.29833 6.76419 7.29049 6.72892L6.82393 4.50074C6.81609 4.45371 6.7965 4.41845 6.76517 4.39495C6.73383 4.37144 6.69465 4.35968 6.64763 4.35968H4.48877C4.45352 4.35968 4.42414 4.37144 4.40063 4.39495C4.37712 4.41845 4.36536 4.44783 4.36536 4.48309C4.38965 5.11576 4.48964 5.77956 4.66534 6.4745C4.84103 7.16944 5.09635 7.9065 5.43128 8.68569ZM13.5332 16.6607C14.1533 16.9603 14.8217 17.1923 15.5384 17.3567C16.2551 17.521 16.9147 17.6141 17.5172 17.6361C17.5524 17.6361 17.5818 17.6243 17.6053 17.6008C17.6288 17.5773 17.6406 17.5479 17.6406 17.5127V15.3703C17.6406 15.3233 17.6288 15.2841 17.6053 15.2527C17.5818 15.2214 17.5466 15.2018 17.4995 15.194L15.4723 14.7779C15.4371 14.7701 15.4062 14.7706 15.3798 14.7794C15.3533 14.7882 15.3254 14.8054 15.296 14.8308L13.5332 16.6607Z" fill="black"/></svg>
								<div class="links">
									<?php if($contact_item_2["phone"]) : ?>
										<p>phone. <a href="tel:<?php echo str_replace(array(' ', '-', '(', ')'), '', $contact_item_2["phone"]); ?>"><?php echo $contact_item_2["phone"]; ?></a></p>
									<?php endif; ?>
									<?php if($contact_item_2["fax"]) : ?>
										<p>fax. <a href="tel:<?php echo str_replace(array(' ', '-', '(', ')'), '', $contact_item_2["fax"]); ?>"><?php echo $contact_item_2["fax"]; ?></a></p>
									<?php endif; ?>
								</div>
							</div>
						</div>
						
						<?php $contact_item_3 = get_field('contact_item_3'); ?>
						<div class="contact-page-body-contacts-item">
							<div class="titl"><?php echo $contact_item_3["label"]; ?></div>
							<p class="loc">
								<svg width="26" height="27" viewBox="0 0 26 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.0033 13.2219C13.5067 13.2219 13.9362 13.0426 14.292 12.6842C14.6478 12.3257 14.8257 11.8948 14.8257 11.3915C14.8257 10.8881 14.6465 10.4586 14.288 10.1028C13.9296 9.74702 13.4987 9.56912 12.9953 9.56912C12.492 9.56912 12.0624 9.74834 11.7066 10.1068C11.3508 10.4652 11.173 10.8961 11.173 11.3995C11.173 11.9028 11.3522 12.3324 11.7106 12.6882C12.0691 13.044 12.5 13.2219 13.0033 13.2219ZM12.9993 21.9948C15.2141 20.017 16.9038 18.1276 18.0684 16.3264C19.233 14.5252 19.8153 12.9504 19.8153 11.6017C19.8153 9.54941 19.1634 7.86249 17.8597 6.54097C16.5559 5.21944 14.9358 4.55868 12.9993 4.55868C11.0628 4.55868 9.4427 5.21944 8.13894 6.54097C6.83521 7.86249 6.18334 9.54941 6.18334 11.6017C6.18334 12.9504 6.77235 14.5252 7.95036 16.3264C9.12838 18.1276 10.8114 20.017 12.9993 21.9948ZM12.9993 23.8017C10.2729 21.4392 8.22851 19.2406 6.866 17.2059C5.50351 15.1712 4.82227 13.3031 4.82227 11.6017C4.82227 9.10176 5.63095 7.07781 7.24831 5.52989C8.86565 3.98197 10.7827 3.20801 12.9993 3.20801C15.216 3.20801 17.133 3.98197 18.7503 5.52989C20.3677 7.07781 21.1764 9.10176 21.1764 11.6017C21.1764 13.3031 20.4951 15.1712 19.1326 17.2059C17.7701 19.2406 15.7257 21.4392 12.9993 23.8017Z" fill="black"/></svg> 
								<?php echo $contact_item_3["address"]; ?>
							</p>
						</div>
						
						<?php $contact_item_4 = get_field('contact_item_4'); ?>
						<div class="contact-page-body-contacts-item">
							<div class="desc"><?php echo $contact_item_4["work_time"]; ?></div> 
						</div>
					</div>
					<div class="contact-page-body-map">
						<?php the_field('google_map'); ?>
					</div>
				</div>
				<div class="contact-page-body-right">
					<?php $contact_form = get_field('contact_form'); ?>
					<div class="title"><?php echo $contact_form["form_title"]; ?></div>
					<div class="dsc"><?php echo $contact_form["description"]; ?></div>
					<?php echo do_shortcode( $contact_form["contact_form_shortcode"] ); ?>    
					<div class="form-dsc"><?php echo $contact_form["bottom_text"]; ?></div> 
				</div>
			</div>
		</div>
	</div> 
</div>  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>

 $(".custom-select").each(function() {
  var classes = $(this).attr("class"),
      id      = $(this).attr("id"),
      name    = $(this).attr("name");
  var template =  '<div class="' + classes + '">';
      template += '<span class="custom-select-trigger">' + $(this).attr("placeholder") + '</span>';
      template += '<div class="custom-options">';
      $(this).find("option").each(function() {
        template += '<span class="custom-option ' + $(this).attr("class") + '" data-value="' + $(this).attr("value") + '">' + $(this).html() + '</span>';
      });
  template += '</div></div>';
  
  $(this).wrap('<div class="custom-select-wrapper"></div>');
  $(this).hide();
  $(this).after(template);
});
$(".custom-option:first-of-type").hover(function() {
  $(this).parents(".custom-options").addClass("option-hover");
}, function() {
  $(this).parents(".custom-options").removeClass("option-hover");
});
$(".custom-select-trigger").on("click", function(event) {
  $('html').one('click',function() {
    $(".custom-select").removeClass("opened");
  });
  $(this).parents(".custom-select").toggleClass("opened");
  event.stopPropagation();
});
$(".custom-option").on("click", function() {
  $(this).parents(".custom-select-wrapper").find("select").val($(this).data("value"));
  $(this).parents(".custom-options").find(".custom-option").removeClass("selection");
  $(this).addClass("selection");
  $(this).parents(".custom-select").removeClass("opened");
  $(this).parents(".custom-select").find(".custom-select-trigger").text($(this).text());
});
</script>
<?php get_footer(); ?>