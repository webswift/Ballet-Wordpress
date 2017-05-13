<?php

/**
 * HFSocialFollowersCounterDraw
 * 
 * Followers counter draw engine
 * 
 * @author creoworx
 * @package HelloFollowers
 * @since 1.0
 *
 */
class HFSocialFollowersCounterDraw {
	
	public static function followers_number($count) {
		$format = HFSocialFollowersCounterHelper::get_option ( 'format' );
		
		$result = "";
		
		switch ($format) {
			case 'full' :
				$result = number_format ( $count, 0, '', ',' );
				break;
			case 'short' :
				$result = self::followers_number_shorten ( $count );
				break;
			default :
				$result = $count;
				break;
		}
		
		return $result;
	}
	
	public static function followers_number_shorten($count) {
		if (! is_numeric ( $count ))
			return $count;
		
		if ($count >= 1000000) {
			return round ( ($count / 1000) / 1000, 1 ) . "M";
		} elseif ($count >= 100000) {
			return round ( $count / 1000, 0 ) . "k";
		} else if ($count >= 1000) {
			return round ( $count / 1000, 1 ) . "k";
		} else {
			return @number_format ( $count );
		}
	}
	
	/**
	 * draw_followers
	 *
	 * Display instance of generated followers counter
	 *
	 * @param $options array       	
	 * @param $draw_title boolean       	
	 * @since 1.0
	 */
	public static function draw_followers($options, $draw_title = false) {
		$hide_title = isset ( $options ['hide_title'] ) ? $options ['hide_title'] : 0;
		if (intval ( $hide_title ) == 1) {
			$draw_title = false;
		}	
		
		$instance_title = isset ( $options ['title'] ) ? $options ['title'] : '';
		$instance_new_window = isset ( $options ['new_window'] ) ? $options ['new_window'] : 0;
		$instance_nofollow = isset ( $options ['nofollow'] ) ? $options ['nofollow'] : 0;
		$instance_show_total = isset ( $options ['show_total'] ) ? $options ['show_total'] : 0;
		$instance_total_type = isset ( $options ['total_type'] ) ? $options ['total_type'] : 'button_single';
		$instance_columns = isset ( $options ['columns'] ) ? $options ['columns'] : 3;
		$instance_template = isset ( $options ['template'] ) ? $options ['template'] : 'flat';
		$instance_animation = isset ( $options ['animation'] ) ? $options ['animation'] : '';
		$instance_bgcolor = isset ( $options ['bgcolor'] ) ? $options ['bgcolor'] : '';
		$instance_nospace = isset ( $options ['nospace'] ) ? $options ['nospace'] : 0;

		
		$instance_cover = isset ( $options ['cover'] ) ? $options ['cover'] : 0;
		$instance_cover_bgcolor = isset ( $options ['cover_bgcolor'] ) ? $options ['cover_bgcolor'] : '';
		$instance_cover_bgimage = isset ( $options ['cover_bgimage'] ) ? $options ['cover_bgimage'] : '';
		$instance_cover_title = isset ( $options ['cover_title'] ) ? $options ['cover_title'] : '';
		$instance_cover_text = isset ( $options ['cover_text'] ) ? $options ['cover_text'] : '';
		$instance_cover_image = isset ( $options ['cover_image'] ) ? $options ['cover_image'] : '';
		$instance_cover_style = isset ( $options ['cover_style'] ) ? $options ['cover_style'] : 'light';
		$instance_cover_image_style = isset ( $options ['cover_image_style'] ) ? $options ['cover_image_style'] : 'round';
		
		$instace_customizer = isset($options['customizer']) ? $options['customizer'] : '';
		
		$instance_networks = isset($options['networks']) ? $options['networks'] : '';
		
		if (!empty($instace_customizer)) {
			$instace_customizer = ' hf-customizer-'.$instace_customizer;
		}
		
		if (!empty($instance_cover_bgcolor)) {
			$instance_cover_bgcolor = 'background-color:'.$instance_cover_bgcolor.';';
		}
		if (!empty($instance_cover_bgimage)) {
			$instance_cover_bgimage = 'background-image: url('.$instance_cover_bgimage.');';
		}
		
		// compatibility with previous template slugs
		if (!empty($instance_template)) {
			if ($instance_template == "lite") {
				$instance_template = "light";
			}
			if ($instance_template == "grey-transparent") {
				$instance_template = "grey";
			}
			if ($instance_template == "color-transparent") {
				$instance_template = "color";
			}
		}
		
		$class_template = (! empty ( $instance_template )) ? " hf-template-" . $instance_template : '';
		$class_animation = (! empty ( $instance_animation )) ? " hf-icon-" . $instance_animation : '';
		$class_columns = (! empty ( $instance_columns )) ? " hf-col-" . $instance_columns : '';
		$class_nospace = (intval ( $instance_nospace ) == 1) ? " hf-nospace" : "";
		
		$style_bgcolor = (! empty ( $instance_bgcolor )) ? ' style="background-color:' . $instance_bgcolor . ';"' : '';
		
		$link_nofollow = (intval ( $instance_nofollow ) == 1) ? ' rel="nofollow"' : '';
		$link_newwindow = (intval ( $instance_new_window ) == 1) ? ' target="_blank"' : '';
		
		// loading animations
		if (! empty ( $class_animation )) {
		}
		
		// followers main element
		printf ( '<div class="hf-container%1$s%2$s%3$s%5$s"%4$s>', '', $class_columns, $class_template.$instace_customizer, $style_bgcolor, $class_nospace );
		
		if ($draw_title && ! empty ( $instance_title )) {
			printf ( '<h3>%1$s</h3>', $instance_title );
		}
		
		
		
		// get current state of followers counter
		$followers_count = hf_followers ()->get_followers ();
		
		$display_total = (intval ( $instance_show_total ) == 1) ? true : false;
		$total_followers = 0;
		foreach ( $followers_count as $network => $count ) {
			if (intval ( $count ) > 0) {
				$total_followers += intval ( $count );
			}
		}
		
		
		$active_social_networks = hf_followers()->active_social_networks();
		$user_layout_active = false;
		$user_layout_total = 0;
		if (strpos($instance_columns, 'user') !== false) {
			$layout_id = str_replace('user', '', $instance_columns);
			$active_social_networks = hf_followers()->active_layout_social_networks($layout_id);
			$user_layout_active = true;
			
			foreach ( $active_social_networks as $social ) {
				$social_followers_counter = isset ( $followers_count [$social] ) ? $followers_count [$social] : 0;
				$user_layout_total += intval($social_followers_counter);
			}
		}
		
		if (!empty($instance_networks)) {
			$active_social_networks = explode(',', $instance_networks);
			$user_layout_active = true;
			$user_layout_total = 0;
			
			foreach ( $active_social_networks as $social ) {
				$social_followers_counter = isset ( $followers_count [$social] ) ? $followers_count [$social] : 0;
				$user_layout_total += intval($social_followers_counter);
			}
		}
		
		if ($user_layout_active) {
			$total_followers = $user_layout_total;
		}
		
		
		// cover box
		if (intval($instance_cover) == 1) {
			echo '<div class="hf-cover hf-cover-style-'.$instance_cover_style.'" style="'.$instance_cover_bgcolor.$instance_cover_bgimage.'">';
			echo self::cover_image($instance_cover_image, $instance_cover_image_style);
			echo self::cover_title($instance_cover_title, self::followers_number ( $total_followers ));
			echo self::cover_text($instance_cover_text, self::followers_number ( $total_followers ));
		
			echo '</div>';
		}
		
		if ($display_total && $instance_total_type == "text_before") {
			printf ( '<div class="hf-totalastext">%1$s %2$s</div>', self::followers_number ( $total_followers ), HFSocialFollowersCounterHelper::get_option ( 'total_text' ) );
		}
		
		echo '<ul>';
		
				
		foreach ( $active_social_networks as $social ) {
			$social_followers_text = HFSocialFollowersCounterHelper::get_option ( $social . '_text' );
			$social_followers_counter = isset ( $followers_count [$social] ) ? $followers_count [$social] : 0;
			
			$social_display = $social;
			if ($social_display == "instgram") {
				$social_display = "instagram";
			}
			
			printf ( '<li class="hf-%1$s">', $social_display );
			
			$follow_url = hf_followers()->create_follow_address ( $social );
			if (! empty ( $follow_url )) {
				printf ( '<a href="%1$s"%2$s%3$s>', $follow_url, $link_newwindow, $link_nofollow );
			}
			
			echo '<div class="hf-network">';
			printf ( '<i class="hf-icon hf-icon-%1$s%2$s"></i>', $social_display, $class_animation );
			
			if ($social == 'total' && $user_layout_active) {
				printf ( '<span class="hf-followers-count">%1$s</span>', self::followers_number ( $user_layout_total ) );
			}
			else {
				printf ( '<span class="hf-followers-count">%1$s</span>', self::followers_number ( $social_followers_counter ) );
			}
			printf ( '<span class="hf-followers-text">%1$s</span>', $social_followers_text );
			echo '</div>';
			
			if (! empty ( $follow_url )) {
				echo '</a>';
			}
			echo '</li>';
		}
		
		if ($display_total && $instance_total_type == "button_single" && !$user_layout_active) {
			$social = 'total';
			printf ( '<li class="hf-%1$s">', $social );
			echo '<div class="hf-network">';
			printf ( '<i class="hf-icon  hf-icon-%1$s%2$s"></i>', $social, $class_animation );
			printf ( '<span class="hf-followers-count">%1$s</span>', self::followers_number ( $total_followers ) );
			printf ( '<span class="hf-followers-text">%1$s</span>', HFSocialFollowersCounterHelper::get_option ( 'total_text' ) );
			echo '</div>';
			echo '</li>';
		}
		
		echo '</ul>';
		
		if ($display_total && $instance_total_type == "text_after") {
			printf ( '<div class="hf-totalastext">%1$s %2$s</div>', self::followers_number ( $total_followers ), HFSocialFollowersCounterHelper::get_option ( 'total_text' ) );
		}
		
		echo '</div>';
		// followers: end
	}
	
	public static function cover_title($title, $fans_total = '') {
	
		if (!empty($title)) {
			$title = str_replace('{TOTAL}', $fans_total, $title);
			return '<div class="hf-cover-title">'.$title.'</div>';
		}
		else {
			return "";
		}
	}
	
	public static function cover_text($title, $fans_total = '') {
	
		if (!empty($title)) {
			$title = str_replace('{TOTAL}', $fans_total, $title);
			return '<div class="hf-cover-text">'.$title.'</div>';
		}
		else {
			return "";
		}
	}
	
	public static function cover_image($value, $style) {
	
		if (!empty($style)) {
			$style = " hf-cover-image-".$style;
		}
	
		if (!empty($value)) {
			return '<div class="hf-cover-image'.$style.'"><img class="" src="'.$value.'" /></div>';
		}
		else {
			return "";
		}
	}
}