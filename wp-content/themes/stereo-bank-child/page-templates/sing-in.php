<?php if(is_user_logged_in()) wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id')), 301); ?>

<?php
/*
Template Name: SIGN IN
*/
?>
<?php get_header(); ?>
 
<div class="custom-page">
	<div class="container">
		<div class="login-page">
			<div class="login-page-body">
				<div class="login-page-left">
					<div class="login-page-top">
						<h1 class="h1-title"><?php the_title(); ?></h1> 
						<div class="dsc">
							<?php _e("Don't have an account?", 'stone'); ?> 
							<a href="<?php the_field('sign_up_page', 'options'); ?>">
								<?php _e('Sign up', 'stone'); ?>
							</a>
						</div>
					</div>
					
					<?php do_action( 'woocommerce_before_customer_login_form' ); ?>
					
					<form class="woocommerce-form woocommerce-form-login login" method="post">
						<div class="form-item">
							<label for="username"><?php _e('email address', 'stone'); ?></label>
							<input type="text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php ?>
						</div>
						<div class="form-item">
							<label for="password"><?php _e('password', 'stone'); ?></label>
							<input type="password" name="password" id="password" autocomplete="password">
						</div>
						<div class="form-bottom">
							<div class="woocommerce-LostPassword lost_password">
								<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>">
									<?php esc_html_e( 'Reset Password', 'woocommerce' ); ?> 
									<svg width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.85076 1.75373H0.447762V0.5H10V10.0522H8.74628V2.64926L0.895523 10.5L0 9.60449L7.85076 1.75373Z" fill="black"/></svg>
								</a>
							</div>
							
							<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
							
							<button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'sign in', 'stone' ); ?>"><?php esc_html_e( 'sign in', 'stone' ); ?></button>
						</div> 
						<?php do_action( 'woocommerce_login_form_end' ); ?>
					</form>

				</div>
				<div class="login-page-img">
					<?php the_post_thumbnail('medium_large'); ?>
				</div>
			</div>
		</div> 		
	</div>
</div>  
<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
 
<?php get_footer(); ?>