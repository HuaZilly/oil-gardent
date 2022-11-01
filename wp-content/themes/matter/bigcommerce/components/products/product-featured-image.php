<?php
/**
 * @var Product $product
 * @var int     $attachment_id
 * @var string  $size
 * @var string  $image
 */

use BigCommerce\Post_Types\Product\Product;

$custom_fields = array();
$flag_color = '';
$flag_text = '';

foreach ($product->custom_fields as $cf) $custom_fields[strtolower($cf->name)] = strtolower($cf->value);
?>


<div class="bc-product-card__featured-image">
	<?php if ( $product->on_sale() ) { ?>
		<span class="bc-product-flag--sale"><?php esc_html_e( 'SALE', 'bigcommerce' ); ?></span>
	<?php } else if ( isset($custom_fields['flag']) ) {
		if ($custom_fields['flag'] === 'best seller' ) { ?>
		<span class="bc-product-flag--seller"><?php esc_html_e( 'BEST SELLER', 'bigcommerce' ); ?></span>
	<?php } else if ( $custom_fields['flag'] === 'new' ) { ?>
		<span class="bc-product-flag--new"><?php esc_html_e( 'NEW', 'bigcommerce' ); ?></span>
	<?php } else if ( $custom_fields['flag'] === 'limited edition' ) { ?>
		<span class="bc-product-flag--limited"><?php esc_html_e( 'LIMIED EDITION', 'bigcommerce' ); ?></span>
	<?php }else if ( $custom_fields['flag'] === 'custom' ){ 
			if( isset($custom_fields['flag-color']) )
				$flag_color = $custom_fields['flag-color'];
			
			if( isset($custom_fields['flag-text']) )
				$flag_text = $custom_fields['flag-text'];
		
	?>

			<span class="bc-product-flag--custom" style="<?php echo ($flag_color !== '') ? 'background-color: ' . $flag_color . ';' : ''; ?>"><?php esc_html_e( $flag_text, 'bigcommerce' ); ?></span>
	<?php } ?>
	<?php } ?>
	

	<?php
	echo $image;
	//echo get_the_post_thumbnail($product->post_id(), 'large');
	?>
</div>
