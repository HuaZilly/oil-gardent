<!doctype html>
<html <?php language_attributes(); ?>>
    <head>
        <!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-T9ZJ627');</script>
        <!-- End Google Tag Manager -->

        <script>document.documentElement.classList.add("js-enabled");if (window.orientation !== undefined) {document.documentElement.classList.add("mobile");}</script>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="profile" href="https://gmpg.org/xfn/11" />
        <link rel="icon" href="/favicon.ico" type="image/x-icon" />
        <script async="" type="text/javascript" src="https://apps.bazaarvoice.com/deployments/oilgarden-au/main_site/production/en_AU/bv.js"></script>
		<script type="text/javascript" async src="https://static.klaviyo.com/onsite/js/klaviyo.js?company_id=VLQM3F"></script>
        <!-- START Rakuten Marketing Tracking -->
          <script type="text/javascript">
            (function (url) {
              /*Tracking Bootstrap Set Up DataLayer objects/properties here*/
              if(!window.DataLayer){
                window.DataLayer = {};
              }
              if(!DataLayer.events){
                DataLayer.events = {};
              }
              DataLayer.events.SPIVersion = DataLayer.events.SPIVersion || "3.4.1";
              DataLayer.events.SiteSection = "1";

              var loc, ct = document.createElement("script");
              ct.type = "text/javascript";
              ct.async = true; ct.src = url; loc = document.getElementsByTagName('script')[0];
              loc.parentNode.insertBefore(ct, loc);
              }(document.location.protocol + "//tag.rmp.rakuten.com/122675.ct.js"));
          </script>
        <!-- END Rakuten Marketing Tracking -->
        <link rel='stylesheet' id='loyalty-style-css'
              href='<?php  echo get_bloginfo('stylesheet_directory'); ?>/css/loyalty.min.css?ver=1.0' type='text/css' media='all' />

        <?php
        $type = 'Content';
        if(is_page( 'home' )) {
            $type = 'Home';
        } else if(in_array('tax-bigcommerce_category',get_body_class())) {
            $type = 'Category';
        } else if(in_array('post-type-archive-bigcommerce_product',get_body_class())) {
            $type = 'Category';
        } else if(in_array('single-bigcommerce_product',get_body_class())) {
            $type = 'Product';
        } else if(strpos($_SERVER['REQUEST_URI'], 'cart') !== false) {
            $type = 'Basket';
        } else if(strpos($_SERVER['REQUEST_URI'], 'search') !== false) {
            $type = 'Search';
        }
        ?>

        <script type="text/javascript">
            window.insider_object = {
                "page": {
                    "type": "<?php echo $type; ?>"
                }
            }
            <?php
            if ( ! is_admin() && is_user_logged_in()) {
            $loggedinUser = wp_get_current_user();
            ?>
            window.insider_object.user =  {
                "uuid": "<?php  if(!empty( $loggedinUser->get( 'user_email' ) )) { echo $loggedinUser->get( 'user_email' ); } else { echo '';}?>",
                "custom": {
                    "membership_type": "<?php  if(isset( $loggedinUser->roles[0])) { echo $loggedinUser->roles[0]; } else { echo '';}?>",
                },
                "gdpr_optin": true,
                "name": "<?php  if(!empty( $loggedinUser->get( 'display_name' ) )) { echo $loggedinUser->get( 'display_name' ); } else { echo '';}?>",
                "email": "<?php  if(!empty( $loggedinUser->get( 'user_email' ) )) { echo $loggedinUser->get( 'user_email' ); } else { echo '';}?>",
            }
            <?php }?>
        </script>
        
        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>
        <!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T9ZJ627"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->

        <div id="page" class="site<?php if(get_field("enable_header_sales_line", 'option')):?> sales-line-enabled<?php endif?>">
            <header class="no-js-menu">
                <?php if(get_field("enable_header_sales_line", 'option')):?>
                <div id="sale-line">
                    <div class="outer">
                        <?php echo get_field("header_sales_line", 'option'); ?>
                    </div>
                </div>
                <?php endif;?>
                <div id="main-navigation">
                    <div class="outer">
                        <?php if ( has_nav_menu( 'header' ) ) : ?>
                            <nav class="navigation left-navigation" aria-label="Top Menu">
                                <div class="mobile-handle"></div>
                                <div class="menu-container">
                                    <?php
                                    wp_nav_menu(
                                        array(
                                            'theme_location'    => 'header',
                                            'menu_class'        => 'main-menu',
                                            'items_wrap'        => '<ul id="%1$s" class="%2$s">%3$s</ul>'
                                        )
                                    );
                                    ?>
                                </div>
                            </nav>
                        <?php endif; ?>

                        <div class="logo">
                            <a href="<?= site_url() ?>"><?php bloginfo( "name" ) ?></a>
                        </div>

                        <div class="navigation right-navigation">
                            <?php
                                wp_nav_menu(
                                    array(
                                        'theme_location'    => 'header_right',
                                        'menu_class'        => 'main-menu',
                                        'items_wrap'        => '<ul id="%1$s" class="%2$s"><li class="menu-item"><a href="#" class="search">Search</a></li>%3$s</ul>',
                                    )
                                );
                            ?>
                        </div>
                    </div>

                    <div id="subnav-backing">
                        <?php 
                            global $func;
                            $nav_menu = wp_get_nav_menu_items( 'header', array( 'post_status' => 'publish' ) );//echo '<pre>';var_dump($nav_menu);
                            $banners = get_field('navigation_banners', 'option');
                            if(isset($banners)){
                                $banner_groups = count($banners);
                            }else{
                                $banner_groups = 0;
                            }
                            $menu_index = 0;
                        ?>
                        <?php
                        foreach($nav_menu as $sub_nav_menu):
                        if($sub_nav_menu->menu_item_parent == "0"):
                            if($menu_index == 0):
                        ?>
                        <div data-menu="<?php echo $menu_index;?>">
                            <div class="menu-items">
                                <?php foreach($nav_menu as $sub_nav_menu_item):
                                if($sub_nav_menu_item->menu_item_parent == $sub_nav_menu->ID):
                                    ?>
                                <div class="menu-container">
                                    <div class="menu-items-title">
                                       <span><?php echo $sub_nav_menu_item->post_title;?></span>
                                    </div>
                                <?php
                                $sub_menu = $func->get_nav_menu_item_children($sub_nav_menu_item->ID, $nav_menu, false);
                                    if (!empty($sub_menu)) :
                                    $count = 0;
                                    ?>
                                    <ul class="menu-list">
                                        <?php foreach ($sub_menu as $menu_item) : ?>
                                        <?php $childMenus = $func->get_nav_menu_item_children($menu_item->ID, $nav_menu, false);?>
                                        <li class="<?php echo !empty($childMenus) ? "has-submenu" : ''?>">
                                            <a href="<?php echo $menu_item->url ?>">
                                                <?php echo $menu_item->title; ?>
                                                <?php if(!empty($childMenus)):?>
                                                <div class="arrow-span"></div>
                                                <?php endif;?>
                                            </a>
                                            <?php if(!empty($childMenus)):?>
                                            <ul class="sub-menu">
                                                <?php foreach($childMenus as $child):?>
                                                <li>
                                                    <a href="<?php echo $child->url ?>"><?php echo $child->title; ?></a>
                                                </li>
                                                <?php endforeach;?>
                                            </ul>
                                            <?php endif;?>
                                        </li>
                                        <?php
                                        $count++;
                                        if($count%6==0):
                                        ?>
                                    </ul>
                                    <ul class="menu-list">
                                        <?php endif;?>
                                        <?php endforeach; ?>
                                    </ul>
                                    <?php endif;
                                ?>
                                </div>
                                <?php endif;
                                endforeach;?>
                            </div>
                            <div class="banner-items">
		                        <?php
		                        if (isset($banners[$count])) $menu_banners = $banners[$count];
		                        else $menu_banners = $banners[$banner_groups-1];
		                        ?>
                                <a href="<?php echo $menu_banners['banner_1']['href'] ?>">
                                    <img src="<?php echo $menu_banners['banner_1']['image'] ?>" />
                                </a>
                            </div>
                        </div>
                        <?php else:?>
                            <?php
	                            $sub_menu = $func->get_nav_menu_item_children($sub_nav_menu->ID, $nav_menu, false);
	                            if (!empty($sub_menu)) :
                                ?>
                                <div data-menu="<?php echo $menu_index;?>">
                                    <div class="menu-items">
                                        <div class="menu-container">
				                            <?php

					                            $total = count($sub_menu); $count = 0; $half = round($total/2);
					                            ?>
                                                <ul class="menu-list">
					                            <?php foreach ($sub_menu as $menu_item) : ?>
					                            <?php $childMenus = $func->get_nav_menu_item_children($menu_item->ID, $nav_menu, false);?>
                                                <li class="<?php echo !empty($childMenus) ? "has-submenu" : ''?>">
                                                    <a href="<?php echo $menu_item->url ?>">
							                            <?php echo $menu_item->title; ?>
							                            <?php if(!empty($childMenus)):?>
                                                            <div class="arrow-span"></div>
							                            <?php endif;?>
                                                    </a>
						                            <?php if(!empty($childMenus)):?>
                                                        <ul class="sub-menu">
								                            <?php foreach($childMenus as $child):?>
                                                                <li>
                                                                    <a href="<?php echo $child->url ?>"><?php echo $child->title; ?></a>
                                                                </li>
								                            <?php endforeach;?>
                                                        </ul>
						                            <?php endif;?>
                                                </li>
				                            <?php endforeach; ?>
                                                </ul>
                                        </div>
                                    </div>
                                    <div class="banner-items">
			                            <?php
			                            if (isset($banners[$count])) $menu_banners = $banners[$count];
			                            else $menu_banners = $banners[$banner_groups-1];
			                            ?>
                                        <a href="<?php echo $menu_banners['banner_1']['href'] ?>">
                                            <img src="<?php echo $menu_banners['banner_1']['image'] ?>" />
                                        </a>
                                        <a href="<?php echo $menu_banners['banner_2']['href'] ?>">
                                            <img src="<?php echo $menu_banners['banner_2']['image'] ?>" />
                                        </a>
                                    </div>
                                </div>

	                            <?php endif;?>
                        <?php endif;

	                        $menu_index++;
                        endif;
                        endforeach;?>
                    <div id="search-bar">
                        <div class="inner">
                            <h3>Search</h3>
                            <?php get_search_form(); ?>
                        </div>
                    </div>
                </div>

            </header><!-- #masthead -->