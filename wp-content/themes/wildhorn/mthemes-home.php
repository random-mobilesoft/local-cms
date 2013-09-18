<?php  
$display_news       = get_field( 'display_news_sidebar' );
$mthemes_icons      = include get_template_directory() . '/mthemes-icons.php';
$pages_boxes        = get_field( 'boxes_pages' );
$display_boxes      = true;
$display_message    = false;
$count_boxes        = 0;
$message_line_1     = get_field( 'claim_message_line_1' );
$message_line_2     = get_field( 'claim_message_line_2' );
$page_button        = get_field( 'page_button' );
$page_button_text   = get_field( 'page_button_text' );
$bot_slider=false;
if (isset($post->post_content)){
    $bot_slider=true;
}
if($post->post_name=='home'){
    $display_message = true;
}
if( is_string($message_line_1) and $message_line_1  !== '' ){
    $display_message = true;
}

if( is_array($pages_boxes) ){
    $count_boxes = count($pages_boxes);
}
else {
    $display_boxes = false;
}

$icons = array();
array_push($icons, get_field( 'box_1_icon' ));
array_push($icons, get_field( 'box_2_icon' ));
array_push($icons, get_field( 'box_3_icon' ));
array_push($icons, get_field( 'box_4_icon' ));

foreach( $icons as $key => $icon ){
    $icons[$key] = ( is_string($icon) and array_key_exists($icon, $mthemes_icons) )? preg_replace("/\#000000/", "#FFF", $mthemes_icons[$icon]) : false;
}

$messages = array();
array_push($messages, get_field('box_1_message'));
array_push($messages, get_field('box_2_message'));
array_push($messages, get_field('box_3_message'));
array_push($messages, get_field('box_4_message'));

// news

$news = null;
if( $display_news ){
    $news_args = array(
        'post_type'     => 'post',
        'post_limit'    => 5,
        'nopaging'      => true,
    );
    $news = new WP_Query( $news_args );
}
?>
<section id="home" data-region="home">
    <?php if ($post->ID =='11'){?>
    <h1 class="page-title"><?php the_title() ?></h1>
    <?php } ?>
    <?php //if($display_news): ?>
    <?php if(false): ?>
    <span id="home-news-display" class="button"><?php echo __('latest news', 'mthemes') ?></span>
    <?php endif; ?>

    <?php if($display_message && $message_line_1){ ?>
    <div id="home-claim">
        <h1>
            <span>
                <?php 
                if ($message_line_1){ echo $message_line_1; };
                echo '<br>';
                if ($message_line_2){ echo $message_line_2; };
                ?>
            </span>
            <?php if( is_array( $page_button ) and count($page_button) === 1 ): $page_button = $page_button[0]; ?>
            <a href="<?php echo get_permalink( $page_button->ID ) ?>" class="button"><?php if( is_string($page_button_text) and $page_button_text !== '' ){ echo $page_button_text; } else {echo __('read more', 'mthemes');} ?></a>
            <?php endif; ?>
        </h1>
    </div>
    <?php } else {?>
    <script type="text/javascript">
        jQuery(window).ready(function() {
           jQuery('#bottom-slider, #home-center, h1.page-title').delay(1000).fadeIn(3000);
           jQuery('#home-boxes .home-box-content > a, #footer-menu #menu-main-menu > li > a, #top-bottoms > a').click(function(){
                jQuery('#bottom-slider, #home-center,  h1.page-title').fadeOut(2000);
            });
        });
    </script>
    <div id=home-center>
        <?php print $message_line_2;?>
    </div>
    <?php } ?>
    <?php if( $bot_slider && $post->post_name=='home'){ ?>
    <div id="bottom-slider">
        <div class="content-bottom-slider">
            <?php wp_enqueue_script( 'cycle', get_template_directory_uri() . '/assets/js/jquery.cycle.all.js'); ?>
            <script type="text/javascript">
                jQuery(window).ready(function() {
                    jQuery('#bottom-slider').delay(1000).fadeIn(3000);
                    jQuery('#slider-bottom').cycle({
                        fx: 'fade',
                        speed:   800,
                        timeout: 9000,
                        pause:   0,
                        next:   '.next',
                        prev:   '.prev'
                    });
                    jQuery('#home-boxes .home-box-content > a, #footer-menu #menu-main-menu > li > a, #top-bottoms > a').click(function(){
                        jQuery('#bottom-slider').fadeOut(2000);
                    });
                });
            </script>
            <div id="slider-bottom">
                <?php print $post->post_content;?>
            </div>
            <div class="next arrow-slider"></div>
            <div class="prev arrow-slider"></div>
        </div>
    </div>
    <?php } else {?>
    <div id="home-tamplate">
        <?php //if( function_exists( 'ninja_forms_display_form' ) ){ ninja_forms_display_form( 1 ); }?>
    </div>
    <?php } ?>
    <?php if( $display_boxes ): ?>    
    <div id="home-boxes" class="home-boxes-<?php echo $count_boxes ?>">
        <?php foreach( $pages_boxes as $key => $page ): ?>
        <div class="home-box">
            <div class="home-box-content">
                <?php echo $icons[$key] ?>
                <h3><?php 
                if(empty($messages[$key])){
                    echo $page->post_title;
                }
                else {
                    echo $messages[$key];
                }
                ?></h3>
                <a href="<?php echo get_permalink( $page->ID ) ?>"><?php echo __('details', 'mthemes') ?></a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</section>  

<?php if($display_news): ?>
<aside id="home-aside" data-region="aside">
    <?php
    while($news->have_posts()):
        $news->the_post();
        echo '<article>';
        echo "<h4>". get_the_title() ."</h4>";
        echo "<p>". get_field('excerpt') ."</p>";
        echo "<p class='aside-button'><a class='button' href='". get_permalink() ."'>". __('read more', 'mthemes') ."</a></p>";
        echo '</article>';
    endwhile;
    wp_reset_postdata();
    ?>
</aside>
<?php endif ?>