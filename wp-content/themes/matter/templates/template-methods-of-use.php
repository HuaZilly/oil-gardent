<?php /* Template Name: Methods Of Use */ 
    define("CUSTOM_CSS", "template-methods");
    define("CUSTOM_JS", "template-methods");

    get_header();

    if (have_posts()) :
        while ( have_posts() ) :
            the_post();


            $hero_banner        = get_field('hero_banner');

            $title_section_1    = get_field('title_section_1');
            $body_2_cols_1      = get_field('body_content_two_cols');
        
            $title_section_2    = get_field('title_section_2');
            $body_multiple_cols = get_field('body_content_multiple_cols');
        
            $title_section_3    = get_field('title_section_3');
            $body_2_cols_2      = get_field('body_content_two_cols_after_multiple_cols');
?>

    <?php if($hero_banner['enable_hero_banner']):
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

    <?php 
    if($body_2_cols_1): 
        $count = 1;
        foreach($body_2_cols_1 as $b2c1): 
?>
        <section class='section two-column<?php echo ($b2c1['overlap_section']) ? ' overlap-section' : ''; ?><?php echo ($b2c1['overlap_next_section']) ? ' overlap-next-section ' : ''; ?><?php echo ($count%2==0) ? ' altbg' : ''; ?>'>
            <div class="inner">
                <?php if($b2c1['content_col_2']['main_title']): ?>
                    <h2 class="title-for-mobile"><?php echo $b2c1['content_col_2']['main_title']; ?></h2>
                <?php endif; ?>

                <?php
                    $format_col_1 = 'visual overflow';
                    $format_col_2 = 'content';
                    if($b2c1['format'] == 'Image - Content'){
                        $format_col_1 = 'visual overflow';
                        $format_col_2 = 'content';
                    }else if($b2c1['format'] == 'Content - Content'){
                        $format_col_1 = 'content ml-0 pb-20';
                        $format_col_2 = 'content';
                    }else if($b2c1['format'] == 'Content - Image'){
                        $format_col_1 = 'content ml-0 pb-20';
                        $format_col_2 = 'visual overflow';
                    }
                ?>

                <div class="<?php echo $format_col_1; ?>">

                    <?php
                        if($b2c1['content_col_1']['main_title']):
                            echo '<h2>'.$b2c1['content_col_1']['main_title'].'</h2>';
                        endif;
                    ?>

                    <?php
                        if($b2c1['content_col_1']['secondary_title']):
                            echo '<h3>'.$b2c1['content_col_1']['secondary_title'].'</h3>';
                        endif;
                    ?>

                    <?php
                        if($b2c1['content_col_1']['body']):
                            echo $b2c1['content_col_1']['body'];
                        endif;
                    ?>

                    <?php   
                        if($b2c1['content_col_1']['images']):
                            foreach($b2c1['content_col_1']['images'] as $image):
                                echo '<img src="'.$image['image'].'" alt="'.$image['image_description'].'" />';
                            endforeach; 
                        endif;
                    ?>
                </div>
                
                <div class="<?php echo $format_col_2; ?>">
                    <?php if($b2c1['content_col_2']['main_title']): ?>
                        <h2><?php echo $b2c1['content_col_2']['main_title']; ?></h2>
                    <?php endif; ?>
                    <?php if($b2c1['content_col_2']['secondary_title']): ?>
                        <h4 class="uppercase custom-h4"><?php echo $b2c1['content_col_2']['secondary_title']; ?></h4>
                    <?php endif; ?>

                    <?php if($b2c1['content_col_2']['body']): ?>
                        <?php echo $b2c1['content_col_2']['body']; ?>
                    <?php endif; ?>

                    <?php   
                        if($b2c1['content_col_2']['images']):
                            foreach($b2c1['content_col_2']['images'] as $image):
                                echo '<img class="fit-img-on-mobile" src="'.$image['image'].'" alt="'.$image['image_description'].'" />';
                            endforeach; 
                        endif;
                    ?>
                    
                </div>
                
            </div>
        </section>
<?php 
        $count++;
        endforeach;  
    endif; 
?>




<?php if($body_multiple_cols): ?>

<section class="section pair certifications">
        <div class="inner">
            <h3 class="mw-441"><?php echo $title_section_2;  ?></h3>
            <div class="row">
                <?php foreach($body_multiple_cols as $bmc): ?>
                    <div class="item" data-imgplus="<?php echo get_template_directory_uri().'/images/global/plus-green.png'; ?>" data-imgminor="<?php echo get_template_directory_uri().'/images/global/minor-green.png'; ?>">
                        <h4 class="underline"><?php echo $bmc['main_title'];  ?></h4>
                        <div class="item-content">
                        <div class="text">
                            <?php echo $bmc['body'];  ?>
                        </div>
                        <div class="certificates-icons">
                            <?php 
                            if($bmc['images']):
                                foreach($bmc['images'] as $image): 
                            ?>
                                <img src="<?php echo $image['image']; ?>" alt="<?php echo (isset($image['image']['image_description'])) ? $image['image']['image_description'] : ''; ?>" />
                            <?php 
                                endforeach; 
                            endif;
                            ?>
                        </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
</section>

<?php endif; ?>



<?php 
        endwhile;
    endif;
    get_footer();
?>