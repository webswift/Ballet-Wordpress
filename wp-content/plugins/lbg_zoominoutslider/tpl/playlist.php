<script>
jQuery(document).ready(function() {
		window.send_to_editor = function(html) {
		 imgurl = jQuery('img',html).attr('src');
		 //jQuery('#'+formfield).val(imgurl);
		 if (formfield=='img') {
		 	document.forms["form-playlist-lbg_zoominoutslider-"+the_i].img.value=imgurl;
		 } else {
			 document.forms["form-playlist-lbg_zoominoutslider-"+the_i].thumbnail.value=imgurl;
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
  <div id="lbg_zoominoutslider_updating_witness"><img src="<?php echo plugins_url('images/ajax-loader.gif', dirname(__FILE__))?>" /> Updating...</div>
  <div id="previewDialog"><iframe id="previewDialogIframe" src="" width="100%" height="600" style="border:0;"></iframe></div>
  
  
<div style="text-align:center; padding:0px 0px 20px 0px;"><img src="<?php echo plugins_url('images/icons/add_icon.gif', dirname(__FILE__))?>" alt="add" align="absmiddle" /> <a href="?page=lbg_zoominoutslider_Playlist&xmlf=add_playlist_record">Add new</a> &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <img src="<?php echo plugins_url('images/icons/magnifier.png', dirname(__FILE__))?>" alt="add" align="absmiddle" /> <a href="javascript: void(0);" onclick="showDialogPreview(<?php echo strip_tags($_SESSION['xid'])?>)">Preview Slider</a></div>
<div style="text-align:left; padding:10px 0px 10px 14px;">#Initial Order</div>


<ul id="lbg_zoominoutslider_sortable">
	<?php foreach ( $result as $row ) 
	{
		$row=lbg_zoominoutslider_unstrip_array($row); ?>
	<li class="ui-state-default cursor_move" id="<?php echo $row['id']?>">#<?php echo $row['ord']?> ---  <img src="<?php echo $row['img']?>" height="30" align="absmiddle" id="top_image_<?php echo $row['id']?>" /><div class="toogle-btn-closed" id="toogle-btn<?php echo $row['ord']?>" onclick="mytoggle('toggleable<?php echo $row['ord']?>','toogle-btn<?php echo $row['ord']?>');"></div><div class="options"><a href="javascript: void(0);" onclick="lbg_zoominoutslider_delete_entire_record(<?php echo $row['id']?>,<?php echo $row['ord']?>);" style="color:#F00;">Delete</a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="?page=lbg_zoominoutslider_Playlist&amp;id=<?php echo strip_tags($_SESSION['xid'])?>&amp;name=<?php echo strip_tags($_SESSION['xname'])?>&amp;duplicate_id=<?php echo $row['id']?>">Duplicate</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
	<div class="toggleable" id="toggleable<?php echo $row['ord']?>">
    <form method="POST" enctype="multipart/form-data" id="form-playlist-lbg_zoominoutslider-<?php echo $row['ord']?>">
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
              <input name="upload_img_button_lbg_zoominoutslider_<?php echo $row['ord']?>" type="button" id="upload_img_button_lbg_zoominoutslider_<?php echo $row['ord']?>" value="Change Image" />
              <br />
              Enter an URL or upload an image<br />
              <br />
              Recommended size: width &amp; height of the banner</td>
            </tr>
      <?php if ($row['img']!='') {?>
      <tr>
        <td align="right" valign="top" class="row-title">&nbsp;</td>
        <td align="left" valign="middle"><img src="<?php echo $row['img']?>" width="300" id="img_<?php echo $row['ord']?>" /></td>
      </tr>
      <?php } ?>
		  <tr>
		    <td align="right" valign="top" class="row-title">Link For The Image</td>
		    <td align="left" valign="top"><input name="data-link" type="text" size="60" id="data-link" value="<?php echo stripslashes($row['data-link']);?>"/></td>
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
              <input name="upload_thumbnail_button_lbg_zoominoutslider_<?php echo $row['ord']?>" type="button" id="upload_thumbnail_button_lbg_zoominoutslider_<?php echo $row['ord']?>" value="Change Thumbnail" />
              <br />
              Enter an URL or upload an image<br />
              <br />
              Recommended size for each skin: <br />
- opportune:
	        80px x 80px<br />
- majestic:
	        110px x 60px<br />
- generous:
	        110px x 65px</td>
            </tr>
      <?php if ($row['thumbnail']!='') { ?>
      <tr>
        <td align="right" valign="top" class="row-title">&nbsp;</td>
        <td align="left" valign="middle"><img src="<?php echo $row['thumbnail']?>" name="thumbnail_<?php echo $row['ord']?>" id="thumbnail_<?php echo $row['ord']?>" /></td>
      </tr>
      <?php } ?>
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
		    <td align="right" valign="top" class="row-title">Particular AutoPlay</td>
		    <td align="left" valign="middle"><input name="data-autoPlay" type="text" size="60" id="data-autoPlay" value="<?php echo stripslashes($row['data-autoPlay']);?>"/> 
		      seconds (0 will be ignored)</td>
		  </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">&nbsp;</td>
		    <td align="left" valign="middle">&nbsp;</td>
		    </tr>
		              
		  <tr>
		    <td colspan="2" align="center" valign="top" class="lbg_regGray">- Zoom In/Out Effect Settings -</td>
		    </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Horizontal Position</td>
		    <td align="left" valign="middle"><select name="data-horizontalPosition" id="data-horizontalPosition">
		      <option value="">select...</option>
		      <option value="left" <?php echo (($row['data-horizontalPosition']=='left')?'selected="selected"':'')?>>left</option>
		      <option value="center" <?php echo (($row['data-horizontalPosition']=='center')?'selected="selected"':'')?>>center</option>
		      <option value="right" <?php echo (($row['data-horizontalPosition']=='right')?'selected="selected"':'')?>>right</option>
		      </select>
		      <i>(If you don't select a value, 'Default Horizontal Position value from 'Slider Settings' will be used)</i></td>
		    </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Vertical Position</td>
		    <td align="left" valign="middle"><select name="data-verticalPosition" id="data-verticalPosition">
		      <option value="">select...</option>
		      <option value="top" <?php echo (($row['data-verticalPosition']=='top')?'selected="selected"':'')?>>top</option>
		      <option value="center" <?php echo (($row['data-verticalPosition']=='center')?'selected="selected"':'')?>>center</option>
		      <option value="bottom" <?php echo (($row['data-verticalPosition']=='bottom')?'selected="selected"':'')?>>bottom</option>
		      </select>
		      <i>(If you don't select a value, 'Default Vertical Position' value from 'Slider Settings' will be used)</i></td>
		    </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Initial Zoom</td>
		    <td align="left" valign="middle"><input name="data-initialZoom" type="text" size="25" id="data-initialZoom" value="<?php echo $row['data-initialZoom'];?>"/>
		      <i>(We recommend values between 0.5 - 1. If you leave it blank, 'Default Initial Zoom' value from 'Slider Settings' will be used)</i></td>
		    </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Final Zoom</td>
		    <td align="left" valign="middle"><input name="data-finalZoom" type="text" size="25" id="data-finalZoom" value="<?php echo $row['data-finalZoom'];?>"/>
		      <i>(We recommend values between 0.5 - 1. If you leave it blank, 'Default Final Zoom' value from 'Slider Settings' will be used)</i></td>
		    </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Zoom In/Out Effect Duration</td>
		    <td align="left" valign="middle"><input name="data-duration" type="text" size="25" id="data-duration" value="<?php echo $row['data-duration'];?>"/>
		      <i>seconds (If you leave it blank, 'Default Zoom In/Out Effect Duration' value from 'Slider Settings' will be used)</i></td>
		    </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Zoom Effect Easing</td>
		    <td align="left" valign="middle"><select name="data-zoomEasing" id="data-zoomEasing">
              <option value="">select...</option>
              <option value="swing" <?php echo (($row['data-zoomEasing']=='swing')?'selected="selected"':'')?>>swing</option>
              <option value="linear" <?php echo (($row['data-zoomEasing']=='linear')?'selected="selected"':'')?>>linear</option>
              <option value="ease" <?php echo (($row['data-zoomEasing']=='ease')?'selected="selected"':'')?>>ease</option> 
              <option value="ease-in" <?php echo (($row['data-zoomEasing']=='ease-in')?'selected="selected"':'')?>>ease-in</option> 
              <option value="ease-out" <?php echo (($row['data-zoomEasing']=='ease-out')?'selected="selected"':'')?>>ease-out</option> 
              <option value="ease-in-out" <?php echo (($row['data-zoomEasing']=='ease-in-out')?'selected="selected"':'')?>>ease-in-out</option>  
		      <option value="easeInQuad" <?php echo (($row['data-zoomEasing']=='easeInQuad')?'selected="selected"':'')?>>easeInQuad</option>
		      <option value="easeOutQuad" <?php echo (($row['data-zoomEasing']=='easeOutQuad')?'selected="selected"':'')?>>easeOutQuad</option>
              <option value="easeInOutQuad" <?php echo (($row['data-zoomEasing']=='easeInOutQuad')?'selected="selected"':'')?>>easeInOutQuad</option>
              <option value="easeInCubic" <?php echo (($row['data-zoomEasing']=='easeInCubic')?'selected="selected"':'')?>>easeInCubic</option>
              <option value="easeOutCubic" <?php echo (($row['data-zoomEasing']=='easeOutCubic')?'selected="selected"':'')?>>easeOutCubic</option>
              <option value="easeInOutCubic" <?php echo (($row['data-zoomEasing']=='easeInOutCubic')?'selected="selected"':'')?>>easeInOutCubic</option>              
              <option value="easeInQuart" <?php echo (($row['data-zoomEasing']=='easeInQuart')?'selected="selected"':'')?>>easeInQuart</option>
              <option value="easeOutQuart" <?php echo (($row['data-zoomEasing']=='easeOutQuart')?'selected="selected"':'')?>>easeOutQuart</option>
              <option value="easeInOutQuart" <?php echo (($row['data-zoomEasing']=='easeInOutQuart')?'selected="selected"':'')?>>easeInOutQuart</option>
              <option value="easeInSine" <?php echo (($row['data-zoomEasing']=='easeInSine')?'selected="selected"':'')?>>easeInSine</option>   
              <option value="easeOutSine" <?php echo (($row['data-zoomEasing']=='easeOutSine')?'selected="selected"':'')?>>easeOutSine</option>
              <option value="easeInOutSine" <?php echo (($row['data-zoomEasing']=='easeInOutSine')?'selected="selected"':'')?>>easeInOutSine</option>
              <option value="easeInExpo" <?php echo (($row['data-zoomEasing']=='easeInExpo')?'selected="selected"':'')?>>easeInExpo</option>
              <option value="easeOutExpo" <?php echo (($row['data-zoomEasing']=='easeOutExpo')?'selected="selected"':'')?>>easeOutExpo</option>
              <option value="easeInOutExpo" <?php echo (($row['data-zoomEasing']=='easeInOutExpo')?'selected="selected"':'')?>>easeInOutExpo</option>
              <option value="easeInQuint" <?php echo (($row['data-zoomEasing']=='easeInQuint')?'selected="selected"':'')?>>easeInQuint</option>
              <option value="easeOutQuint" <?php echo (($row['data-zoomEasing']=='easeOutQuint')?'selected="selected"':'')?>>easeOutQuint</option>
              <option value="easeInOutQuint" <?php echo (($row['data-zoomEasing']=='easeInOutQuint')?'selected="selected"':'')?>>easeInOutQuint</option>
              <option value="easeInCirc" <?php echo (($row['data-zoomEasing']=='easeInCirc')?'selected="selected"':'')?>>easeInCirc</option>
              <option value="easeOutCirc" <?php echo (($row['data-zoomEasing']=='easeOutCirc')?'selected="selected"':'')?>>easeOutCirc</option>
              <option value="easeInOutCirc" <?php echo (($row['data-zoomEasing']=='easeInOutCirc')?'selected="selected"':'')?>>easeInOutCirc</option>
	        </select> <i>(If you leave it blank, 'Default Zoom Effect Easing' value from 'Slider Settings' will be used)</i></td>
		    </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">&nbsp;</td>
		    <td align="left" valign="middle">&nbsp;</td>
		  </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Manage Layers Over the Photo</td>
		    <td align="left" valign="middle"><a href="?page=lbg_zoominoutslider_Edit_Elements&playlistID=<?php echo $row['id']?>"><input name="texts<?php echo $row['ord']?>" id="texts<?php echo $row['ord']?>" type="button" class="button-primary" value="Open Elements Editor"></a></td>
		  </tr>          
		  <tr>
		    <td colspan="2" align="left" valign="middle">
            
<div id="dialog<?php echo $row['ord']?>" title="Manage Texts Over the Photo" style="display:none; padding:0; margin:0;">
	<div id="photo_div<?php echo $row['id']?>" style="padding:50px 0 0px 50px;">
    <?php
		if ($row['img']!='') { ?>
    		<img src="<?php echo $row['img']?>" />
        <?php } else { ?>
			<div style="width:<?php echo $row_settings->width?>px; height:<?php echo $row_settings->height?>px; border:1px solid #CCC;">&nbsp;</div>
		<?php }
    	$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_texts) WHERE photoid = %d ORDER BY id",$row['id'] );
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
				jQuery("#data-initial-left<?php echo $row_text['id']?>").val(lbg_zoominoutslider_process_val(jQuery(this).css('left'),'left'));
				jQuery("#data-initial-top<?php echo $row_text['id']?>").val(lbg_zoominoutslider_process_val(jQuery(this).css('top'),'top'));
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
	<td colspan="8"><div class="delete_text" onclick="lbg_zoominoutslider_delete_text_line(<?php echo $row_text['id']?>)">&nbsp;</div></td>
	</tr>  
</table>
    </div>
    
    <?php } ?>    
    </div>
    <p><input name="lbg_zoominoutslider_add_text_line<?php echo $row['ord']?>" id="lbg_zoominoutslider_add_text_line<?php echo $row['ord']?>" type="button" class="button-primary" value="Add New Text Line" onclick="lbg_zoominoutslider_add_text_line(<?php echo $row['id']?>)"></p>

 
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