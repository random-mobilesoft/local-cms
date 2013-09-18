<?php
/**
* @package mredirect
* @version 1.0
*/
/*
  Plugin Name: Mobile Redirection Plugin
  Description: Redirect mobile visitors to mobile site using DeviceAtlas device detection service.
  Version: 1.0
*/
  define('MOBILE_SITE', 'http://mobilesite.local.mobi');


  function load_redirection_script() {
    print '<link rel="alternate" media="handheld" href="'.MOBILE_SITE.'" />';
    print '<script type="text/javascript" src="http://detect.deviceatlas.com/redirect.js?m='.MOBILE_SITE.'&t=false"></script>';
  }

  add_action('wp_head', 'load_redirection_script');
?>
