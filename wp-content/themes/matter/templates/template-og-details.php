<?php /* Template Name: Oil Garden Details */ 
    define("CUSTOM_CSS", "template-og-details");
    define("CUSTOM_JS", "template-og-details");

    get_header();

    if (have_posts()) :
        while ( have_posts() ) :
            the_post();
?>
<?php 
    $menu           = get_field('menu'); 
    $hero_banner    = get_field('hero_banner');
?>

<?php if($hero_banner['enable_hero_banner']): ?>

    <?php 
        $imgBackgroundURL = $hero_banner['background_image_banner']; 
        if($imgBackgroundURL):
            $backgroudImage = "background-image: url('".$imgBackgroundURL."')";
        else:
            $backgroudImage = '';
        endif;
    ?>
    <section id="hero-section" style="<?php echo $backgroudImage; ?>">
        <div class="inner hero-title">
            <h2><?php the_title(); ?></h2>     
        </div>
    </section>
<?php endif; ?>

<section class="body-og-details">

    <div class="inner row">
        

        <?php if($menu['enable_submenu']): ?>
            <div class="menu cols">
                
                <?php 
                    $menu_data = get_field("menu");
                    if ($menu_data['enable_submenu'] && $menu_data['select_menu']) :

                        wp_nav_menu(
                            array(
                                'theme_location'    => $menu_data['select_menu'],
                                'menu_class'        => 'sub-menu',
                                'items_wrap'        => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                            )
                        );

                    endif;
                ?>
            </div>
        <?php else: $withoutMargin = 'margin-left: 0;'; endif;?>

        <div class="body-details cols" style="<?php echo $withoutMargin; ?>" >
                <?php the_content(); ?>
                <?php //include(get_stylesheet_directory() . '/components/contact-form-output.php'); ?>
        </div>
        
    </div>

</section>


<?php 
        endwhile;
    endif;
    get_footer();
?>