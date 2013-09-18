<?php 
$pages          = get_field( 'collection_pages' );
$display_tags   = get_field( 'display_tags' );
$display_tags   = ( is_array($display_tags) and $display_tags[0] === '1' )? true : false;
$tags           = array();
$tags_elements  = array();

if( is_array($pages) ){
    foreach( $pages as $page ){
        $_tags = wp_get_object_terms( $page->ID, 'post_tag' );
        $tags_elements[ $page->ID ] = $_tags;
        foreach( $_tags as $_tag ){
            if( !array_key_exists( $_tag->slug, $tags ) ){
                $tags[ $_tag->slug ] = $_tag;
            }
        }
    }
}
ksort( $tags );
?>
<section id="section-collection" data-region="collection">
    <?php if ( have_posts() ) : the_post(); ?>
    <h1 class="page-title"><?php the_title() ?></h1>
    
    <?php if( $display_tags and count( $tags > 0 ) ): ?>
        <div id="collection-tags-title"><?php echo __('tags', 'mthemes') ?></div>
        <div id="collection-tags-list">
            <ul>
                <li class="collection-tags-list-sel" data-slug="*"><?php echo __('all', 'mthemes') ?></li>
                <?php foreach( $tags as $_slug => $_tag ): ?>
                <li data-slug="<?php echo $_slug ?>"><?php echo $_tag->name; ?></li>
                <?php endforeach; ?>
                <li>&nbsp;</li>
            </ul>
        </div>
    <?php endif; ?>

    <div id="collection-container">
        <div id="collection-content">
            <?php foreach( $pages as $page ):
            $class_tags = '';
            $page_tags  = $tags_elements[ $page->ID ];
            if( !empty($page_tags) ){
                foreach( $page_tags as $page_tag ){
                    $class_tags .= 'tag-' . $page_tag->slug . ' ';
                }
            }
            $class_tags = substr($class_tags, 0, -1); ?>
            <div class="collection-item <?php echo $class_tags ?>">
                <a href="<?php echo get_permalink( $page->ID ) ?>" class="collection-item-content">
                    <?php echo get_the_post_thumbnail($page->ID, 'large', array('class' => 'collection-item-thumb')); ?>
                    <div>
                        <span><?php echo $page->post_title; ?></span>
                    </div>
                </a>
            </div>    
            <?php endforeach; ?>
        </div>
    </div>

    <?php endif; ?>
</section>