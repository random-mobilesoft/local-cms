<?php 
$images     = get_field( 'images' );
$autoplay   = get_field( 'images_autoplay' );
$autoplay   = ( $autoplay !== '' )? true : false;
$delay      = (int) get_field( 'images_delay' );
$delay      = ( $delay < 4000 )? 4000 : $delay;
$gallery    = array();

if( is_array($images) ){
    foreach ($images as $key => $value) {
        $tmp = array( 'src' => $value['url'], 'caption' => $value['caption'] );
        array_push( $gallery, $tmp );
    }
}
?>
<section id="section-gallery" data-region="gallery">
    <?php if ( have_posts() ) : the_post(); ?>
    <div id="gallery-loader-container">
        <div id="gallery-loader">50%</div>
    </div>
    <h1 class="page-title">
        <span id="gallery-button-prev" class="button"><?php echo __('prev', 'mthemes') ?></span>
        <span id="gallery-button-next" class="button"><?php echo __('next', 'mthemes') ?></span>
        <?php the_title() ?>
    </h1>
    <div id="gallery-image-container">
        <div id="gallery-image-caption"></div>
        <div id="gallery-image-swipe"></div>
    </div>
    <script>
        var mthemes_gallery_images       = <?php echo json_encode($gallery); ?>;
        var mthemes_gallery_images_delay = <?php echo $delay ?>;
        var mthemes_gallery_autoplay     = <?php echo ($autoplay)? 'true' : 'false' ?>;
    </script>
    <?php endif; ?>
</section>