<?php 

define("CUSTOM_CSS", "homepage");
define("CUSTOM_JS", "homepage");

get_header() ?>

<?php $hero = get_field('hero'); ?>
<section id="hero">
    <?php $active = " active"; foreach ($hero['slides'] as $slide) : ?>
    <div class="slide<?php echo $active; ?>">
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
                    $icons = array('stress', 'sleep', 'focus', 'breathe', 'headache', 'energy', 'immunity');
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

<?php $social = get_field('social'); ?>
<section id="social" class="content-block sixtyfourty v-left">
    <div class="inner">
        <div class="visual">
            <?php $delay = 300; foreach ($social['images'] as $key=>$image) : ?>
            <img src="<?php echo $image['image']; ?>" data-parallax data-parallax-speed="<?php echo 3 - 0.75 * $key; ?>" data-parallax-mobile-speed="<?php echo 2 - 0.5 * $key; ?>"/>
            <?php $delay += 400; endforeach; ?>
        </div>
        <div class="content" data-parallax data-parallax-speed="0.5">
            <div class="icons">
            <?php 
                $social_networks = get_field('social_networks', 'option'); 
                $to_show = array('Facebook', 'Instagram');
                foreach ($social_networks['network'] as $network) :
                    if (in_array($network['name'], $to_show)) :
            ?>
                <a href="<?php echo $network['href'] ?>" class="<?php echo strtolower($network['name']) ?>"><?php echo $network['icon'] ?></a>
            <?php endif; endforeach; ?>
            </div>
            <?php echo $social['content']; ?>
        </div>
    </div>
</section>

<?php get_footer() ?>