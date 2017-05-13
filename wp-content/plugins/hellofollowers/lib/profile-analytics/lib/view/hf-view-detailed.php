<?php
$last_date = HFSPADatabase::get_last_date ();
if (empty ( $last_date )) {
	$last_date = date ( "Y-m-d" );
}
$first_date = HFSPAHelpers::previous_date_with_offset ( $last_date, '30' );

$user_from_date = isset ( $_REQUEST ['from_date'] ) ? $_REQUEST ['from_date'] : '';
$user_to_date = isset ( $_REQUEST ['to_date'] ) ? $_REQUEST ['to_date'] : '';

$user_filter_networks = isset($_REQUEST['filter_networks']) ? $_REQUEST['filter_networks'] : array();

if (! empty ( $user_from_date )) {
	$first_date = $user_from_date;
}
if (! empty ( $user_to_date )) {
	$last_date = $user_to_date;
}


$user_filter = false;
if (count($user_filter_networks) == 0) {
	$user_filter_networks = hf_followers()->active_social_networks();
}
else {
	$user_filter = true;
}

$glboal_diff_object = HFSPAHelpers::get_period_fans_change_object ( $first_date, $last_date );
$global_data_object = HFSPADatabase::get_date_range_records ( $first_date, $last_date );
$social_object = array ();

foreach ( $user_filter_networks as $social ) {
	$changes_object = HFSPAHelpers::get_period_fans_change_object ( $first_date, $last_date, $social );
	$data_object = HFSPADatabase::get_date_range_records ( $first_date, $last_date, $social );
	
	$social_object [$social] = array ("diff" => $changes_object, "data" => $data_object );
}

$best_day_value = array();
$work_date = $last_date;
$cnt = 0;
while ( $work_date >= $first_date ) {
	
	foreach ( $user_filter_networks as $social ) {
		if (isset ( $social_object [$social] )) {
			$diff_value = isset ( $social_object [$social] ["diff"] [$work_date] ) ? $social_object [$social] ["diff"] [$work_date] : '';
			
			if (!isset($best_day_value[$work_date])) {
				$best_day_value[$work_date] = 0;
			}
			
			$best_value = $best_day_value[$work_date];
			
			if (intval($best_value) < intval($diff_value)) {
				$best_day_value[$work_date] = $diff_value;
			}
		}
	}
	
	$work_date = HFSPAHelpers::previous_date($work_date);
}

$list_of_networks = HFSocialFollowersCounterHelper::available_social_networks ();

?>

<div class="hfspa-dashboard hfspa-dashboard">

	<div class="hfspa-dashboard-panel">
		<div class="hfspa-dashboard-panel-title color1">
			<h4>Detailed Period Report</h4>
		</div>
		<div class="hfspa-dashboard-panel-content">

			<form method="POST">
			<table boder="0" cellpadding="6" cellspacing="0" width="100%" style="background-color: #f4f5f6;">
				<col width="15%"/>
				<col width="85%"/>
				<tr>
					<td>Period:</td>
					<td><input name="from_date"
					value="<?php echo $first_date;?>" id="from_date"
					class="input-element center" style="width: 120px; text-align: center;" /> <input name="to_date"
					value="<?php echo $last_date;?>" id="to_date"
					class="input-element center" style="width: 120px; text-align: center;" /></td>
				</tr>
				<tr>
					<td>Display only selected networks:</td>
					<td><?php 
					foreach ( hf_followers()->active_social_networks() as $social ) {
						$network_name = (isset ( $list_of_networks [$social] ) ? $list_of_networks [$social] : $social);
						
						$is_checked = false;
						if ($user_filter) {
							if (in_array($social, $user_filter_networks)) {
								$is_checked = true;
							}
						}
						
						print '<input type="checkbox" name="filter_networks[]" value="'.$social.'" '.($is_checked ? 'checked="checked"' : '').'/>'.$network_name.'&nbsp;';
					}
					?></td>					
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" value="Apply"
					class="button" /></td>
				</tr>
			</table>
				
					
			</form>

			<div class="hfspa-space20">&nbsp;</div>
			<div id="hfspa-changes-graph"
				style="min-height: 300px; height: 300px;"></div>
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
						<th>Fans</th>
						<th>Change</th>
								<?php
								foreach ( $user_filter_networks as $social ) {
									print '<th>Fans</th>';
									print '<th>Change</th>';
								}
								?>
							</tr>
				</thead>
				<tbody>
						<?php
						
						$work_date = $last_date;
						$cnt = 0;
						while ( $work_date >= $first_date ) {
							
							if (isset ( $global_data_object [$work_date] )) {
								$class = ($cnt % 2 == 0) ? "even" : "odd";
								print '<tr>';
								
								print '<td>' . $work_date . '</td>';
								
								$total_value = isset ( $global_data_object [$work_date] ) ? $global_data_object [$work_date] : '';
								$total_diff = isset ( $glboal_diff_object [$work_date] ) ? $glboal_diff_object [$work_date] : '';
								$total_value = intval ( $total_value );
								print '<td align="right">' . number_format ( $total_value ) . '</td>';
								print '<td align="right" '.(($total_diff < 0) ? ' class="worst-value"' : "" ).'>' . $total_diff . '</td>';
								
								// by network
								foreach ( $user_filter_networks as $social ) {
									if (isset ( $social_object [$social] )) {
										$data_value = isset ( $social_object [$social] ["data"] [$work_date] ) ? $social_object [$social] ["data"] [$work_date] : '';
										$diff_value = isset ( $social_object [$social] ["diff"] [$work_date] ) ? $social_object [$social] ["diff"] [$work_date] : '';
										$data_value = intval ( $data_value );
										
										$best_day_value_diff = isset($best_day_value[$work_date]) ? $best_day_value[$work_date] : 0;
										$best_day_value_diff = intval($best_day_value_diff);

										$diff_value = intval($diff_value);
										
										print '<td align="right">' . number_format ( $data_value ) . '</td>';
										print '<td align="right" '.(($diff_value < 0) ? ' class="worst-value"' : "" ).' '.(($diff_value == $best_day_value_diff && $best_day_value_diff > 0) ? ' class="top-day-value"' : "" ).'>' . number_format(intval($diff_value)) . '</td>';
									} else {
										print '<td align="right"></td>';
										print '<td align="right"></td>';
									}
								}
								print '</tr>';
							}
							$work_date = HFSPAHelpers::previous_date ( $work_date );
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

	 <?php
		if (! empty ( $first_date ) && ! empty ( $last_date )) {
			echo HFSPAHelpers::named_object_to_line_graph ( 'hfspa-changes-graph', $glboal_diff_object, 'Social Fans Change' );
		}
		?>

				jQuery('#hfspa-detailed-result').DataTable({ pageLength: 50, paging: false, info: false, searching: false, "order": [[ 0, "desc" ]], 
					dom: 'Bfrtip',
					'scrollX': true,
			        buttons: [
			                  'csv', 'excel'
			              ]});
});
	
</script>