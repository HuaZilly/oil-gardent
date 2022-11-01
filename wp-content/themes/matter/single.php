<?php 
define("CUSTOM_CSS", "blog");
define("CUSTOM_JS", "blog-single");

get_header();
    ?>

    <section id="blog-post">
        <div class="inner">
            <?php
            /* Start the Loop */
            while ( have_posts() ) : the_post();
                ?>
                <div class="blog-post">
                    <div class="header">
                        <div>
                            <span class="date"><?php echo get_the_date('d F Y'); ?></span>
                            <h1 class="h2"><?php the_title() ?></h1>
                        </div>
                    </div>
                    <div class="feature-image">
                        <?php the_post_thumbnail() ?>
                    </div>
                    <div class="content">
                        <?php the_content(); ?>
                    </div>
                </div>
                <?php
            endwhile; // End of the loop.
            ?>
        </div>
    </section>

    <?php 
        $products = get_field('products');

        if($products){

    ?>
        <section class="inner" id="products-section">
            <div class="recipe">
                <div class="products ">
                    <?php
                        $prod_ids = array();
                        foreach ($products as $p) $prod_ids[] = $p['product']->ID;
                        echo do_shortcode('[bigcommerce_product post_id="'.implode(',',$prod_ids).'"]');
                    ?>
                </div>
            </div>
        </section>

    <?php 
        }
    ?>

    

    <?php
get_footer();