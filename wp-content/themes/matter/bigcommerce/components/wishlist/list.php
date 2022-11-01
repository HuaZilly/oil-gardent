<?php

/**
 * @var string[] $wishlists   An array of wishlist rows
 * @var string   $create_list The form to create a new wishlist
 */

?>
<div class="inner">
	<h3>Wishlists</h3>
	<div class="bc-wish-list-header">
		<?php if ( ! empty( $wishlists) ) { ?>
			<div class="bc-wish-list-header-title bc-wish-list-name"><?php _e( 'List', 'bigcommerce' ); ?></div>
			<div class="bc-wish-list-header-title bc-wish-list-item-count"><?php _e( 'Items', 'bigcommerce' ); ?></div>
			<div class="bc-wish-list-header-title bc-wish-list-shared"><?php _e( 'Shared', 'bigcommerce' ); ?></div>
			<div class="bc-wish-list-header-title bc-wish-list-actions u-bc-visual-hide"><?php _e( 'Actions', 'bigcommerce' ); ?></div>
		<?php } else { ?>
			<p><?php _e( 'Click the heart icon on a product page to add items to your wishlist.', 'bigcommerce' ); ?></p>
		<?php } ?>
	</div>

	<div class="bc-wish-list-body">
		<?php foreach ( $wishlists as $list ) { ?>
			<?php echo $list; ?>
		<?php } ?>
	</div>

	<?php echo $create_list; ?>
</div>