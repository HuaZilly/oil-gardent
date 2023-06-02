<?php 

define("CUSTOM_CSS", "homepage");
define("CUSTOM_JS", "homepage");

get_header() ?>

<?php
    $hero = get_field('hero');
    $slideIndex = 0;
    $dotIndex = 0;
; ?>
<section id="hero">
    <?php $active = " active"; foreach ($hero['slides'] as $slide) : ?>
    <?php $slideIndex++; ?>
    <div class="slide<?php echo $active; ?> swiper-slide" slideIndex="<?= $slideIndex ?>" >
        <div class="slide-image"
            style="background-image: url(<?php echo $slide['background_image'] ?>);"
            data-parallax data-parallax-speed="-3" data-parallax-mobile-speed="-1.5" data-parallax-position="centre" data-parallax-scale="1.2"></div>
        <div class="inner">
            <div class="content" data-parallax data-parallax-speed="2">
                <h1><?php echo $slide['title'] ?></h1>
                <?php if ($slide['sub_text'] !== "") : ?><p><?php echo $slide['sub_text'] ?></p><?php endif; ?>
                <?php if ($slide['cta_button']['href'] !== "") : ?><a href="<?php echo $slide['cta_button']['href'] ?>" class="btn primary"><?php echo $slide['cta_button']['label'] ?></a><?php endif; ?>
            </div>
        </div>
    </div>
    <?php $active = ""; endforeach; ?>
</section>
<section class="custom-dots">
    <ul>
        <?php $dotClass = ' dot-active'; foreach ($hero['slides'] as $slide) : ?>
            <?php
                $dotIndex++;
                $bottomIcon = $slide['bottom_icon'];
                $bottomText = $slide['bottom_text'];
                $bottomTextColour = $slide['bottom_colour'];

            ?>
            <?php if ($bottomIcon || $bottomText): ?>
                <li class="dot-slide <?= $dotClass ?> " slideIndex="<?= $dotIndex ?> ">
                    <div class="content">
                        <?php if ($bottomIcon):?>
                            <img srcset="<?= $bottomIcon ?>" alt="Icon" width="100" height="100" />
                        <?php endif; ?>
                        <?php if ($bottomText):?>
                            <p style="color: <?= $bottomTextColour ? $bottomTextColour : 'white' ?>"><?= $bottomText ?></p>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endif; ?>

        <?php $dotClass = ''; endforeach; ?>
    </ul>
</section>


<section class="acknowledgement"><h4>Oil Garden wishes to acknowledge the Traditional Custodians of the land on which we work and live. We pay our respects to Elders – past, present and emerging.</h4></section>

<?php $initial_content = get_field('initial_content'); ?>
<section class="content-block fifty v-left m-over">
    <div class="inner">
        <div class="visual">
            <div class="content-block-image content-block-image--offset-neg js-parallax-image-wrapper" data-parallax data-parallax-speed="2.5" data-parallax-mobile-speed="0">
                <div class="js-parallax-image-inner">
                    <img src="<?php echo $initial_content['image']; ?>" data-parallax data-parallax-speed="-3" data-parallax-mobile-speed="-1.5" data-parallax-scale="1.3" />
                </div>
            </div>
        </div>

        <div class="content" data-js="md-fadein" data-parallax data-parallax-speed="0.5">
            <h2><?php echo $initial_content['title']; ?></h2>
            <?php echo $initial_content['content']; ?>
            <?php if ($initial_content['button']['href'] !== "") : ?>
            <a href="<?php echo $initial_content['button']['href']; ?>" class="btn primary"><?php echo $initial_content['button']['label']; ?></a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php $benefits = get_field('benefits'); ?>
<section id="health-benefits">
    <div class="inner">
        <h3 data-js="md-fadein" class="anim"><?php echo $benefits['title']; ?></h3>
        <div class="health-attrs" data-overflow-scroll="horizontal">
            <span class="health-attrs-ctrl left" data-overflow-control="left">
                <span class="visually-hidden">Left</span>
            </span>
            <div class="health-attrs-track" data-overflow-track>
                <?php 
                    $icons = array("stress", "sleep", "breathe", "Natural Cleaning", "Cold & Flu", "Women's Health");
                    foreach ($icons as $icon) :
                ?>
                <div>
                    <a class="a-home" href="<?php print_r($benefits['benefit_links'][$icon])  || "#"; ?>">
                    <div><?php include "images/homepage/icon-".$icon.".svg" ?></div>
                    <span><?php echo ucfirst($icon); ?></span>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <span class="health-attrs-ctrl right" data-overflow-control="right">
                <span class="visually-hidden">Right</span>
            </span>
        </div>
    </div>
</section>

<section id="best-sellers">
    <!-- TO BE POPULATED BY INSIDER -->
</section>

<?php $aboutus = get_field('about_us'); ?>
<section id="about-us" class="content-block fifty v-right">
    <div class="inner">
        <div class="visual">
            <div class="content-block-image content-block-image--offset-pos js-parallax-image-wrapper" data-parallax data-parallax-speed="2.5" data-parallax-mobile-speed="0.5">
                <div class="js-parallax-image-inner">
                    <img data-dir="left" src="<?php echo $aboutus['image']; ?>" data-parallax data-parallax-speed="-3" data-parallax-mobile-speed="-1.5" data-parallax-scale="1.3" />
                </div>
            </div>
        </div>

        <div class="content" data-js="md-fadein" data-parallax data-parallax-speed="0.5">
            <div class="blocks">
                <?php $active = " active"; foreach ($aboutus['sections'] as $section) : ?>
                <div class="block anim <?php echo strtolower(str_replace(' ', '-', $section['title'])) ?><?php echo $active ?>">
                    <h3 class="h2"><?php echo $section['title'] ?></h3>
                    <div class="display">
                        <?php echo $section['content'] ?>
                        <?php if ($section['button']['href'] !== "") : ?>
                        <a href="<?php echo $section['button']['href']; ?>" class="btn primary"><?php echo $section['button']['label']; ?></a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php $active = ""; endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php $nature = get_field('nature_video'); ?>
<section id="nature">
    <div class="bg" data-parallax data-parallax-speed="-3" data-parallax-mobile-speed="-1.5" data-parallax-scale="1.6">
        <!-- <div> -->
            <!-- <iframe data-keepplaying width="560" height="315" src="https://www.youtube.com/embed/YzkpsG7svlc?rel=0&controls=0&showinfo=0&loop=1&autoplay=1&playlist=YzkpsG7svlc&mute=1&enablejsapi=1&modestbranding=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
        <!-- </div> -->
    </div>
    <div class="layer"></div>
    <div class="inner" data-parallax data-parallax-speed="0.5">
        <div class="content" data-js="md-fadein">
            <h3><?php echo $nature['title'] ?></h3>
            <?php echo $nature['content'] ?>
            <?php if ($nature['button']['href'] !== "") : ?>
            <a href="<?php echo $nature['button']['href']; ?>" class="btn primary"><?php echo $nature['button']['label']; ?></a>
            <?php endif; ?>
        </div>
    </div>
</section>


<section id="instagram">
    <h3 data-js="md-fadein" class="anim initilised inview">Keep up to date with all the latest news and events. Follow us <a href="https://www.instagram.com/oilgardenbyronbay/">@oilgardenbyronbay.</a></h3>            
</section>

<?php echo do_shortcode('[instagram-feed feed=1]'); ?>

<?php $additionalContent = get_field('homepage_tag', 'options'); ?>
<?php if($additionalContent): ?>
    <?= $additionalContent ?>
<?php endif;?>

<?php get_footer() ?>