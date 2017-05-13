<?php
	$access_token = $this->get_option_value( 'access_token' );
	$instagram_mode = $this->get_option_value( 'instagram_mode' );
	$username_instagram = $this->get_option_value( 'username_instagram' );
	$filter_users = $atts['filter_users'] ? $atts['filter_users'] : $this->get_option_value( 'filter_users' );
	
	///// SHORTCODE PARAMETERS FIRST
	if($atts['hashtag']){
		$instagram_mode = "Hashtag";
		$username_instagram = $atts['hashtag'];
	}
	if($atts['user']){
		$instagram_mode = "User";
		$username_instagram = $atts['user'];
	}
	if($atts['location']){
		$instagram_mode = "Location";
		$username_instagram = $atts['location'];
	}
	if( substr($username_instagram, 0, 1) == "#" )
		$username_instagram = substr($username_instagram, 1, (strlen($username_instagram) - 1));
	$number_photos = $atts['number_photos'] ? $atts['number_photos'] : $this->get_option_value( 'number_photos' );
	$height = $atts['height'] ? $atts['height'] : $this->get_option_value( 'height' );
	$fixed_height = $atts['fixed_height'] ? $atts['fixed_height'] : $this->get_option_value( 'fixed_height' );
	$last_row = $atts['last_row'] ? $atts['last_row'] : $this->get_option_value( 'last_row' );
	$last_row = str_replace(" ", "", strtolower($last_row));
	$width = $atts['width'] ? $atts['width'] : $this->get_option_value( 'width' );
	$padding = $atts['padding'] ? $atts['padding'] : $this->get_option_value( 'padding' );
	$padding = str_replace("px", "", $padding);
	$photo_effect = $atts['photo_effect'] ? $atts['photo_effect'] : $this->get_option_value( 'photo_effect' );
	$photo_effect = strtolower($photo_effect);
	$photo_background = $atts['photo_background'] ? $atts['photo_background'] : $this->get_option_value( 'photo_background' );
	$photo_background2 = $atts['photo_background2'] ? $atts['photo_background2'] : $this->get_option_value( 'photo_background2' );
	$photo_opacity = $atts['photo_opacity'] ? $atts['photo_opacity'] : $this->get_option_value( 'photo_opacity' );
	if( substr($photo_opacity, 0, 1) != "." )
		$photo_opacity = "." . $photo_opacity;
	$photo_shape = $atts['photo_shape'] ? $atts['photo_shape'] : $this->get_option_value( 'photo_shape' );
	$photo_shape = strtolower($photo_shape);
	$hover_background = $atts['hover_background'] ? $atts['hover_background'] : $this->get_option_value( 'hover_background' );
	$hover_background2 = $atts['hover_background2'] ? $atts['hover_background2'] : $this->get_option_value( 'hover_background2' );
	$hover_opacity = $atts['hover_opacity'] ? $atts['hover_opacity'] : $this->get_option_value( 'hover_opacity' );
	if( substr($hover_opacity, 0, 1) != "." )
		$hover_opacity = "." . $hover_opacity;
	$hover_photo_filter_effect = $atts['hover_photo_filter_effect'] ? $atts['hover_photo_filter_effect'] : $this->get_option_value( 'hover_photo_filter_effect' );
	$hover_photo_filter_effect = strtolower($hover_photo_filter_effect);



	$hover_effect = $atts['hover_effect'] ? $atts['hover_effect'] : $this->get_option_value( 'hover_effect' );
	$hover_effect = strtolower($hover_effect);
	
	switch($hover_effect){case "no effect": $hover_effect = "seg-no-effect"; break; case "flinders": $hover_effect = "seg-flinders"; break; case "clayton": $hover_effect = "seg-clayton"; break; case "bells": $hover_effect = "seg-bells"; break; case "swanston": $hover_effect = "seg-swanston"; break; case "caulfield": $hover_effect = "seg-caulfield"; break; case "chapel": $hover_effect = "seg-chapel"; break; case "st kilda": $hover_effect = "seg-st-kilda"; break; case "tamachi": $hover_effect = "seg-tamachi"; break; case "shibuya": $hover_effect = "seg-shibuya"; break; case "asakusa": $hover_effect = "seg-asakusa"; break; case "shinjuku": $hover_effect = "seg-shinjuku"; break; case "tiradentes": $hover_effect = "seg-tiradentes"; break; case "cinquentenario": $hover_effect = "seg-cinquentenario"; break; case "yoyogi": $hover_effect = "seg-yoyogi"; break; case "halong": $hover_effect = "seg-halong"; break; case "chiang": $hover_effect = "seg-chiang"; break; case "saigon": $hover_effect = "seg-saigon"; break; case "angkor": $hover_effect = "seg-angkor"; break; }
	
	$display_animated_lines = $atts['display_animated_lines'] ? $atts['display_animated_lines'] : $this->get_option_value( 'display_animated_lines' );
	$animated_lines_colour = $atts['animated_lines_colour'] ? $atts['animated_lines_colour'] : $this->get_option_value( 'animated_lines_colour' );
	$display_icon_instagram = $atts['display_icon_instagram'] ? $atts['display_icon_instagram'] : $this->get_option_value( 'display_icon_instagram' );
	$icon_instagram_colour = $atts['icon_instagram_colour'] ? $atts['icon_instagram_colour'] : $this->get_option_value( 'icon_instagram_colour' );
	$icon_instagram_effect = $atts['icon_instagram_effect'] ? $atts['icon_instagram_effect'] : $this->get_option_value( 'icon_instagram_effect' );
	$icon_instagram_size = $atts['icon_instagram_size'] ? $atts['icon_instagram_size'] : $this->get_option_value( 'icon_instagram_size' );
	$icon_instagram_size = strtolower($icon_instagram_size);
	$display_photo_description = $atts['display_photo_description'] ? $atts['display_photo_description'] : $this->get_option_value( 'display_photo_description' );
	$photo_description_colour = $atts['photo_description_colour'] ? $atts['photo_description_colour'] : $this->get_option_value( 'photo_description_colour' );
	$photo_description_effect = $atts['photo_description_effect'] ? $atts['photo_description_effect'] : $this->get_option_value( 'photo_description_effect' );
	$photo_description_size = $atts['photo_description_size'] ? $atts['photo_description_size'] : $this->get_option_value( 'photo_description_size' );
	$photo_description_size = strtolower($photo_description_size);
	$photo_description_limit = $atts['photo_description_limit'] ? $atts['photo_description_limit'] : $this->get_option_value( 'photo_description_limit' );
	$display_photo_likes = $atts['display_photo_likes'] ? $atts['display_photo_likes'] : $this->get_option_value( 'display_photo_likes' );
	$photo_likes_colour = $atts['photo_likes_colour'] ? $atts['photo_likes_colour'] : $this->get_option_value( 'photo_likes_colour' );
	$photo_likes_effect = $atts['photo_likes_effect'] ? $atts['photo_likes_effect'] : $this->get_option_value( 'photo_likes_effect' );
	$photo_likes_size = $atts['photo_likes_size'] ? $atts['photo_likes_size'] : $this->get_option_value( 'photo_likes_size' );
	$photo_likes_size = strtolower($photo_likes_size);
	$shadow = $atts['shadow'] ? $atts['shadow'] : $this->get_option_value( 'shadow' );
	$loading_color = $atts['loading_color'] ? $atts['loading_color'] : $this->get_option_value( 'loading_color' );
	$load_more = $atts['load_more'] ? $atts['load_more'] : $this->get_option_value( 'load_more' );
	$load_more_dinamically = $atts['load_more_dinamically'] ? $atts['load_more_dinamically'] : $this->get_option_value( 'load_more_dinamically' );
	$lightbox = $atts['lightbox'] ? $atts['lightbox'] : $this->get_option_value( 'lightbox' );
	$scroll_delay = $atts['scroll_delay'] ? $atts['scroll_delay'] : $this->get_option_value( 'scroll_delay' );
	$scroll_effect_class = $atts['scroll_effect'] ? $atts['scroll_effect'] : $this->get_option_value( 'scroll_effect' );
	switch($scroll_effect_class){case 'No effect': $scroll_effect_class = "seg-scroll-no-effect"; break; case 'Fade': $scroll_effect_class = "seg-scroll-fade"; break; case 'Scale': $scroll_effect_class = "seg-scroll-scale"; break; }
	$display_header = $atts['display_header'] ? $atts['display_header'] : $this->get_option_value( 'display_header' );
	$display_social_icons = $atts['display_social_icons'] ? $atts['display_social_icons'] : $this->get_option_value( 'display_social_icons' );
	$responsive = $atts['responsive'] ? $atts['responsive'] : $this->get_option_value( 'responsive' );
	$header_background = $atts['header_background'] ? $atts['header_background'] : $this->get_option_value( 'header_background' );
	$header_panel_button_colour = $atts['header_panel_button_colour'] ? $atts['header_panel_button_colour'] : $this->get_option_value( 'header_panel_button_colour' );
	$header_text_colour = $atts['header_text_colour'] ? $atts['header_text_colour'] : $this->get_option_value( 'header_text_colour' );
	$custom_css = $this->get_option_value( 'custom_css' );

	switch($photo_effect){case 'grayscale': $photo_effect = "seg-grayscale"; break; case 'sepia': $photo_effect = "seg-sepia"; break; case 'saturate': $photo_effect = "seg-saturate"; break; case 'hue-rotate': $photo_effect = "seg-hue-rotate"; break; case 'invert': $photo_effect = "seg-invert"; break; case 'brightness': $photo_effect = "seg-brightness"; break; case 'contrast': $photo_effect = "seg-contrast"; break; case 'blur': $photo_effect = "seg-blur"; break; case 'normal': $photo_effect = "seg-normal"; break; } 

	switch($hover_photo_filter_effect){case 'grayscale': $hover_photo_filter_effect = "seg-grayscale"; break; case 'sepia': $hover_photo_filter_effect = "seg-sepia"; break; case 'saturate': $hover_photo_filter_effect = "seg-saturate"; break; case 'hue-rotate': $hover_photo_filter_effect = "seg-hue-rotate"; break; case 'invert': $hover_photo_filter_effect = "seg-invert"; break; case 'brightness': $hover_photo_filter_effect = "seg-brightness"; break; case 'contrast': $hover_photo_filter_effect = "seg-contrast"; break; case 'blur': $hover_photo_filter_effect = "seg-blur"; break; case 'normal': $hover_photo_filter_effect = "seg-normal"; break; }
	$hover_photo_filter_effect = $hover_photo_filter_effect . '-hover';
	///// LET`S GENERATE A UNIQUE STRING TOKEN
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < 5 ; $i++) {
	    $randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	$token = $randomString;
	$app_callback = '';
	$idUser = '';
	?>
	<div id="seg-instaportfolio-token-<?php echo $token; ?>" class="seg-instaportfolio token-<?php echo $token; ?>">
		<div class="seg-spinner" style="background-color: <?php echo $loading_color; ?>;"></div>
	</div>
	<script type="text/javascript">
				// ###
				jQuery('.seg-instaportfolio').seg_instaportfolio({
					id_main_div : '<?php echo "#seg-instaportfolio-token-".$token; ?>',
					id_main_div_photos : '<?php echo "seg-instaportfolio-photos-token-".$token; ?>',
					main_div : '<?php echo ".seg-instaportfolio.token-" . $token; ?>',
					access_token : '<?php echo $access_token; ?>',
					username : '<?php echo $username_instagram; ?>',
					filter_users: '<?php echo $filter_users; ?>',
					number_photos : '<?php echo $number_photos; ?>',
					instagram_mode : '<?php echo $instagram_mode; ?>',
					shadow : '<?php echo $shadow; ?>',
					scroll_effect_class : '<?php echo $scroll_effect_class; ?>',
					display_animated_lines : '<?php echo $display_animated_lines; ?>',
					display_icon_instagram : '<?php echo $display_icon_instagram; ?>',
					display_photo_description : '<?php echo $display_photo_description; ?>',
					display_photo_likes : '<?php echo $display_photo_likes; ?>',
					scroll_delay : '<?php echo $scroll_delay; ?>',
					icon_instagram_effect : '<?php echo $icon_instagram_effect; ?>',
					photo_description_effect : '<?php echo $photo_description_effect; ?>',
					photo_description_size : '<?php echo $photo_description_size; ?>',
					photo_description_limit : '<?php echo $photo_description_limit; ?>',
					photo_likes_effect : '<?php echo $photo_likes_effect; ?>',
					width : '<?php echo $width; ?>',
					height : '<?php echo $height; ?>',
					fixed_height: '<?php echo $fixed_height; ?>',
					last_row: '<?php echo $last_row; ?>',
					padding: '<?php echo $padding; ?>',
					photo_background : '<?php echo $photo_background; ?>',
					photo_background2: '<?php echo $photo_background2; ?>',
					photo_opacity : '<?php echo $photo_opacity; ?>',
					photo_effect : '<?php echo $photo_effect; ?>',
					hover_effect : '<?php echo $hover_effect; ?>',
					hover_photo_filter_effect : '<?php echo $hover_photo_filter_effect; ?>',
					photo_shape : '<?php echo $photo_shape; ?>',
					hover_background : '<?php echo $hover_background; ?>',
					hover_background2: '<?php echo $hover_background2; ?>',
					animated_lines_colour : '<?php echo $animated_lines_colour; ?>',
					icon_instagram_colour : '<?php echo $icon_instagram_colour; ?>',
					icon_instagram_size : '<?php echo $icon_instagram_size; ?>',
					photo_description_colour : '<?php echo $photo_description_colour; ?>',
					photo_likes_colour : '<?php echo $photo_likes_colour; ?>',
					photo_likes_size : '<?php echo $photo_likes_size; ?>',
					hover_opacity : '<?php echo $hover_opacity; ?>',
					custom_css : '<?php echo $custom_css; ?>',
					load_more : '<?php echo $load_more; ?>',
					load_more_dinamically : '<?php echo $load_more_dinamically; ?>',
					lightbox : '<?php echo $lightbox; ?>',
					display_header : '<?php echo $display_header; ?>',
					header_background : '<?php echo $header_background; ?>',
					header_panel_button_colour : '<?php echo $header_panel_button_colour; ?>',
					header_text_colour : '<?php echo $header_text_colour; ?>',
					display_social_icons : '<?php echo $display_social_icons; ?>',
					responsive : '<?php echo $responsive; ?>',
					loading_color: '<?php echo $loading_color; ?>',
				});
		</script>