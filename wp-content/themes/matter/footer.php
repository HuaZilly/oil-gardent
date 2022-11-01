<?php
    $locations = get_nav_menu_locations();
    $footer_1_obj = wp_get_nav_menu_object($locations['footer_1']);
    $footer_2_obj = wp_get_nav_menu_object($locations['footer_2']);
    global $func;
?>
	<footer>
        <div class="company-features">
            <div class="inner">
                <div class="icon natural"><span>Natural & Organic</span></div>
                <div class="icon australian"><span>Australian Made & Owned</span></div>
                <div class="icon quality"><span>Quality Assured</span></div>
                <div class="icon noanimals"><span>Cruelty Free</span></div>
            </div>
        </div>
        <div class="brand-bar">
            <?php include "images/global/logo.svg" ?>
        </div>
        
        <section class="acknowledgement-footer"><h5>Oil Garden wishes to acknowledge the Traditional Custodians of the land on which we work and live. We pay our respects to Elders – past, present and emerging.</h5></section>

		
        <div class="outer">            
			<div class="klaviyo-form-WBmMKQ" style="margin-left:auto;margin-right:auto;display:flex;"></div>
			
			<div class="klaviyo-form-T56HAw" style="margin-left:auto;margin-right:auto;display:flex;"></div>
			
			<style>
				.ql-editor {
					height: 40px;
				}
			</style>
			

            <div class="footer-menu-container">
                <?php if ( has_nav_menu( 'footer_1' ) ) : ?>
                    <div>
                        <h6><?php echo $footer_1_obj->name; ?></h6>
                        <?php
                        wp_nav_menu(
                            array(
                                'theme_location' => 'footer_1',
                                'menu_class'     => 'footer-menu',
                                'depth'          => 1,
                            )
                        );
                        ?>
                    </div>
                <?php endif; ?>
                <?php /* if ( has_nav_menu( 'footer_2' ) ) : ?>
                    <div>
                        <h6><?php echo $footer_2_obj->name; ?></h6>
                        <?php
                        wp_nav_menu(
                            array(
                                'theme_location' => 'footer_2',
                                'menu_class'     => 'footer-menu',
                                'depth'          => 1,
                            )
                        );
                        ?>
                    </div>
                <?php endif;*/ ?>
                <div>
                    <h6>Social</h6>
                    <ul>
                        <?php 
                            $social_networks = get_field('social_networks', 'option'); 
                            foreach ($social_networks['network'] as $network) : ?>
                        <li>
                            <a target="_blank" href="<?php echo $network['href'] ?>" class="social <?php echo strtolower($network['name']) ?>"><?php echo $network['icon'] ?> <?php echo $network['name'] ?></a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="footnotes">
                <div class="payment-methods">
                    <span class="zippay">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/global/logo-zippay.svg" alt="Zip Pay" title="Zip Pay" />
                    </span>
                    <span class="visa">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/global/logo-visa.svg" alt="Visa" title="Visa" />
                    </span>
                    <span class="mastercard">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/global/logo-mastercard.svg" alt="Mastercard" title="Mastercard" />
                    </span>
                    <span class="paypal">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/global/logo-paypal.svg" alt="Paypal" title="Paypal" />
                    </span>
                </div>
                <div class="copyright">
                    ALWAYS READ THE LABEL. Use only as directed. If symptoms persist consult your healthcare professional.<br />
                    &copy;<?php echo date('Y') ?> Heritage Brands Limited. All Rights Reserved.
                </div>
            </div>
        </div>
	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>