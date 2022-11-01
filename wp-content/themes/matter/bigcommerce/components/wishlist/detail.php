<?php

use BigCommerce\Accounts\Wishlists\Wishlist;

/**
 * @var Wishlist $wishlist   The wishlist to display
 * @var string[] $products   The rendered product rows
 * @var string   $breadcrumb The rendered breadcrumb HTML
 * @var string   $header     The rendered header HTML
 */

?>

<div class="inner">
	<?php echo $breadcrumb; ?>
	<?php echo $header; ?>

	<section class="bc-product-archive bc-product-grid bc-product-grid--archive bc-product-grid--3col facetwp-template">
		<?php if (count($products) > 0) : ?>
		<?php foreach ( $products as $product ) { ?>
			<div class="bc-product-card">
			<?php echo $product; ?>
			</div>
		<?php } ?>
		<?php else : ?>
			<p>No items in your wishlist. Click the heart icon on a product page to add items to your wishlist.</p>
		<?php endif; ?>
	</section>

</div>