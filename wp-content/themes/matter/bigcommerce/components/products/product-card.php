<?php
/**
 * Product Card used in loops and grids.
 *
 * @package BigCommerce
 * @since v1.7
 *
 * @var Product $product
 * @var string  $title
 * @var string  $brand
 * @var string  $image
 * @var string  $price
 * @var string  $quick_view @deprecated since 3.1, @see quick-view-image.php
 * @var string  $attributes @deprecated since 3.1, @see quick-view-image.php
 */

use BigCommerce\Post_Types\Product\Product;
use BigCommerce\Templates\Product_Rating;
// use BigCommerce\Accounts\Customer;
// use BigCommerce\Accounts\Wishlists\Wishlist as Account_Wishlist;
// use BigCommerce\Api\v3\Api\WishlistsApi;
// use BigCommerce\Api\v3\ApiException;
// use BigCommerce\Api\v3\Model\Wishlist as Api_Wishlist;
// use BigCommerce\Templates\Wishlist_Add_Item;

/* Pull except from ACF */
$excerpt = get_field('category_excerpt');

/* Expose rating */
$component = Product_Rating::factory( [
	Product_Rating::PRODUCT => $product,
	Product_Rating::LINK    => get_the_permalink( $product->post_id() ) . '#bc-single-product__reviews',
] );

$rating = $component->render();

/* Expose add to wishlist functionality */
// if (!function_exists("get_wishlists")) {
// 	function get_wishlists( $customer_id ) {
// 		try {
// 			return array_map( function ( Api_Wishlist $wishlist ) {
// 				return new Account_Wishlist( $wishlist );
// 			}, array() );
// 		} catch (ApiException $e) {
// 			return [];
// 		}
// 	}
// }

// if (!function_exists('wishlist_output')) {
// 	function wishlist_output($product) {
// 		if ( ! is_user_logged_in() ) {
// 			return null;
// 		}
// 		$customer    = new Customer( get_current_user_id() );

// 		$customer_id = $customer->get_customer_id();
// 		if ( empty( $customer_id ) ) {
// 			return null;
// 		}
// 		$wishlists  = get_wishlists( $customer_id );
// 		$component = Wishlist_Add_Item::factory( [
// 			Wishlist_Add_Item::PRODUCT_ID => $product->bc_id(),
// 			Wishlist_Add_Item::WISHLISTS  => $wishlists,
// 		] );

// 		return $component->render();
// 	}
// }

// $wishlist = wishlist_output($product);

?>
<?php echo $image; ?>
<div class="bc-product__flip">
<?php echo $title; ?>
	<div class="bc-product__meta">
        <div class="review">
            <?php
            $starRating = get_field('star_rating_bottomline', 'options');
            ?>
            <div class="rating-star">
                <?php if ($starRating) :?>
                    <?=
                    str_replace('%product.id', $product->bc_id(), $starRating);
                    ?>
                <?php endif; ?>
            </div>
        </div>
		<div class="bc-product__description"><?php echo ($excerpt !== "" && $excerpt !== NULL) ? "<p>".$excerpt."</p>" : ((strstr($product->description, "<p>") > -1) ? $product->description : "<p>".$product->description."</p>"); ?></div>
	</div>
    <div class="action-container">
        <?php echo $price; ?>

        <?php if ( ! empty( $form ) ) { ?>
            <!-- data-js="bc-product-group-actions" is required -->
            <div class="bc-product__actions" data-js="bc-product-group-actions">
                <?php echo $form; ?>
                <?php //echo $wishlist ?>
            </div>
        <?php } else if ($product->out_of_stock()) { ?>
            <div class="bc-product__actions">
                <div class="btn disabled">Out Of Stock</div>
            </div>
        <?php } ?>
    </div>
</div>