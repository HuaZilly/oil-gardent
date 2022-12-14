<?php

/**
 * @var string $label         The button label
 * @var string $attributes    The rendered attributes for the button
 * @var string $form_template The rendered template for the wishlist form
 */

?>
<button <?php echo $attributes; ?> class="btn primary bc-wish-list-btn--new"><?php echo esc_html( $label ); ?></button>
<?php echo $form_template; ?>
