<?php
/**
 * Cart Items
 *
 * @package BigCommerce
 *
 * @var array $cart
 * @var string $fallback_image The fallback image to use for items that do not have one
 * @var string $image_size     The image size to use for product images
 */

use BigCommerce\Taxonomies\Brand\Brand;
$itemsArray = [];
?>

<?php foreach ( $cart['items'] as $item ) { ?>
	<div class="bc-cart-item" data-js="<?php echo esc_attr( $item['id'] ); ?>">
		<div class="bc-cart-item-product">
			<div class="bc-cart-item-image">

				<?php if ( ! empty( $item['post_id'] ) ) { ?>
				<a
						href="<?php echo esc_url( get_the_permalink( $item['post_id'] ) ); ?>"
						class="bc-product__thumbnail-link"
				>
					<?php } ?>

					<?php
					echo( has_post_thumbnail( $item['post_id'] ) ? get_the_post_thumbnail( $item['post_id'], $image_size ) : $fallback_image );
					?>

					<?php if ( ! empty( $item['post_id'] ) ) { ?>
				</a>
			<?php } ?>
			</div>
			<div class="bc-cart-item-meta">
				<div class="bc-cart-item__product-title">
					<?php if ( ! empty( $item['post_id'] ) ) { ?>
					<a
							href="<?php echo esc_url( get_the_permalink( $item['post_id'] ) ); ?>"
							class="bc-product__title-link"
					>
						<?php } ?>

						<?php echo esc_html( $item['name'] ); ?>
						<?php if ( $item['show_condition'] && $item['bigcommerce_condition'] ) { ?>
							<span class="bc-product-flag--grey"><?php echo esc_html( $item['bigcommerce_condition'][0]['label'] ); ?></span>
						<?php } ?>

						<?php if ( ! empty( $item['post_id'] ) ) { ?>
					</a>
				<?php } ?>
				</div>
			</div>
		</div>

		<div class="bc-cart-item-quantity">
			<?php
			$max = ( 0 >= $item['maximum_quantity'] ) ? '' : $item['maximum_quantity'];
			$min = ( 0 <= $item['minimum_quantity'] ) ? 1 : $item['minimum_quantity'];
			?>
			<label
					for="bc-cart-item__quantity"
					class="u-bc-screen-reader-text"
			><?php esc_html_e( 'Quantity', 'bigcommerce' ); ?></label>

			<!-- data-js="bc-cart-item__quantity" is required -->
			<input
					type="number"
					name="bc-cart-item__quantity"
					class="bc-cart-item__quantity-input"
					data-js="bc-cart-item__quantity" data-cart_item_id="<?php echo esc_attr( $item['id'] ); ?>"
					value="<?php echo intval( $item['quantity'] ); ?>"
					min="<?php echo esc_attr( $min ); ?>"
					max="<?php echo esc_attr( $max ); ?>"
			>
		</div>

		<?php $price_classes = $item['on_sale'] ? 'bc-cart-item-total-price bc-cart-item--on-sale' : 'bc-cart-item-total-price'; ?>
		<div class="<?php echo esc_attr( $price_classes ); ?>">
			<?php echo esc_html( $item['total_sale_price']['formatted'] ); ?>
		</div>

		<div class="bc-cart-item-remove">
			<!-- data-js="remove-cart-item" and class="bc-cart-item__remove-button" are required -->
			<button
				class="bc-link bc-cart-item__remove-button"
				data-js="remove-cart-item"
				data-cart_item_id="<?php echo esc_attr( $item['id'] ); ?>"
				type="button"
			>
				<?php esc_html_e( 'Remove', 'bigcommerce' ); ?>
			</button>
		</div>
		
	</div>
    <?php
    $post_term_list = wp_get_post_terms( $item['post_id'], 'bigcommerce_category', array( 'fields' => 'all' ) );
    $categories = [];
    foreach($post_term_list as $term) {
        if (strpos($term->name, 'Select') === false) {
            $categories[] = $term->name;
        }
    }


    $productLoad = new \BigCommerce\Post_Types\Product\Product($item['post_id']);


    $itemCustom['product'] = [
        "id" => (string)$item['product_id'],
        "name" => $item['name'],
        "taxonomy" => $categories,
        "currency" => "AUD",
        "unit_price" => $productLoad->price,
        "unit_sale_price" => $item['sale_price']['raw'],
        "url" =>  esc_url( get_the_permalink( $item['post_id'] ) ),
        "stock" => $productLoad->inventory_level,
        "product_image_url" =>  (string)$productLoad->images[0]->url_standard
    ];
    $itemCustom['quantity'] = $item['quantity'];
    $itemCustom['subtotal'] = $item['total_list_price']['raw'];
    $itemsArray[] = $itemCustom;
    ?>
<?php } ?>

<script type="text/javascript">
    window.insider_object.basket = {
            "currency": "AUD",
            "total": <?php echo $cart['base_amount']['raw'];?>,
            "line_items": <?php echo json_encode($itemsArray); ?>
    }
</script>