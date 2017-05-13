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
			<h2>Playlist for banner: <span style="color:#FF0000; font-weight:bold;"><?php echo $_SESSION['xname']?> - ID #<?php echo $_SESSION['xid']?></span> - Add New</h2>
 	</div>

    <form method="POST" enctype="multipart/form-data" id="form-add-playlist-record">
	    <input name="bannerid" type="hidden" value="<?php echo $_SESSION['xid']?>" />
		<table class="wp-list-table widefat fixed pages" cellspacing="0">
		  <tr>
		    <td align="left" valign="middle" width="25%">&nbsp;</td>
		    <td align="left" valign="middle" width="77%"><a href="?page=all_in_one_bannerWithPlaylist_Playlist" style="padding-left:25%;">Back to Playlist</a></td>
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
		    <td align="right" valign="top" class="row-title">Image* </td>
		    <td width="77%" align="left" valign="top"><input name="img" type="text" id="img" size="60" value="<?php echo $_POST['img']?>" /> <input name="upload_img_button" type="button" id="upload_img_button" value="Upload Image" />
	        <br />
	        Enter an URL or upload an image<br />
	        <br />
	        Recommended size: width &amp; height of the banner</td>
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
Recommended size for each skin:<br />
<blockquote>
  Pure Gallery: 214px × 128px<br />
  Elegant: 90px × 90px
</blockquote></td>
		  </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Image Title/Alternative Text</td>
		    <td align="left" valign="top"><input name="alt_text" type="text" size="60" id="alt_text" value="<?php echo $_POST['alt_text'];?>"/>    </td>
		  </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Image Effect</td>
		    <td align="left" valign="middle"><select name="data-transition" id="data-transition">
              <option value="" <?php echo (($_POST['data-transition']=='')?'selected="selected"':'')?>>select...</option>
		      <option value="random" <?php echo (($_POST['data-transition']=='random')?'selected="selected"':'')?>>random</option>
		      <option value="asynchronousDroppingStripes" <?php echo (($_POST['data-transition']=='asynchronousDroppingStripes')?'selected="selected"':'')?>>asynchronousDroppingStripes</option>
		      <option value="bottomTopDroppingReverseStripes" <?php echo (($_POST['data-transition']=='bottomTopDroppingReverseStripes')?'selected="selected"':'')?>>bottomTopDroppingReverseStripes</option>
              <option value="bottomTopDroppingStripes" <?php echo (($_POST['data-transition']=='bottomTopDroppingStripes')?'selected="selected"':'')?>>bottomTopDroppingStripes</option>
              <option value="fade" <?php echo (($_POST['data-transition']=='fade')?'selected="selected"':'')?>>fade</option>
              <option value="leftRightFadingReverseStripes" <?php echo (($_POST['data-transition']=='leftRightFadingReverseStripes')?'selected="selected"':'')?>>leftRightFadingReverseStripes</option>
              <option value="leftRightFadingStripes" <?php echo (($_POST['data-transition']=='leftRightFadingStripes')?'selected="selected"':'')?>>leftRightFadingStripes</option>
              <option value="randomBlocks" <?php echo (($_POST['data-transition']=='randomBlocks')?'selected="selected"':'')?>>randomBlocks</option>
              <option value="slideFromBottom" <?php echo (($_POST['data-transition']=='slideFromBottom')?'selected="selected"':'')?>>slideFromBottom</option>
              <option value="slideFromLeft" <?php echo (($_POST['data-transition']=='slideFromLeft')?'selected="selected"':'')?>>slideFromLeft</option>
              <option value="slideFromTop" <?php echo (($_POST['data-transition']=='slideFromTop')?'selected="selected"':'')?>>slideFromTop</option>
              <option value="topBottomDiagonalBlocks" <?php echo (($_POST['data-transition']=='topBottomDiagonalBlocks')?'selected="selected"':'')?>>topBottomDiagonalBlocks</option>
              <option value="topBottomDiagonalReverseBlocks" <?php echo (($_POST['data-transition']=='topBottomDiagonalReverseBlocks')?'selected="selected"':'')?>>topBottomDiagonalReverseBlocks</option>
              <option value="topBottomDroppingReverseStripes" <?php echo (($_POST['data-transition']=='topBottomDroppingReverseStripes')?'selected="selected"':'')?>>topBottomDroppingReverseStripes</option>
              <option value="topBottomDroppingStripe" <?php echo (($_POST['data-transition']=='topBottomDroppingStripe')?'selected="selected"':'')?>>topBottomDroppingStripe</option>
	        </select></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Playlist Title</td>
		    <td align="left" valign="top"><input name="data-title" type="text" size="60" id="data-title" value="<?php echo $_POST['data-title'];?>"/></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Playlist Description</td>
		    <td align="left" valign="top"><textarea name="data-desc" id="data-desc" cols="45" rows="5"><?php echo $_POST['data-desc'];?></textarea></td>
	      </tr>
		  <tr>
            <td align="right" valign="top" class="row-title">&nbsp;</td>
		    <td align="left" valign="top">&nbsp;</td>
	      </tr>
		  <tr>
		    <td colspan="2" align="left" valign="middle">*Required fields</td>
		  </tr>
		  <tr>
		    <td colspan="2" align="center" valign="middle"><input name="Submit<?php echo $_POST['ord']?>" id="Submit<?php echo $_POST['ord']?>" type="submit" class="button-primary" value="Add Record"></td>
		  </tr>
		</table>     
  </form>






</div>				