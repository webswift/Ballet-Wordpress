<script>
jQuery(document).ready(function() {
 
jQuery('#upload_img_button').click(function() {
 //formfield = jQuery('#img').attr('name');
 formfield = 'img';
 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
 return false;
});

jQuery('#upload_thumbnail_button').click(function() {
 //formfield = jQuery('#thumbnail').attr('name');
 formfield = 'thumbnail';
 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
 return false;
});
 
window.send_to_editor = function(html) {
 imgurl = jQuery('img',html).attr('src');
 jQuery('#'+formfield).val(imgurl);
 tb_remove();
 
 
}
 
});
</script>

<div class="wrap">
	<div id="lbg_logo">
			<h2>Playlist for slider: <span style="color:#FF0000; font-weight:bold;"><?php echo strip_tags($_SESSION['xname'])?> - ID #<?php echo strip_tags($_SESSION['xid'])?></span> - Add New</h2>
 	</div>

    <form method="POST" enctype="multipart/form-data" id="form-add-playlist-record">
	    <input name="bannerid" type="hidden" value="<?php echo strip_tags($_SESSION['xid'])?>" />
		<table class="wp-list-table widefat fixed pages" cellspacing="0">
		  <tr>
		    <td align="left" valign="middle" width="25%">&nbsp;</td>
		    <td align="left" valign="middle" width="77%"><a href="?page=lbg_zoominoutslider_Playlist" style="padding-left:25%;">Back to Playlist</a></td>
		  </tr>
		  <tr>
		    <td colspan="2" align="left" valign="middle">&nbsp;</td>
	      </tr>
		  <tr>
		    <td align="right" valign="middle" class="row-title">Set It First</td>
		    <td align="left" valign="top"><input name="setitfirst" type="checkbox" id="setitfirst" value="1" checked="checked" />
		      <label for="setitfirst"></label></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Image </td>
		    <td width="77%" align="left" valign="top"><input name="img" type="text" id="img" size="60" value="<?php echo strip_tags($_POST['img'])?>" /> <input name="upload_img_button" type="button" id="upload_img_button" value="Upload Image" />
	        <br />
	        Enter an URL or upload an image<br />
	        <br />
	        Recommended size: width &amp; height of the Slider</td>
		  </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Link For The Image</td>
		    <td align="left" valign="top"><input name="data-link" type="text" size="60" id="data-link" value="<?php echo strip_tags($_POST['data-link']);?>"/></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Link Target</td>
		    <td align="left" valign="top"><select name="data-target" id="data-target">
              <option value="" <?php echo (($_POST['data-target']=='')?'selected="selected"':'')?>>select...</option>
		      <option value="_blank" <?php echo (($_POST['data-target']=='_blank')?'selected="selected"':'')?>>_blank</option>
		      <option value="_self" <?php echo (($_POST['data-target']=='_self')?'selected="selected"':'')?>>_self</option>
		      
	        </select></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Thumbnail </td>
		    <td width="77%" align="left" valign="top"><input name="thumbnail" type="text" id="thumbnail" size="60" value="<?php echo strip_tags($_POST['thumbnail'])?>" /> <input name="upload_thumbnail_button" type="button" id="upload_thumbnail_button" value="Upload Image" />
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
		  <tr>
		    <td align="right" valign="top" class="row-title">Image Title/Alternative Text</td>
		    <td align="left" valign="top"><input name="alt_text" type="text" size="60" id="alt_text" value="<?php echo strip_tags($_POST['alt_text']);?>"/>    </td>
		  </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Video Beneath Image</td>
		    <td align="left" valign="middle"><select name="data-video" id="data-video">
		      <option value="false" <?php echo (($_POST['data-video']=='false')?'selected="selected"':'')?>>false</option>
		      <option value="true" <?php echo (($_POST['data-video']=='true')?'selected="selected"':'')?>>true</option>
	        </select></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Content</td>
		    <td align="left" valign="top"><textarea name="content" id="content" cols="45" rows="5"><?php echo strip_tags($_POST['content'])?></textarea></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Particular AutoPlay</td>
		    <td align="left" valign="middle"><input name="data-autoPlay" type="text" size="60" id="data-autoPlay" value="<?php echo strip_tags($_POST['data-autoPlay']);?>"/> 
		      seconds (0 will be ignored)</td>
		  </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">&nbsp;</td>
		    <td align="left" valign="top">&nbsp;</td>
	      </tr>
		  <tr>
		    <td colspan="2" align="center" valign="top" class="lbg_regGray">- Zoom In/Out Effect Settings -</td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Horizontal Position</td>
		    <td align="left" valign="middle"><select name="data-horizontalPosition" id="data-horizontalPosition">
              <option value="">select...</option>
		      <option value="left" <?php echo (($_POST['data-horizontalPosition']=='left')?'selected="selected"':'')?>>left</option>
		      <option value="center" <?php echo (($_POST['data-horizontalPosition']=='center')?'selected="selected"':'')?>>center</option>
		      <option value="right" <?php echo (($_POST['data-horizontalPosition']=='right')?'selected="selected"':'')?>>right</option>
		      </select>
		      <i>(If you don't select a value, 'Default Horizontal Position value from 'Slider Settings' will be used)</i></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Vertical Position</td>
		    <td align="left" valign="middle"><select name="data-verticalPosition" id="data-verticalPosition">
              <option value="">select...</option>
		      <option value="top" <?php echo (($_POST['data-verticalPosition']=='top')?'selected="selected"':'')?>>top</option>
		      <option value="center" <?php echo (($_POST['data-verticalPosition']=='center')?'selected="selected"':'')?>>center</option>
		      <option value="bottom" <?php echo (($_POST['data-verticalPosition']=='bottom')?'selected="selected"':'')?>>bottom</option>
		      </select>
		      <i>(If you don't select a value, 'Default Vertical Position' value from 'Slider Settings' will be used)</i></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Initial Zoom</td>
		    <td align="left" valign="middle"><input name="data-initialZoom" type="text" size="25" id="data-initialZoom" value="<?php echo $_POST['data-initialZoom'];?>"/>
		      <i>(We recommend values between 0.5 - 1. If you leave it blank, 'Default Initial Zoom' value from 'Slider Settings' will be used)</i></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Final Zoom</td>
		    <td align="left" valign="middle"><input name="data-finalZoom" type="text" size="25" id="data-finalZoom" value="<?php echo $_POST['data-finalZoom'];?>"/>
		      <i>(We recommend values between 0.5 - 1. If you leave it blank, 'Default Final Zoom' value from 'Slider Settings' will be used)</i></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Zoom In/Out Effect Duration</td>
		    <td align="left" valign="middle"><input name="data-duration" type="text" size="25" id="data-duration" value="<?php echo $_POST['data-duration'];?>"/>
	        <i>seconds (If you leave it blank, 'Default Zoom In/Out Effect Duration' value from 'Slider Settings' will be used)</i></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Zoom Effect Easing</td>
		    <td align="left" valign="top"><select name="data-zoomEasing" id="data-zoomEasing">
              <option value="">select...</option>
              <option value="swing" <?php echo (($_POST['data-zoomEasing']=='swing')?'selected="selected"':'')?>>swing</option>
              <option value="linear" <?php echo (($_POST['data-zoomEasing']=='linear')?'selected="selected"':'')?>>linear</option>
              <option value="ease" <?php echo (($_POST['data-zoomEasing']=='ease')?'selected="selected"':'')?>>ease</option> 
              <option value="ease-in" <?php echo (($_POST['data-zoomEasing']=='ease-in')?'selected="selected"':'')?>>ease-in</option> 
              <option value="ease-out" <?php echo (($_POST['data-zoomEasing']=='ease-out')?'selected="selected"':'')?>>ease-out</option> 
              <option value="ease-in-out" <?php echo (($_POST['data-zoomEasing']=='ease-in-out')?'selected="selected"':'')?>>ease-in-out</option>  
		      <option value="easeInQuad" <?php echo (($_POST['data-zoomEasing']=='easeInQuad')?'selected="selected"':'')?>>easeInQuad</option>
		      <option value="easeOutQuad" <?php echo (($_POST['data-zoomEasing']=='easeOutQuad')?'selected="selected"':'')?>>easeOutQuad</option>
              <option value="easeInOutQuad" <?php echo (($_POST['data-zoomEasing']=='easeInOutQuad')?'selected="selected"':'')?>>easeInOutQuad</option>
              <option value="easeInCubic" <?php echo (($_POST['data-zoomEasing']=='easeInCubic')?'selected="selected"':'')?>>easeInCubic</option>
              <option value="easeOutCubic" <?php echo (($_POST['data-zoomEasing']=='easeOutCubic')?'selected="selected"':'')?>>easeOutCubic</option>
              <option value="easeInOutCubic" <?php echo (($_POST['data-zoomEasing']=='easeInOutCubic')?'selected="selected"':'')?>>easeInOutCubic</option>              
              <option value="easeInQuart" <?php echo (($_POST['data-zoomEasing']=='easeInQuart')?'selected="selected"':'')?>>easeInQuart</option>
              <option value="easeOutQuart" <?php echo (($_POST['data-zoomEasing']=='easeOutQuart')?'selected="selected"':'')?>>easeOutQuart</option>
              <option value="easeInOutQuart" <?php echo (($_POST['data-zoomEasing']=='easeInOutQuart')?'selected="selected"':'')?>>easeInOutQuart</option>
              <option value="easeInSine" <?php echo (($_POST['data-zoomEasing']=='easeInSine')?'selected="selected"':'')?>>easeInSine</option>   
              <option value="easeOutSine" <?php echo (($_POST['data-zoomEasing']=='easeOutSine')?'selected="selected"':'')?>>easeOutSine</option>
              <option value="easeInOutSine" <?php echo (($_POST['data-zoomEasing']=='easeInOutSine')?'selected="selected"':'')?>>easeInOutSine</option>
              <option value="easeInExpo" <?php echo (($_POST['data-zoomEasing']=='easeInExpo')?'selected="selected"':'')?>>easeInExpo</option>
              <option value="easeOutExpo" <?php echo (($_POST['data-zoomEasing']=='easeOutExpo')?'selected="selected"':'')?>>easeOutExpo</option>
              <option value="easeInOutExpo" <?php echo (($_POST['data-zoomEasing']=='easeInOutExpo')?'selected="selected"':'')?>>easeInOutExpo</option>
              <option value="easeInQuint" <?php echo (($_POST['data-zoomEasing']=='easeInQuint')?'selected="selected"':'')?>>easeInQuint</option>
              <option value="easeOutQuint" <?php echo (($_POST['data-zoomEasing']=='easeOutQuint')?'selected="selected"':'')?>>easeOutQuint</option>
              <option value="easeInOutQuint" <?php echo (($_POST['data-zoomEasing']=='easeInOutQuint')?'selected="selected"':'')?>>easeInOutQuint</option>
              <option value="easeInCirc" <?php echo (($_POST['data-zoomEasing']=='easeInCirc')?'selected="selected"':'')?>>easeInCirc</option>
              <option value="easeOutCirc" <?php echo (($_POST['data-zoomEasing']=='easeOutCirc')?'selected="selected"':'')?>>easeOutCirc</option>
              <option value="easeInOutCirc" <?php echo (($_POST['data-zoomEasing']=='easeInOutCirc')?'selected="selected"':'')?>>easeInOutCirc</option>
	        </select> <i>(If you leave it blank, 'Default Zoom Effect Easing' value from 'Slider Settings' will be used)</i></td>
	      </tr>
		  <tr>
            <td align="right" valign="top" class="row-title">&nbsp;</td>
		    <td align="left" valign="top">&nbsp;</td>
	      </tr>
		  <tr>
		    <td colspan="2" align="left" valign="middle">&nbsp;</td>
		  </tr>

		  <tr>
		    <td colspan="2" align="center" valign="middle"><input name="Submit<?php echo strip_tags($_POST['ord'])?>" id="Submit<?php echo strip_tags($_POST['ord'])?>" type="submit" class="button-primary" value="Add Record"></td>
		  </tr>
		</table>     
  </form>






</div>				