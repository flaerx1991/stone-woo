<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked wc_empty_cart_message - 10
 */
//do_action( 'woocommerce_cart_is_empty' );

if ( wc_get_page_id( 'shop' ) > 0 ) : ?>

	<div class="woocommerce-cart-top">
	
	<h1><?php the_title(); ?></h1>

		<a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="link-button">
		<?php _e('Continue Shopping', 'stone'); ?>
		<svg width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.85076 1.75373H0.447762V0.5H10V10.0522H8.74628V2.64926L0.895523 10.5L0 9.60449L7.85076 1.75373Z" fill="black"/></svg>
	</a>

</div>

<div class="woocommerce-cart-body">
	<div class="empty-cart">
		<svg width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M45.4242 13.8274H37.2218V5.4509C37.2211 4.86714 36.9889 4.30749 36.5762 3.89471C36.1634 3.48193 35.6037 3.24972 35.02 3.24902H16.9823C16.3986 3.24971 15.8389 3.48192 15.4261 3.8947C15.0133 4.30748 14.7811 4.86714 14.7805 5.4509V13.8274H6.57813C6.47142 13.8274 6.36575 13.8484 6.26716 13.8892C6.16857 13.93 6.07899 13.9899 6.00354 14.0653C5.92809 14.1408 5.86824 14.2303 5.82742 14.3289C5.78659 14.4275 5.7656 14.5332 5.76563 14.6399V41.0001C5.76797 43.0546 6.58517 45.0243 8.03795 46.477C9.49073 47.9297 11.4604 48.7468 13.5149 48.749H38.4874C40.5419 48.7468 42.5116 47.9297 43.9644 46.477C45.4171 45.0243 46.2343 43.0546 46.2367 41.0001V14.6399C46.2367 14.5332 46.2157 14.4275 46.1749 14.3289C46.1341 14.2303 46.0742 14.1408 45.9988 14.0653C45.9233 13.9899 45.8337 13.93 45.7352 13.8892C45.6366 13.8484 45.5309 13.8274 45.4242 13.8274ZM16.4055 5.4509C16.4056 5.29794 16.4664 5.15129 16.5746 5.04313C16.6827 4.93497 16.8294 4.87415 16.9824 4.87402H35.02C35.1729 4.87415 35.3196 4.93497 35.4278 5.04313C35.5359 5.15129 35.5967 5.29794 35.5969 5.4509V13.8274H16.4055V5.4509ZM44.6117 41.0001C44.6098 42.6238 43.964 44.1804 42.8159 45.3285C41.6677 46.4765 40.1111 47.1223 38.4874 47.124H13.5149C11.8913 47.1223 10.3346 46.4765 9.18646 45.3285C8.03832 44.1804 7.39248 42.6238 7.39063 41.0001V15.4524H44.6117V41.0001Z" fill="#BDBDBD"/>
<path d="M26 38.999C28.1541 38.9966 30.2193 38.1398 31.7425 36.6166C33.2657 35.0934 34.1225 33.0282 34.125 30.8741C33.6802 20.0982 18.3215 20.0951 17.875 30.874C17.8775 33.0282 18.7343 35.0933 20.2575 36.6165C21.7807 38.1397 23.8459 38.9966 26 38.999ZM31.1513 34.8768C29.3422 33.0675 23.8621 27.587 22.0209 25.7457C28.1773 21.1591 35.6258 28.8213 31.1513 34.8768ZM20.8719 26.8945L29.9985 36.0218C24.2888 40.2501 16.2094 33.6652 20.8719 26.8945Z" fill="#BDBDBD"/></svg>
		
		<h2>cart is empty</h2>	
		<p>Looks like you haven’t added anything to your cart yet.</p>
	</div>
	
</div>
<?php endif; ?>
