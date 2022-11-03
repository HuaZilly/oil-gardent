<?php
    define("CUSTOM_CSS", "search");

    get_header();

    $post_types = array(
        'bigcommerce_product' => 'Product',
        'post' => 'Blog'
    );
    $searchTerm = get_search_query();
    if ( have_posts() ) :
        ?>
        <section id="hero">
            <div class="inner">
                <h1 class="h3">
                    <?php _e( 'Search results for:', 'twentynineteen' ); ?>
                    <?php echo get_search_query(); ?>
				</h1>
            </div>
        </section>

        <section id="search-results">
            <div class="inner">
                
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        ?>

                        <div class="result">
                            <div class="featured-image">
                                <?php the_post_thumbnail('thumbnail') ?>
                            </div>
                            <div class="detail">
                                <h3><?php echo get_the_title(); ?></h3>
                                <?php the_excerpt() ?>
                            </div>

                            <span class="content-type"><?php echo $post_types[$post->post_type] ?></span>
                            <a href="<?php echo get_permalink(); ?>"></a>
                        </div>

                        <?php
                    endwhile;
                    ?>
                </div>
            </div>
        </section>
    
        <section id="pagination-style">
            <?php echo get_the_posts_pagination( [
                'prev_text'          => __( 'Previous page', 'twentysixteen' ),
                'next_text'          => __( 'Next page', 'twentysixteen' ),
                'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'bigcommerce' ) . ' </span>',
            ] ); ?>
        </section>

<?php
    else :
        echo "<div style='padding: 20% 0;text-align:center;'><h3 >No results found...</h3><a href='".home_url('/products')."' class='btn primary'>Shop Around</a></div>";
    endif;
    ?>

    <?php $searchTag = get_field('search_tag', 'options') ?>
    <?php if($searchTag): ?>
        <?= str_replace('replace_by_search_query', $searchTerm , $searchTag) ?>
    <?php endif; ?>

<?php get_footer(); ?>