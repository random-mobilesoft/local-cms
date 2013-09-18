<?php
// mobile detect

require_once get_template_directory() . '/vendor/mobile-detect/Mobile_Detect.php';
$detect = new Mobile_Detect();

// global vars

$is_ajax            = ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' )? true : false;
$is_dev             = false;
$is_mobile          = ( $detect->isMobile() )? true : false;
$is_ie8             = ( strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE 8') )? true : false;
$is_ie9             = ( strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE 9') )? true : false;
$is_ie10            = ( strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE 10') )? true : false;
$default_post_types = array( 'post', 'page', 'attachment', 'revision', 'nav_menu_item' );

// content width
if ( ! isset( $content_width ) ) $content_width = 984;

// text domain

load_theme_textdomain( 'mthemes', get_stylesheet_directory() . '/languages' );

// acf lite

if(!$is_dev){ 
    define( 'ACF_LITE' , true );
}

require_once get_template_directory() . '/vendor/acf/acf.php';
require_once get_template_directory() . '/vendor/acf-gallery/acf-gallery.php';
require_once get_template_directory() . '/vendor/acf-flexible-content/acf-flexible-content.php';
require_once get_template_directory() . '/vendor/acf-options-page/acf-options-page.php';

if ( !$is_dev ) {
    get_template_part( 'mthemes', 'customfields' );
}

// theme supprt

add_theme_support( 'automatic-feed-links' );
add_theme_support( 'post-thumbnails' );
// add_theme_support( 'post-formats', array( 'link', 'image', 'quote', 'status' ) );

// thumbs

# add_image_size( 'home-boxes', 182, 140, true );

// addons

get_template_part( 'mthemes', 'shortcodes' );

// nav menu

register_nav_menu( 'main_menu', __( 'Main menu', 'mthemes' ) );

// custom post type

function mthemes_create_post_types() {
    register_post_type( 'audio tracks',
        array(
            'labels' => array(
                'name'          => __( 'Audio Tracks', 'mthemes' ),
                'singular_name' => __( 'Track', 'mthemes' ),
                'add_new_item'  => __( 'Add New Track', 'mthemes' ),
                'edit_item'     => __( 'Edit Track', 'mthemes' )
            ),
            'menu_position' => 30,
            'public'        => true,
            'has_archive'   => true,
            'rewrite'       => array( 'slug' => 'fuji-audio-tracks' ),
            'supports'      => array( 'title' )
        )
    );
}

add_action( 'init', 'mthemes_create_post_types' );

// widgets

register_sidebar(array(
    'name'          => 'Page Sidebar',
    'before_widget' => '<div class="sidebar-widget">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
));

register_sidebar(array(
    'name'          => 'Post Sidebar',
    'before_widget' => '<div class="sidebar-widget">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
));

register_sidebar(array(
    'name'          => 'Blog Sidebar',
    'before_widget' => '<div class="sidebar-widget">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
));

// acf settings

function mthemes_acf_settings( $settings ) {
    $settings['title'] = 'Theme Options';
    return $settings;
}

add_filter( 'acf/options_page/settings', 'mthemes_acf_settings' );

// add tags to pages for collections

register_taxonomy_for_object_type('post_tag', 'page');

function mthemes_add_meta_boxes(){
    add_meta_box( 'tagsdiv-post_tag', __('Page Tags', 'mthemes'), 'post_tags_meta_box', 'page', 'side', 'low');
}

add_action( 'add_meta_boxes', 'mthemes_add_meta_boxes' );

// less compiler

function mthemes_after_save_post( $post_id ) {

    if ( $post_id === 'options' ) {

        $fields      = get_fields( 'option' );
        $less_input  = get_template_directory() . '/assets/less/style.less';
        $less_output = get_template_directory() . '/style.css';
        $less_custom = get_template_directory() . '/assets/less/custom.less';

        include get_template_directory() . '/vendor/less-php/lessc.inc.php';

        // open custom.less

        $file = fopen( $less_custom, 'w' );
        $less_custom_content = "";

        // fonts

        $font_family_default  = get_field('google_webfont_name_default', 'options');
        $font_family_headings = get_field('google_webfont_name_headings', 'options');
        $font_family_menu     = get_field('google_webfont_name_menu', 'options');


        if( is_string($font_family_default) and $font_family_default !== '' ){
            $less_custom_content .= "@font-family-default : '{$font_family_default}';\n";
        }

        if( is_string($font_family_headings) and $font_family_headings !== '' ){
            $less_custom_content .= "@font-family-headings : '{$font_family_headings}';\n";
        }

        if( is_string($font_family_menu) and $font_family_menu !== '' ){
            $less_custom_content .= "@font-family-menu : '{$font_family_menu}';\n";
        }

        // colors

        $fields_colors = array();
        $less_colors   = array();

        foreach ( $fields as $field_name => $field_value ) {
            if ( preg_match( "/color\-/", $field_name ) ) {
                array_push( $fields_colors, substr($field_name, 9) );
            }
        }

        foreach ( $fields_colors as $field_color ) {
            $color = get_field( $field_color, 'options' );
            if ( $color ) {
                $less_colors[$field_color] = $color;
            }
        }

        // write colors

        foreach ( $less_colors as $key => $value ) {
            if ( is_string( $value ) and preg_match( "/^\#[0-9a-fA-F]{3,6}/", $value ) ) {
                $less_custom_content .= "@{$key} : {$value};\n";
            }
        }

        // custom vars

        $custom_vars = array(  );
        foreach ( $custom_vars as $custom_var ) {
            $value = get_field( $custom_var, 'options' );

            // loader_image
            if ( $custom_var === 'loader_image' ) {
                if( is_string($value) and preg_match("/^.*\.gif$/", $value) ){
                    $custom_var = "spinner";
                    $value = "url('{$value}')";
                }
                else {
                    $value = false;
                }
            }

            if ( $value !== false ) {
                $less_custom_content .= "@{$custom_var} : {$value};\n";
            }
        }

        // save custom.less

        fwrite( $file, $less_custom_content );
        fclose( $file );

        // compile css

        $less = new lessc;
        $less->setPreserveComments( true );
        $less->setFormatter( 'classic' );
        $less->compileFile( $less_input, $less_output );

    }
}

add_action( 'acf/save_post', 'mthemes_after_save_post', 20 );

// enqueue scripts

function mthemes_enqueue_scripts() {
    global $is_ajax;

    if ( !$is_ajax ) {

        // google web fonts query

        // $font_family_default_url    = "Arimo";
        $font_family_headings_url   = "Roboto:400,300";
        $fonts_url                  = "http://fonts.googleapis.com/css?family=";

        $font_family_default        = get_field('google_webfont_name_default', 'options');
        $font_family_headings       = get_field('google_webfont_name_headings', 'options');
        $font_family_menu           = get_field('google_webfont_name_menu', 'options');
        $font_previous              = false;

        if( is_string($font_family_default) and $font_family_default !== '' ){
            $font_family_default        = preg_replace("/ /", "+", trim($font_family_default));
            $font_family_default_styles = get_field('font_styles_default', 'options');
            
            $fonts_url .= $font_family_default . ':400,';
            if( is_array($font_family_default_styles) ){
                foreach( $font_family_default_styles as $key => $value ){
                    $fonts_url .= $value . ',';
                }
            }
            $fonts_url     = substr($fonts_url, 0, -1);
            $font_previous = true;
        }

        if( is_string($font_family_headings) and $font_family_headings !== '' ){
            $font_family_headings        = preg_replace("/ /", "+", trim($font_family_headings));
            $font_family_headings_styles = get_field('font_styles_headings', 'options');

            if( $font_previous ){
                $fonts_url .= '|';
            }

            $fonts_url .= $font_family_headings . ':400,';

            if( is_array($font_family_headings_styles) ){
                foreach( $font_family_headings_styles as $key => $value ){
                    $fonts_url .= $value . ',';
                }
            }
            $fonts_url     = substr($fonts_url, 0, -1);
            $font_previous = true;
        }
        else {
            if( $font_previous ){
                $fonts_url .= '|';
            }
            $fonts_url    .= $font_family_headings_url;
            $font_previous = true;
        }

        if( is_string($font_family_menu) and $font_family_menu !== '' ){
            $font_family_menu        = preg_replace("/ /", "+", trim($font_family_menu));
            $font_family_menu_styles = get_field('font_styles_menu', 'options');

            if( $font_previous ){
                $fonts_url .= '|';
            }

            $fonts_url .= $font_family_menu . ':400,';
            if( is_array($font_family_menu_styles) ){
                foreach( $font_family_menu_styles as $key => $value ){
                    $fonts_url .= $value . ',';
                }
            }
            $fonts_url = substr($fonts_url, 0, -1);
        }

        wp_register_style( 'googlewebfonts',    $fonts_url );
        wp_register_style( 'style',             get_bloginfo( 'stylesheet_url' ) );
        wp_register_style( 'style-custom',      get_template_directory_uri().'/assets/css/custom.css' );

        wp_register_script( 'jq-isotope',       get_template_directory_uri().'/assets/js/jquery/jquery.isotope.min.js', array(), false, true );
        wp_register_script( 'jq-hammer',        get_template_directory_uri().'/assets/js/jquery/jquery.hammer.min.js', array(), false, true );
        wp_register_script( 'modernizr',        get_template_directory_uri().'/assets/js/modernizr.min.js' );
        wp_register_script( 'underscore',       get_template_directory_uri().'/assets/js/underscore.min.js', array(), false, true );
        wp_register_script( 'eventemitter',     get_template_directory_uri().'/assets/js/eventemitter.min.js', array(), false, true );
        wp_register_script( 'app',              get_template_directory_uri().'/assets/js/app.min.js', array(
            'jq-isotope',
            'jq-hammer',
            'underscore',
            'eventemitter',
        ), false, true);

        wp_enqueue_style( 'googlewebfonts' );
        wp_enqueue_style( 'style' );
        wp_enqueue_style( 'style-custom' );

        wp_enqueue_script( 'app', false, array(), false, true );
        wp_enqueue_script( 'modernizr' );
        wp_enqueue_script( 'jquery' );

        if ( is_singular() && comments_open() && get_option('thread_comments') ){
            wp_enqueue_script( 'comment-reply' );
        }
    }
}

add_action( 'wp_enqueue_scripts', 'mthemes_enqueue_scripts' );

// remove 'view post' for custom post types

function mthemes_remove_permalink( $return, $id, $new_title, $new_slug ) {
    global $post;
    global $default_post_types;

    if ( $post and !in_array( $post->post_type, $default_post_types ) ) {
        $return = '';
    }

    return $return;
}

function mthemes_edit_form_after_title() {
    global $post;
    global $default_post_types;

    if ( $post and !in_array( $post->post_type, $default_post_types ) ) {
        echo '<style> #edit-slug-box { display : none } #titlediv { margin-bottom: 0 } </style>';
    }
}

function mthemes_wp_before_admin_bar_render() {
    global $wp_admin_bar;
    global $post;
    global $default_post_types;

    if ( $post and !in_array( $post->post_type, $default_post_types ) ) {
        $wp_admin_bar->remove_menu( 'view' );
    }
}

add_filter( 'get_sample_permalink_html', 'mthemes_remove_permalink', '', 4 );
add_filter( 'edit_form_after_title', 'mthemes_edit_form_after_title' );
add_action( 'wp_before_admin_bar_render', 'mthemes_wp_before_admin_bar_render' );

// custom functions

function mthemes_hex2rgb( $hex ) {
    $hex = str_replace( "#", "", $hex );

    if ( strlen( $hex ) == 3 ) {
        $r = hexdec( substr( $hex, 0, 1 ).substr( $hex, 0, 1 ) );
        $g = hexdec( substr( $hex, 1, 1 ).substr( $hex, 1, 1 ) );
        $b = hexdec( substr( $hex, 2, 1 ).substr( $hex, 2, 1 ) );
    } else {
        $r = hexdec( substr( $hex, 0, 2 ) );
        $g = hexdec( substr( $hex, 2, 2 ) );
        $b = hexdec( substr( $hex, 4, 2 ) );
    }
    $rgb = array( $r, $g, $b );
    //return implode(",", $rgb); // returns the rgb values separated by commas
    return $rgb; // returns an array with the rgb values
}

function showmoburl(){
        if(isset($_REQUEST["lm_url"]))$res=$_REQUEST["lm_url"];
        else $res="missing";
        return $res;
}
add_shortcode("lm_url", "showmoburl");
function get_gomobi_link($atts, $content){
        if(isset($_REQUEST["lm_url"]))$lm_urlen=urlencode($_REQUEST["lm_url"]);
        else return "missing";
        if(!empty($_REQUEST["lm_sandbox"]))$sb=1;
        else $sb=0;
        $ch = curl_init("http://gmapi.local.mobi/web/gomobi/gmlink.php?lm_url=".$lm_urlen."&s=$sb");
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 90);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec ($ch);
        $errno = curl_errno ($ch);
        $err_str = curl_error ($ch);
        curl_close ($ch);

        if($errno)return "failed";
        else return $response;
}
add_shortcode("lm_gmlink", "get_gomobi_link");
function get_mobi_error(){
        if(isset($_REQUEST["errormsg"]))$res="<p style=\"color: red;\" align=\"center\">".$_REQUEST["errormsg"]."</p>";
        else $res="";
        return $res;
}
add_shortcode("lm_error", "get_mobi_error");
function check_url_validity($atts, $content){
    $res=get_gomobi_link($atts, $content);
    if($res=="expired"){
        header("Location: /build-fail/?errormsg=The mobile site is now longer available, please create a new mobile site and try again");
        exit();
    }
    elseif($res=="missing"){
        header("Location: /build-fail/?errormsg=You did not provide a mobile site to be claimed");
        exit();
    }
   elseif($res=="failed"){
        header("Location: /build-fail/?errormsg=Unknown error, please try again later");
        exit();
    }
    else return "";
}
add_shortcode("lm_chk_url", "check_url_validity");
function get_jm_link($atts, $content){
        if(isset($_REQUEST["lm_url"]))$lm_urlen=urlencode($_REQUEST["lm_url"]);
        else return "missing";
        if(!empty($_REQUEST["lm_sandbox"]))$sb=1;
        else $sb=0;
        $ch = curl_init("http://gmapi.local.mobi/web/gomobi/gmjmlink.php?lm_url=".$lm_urlen."&s=$sb");
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 90);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec ($ch);
        $errno = curl_errno ($ch);
        $err_str = curl_error ($ch);
        curl_close ($ch);

        if($errno)return "failed";
        else return $response;
}
add_shortcode("lm_jm_gmlink", "get_jm_link");
