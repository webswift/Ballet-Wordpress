<script>
jQuery(document).ready(function() {
		window.send_to_editor = function(html) {
		 imgurl = jQuery('img',html).attr('src');
		 //jQuery('#'+formfield).val(imgurl);
		 if (formfield=='img') {
		 	document.forms["form-playlist-all_in_one_contentSlider-"+the_i].img.value=imgurl;
		 } else {
			 document.forms["form-playlist-all_in_one_contentSlider-"+the_i].thumbnail.value=imgurl;
		 }
		 //alert (the_i); 	
		 jQuery('#'+formfield+'_'+the_i).attr('src',imgurl);
		 tb_remove();
		 
		}
});		
</script>


<div class="wrap">
	<div id="lbg_logo">
			<h2>Playlist for banner: <span style="color:#FF0000; font-weight:bold;"><?php echo $_SESSION['xname']?> - ID #<?php echo $_SESSION['xid']?></span></h2>
 	</div>
  <div id="all_in_one_contentSlider_updating_witness"><img src="<?php echo plugins_url('images/ajax-loader.gif', dirname(__FILE__))?>" /> Updating...</div>
  
  
<div style="text-align:center; padding:0px 0px 20px 0px;"><img src="<?php echo plugins_url('images/icons/add_icon.gif', dirname(__FILE__))?>" alt="add" align="absmiddle" /> <a href="?page=all_in_one_contentSlider_Playlist&xmlf=add_playlist_record">Add new</a></div>
<div style="text-align:left; padding:10px 0px 10px 14px;">#Initial Order</div>


<ul id="all_in_one_contentSlider_sortable">
	<?php foreach ( $result as $row ) 
	{
		$row=all_in_one_contentSlider_unstrip_array($row); ?>
	<li class="ui-state-default cursor_move" id="<?php echo $row['id']?>">#<?php echo $row['ord']?> ---  <img src="<?php echo $row['img']?>" height="30" align="absmiddle" id="top_image_<?php echo $row['id']?>" /><div class="toogle-btn-closed" id="toogle-btn<?php echo $row['ord']?>" onclick="mytoggle('toggleable<?php echo $row['ord']?>','toogle-btn<?php echo $row['ord']?>');"></div><div class="options"><a href="javascript: void(0);" onclick="all_in_one_contentSlider_delete_entire_record(<?php echo $row['id']?>,<?php echo $row['ord']?>);">Delete</a></div>
	<div class="toggleable" id="toggleable<?php echo $row['ord']?>">
    <form method="POST" enctype="multipart/form-data" id="form-playlist-all_in_one_contentSlider-<?php echo $row['ord']?>">
	    <input name="id" type="hidden" value="<?php echo $row['id']?>" />
        <input name="ord" type="hidden" value="<?php echo $row['ord']?>" />
		<table width="100%" cellspacing="0" class="wp-list-table widefat fixed pages" style="background-color:#FFFFFF;">
		  <tr>
		    <td align="left" valign="middle" width="25%"></td>
		    <td align="left" valign="middle" width="77%"></td>
		  </tr>
		  <tr>
		    <td colspan="2" align="center" valign="middle">&nbsp;</td>
		  </tr>
          <tr>
            <td align="right" valign="top" class="row-title">Image</td>
            <td align="left" valign="middle"><input name="img" type="text" id="img" size="100" value="<?php echo stripslashes($row['img']);?>" />
              <input name="upload_img_button_<?php echo $row['ord']?>" type="button" id="upload_img_button_contentSlider_<?php echo $row['ord']?>" value="Change Image" />
              <br />
              Enter an URL or upload an image<br />
              <br />
              Recommended size: width &amp; height of the banner</td>
            </tr>
          <tr>
        <td align="right" valign="top" class="row-title">&nbsp;</td>
        <td align="left" valign="middle"><img src="<?php echo $row['img']?>" width="300" id="img_<?php echo $row['ord']?>" /></td>
      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Link For The Image</td>
		    <td align="left" valign="top"><input name="data-link" type="text" size="60" id="data-link" value="<?php echo $row['data-link'];?>"/></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Link Target</td>
		    <td align="left" valign="top"><select name="data-target" id="data-target">
              <option value="" <?php echo (($row['data-target']=='')?'selected="selected"':'')?>>select...</option>
		      <option value="_blank" <?php echo (($row['data-target']=='_blank')?'selected="selected"':'')?>>_blank</option>
		      <option value="_self" <?php echo (($row['data-target']=='_self')?'selected="selected"':'')?>>_self</option>
		      
	        </select></td>
	      </tr>
          <tr>
            <td align="right" valign="top" class="row-title">Thumbnail</td>
            <td align="left" valign="middle"><input name="thumbnail" type="text" id="thumbnail" size="100" value="<?php echo stripslashes($row['thumbnail'])?>" />
              <input name="upload_thumbnail_button_<?php echo $row['ord']?>" type="button" id="upload_thumbnail_button_contentSlider_<?php echo $row['ord']?>" value="Change Thumbnail" />
              <br />
              Enter an URL or upload an image<br />
              <br />
              Recommended size for each skin: 80px x 80px</td>
            </tr>
          <tr>
        <td align="right" valign="top" class="row-title">&nbsp;</td>
        <td align="left" valign="middle"><img src="<?php echo $row['thumbnail']?>" name="thumbnail_<?php echo $row['ord']?>" id="thumbnail_<?php echo $row['ord']?>" /></td>
      </tr>
          <tr>
            <td align="right" valign="top" class="row-title">Image Title/Alternative Text</td>
            <td align="left" valign="top"><input name="alt_text" type="text" size="60" id="alt_text" value="<?php echo stripslashes($row['alt_text']);?>"/></td>
          </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Video Beneath Image</td>
		    <td align="left" valign="middle"><select name="data-video" id="data-video">
		      <option value="false" <?php echo (($row['data-video']=='false')?'selected="selected"':'')?>>false</option>
		      <option value="true" <?php echo (($row['data-video']=='true')?'selected="selected"':'')?>>true</option>
		      </select></td>
		    </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Content</td>
		    <td align="left" valign="top"><textarea name="content" id="content" cols="45" rows="5"><?php echo stripslashes($row['content']);?></textarea></td>
		  </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Manage Texts Over the Photo</td>
		    <td align="left" valign="middle"><input name="texts<?php echo $row['ord']?>" id="texts<?php echo $row['ord']?>" type="button" class="button-primary" value="Open Texts Editor" onclick="all_in_one_contentSlider_open_dialog(<?php echo $row['ord']?>)"></td>
   
		  </tr>          
		  <tr>
		    <td colspan="2" align="left" valign="middle">
            
<div id="dialog<?php echo $row['ord']?>" title="Manage Texts Over the Photo" style="display:none; padding:0; margin:0;">
	<div id="photo_div<?php echo $row['id']?>" style="padding:50px 0 0px 50px;">
    <?php
		if ($row['img']!='') { ?>
    		<img src="<?php echo $row['img']?>" />
        <? } else { ?>
			<div style="width:<?php echo $row_settings->width?>px; height:<?php echo $row_settings->height?>px; border:1px solid #CCC;">&nbsp;</div>
		<? }
    	$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."all_in_one_contentSlider_texts) WHERE photoid = %d ORDER BY id",$row['id'] );
		$result_text = $wpdb->get_results($safe_sql,ARRAY_A);
		
		foreach ( $result_text as $row_text ) {
	?>	

		<div id="draggable<?php echo $row_text['id']?>" class="my_draggable" style="left:<?php echo $row_text['data-initial-left']+50?>px;top:<?php echo $row_text['data-initial-top']+50-32?>px;"><h2>&nbsp;</h2><textarea name="content<?php echo $row_text['id']?>" id="content<?php echo $row_text['id']?>" cols="30" rows="1"><?php echo stripslashes($row_text['content'])?></textarea></div>
<script>
		jQuery("#draggable<?php echo $row_text['id']?>").draggable( { 
			handle: 'h2',
			start: function(event, ui) {
				jQuery("#text_line_settings<?php echo $row_text['id']?>").css('background','#cccccc');
			},
			stop: function(event, ui) {
				jQuery("#text_line_settings<?php echo $row_text['id']?>").css('background','#ffffff');
			},
			drag: function(event, ui) { 
				jQuery("#data-initial-left<?php echo $row_text['id']?>").val(all_in_one_contentSlider_process_val(jQuery(this).css('left'),'left'));
				jQuery("#data-initial-top<?php echo $row_text['id']?>").val(all_in_one_contentSlider_process_val(jQuery(this).css('top'),'top'));
			}
		});
</script>    
    <div class="text_line_settings" id="text_line_settings<?php echo $row_text['id']?>">
    <table width="100%" border="0">
  <tr>
    <td>Initial Left:</td>
    <td> <input name="data-initial-left<?php echo $row_text['id']?>" type="text" id="data-initial-left<?php echo $row_text['id']?>" size="10" value="<?php echo $row_text['data-initial-left']?>" /> px</td>
    <td>Initial Top:</td>
    <td><input name="data-initial-top<?php echo $row_text['id']?>" type="text" id="data-initial-top<?php echo $row_text['id']?>" size="10" value="<?php echo $row_text['data-initial-top']?>" /> px</td>
    <td>Final Left:</td>
    <td><input name="data-final-left<?php echo $row_text['id']?>" type="text" id="data-final-left<?php echo $row_text['id']?>" size="10" value="<?php echo $row_text['data-final-left']?>" /> px</td>
    <td>Final Top:</td>
    <td><input name="data-final-top<?php echo $row_text['id']?>" type="text" id="data-final-top<?php echo $row_text['id']?>" size="10" value="<?php echo $row_text['data-final-top']?>" /> px</td>
  </tr>
  <tr>
    <td>Duration:</td>
    <td><input name="data-duration<?php echo $row_text['id']?>" type="text" id="data-duration<?php echo $row_text['id']?>" size="10" value="<?php echo $row_text['data-duration']?>" /> s</td>
    <td>Initial Opacity:</td>
    <td><input name="data-fade-start<?php echo $row_text['id']?>" type="text" id="data-fade-start<?php echo $row_text['id']?>" size="10" value="<?php echo $row_text['data-fade-start']?>" /> (Value between 0-100)</td>
    <td>Delay:</td>
    <td><input name="data-delay<?php echo $row_text['id']?>" type="text" id="data-delay<?php echo $row_text['id']?>" size="10" value="<?php echo $row_text['data-delay']?>" /> s</td>
    <td>CSS Styles</td>
    <td><textarea name="css<?php echo $row_text['id']?>" id="css<?php echo $row_text['id']?>" cols="30" rows="3"><?php echo stripslashes($row_text['css'])?></textarea></td>
  </tr>
	<tr>
	<td colspan="8"><div class="delete_text" onclick="all_in_one_contentSlider_delete_text_line(<?php echo $row_text['id']?>)">&nbsp;</div></td>
	</tr>  
</table>
    </div>
    
    <?php } ?>    
    </div>
    <p><input name="all_in_one_contentSlider_add_text_line<?php echo $row['ord']?>" id="all_in_one_contentSlider_add_text_line<?php echo $row['ord']?>" type="button" class="button-primary" value="Add New Text Line" onclick="all_in_one_contentSlider_add_text_line(<?php echo $row['id']?>)"></p>

 
</div>             
            </td>
		    </tr>
		  <tr>
		    <td colspan="2" align="left" valign="middle">&nbsp;</td>
		  </tr>
		  <tr>
		    <td colspan="2" align="center" valign="middle"><input name="Submit<?php echo $row['ord']?>" id="Submit<?php echo $row['ord']?>" type="submit" class="button-primary" value="Update Playlist Record"></td>
		  </tr>
		</table>
       
            
        </form>
            <div id="ajax-message-<?php echo $row['ord']?>" class="ajax-message"></div>
    </div>
    </li>
	<?php } ?>
</ul>





</div>				