<?php 
global $is_mobile;

$background_type            = get_field('background_type');
$background_type            = ( $background_type === 'video' and $is_mobile )? 'images' : $background_type;

$background_delay           = get_field('background_image_delay');
$background_delay           = ( is_numeric( $background_delay ) )? (int) $background_delay : 7500;
$background_delay           = ( $background_delay < 2000 )? 2000 : $background_delay;

$background_overlay_color   = get_field('background_color_overlay');
$background_overlay_color   = ( $background_overlay_color === false or $background_overlay_color === '' )? 'transparent' : $background_overlay_color;

$background_overlay_opacity = get_field('background_overlay_opacity');
$background_overlay_opacity = ( is_numeric( $background_overlay_opacity ) )? (int) $background_overlay_opacity : 80;
$background_overlay_opacity = ( $background_overlay_opacity > 100 )? 100 : $background_overlay_opacity;
$background_overlay_opacity = $background_overlay_opacity / 100;

$background_color           = get_field('background_color');
$background_color           = ( is_bool($background_color) )? 'transparent' : $background_color;

$background_pattern         = get_field('pixel_pattern');
?>
<div id="background" data-region="background" data-delay="<?php echo $background_delay ?>" data-type="<?php echo $background_type ?>">
<?php 
switch( $background_type ){

    case "images":
        $images = get_field('background_images');
        if( is_array($images) and count($images) > 0 ){
            foreach($images as $image){
                ?><div class="background-image" data-src="<?php echo $image['url'] ?>"></div><?php
            }
        }
        break;

    case "video":
        if( $is_mobile ):
            $mobile_image = get_field('video_replacement_image_on_mobile');
            if( is_array( $mobile_image )):
                ?><div class="background-image" data-src="<?php echo $mobile_image['url'] ?>"></div><?php
            endif;
        else:
            $video_url  = get_field('video_url');
            $video_code      = '<iframe id="content-video-bkg" src="{video_url}" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
            $video_embed_url = null;
            if($video_url){
                $url = parse_url($video_url);
                switch($url['host']){
                    case 'www.youtube.com':
                    case 'youtube.com':
                        parse_str($url['query']);
                        if(isset($v)){
                            $video_embed_url = "http://www.youtube.com/embed/{$v}?autoplay=1&color=white&controls=0&iv_load_policy=3&modestbranding=1&rel=0&showinfo=0&loop=1&wmode=opaque&playlist={$v}";
                            unset($v);
                        }
                        break;
                    case 'vimeo.com':
                    case 'www.vimeo.com':
                        $video_id = substr($url['path'], 1);
                        if( is_numeric($video_id) ){
                            $video_embed_url = "http://player.vimeo.com/video/{$video_id}?title=0&byline=0&portrait=0&color=ffffff&autoplay=1&loop=1";
                            unset($video_id);
                        }
                        break;
                }
            }

            if( is_string($video_embed_url) ){
                $video_code = preg_replace("/\{video_url\}/", $video_embed_url, $video_code);
                echo $video_code;
                
                ?>
                <script type="text/javascript">
                jQuery(function(){ app.events.trigger('audioplayer.pause') });
                </script>
                <?php
            }
        endif;
        break;

    case "color":
        ?><div id="background-color" style="background-color:<?php echo $background_color ?>"></div><?php 
        break;

} 
if( $background_pattern ){
    ?><div id="background-pattern"></div><?php 
}
?>
    <div id="background-overlay" style="background-color:<?php echo $background_overlay_color ?>; opacity:<?php echo $background_overlay_opacity ?>"></div>
</div>