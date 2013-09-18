<?php  
$blog_category      = get_field('category');
$blog_category_id   = ( is_object($blog_category) and property_exists($blog_category, 'term_id') )? $blog_category->term_id : false;
$blog_paged         = (get_query_var('paged')) ? get_query_var('paged') : 1;
$blog_args          = array(
    'posts_per_page' => get_option('posts_per_page'),
    'post_type'      => 'post', 
    'paged'          => $blog_paged
);

$blog_tag = get_query_var('tag');
$blog_tag = ($blog_tag !== '')? $blog_tag : false;

if( $blog_tag ){
    $blog_args['tag'] = $blog_tag;
}

if( $blog_category_id ){
    $blog_args['cat'] = $blog_category_id;
}

$blog_query          = new WP_Query( $blog_args );
$page_hide_sidebar   = get_field( 'hide_sidebar' );
?>
<?php if ( have_posts() ) : the_post(); ?>
<section id="section-blog" data-region="blog">
    <div id="blog-container">
        <div id="blog-content">
            <span class="blog-button" id="blog-button-up">
                <svg viewBox="0 0 86 46" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <path d="M5.90725014,1.00375014 C4.55525014,-0.33424986 2.36525014,-0.33424986 1.01325014,1.00375014 C-0.336749861,2.33975014 -0.338749861,4.50975014 1.01325014,5.84775014 L40.5532501,44.9977501 C41.9052501,46.3357501 44.0952501,46.3357501 45.4472501,44.9977501 L84.9872501,5.84775014 C86.3382501,4.50975014 86.3392501,2.34175014 84.9872501,1.00375014 C83.6352501,-0.33424986 81.4452501,-0.33424986 80.0932501,1.00175014 L43.0002501,36.7067501 L5.90725014,1.00375014 L5.90725014,1.00375014 Z M5.90725014,1.00375014" id="Shape" fill="#fff" transform="translate(43.000438, 23.000625) rotate(-180.000000) translate(-43.000438, -23.000625) "></path>
                    </g>
                </svg>
            </span>
            <span class="blog-button" id="blog-button-down">
                <svg viewBox="0 0 86 46" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <path d="M5.90725014,1.00375014 C4.55525014,-0.33424986 2.36525014,-0.33424986 1.01325014,1.00375014 C-0.336749861,2.33975014 -0.338749861,4.50975014 1.01325014,5.84775014 L40.5532501,44.9977501 C41.9052501,46.3357501 44.0952501,46.3357501 45.4472501,44.9977501 L84.9872501,5.84775014 C86.3382501,4.50975014 86.3392501,2.34175014 84.9872501,1.00375014 C83.6352501,-0.33424986 81.4452501,-0.33424986 80.0932501,1.00175014 L43.0002501,36.7067501 L5.90725014,1.00375014 L5.90725014,1.00375014 Z M5.90725014,1.00375014" id="Shape" fill="#fff"></path>
                    </g>
                </svg>
            </span>
            <div id="blog-content-bar">
            <?php if( is_page() ): ?>
            <h1><?php the_title() ?></h1>
            <?php else: ?>
            <h1><?php echo __('Latest News', 'mthemes') ?></h1>
            <?php endif; ?>
            <?php  
            $i = 0;
            if( $blog_query->have_posts() ):
                while ( $blog_query->have_posts() ) :
                    $blog_query->the_post();
                    $article_class = ( $i === 0 )? 'class="blog-list-active"' : false;
                    ?>
                    <article <?php echo ( $article_class )? $article_class : null;  ?>>
                        <div <?php post_class('blog-list-content'); ?>>
                            <p class="blog-list-meta"><?php the_time( get_option('date_format') ); ?> - <?php echo __('by', 'mthemes') ?>  <?php the_author() ?></p>
                            <h2><?php the_title(); ?></h2>
                            <p class="blog-list-excerpt"><?php echo get_field( 'excerpt' ) ?></p>
                            <p><a href="<?php echo the_permalink() ?>" class="button"><?php echo __('read more', 'mthemes') ?></a></p>
                        </div>
                    </article>
                    <?php 
                    $i++;
                endwhile;
            else:
                ?>
                <article class="blog-list-active">
                    <div class="blog-list-content">
                        <h2><?php echo __('No posts found', 'mthemes') ?></h2>
                        <p class="blog-list-excerpt"><?php echo __('This section has no related posts.', 'mthemes') ?></p>
                    </div>
                </article>
                <?php 
            endif;

            if( get_next_posts_link(null, $blog_query->max_num_pages) || get_previous_posts_link(null, $blog_query->max_num_pages) ): ?>
            <p id="blog-pagination">
            <?php
            echo "<span class='left'>"  . get_next_posts_link( __('next posts', 'mthemes'), $blog_query->max_num_pages) . "</span>";
            echo "<span class='right'>" . get_previous_posts_link( __('previous posts', 'mthemes'), $blog_query->max_num_pages) . "</span>";
            ?>
            </p>
            <?php 
            endif;
            wp_reset_postdata();
            ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if(!$page_hide_sidebar): ?>
<div id="sidebar" data-region="sidebar">
    <?php dynamic_sidebar('Blog Sidebar') ?>
</div>
<?php endif; ?>