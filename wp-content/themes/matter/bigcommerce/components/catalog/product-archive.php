<?php
/**
 * The template for rendering the product archive page content
 *
 * @var string[] $posts
 * @var string   $no_results
 * @var string   $title
 * @var string   $description
 * @var string   $refinery
 * @var string   $pagination
 * @var string   $columns
 */

$obj = get_queried_object();
$term_parent_id = (get_class($obj) === "WP_Term") ? $obj->parent : 0;
$term_title = (get_class($obj) === "WP_Term") ? $obj->name : $title;

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

<section class="bc-product-archive-banner">
    <?php
    $taxonomySlug = get_queried_object()->slug;
    $cc = 0;
    if(!empty(get_field('banners', 'options'))){
    foreach(get_field( 'banners', 'options' ) as $banner){
        if($banner['slug'] == $taxonomySlug){
            $cc++;
            ?>
            <div class="inner">
                <div class="content">
                    <h1 class="heading <?php if(isset($banner['text_color'])) { echo 'txt-color-'.strtolower($banner['text_color']); } ?>"><?php echo $banner['title']; ?>.</h1>
                    <p class="<?php if(isset($banner['text_color'])) { echo 'txt-color-'.strtolower($banner['text_color']); } ?>"><?php echo $banner[ 'description' ]; ?></p>
                </div>
            </div>
            <?php
            $featuredimage_url = wp_get_attachment_image_src($banner['background_image']['ID'], 'full' , true)[0];
            $featuredimage_url_mobile = wp_get_attachment_image_src($banner['background_image_mobile']['ID'], 'full' , true)[0];
            ?>
            <div class="img">
                <img class="background-cover desktop" loading="lazy" src="<?php echo $featuredimage_url; ?>" />
                <img class="background-cover mobile" loading="lazy" src="<?php echo $featuredimage_url_mobile; ?>" />
            </div>
            <?php
            break;
        }

    }
    }
    if($cc == 0){
        ?>
        <?php if(is_post_type_archive( 'bigcommerce_product' )){ ?>
            <div class="inner">
                <div class="content">
                    <h1 class="heading <?php if(isset(get_field( 'shop_banner', 'options' )['text_color'])) { echo 'txt-color-'.strtolower(get_field( 'shop_banner', 'options' )['text_color']); } ?>"><?php echo get_field( 'shop_banner', 'options' )['title']; ?>.</h1>
                    <p class="<?php if(isset(get_field( 'shop_banner', 'options' )['text_color'])) { echo 'txt-color-'.strtolower(get_field( 'shop_banner', 'options' )['text_color']); } ?>"><?php echo get_field( 'shop_banner', 'options' )['decription']; ?></p>
                </div>
            </div>
            <?php
            $featuredimage_url = wp_get_attachment_image_src(get_field( 'shop_banner', 'options' )['background_image']['ID'], 'full' , true)[0];
            $featuredimage_url_mobile = wp_get_attachment_image_src(get_field( 'shop_banner', 'options' )['background_image_mobile']['ID'], 'full' , true)[0];
            ?>
            <div class="img">
                <img class="background-cover desktop" loading="lazy" src="<?php echo $featuredimage_url; ?>" />
                <img class="background-cover mobile" loading="lazy" src="<?php echo $featuredimage_url_mobile; ?>" />
            </div>
        <?php } else {   ?>
            <div class="inner">
                <div class="content">
                    <h1 class="heading"><?php
                        echo str_replace('Category: ', '', $title); ?>.</h1>
                    <p><?php echo esc_html( $description ); ?></p>
                </div>
            </div>
            <div class="img"></div>
        <?php }  ?>
    <?php }  ?>
</section>
<section class="bc-product-archive">
	<div class="inner">
		<div id="breadcrumbs">
			<a href="/products/">Shop</a>
			<span> / </span>
			<?php echo traverseCats( $term_parent_id,  esc_html( $term_title ) ); ?>
		</div>

		<div class="bc-product-archive__header">
			<div class="bc-product-archive__heading">
				<h1 class="h3"><?php echo esc_html( $term_title ); ?></h1>
				<div><?php echo esc_html( $description ); ?></div>
			</div>
			<?php echo $refinery; ?>
		</div>

		<section class="bc-product-grid bc-product-grid--archive bc-product-grid--<?php echo esc_attr( $columns ); ?>col facetwp-template">
            <script type="text/javascript">
                window.insider_object.listing = {
                    "items": []
                }
            </script>

            <?php
            $itemsArray = [];
			if ( ! empty( $posts ) ) {
				foreach ( $posts as $post ) {
					echo $post;
				}
                global $wp_session; ?>
                <script type="text/javascript">
                    window.insider_object.listing.items = <?php echo substr(json_encode($wp_session['items']), 1, -1); ?>
                </script>
                <?php
            } else {
				echo $no_results;
			}
			?>
		</section>

		<?php echo $pagination; ?>

	</div>
</section>
