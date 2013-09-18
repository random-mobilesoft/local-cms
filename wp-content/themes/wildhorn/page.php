<?php 
if(is_page( 'claim-your-mobile-site' )){
	check_url_validity(1,1);
}
get_header();
get_template_part('mthemes', 'background');

$page_type = get_field('page_type');
switch( $page_type ){

    case 'blog':
        get_template_part('mthemes', 'blog');
        break;

    case 'collection':
        get_template_part('mthemes', 'collection');
        break;

    case 'half page':
        get_template_part('mthemes', 'half-page');
        break;

    case 'home':
        get_template_part('mthemes', 'home');
        break;

    case 'gallery':
        get_template_part('mthemes', 'gallery');
        break;

    case 'page':
        get_template_part('mthemes', 'page');
        break;

    case 'video':
        get_template_part('mthemes', 'video');
        break;

    default:
        get_template_part('mthemes', 'page');
        break;
}
get_footer();
