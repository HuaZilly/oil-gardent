<?php 

define("CUSTOM_CSS", "recipes");
define("SLICK_CSS",'slick-css');
define("CUSTOM_JS", "slick");

define("SLICK_JS", "slick");
get_header() ?>

<section id="recipe">
    <div class="inner">
        <?php
        /* Start the Loop */
        while ( have_posts() ) : the_post();
            ?>
            <div class="blog-post">
                <div class="header" style="background-image: url(<?php echo get_the_post_thumbnail_url() ?>);">
                    <div>
                        <h1 class="h2"><?php the_title() ?></h1>
                    </div>
                </div>
                <div class="content">
                    <?php the_content(); ?>

                    <div class="recipes">
                        <?php foreach (get_field('recipes') as $recipe) : ?>
                        <div class="recipe">
                            <div class="split">
                                <div class="image">
                                    <img src="<?php echo $recipe['image'] ?>" />
                                </div>
                                <div class="content">
                                    <h3><?php echo $recipe['title'] ?></h3>
                                    <?php echo $recipe['description'] ?>
                                </div>
                            </div>
                            <div class="products ">
                                <?php
                                    $prod_ids = array();
                                    foreach ($recipe['products'] as $p) $prod_ids[] = $p['product']->ID;
                                    echo do_shortcode('[bigcommerce_product post_id="'.implode(',',$prod_ids).'"]');
                                ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php
        endwhile; // End of the loop.
        ?>
    </div>
</section>



<?php get_footer() ?>