<?php

class HFSocialFollowersCounter {
	
	private $version = "1.0";
	private $hf_cache_option_name = "hffcounter_cached";
	private $hf_expire_name = "hffcounter_expire";
	private $updater_instance;
	
	function __construct() {

		// include updater class
		include_once (HF_PLUGIN_ROOT . 'lib/core/hf-social-followers-counter-updater.php');
		
		// include visual draw class
		include_once (HF_PLUGIN_ROOT . 'lib/core/hf-social-followers-counter-draw.php');
		
		add_shortcode( 'hello-followers' , array ( $this , 'register_plugin_shortcodes' ) );
		add_shortcode( 'hello-total-followers' , array ( $this , 'register_plugin_shortcode_totalfans' ) );
		
		add_action( 'wp_enqueue_scripts' , array ( $this , 'register_front_assets' ), 9);
		add_action( 'wp_head', array($this, 'generate_customizer'));
		
	}
		
	public function register_front_assets() {
		wp_register_style ( 'hello-followers', HF_PLUGIN_URL . '/assets/css/hello-followers.css', array (), HF_VERSION );
		wp_enqueue_style ( 'hello-followers' );
		
		wp_register_style ( 'hello-followers-animation', HF_PLUGIN_URL . '/assets/css/hover.css', array (), HF_VERSION );
		wp_enqueue_style ( 'hello-followers-animation' );
		
				
	}
	
	public function register_plugin_shortcodes($attrs) {
		$default_options = HFSocialFollowersCounterHelper::default_instance_settings();
		
		
		$attrs = shortcode_atts( $default_options , $attrs );
		//print_r($attrs);
		
		ob_start();
		HFSocialFollowersCounterDraw::draw_followers($attrs, true);
		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
	
	public function generate_customizer() {

		$output = '';
		
		// single color
		$hf_monotone_bgcolor1 = HFSocialFollowersCounterHelper::get_option('monotone_bgcolor1');
		$hf_monotone_bgcolor2 = HFSocialFollowersCounterHelper::get_option('monotone_bgcolor2');
		$hf_monotone_color1 = HFSocialFollowersCounterHelper::get_option('monotone_color1');
		$hf_monotone_color2 = HFSocialFollowersCounterHelper::get_option('monotone_color2');
		
		$all_networks = HFSocialFollowersCounterHelper::available_social_networks();

			if ($hf_monotone_bgcolor1 != '') {
				$output .= '.hf-customizer-mono li[class^="hf-"] .hf-network { background-color: '.$hf_monotone_bgcolor1.'!important;}';
			}
			if ($hf_monotone_bgcolor2 != '') {
				$output .= '.hf-customizer-mono li[class^="hf-"] .hf-network:hover { background-color: '.$hf_monotone_bgcolor2.'!important;}';
			}
			if ($hf_monotone_color1 != '') {
				$output .= '.hf-customizer-mono li[class^="hf-"] .hf-network { color: '.$hf_monotone_color1.'!important;}';
			}
			if ($hf_monotone_color2 != '') {
				$output .= '.hf-customizer-mono li[class^="hf-"] .hf-network:hover { color: '.$hf_monotone_color2.'!important;}';
			}
				
		
		foreach ($all_networks as $network => $title) {
			// multi color
			$hf_network_bgcolor1 = HFSocialFollowersCounterHelper::get_option($network.'_bgcolor1');
			$hf_network_bgcolor2 = HFSocialFollowersCounterHelper::get_option($network.'_bgcolor2');
			$hf_network_color1 = HFSocialFollowersCounterHelper::get_option($network.'_color1');
			$hf_network_color2 = HFSocialFollowersCounterHelper::get_option($network.'_color2');

			if ($hf_network_bgcolor1 != '') {
				$output .= '.hf-customizer-color .hf-'.$network.' .hf-network { background-color: '.$hf_network_bgcolor1.'!important;}';
			}
			if ($hf_network_bgcolor2 != '') {
				$output .= '.hf-customizer-color .hf-'.$network.' .hf-network:hover { background-color: '.$hf_network_bgcolor2.'!important;}';
			}
			if ($hf_network_color1 != '') {
				$output .= '.hf-customizer-color .hf-'.$network.' .hf-network { color: '.$hf_network_color1.'!important;}';
			}
			if ($hf_network_color2 != '') {
				$output .= '.hf-customizer-color .hf-'.$network.' .hf-network:hover { color: '.$hf_network_color2.'!important;}';
			}
				
		}
		
		// custom layout builder
		for ($layout_id=1;$layout_id <= 5;$layout_id++) {
			$columns = HFSocialFollowersCounterHelper::get_option('columns_layout'.$layout_id);
			//print " columns for ".$layout_id. ' = '.$columns;
			$width = '100%';
			
			if ($columns == '2') { $width = '50';}
			if ($columns == '3') { $width = '33.333'; }
			if ($columns == '4') { $width = '25'; }
			if ($columns == '5') { $width = '20'; }
			if ($columns == '6') {
				$width = '16.666';
			}
						
			$output .= '.hf-container.hf-col-user'.$layout_id.' li { width: '.$width.'% !important; display: inline-block; }';
			foreach ($all_networks as $network => $title) {
				$network_width = HFSocialFollowersCounterHelper::get_option('column_layout'.$layout_id.'_'.$network);
				
				if ($network_width != '' && $network_width != '1') {
					$network_percentage = $network_width * $width;
					
					$output .= '.hf-container.hf-col-user'.$layout_id.' li.hf-'.$network.' { width: '.$network_percentage.'% !important; display: inline-block; }';
					
				}
			}	
		}
		
		if ($output != '') {
			echo '<style type="text/css">'.$output.'</style>';
		}
	}
	
	/**
	 * register_plugin_shortcode_totalfans
	 * 
	 * handle [easy-total-fans] shortcode
	 * @since 3.4
	 * 
	 */
	public function register_plugin_shortcode_totalfans($attrs) {

		$counters = $this->get_followers();
		
		$total = 0;
		foreach ($counters as $network => $follow_count) {
			if (intval($follow_count) > 0) {
				$total += $follow_count;
			}
		}
		
		echo HFSocialFollowersCounterDraw::followers_number($total);
	}
	
	// -- social counters updater
	/**
	 * require_counter_update
	 * 
	 * check and make update of social counters uppon cache expiration
	 * 
	 * @return boolean
	 * @since 3.4
	 */
	public function require_counter_update() {
		$expire_time = get_option ( $this->hf_expire_name );
		$now = time ();
		
		$is_alive = ($expire_time > $now);
				
		if (true == $is_alive) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * get_followers
	 * 
	 * get value of followers as object
	 * 
	 * @since 3.4
	 * @return array
	 */
	public function get_followers() {
		// check previously stored time for expiration based on user settings
		$request_update = $this->require_counter_update();
		
		$counters = array();
		
		// if it is not required we load the counters from cache
		if (!$request_update) {
			$counters = get_option ( $this->hf_cache_option_name );
			
			// does not exist cached counters - initiate full counter update
			if (!isset($counters)) {
				$request_update = true;
			}
			else {
				if (!is_array($counters)) {
					$request_update = true;
				}
			}
		}
		
		if ($request_update) {
			$counters = $this->update_all_followers();
		}
		
		return $counters;
	}
	
	public function settle_immediate_update() {
		delete_option($this->hf_expire_name);
	}
	
	public function updater() {
		if (!$this->updater_instance) {
			$this->updater_instance = new HFSocialFollowersCounterUpdater;
		}
		
		return $this->updater_instance;
	}
	
	/**
	 * update_all_followers
	 * 
	 * make full counter update of all active social networks from the list
	 * 
	 * @since 3.4
	 */
	public function update_all_followers() {
		$counters = array();
		
		$require_check_in_cache = false;
		foreach ( $this->active_social_networks() as $social ) {
			switch ($social) {
				case 'twitter' :
					$count = $this->updater()->update_twitter ();
					break;
				case 'facebook' :
					$count = $this->updater()->update_facebook ();
					break;
				case 'google' :
					$count = $this->updater()->update_googleplus ();
					break;
				case 'pinterest' :
					$count = $this->updater()->update_pinterest ();
					break;
				case 'linkedin' :
					$count = $this->updater()->update_linkedin ();
					break;
				case 'vimeo' :
					$count = $this->updater()->update_vimeo ();
					break;
				case 'github' :
					$count = $this->updater()->update_github ();
					break;
				case 'dribbble' :
					$count = $this->updater()->update_dribbble ();
					break;
				case 'envato' :
					$count = $this->updater()->update_envato ();
					break;
				case 'soundcloud' :
					$count = $this->updater()->update_soundcloud ();
					break;
				case 'behance' :
					$count = $this->updater()->update_behance ();
					break;
				case 'foursquare' :
					$count = $this->updater()->update_foursquare ();
					break;
				case 'forrst' :
					$count = $this->updater()->update_forrst ();
					break;
				case 'mailchimp' :
					$count = $this->updater()->update_mailchimp ();
					break;
				case 'delicious' :
					$count = $this->updater()->update_delicious ();
					break;
				case 'instgram':
				case 'instagram' :
					$count = $this->updater()->update_instagram ();
					break;
				case 'youtube' :
					$count = $this->updater()->update_youtube ();
					break;
				case 'vk' :
					$count = $this->updater()->update_vk ();
					break;
				case 'rss' :
					$count = $this->updater()->update_rss ();
					break;
				case 'vine' :
					$count = $this->updater()->update_vine ();
					break;
				case 'tumblr' :
					$count = $this->updater()->update_tumblr ();
					break;
				case 'slideshare' :
					$count = $this->updater()->update_slideshare ();
					break;
				case '500px' :
					$count = $this->updater()->update_c500Px ();
					break;
				case 'flickr' :
					$count = $this->updater()->update_flickr ();
					break;
				case 'wp_posts' :
					$count = $this->updater()->update_wpposts ();
					break;
				case 'wp_comments' :
					$count = $this->updater()->update_wpcomments ();
					break;
				case 'wp_users' :
					$count = $this->updater()->update_wpusers ();
					break;
				case 'audioboo' :
					$count = $this->updater()->update_audioboo ();
					break;
				case 'steamcommunity' :
					$count = $this->updater()->update_steamcommunity ();
					break;
				case 'weheartit' :
					$count = $this->updater()->update_weheartit ();
					break;
				case 'feedly' :
					$count = $this->updater()->update_feedly ();
					break;
				case 'love' :
					$count = $this->updater()->update_love ();
					break;
				case 'spotify':
					$count = $this->updater()->update_spotify();
					break;
				case 'twitch':
					$count = $this->updater()->update_twitch();
					break;
				case 'mymail':
					$count = $this->updater()->update_mymail();
					break;
				case 'mailpoet':
					$count = $this->updater()->update_mailpoet();
					break;
				default :
					$count = 0;
					break;
			}
			
			$counters[$social] = $count;
			
			if (empty($count)) {
				$require_check_in_cache = true;
			}
		}
		
		// validete and apply active manual user values
		$is_active_selfcounts = HFSocialFollowersCounterHelper::get_option('uservalues');
		if ($is_active_selfcounts) {
			foreach ( $this->active_social_networks() as $social ) {
				$user_value = HFSocialFollowersCounterHelper::get_option($social.'_uservalue');
				$count = isset($counters[$social]) ? $counters[$social] : 0;
				
				if (intval($user_value) > intval($count)) {
					$count = $user_value;
					$counters[$social] = $count;
				}
			}
		}
		
		if ($require_check_in_cache) {
			// apply additional check for previously cached counters for blanked values
			$cached_counters = get_option ( $this->hf_cache_option_name );
			
			foreach ( $this->active_social_networks() as $social ) {
				$prev_value = isset($cached_counters[$social]) ? $cached_counters[$social] : 0;
				$new_value = isset($counters[$social]) ? $counters[$social] : 0;
				
				if (intval($new_value) < intval($prev_value)) {
					$counters[$social] = $prev_value;
				}
			}
		}
		
		$expire_time = HFSocialFollowersCounterHelper::get_option ( 'update' );
		
		update_option ( $this->hf_cache_option_name, $counters );
		update_option ( $this->hf_expire_name, (time () + ($expire_time * 60)) );
		
		// Profile Analytics Bridge
		if (class_exists('HFFollowersCounterBridge')) {
			foreach ( $this->active_social_networks() as $social ) {
				$new_value = isset($counters[$social]) ? $counters[$social] : 0;

				HFFollowersCounterBridge::log_single_network($social, $new_value);
			}
		}
		
		return $counters;
	}
	
	/**
	 * active_social_networks
	 * 
	 * Generate list of available social networks 
	 * @return array
	 * @since 3.4
	 */
	public function active_social_networks() {
		$networks_order = HFSocialFollowersCounterHelper::get_active_networks_order();
		$networks = HFSocialFollowersCounterHelper::get_active_networks();
		
		$result = array ();
		
		if (!is_array($networks_order)) {
			$networks_order = array();
		}
		
		foreach ( $networks_order as $social ) {
			if (in_array($social, $networks)) {
				if ($this->is_properly_configured ( $social )) {
						
					$result [] = $social;
				}
			}
		}

		return $result;
	}
	
	public function active_layout_social_networks($layout_id) {
		$networks_order = HFSocialFollowersCounterHelper::get_active_networks_for_layout_order($layout_id);
		
		if (!is_array($networks_order)) {
			$networks_order = HFSocialFollowersCounterHelper::get_active_networks_order();
		}
		
		$networks = HFSocialFollowersCounterHelper::get_active_networks_for_layout($layout_id);
		if (!is_array($networks)) {
			$networks = HFSocialFollowersCounterHelper::get_active_networks();
		}
		
		$result = array ();
		
		foreach ( $networks_order as $social ) {
			if (in_array ( $social, $networks )) {
				if ($social == 'total') {
					$result [] = $social;
				} else {
					if ($this->is_properly_configured ( $social )) {
						
						$result [] = $social;
					}
				}
			}
		}		
		
		return $result;
	}
	
	/**
	 * is_properly_configured
	 * 
	 * Check active social networks to ensure is the activated networks properly set
	 * 
	 * @param string $social
	 * @return boolean
	 */
	private function is_properly_configured($social) {
	
		switch ($social) {
				
			case 'mailchimp' :
				return HFSocialFollowersCounterHelper::get_option ( $social . '_list_id' );
				break;
			case 'rss' :
				return HFSocialFollowersCounterHelper::get_option ( $social . '_link' );
				break;
			case 'feedly' :
				return HFSocialFollowersCounterHelper::get_option ( $social . '_url' );
				break;
			case 'vine' :
			case 'slideshare' :
			case '500px' :
				return HFSocialFollowersCounterHelper::get_option ( $social . '_username' );
				break;
			case 'tumblr' :
				return HFSocialFollowersCounterHelper::get_option ( $social . '_basename' );
				break;
			case 'wp_posts' :
			case 'wp_comments' :
			case 'wp_users' :
			case 'love':
				return true;
				break;
			default :
				return HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				break;
		}
	}
	
	/**
	 * create_follow_address
	 * 
	 * Generate social follow address based on user settings
	 * 
	 * @param string $social
	 * @return string
	 * @since 3.4
	 */
	public static function create_follow_address($social) {
	
		switch ($social) {
			case 'facebook' :
				return 'https://www.facebook.com/' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				break;
			case 'twitter' :
				return 'https://www.twitter.com/' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				break;
			case 'google' :
				return 'https://plus.google.com/' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				break;
			case 'pinterest' :
				return 'https://www.pinterest.com/' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				break;
			case 'linkedin' :
				return HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				break;
			case 'github' :
				return 'http://github.com/' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				break;
			case 'vimeo' :
				if (HFSocialFollowersCounterHelper::get_option ( $social . '_account_type', 'channel' ) == 'user') {
					{
						$vimeo_id = trim ( HFSocialFollowersCounterHelper::get_option ( $social . '_id' ) );
	
						if (preg_match ( '/^[0-9]+$/', $vimeo_id )) {
							return 'http://vimeo.com/user' . $vimeo_id;
						} else {
							return 'http://vimeo.com/' . $vimeo_id;
						}
					}
				} else {
					return 'http://vimeo.com/channels/' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				}
				break;
			case 'dribbble' :
				return 'http://dribbble.com/' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				break;
			case 'soundcloud' :
				return 'https://soundcloud.com/' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				break;
			case 'behance' :
				return 'http://www.behance.net/' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				break;
			case 'foursquare' :
				if (intval ( HFSocialFollowersCounterHelper::get_option ( $social . '_id' ) ) && intval ( HFSocialFollowersCounterHelper::get_option ( $social . '_id' ) ) == HFSocialFollowersCounterHelper::get_option ( $social . '_id' )) {
					return 'https://foursquare.com/user/' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				} else {
					return 'https://foursquare.com/' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				}
				break;
			case 'forrst' :
				return 'http://forrst.com/people/' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				break;
			case 'mailchimp' :
				return HFSocialFollowersCounterHelper::get_option ( $social . '_list_url' );
				break;
			case 'delicious' :
				return 'https://delicious.com/' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				break;
			case 'instgram' :
			case 'instagram' :
				return 'http://instagram.com/' . HFSocialFollowersCounterHelper::get_option ( $social . '_username' );
				break;
			case 'youtube' :
				return 'https://www.youtube.com/' . HFSocialFollowersCounterHelper::get_option ( $social . '_account_type' ) . '/' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				break;
			case 'envato' :
				$ref = '';
				if (HFSocialFollowersCounterHelper::get_option ( $social . '_ref' )) {
					$ref = '?ref=' . HFSocialFollowersCounterHelper::get_option ( $social . '_ref' );
				}
				return 'http://www.' . HFSocialFollowersCounterHelper::get_option ( $social . '_site' ) . '.net/user/' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' ) . $ref;
				break;
			case 'vk' :
				$account_type = HFSocialFollowersCounterHelper::get_option ( $social . '_account_type' );
				if ($account_type == "community") {
					return 'http://www.vk.com/' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				}
				else {
					return 'http://www.vk.com/id' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				}
				break;
			case 'rss' :
				return HFSocialFollowersCounterHelper::get_option ( $social . '_link' );
				break;
			case 'vine' :
				return 'https://vine.co/' . HFSocialFollowersCounterHelper::get_option ( $social . '_username' );
				break;
			case 'tumblr' :
				$basename2arr = explode ( '.', HFSocialFollowersCounterHelper::get_option ( $social . '_basename' ) );
				if ($basename2arr == 'www')
					return 'http://' . HFSocialFollowersCounterHelper::get_option ( $social . '_basename' );
				else
					return 'http://www.tumblr.com/follow/' . @$basename2arr [0];
				break;
			case 'slideshare' :
				return 'http://www.slideshare.net/' . HFSocialFollowersCounterHelper::get_option ( $social . '_username' );
				break;
			case '500px' :
				return 'http://500px.com/' . HFSocialFollowersCounterHelper::get_option ( $social . '_username' );
				break;
			case 'flickr' :
				return 'https://www.flickr.com/photos/' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				break;
			case 'wp_posts' :
			case 'wp_users' :
			case 'wp_comments' :
				return HFSocialFollowersCounterHelper::get_option ( $social . '_url' );				
				break;
			case 'audioboo' :
				return 'https://audioboo.fm/users/' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				break;
			case 'steamcommunity' :
				return 'http://steamcommunity.com/groups/' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				break;
			case 'weheartit' :
				return 'http://weheartit.com/' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				break;
			case 'love' :
				return HFSocialFollowersCounterHelper::get_option ( $social . '_url' );
				break;
			case 'total' :
				return HFSocialFollowersCounterHelper::get_option ( $social . '_url' );
				break;
			case 'feedly' :
				return 'http://feedly.com/i/subscription/feed' . urlencode ( '/' . HFSocialFollowersCounterHelper::get_option ( $social . '_url' ) );
				break;
			case 'mymail':
				return HFSocialFollowersCounterHelper::get_option ( $social . '_url' );
				break;
			case 'mailpoet':
				return HFSocialFollowersCounterHelper::get_option ( $social . '_url' );
				break;
			case 'twitch' :
				return 'http://www.twitch.tv/' . HFSocialFollowersCounterHelper::get_option ( $social . '_id' ).'/profile';
				break;
			case 'spotify' :
				return HFSocialFollowersCounterHelper::get_option ( $social . '_id' );
				break;
		}
	}
}