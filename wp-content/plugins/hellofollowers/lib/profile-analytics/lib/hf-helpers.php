<?php
class HFSPAHelpers {
	public static function previous_date($date) {
		
		$yesterday = date('Y-m-d', strtotime($date . " - 1 day"));
		
		return $yesterday;
	}
	
	public static function next_date($date) {
	
		$yesterday = date('Y-m-d', strtotime($date . " + 1 day"));
	
		return $yesterday;
	}
	
	public static function previous_date_with_offset($date, $offset) {
		$days_ago = date('Y-m-d', strtotime('-'.$offset.' days', strtotime($date)));
		
		return $days_ago;
	}
	
	public static function value_diff($new_value, $previous_value) {
		$new_value = intval($new_value);
		$previous_value = intval($previous_value);
		
		if ($previous_value == 0) {
			return 0;
		}
		else {
			return $new_value - $previous_value;
		}
	}
	
	public static function value_diff_text($diff) {
		$text = __('No change', 'hellofollowers');
		
		if ($diff < 0) {
			$diff = abs($diff);
			$text = number_format(intval($diff)).' '.__(' Lost', 'hellofollowers');
		}
		else if ($diff > 0) {
			$text = number_format(intval($diff)).' '.__(' New', 'hellofollowers');
		}
		
		return $text;
	}
	
	public static function value_diff_text_only ($diff) {
		$text = "No change";
		if ($diff < 0) {
			$text = __(' Lost', 'hellofollowers');
		}
		else if ($diff > 0) {
			$text = __(' New', 'hellofollowers');
		}
		
		return $text;
	}
	
	public static function previous_month_date($date) {
		$yesterday = date('Y-m-d', strtotime($date . " - 1 month"));
		
		return $yesterday;
	}
	
	public static function get_period_fans_change_object($from_date, $to_date, $network = '', $as_day_number = false, $network_list = array()) {
		// get the database saved values;
		$data_object = HFSPADatabase::get_date_range_records($from_date, $to_date, $network, $network_list);

		$output_values = array();
		
		$work_date = $from_date;
		
		while ( $work_date <= $to_date ) {
				
			$current_value = 0;
				
			if (isset ( $data_object [$work_date] )) {
				$current_value = $data_object [$work_date];
			} else {
				$current_value = '0';
			}
				
			$output_values[$work_date] = $current_value;
				
			$work_date = date ( 'Y-m-d', strtotime ( $work_date . ' +1 day' ) );
		
		}
		
		$changes_values = array();
		$work_date = $from_date;
		$previous_value = -1;
		$day_number = 1;
		while ( $work_date <= $to_date ) {
		
			$current_value = 0;
		
			if (isset ( $data_object [$work_date] )) {
				$current_value = $data_object [$work_date];
			} else {
				$current_value = '0';
			}
			
			if ($previous_value == - 1) {
				if ($as_day_number) {
					$changes_values ["day".$day_number] = 0;
				}
				else {
					$changes_values [$work_date] = 0;
				}
				$previous_value = $current_value;
			} else {
				if (intval($previous_value) != 0 && intval($current_value == 0)) {
					$data_object[$work_date] = $previous_value;
				}
				
				$diff = intval ( $current_value ) - intval ( $previous_value );
				if (intval($previous_value) == 0) { $diff = 0; }
				if (intval($current_value) == 0) { $diff = 0; }
				if ($as_day_number) {
					$changes_values ["day".$day_number] = $diff;
				}
				else {
					$changes_values [$work_date] = $diff;
				}
				$previous_value = $current_value;
			}
		
			$work_date = date ( 'Y-m-d', strtotime ( $work_date . ' +1 day' ) );		
			$day_number++;
		}

		return $changes_values;
	}
	
	public static function named_object_to_line_graph($chart_id, $object, $series_label = 'Total Value:', $color = '#314872') {
		$output = "";
		
		$output .= "Morris.Line({
		element: '" . $chart_id . "',
		data: [";
		
		$is_passedOne = false;
		foreach ( $object as $key => $value ) {
			if ($value == 0 && !$is_passedOne) {
				$is_passedOne = true;
				continue;
			}
			$is_passedOne = true;
		
			$output .= "{ y: '" . $key . "', a: '" . $value . "' },";
		
		}
		
		$output .= "],
		xkey: 'y',
		ykeys: ['a'],
		hideHover: true,
		labels: ['" . $series_label . "'],
		lineColors: ['".$color."']
		});";
		
		$output = str_replace ( ',]', ']', $output );
		
		return $output;
	}

	public static function named_object_to_2line_graph($chart_id, $object, $object2, $series_label1 = 'Value:',  $series_label2 = 'Value:', $color = '#314872', $color2 = '') {
		$output = "";
	
		$output .= "Morris.Bar({
		element: '" . $chart_id . "',
		data: [";
	
		$is_passedOne = false;
		foreach ( $object as $key => $value ) {
			$value2 = isset($object2[$key]) ? $object2[$key] : '';
			
			$key = str_replace("day", "Day ", $key);
			
			$value = intval($value);
			$value2 = intval($value2);
			
	
			$output .= "{ y: '" . $key . "', a: '" . $value . "', b: '" . $value2 . "' },";
	
		}
	
		$output .= "],
		xkey: 'y',
		ykeys: ['a', 'b'],
		hideHover: true,
		labels: ['" . $series_label1 . "', '" . $series_label2 . "'],
		barColors: ['".$color."', '".$color2."']
	});";
	
		$output = str_replace ( ',]', ']', $output );
	
		return $output;
	}
	
	public static function social_network_color ($network) {
		$colors = array();
		
		$colors['youtube'] = '#CD332D';
		$colors['email'] = '#393939';
		$colors['vimeo'] = '#1ab7ea';
		$colors['twitter'] = '#4099FF';
		$colors['facebook'] = '#3B5998';
		$colors['google'] = '#dd4b39';
		$colors['pinterest'] = '#cb2027';
		$colors['linkedin'] = '#007bb6';
		$colors['github'] = '#171515';
		$colors['instagram'] = '#3f729b';
		$colors['instgram'] = '#3f729b';
		$colors['soundcloud'] = '#ff7700';
		$colors['behance'] = '#005cff';
		$colors['delicious'] = '#205cc0';
		$colors['foursquare'] = '#25a0ca';
		$colors['forrst'] = '#5b9a68';
		$colors['dribbble'] = '#ea4c89';
		$colors['envato'] = '#82b540';
		$colors['vk'] = '#45668e';
		$colors['rss'] = '#FF6600';
		$colors['tumblr'] = '#32506d';
		$colors['vine'] = '#00b488';
		$colors['slideshare'] = '#e98325';
		$colors['500px'] = '#02adea';
		$colors['flickr'] = '#FF0084';
		$colors['wp_posts'] = '#c2685f';
		$colors['wp_comments'] = '#b8c25f';
		$colors['wp_users'] = '#5fa7c2';
		$colors['audioboo'] = '#b0006d';
		$colors['steamcommunity'] = '#000000';
		$colors['weheartit'] = '#ff679d';
		$colors['feedly'] = '#02bb24';
		$colors['love'] = '#ED1C24';
		
		if (isset($colors[$network])) {
			return $colors[$network];
		}
		else {
			return "";		
		}
	}
	
	public static function get_year_container($data_container) {
		$result = array();
		
		foreach ($data_container as $date => $value) {
			$date_obj = explode("-", $date);
			$year = $date_obj[0];

			if (!isset($result[$year])) {
				$result[$year] = 0;
			}
			
			$result[$year] += intval($value);
 		}
 		
 		return $result;
	}

	public static function get_month_container($data_container) {
		$result = array();
		
		foreach ($data_container as $date => $value) {
			$date_obj = explode("-", $date);
			$year = $date_obj[1];

			if (!isset($result[$year])) {
				$result[$year] = 0;
			}
			
			$result[$year] += intval($value);
 		}
 		
 		return $result;
	}
	
	public static function get_day_container($data_container) {
		$result = array();
	
		foreach ($data_container as $date => $value) {
			$date_obj = explode("-", $date);
			$year = $date_obj[2];
	
			if (!isset($result[$year])) {
				$result[$year] = 0;
			}
				
			$result[$year] += intval($value);
		}
			
		return $result;
	}
	
	public static function get_day_of_week_container($data_container) {
		$result = array();
	
		foreach ($data_container as $date => $value) {

			$year = date('l', strtotime( $date));
	
			if (!isset($result[$year])) {
				$result[$year] = 0;
			}
	
			$result[$year] += intval($value);
		}
			
		return $result;
	}
	
	public static function simple_table($headers = array(), $data = array()) {
		print '<table border="0" cellpadding="4" cellspacing="0" width="100%">';
		print '<thead>';
		
		print '<tr>';
		foreach ($headers as $title => $key) {
			print '<th>'.$key.'</th>';
		}
		
		print '</tr>';
		
		print '</thead>';
		
		print '<tbody>';
		$cnt = 0;
		foreach ($data as $key => $value) {
			$css_class = ($cnt % 2 == 0) ? "even" : "odd";
			
			print '<tr class="'.$css_class.'">';
			print '<td>'.$key.'</td>';
			print '<td align="right">'.number_format(intval($value)).'</td>';
			print '</tr>';
			
			$cnt++;
		}
		print '</tbody>';
		
		print '</table>';
	}
	
	public static function days_between_two_dates($date1, $date2) {
		$now = strtotime($date2);
		$your_date = strtotime($date1);
		$datediff = $now - $your_date;
		return floor($datediff/(60*60*24));
		
	}
	
	public static function custom_shift_array($arr) {
		$day_count = 1;
		$save_count = 1;
		
		$result = array();
		
		foreach ($arr as $key => $value) {
			if ($key == "day1") { continue; }
			
			$result["day".$save_count] = $value;
			$save_count++;
		}
		
		return $result;
	}
}
?>