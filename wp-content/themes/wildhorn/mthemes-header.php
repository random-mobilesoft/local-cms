<?php 
$logo_url = get_field('logo', 'options');
$logo_url = ( is_string($logo_url) and $logo_url !== '' )? $logo_url : get_template_directory_uri().'/assets/img/logo.png';
?>
<header>
    <div id="header-logo">
        <a href="<?php echo home_url() ?>/"><img src="<?php echo $logo_url ?>" alt=""></a>
    </div>
</header>