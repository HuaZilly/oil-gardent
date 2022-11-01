<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

get_header();

    /* Start the Loop */
    while ( have_posts() ) :
        the_post();
        the_content();
    endwhile; // End of the loop.
global $no_footer;
if ($no_footer !== true) {
    get_footer();
} else {
    get_footer('lite');
}
