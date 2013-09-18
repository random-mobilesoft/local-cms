<div id="comments">
<?php
if ( post_password_required() ) :  ?>
    <p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'mthemes' ); ?></p>
</div><!-- #comments -->
<?php
return;
endif;

if ( have_comments() ) : ?>
<h3 id="comments-title">
    <?php
    printf( _n( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'mthemes' ),
        number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
    ?>
</h3>

<?php 
if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
<nav id="comment-nav-above">
    <h1 class="assistive-text"><?php _e( 'Comment navigation', 'mthemes' ); ?></h1>
    <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'mthemes' ) ); ?></div>
    <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'mthemes' ) ); ?></div>
</nav>
<?php 
endif; 
?>

<ol class="commentlist">
    <?php 
    wp_list_comments(array( 'avatar_size' => 84 ));
    ?>
</ol>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
<nav id="comment-nav-below">
    <h1 class="assistive-text"><?php _e( 'Comment navigation', 'mthemes' ); ?></h1>
    <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'mthemes' ) ); ?></div>
    <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'mthemes' ) ); ?></div>
</nav>
<?php 
endif; // check for comment navigation 

endif; 

comment_form(); 
?>
</div><!-- #comments -->