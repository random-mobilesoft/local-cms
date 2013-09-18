<section id="section-video" data-region="video">
    <?php if ( have_posts() ) : the_post(); ?>
    <h1><?php the_title() ?></h1>
    <div id="video-container">
        <div id="video-content">
            <?php    
            $video_url       = get_field('page_video_url');
            $video_autoplay  = get_field('page_video_autoplay');
            $video_autoplay  = ( is_bool($video_autoplay) )? (int) $video_autoplay : 1;
            $video_code      = '<iframe id="page-video-iframe" src="{video_url}" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
            $video_embed_url = null;

            if( is_string($video_url) ){
                $url = parse_url($video_url);
                switch($url['host']){
                    case 'www.youtube.com':
                    case 'youtube.com':
                        parse_str($url['query']);
                        if(isset($v)){
                            $video_embed_url = "http://www.youtube.com/embed/{$v}?autoplay={$video_autoplay}&color=white&controls=1&iv_load_policy=3&modestbranding=1&rel=0&showinfo=0&wmode=opaque";
                            unset($v);
                        }
                        break;

                    case 'vimeo.com':
                    case 'www.vimeo.com':
                        $video_id = substr($url['path'], 1);
                        if( is_numeric($video_id) ){
                            $video_embed_url = "http://player.vimeo.com/video/{$video_id}?title=0&byline=0&portrait=0&color=ffffff&autoplay={$video_autoplay}";
                            unset($video_id);
                        }
                        break;
                }
            }

            if( is_string($video_embed_url) ){
                $video_code = preg_replace("/\{video_url\}/", $video_embed_url, $video_code);
                echo $video_code;
            }

            if( $video_autoplay ){ ?>
                <script>jQuery(function(){ app.events.trigger('audioplayer.pause') });</script>
                <?php 
            }
            ?>
        </div>
    </div>
    <?php endif; ?>
</section>