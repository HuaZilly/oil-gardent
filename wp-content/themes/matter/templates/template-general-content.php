<?php /* Template Name: General Content */ 
    define("CUSTOM_CSS", "template-general-content");
    define("CUSTOM_JS", "template-general-content");

    get_header();

    if (have_posts()) :
        while ( have_posts() ) :
            the_post();
?>

<?php 
    $hero_banner        = get_field('hero_banner');

    $title_section_1    = get_field('title_section_1');
    $body_2_cols_1      = get_field('body_content_two_cols');

    $title_section_2    = get_field('title_section_2');
    $body_multiple_cols = get_field('body_content_multiple_cols');

    $title_section_3    = get_field('title_section_3');
    $body_2_cols_2      = get_field('body_content_two_cols_after_multiple_cols');
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

<?php 
    if($body_2_cols_1): 
        $count = 1;
        foreach($body_2_cols_1 as $b2c1): 

            $overflow_hidden = $b2c1['overflow_hidden'];
            $overflow = '';
            if($overflow_hidden):
                $overflow = ' overflow_hidden';
            endif;

            $backgroundColor = ' ';
            if($b2c1['background_color'] == 'Alternate'){
                if($count%2==0){
                    $backgroundColor = ' altbg ';
                }
            }elseif($b2c1['background_color'] == 'White'){
                $backgroundColor = ' ';
            }elseif($b2c1['background_color'] == 'Light-Green' ){
                $backgroundColor = ' altbg ';
            };



?>
        <section class='section two-column <?php echo ($b2c1['overlap_section']) ? ' overlap-section' : ''; ?><?php echo $backgroundColor; echo $overflow; ?>'>
            <div class="inner">
                <?php 
                        if($b2c1['format'] == 'Content - Image'):
                            $aux_class = ' content-image';
                            if($b2c1['content_col_1']['main_title']): 
                ?>
                            <h2 class="title-for-mobile"><?php echo $b2c1['content_col_1']['main_title']; ?></h2>
                <?php       
                            endif;
                        endif; 
                ?>

                <?php 
                        if($b2c1['format'] == 'Image - Content'):
                            $aux_class = ' image-content'; 
                            if($b2c1['content_col_2']['main_title']): 
                ?>
                            <h2 class="title-for-mobile"><?php echo $b2c1['content_col_2']['main_title']; ?></h2>
                <?php       
                            endif;
                        endif; 
                ?>

                <?php 
                        if($b2c1['format'] == 'Content - Content'): 
                            $aux_class = ' content-content';
                            if($b2c1['title_section']): 
                ?>
                            <h2 class="title-for-mobile"><?php echo $b2c1['title_section']; ?></h2>
                <?php       
                            endif;
                        endif; 
                ?>

                <?php 
                        if($b2c1['format'] == 'Video - Section' || $b2c1['format'] == 'One - Column'): 
                            $aux_class = ' one-col-section';
                        endif; 
                ?>

                

            </div>
        
            <div class="inner <?php echo $aux_class; ?>">
                

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
                        $format_col_2 = 'visual overflow img-right';
                    }else if($b2c1['format'] == 'Video - Section'){
                        $format_col_1 = 'one-col';
                        $format_col_2 = 'display-none';
                    }else if($b2c1['format'] == 'One - Column'){
                        $format_col_1 = 'one-col';
                        $format_col_2 = 'display-none';
                    }



                ?>

                <div class="<?php echo $format_col_1; ?>">

                    <?php
                        if($b2c1['format'] == 'Video - Section'):
                    ?>
                        <?php if($b2c1['video_section']['video_title']): ?>
                            <h3 class="video-title"><?php echo $b2c1['video_section']['video_title']; ?></h3>
                        <?php endif; ?>
                        <div id="ytplayer" class="video-section" <?php echo ( $b2c1['video_section']['youtube_url'] ) ? ' data-ytv="'.$b2c1['video_section']['youtube_url'].'"' : '' ?>>
                            <img src="<?php echo ($b2c1['video_section']['video_background_image']) ? $b2c1['video_section']['video_background_image'] : ''; ?>" />
                            <div  class="play-icon"></div>
                        </div>
                    <?php
                        endif;
                    ?>

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
        <div class="outer">
            <h3 class="mw-441"><?php echo $title_section_2;  ?></h3>
            <div class="row">
                <?php 
                    $count = 0;
                    foreach($body_multiple_cols as $bmc): 
                        $count++;
                ?>
                    <div class="item <?php echo ($count == 1)?' active':''; ?>" data-id='<?php echo $count; ?>'>
                        <h5 class="text-black"><?php echo $bmc['main_title'];  ?></h5>
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


                <?php endforeach; ?>

                <div class="pagination" >
                    <?php 
                        $c_a = 1;
                        while(($c_a) <= $count): ?>
                        <div class="<?php echo ($c_a == 1)?' active':''; ?>" data-target="<?php echo $c_a; ?>"></div>
                    <?php 
                        $c_a++;
                        endwhile; ?>
                </div>

            </div>
        </div>
</section>

<?php endif; ?>


<?php 
    if($body_2_cols_2):  
        foreach($body_2_cols_2 as $b2c2): 
?>

    <section class='section two-column <?php echo ($b2c2['overlap_section']) ? ' overlap-section-white' : ''; ?>'>
        <div class="inner">

            <div class="visual overflow ">

                <?php if($b2c2['content_col_1']['main_title']): ?>
                    <h2><?php echo $b2c2['content_col_1']['main_title']; ?></h2>
                <?php endif; ?>
                <?php if($b2c2['content_col_1']['secondary_title']): ?>
                    <h4 class="uppercase custom-h4"><?php echo $b2c2['content_col_1']['secondary_title']; ?></h4>
                <?php endif; ?>

                <?php if($b2c2['content_col_1']['body']): ?>
                    <?php echo $b2c2['content_col_1']['body']; ?>
                <?php endif; ?>

                <?php 
                    $cont = 0;
                    if($b2c2['content_col_1']['images']):
                        foreach( $b2c2['content_col_1']['images'] as $image): 
                ?>
                        <img class='<?php echo ($cont > 0) ? 'margin-top-60' : ''; ?>' src="<?php  echo $image['image']; ?>" alt="<?php echo (isset($image['image']['image_description'])) ? $image['image']['image_description'] : ''; ?>" />
                <?php 
                        $cont++;
                        endforeach; 
                    endif;
                ?>
            </div>
            
            <div class="content">
                
                
                <?php if($b2c2['content_col_2']['main_title']): ?>
                    <h4 class="uppercase custom-h4"><?php echo $b2c2['content_col_2']['main_title']; ?></h4>
                <?php endif; ?>
                <?php if($b2c2['content_col_2']['secondary_title']): ?>
                    <h4 class="uppercase custom-h4"><?php echo $b2c2['content_col_2']['secondary_title']; ?></h4>
                <?php endif; ?>

                <?php if($b2c2['content_col_2']['body']): ?>
                    <?php echo $b2c2['content_col_2']['body']; ?>
                <?php endif; ?>

                <?php   
                    if($b2c2['content_col_2']['images']):
                        foreach($b2c2['content_col_2']['images'] as $image):
                            echo '<img class="fit-img-on-mobile" src="'.$image['image'].'" alt="'.$image['image_description'].'" />';
                        endforeach; 
                    endif;
                ?>


            </div>
            
        </div>
    </section>

<?php 
        endforeach;  
    endif; 
?>

<?php 
        endwhile;
    endif;
    get_footer();
?>