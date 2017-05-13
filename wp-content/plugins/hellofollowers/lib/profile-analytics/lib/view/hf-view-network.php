<?php

$last_date = HFSPADatabase::get_last_date ();
$before7days = "";
$before30days = "";
$last_date_object = array();
$before7days_object = array();
$before30days_object = array();
if (! empty ( $last_date )) {
	$last_date_object = HFSPADatabase::get_day_data ( $last_date );
	
	
}

$active_network = isset ( $_REQUEST ['network'] ) ? $_REQUEST ['network'] : '';
$list_of_networks = HFSocialFollowersCounterHelper::available_social_networks ();

$active_network_name = "";

if (!empty($active_network)) {
	$active_network_name = isset($list_of_networks[$active_network]) ? $list_of_networks[$active_network] : '';
}

?>
<div class="hfspa-dashboard hfspa-dashboard">
<?php if ($active_network == '') { ?>

<div class="hfspa-dashboard-panel">
		<div class="hfspa-dashboard-panel-title color1">
			<h4>Choose social network profile you wish to see report for</h4>
		</div>
		<div class="hfspa-dashboard-panel-content">
		
		<?php
	
	foreach ( hf_followers()->active_social_networks() as $social ) {
		$network_name = isset ( $list_of_networks [$social] ) ? $list_of_networks [$social] : '';
		$today_value = isset ( $last_date_object [$social] ) ? $last_date_object [$social] : '';
		$prev_date_value = isset ( $prev_date_object [$social] ) ? $prev_date_object [$social] : '';
		
		$diff_value = HFSPAHelpers::value_diff ( $today_value, $prev_date_value );
		
		print '<a href="' . admin_url ( 'admin.php?page=hf_spa&tab=detailed&network=' . $social ) . '" title="Start detailed network report for ' . $network_name . '">';
		print '<div class="hfspa-profile-card hfspa-profile-card-small-colored hfspa-profile-' . $social . ' hfspa-bg-' . $social . '">';
		print '<div class="hfspa-profile-icon"><i class="hfspa-icon-' . $social . '"></i></div>';
		print '<div class="hfspa-profile-text">';
		print '<h4>' . $network_name . '</h4>';
		print '<h3>' . number_format(intval($today_value)) . '</h3>';
		print '</div>';
		print '</div>';
		print '</a>';
	}
	
	?>
		
		</div>
	</div>

<?php } else { ?>
<div class="hfspa-dashboard-panel">
		<div class="hfspa-dashboard-panel-title color1">
			<h4>Overview report for <?php echo $active_network_name; ?></h4>
			<h5>Last updated: <?php echo $last_date; ?></h5>
		</div>
		<div class="hfspa-dashboard-panel-content">
		
		<?php
		$social = $active_network;
		
		
		$before7days = HFSPAHelpers::previous_date_with_offset($last_date, '7');
		$before30days = HFSPAHelpers::previous_date_with_offset($last_date, '30');
						
		$date_range_object = HFSPADatabase::get_date_range_records($before30days, $last_date);
		
		$date_range_diff_object = HFSPAHelpers::get_period_fans_change_object($before30days, $last_date, $social);
		
		if (!isset($date_range_object[$before7days])) {
			$is_found = false;
			while (!$is_found) {
				$before7days = HFSPAHelpers::next_date($before7days);
				
				if (isset($date_range_object[$before7days])) {
					$is_found = true;
				}
				
				if ($before7days == $last_date) {
					$is_found = true;
				}
			}
		}
		
		if (!isset($date_range_object[$before30days])) {
			$is_found = false;
			while (!$is_found) {
				$before30days = HFSPAHelpers::next_date($before30days);
		
				if (isset($date_range_object[$before30days])) {
					$is_found = true;
				}
		
				if ($before30days == $last_date) {
					$is_found = true;
				}
			}
		}
		
		$before30days_object = HFSPADatabase::get_day_data($before30days);
		$before7days_object = HFSPADatabase::get_day_data($before7days);
		
		$before30days_value = isset($before30days_object[$social]) ? $before30days_object[$social]: '';
		$before7days_value = isset($before7days_object[$social]) ? $before7days_object[$social]: '';
		$today_value = isset ( $last_date_object [$social] ) ? $last_date_object [$social] : '';
		
		
		$diff7_value = HFSPAHelpers::value_diff ( $today_value, $before7days_value );
		$diff30_value = HFSPAHelpers::value_diff ( $today_value, $before30days_value );
		
		print '<div class="hfspa-network-profile hfspa-profile-' . $social . ' hfspa-bg-' . $social . '">';
		print '<div class="hfspa-profile-icon"><i class="hfspa-icon-' . $social . '"></i></div>';
		print '<div class="hfspa-profile-title"><h2>'.$active_network_name.'</h2><a target="_blank" title="Visit social profile" href="'.hf_followers()->create_follow_address($social).'" class="hfspa-profile-link">'.hf_followers()->create_follow_address($social).'</a></div>';

		
		print '<div class="hfspa-profile-total-single hfspa-float-right hfspa-inline">';
		print '<h3>' . number_format(abs($diff30_value)) . '</h3>';
		print '<h4>' . HFSPAHelpers::value_diff_text_only($diff30_value) . ' for the<br/> last 30 days</h4>';
		print '</div>';

		print '<div class="hfspa-profile-total-single hfspa-float-right hfspa-inline">';
		print '<h3>' . number_format(abs($diff7_value)) . '</h3>';
		print '<h4>' . HFSPAHelpers::value_diff_text_only($diff7_value) . ' for the<br/> last 7 days</h4>';
		print '</div>';
		
		
		print '<div class="hfspa-profile-total-single hfspa-float-right hfspa-inline">';
		print '<h3>' . number_format(intval($today_value)) . '</h3>';
		print '<h4>' . 'Total Fans' . '</h4>';
		print '</div>';
		
		print '</div>';
		?>
		
		<div class="hfspa-space20">&nbsp;</div>
		<h4>New followers dynamic for the last 30 days</h4>
		<div id="hfpsa-dynamic-graph" style="height: 300px; min-height: 300px;"></div>
		
		</div>
	</div>
	<div class="hfspa-space20">&nbsp;</div>
	
	<?php 
	
	// pre data generation
	$first_date = HFSPADatabase::get_first_date();
	$all_days_object = HFSPAHelpers::get_period_fans_change_object($first_date, $last_date, $social);
	
	$year_container = HFSPAHelpers::get_year_container($all_days_object);
	$month_container = HFSPAHelpers::get_month_container($all_days_object);
	$day_container = HFSPAHelpers::get_day_container($all_days_object);
	$dayofweek_container= HFSPAHelpers::get_day_of_week_container($all_days_object);
	
	//print_r($all_days_object);
	
	?>
	<div class="hfspa-dashboard-panel">
	<div class="hfspa-dashboard-panel-title color1">
			<h4>Summary of new followers</h4>
		</div>
		<div class="hfspa-dashboard-panel-content hfspa-row">
			<div class="col1_4 hfspa-col">
				<div class="hfspa-inner-margin">
				<h4 style="color: <?php echo HFSPAHelpers::social_network_color($social);?> ">New followers by year</h4>
				<?php 
				HFSPAHelpers::simple_table(array("Year", "New Followers"), $year_container);
				?>
				</div>
			</div>
			
			<div class="col1_4 hfspa-col light-bgcolor">
				<div class="hfspa-inner-margin">
				<h4 style="color: <?php echo HFSPAHelpers::social_network_color($social);?> ">New followers by month</h4>
				<?php 
				HFSPAHelpers::simple_table(array("Month", "New Followers"), $month_container);
				?>
				</div>
			</div>
			
			<div class="col1_4 hfspa-col">
				<div class="hfspa-inner-margin">
				<h4 style="color: <?php echo HFSPAHelpers::social_network_color($social);?> ">New followers by date</h4>				
				<?php 
				HFSPAHelpers::simple_table(array("Day", "New Followers"), $day_container);
				?>
				</div>
			</div>
			
			<div class="col1_4 hfspa-col light-bgcolor">
				<div class="hfspa-inner-margin">
								<h4 style="color: <?php echo HFSPAHelpers::social_network_color($social);?> ">New followers by date of week</h4>
				
				<?php 
				HFSPAHelpers::simple_table(array("Day of Week", "New Followers"), $dayofweek_container);
				?>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
jQuery(document).ready(function($){
<?php
echo HFSPAHelpers::named_object_to_line_graph( 'hfpsa-dynamic-graph', $date_range_diff_object, 'Profile Fans Change', HFSPAHelpers::social_network_color($social));
?>
});
	
</script>
<?php } ?>

</div>