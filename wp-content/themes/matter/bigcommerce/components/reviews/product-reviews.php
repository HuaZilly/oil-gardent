<?php

/**
 * @var string   $header   Rendered summary header
 * @var string   $form     Rendered product review form. May be empty if reviewing is disabled.
 * @var string   $reviews  Rendered product reviews
 */
global $md_review_count;
?>

<div class="inner">
    <div class="bc-product-reviews__top">
        <?php echo $header; ?>
        <?php if ($form) : ?>
        <?php echo $form; ?>
        <?php else : ?>
        <?php if ($md_review_count > 1) : ?><div class="bc-product-review-form-wrapper" data-js="bc-product-review-form-wrapper">
            <div class="actions">
	            <span class="btn secondary" id="see-all-reviews">See all reviews</span>
            </div>
        </div><?php endif; ?>
        <?php endif; ?>
    </div>

    <?php echo $reviews; ?>
</div>