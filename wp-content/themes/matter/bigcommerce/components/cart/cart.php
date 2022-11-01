<?php
/**
 * Cart
 *
 * @package BigCommerce
 *
 * @var array  $cart
 * @var string $error_message The error message container
 * @var string $header        The cart table layout header
 * @var string $items         The cart items
 * @var string $footer        The cart table layout footer
 */

?>
<!-- data-js="bc-cart" is required -->
<section class="bc-cart" data-js="bc-cart" data-cart_id="<?php echo esc_attr( $cart['cart_id'] ); ?>">
	<div class="inner">
		<h3>Bag</h3>
		<?php echo $error_message; ?>
		<div class="bc-cart-container">
			<div class="bc-cart-items">
				<?php 
					echo $header;
					echo $items;
				?>
			</div>
			
			<div class="bc-cart-summary">
				<?php echo $footer; ?>
			</div>
			
		</div>
	</div>
</section>
