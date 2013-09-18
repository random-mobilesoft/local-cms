/*
*  Date Picker
*
*  @description: 
*  @since: 3.5.8
*  @created: 17/01/13
*/(function(e){var t=acf.fields.date_picker;e(document).live("acf/setup_fields",function(n,r){e(r).find("input.acf_datepicker").each(function(){var n=e(this),r=n.siblings(".acf-hidden-datepicker"),i=n.attr("data-save_format"),s=n.attr("data-display_format"),o=n.attr("data-first_day");if(acf.helpers.is_clone_field(r))return;n.val(r.val());var u=e.extend({},t.text,{dateFormat:i,altField:r,altFormat:i,changeYear:!0,yearRange:"-100:+100",changeMonth:!0,showButtonPanel:!0,firstDay:o});n.addClass("active").datepicker(u);n.datepicker("option","dateFormat",s);e("body > #ui-datepicker-div").length>0&&e("#ui-datepicker-div").wrap('<div class="ui-acf" />');n.blur(function(){n.val()||r.val("")})})})})(jQuery);