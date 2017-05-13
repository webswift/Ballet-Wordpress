<?php

$from_date = isset ( $_REQUEST ['from_date'] ) ? $_REQUEST ['from_date'] : '';
$to_date = isset ( $_REQUEST ['to_date'] ) ? $_REQUEST ['to_date'] : '';
$from_date2 = isset ( $_REQUEST ['from_date2'] ) ? $_REQUEST ['from_date2'] : '';
$to_date2 = isset ( $_REQUEST ['to_date2'] ) ? $_REQUEST ['to_date2'] : '';

$user_filter_networks = isset ( $_REQUEST ['filter_networks'] ) ? $_REQUEST ['filter_networks'] : array ();

$original_from_date = $from_date;
$original_from_date2 = $from_date2;

$user_filter = false;
if (count ( $user_filter_networks ) == 0) {
	$user_filter_networks = hf_followers()->active_social_networks();
} else {
	$user_filter = true;
}

$is_valid = false;
$is_valid_period = true;
if (! empty ( $from_date ) && ! empty ( $from_date2 ) && ! empty ( $to_date ) && ! empty ( $to_date2 )) {
	$is_valid = true;
	
	$diff = HFSPAHelpers::days_between_two_dates($to_date, $from_date);
	$diff2 = HFSPAHelpers::days_between_two_dates($to_date2, $from_date2);
	
	if ($diff != $diff2) {
		$is_valid = false;
		$is_valid_period = false;
	}
	
	$from_date = HFSPAHelpers::previous_date($from_date);
	$from_date2 = HFSPAHelpers::previous_date($from_date2);
}

if ($is_valid) {
	$list_filter = $user_filter ? "list" : "";
	
	$globoal_diff_object = HFSPAHelpers::get_period_fans_change_object ( $from_date, $to_date, $list_filter, true, $user_filter_networks );
	$globoal_diff_object2 = HFSPAHelpers::get_period_fans_change_object ( $from_date2, $to_date2, $list_filter, true, $user_filter_networks );
	
	$globoal_diff_object = HFSPAHelpers::custom_shift_array($globoal_diff_object);
	$globoal_diff_object2 = HFSPAHelpers::custom_shift_array($globoal_diff_object2);
	
	$social_object = array ();
	
	foreach ( $user_filter_networks as $social ) {
		$changes_object = HFSPAHelpers::get_period_fans_change_object ( $from_date, $to_date, $social, true );
		$changes_object2 = HFSPAHelpers::get_period_fans_change_object ( $from_date2, $to_date2, $social, true );
		$changes_object = HFSPAHelpers::custom_shift_array($changes_object);
		$changes_object2 = HFSPAHelpers::custom_shift_array($changes_object2);
		
		$social_object [$social] = array ("diff" => $changes_object, "diff2" => $changes_object2 );
	}
	
	
}
$list_of_networks = HFSocialFollowersCounterHelper::available_social_networks ();

?>

<div class="hfspa-dashboard hfspa-dashboard">

	<div class="hfspa-dashboard-panel">
		<div class="hfspa-dashboard-panel-title color1">
			<h4>Compare Period Report</h4>
		</div>
		<div class="hfspa-dashboard-panel-content">

			<form method="POST">
				<table boder="0" cellpadding="6" cellspacing="0" width="100%"
					style="background-color: #f4f5f6;">
					<col width="15%" />
					<col width="85%" />
					<?php 
					
					if (!$is_valid && $is_valid_period) {
						print "<tr>";
						print '<td colspan="2"><b>Please select period 1 and period 2 to display compared information</b></td>';
						print "</tr>";
					}

					if (!$is_valid && !$is_valid_period) {
						print "<tr>";
						print '<td colspan="2"><b>Period 1 and period 2 should contain equal number of days</b></td>';
						print "</tr>";
					}
						
					?>
					<tr>
						<td>Period 1:</td>
						<td><input name="from_date" value="<?php echo $original_from_date;?>"
							id="from_date" class="input-element center"
							style="width: 120px; text-align: center;" /> <input
							name="to_date" value="<?php echo $to_date;?>" id="to_date"
							class="input-element center"
							style="width: 120px; text-align: center;" /></td>
					</tr>
					<tr>
						<td>Period 2:</td>
						<td><input name="from_date2" value="<?php echo $original_from_date2;?>"
							id="from_date2" class="input-element center"
							style="width: 120px; text-align: center;" /> <input
							name="to_date2" value="<?php echo $to_date2;?>" id="to_date2"
							class="input-element center"
							style="width: 120px; text-align: center;" /></td>
					</tr>
					<tr>
						<td>Display only selected networks:</td>
						<td><?php
						foreach ( hf_followers()->active_social_networks() as $social ) {
							$network_name = (isset ( $list_of_networks [$social] ) ? $list_of_networks [$social] : $social);
							
							$is_checked = false;
							if ($user_filter) {
								if (in_array ( $social, $user_filter_networks )) {
									$is_checked = true;
								}
							}
							
							print '<input type="checkbox" name="filter_networks[]" value="' . $social . '" ' . ($is_checked ? 'checked="checked"' : '') . '/>' . $network_name . '&nbsp;';
						}
						?></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" value="Apply" class="button" /></td>
					</tr>
				</table>


			</form>

			<div class="hfspa-space20">&nbsp;</div>
			<div id="hfspa-changes-graph"
				style="min-height: 300px; height: 300px;"></div>
			<div class="hfspa-space20">&nbsp;</div>
			<div style="text-align: right;">
				<span style="width: 20px; height: 20px; background-color: #2980b9; display:inline-block;">&nbsp;</span> Period 1
				<span style="width: 20px; height: 20px; background-color: #e67e22; display:inline-block;">&nbsp;</span> Period 2
				</div>
			<div class="hfspa-space20">&nbsp;</div>
			
			<table id="hfspa-detailed-result"
				class="display order-column cell-border row-border stripe hover"
				cellspacing="0" width="100%">
				<thead>
					<tr>
						<th rowspan="2">Date</th>
						<th colspan="2">Total</th>
								
								<?php
								foreach ( $user_filter_networks as $social ) {
									print '<th colspan="2">' . (isset ( $list_of_networks [$social] ) ? $list_of_networks [$social] : $social) . '</th>';
								}
								?>
							</tr>
					<tr>
						<th>Period 1 Change</th>
						<th>Period 2 Change</th>
								<?php
								foreach ( $user_filter_networks as $social ) {
									print '<th>Period 1 Change</th>';
									print '<th>Period 2 Change</th>';
								}
								?>
							</tr>
				</thead>
				<tbody>
						<?php
						if ($is_valid) {
							$work_date = $to_date;
							$cnt = 0;
							$day_count = 1;
							while ( $work_date >= $original_from_date ) {
									$class = ($cnt % 2 == 0) ? "even" : "odd";
									print '<tr>';
									
									if ($day_count < 10) {
										print '<td>' . "Day &nbsp;".$day_count . '</td>';
									}
									else {
										print '<td>' . "Day ".$day_count . '</td>';
									}
									
									$total_value = isset ( $globoal_diff_object ["day".$day_count] ) ? $globoal_diff_object ["day".$day_count] : '';
									$total_diff = isset ( $globoal_diff_object2 ["day".$day_count] ) ? $globoal_diff_object2 ["day".$day_count] : '';
									$total_value = intval ( $total_value );
									print '<td align="right">' . number_format ( intval($total_value) ) . '</td>';
									print '<td align="right">' . number_format(intval($total_diff)) . '</td>';
									
									// by network
									foreach ( $user_filter_networks as $social ) {
										if (isset ( $social_object [$social] )) {
											$data_value = isset ( $social_object [$social] ["diff"] ["day".$day_count] ) ? $social_object [$social] ["diff"] ["day".$day_count] : '';
											$diff_value = isset ( $social_object [$social] ["diff2"] ["day".$day_count] ) ? $social_object [$social] ["diff2"] ["day".$day_count] : '';
											$data_value = intval ( $data_value );
																						
											$diff_value = intval ( $diff_value );
											
											print '<td align="right">' . number_format ( $data_value ) . '</td>';
											print '<td align="right">' . number_format ( intval ( $diff_value ) ) . '</td>';
										} else {
											print '<td align="right"></td>';
											print '<td align="right"></td>';
										}
									}
									print '</tr>';
								
								$work_date = HFSPAHelpers::previous_date ( $work_date );
								$day_count++;
							}
						}
						?>
						</tbody>
			</table>

		</div>
	</div>
</div>


<script type="text/javascript">
jQuery(document).ready(function($){
      //$( "#esmp_date_from" ).datepicker( );
	var pickerFrom = new Pikaday({ field: $('#from_date')[0], format: "YYYY-MM-DD" });
	var pickerTo = new Pikaday({ field: $('#to_date')[0], format: "YYYY-MM-DD" });
	var pickerFrom2 = new Pikaday({ field: $('#from_date2')[0], format: "YYYY-MM-DD" });
	var pickerTo2 = new Pikaday({ field: $('#to_date2')[0], format: "YYYY-MM-DD" });
	
	 <?php
		if ($is_valid) {
			echo HFSPAHelpers::named_object_to_2line_graph ( 'hfspa-changes-graph', $globoal_diff_object, $globoal_diff_object2, 'Period 1', 'Period 2', '#2980b9', '#e67e22' );
		}
		?>

				jQuery('#hfspa-detailed-result').DataTable({ pageLength: 50, paging: false, info: false, searching: false, "order": [[ 0, "asc" ]], 
					dom: 'Bfrtip',
					'scrollX': true,
			        buttons: [
			                  'csv', 'excel'
			              ]});
});
	
</script>