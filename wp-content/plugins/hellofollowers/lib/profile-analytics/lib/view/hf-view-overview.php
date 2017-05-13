<?php

$last_date = HFSPADatabase::get_last_date ();
$previous_date = "";

if (! empty ( $last_date )) {
	$previous_date = HFSPAHelpers::previous_date ( $last_date );
}

$last_date_object = array ();
$prev_date_object = array ();

if (! empty ( $last_date )) {
	$last_date_object = HFSPADatabase::get_day_data ( $last_date );
}
if (! empty ( $previous_date )) {
	$prev_date_object = HFSPADatabase::get_day_data ( $previous_date );
}

$best_value = 0;
$best_network = "";

$best_diff_value = 0;
$best_diff_network = "";

if (isset($last_date_object)) {
	foreach ($last_date_object as $network => $value) {
		if (intval($value) > intval($best_value)) {
			$best_value = $value;
			$best_network = $network;
		}
	}
}

if (isset($last_date_object) && isset($prev_date_object)) {
	foreach ($last_date_object as $network => $value) {
		$prev_date_value = isset ( $prev_date_object [$network] ) ? $prev_date_object [$network] : '';
			
		$diff_value = HFSPAHelpers::value_diff ( $value, $prev_date_value );
		if (intval($diff_value) > intval($best_diff_value)) {
			$best_diff_value = $diff_value;
			$best_diff_network = $network;
		}
	}
}

$from_date = "";
$to_date = "";
$month_changes_object = array();

if (!empty($last_date)) {
	$from_date = HFSPAHelpers::previous_month_date($last_date);
	$to_date = $last_date;
	
	$month_changes_object = HFSPAHelpers::get_period_fans_change_object($from_date, $to_date);
	
}

?>

<div class="hfspa-dashboard hfspa-dashboard">

	<div class="hfspa-dashboard-panel">
		<div class="hfspa-dashboard-panel-title color1">
			<h4>Overview of Social Followers</h4>
			<h5>Last updated: <?php echo $last_date; ?></h5>
		</div>
		<div class="hfspa-dashboard-panel-content">
			<!--  report by single network  -->
		
		<?php
		
		$list_of_networks = HFSocialFollowersCounterHelper::available_social_networks ();
		
		$total_fans = 0;
		$total_diff = 0;
		
		foreach ( hf_followers()->active_social_networks() as $social ) {
			$network_name = isset ( $list_of_networks [$social] ) ? $list_of_networks [$social] : '';
			$today_value = isset ( $last_date_object [$social] ) ? $last_date_object [$social] : '';
			$prev_date_value = isset ( $prev_date_object [$social] ) ? $prev_date_object [$social] : '';
			
			$diff_value = HFSPAHelpers::value_diff ( $today_value, $prev_date_value );
			
			$total_fans += intval ( $today_value );
			$total_diff += intval ( $diff_value );
		}
		
		print '<div class="hfspa-total-fans">';
		
		print '<div class="hfspa-profile-card hfspa-profile-total" style="width:100%;">';
		print '<div class="hfspa-profile-icon hfspa-bg-total"><i class="hfspa-icon-heart"></i></div>';
		print '<div class="hfspa-profile-text">';
		print '<h4>' . __ ( 'Total Followers', 'hellofollowers' ) . '</h4>';
		print '<h3>' . number_format(intval($total_fans)) . '</h3>';
		print '<h5>' . HFSPAHelpers::value_diff_text ( $total_diff ) . '</h5>';
		print '</div>';
		
		if ($best_diff_network != "") {
			print '<div class="hfspa-profile-text hfspa-float-right" style="margin-right: 20px; text-align: center;">';
			print '<h4>' . __ ( 'Top New Followers', 'hellofollowers' ) . '</h4>';
			print '<h3>' . number_format(intval($best_diff_value)) . '</h3>';
			print '<h5>' . $best_diff_network . '</h5>';
			print '</div>';
		}
		
		
		if ($best_network != "") {
			print '<div class="hfspa-profile-text hfspa-float-right" style="margin-right: 20px; text-align: center;">';
			print '<h4>' . __ ( 'Best Profile', 'hellofollowers' ) . '</h4>';
			print '<h3>' . number_format(intval($best_value)) . '</h3>';
			print '<h5>' . $best_network . '</h5>';
			print '</div>';
		}
				
		print '</div>';
		
		print '</div>';
		
		foreach ( hf_followers()->active_social_networks() as $social ) {
			$network_name = isset ( $list_of_networks [$social] ) ? $list_of_networks [$social] : '';
			$today_value = isset ( $last_date_object [$social] ) ? $last_date_object [$social] : '';
			$prev_date_value = isset ( $prev_date_object [$social] ) ? $prev_date_object [$social] : '';
			
			$diff_value = HFSPAHelpers::value_diff ( $today_value, $prev_date_value );
			
			print '<a href="' . admin_url ( 'admin.php?page=hf_spa&tab=detailed&network=' . $social ) . '" title="Start detailed network report for ' . $network_name . '">';
			print '<div class="hfspa-profile-card hfspa-profile-' . $social . '">';
			print '<div class="hfspa-profile-icon hfspa-bg-' . $social . '"><i class="hfspa-icon-' . $social . '"></i></div>';
			print '<div class="hfspa-profile-text">';
			print '<h4>' . $network_name . '</h4>';
			print '<h3>' . number_format(intval($today_value)) . '</h3>';
			print '<h5>' . HFSPAHelpers::value_diff_text ( $diff_value ) . '</h5>';
			print '</div>';
			print '</div>';
			print '</a>';
		}
		
		?>
		
		</div>
	</div>
	<div class="hfspa-space20">&nbsp;</div>
	<div class="hfspa-dashboard-panel">
		<div class="hfspa-dashboard-panel-title color1">
			<h4>Social Followers Dynamic for the previous month</h4>
			<h5>Last updated: <?php echo $last_date; ?></h5>
		</div>
		<div class="hfspa-dashboard-panel-content" style="min-height: 300px; height: 300px;" id="hfspa-changes-graph"></div>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($){
      <?php
						if (!empty($from_date) && !empty($to_date)) {
							echo HFSPAHelpers::named_object_to_line_graph( 'hfspa-changes-graph', $month_changes_object, 'Followers Change' );
						}
						?>
});
	
</script>