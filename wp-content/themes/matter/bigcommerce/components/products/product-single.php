<?php
/**
 * @var string $images
 * @var string $title
 * @var string $brand
 * @var string $price
 * @var string $rating
 * @var string $form
 * @var string $description
 * @var string $sku
 * @var string $specs
 * @var string $related
 * @var string $reviews
 */

$obj = get_queried_object();
$term = get_the_terms($obj, 'bigcommerce_category')[0];
$term_id = (get_class($term) === "WP_Term") ? $term->term_id : 0;

function traverseCats($term_id, $str = "") {
	if ($term_id === 0) {
		return $str;
	} else {
		$term = get_term_by('id', $term_id, 'bigcommerce_category');
		$str = "<a href='".get_term_link($term_id, 'bigcommerce_category')."'>".$term->name."</a><span> / </span>".$str;
		if ($term->parent === 0) return $str;
		else return traverseCats($term->parent, $str); 
	}
}
?>

<section id="bc-product-single__header" itemscope itemtype="http://schema.org/Product">
	<div class="inner">

		<div class="bc-product-breadcrumb">
			<div id="breadcrumbs">
				<a href="/products/">Shop</a>
				<span> / </span>
				<?php echo traverseCats( $term_id, esc_html( $product->name ) ); ?>
			</div>
		</div>

		<!-- data-js="bc-product-data-wrapper" is required. -->
		<div class="bc-product-single__top" data-js="bc-product-data-wrapper" itemid="<?php the_permalink(  ); ?>">
			<?php echo $images; ?>

			<!-- data-js="bc-product-meta" is required. -->
			<div class="bc-product-single__meta" data-js="bc-product-meta">
				<div>					
					<h1 class="bc-product__title" itemprop="name"><?php the_title(); ?></h1>					
					<div class="bc-product-single__excerpt"><?php echo get_field('excerpt', $obj->ID); ?></div>
					<div class="bc-single-product__ratings">
						<div data-bv-show="rating_summary" data-bv-productId="<?php echo esc_html( $product->bc_id() ); ?>"></div>
					</div>
					<meta itemprop="sku" content="<?php echo $product->sku; ?>">
					<meta itemprop="brand" content="Oil Garden">
					<meta itemprop="description" content="<?php echo strip_tags($product->description); ?>">

					<span class="product-price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">

						<?php echo $price; ?>

							<?php  
								if($product->availability){
									if($product->availability === 'available'){
							?>
									<link itemprop="availability" href="http://schema.org/InStock" />
							<?php
									}else{
							?>
									<link itemprop="availability" href="http://schema.org/OutOfStock" />
							<?php
									}
								}
							?>
							
						<meta itemprop="price" content="<?php echo esc_html( floatval(ltrim($product->price_range(), '$')) ) ?>">	
					</span>

					<?php if (!($product->out_of_stock() || $product->type =='digital')) : ?>
					
					<?php echo $form; ?>
					<?php else : ?>
					<div class="btn disabled">Out Of Stock</div>
					<?php endif; ?>

					<section id="buy-bar">
						<div class="fixed-bar">
							<div class="inner">
								<img src="<?php echo $product->images[0]->url_thumbnail ?>" />
								<div class="cart">
									<h5><?php echo $product->name ?></h5>
									<span class="price"></span>
								</div>
								<?php if (!($product->out_of_stock() || $product->type =='digital')) : ?>
								<div class="faux-cart">
									
									<div id="action-cart" class="btn primary">
										Add to Cart
									</div>
								</div>
								<?php else : ?>
								<div class="faux-cart">
									<div class="btn disabled">Out Of Stock</div>
								</div>
								<?php endif; ?>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>

	</div>
</section>

<?php $product_details = get_field('product_details'); ?>
<section id="bc-single-product__details">
	<div class="inner">
		<?php if ($product_details['include_description']) : ?>
		<div class="section">
			<h4 class="bc-single-product__section-title"><?php echo esc_html__( 'Description', 'bigcommerce' ); ?></h4>
			<?php echo $description; ?>
		</div>
		<?php endif; ?>
		<?php
			if (is_array($product_details['sections']) && !empty($product_details['sections'])) : 
			foreach ($product_details['sections'] as $section) : ?>
		<div class="section">
			<h4 class="bc-single-product__section-title"><?php echo $section['title']; ?></h4>
			<div class="bc-product__description"><?php echo $section['content']; ?></div>
		</div>
		<?php endforeach; endif; ?>
	</div>
</section>

<section class="bc-single-product__reviews" id="bc-single-product__reviews">
	<div class="inner">
		<div data-bv-show="reviews" data-bv-productId="<?php echo esc_html( $product->bc_id() ); ?>"></div>
	</div>
</section>

<section id="bc-single-product__related">
	<div class="inner">
		<h6>You might also like</h6>
		<?php //echo $related; ?>
		<!-- TO BE POPULATED BY INSIDER -->
	</div>
</section>


