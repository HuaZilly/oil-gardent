<?php
/**
 * Template for the order history page/shortcode
 *
 * @var string[] $orders Rendered order objects
 * @var string   $pagination
 * @var bool     $wrap
 *
 */

?>

<section class="bc-account-page">
	<div class="inner">
		<h3>Order history</h3>

		<?php if ( $wrap ) { ?>
		<!-- class="bc-load-items" is required -->
			<div class="bc-load-items bc-shortcode-order-list-wrapper">

			<?php if ( ! empty( $pagination ) ) { ?>
				<!-- class="bc-load-items__loader" is required -->
				<div class="bc-load-items__loader"></div>
			<?php } ?>
			<!-- classs="bc-load-items-container" and the conditional class "bc-load-items-container--has-pages are required -->
			<ul class="bc-order-list bc-load-items-container <?php echo( ! empty( $pagination ) ? esc_attr( 'bc-load-items-container--has-pages' ) : '' ); ?>">
		<?php } ?>

		<?php if (count($orders)) : foreach ( $orders as $order ) { ?>
			<li class="bc-order-list__item">
				<?php echo $order; ?>
			</li>
		<?php } else : ?>
			<p>You have not yet made any orders.</p>
		<?php endif; ?>

		<?php echo $pagination; ?>

		<?php if ( $wrap ) { ?>
			</ul>
			</div>
		<?php } ?>
	</div>
</section>