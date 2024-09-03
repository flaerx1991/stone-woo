<?php
/**
 * HTML Template Email
 *
 * @package YITH\RequestAQuote
 * @since   1.0.0
 * @version 1.5.3
 * @author  YITH <plugins@yithemes.com>
 *
 * @var $email_heading array
 * @var $raq_data array
 * @var $email
 */

?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>


<p>
<?php
	/* translators: %s: user name */
	printf( esc_html__( 'You received a quote request from %s. The request is the following:', 'stone' ), esc_html( $raq_data['user_name'] ) );
?>
	</p>

<?php do_action( 'yith_ywraq_email_before_raq_table', $raq_data ); ?>

<h2><?php esc_html_e( 'Quote request', 'stone' ); ?></h2>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee; margin-bottom:30px" border="1" bordercolor="#eee">
	<thead>
	<tr>
		<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php esc_html_e( 'Product', 'stone' ); ?></th>
		<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php esc_html_e( 'Quantity', 'stone' ); ?></th>
		<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php esc_html_e( 'Price', 'stone' ); ?></th>
	</tr>
	</thead>
	<tbody>
    <h2>ghjklm</h2>
	<?php
	if ( ! empty( $raq_data['raq_content'] ) ) :
		foreach ( $raq_data['raq_content'] as $item ) :
			$_product = isset( $item['variation_id'] ) ? wc_get_product( $item['variation_id'] ) : wc_get_product( $item['product_id'] );
			if ( ! $_product ) {
				continue;
			}

			$product_admin_link = '';
			$posttype_object    = get_post_type_object( get_post( $_product->get_id() )->post_type );
			if ( ( $posttype_object ) && ( $posttype_object->_edit_link ) ) {
				$product_admin_link = admin_url( sprintf( $posttype_object->_edit_link . '&action=edit', $_product->get_id() ) );
			}
			?>
			<tr>
				<td scope="col" style="text-align:left;"><a href="<?php echo esc_url( $product_admin_link ); ?>"><?php echo wp_kses_post( $_product->get_title() ); ?></a>
					<?php if ( isset( $item['variations'] ) ) : ?>
						<small><?php echo wp_kses_post( yith_ywraq_get_product_meta( $item ) ); ?></small>
					<?php endif ?>
				</td>
				<td scope="col" style="text-align:left;"><?php echo esc_html( $item['quantity'] ); ?></td>
				<td scope="col" style="text-align:left;"><?php echo $_product->get_price_html() ; ?></td>
			</tr>
			<?php
		endforeach;
	endif;
	?>
	</tbody>
</table>

<?php do_action( 'yith_ywraq_email_after_raq_table', $raq_data ); ?>

<h2><?php esc_html_e( 'Request details', 'stone' ); ?></h2>

<p><strong><?php esc_html_e( 'Project Name:', 'stone' ); ?></strong> <?php echo esc_html( $raq_data['project_name'] ); ?></p>
<p><strong><?php esc_html_e( 'Project Location:', 'stone' ); ?></strong> <?php echo esc_html( $raq_data['project_location'] ); ?></p>
<p><strong><?php esc_html_e( 'Project Timeline:', 'stone' ); ?></strong> <?php echo esc_html( $raq_data['project_timeline'] ); ?></p>

<p><strong><?php esc_html_e( 'Client Name:', 'stone' ); ?></strong> <?php echo esc_html( $raq_data['user_name'] ); ?></p>
<p><strong><?php esc_html_e( 'Client Email:', 'stone' ); ?></strong> <a href="mailto:<?php echo esc_attr( $raq_data['user_email'] ); ?>"><?php echo esc_html( $raq_data['user_email'] ); ?></a></p>

<?php if(is_user_logged_in()) : ?>
<?php $current_user_role = get_user_role(); ?>
<p><strong><?php esc_html_e( 'Client Role:', 'stone' ); ?></strong> <?php echo esc_html( ucfirst($current_user_role) ); ?></p>
<?php endif ?>

<?php if($raq_data['user_phone']) : ?>
	<p><strong><?php esc_html_e( 'Client Phone:', 'stone' ); ?></strong> <a href="tel:<?php echo esc_attr( $raq_data['user_phone'] ); ?>"><?php echo esc_html( $raq_data['user_phone'] ); ?></a></p>
<?php endif; ?>



<?php if ( ! empty( $raq_data['user_message'] ) ) : ?>
<h2><?php esc_html_e( 'Request Notes', 'stone' ); ?></h2>
	<p><?php echo wp_kses_post( $raq_data['user_message'] ); ?></p>
<?php endif ?>

<?php do_action( 'woocommerce_email_footer' ); ?>
