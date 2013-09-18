<?php
$page_width          = get_field( 'page_width' );
$hide_featured_image = false;
$page_hide_sidebar   = get_field( 'hide_sidebar' );
?>
<div id="section-page" data-region="page" class="section-page-<?php echo $page_width ?>">
    <?php if ( have_posts() ) : the_post(); ?>
    <h1><?php the_title() ?></h1>
    <div id="post-<?php the_ID(); ?>" <?php post_class('page-content text-format'); ?>>
        <?php
        if( has_post_thumbnail() and !$hide_featured_image ){
            echo '<div id="page-featured">';
            the_post_thumbnail('large');
            echo '</div>';
        }
        if( is_single() ){
            echo '<div id="page-meta">';
            echo '<strong>';
            the_author();
            echo '</strong>';
            echo __(' on ', 'mthemes');
            the_time( get_option('date_format') );
            the_tags( __('. Tags: ', 'mthemes') );
            echo '</div>';
        }

        the_content();
        
        if( is_single() ){
            comments_template();
        }
        ?>
    </div>
    <?php endif; ?>
</div>

<?php if(!$page_hide_sidebar): ?>
<div id="sidebar" data-region="sidebar">
    <?php 
    if( is_single() ) {
        dynamic_sidebar('Post Sidebar');
    }
    else {
        dynamic_sidebar('Page Sidebar');
    }
    ?>
</div>
<?php endif; ?>