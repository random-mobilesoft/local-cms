<?php
class MthemesUtils {
    public static function remove_trailing_pragraphs( $content ) {
        $content = trim($content);

        // remove starting br

        if( substr($content, 0, 6) === '<br />' ){
            $content = trim(substr($content, 6));
        }

        if( substr($content, 0, 4) === '<br>' ){
            $content = trim(substr($content, 4));
        }
        
        if ( '</p>' === substr( $content, 0, 4 ) ){
            $content = substr($content, 4);
        }
        if ( '<p>'  === substr( $content, strlen( $content ) - 3 ) ){
            $content = substr($content, 0, strlen( $content ) - 3);
        }

        $content = trim($content);
        return $content;
    }

    public static function add_trailing_paragraphs( $content ) {
        $content = trim($content);
        
        if( substr( $content, 0, 2 ) !== '<h' ){
            if( '<p>' !== substr( $content , 0, 3) ){
                $content = '<p>' . $content;
            }

            if( '</p>' !== substr( $content , strlen( $content ) - 4) ){
                $content = $content . '</p>';
            }

            $content = trim($content);
        }

        return $content;
    }
}

class MthemesShortcodeGrid {
    public static function grid( $attr, $content ) {
        
        $content    = do_shortcode( $content );
        $content    = MthemesUtils::remove_trailing_pragraphs( $content );
        $content    = "<div class='columns'>". $content ."</div>";
        return $content;
    }

    public static function column( $attr, $content ) {

        $width   = 4;
        if ( !empty( $attr['width'] ) ) {
            $width = ( is_numeric( $attr['width'] ) )? (int) abs( $attr['width'] ) : $width;
        }
        $width   = ( $width > 0 and $width < 13 )? $width : 4;

        $classes = '';
        if ( isset( $attr['clear'] ) ) {
            $classes .= ' col-clear';
        }

        $content = do_shortcode( $content );
        $content = MthemesUtils::remove_trailing_pragraphs( $content );
        // $content = MthemesUtils::add_trailing_paragraphs( $content );
        $content = "<div class='col-{$width}{$classes}'>". $content ."</div>";
        return $content;
    }
}

class MthemesShortcodeTab {
    public static function tabs( $attr, $content ) {
        $content = do_shortcode( $content );
        $content = MthemesUtils::remove_trailing_pragraphs( $content );
        $content = "<div class='tabs'>{$content}</div>";
        return $content;
    }

    public static function tab( $attr, $content ) {
        if( isset($attr['label']) ){
            $label   = $attr['label'];
            $content = do_shortcode( $content );
            $content = MthemesUtils::remove_trailing_pragraphs( $content );
            $content = MthemesUtils::add_trailing_paragraphs( $content );
            $content = "<span class='tab-label'>{$label}</span><div class='tab'>{$content}</div>";
        }
        return $content;
    }
}

add_shortcode( 'grid',   array( 'MthemesShortcodeGrid', 'grid' ) );
add_shortcode( 'column', array( 'MthemesShortcodeGrid', 'column' ) );

add_shortcode( 'tab',    array( 'MthemesShortcodeTab', 'tab' ) );
add_shortcode( 'tabs',   array( 'MthemesShortcodeTab', 'tabs' ) );