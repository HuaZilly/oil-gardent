<?php 
define("CUSTOM_CSS", "blog");
get_header();
    ?>

    <section id="blog-posts">
        <div class="inner">
            <h1 class="h3">Blog</h1>
            <?php
            /* Start the Loop */
            while ( have_posts() ) : the_post();
                ?>
                <div class="blog-post">
                    <div class="feature-image" style="background-image: url(<?php echo get_the_post_thumbnail_url() ?>);"></div>
                    <div class="detail">
                        <div>
                            <span class="date"><?php echo get_the_date('d F Y'); ?></span>
                            <h4><?php the_title() ?></h4>
                        </div>
                    </div>
                    <a href="<?php echo get_permalink(); ?>"></a>
                </div>
                <?php
            endwhile; // End of the loop.
            ?>
        </div>
    </section>

    <?php
get_footer();