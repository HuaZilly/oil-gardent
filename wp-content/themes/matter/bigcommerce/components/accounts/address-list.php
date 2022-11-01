<?php
/**
 * Address List Template
 *
 * @var array $addresses
 * @var string $new_address
 */

?>

<div class="bc-account-page">
	<div class="inner">
		<h3>Addresses</h3>

		<!-- class="bc-account-addresses__list" is required -->
		<ul class="bc-account-addresses__list">
			<?php foreach ( $addresses as $address ) { ?>
				<!-- class="bc-account-addresses__item" is required -->
				<li class="bc-account-addresses__item" data-js="bc-account-address-entry">

					<?php echo $address[ 'formatted' ]; ?>
					<?php echo $address[ 'actions' ]; ?>

				</li>
			<?php } ?>

			<!-- class="bc-account-addresses__item" is required -->
			<li class="bc-account-addresses__item bc-account-addresses__add-new" data-js="bc-account-addresses__add-new">
				<?php echo $new_address; ?>
			</li>
		</ul>
	</div>
</div>