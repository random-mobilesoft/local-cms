<?php

$audio_player_display = get_field('audio_player', 'options');

// color icons
$icons_color = get_field('color-menu-anchors', 'options');
$icons_color = ( !$icons_color or $icons_color === '' )? '#FFF' : $icons_color;

// audio tracks
$audio_tracks_query = new WP_Query( array('post_type' => 'audiotracks', 'nopaging' => true) );
$audio_tracks       = array();
while( $audio_tracks_query->have_posts() ){
    $audio_tracks_query->the_post();
    $audio_track          = array();
    $audio_track['index'] = get_field('track_index');
    $audio_track['title'] = get_the_title();
    $audio_track['mp3']   = get_field('custom_mp3_file_url');
    if( !$audio_track['mp3'] ){
        $audio_track['mp3'] = get_field('mp3_file');
    }
    $audio_track['ogg']   = get_field('custom_ogg_file_url');
    if( !$audio_track['ogg'] ){
        $audio_track['ogg'] = get_field('ogg_file');
    }

    $_index = $audio_track['index'];
    $key_found = (isset($audio_tracks[$_index]) and is_array($audio_tracks[$_index]))? true : false;
    while($key_found){
        $_index++;
        $key_found = (isset($audio_tracks[$_index]) and is_array($audio_tracks[$_index]))? true : false;
    }
    $audio_tracks[$_index] = $audio_track;
}
wp_reset_postdata();
unset($_index);
unset($key_found);
unset($audio_tracks_query);
sort($audio_tracks);


if( $audio_player_display and count($audio_tracks) > 0 ): ?>

    <script>
    <?php  
    echo 'mthemes_audioPlayerTracks = ' . json_encode($audio_tracks) . ";\n";

    if( get_field('audio_player_autoplay', 'options') ){
        echo 'mthemes_audioPlayerAutoplay = true';
    }
    ?>
    </script>
    <section id="audioplayer" data-region="audioplayer">
        <audio preload="auto"></audio>
        <span id="audioplayer-next">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve">
            <g>
                <path fill="<?php echo $icons_color ?>" d="M256,0C114.609,0,0,114.609,0,256s114.609,256,256,256s256-114.609,256-256S397.391,0,256,0z M256,472 c-119.297,0-216-96.703-216-216S136.703,40,256,40s216,96.703,216,216S375.297,472,256,472z"/>
                <polygon fill="<?php echo $icons_color ?>" points="176,336 272,256 176,176    "/>
                <polygon fill="<?php echo $icons_color ?>" points="272,256 272,336 368,256 272,176    "/>
            </g>
            </svg>
        </span>
        <span id="audioplayer-previous">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve">
            <g>
                <path fill="<?php echo $icons_color ?>" d="M256,0C114.609,0,0,114.609,0,256s114.609,256,256,256s256-114.609,256-256S397.391,0,256,0z M256,472 c-119.297,0-216-96.703-216-216S136.703,40,256,40s216,96.703,216,216S375.297,472,256,472z"/>
                <polygon fill="<?php echo $icons_color ?>" points="336,336 336,176 240,256    "/>
                <polygon fill="<?php echo $icons_color ?>" points="144,256 240,336 240,256 240,176    "/>
            </g>
            </svg>
        </span>
        <span id="audioplayer-pause">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve">
            <g>
                <path fill="<?php echo $icons_color ?>" d="M256,0C114.609,0,0,114.609,0,256s114.609,256,256,256s256-114.609,256-256S397.391,0,256,0z M256,472 c-119.297,0-216-96.703-216-216S136.703,40,256,40s216,96.703,216,216S375.297,472,256,472z"/>
                <g>
                    <polygon fill="<?php echo $icons_color ?>" points="271.5,336.5 335.5,336.5 335.5,211.5 335.5,176.5 271.5,176.5"/>
                    <rect fill="<?php echo $icons_color ?>" x="175.5" y="176.5" width="64" height="160"/>
                </g>
            </g>
            </svg>
        </span>
        <span id="audioplayer-play">
            <svg version="1.1" id="audioplayer-icon-play" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve">
            <g>
                <path fill="<?php echo $icons_color ?>" d="M256,0C114.609,0,0,114.609,0,256s114.609,256,256,256s256-114.609,256-256S397.391,0,256,0z M256,472 c-119.297,0-216-96.703-216-216S136.703,40,256,40s216,96.703,216,216S375.297,472,256,472z"/>
                <polygon fill="<?php echo $icons_color ?>" points="192,336 352,256 192,176"/>
            </g>
            </svg>
        </span>
        <span id="audioplayer-text"></span>
    </section>

<?php endif; ?>