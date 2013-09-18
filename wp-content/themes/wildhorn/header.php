<?php
global $is_ajax;
global $is_mobile;
global $is_ie8;
global $is_ie9;
global $is_ie10;

$mobile_class = ( $is_mobile )? 'mobile-device' : null;

if(!$is_ajax): ?>
<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<head>
    <!-- meta -->
    <?php if( $is_ie8 ): ?><meta http-equiv="X-UA-Compatible" content="IE=8"><?php endif; ?>
    <?php if( $is_ie9 ): ?><meta http-equiv="X-UA-Compatible" content="IE=9"><?php endif; ?>
    <?php if( $is_ie10 ): ?><meta http-equiv="X-UA-Compatible" content="IE=10"><?php endif; ?>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width,user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <?php
    // custom favicon
    $favicon_url = get_field('favicon', 'options');
    if( is_string($favicon_url) and preg_match("/\.ico$/", $favicon_url) ){
        echo '<link rel="shortcut icon" href="'. $favicon_url .'" />';
    }
    ?>
    <!-- title -->
    <title><?php echo bloginfo('name'); ?></title>
    <?php wp_head(); ?>
    <script type="text/javascript">
    if(top != self) {
        window.open(self.location.href, '_top');
    }
	
	function isValidURL(url){
    var RegExp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

    if(RegExp.test(url)){
        return true;
    }else{
        return false;
    }
	} 
	
	
	function validatemyURL() {
		
		if(document.forms['form_1']['input_1'].value==''){alert('Please include the URL of your site.')}else{ if(!isValidURL(document.forms['form_1']['input_1'].value)){alert('Please include the URL of your site.');}else{document.forms['form_1'].submit();}}
		
		}
	
	
    </script>
</head>
<body <?php body_class($mobile_class); ?>>
<div id="wrapper">

<div id="container">
<?php 
get_template_part('mthemes', 'header'); 
get_template_part( 'mthemes', 'footer' );
?>
<div id="content">
<?php 
else:

    wp_head();

endif;