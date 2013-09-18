<?php  
$social_fields = array(
    'social_500px',
    'social_behance',
    'social_blogger',
    'social_deviantart',
    'social_digg',
    'social_dribbble',
    'social_email',
    'social_envato',
    'social_facebook',
    'social_flickr',
    'social_forrst',
    'social_github',
    'social_google-plus',
    'social_grooveshark',
    'social_last-fm',
    'social_linkedin',
    'social_myspace',
    'social_paypal',
    'social_photobucket',
    'social_pinterest',
    'social_quora',
    'social_soundcloud',
    'social_stumbleupon',
    'social_tumblr',
    'social_twitter',
    'social_viddler',
    'social_vimeo',
    'social_virb',
    'social_wordpress',
    'social_yahoo',
    'social_yelp',
    'social_youtube',
    'social_zerply'
);
$social_footer       = array();
$social_footer_icons = array();

foreach( $social_fields as $social_field ){
    $social_url = get_field($social_field, 'options');
    if( is_string($social_url) and $social_url !== '' ){
        $key = substr($social_field, 7);
        $social_footer[$key] = $social_url;
    }
}

foreach( $social_footer as $key => $value ){
    $svg = file(get_template_directory() . "/assets/svg/social-{$key}.svg");
    // remove doctype & xml declaration
    unset( $svg[0] );
    unset( $svg[1] );
    unset( $svg[2] );
    $svg = implode( $svg );
    $social_footer_icons[$key] = array( 'svg' => $svg, 'url' => $value );
}
?>
<footer>
    <div id="footer-menu" data-region="menu">
    <?php 
    wp_nav_menu(array(
        'menu'          => 'main_menu',
        'container'     => 'nav',
        'container_id'  => false,
        'depth'         => 5
    )); 
    ?>
    </div>
    <div id="footer-bar"></div>
    <div id="footer-socials" data-region="socials">
    <?php
    foreach( $social_footer_icons as $icon ){
        ?>
        <a href="<?php echo $icon['url'] ?>"><?php echo $icon['svg'] ?></a>
        <?php
    }
    ?>
    </div>
    <?php get_template_part('mthemes', 'audioplayer'); ?>
</footer>