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
			<h2>Playlist for carousel: <span style="color:#FF0000; font-weight:bold;"><?php echo $_SESSION['xname']?> - ID #<?php echo $_SESSION['xid']?></span> - Add New</h2>
 	</div>

    <form method="POST" enctype="multipart/form-data" id="form-add-playlist-record">
	    <input name="carouselid" type="hidden" value="<?php echo $_SESSION['xid']?>" />
		<table class="wp-list-table widefat fixed pages" cellspacing="0">
		  <tr>
		    <td align="left" valign="middle" width="25%">&nbsp;</td>
		    <td align="left" valign="middle" width="77%"><a href="?page=all_in_one_carousel_Playlist" style="padding-left:25%;">Back to Playlist</a></td>
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
		    <td width="77%" align="left" valign="top"><input name="img" type="text" id="img" size="60" value="<?php echo $_POST['img']?>" /> <input name="upload_img_button" type="button" id="upload_img_button" value="Upload Image" />
	        <br />
              Enter an URL or upload an image<br />
              <br />
              Recommended size for each skin:
              <blockquote>
              Sweet: 	520px x 385px<br />
				Powerful: 	326px x 329px<br />
				Charming: 	452px x 302px 
              </blockquote><br />
If you need other dimensions please edit in wp-content\plugins\all_in_one_carousel\carousel\allinone_carousel.css file the "contentHolderUnit" class associated with the skin:
<blockquote>.allinone_carousel.sweet .contentHolderUnit <br />
.allinone_carousel.powerful .contentHolderUnit <br />
.allinone_carousel.charming .contentHolderUnit </blockquote></td>
		  </tr>
<tr>
		    <td align="right" valign="top" class="row-title">Link For The Image</td>
		    <td align="left" valign="top"><input name="data-link" type="text" size="60" id="data-link" value="<?php echo $_POST['data-link'];?>"/></td>
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
		    <td width="77%" align="left" valign="top"><input name="thumbnail" type="text" id="thumbnail" size="60" value="<?php echo $_POST['thumbnail']?>" /> <input name="upload_thumbnail_button" type="button" id="upload_thumbnail_button" value="Upload Image" />
	        <br />
	        Enter an URL or upload an image<br />
	        <br />
	        Recommended size for each skin: 80px x 80px </td>
		  </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Image Title</td>
		    <td align="left" valign="top"><textarea name="data-title" id="data-title" cols="45" rows="5"><?php echo $_POST['data-title'];?></textarea>    </td>
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
		    <td align="left" valign="top"><textarea name="content" id="content" cols="45" rows="5"><?php echo $_POST['content'];?></textarea></td>
	      </tr>
		  <tr>
            <td align="right" valign="top" class="row-title">&nbsp;</td>
		    <td align="left" valign="top">&nbsp;</td>
	      </tr>
		  <tr>
		    <td colspan="2" align="left" valign="middle">&nbsp;</td>
		  </tr>
		  <tr>
		    <td colspan="2" align="center" valign="middle"><input name="Submit<?php echo $_POST['ord']?>" id="Submit<?php echo $_POST['ord']?>" type="submit" class="button-primary" value="Add Record"></td>
		  </tr>
		</table>     
  </form>






</div>				