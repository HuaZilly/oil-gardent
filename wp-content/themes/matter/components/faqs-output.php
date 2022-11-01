

<?php 

$args = array(
    'post_type'=> 'faqs',
    'order'    => 'ASC',
    'post_status' => 'publish',
    'posts_per_page' => -1
);              

$faqs = new WP_Query( $args );


if( $faqs->have_posts() ) :

          while( $faqs->have_posts() ) :
            $faqs->the_post();
            ?>
            <div class="question">
                <h5 data-imgplus="<?php echo get_template_directory_uri().'/images/global/plus-green.png'; ?>" data-imgminor="<?php echo get_template_directory_uri().'/images/global/minor-green.png'; ?>"><?php the_title(); ?></h5>
                <div class="answer content">
                    <?php the_content(); ?>
                </div>
            </div>
            <?php
          endwhile;
          wp_reset_postdata();

else :
    esc_html_e( 'No FAQs in the diving taxonomy!', 'oilgarden' );
endif;

?>


