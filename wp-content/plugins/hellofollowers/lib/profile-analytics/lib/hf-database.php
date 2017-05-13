<?php

class HFSPADatabase {
	public static function log($date, $network, $value) {
		global $wpdb;
		
		$table_name = $wpdb->prefix . HF_SPA_TRACKER_TABLE;
		
		$query = 'SELECT hfspa_id FROM '.$table_name.' WHERE hfspa_date="'.$date.'" AND hfspa_network="'.$network.'"';
		$exist_record = $wpdb->get_row($query);
		
		if ($exist_record != null) {
			$update_id = $exist_record->hfspa_id;
				
			$rows_affected = $wpdb->update ( $table_name, array (
					'hfspa_value' => $value ),
					array( 'hfspa_id' => $update_id) );
				
		}
		else {
			$rows_affected = $wpdb->insert ( $table_name, array (
					'hfspa_date' => $date,
					'hfspa_network' => $network,
					'hfspa_profile' => '',
					'hfspa_value' => $value ) );
		}
	}
	
	public static function get_last_date() {
		global $wpdb;
		
		$table_name = $wpdb->prefix . HF_SPA_TRACKER_TABLE;
		$query = 'SELECT MAX(hfspa_date) as date FROM '.$table_name;
		
		$exist_record = $wpdb->get_row($query);
		
		$last_date = "";
		
		if ($exist_record != null) {
			$last_date = $exist_record->date;
		}
		
		return $last_date;
	}
	
	public static function reset_data() {
		global $wpdb;
		
		$table_name = $wpdb->prefix . HF_SPA_TRACKER_TABLE;
		$query = 'DELETE FROM '.$table_name;
		
		$exist_record = $wpdb->get_row($query);
		
	}
	
	public static function get_first_date() {
		global $wpdb;
	
		$table_name = $wpdb->prefix . HF_SPA_TRACKER_TABLE;
		$query = 'SELECT MIN(hfspa_date) as date FROM '.$table_name;
	
		$exist_record = $wpdb->get_row($query);
	
		$last_date = "";
	
		if ($exist_record != null) {
			$last_date = $exist_record->date;
		}
	
		return $last_date;
	}
	
	public static function get_day_data($date) {
		global $wpdb;
		
		$table_name = $wpdb->prefix . HF_SPA_TRACKER_TABLE;
		$query = 'SELECT hfspa_network, hfspa_value FROM '.$table_name.' WHERE hfspa_date = "'.$date.'"';
		
		$rows = $wpdb->get_results($query);
		
		$result = array();
		
		if ($rows != null) {
			foreach ($rows as $record) {
				$network = $record->hfspa_network;
				$value = $record->hfspa_value;
				
				$result[$network] = $value;
			}
		}
		
		return $result;
	}
	
	public static function get_date_range_records($from_date, $to_date, $network = '', $network_list = array()) {
		global $wpdb;
		
		//$from_date = date('Y-m-d', strtotime($date . " - 1 month"));
		$table_name = $wpdb->prefix . HF_SPA_TRACKER_TABLE;
		
		if (!empty($network)) {
			if ($network != "list") {
				$network = ' AND hfspa_network="'.$network.'"';
			}
			else {
				$network_query = ' AND (';
				
				$is_first = true;
				foreach ($network_list as $one) {
					if (!$is_first) {
						$network_query .= " OR ";
					}
					$network_query .= ' hfspa_network="'.$one.'"';
					$is_first = false;
				}
				
				$network_query .= ') ';
				$network = $network_query;
			}
		}			
		
		$query = sprintf('SELECT hfspa_date as date, SUM(hfspa_value) as value FROM %3$s WHERE hfspa_date BETWEEN "%1$s" AND "%2$s" %4$sGROUP BY hfspa_date ORDER BY hfspa_date ASC', $from_date, $to_date, $table_name, $network);
		$rows = $wpdb->get_results($query);
		$result = array();
		
		if ($rows != null) {
			foreach ($rows as $row) {
				$date = $row->date;
				$value = $row->value;
				
				$result[$date] = $value;
			}
		}
		
		return $result;
	}
}

?>