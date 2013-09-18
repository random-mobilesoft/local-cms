/*
*  WYSIWYG
*
*  @description: 
*  @since: 3.5.8
*  @created: 17/01/13
*/(function(e){var t=acf.fields.wysiwyg;t.has_tinymce=function(){var e=!1;typeof tinyMCE=="object"&&(e=!0);return e};t.add_tinymce=function(n){if(!t.has_tinymce())return;n.find(".acf_wysiwyg textarea").each(function(){var n=e(this),r=n.attr("id"),i=n.closest(".acf_wysiwyg").attr("data-toolbar");if(acf.helpers.is_clone_field(n))return;tinyMCE.settings.theme_advanced_buttons1="";tinyMCE.settings.theme_advanced_buttons2="";tinyMCE.settings.theme_advanced_buttons3="";tinyMCE.settings.theme_advanced_buttons4="";t.toolbars[i]&&e.each(t.toolbars[i],function(e,t){tinyMCE.settings[e]=t});tinyMCE.execCommand("mceAddControl",!1,r);e(document).trigger("acf/wysiwyg/load",r);t.add_events(r)});wpActiveEditor=null};t.add_events=function(n){if(!t.has_tinymce())return;var r=tinyMCE.get(n);if(!r)return;var i=e("#wp-"+n+"-wrap"),s=e(r.getBody());i.click(function(){e(document).trigger("acf/wysiwyg/click",n)});s.focus(function(){e(document).trigger("acf/wysiwyg/focus",n)}).blur(function(){e(document).trigger("acf/wysiwyg/blur",n)})};t.remove_tinymce=function(n){if(!t.has_tinymce())return;n.find(".acf_wysiwyg textarea").each(function(){var t=e(this),n=t.attr("id"),r=tinyMCE.get(n);if(r){var i=r.getContent();tinyMCE.execCommand("mceRemoveControl",!1,n);t.val(i)}});wpActiveEditor=null};e(document).live("acf/wysiwyg/click",function(t,n){wpActiveEditor=n;container=e("#wp-"+n+"-wrap").closest(".field").removeClass("error")});e(document).live("acf/wysiwyg/focus",function(t,n){wpActiveEditor=n;container=e("#wp-"+n+"-wrap").closest(".field").removeClass("error")});e(document).live("acf/wysiwyg/blur",function(t,n){wpActiveEditor=null;var r=tinyMCE.get(n),i=r.getElement();r.save();e(i).trigger("change")});e(document).live("acf/setup_fields",function(n,r){t.add_tinymce(e(r))});e(document).live("acf/sortable_start",function(n,r){t.remove_tinymce(e(r))});e(document).live("acf/sortable_stop",function(n,r){t.add_tinymce(e(r))});e(window).load(function(){var n=e("#wp-content-wrap").exists(),r=e("#wp-acf_settings-wrap").exists();mode="tmce";r&&e("#wp-acf_settings-wrap").hasClass("html-active")&&(mode="html");setTimeout(function(){r&&mode=="html"&&e("#acf_settings-tmce").trigger("click")},1);setTimeout(function(){r&&mode=="html"&&e("#acf_settings-html").trigger("click");n&&t.add_events("content")},11)});e(".acf_wysiwyg a.mce_fullscreen").live("click",function(){var t=e(this).closest(".acf_wysiwyg"),n=t.attr("data-upload");n=="no"&&e("#mce_fullscreen_container td.mceToolbar .mce_add_media").hide()})})(jQuery);