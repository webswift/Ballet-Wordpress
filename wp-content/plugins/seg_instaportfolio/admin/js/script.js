jQuery(window).load(function(){
	console.log('sddsd');
	var group_collapse = jQuery("#seg-instaportfolio-form .group-options .title-group-options .group-collapse");
	group_collapse.each(function() {
		var group_option = jQuery(this).attr("group");
		if(jQuery(this).hasClass("up")){
				jQuery("#seg-instaportfolio-form ." + group_option + ' .group-fields').slideUp();
				jQuery(this).html('&darr;');
				jQuery(this).addClass('down').removeClass("up");
		}else{
			jQuery("#seg-instaportfolio-form ." + group_option + ' .group-fields').slideDown();
			jQuery(this).html('&uarr;');
			jQuery(this).addClass('up').removeClass("down");
		}
	});

	jQuery("#seg-instaportfolio-form .group-options .title-group-options").click(function(){
		group_collapse = jQuery(this).find(".group-collapse");
		group_option = group_collapse.attr("group");
		if(group_collapse.hasClass("up")){
			jQuery("#seg-instaportfolio-form ." + group_option + ' .group-fields').slideUp();
			group_collapse.html('&darr;');
			group_collapse.addClass('down').removeClass("up");
		}else{
			jQuery("#seg-instaportfolio-form ." + group_option + ' .group-fields').slideDown();
			group_collapse.html('&uarr;');
			group_collapse.addClass('up').removeClass("down");
		}
	})

	///// COLOUR PICKER
	jQuery('#seg-instaportfolio-form .group-options .colour-picker').focus(function(){
		var input_colour = jQuery(this);
		jQuery(this).ColorPicker({
			color: '',
			onShow: function (colpkr) {
				jQuery(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				jQuery(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				
				input_colour.val('#' + hex);
				input_colour.css({'border' : '2px solid #' + hex});
				input_colour.css({'border-right' : '25px solid #' + hex});
			}
		});
	});


	jQuery('#seg-instaportfolio-form .group-options .colour-picker').each(function() {
		if(jQuery(this).val() == ''){
			jQuery(this).css({'border' : '1px solid #ddd'});
			jQuery(this).css({'border-right' : '1px solid #ddd'});
		}else{
			jQuery(this).css({'border' : '2px solid ' + jQuery(this).val()});
			jQuery(this).css({'border-right' : '25px solid ' + jQuery(this).val()});
		}
	});
	

	jQuery('#seg-instaportfolio-form .group-options .colour-picker').keyup(function(){
		if(jQuery(this).val() == ''){
			jQuery(this).css({'border' : '1px solid #ddd'});
			jQuery(this).css({'border-right' : '1px solid #ddd'});
		}else{
			jQuery(this).css({'border' : '2px solid ' + jQuery(this).val()});
			jQuery(this).css({'border-right' : '25px solid ' + jQuery(this).val()});
		}
	});


	///// LETS LOOP EACH CHECKBOX TO CHECK IF HAS CHILDREN AND DISPLAY OR NOT
	jQuery("#seg-instaportfolio-form input[type=checkbox]").each(function(){
		var children = jQuery(this).attr("id") + '_child';
		if(jQuery(this).attr('checked') == "checked"){
			jQuery("." + children).slideDown();
		}else{
			jQuery("." + children).slideUp();
		}
	});


	///// IF CLICK CHECKBOXES AND HAS CHILDREN DISPLAY OR NOT CHILDREN
	jQuery("#seg-instaportfolio-form input[type=checkbox]").click(function(){
		var children = jQuery(this).attr("id") + '_child';
		if(jQuery(this).attr('checked') == "checked"){
			jQuery("." + children).slideDown();
		}else{
			jQuery("." + children).slideUp();
		}
	});

	//// WHEN MODE CHANGE
	var valMode = jQuery("#seg_instagram_mode").val();

	if(valMode === "User")
		jQuery(".seg_username_instagram .shortcode").text('[seg_instaportfolio user="michaeljackson"]');
	else if(valMode === "Hashtag")
		jQuery(".seg_username_instagram .shortcode").text('[seg_instaportfolio hashtag="#thailand"]');
	else if(valMode === "Location")
		jQuery(".seg_username_instagram .shortcode").text('[seg_instaportfolio location="187467"]');
	else if(valMode === "Multiusers")
		jQuery(".seg_username_instagram .shortcode").text('[seg_instaportfolio multiuser="chelseafc,fcbarcelona,liverpoolfc"]');

	jQuery(".seg_username_instagram label").text(jQuery("#seg_instagram_mode").val());
	jQuery("#seg_instagram_mode").change(function(){

		valMode = jQuery(this).val();

		if(valMode === "User")
			jQuery(".seg_username_instagram .shortcode").text('[seg_instaportfolio user="michaeljackson"]');
		else if(valMode === "Hashtag")
			jQuery(".seg_username_instagram .shortcode").text('[seg_instaportfolio hashtag="#thailand"]');
		else if(valMode === "Location")
			jQuery(".seg_username_instagram .shortcode").text('[seg_instaportfolio location="187467"]');
		else if(valMode === "Multiusers")
			jQuery(".seg_username_instagram .shortcode").text('[seg_instaportfolio multiuser="chelseafc,fcbarcelona,liverpoolfc"]');

		jQuery(".seg_username_instagram label").text(jQuery(this).val());

		//jQuery(".seg_username_instagram input").val("");

	});
});