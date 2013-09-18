<?php  
$second_column_content = get_field('second_column_content');
$second_column_content = ( is_string($second_column_content) )? $second_column_content : '';

?>
<div id="section-half-page" data-region="halfpage">
    <?php if ( have_posts() ) : the_post(); ?>
    <h1 class="page-title"><?php the_title() ?></h1>
    <div id="post-<?php the_ID(); ?>" <?php post_class('page-content text-format'); ?>>
        <div id="half-page-container">
            <div id="half-page-column-1" class="half-page-column">
                <div class="half-page-column-content">
                    <?php the_content(); ?>
                </div>
            </div>
            <div id="half-page-column-2" class="half-page-column">
                <div class="half-page-column-content">
                    <?php echo $second_column_content; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>