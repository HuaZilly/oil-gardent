<?php

use BigCommerce\Accounts\Wishlists\Wishlist;
use BigCommerce\Post_Types\Product\Product;

/**
 * Template for a single wishlist product row
 *
 * @var Product  $product
 * @var Wishlist $wishlist
 * @var string   $title
 * @var int      $image_id
 * @var string   $image
 * @var string   $price
 * @var string   $sku
 * @var string[] $bigcommerce_brand
 * @var string[] $bigcommerce_condition
 * @var string   $permalink
 * @var string   $delete URL to remove the product from the wishlist
 */

$excerpt = get_field('category_excerpt');

?>
<?php echo $image; ?>
<div class="bc-product__flip">
	<h3 class="bc-product__title">
		<?php if ( $permalink ) { ?>
		<a href="<?php echo esc_url( $permalink ); ?>" class="bc-product__title-link">
			<?php } echo esc_html( $title ); if ( $permalink ) { ?>
		</a>
		<?php } ?>
	</h3>
	<div class="bc-product__meta">
		<div class="bc-product__description"><?php echo ($excerpt !== "" && $excerpt !== NULL) ? "<p>".$excerpt."</p>" : $product->description; ?></div>
		<div class="bc-product__pricing">
			<p class="bc-product__pricing--cached bc-product__pricing--visible">
				<!-- class="bc-product__price" is required. -->
				<span class="bc-product__price"><?php echo esc_html( $price ); ?></span>
			</p>
		</div>
		
	</div>

	<!-- data-js="bc-product-group-actions" is required -->
	<div class="bc-product__actions" data-js="bc-product-group-actions">
		<div class="bc-wish-list-product-row__delete"><a href="<?php echo esc_url( $delete ); ?>" class="bc-link"><?php _e( 'Remove from wishlist', 'bigcommerce' ); ?></a></div>
	</div>
</div>

