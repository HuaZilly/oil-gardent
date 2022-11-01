<?php 

define("CUSTOM_CSS", "recipes");
get_header() ?>

<section id="page-head">
    <div class="inner">
        <h1 class="h2">Recipes</h1>
        <div class="content">
            <div class="block">
                <h3><?php echo get_field('blurb_headline', 'option'); ?></h3>
            </div>
            <div class="block">
                <p><?php echo get_field('blurb_content', 'option'); ?></p>
            </div>
        </div>
    </div>
</section>

<section id="recipes">
    <div class="inner">
        <?php
        /* Start the Loop */
        while ( have_posts() ) : the_post();
            ?>
            <div class="recipe">
                <div class="feature-image" style="background-image: url(<?php echo get_the_post_thumbnail_url() ?>);"></div>
                <div class="detail">
                    <div>
                        <h4><?php the_title() ?></h4>
                        <?php the_excerpt() ?>
                    </div>
                </div>
                <a href="<?php echo get_permalink(); ?>"></a>
            </div>
            <?php
        endwhile; // End of the loop.
        ?>
    </div>
</section>

<?php get_footer() ?>