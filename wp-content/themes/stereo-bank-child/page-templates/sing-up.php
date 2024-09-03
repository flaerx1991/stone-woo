<?php
/*
Template Name: SIGN UP
*/
?>
<?php get_header(); ?>

<div class="custom-page">
	<div class="container">
		<div class="login-page">
			<div class="login-page-body">

				<div class="login-page-left">
	
					<div class="login-page-top">
						<h1 class="h1-title">Create <br/> account</h1> 
						<div class="dsc"> <?php _e("Already have an account?", 'stone'); ?>
							<a href="<?php the_field('sign_in_page', 'options'); ?>">Sign in</a>
						</div>
					</div> 
					
					<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

					<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >
						
						<?php do_action( 'woocommerce_register_form_start' ); ?>
						
						<div class="form-item">
							<label for="reg_email">Company Name</label>
							<input type="text"  name="company" id="company" autocomplete="off" value="" />
						</div>
						
						<div class="form-row">
							<div class="form-item">
								<label for="reg_first_name">first name</label>
								<input type="text"  name="first_name" id="reg_first_name" autocomplete="first_name" value="<?php echo ( ! empty( $_POST['first_name'] ) ) ? esc_attr( wp_unslash( $_POST['first_name'] ) ) : ''; ?>" />
							</div>
							<div class="form-item">
								<label for="reg_last_name">last name</label>
								<input type="text"  name="last_name" id="reg_last_name" autocomplete="last_name" value="<?php echo ( ! empty( $_POST['last_name'] ) ) ? esc_attr( wp_unslash( $_POST['last_name'] ) ) : ''; ?>" />
							</div>
						</div>
						
						<div class="form-row">
							<div class="form-item">
								<label for="reg_email">email address*</label>
								<input type="email"  name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required />
							</div>
							<div class="form-item">
								<label for="reg_billing_phone">Phone*</label>
								<input type="text"  name="billing_phone" id="reg_billing_phone" autocomplete="billing_phone" value="<?php echo ( ! empty( $_POST['billing_phone'] ) ) ? esc_attr( wp_unslash( $_POST['billing_phone'] ) ) : ''; ?>" required onkeypress="return /[0-9]/i.test(event.key)" />
							</div>
						</div>
						
						<div class="form-row">
							<div class="form-item">
								<label for="reg_password">password*</label>
								<input type="password" name="password" id="reg_password" autocomplete="new-password" required />
							</div> 
							<div class="form-item">
								<label for="reg_password">Confirm password*</label>
								<input type="password" name="password2" id="reg_password2" autocomplete="new-password" required />
							</div> 
						</div>
						
						

						<?php if(have_rows('account_types', 'options')) : ?>
							<div class="form-item">
								<label for="sources"><?php _e('Account type', 'stone'); ?></label> 
								<select class="custom-select sources" id="sources" name="account_type" placeholder="<?php _e('Select please', 'stone'); ?>">
									<?php while(have_rows('account_types', 'options')) : the_row(); ?>
										<?php $account_type = get_sub_field('account_type'); ?>
										<option value="<?php echo $account_type; ?>"><?php echo $account_type; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
						<?php endif; ?>
						
						<?php do_action( 'woocommerce_register_form' ); ?>
						
						<div class="form-bottom reg-bottom"> 
							<div class="form-group">
								<input type="checkbox" id="user_receive_updates" name="user_receive_updates">
								<label for="user_receive_updates">Yes, I would like to receive updates <br>and offers from Stone Solutions.</label>
							</div>
							<p class="woocommerce-form-row">
								<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
								<button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'SIGN UP', 'stone' ); ?>"><?php esc_html_e( 'SIGN UP', 'stone' ); ?></button>
							</p> 
						</div>

						<?php do_action( 'woocommerce_register_form_end' ); ?>

					</form>
					
					
					
					<div class="policy">
						<?php the_content(); ?>
					</div>
				</div>
				<div class="login-page-img">
					<?php the_post_thumbnail('large'); ?>
				</div>
			</div>
			
			<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
		</div> 		
	</div>
</div>  

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>

<?php get_footer(); ?>