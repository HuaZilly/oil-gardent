<?php
/**
 * @var float   $stars        The number of stars out of 5
 * @var int     $percentage   The star rating converted to a percentage (e.g., 4.2 stars = 84%)
 * @var int     $review_count The number of reviews the product has received
 * @var Product $product
 */

use BigCommerce\Post_Types\Product\Product;

global $md_review_count;
$md_review_count = $review_count;

?>
<div class="bc-product-reviews__header">
	<h3 class="bc-product-reviews__title"><?php printf( _n( '%d review', '%d reviews', $review_count, 'bigcommerce' ), $review_count ); ?></h3>

	<div class="bc-single-product__rating bc-product-reviews__ratings-total">
		<div class="bc-single-product__rating--mask" style="width: <?php echo (int) $percentage; ?>%">
			<div class="bc-single-product__rating--top">
				<span class="bc-rating-star"></span>
				<span class="bc-rating-star"></span>
				<span class="bc-rating-star"></span>
				<span class="bc-rating-star"></span>
				<span class="bc-rating-star"></span>
			</div>
		</div>
		<div class="bc-single-product__rating--bottom">
			<span class="bc-rating-star"></span>
			<span class="bc-rating-star"></span>
			<span class="bc-rating-star"></span>
			<span class="bc-rating-star"></span>
			<span class="bc-rating-star"></span>
		</div>
	</div>
</div>