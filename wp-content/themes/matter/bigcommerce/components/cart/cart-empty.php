<?php
/**
 * Empty Cart
 *
 * @package BigCommerce
 *
 */
?>

<section class="bc-cart" data-js="bc-cart" data-cart_id="<?php echo esc_attr( $cart['cart_id'] ); ?>">
	<div class="inner">
		<?php include 'cart-error-message.php'; ?>
		<div class="bc-cart__empty">
			<h3 class="bc-cart__title--empty"><?php _e( 'Your cart is empty.', 'bigcommerce' ); ?></h3>
			<h6><a href="<?php echo esc_url( home_url('/products/') ); ?>" class="bc-cart__continue-shopping"><?php esc_html_e( 'Take a look around.', 'bigcpommerce' ); ?></a></h6>
		</div>
	</div>
</section>
