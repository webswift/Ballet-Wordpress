<?php

class HFFollowersCounterBridge {
	
	public static function log_profile_values_on_install() {
		$followers_count = hf_followers ()->get_followers ();
		
		foreach ( hf_followers()->active_social_networks () as $social ) {
			$social_followers_text = HFSocialFollowersCounterHelper::get_option ( $social . '_text' );
			$social_followers_counter = isset ( $followers_count [$social] ) ? $followers_count [$social] : 0;
			
			self::log_single_network($social, $social_followers_counter);
		}
	}
	
	public static function log_single_network($network, $value) {
		$date = date("Y-m-d");
		
		HFSPADatabase::log($date, $network, $value);
	}
	
	public static function log_single_network_for_date($date, $network, $value) {
		HFSPADatabase::log($date, $network, $value);
	}
	
	public static function generate_dummy_data ($from_date, $to_date) {
		$networks = HFSocialFollowersCounterHelper::available_social_networks(false);
		
		$initial_values = array();
		
		// set initial dummy data
		foreach ($networks as $key => $name) {
			$initial_values[$key] = rand(500, 3000);
		}
		
		while ($from_date <= $to_date) {
			
			foreach ($initial_values as $network => $value) {
				$value = intval($value);
				$value += rand(4, 38);
				
				HFFollowersCounterBridge::log_single_network_for_date($from_date, $network, $value);
				$initial_values[$network] = $value;
			}
			
			$from_date = HFSPAHelpers::next_date($from_date);
		}
	}
}

?>