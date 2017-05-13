<div class="wrap">
	<div id="lbg_logo">
			<h2>Manage Sliders - Add New Slider</h2>
 	</div>

    <form method="POST" enctype="multipart/form-data" id="form-add-playlist-record">
		<table class="wp-list-table widefat fixed pages" cellspacing="0">
		  <tr>
		    <td align="left" valign="middle" width="25%">&nbsp;</td>
		    <td align="left" valign="middle" width="77%"><a href="?page=lbg_zoominoutslider_Manage_Sliders" style="padding-left:25%;">Back to Manage Sliders</a></td>
		  </tr>
		  <tr>
		    <td width="25%" align="right" valign="middle" class="row-title">Name*</td>
		    <td width="77%" align="left" valign="top"><input name="name" type="text" id="name" size="80" value="<?php echo strip_tags($_POST['name'])?>" /></td>
		  </tr>
		  <tr>
		    <td colspan="2" align="left" valign="middle">*Required fields</td>
		  </tr>
		  <tr>
		    <td colspan="2" align="center" valign="middle"><input name="Submit" id="Submit" type="submit" class="button-primary" value="Add New"></td>
		  </tr>
		</table>    
  </form>






</div>				