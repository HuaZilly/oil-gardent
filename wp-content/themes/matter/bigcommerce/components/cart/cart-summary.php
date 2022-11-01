<?php
/**
 * Cart Summary
 *
 * @package BigCommerce
 *
 * @var array $cart
 */

?>
<!-- class="bc-cart-subtotal" is required -->
<div class="bc-cart-summary-detail">
	<h6>ORDER SUMMARY</h6>

	<?php if ( $cart['tax_amount']['raw'] > 0 ) { ?>
		<!-- class="bc-cart-tax" is required -->
		<div class="bc-cart-tax">
			<span class="bc-cart-tax__label"><?php echo esc_html( $cart['tax_included'] ? __( 'Tax Included: ', 'bigcommerce' ) : __( 'Tax: ', 'bigcommerce' ) ); ?></span>
			<!-- class="bc-cart-tax__amount" is required -->
			<span class="bc-cart-tax__amount"><?php echo esc_html( $cart['tax_amount']['formatted'] ); ?></span>
		</div>
	<?php } ?>

	<div class="bc-cart-subtotal">
		<span class="bc-cart-subtotal__label"><?php esc_html_e( 'Subtotal: ', 'bigcommerce' ); ?></span>
		<!-- class="bc-cart-subtotal__amount" is required -->
		<span class="bc-cart-subtotal__amount"><?php echo esc_html( $cart['subtotal']['formatted'] ); ?></span>
	</div>
	
</div>
<?php
$customer_name = '';
if ( class_exists( '\BigCommerce\Accounts\Customer' ) ) {
	$wp_user_id = get_current_user_id();
	$customer   = new \BigCommerce\Accounts\Customer( $wp_user_id );
	if ( ! empty( $customer ) ) {
	 	$profile = $customer->get_profile();
	 	if ( ! empty( $profile ) &&  ! empty( $profile['first_name'] ) ) {
	 		$customer_name = $profile['first_name'] . ' ' . $profile['last_name'];
	 	}
	}
}
?>

<?php if ( 'Fast Testing' === $customer_name ) : ?>

<?php
$bigcommerce = bigcommerce();
$container   = $bigcommerce->container();
$cart_id     = '';
if ( ! empty( $container['api.factory'] ) ) {
	$api_factory = $container['api.factory'];
	$cart_api    = $api_factory->cart();
	$cart        = new \BigCommerce\Cart\Cart( $cart_api );
	$cart_id     = $cart->get_cart_id();
}
?>
<?php if ( ! empty( $cart_id ) ) : ?>
						<!------ FAST CHECKOUT BUTTON START ----------->
 						<div class="fast-wrapper">
							<div class="fast-or">OR</div>
							<fast-checkout-cart-button cart_id="<?php echo esc_attr( $cart_id ); ?>" app_id="436cec0c-92da-4744-a496-a0ceeca52f35"/>
						</div>
						<style>
						.fast-wrapper {
							clear: both;
							border-bottom: 1px solid #dfdfdf;
							border-radius: none;
							padding-bottom: 20px;
							padding-top: 5px;
							margin-top: 15px;
							margin-bottom: -10px !important;
						}
						.fast-or {
							position: relative;
							top: 76px;
							background: white;
							width: 40px;
							text-align: center;
							margin-left: auto;
							margin-right: auto;
							color: #757575;
						}
						@media (min-width: 551px) {
							.fast-wrapper {
								margin-left: auto;
								margin-right: 0;
								width: 100%;
								border: 1px solid #dfdfdf;
								padding-left: 10%;
								padding-right: 10%;
								padding-bottom: 20px;
								border-radius: 5px;
										width: 25rem;
										/*width: 26.33rem;*/
							}
						}
						@media (min-width: 801px) {
							.fast-wrapper {
										width: 25rem;
								/*width: 27.66rem;*/
							}
						}
						@media (min-width: 1261px) {
							.fast-wrapper {
										width: 25rem;
								/*width: 35.33rem;*/
								
							}
						}
						</style>

						<script src="https://js.fast.co/fast-bigcommerce.js"></script>
<script type="text/javascript">

    var fastText = document.querySelectorAll('fast-checkout-cart-button');
    fastText.forEach(el => {
        el.shadowRoot.lastElementChild.lastElementChild.setAttribute('style', 'font-size: 17px; border-radius: 0;');
    });
                        
</script>
						<!------ FAST CHECKOUT BUTTON END ----------->
<?php endif; ?>

<?php endif; ?>
