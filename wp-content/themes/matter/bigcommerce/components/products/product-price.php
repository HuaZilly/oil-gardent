<?php
/**
 * Component: Product Price
 *
 * @description Display the price for a product
 *
 * @var Product $product
 * @var string  $visible HTML class name to indicate if default pricing should be visible
 * @var string  $price_range
 * @var string  $calculated_price_range
 */

use BigCommerce\Post_Types\Product\Product;
use BigCommerce\Accounts\Customer;

global $post;
$customer = new Customer( get_current_user_id() ); 
$group_info = $customer->get_group()->get_info();
$customer_discounted_category = $group_info['discount_rules'][0]['category_id'];
$customer_discount = $group_info['discount_rules'][0]['amount'];
$discountArray = array();
if(isset($group_info['discount_rules'])) {
    foreach ($group_info['discount_rules'] as $rules) {
        $discountArray[$rules['category_id']] = $rules['amount'];
    }
}
$bc_category_id = get_term_meta( get_queried_object_id(), 'bigcommerce_id', true);

// print_r($product);
$post_term_list = wp_get_post_terms( $post->ID, 'bigcommerce_category', array( 'fields' => 'all' ) );
$discounted_category = array();
$categories = [];
	foreach($post_term_list as $term) {
        //print_r($term);
        $categories[] = $term->name;
		$bc_category_term_id = get_term_meta( $term->term_id, 'bigcommerce_id', true);
		//if ($customer_discounted_category === $bc_category_term_id) {
        if (isset($discountArray[$bc_category_term_id])) {
            $customer_discount = $discountArray[$bc_category_term_id];
			array_push($discounted_category, $term->term_id);
		}
	}
echo '<div style="display: none">';
print_r($discounted_category[0]);
echo '</div>';
	if(count($discounted_category) > 0) {
    $discounted_category = $discounted_category[0];
}
echo '<div style="display: none">';
print_r($discounted_category);
echo '</div>';
//$discounted_category = implode('', $discounted_category);
$discounted_category_slug = get_term($discounted_category, 'bigcommerce_category');
$discounted_category_name = $discounted_category_slug->slug;
$salePriceSend = $product->price;

?>


<div style="display: none"><?php print_r($discounted_category_name); ?></div>

<div style="display: none"><?php print_r($group_info); ?></div>
<!-- data-js="bc-cached-product-pricing" is required. -->
<p class="bc-product__pricing--cached <?php echo sanitize_html_class( $visible ); ?>" data-js="bc-cached-product-pricing">
<?php if ( $product->on_sale() ) { ?>
	<!-- class="bc-product__price" is required. -->
	<span class="bc-product__price bc-product__price--sale">
		<?php echo esc_html( $calculated_price_range ); ?>
	</span>
	<!-- class="bc-product__original-price" is required. -->
	<span class="bc-product__original-price"><?php echo esc_html( $price_range ) ?></span>
<?php } else if ( $customer_discount && $discounted_category_name ) { ?>
    <?php if(is_single() && has_term($discounted_category_name, 'bigcommerce_category')) { ?>
		<!-- class="bc-product__original-price" is required. -->
		<span class="bc-product__original-price"><?php echo esc_html( $price_range ) ?></span>
		<!-- class="bc-product__price" is required. -->
		<span class="bc-product__price bc-product__price--sale">
			<?php 
				$customer_sale_price = $product->price - (($customer_discount / 100) * $product->price); 
				echo floor(($customer_sale_price*100))/100;
			?> 
		</span>
    <?php } else { ?>
        <?php if ( has_term($discounted_category_name, 'bigcommerce_category')) { ?>

            <!-- class="bc-product__original-price" is required. -->
            <span class="bc-product__original-price"><?php echo esc_html( $price_range ) ?></span>
            <!-- class="bc-product__price" is required. -->
            <span class="bc-product__price bc-product__price--sale">
                <?php 
					$customer_sale_price = $product->price - (($customer_discount / 100) * $product->price); 
					echo floor(($customer_sale_price*100))/100;
                ?>
            </span>
        <?php } else { ?>
			 <!-- class="bc-product__price" is required. -->
			 <span class="bc-product__price"><?php echo esc_html( $calculated_price_range ); ?></span>
		<?php } ?>
    <?php } ?>
<?php } else { ?>
	<!-- class="bc-product__price" is required. -->
	<span class="bc-product__price"><?php echo esc_html( $calculated_price_range ); ?></span>
<?php } ?>
</p>

 <?php if(is_single()) { ?>
<script type="text/javascript">
    window.insider_object.page = {
            "type": "Product"
    }
    window.insider_object.product =  {
        "id": "<?php echo $product->bc_id(); ?>",
        "name": "<?php the_title(); ?>",
        "taxonomy": <?php echo json_encode($categories); ?>,
        "currency": "AUD",
        "unit_price": <?php echo $product->price; ?>,
        "unit_sale_price": <?php echo $salePriceSend; ?>,
        "url": "<?php echo  (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>",
        "stock": <?php echo $product->inventory_level; ?>,
        "product_image_url": "<?php echo $product->images[0]->url_standard; ?>"
    }
</script>
<?php } else {

     global $wp_session;
     $itemsArray[] = [
         "id" => (string)$product->bc_id(),
         "name" => $product->name,
         "taxonomy" => $categories,
         "currency" => "AUD",
         "unit_price" => $product->price,
         "unit_sale_price" => $salePriceSend,
         "url" => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
         "stock" => $product->inventory_level,
         "product_image_url" =>  $product->images[0]->url_standard
     ];
     $wp_session['items'][] = $itemsArray;
     ?>


<?php } ?>
