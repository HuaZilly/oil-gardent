<?php

ini_set('display_errors', 1);

/**
 * Theme only works in WordPress 5.0 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '5.0', '<' ) ) {
	exit("Update Wordpress to 5.0 or later to use this theme.");
}

class Functions {
    function __construct() {
        add_action( 'admin_menu',                   array( $this, 'menu_organisation'));
        add_action( 'after_setup_theme',            array( $this, 'init' ) );
        add_action( 'init',                         array( $this, 'register'));
        //add_action( 'init',                         array( $this, 'shortcodes'));
        add_action( 'init',                         array( $this, 'rewrite_rules'));
        add_filter( 'query_vars',                   array( $this, 'custom_query_vars' ));
        add_action( 'template_redirect',            array( $this, 'change_search_url' ));
        add_action( 'wp_enqueue_scripts',           array( $this, 'scripts' ) );
        add_filter( 'get_search_form',              array( $this, 'search_form' ) );
        add_filter('wpcf7_form_action_url', array( $this, 'wpcf7_custom_form_action_url') );
        add_action( 'after_setup_theme',            array( $this, 'remove_admin_bar' ) );
        add_filter( 'pre_get_posts',                array( $this, 'query_post_customisations' ) );

        add_shortcode('faqs',                       array( $this, 'faqs_shortcode') );
        add_shortcode('contact-us-form',            array( $this, 'contact_us_form_shortcode') );
        add_shortcode('inspiration-blocks-shortcode' , array($this, 'story_inspiration_shortcode') );

        add_filter( 'bigcommerce/product/archive/filter_options' , array( $this, 'add_bc_filters' ), 10, 1);
        add_action( 'bigcommerce/import/product/saved', array( $this, 'update_price_filter'), 10, 4);
        
        /**
         * Script to style the default wordpress's pages
         */
        add_action( 'login_enqueue_scripts',        array( $this, 'wpb_login_logo') );

        /**
         * Custom Email Forgot password
         */
        remove_filter( 'wp_mail_content_type',      array( $this, 'set_html_content_type') );
        add_filter( 'wp_mail_content_type',         array( $this, 'set_html_content_type') );
        add_filter( 'retrieve_password_message',    array( $this, 'my_retrieve_password_message'), 10, 4 );


        /**
         * CUSTOM NAME AND EMAIL IN WORDPRESS FUNCTIONALITY
         */

        add_filter( 'wp_mail_from' , array( $this, 'og_mail_from' ), 10, 1);
        add_filter( 'wp_mail_from_name' , array( $this, 'og_mail_from_name' ), 10, 1);


        /**
         *  Assigning default customer group from BC
         */
        add_filter('bigcommerce/customer/group_id', array( $this, 'set_default_customer_group'), 10, 2);

        /**
         * CUSTOM FACETWP PAGINATION TEMPLATE
         */
        add_filter( 'facetwp_pager_html', function( $output, $params ) {
            $output = '';
            $page = $params['page'];
            $total_pages = $params['total_pages'];
            $dots = '<span class="page-numbers dots">…</span>';

            var_dump($params);

            if ( 1 < $params['total_pages'] ) {
                for ( $i = 1; $i <= $params['total_pages']; $i++ ) {
                    $is_curr = ( $i === $params['page'] ) ? ' active' : '';

                    // only show first, last, current page, previous and next page numbers
                    if ($i === 1 || 
                    $i === $params['page'] - 1 ||
                    $i === $params['page'] ||
                    $i === $params['page'] + 1 ||
                    $i === $params['total_pages']) {
                        // skip numbers between first and previous page
                        if ($i === 1 && $params['page'] > 3 ) {
                            $output .= '<a class="facetwp-page page-numbers' . $is_curr . '" data-page="' . $i . '">' . $i . '</a>' . $dots;
                        }
                        // skip numbers between last and next page
                        else if ($i === $params['total_pages'] && $params['total_pages'] - $params['page'] > 2 ) {
                            $output .= $dots . '<a class="facetwp-page page-numbers' . $is_curr . '" data-page="' . $i . '">' . $i . '</a>';
                        } else {
                            $output .= '<a class="facetwp-page page-numbers' . $is_curr . '" data-page="' . $i . '">' . $i . '</a>';
                        }
                    }
                }
            }
        
            if ( $page > 1 ) {
                $output .= '<a class="facetwp-page page-numbers prev" data-page="' . ($page - 1) . '">Previous page</a>';
            }
        
            if ( $page < $total_pages && $total_pages > 1 ) {
                $output .= '<a class="facetwp-page page-numbers next" data-page="' . ($page + 1) . '">Next page</a>';
            }
        
            return $output;
        }, 10, 2 );

        add_action('wp_ajax_data_fetch' , array($this , 'data_fetch' ) );
        add_action('wp_ajax_nopriv_data_fetch', array($this, 'data_fetch') );

        add_action( 'wp_ajax_submitsf',             array( $this, 'submitSf' ) ); 
        add_action( 'wp_ajax_nopriv_submitsf',      array( $this, 'submitSf' ) );

        // Fetch live directory uploads folder -
        // add_filter('upload_dir', array( $this, 'fetch_live_upload_files') );
        add_filter( 'wp_die_handler', array( $this, 'custom_wp_die_handler' ));
    }

    // Fetch live upload files
    function fetch_live_upload_files( $param ){
        $mydir = '/uploads';
        $path = '/var/www/ogtransfer1/wp-content';
        $url = 'https://www.oilgarden.com.au/';
    
        $param['path'] = $path . $mydir . $param['subdir'];
        $param['url'] = $url . '/wp-content' . $mydir . $param['subdir'];
        $param['basedir'] = $path . $mydir;
        $param['baseurl'] = $url .'/wp-content' . $mydir;

        return $param;
    }


    /**
     *  Assigning default customer group from BC
     */
    function set_default_customer_group($group, $customer) {
        if ($customer->get_customer_id() === 0) {
            return 3;
        }

        return $group;
    }

    function og_mail_from($email) {
        return 'customer.service@oilgarden.com.au';
    }
        
    function og_mail_from_name($name) {
        return get_bloginfo( 'name' );
    }

    function set_html_content_type() {
        return 'text/html';
    }
    
    function my_retrieve_password_message( $message, $key, $user_login, $user_data ) {


        // Start with the default content.
        $site_name  = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
        $link       = network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' );

        $message = "<div style='background-color: #ffffff;'>";
        $message .=     "<div style='background-color: #ffffff; margin: 0 auto; max-width: 700px;'>";
        $message .=         "<div style='text-align: center; padding: 50px 0 20px 0;'>";
        $message .=             "<img title='Oil Garden' src='https://cdn11.bigcommerce.com/s-67f4785fo8/product_images/uploaded_images/logo-1-.png' alt='Oil Garden' width='248' height='53' />";
        $message .=         "</div>";
        $message .=         "<div style='padding: 20px 40px 40px; word-break: break-word;'>";
        

        $message .= __( 'Someone has requested a password reset for the following account:' ) . "<br/><br/>";
        $message .= sprintf( __( '<strong style="color: #74b44d;">Site Name:</strong> %s' ), $site_name ) . "<br/><br/>";
        $message .= sprintf( __( '<strong style="color: #74b44d;">Username:</strong> %s' ), $user_login ) . "<br/><br/>";
        $message .= __( 'If this was a mistake, just ignore this email and nothing will happen.' ) . "<br/><br/>";
        $message .= __( 'To reset your password, visit the following address:' ) . "<br/><br/>";
        $message .= "<a style='color: #74b44d;' target='_blank' href='".$link."'>" . $link . "</a>";


        $message .=         "</div>";
        $message .=     "</div>";
        $message .= "</div>";   


        // $message .= '<http://yoursite.com/wp-login.php?action=rp&key=' . $key . '&login=' . rawurlencode( $user_login ) . ">\r\n";


        return $message;
    }

    function submitSf() {
       $fields_string = null;
       foreach( $_POST['fields'] as $key=>$value ){
         if( !isset($_POST['fields'][$key]) || empty($_POST['fields'][$key])) {}else{ 
           $fields_string .= $key.'='.$value.'&'; 
         }
       }
       rtrim( $fields_string, '&' );
       $_server = 'https://webto.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';
       $curl = curl_init($_server);
        // curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_URL, $_server);
        curl_setopt($curl, CURLOPT_POST,count($_POST['fields']));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields_string);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($curl);
        curl_close($curl);
        
        echo $response;
    }


    function wpb_login_logo() { ?>
        <style type="text/css">
            #login h1 a, .login h1 a {
                background-image: url(<?php echo get_theme_file_uri( '/images/global/logo.png' ); ?>);
                height: 53px;
                width: 248px;
                background-size: 248px 53px;
                background-repeat: no-repeat;
                padding-bottom: 10px;
            }
            #nav{
                display: none !important;
            }
            a{
                color: #74B44D !important;
                font-family: Avenir Next !important;
            }
            body{
                background: #ffffff !important;
            }
            #lostpasswordform,#resetpassform,#loginform{
                box-shadow: none !important;
            }
            form{
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            #user_login{
                background: #ffffff !important;
                height: 50px !important;
                font-family: Avenir Next !important;
                font-size: 16px !important;
            }
            #user_login:focus{
                border-color: #74B44D !important;
                box-shadow: 0 0 2px #74B44D !important;
            }
            #wp-submit{
                width: 100% !important;
                font-size: 0.9em !important;
                font-weight: 500 !important;
                letter-spacing: 0.1667em !important;
                text-align: center !important;
                min-width: 170px !important;
                text-transform: uppercase !important;
                display: inline-block !important;
                border: none !important;
                border-radius: none !important;
                cursor: pointer !important;
                background: #74B44D !important;
                height: 60px !important;
                text-shadow: none !important;
                font-family: Avenir Next !important;
                box-shadow: none !important;
                border-radius: 0px !important;
                border-bottom-left-radius: 0px !important;
                border-bottom-right-radius: 0px !important;
            }
            #wp-submit:hover{
                opacity: 0.8 !important;
                
            }
            label{
                color: #A3A3A3 !important;
            }
            #backtoblog{
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            
        </style>
    <?php }
    
    

    function add_bc_filters($choices) {
        $choices['bigcommerce_price'] = "Price";
        $choices['bigcommerce_size'] = "Size"; 
        return $choices;
    }

    function update_price_filter($post_id, $product, $listing, $catalog) {
        $price = $product['calculated_price'];
        // print_r($product);
        $brackets = get_field('price_bracket_filters', 'option');
        $product_bracket = '';
        foreach ($brackets as $bracket) :
            $max = $bracket['bracket_maximum'] !== '' ? $bracket['bracket_maximum'] : 99999999;
            // print_r($bracket);
            // echo (float) $price;
            // echo (float) $bracket['bracket_minimum'];
            // echo (float) $max;
            if ((float) $price >= (float) $bracket['bracket_minimum'] && (float) $price < (float) $max)
                $product_bracket = $bracket['bracket_display'];
        endforeach;
        // echo $product_bracket;
        update_field('price_bracket_filter', $product_bracket, $post_id);
        // die;
    }

    /***
     * CUSTOMISE SEARCH FORM
     */

    function search_form( $html ) {
        $html = str_replace( 'placeholder="Search ', 'placeholder="Type something here..."', $html );
        $html = str_replace( '<span class="screen-reader-text">Search for:</span>', '', $html );
        return $html;
    }

    /***
     * CHANGE CONTACT FORM 7 ACTION URL
     */

    function wpcf7_custom_form_action_url($url)
    {
        global $post;
        $id_to_change = 629;
        if($post->ID === $id_to_change)
            return 'https://heritagebrands--dev.my.salesforce.com/servlet/servlet.WebToCase?encoding=UTF-8';
        else
            return $url;
    }

    /***
     *  QUERY POST MODIFICATIONS
     */

    function query_post_customisations( $query ) {
        if ( is_admin() || ! $query->is_main_query() ) {
           return;
        }

        if ( is_post_type_archive( 'bigcommerce_product' ) || $query->is_search() ) {
            $query->set( 'tax_query', array(
                array(
                    'taxonomy' => 'bigcommerce_category',
                    'field'    => 'term_id',
                    'terms'    => array( 43 ),
                    'operator' => 'NOT IN',
                )
            ) );
        }
    
        if ( is_post_type_archive( 'recipes' ) ) {
           $query->set( 'posts_per_page', -1 );
        }
    }

    /***
     * REMOVING ADMIN BAR FROM CUSTOMER ACCOUNTS
     */
 
    function remove_admin_bar() {
        if (!current_user_can('administrator') && !is_admin()) {
            show_admin_bar(false);
        }
    }

    /***
     * REWRITE RULES
     */

    function rewrite_rules() {
        add_rewrite_rule('^search/([^/]*)/page/([^/]*)/?', 'index.php?s=$matches[1]&paged=$matches[2]', 'top');
        add_rewrite_rule('^search/([^/]*)/?', 'index.php?s=$matches[1]', 'top');
    }

    /***
     *  CUSTOM QUERY VARS
     */

    function custom_query_vars( $vars ){
        $vars[] = "section";
        return $vars;
    }

    /***
     *  INIT THEME SETTINGS
     */
    
    public function init() {
        /*
         *  THEME SUPPORT
         */
        add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
        );

        add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size( 1568, 9999 );
        
        add_theme_support( 'title-tag' );
        
        /*
         *  REGISTER MENUS
         */ 
        register_nav_menus(
			array(
                'header' => __( 'Header Menu', 'oilgarden' ),
                'header_right' => __( 'Header Right Menu', 'oilgarden' ),
                'footer_1' => __( 'Footer 1 Menu', 'oilgarden' ),
                'footer_2' => __( 'Footer 2 Menu', 'oilgarden' ),
                'customer_service' => __( 'Customer Service Submenu', 'oilgarden' ),
			)
        );

        /*
         *  ADD OPTIONS PAGE
         */
        if( function_exists('acf_add_options_page') ) {
	
            acf_add_options_page(array(
                'page_title' 	=> 'Theme Settings',
                'menu_title'	=> 'Theme Settings',
                'menu_slug' 	=> 'theme-settings',
                'capability'	=> 'edit_posts',
            ));

            acf_add_options_sub_page(array(
                'page_title' 	=> 'General',
                'menu_title'	=> 'General',
                'parent_slug'	=> 'theme-settings',
            ));

            acf_add_options_sub_page(array(
                'page_title' 	=> 'Shop',
                'menu_title'	=> 'Shop',
                'parent_slug'	=> 'theme-settings',
            ));

            acf_add_options_sub_page(array(
                'page_title' 	=> 'Recipes',
                'menu_title'	=> 'Recipes',
                'parent_slug'	=> 'theme-settings',
            ));
        }
    }


    /***
     *  ENQUEUE SCRIPTS FOR SITE
     */

    public function scripts() {
        /* FONT INCLUDE */
        wp_enqueue_style( 'google-fonts', "https://fonts.googleapis.com/css?family=Source+Serif+Pro:400,600&display=swap", array(), wp_get_theme()->get( 'Version' ));
        /* STYLES */
        if (defined("CUSTOM_CSS"))
            wp_enqueue_style( CUSTOM_CSS.'-style', get_theme_file_uri( '/css/'.CUSTOM_CSS.'.min.css'), array(), wp_get_theme()->get( 'Version' ) );
        else
            wp_enqueue_style( 'site-style', get_theme_file_uri( '/css/global.min.css' ), array(), wp_get_theme()->get( 'Version' ) );

        /**
         * SLICK PLUGIN CARROUSEL CSS
         */
        if(defined("SLICK_CSS")){
            wp_enqueue_style( 'site-style', get_theme_file_uri( '/js/external/slick/slick.css' ), array(), wp_get_theme()->get( 'Version' ) );
            wp_enqueue_style( 'site-style-t', get_theme_file_uri( '/js/external/slick/slick-theme.css' ), array(), wp_get_theme()->get( 'Version' ) );
        }
            
        /* SCRIPTS */
        // External
        if (!is_admin()) {
            wp_deregister_script('jquery');
            wp_enqueue_script('jquery', get_theme_file_uri( '/js/external/jquery.min.js' ), array(), '3.4.1', true );
            wp_enqueue_script('jquery');
        }
        wp_enqueue_script( 'insider',  "//oilgardenau.api.useinsider.com/ins.js?id=10003107", array(), '3.4.1', true );

        /**
         * PLUGIN SLICK CARROUSEL JS
         */
        if(defined("SLICK_JS")){
            wp_enqueue_script( 'custom-slick', get_theme_file_uri( '/js/external/slick/slick.js' ), array('jquery'), '1.8.1', true );
        }
        
        // Internal
        wp_enqueue_script( 'site-script', get_theme_file_uri( '/js/script.min.js' ), array('jquery'), wp_get_theme()->get( 'Version' ), true );
        wp_localize_script( 'site-script', 'site_params', array(
                'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php',
            ) );

        wp_enqueue_script( 'custom-script', get_theme_file_uri( '/js/custom.min.js' ), array('jquery','site-script'), wp_get_theme()->get( 'Version' ), true );
        wp_localize_script( 'custom-script', 'site_params', array(
            'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php',
        ) );

        if (defined("CUSTOM_JS")) {
            wp_register_script( CUSTOM_JS.'-script', get_theme_file_uri( '/js/'.CUSTOM_JS.'.min.js' ), array('jquery'), wp_get_theme()->get( 'Version' ), true );
            wp_localize_script( CUSTOM_JS.'-script', str_replace('-', '_', CUSTOM_JS).'_params', array(
                'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php'
            ) );

            wp_enqueue_script(CUSTOM_JS.'-script');
        }
    }

    /***
     * CUSTOM POST TYPES AND TAXONOMIES
     */
    
    function register() {
        /* FAQs */
        register_post_type('faqs', array(
            'labels' => array(
                'name'                  => _x('FAQs', 'oilgarden'),
                'singular_name'         => _x('Question', 'oilgarden'),
                'add_new'               => _x('Add New', 'oilgarden'),
                'add_new_item'          => _x('Add New Question', 'oilgarden'),
                'edit_item'             => _x('Edit Question', 'oilgarden'),
                'new_item'              => _x('New Question', 'oilgarden'),
                'view_item'             => _x('View Question', 'oilgarden'),
                'search_items'          => _x('Search Questions', 'oilgarden'),
                'not_found'             => _x('No Questions found', 'oilgarden'),
                'not_found_in_trash'    => _x('No Questions found in Trash', 'oilgarden'),
                'parent_item_colon'     => _x('Parent Questions:', 'oilgarden'),
                'menu_name'             => _x('FAQs', 'oilgarden'),
            ),
            'hierarchical'              => false,
            'supports'                  => array('title', 'editor'),
            'public'                    => false,
            'show_ui'                   => true,
            'show_in_menu'              => true,
            'show_in_nav_menus'         => false,
            'publicly_queryable'        => false,
            'exclude_from_search'       => true,
            'has_archive'               => false,
            'query_var'                 => true,
            'can_export'                => true,
            'menu_icon'                 => 'dashicons-lightbulb',
            'rewrite'                   => true,
            'capability_type'           => 'post'
        ));

        /* Recipes */
        register_post_type('recipes', array(
            'labels' => array(
                'name'                  => _x('Recipes', 'oilgarden'),
                'singular_name'         => _x('Recipe', 'oilgarden'),
                'add_new'               => _x('Add New', 'oilgarden'),
                'add_new_item'          => _x('Add New Recipe', 'oilgarden'),
                'edit_item'             => _x('Edit Recipe', 'oilgarden'),
                'new_item'              => _x('New Recipe', 'oilgarden'),
                'view_item'             => _x('View Recipe', 'oilgarden'),
                'search_items'          => _x('Search Recipes', 'oilgarden'),
                'not_found'             => _x('No Recipes found', 'oilgarden'),
                'not_found_in_trash'    => _x('No Recipes found in Trash', 'oilgarden'),
                'parent_item_colon'     => _x('Parent Recipe:', 'oilgarden'),
                'menu_name'             => _x('Recipes', 'oilgarden'),
            ),
            'hierarchical'              => false,
            'supports'                  => array('title', 'editor', 'excerpt', 'thumbnail'),
            'public'                    => true,
            'show_ui'                   => true,
            'show_in_menu'              => true,
            'show_in_nav_menus'         => true,
            'publicly_queryable'        => true,
            'exclude_from_search'       => false,
            'has_archive'               => true,
            'query_var'                 => true,
            'can_export'                => true,
            'menu_icon'                 => 'dashicons-book-alt',
            'rewrite'                   => array(
                'with_front'            => false,
                'slug'                  => 'recipes'
            ),
            'capability_type'           => 'post'
        ));
    }

    /***
     * CUSTOM SHORT CODES
     */

    function faqs_shortcode() {
        ob_start();
        include get_template_directory()."/components/faqs-output.php";
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    function contact_us_form_shortcode() {
        ob_start();
        include get_template_directory()."/components/contact-form-output.php";
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    function story_inspiration_shortcode(){
        ob_start();
        include get_template_directory()."/components/story-inspiration-output.php";
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /*** 
     *  ADMIN MENU ORGANISATION 
     */

    function menu_organisation() {
        global $menu;
        global $submenu;
    }

    /***
     *  RESOURCES SEARCH URL CHANGE
     */
    
    function change_search_url() {
        if ( is_search() && ! empty( $_GET['s'] ) ) {
            wp_redirect( home_url( "/search/" ) . urlencode( strtolower( get_query_var( 's' ) ) ) . "/" );
            exit();
        }   
    }


    /***
     *  GET COMPONENT FUNCTION
     */

    function get_component($component) {
        include get_template_directory()."/components/".$component.".php";
    }

    /***
     *  SUPPORTIVE FUNCTIONS
     */

    function clean_string($string) {
        return strtolower(trim(preg_replace('/-+/', '-', preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $string))), '-'));
    }

    /**
     * Returns all child nav_menu_items under a specific parent
     *
     * @param int the parent nav_menu_item ID
     * @param array nav_menu_items
     * @param bool gives all children or direct children only
     * @return array returns filtered array of nav_menu_items
     */
    function get_nav_menu_item_children( $parent_id, $nav_menu_items, $depth = true ) {
        $nav_menu_item_list = array();
        foreach ( (array) $nav_menu_items as $nav_menu_item ) {
            if ( $nav_menu_item->menu_item_parent == $parent_id ) {
                $nav_menu_item_list[] = $nav_menu_item;
                if ( $depth ) {
                    if ( $children = get_nav_menu_item_children( $nav_menu_item->ID, $nav_menu_items ) )
                        $nav_menu_item_list = array_merge( $nav_menu_item_list, $children );
                }
            }
        }
        return $nav_menu_item_list;
    }

    /**
     * Customize logout confirmation template
     *
     * @param $message
     * @param string $title
     * @param array $args
     */
    function custom_wp_die_handler ( $message, $title = '', $args = array() ) {
        $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';
        $title = __( 'Log Out.' );
        $message       = sprintf(
            __( '<p>You are attempting to log out of your Oil Garden Account.</p><p> Are you sure you would like to <a href="%s">log out</a>?</p>' ),
            wp_logout_url( $redirect_to )
        );
        list( $message, $title, $parsed_args ) = _wp_die_process_input( $message, $title, $args );

        if ( is_string( $message ) ) {
            if ( ! empty( $parsed_args['additional_errors'] ) ) {
                $message = array_merge(
                    array( $message ),
                    wp_list_pluck( $parsed_args['additional_errors'], 'message' )
                );
                $message = "<ul>\n\t\t<li>" . join( "</li>\n\t\t<li>", $message ) . "</li>\n\t</ul>";
            }

            $message = sprintf(
                '<div class="wp-die-message">%s</div>',
                $message
            );
        }

        $have_gettext = function_exists( '__' );

        if ( ! empty( $parsed_args['link_url'] ) && ! empty( $parsed_args['link_text'] ) ) {
            $link_url = $parsed_args['link_url'];
            if ( function_exists( 'esc_url' ) ) {
                $link_url = esc_url( $link_url );
            }
            $link_text = $parsed_args['link_text'];
            $message  .= "\n<p><a href='{$link_url}'>{$link_text}</a></p>";
        }

        if ( isset( $parsed_args['back_link'] ) && $parsed_args['back_link'] ) {
            $back_text = $have_gettext ? __( '&laquo; Back' ) : '&laquo; Back';
            $message  .= "\n<p><a href='javascript:history.back()'>$back_text</a></p>";
        }

        if ( ! did_action( 'admin_head' ) ) :
            if ( ! headers_sent() ) {
                header( "Content-Type: text/html; charset={$parsed_args['charset']}" );
                status_header( $parsed_args['response'] );
                nocache_headers();
            }

            $text_direction = $parsed_args['text_direction'];
            if ( function_exists( 'language_attributes' ) && function_exists( 'is_rtl' ) ) {
                $dir_attr = get_language_attributes();
            } else {
                $dir_attr = "dir='$text_direction'";
            }
            ?>
            <!DOCTYPE html>
            <html xmlns="http://www.w3.org/1999/xhtml" <?php echo $dir_attr; ?>>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $parsed_args['charset']; ?>" />
                <meta name="viewport" content="width=device-width">
                <?php
                if ( function_exists( 'wp_no_robots' ) ) {
                    wp_no_robots();
                }
                ?>
                <title><?php echo $title; ?></title>
                <style type="text/css">
                    body {
                        color: #2C2A29;
                        font-family: 'Roboto', sans-serif;
                        font-size: 15px;
                        margin: 0;
                    }

                    .account-page-title {
                        margin: 1.75em 0;
                    }

                    h1 {
                        clear: both;
                        font-family: 'Georgia', serif;
                        font-size: 2.25em;
                        font-weight: 700;
                        color: #2C2A29;
                    }
                    .wp-die-message {
                        line-height: 1.5;
                        padding: 30px 150px;
                        height: 100%;
                    }
                    ul li {
                        margin-bottom: 10px;
                        font-size: 14px ;
                    }
                    a {
                        color: #2C2A29;
                    }

                    .logo-wrap img{
                        min-width: 270px;
                    }

                    .wrapper {
                        max-width: 1345px;
                        margin: 50px auto;
                        padding: 0 2.4rem;
                    }

                    .background-color-paleslate {
                        background-color: #F7FCF5;
                        display: flex;
                        justify-content: center;
                    }

                    .header-logo {
                        margin-bottom: 100px;
                    }

                    .wp-die-message {
                        background: #FFF;
                        border: solid .5px #B8B8B8;
                        margin: 90px auto;
                        padding: 70px 185px;
                        text-align: center;
                    }

                    .wp-die-message p {
                        margin: 0;
                    }

                    @media (max-width: 767px) {
                        .wp-die-message {
                            padding: 70px;
                        }
                    }
                </style>
            </head>
            <body id="error-page" class="custom-wp-die-handle">
        <?php endif; // ! did_action( 'admin_head' ) ?>
        <div class="header-logo wrapper">
            <a href="<?php echo get_site_url(); ?>" class="logo-wrap">
                <img src="<?php echo get_template_directory_uri(  ); ?>/images/global/logo.svg" alt="oilgarden-logo" >
            </a>
        </div>
        <div class="page-title wrapper">
            <h1 class="account-page-title">Log Out.</h1>
        </div>
        <div class="background-color-paleslate">
            <?php echo $message; ?>
        </div>
    </body>
        </html>
        <?php
        if ( $parsed_args['exit'] ) {
            die();
        }
    }

}

$func = new Functions();

function disable_emojis() {
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('wp_print_styles', 'print_emoji_styles');
}
 
add_action( 'init', 'disable_emojis' );