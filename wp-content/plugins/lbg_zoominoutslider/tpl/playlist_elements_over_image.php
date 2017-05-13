<script>
jQuery(document).ready(function() {
		jQuery('#addimagelayer').click(function() {
			 //formfield = jQuery('#img').attr('name');
			 formfield = 'addimg';
			 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
			 return false;
		});	
		jQuery('[id^="changeImage"]').click(function() {
			change_layer_image();
		});			
	
		window.send_to_editor = function(html) {
		 imgurl = jQuery('img',html).attr('src');
		 //alert (imgurl);
		 //jQuery('#'+formfield).val(imgurl);
		 if (formfield!='img_src') {
		 	lbg_zoominoutslider_add_text_line(<?php echo $row['id']?>,imgurl);
		 } else {
			jQuery('input[name=img_src'+jQuery('input[name=currentTextID]').val()+']').val(imgurl);
			jQuery('#draggable'+jQuery('input[name=currentTextID]').val()).html('<img src="'+imgurl+'">');
		 }
		 //alert (the_i); 	
		 //jQuery('#'+formfield+'_'+the_i).attr('src',imgurl);
		 tb_remove();
		 
		}
		
		jQuery('[id^="lbg_zoominoutsliderTable"]').css({
			'display':'none'
		});
		
		jQuery( "#dialogDataSaved" ).dialog({
		  width:210,
		  height: 120,
		  modal: true,
		  autoOpen:<?php echo ($_POST['Submit'] == 'Save All Changes')?'true':'false';?>,
		  hide: "fade",
		  resizable: false,
		  open: function(event, ui) {
    			setTimeout(function(){
       				 jQuery('#dialogDataSaved').dialog('close');                
    			}, 1000);
			}
    	});
		
		jQuery( "#dialogEditCSSClasses" ).dialog({
		  width:820,
		  height:590,
		  modal: true,
		  autoOpen:false,
		  hide: "fade",
		  resizable: true,
		  buttons: {
       		 "Update": function() {
          		lbg_zoominoutslider_edit_css_classes('update_css',jQuery('textarea#css_classes').val(),<?php echo $row['id']?>);
        	 },
       		 "Restore Original": function() {
          		lbg_zoominoutslider_edit_css_classes('restore_css','',<?php echo $row['id']?>);
        	 },			 
        	 Cancel: function() {
	          jQuery( this ).dialog( "close" );
        	 }
      	  },
		  close: function() {
			//allFields.val( "" ).removeClass( "ui-state-error" );
		  }
    	});	
	

});	



</script>


<div class="wrap">
	<div id="lbg_logo">
			<h2>Manage elemens over main image for <span style="color:#FF0000; font-weight:bold;">Slide No. <?php echo $row['ord']?></span>. The slider name is: <span style="color:#FF0000; font-weight:bold;"><?php echo strip_tags($_SESSION['xname'])?> - ID #<?php echo strip_tags($_SESSION['xid'])?></span></h2>
 	</div>
  <div id="lbg_zoominoutslider_updating_witness"><img src="<?php echo plugins_url('images/ajax-loader.gif', dirname(__FILE__))?>" /> Updating...</div>
  <div id="dialogDataSaved">
  	<p class="data_saved">Data Saved</p>
  </div>
  
  <div id="previewDialog"><iframe id="previewDialogIframe" src="" width="100%" height="600" style="border:0;"></iframe></div> 
  
  <div id="dialogEditCSSClasses" title="Edit CSS Classes">
  <form>
  	<textarea name="css_classes" id="css_classes" cols="80" rows="20"><?php echo $row_css['css_styles'];?></textarea>
  </form>
  <div id="editCSSClasses_message"></div>
</div>
  
<div class="bringElemToFront">  
<div style="text-align:center; padding:0px 0px 20px 0px;"><img src="<?php echo plugins_url('images/icons/back_icon.gif', dirname(__FILE__))?>" alt="back" align="absmiddle" /> <a href="?page=lbg_zoominoutslider_Playlist&amp;id=<?php echo $row['id']?>">Back to Playlist</a></div>


<table cellpadding="0" cellspacing="0">
	<tr>
    	<td><input name="addtextlayer" id="addtextlayer" type="button" class="button-primary" value="Add Text Layer" onclick="lbg_zoominoutslider_add_text_line(<?php echo $row['id']?>,'')"></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input name="addimagelayer" id="addimagelayer" type="button" class="button-primary" value="Add Image Layer"></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input name="deletelayer" id="deletelayer" type="button" class="button-primary" value="Delete Layer" onclick="lbg_zoominoutslider_delete_text_line(jQuery('input[name=currentTextID]').val())" disabled="disabled" ></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><a href="?page=lbg_zoominoutslider_Edit_Elements&playlistID=<?php echo $row['id']?>&deletealllayers=<?php echo $row['id']?>" onclick="return confirm('Are you sure?')"><input name="deletealllayers" id="deletealllayers" type="button" class="button-primary" value="Delete All Layers"></a></td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input name="duplicatelayer" id="duplicatelayer" type="button" class="button-secondary" disabled="disabled" value="Duplicate Layer" onclick="lbg_zoominoutslider_duplicate('duplicate layer',jQuery('input[name=currentTextID]').val())"></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input name="previewslider" id="previewslider" type="button" class="button-secondary" value="Preview Slider" onclick="showDialogPreview(<?php echo strip_tags($_SESSION['xid'])?>)"></td>     
    </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
   	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>      
    </tr>    
</table>
</div>
<div id="photo_div<?php echo $row['id']?>" style="width:<?php echo $row_settings['width'];?>px; height:<?php echo $row_settings['height'];?>px; border:2px dotted #000; margin-left:-2px;margin-top:-2px; position:relative;"><img src="<?php echo $row['img']?>"  onclick="deactivate_all_layers()" />		

<?php foreach ( $result_text as $row_text ) {
	if ($row_text['img_src']=='') {
		$draggable_aux=stripslashes($row_text['content']);
	} else {
		$draggable_aux='<img src="'.$row_text['img_src'].'">';
	}
	?>	
		<div id="draggable<?php echo $row_text['id']?>" rel="<?php echo $row_text['id']?>" class="my_draggable <?php echo $row_text['css']?>" style="left:<?php echo $row_text['data-final-left']?>px;top:<?php echo $row_text['data-final-top']?>px;z-index:<?php echo 100-$row_text['ord'];?>;" onclick="activate_layer(<?php echo $row_text['id']?>);"><?php echo $draggable_aux?></div>
		
<script>
		jQuery("#draggable<?php echo $row_text['id']?>").draggable( {
			drag: function(event, ui) { 
				jQuery("#data-final-left<?php echo $row_text['id']?>").val(lbg_zoominoutslider_process_val(jQuery(this).css('left'),'left'));
				jQuery("#data-final-top<?php echo $row_text['id']?>").val(lbg_zoominoutslider_process_val(jQuery(this).css('top'),'top'));
			}
		});

</script>    

    <?php } ?>   
    
</div>    
   <p>&nbsp;</p>  
<div class="bringElemToFront"> 
 <form method="POST" enctype="multipart/form-data" id="form-elements-lbg_zoominoutslider" name="form-elements-lbg_zoominoutslider">
<input name="currentTextID" id="currentTextID" type="hidden" />
<table width="100%" border="0" cellpadding="10" cellspacing="10" class="widefat">
			<thead>
				<tr>
					<th align="center" valign="middle" scope="col" style="border-right:1px solid #cccccc;">Layer Settings <i>(Click on a layer to activate its settings)</i></th>
                    <th align="center" valign="middle" scope="col">Layers Order <i>(drag the items to reposition them)</i></th>
				</tr>
			</thead>
  <tbody>          
  <tr>
    <td width="50%" style="border-right:1px solid #cccccc;" id="all_texts_settings">
    <?php if (!$wpdb->num_rows) 
			echo '<div id="no_layers">No layers defined, yet.</div>';
	?>
   
    <?php 
		$search_array=array('\"',"\'");
		$replace_array=array('"',"'");
		foreach ( $result_text as $row_text ) { ?>
    	<div id="lbg_zoominoutsliderTable<?php echo $row_text['id'];?>">

		<table width="100%" cellspacing="0" class="widefat">
		  <tr>
		    <td width="15%" align="left" valign="middle" class="row-title"><?php echo ($row_text['img_src']!='')?'Image Title':'HTML Content'?></td>
		    <td colspan="3" align="left" valign="middle"><textarea name="content<?php echo $row_text['id'];?>" cols="80" rows="5" id="content<?php echo $row_text['id'];?>" onkeyup="change_text_div_content(<?php echo $row_text['id'];?>,this);"><?php echo str_replace($search_array,$replace_array,$row_text['content']);?></textarea></td>
		  </tr>
		  <tr>
		    <td align="left" valign="middle" class="row-title"><?php echo (($row_text['img_src']!='')?'Image Path':'')?></td>
		    <td colspan="3" align="left" valign="middle"><input name="img_src<?php echo $row_text['id'];?>" id="img_src<?php echo $row_text['id'];?>" value="<?php echo $row_text['img_src'];?>" size="60" type="<?php echo (($row_text['img_src']!='')?'text':'hidden')?>" /> <input name="changeImage<?php echo $row_text['id'];?>" id="changeImage<?php echo $row_text['id'];?>" type="button" class="button-primary" value="Change Image" style="float:right; display:<?php echo (($row_text['img_src']!='')?'block':'none')?>;"></td>
		  </tr>          
		  <tr>
		    <td align="left" valign="middle" class="row-title">CSS Style</td>
		    <td colspan="3" align="left" valign="middle"><div id="lbg_zoominoutsliderCSS_div<?php echo $row_text['id'];?>"><?php lbg_zoominoutslider_getCssStyles($row_text['css'],$row_text['id'],'');?></div> <input name="EditCssClasses<?php echo $row_text['id'];?>" id="EditCssClasses<?php echo $row_text['id'];?>" type="button" class="button-primary" value="Edit CSS Clases" style="float:right; margin-top:-25px;" onclick="lbg_zoominoutslider_edit_css_classes('open','',<?php echo $row['id']?>)"></td>
		    </tr>
		  <tr>
		    <td align="left" valign="middle" class="row-title">Element Link</td>
		    <td colspan="3" align="left" valign="middle"><input name="element-link<?php echo $row_text['id'];?>" type="text" id="element-link<?php echo $row_text['id'];?>" value="<?php echo $row_text['element-link'];?>" size="80" /></td>
		  </tr>
		  <tr>
		    <td align="left" valign="middle" class="row-title">Element Link Target</td>
		    <td colspan="3" align="left" valign="middle"><select name="element-link-target<?php echo $row_text['id'];?>" id="element-link-target<?php echo $row_text['id'];?>">
              <option value="_self" <?php echo (($row_text['element-link-target']=='_self')?'selected="selected"':'')?>>_self</option>
              <option value="_blank" <?php echo (($row_text['element-link-target']=='_blank')?'selected="selected"':'')?>>_blank</option>
            </select></td>
		  </tr>          
		  <tr>
		    <td align="left" valign="middle" class="row-title">Preanimate</td>
		    <td colspan="3" align="left" valign="middle"><select name="data-preanimate<?php echo $row_text['id'];?>" id="data-preanimate<?php echo $row_text['id'];?>">
              <option value="true" <?php echo (($row_text['data-preanimate']=='true')?'selected="selected"':'')?>>true</option>
              <option value="false" <?php echo (($row_text['data-preanimate']=='false')?'selected="selected"':'')?>>false</option>
            </select></td>
		  </tr>
      
		  <tr>
		    <td align="left" valign="middle">&nbsp;</td>
		    <td width="35%" align="left" valign="middle">&nbsp;</td>
		    <td width="15%" align="left" valign="middle">&nbsp;</td>
		    <td width="35%" align="left" valign="middle">&nbsp;</td>
		    </tr>                 
		  <tr>
		    <td colspan="4" align="left" valign="middle" class="lbg_regGrayWithBg">Enter Values</td>
		    </tr>
		  <tr>
		    <td align="left" valign="middle" class="row-title">Initial Left</td>
		    <td align="left" valign="middle"><input name="data-initial-left<?php echo $row_text['id'];?>" type="text" id="data-initial-left<?php echo $row_text['id'];?>" size="10" value="<?php echo $row_text['data-initial-left'];?>" /> px</td>
		    <td align="left" valign="middle" class="row-title">Inital Top</td>
		    <td align="left" valign="middle"><input name="data-initial-top<?php echo $row_text['id'];?>" type="text" id="data-initial-top<?php echo $row_text['id'];?>" size="10" value="<?php echo $row_text['data-initial-top'];?>" /> px</td>
		  </tr>
		  <tr>
		    <td align="left" valign="middle" class="row-title">Final Left</td>
		    <td align="left" valign="middle"><input name="data-final-left<?php echo $row_text['id'];?>" type="text" id="data-final-left<?php echo $row_text['id'];?>" size="10" value="<?php echo $row_text['data-final-left'];?>" /> px</td>
		    <td align="left" valign="middle" class="row-title">Final Top</td>
		    <td align="left" valign="middle"><input name="data-final-top<?php echo $row_text['id'];?>" type="text" id="data-final-top<?php echo $row_text['id'];?>" size="10" value="<?php echo $row_text['data-final-top'];?>" /> px</td>
		  </tr>
		  <tr>
		    <td align="left" valign="middle" class="row-title">Intial Scale</td>
		    <td align="left" valign="middle"><input name="data-initial-scale<?php echo $row_text['id'];?>" type="text" id="data-initial-scale<?php echo $row_text['id'];?>" size="20" value="<?php echo $row_text['data-initial-scale'];?>" />		      
		       (0-100)</td>
		    <td align="left" valign="middle" class="row-title">Final Scale</td>
		    <td align="left" valign="middle"><input name="data-final-scale<?php echo $row_text['id'];?>" type="text" id="data-final-scale<?php echo $row_text['id'];?>" size="20" value="<?php echo $row_text['data-final-scale'];?>" />		        
		    (0-100)</td>
		    </tr>
		  <tr>
		    <td align="left" valign="middle" class="row-title">Initial Skew</td>
		    <td align="left" valign="middle"><input name="data-initial-skew<?php echo $row_text['id'];?>" type="text" id="data-initial-skew<?php echo $row_text['id'];?>" size="20" value="<?php echo $row_text['data-initial-skew'];?>" /> <br />
		      (Ex: 50deg,0deg)</td>
		    <td align="left" valign="middle" class="row-title">Final Skew</td>
		    <td align="left" valign="middle"><input name="data-final-skew<?php echo $row_text['id'];?>" type="text" id="data-final-skew<?php echo $row_text['id'];?>" size="20" value="<?php echo $row_text['data-final-skew'];?>" /> <br />
		      (Ex: 0deg,0deg)</td>
		    </tr>
		  <tr>
		    <td align="left" valign="middle" class="row-title">Duration</td>
		    <td align="left" valign="middle"><input name="data-duration<?php echo $row_text['id'];?>" type="text" id="data-duration<?php echo $row_text['id'];?>" size="10" value="<?php echo $row_text['data-duration'];?>" /> seconds</td>
		    <td align="left" valign="middle" class="row-title">Delay</td>
		    <td align="left" valign="middle"><input name="data-delay<?php echo $row_text['id'];?>" type="text" id="data-delay<?php echo $row_text['id'];?>" size="10" value="<?php echo $row_text['data-delay'];?>" /> seconds</td>
		    </tr>
		  <tr>
		    <td align="left" valign="middle" class="row-title">Initial Fade</td>
		    <td align="left" valign="middle"><input name="data-fade-start<?php echo $row_text['id'];?>" type="text" id="data-fade-start<?php echo $row_text['id'];?>" size="10" value="<?php echo $row_text['data-fade-start'];?>" /> (0-100)</td>
		    <td align="left" valign="middle" class="row-title">Easing</td>
		    <td align="left" valign="middle"><select name="data-easing<?php echo $row_text['id'];?>" id="data-easing<?php echo $row_text['id'];?>">
              <option value="swing" <?php echo (($row_text['data-easing']=='swing')?'selected="selected"':'')?>>swing</option>
              <option value="linear" <?php echo (($row_text['data-easing']=='linear')?'selected="selected"':'')?>>linear</option>
              <option value="ease" <?php echo (($row_text['data-easing']=='ease')?'selected="selected"':'')?>>ease</option> 
              <option value="ease-in" <?php echo (($row_text['data-easing']=='ease-in')?'selected="selected"':'')?>>ease-in</option> 
              <option value="ease-out" <?php echo (($row_text['data-easing']=='ease-out')?'selected="selected"':'')?>>ease-out</option> 
              <option value="ease-in-out" <?php echo (($row_text['data-easing']=='ease-in-out')?'selected="selected"':'')?>>ease-in-out</option>
		      <option value="easeInQuad" <?php echo (($row_text['data-easing']=='easeInQuad')?'selected="selected"':'')?>>easeInQuad</option>
		      <option value="easeOutQuad" <?php echo (($row_text['data-easing']=='easeOutQuad')?'selected="selected"':'')?>>easeOutQuad</option>
              <option value="easeInOutQuad" <?php echo (($row_text['data-easing']=='easeInOutQuad')?'selected="selected"':'')?>>easeInOutQuad</option>
              <option value="easeInCubic" <?php echo (($row_text['data-easing']=='easeInCubic')?'selected="selected"':'')?>>easeInCubic</option>
              <option value="easeOutCubic" <?php echo (($row_text['data-easing']=='easeOutCubic')?'selected="selected"':'')?>>easeOutCubic</option>
              <option value="easeInOutCubic" <?php echo (($row_text['data-easing']=='easeInOutCubic')?'selected="selected"':'')?>>easeInOutCubic</option>              
              <option value="easeInQuart" <?php echo (($row_text['data-easing']=='easeInQuart')?'selected="selected"':'')?>>easeInQuart</option>
              <option value="easeOutQuart" <?php echo (($row_text['data-easing']=='easeOutQuart')?'selected="selected"':'')?>>easeOutQuart</option>
              <option value="easeInOutQuart" <?php echo (($row_text['data-easing']=='easeInOutQuart')?'selected="selected"':'')?>>easeInOutQuart</option>
              <option value="easeInSine" <?php echo (($row_text['data-easing']=='easeInSine')?'selected="selected"':'')?>>easeInSine</option>   
              <option value="easeOutSine" <?php echo (($row_text['data-easing']=='easeOutSine')?'selected="selected"':'')?>>easeOutSine</option>
              <option value="easeInOutSine" <?php echo (($row_text['data-easing']=='easeInOutSine')?'selected="selected"':'')?>>easeInOutSine</option>
              <option value="easeInExpo" <?php echo (($row_text['data-easing']=='easeInExpo')?'selected="selected"':'')?>>easeInExpo</option>
              <option value="easeOutExpo" <?php echo (($row_text['data-easing']=='easeOutExpo')?'selected="selected"':'')?>>easeOutExpo</option>
              <option value="easeInOutExpo" <?php echo (($row_text['data-easing']=='easeInOutExpo')?'selected="selected"':'')?>>easeInOutExpo</option>
              <option value="easeInQuint" <?php echo (($row_text['data-easing']=='easeInQuint')?'selected="selected"':'')?>>easeInQuint</option>
              <option value="easeOutQuint" <?php echo (($row_text['data-easing']=='easeOutQuint')?'selected="selected"':'')?>>easeOutQuint</option>
              <option value="easeInOutQuint" <?php echo (($row_text['data-easing']=='easeInOutQuint')?'selected="selected"':'')?>>easeInOutQuint</option>
              <option value="easeInCirc" <?php echo (($row_text['data-easing']=='easeInCirc')?'selected="selected"':'')?>>easeInCirc</option>
              <option value="easeOutCirc" <?php echo (($row_text['data-easing']=='easeOutCirc')?'selected="selected"':'')?>>easeOutCirc</option>
              <option value="easeInOutCirc" <?php echo (($row_text['data-easing']=='easeInOutCirc')?'selected="selected"':'')?>>easeInOutCirc</option>
              <option value="easeInBack" <?php echo (($row_text['data-easing']=='easeInBack')?'selected="selected"':'')?>>easeInBack</option>
              <option value="easeOutBack" <?php echo (($row_text['data-easing']=='easeOutBack')?'selected="selected"':'')?>>easeOutBack</option>
              <option value="easeInOutBack" <?php echo (($row_text['data-easing']=='easeInOutBack')?'selected="selected"':'')?>>easeInOutBack</option>
	        </select></td>
		    </tr>
		  <tr>
		    <td colspan="4" align="left" valign="middle" class="lbg_regGrayWithBg">Exit Values</td>
		    </tr>
		  <tr>
		    <td align="left" valign="middle" class="row-title">Exit Left</td>
		    <td align="left" valign="middle"><input name="data-exit-left<?php echo $row_text['id'];?>" type="text" id="data-exit-left<?php echo $row_text['id'];?>" size="10" value="<?php echo $row_text['data-exit-left'];?>" /> px</td>
		    <td align="left" valign="middle" class="row-title">Exit Top</td>
		    <td align="left" valign="middle"><input name="data-exit-top<?php echo $row_text['id'];?>" type="text" id="data-exit-top<?php echo $row_text['id'];?>" size="10" value="<?php echo $row_text['data-exit-top'];?>" /> px</td>
		    </tr>
		  <tr>
		    <td align="left" valign="middle" class="row-title">Exit Skew</td>
		    <td align="left" valign="middle"><input name="data-exit-skew<?php echo $row_text['id'];?>" type="text" id="data-exit-skew<?php echo $row_text['id'];?>" size="20" value="<?php echo $row_text['data-exit-skew'];?>" /> <br />
		      (Ex: 50deg,0deg)</td>
		    <td align="left" valign="middle" class="row-title">Exit Scale</td>
		    <td align="left" valign="middle"><input name="data-exit-scale<?php echo $row_text['id'];?>" type="text" id="data-exit-scale<?php echo $row_text['id'];?>" size="20" value="<?php echo $row_text['data-exit-scale'];?>" />
		      (0-100)</td>
		    </tr>
		  <tr>
		    <td align="left" valign="middle" class="row-title">Exit Duration</td>
		    <td align="left" valign="middle"><input name="data-exit-duration<?php echo $row_text['id'];?>" type="text" id="data-exit-duration<?php echo $row_text['id'];?>" size="10" value="<?php echo $row_text['data-exit-duration'];?>" /> seconds</td>
		    <td align="left" valign="middle" class="row-title">Exit Delay</td>
		    <td align="left" valign="middle"><input name="data-exit-delay<?php echo $row_text['id'];?>" type="text" id="data-exit-delay<?php echo $row_text['id'];?>" size="10" value="<?php echo $row_text['data-exit-delay'];?>" /> seconds</td>
		    </tr>
		  <tr>
		    <td align="left" valign="middle" class="row-title">Exit Fade</td>
		    <td align="left" valign="middle"><input name="data-exit-fade<?php echo $row_text['id'];?>" type="text" id="data-exit-fade<?php echo $row_text['id'];?>" size="10" value="<?php echo $row_text['data-exit-fade'];?>" /> (0-1)</td>
		    <td align="left" valign="middle"><span class="row-title">Exit Easing</span></td>
		    <td align="left" valign="middle"><select name="data-exit-easing<?php echo $row_text['id'];?>" id="data-exit-easing<?php echo $row_text['id'];?>">
              <option value="swing" <?php echo (($row_text['data-exit-easing']=='swing')?'selected="selected"':'')?>>swing</option>
              <option value="linear" <?php echo (($row_text['data-exit-easing']=='linear')?'selected="selected"':'')?>>linear</option>
              <option value="ease" <?php echo (($row_text['data-exit-easing']=='ease')?'selected="selected"':'')?>>ease</option> 
              <option value="ease-in" <?php echo (($row_text['data-exit-easing']=='ease-in')?'selected="selected"':'')?>>ease-in</option> 
              <option value="ease-out" <?php echo (($row_text['data-exit-easing']=='ease-out')?'selected="selected"':'')?>>ease-out</option> 
              <option value="ease-in-out" <?php echo (($row_text['data-exit-easing']=='ease-in-out')?'selected="selected"':'')?>>ease-in-out</option>             
		      <option value="easeInQuad" <?php echo (($row_text['data-exit-easing']=='easeInQuad')?'selected="selected"':'')?>>easeInQuad</option>
		      <option value="easeOutQuad" <?php echo (($row_text['data-exit-easing']=='easeOutQuad')?'selected="selected"':'')?>>easeOutQuad</option>
              <option value="easeInOutQuad" <?php echo (($row_text['data-exit-easing']=='easeInOutQuad')?'selected="selected"':'')?>>easeInOutQuad</option>
              <option value="easeInCubic" <?php echo (($row_text['data-exit-easing']=='easeInCubic')?'selected="selected"':'')?>>easeInCubic</option>
              <option value="easeOutCubic" <?php echo (($row_text['data-exit-easing']=='easeOutCubic')?'selected="selected"':'')?>>easeOutCubic</option>
              <option value="easeInOutCubic" <?php echo (($row_text['data-exit-easing']=='easeInOutCubic')?'selected="selected"':'')?>>easeInOutCubic</option>              
              <option value="easeInQuart" <?php echo (($row_text['data-exit-easing']=='easeInQuart')?'selected="selected"':'')?>>easeInQuart</option>
              <option value="easeOutQuart" <?php echo (($row_text['data-exit-easing']=='easeOutQuart')?'selected="selected"':'')?>>easeOutQuart</option>
              <option value="easeInOutQuart" <?php echo (($row_text['data-exit-easing']=='easeInOutQuart')?'selected="selected"':'')?>>easeInOutQuart</option>
              <option value="easeInSine" <?php echo (($row_text['data-exit-easing']=='easeInSine')?'selected="selected"':'')?>>easeInSine</option>   
              <option value="easeOutSine" <?php echo (($row_text['data-exit-easing']=='easeOutSine')?'selected="selected"':'')?>>easeOutSine</option>
              <option value="easeInOutSine" <?php echo (($row_text['data-exit-easing']=='easeInOutSine')?'selected="selected"':'')?>>easeInOutSine</option>
              <option value="easeInExpo" <?php echo (($row_text['data-exit-easing']=='easeInExpo')?'selected="selected"':'')?>>easeInExpo</option>
              <option value="easeOutExpo" <?php echo (($row_text['data-exit-easing']=='easeOutExpo')?'selected="selected"':'')?>>easeOutExpo</option>
              <option value="easeInOutExpo" <?php echo (($row_text['data-exit-easing']=='easeInOutExpo')?'selected="selected"':'')?>>easeInOutExpo</option>
              <option value="easeInQuint" <?php echo (($row_text['data-exit-easing']=='easeInQuint')?'selected="selected"':'')?>>easeInQuint</option>
              <option value="easeOutQuint" <?php echo (($row_text['data-exit-easing']=='easeOutQuint')?'selected="selected"':'')?>>easeOutQuint</option>
              <option value="easeInOutQuint" <?php echo (($row_text['data-exit-easing']=='easeInOutQuint')?'selected="selected"':'')?>>easeInOutQuint</option>
              <option value="easeInCirc" <?php echo (($row_text['data-exit-easing']=='easeInCirc')?'selected="selected"':'')?>>easeInCirc</option>
              <option value="easeOutCirc" <?php echo (($row_text['data-exit-easing']=='easeOutCirc')?'selected="selected"':'')?>>easeOutCirc</option>
              <option value="easeInOutCirc" <?php echo (($row_text['data-exit-easing']=='easeInOutCirc')?'selected="selected"':'')?>>easeInOutCirc</option>
              <option value="easeInBack" <?php echo (($row_text['data-exit-easing']=='easeInBack')?'selected="selected"':'')?>>easeInBack</option>
              <option value="easeOutBack" <?php echo (($row_text['data-exit-easing']=='easeOutBack')?'selected="selected"':'')?>>easeOutBack</option>
              <option value="easeInOutBack" <?php echo (($row_text['data-exit-easing']=='easeInOutBack')?'selected="selected"':'')?>>easeInOutBack</option>
	        </select></td>
		    </tr>
		  <tr>
		    <td align="left" valign="middle" class="row-title">Cancel Exit</td>
		    <td align="left" valign="middle"><select name="data-exit-off<?php echo $row_text['id'];?>" id="data-exit-off<?php echo $row_text['id'];?>">
              <option value="true" <?php echo (($row_text['data-exit-off']=='true')?'selected="selected"':'')?>>true</option>
              <option value="false" <?php echo (($row_text['data-exit-off']=='false')?'selected="selected"':'')?>>false</option>
            </select></td>
		    <td align="left" valign="middle">&nbsp;</td>
		    <td align="left" valign="middle">&nbsp;</td>
		   </tr>
		  <tr>
		    <td align="left" valign="middle">&nbsp;</td>
		    <td align="left" valign="middle">&nbsp;</td>
		    <td align="left" valign="middle">&nbsp;</td>
		    <td align="left" valign="middle">&nbsp;</td>
		    </tr>
<tr>
		    <td colspan="4" align="left" valign="middle" class="lbg_regGrayWithBg">Intermediate Move Parameters</td>
		    </tr>
		  <tr>
		    <td align="left" valign="middle" class="row-title">Intermediate Left</td>
		    <td align="left" valign="middle"><input name="data-intermediate-left<?php echo $row_text['id'];?>" type="text" id="data-intermediate-left<?php echo $row_text['id'];?>" size="10" value="<?php echo $row_text['data-intermediate-left'];?>" /> px</td>
		    <td align="left" valign="middle" class="row-title">Intermediate Top</td>
		    <td align="left" valign="middle"><input name="data-intermediate-top<?php echo $row_text['id'];?>" type="text" id="data-intermediate-top<?php echo $row_text['id'];?>" size="10" value="<?php echo $row_text['data-intermediate-top'];?>" /> px</td>
		  </tr>
          <tr>
            <td align="left" valign="middle" class="row-title">Intermediate Skew</td>
            <td align="left" valign="middle"><input name="data-intermediate-skew<?php echo $row_text['id'];?>" type="text" id="data-intermediate-skew<?php echo $row_text['id'];?>" size="20" value="<?php echo $row_text['data-intermediate-skew'];?>" />
              <br />
              (Ex: 50deg,0deg)</td>
            <td align="left" valign="middle" class="row-title">Intermediate Scale</td>
            <td align="left" valign="middle"><input name="data-intermediate-scale<?php echo $row_text['id'];?>" type="text" id="data-intermediate-scale<?php echo $row_text['id'];?>" size="20" value="<?php echo $row_text['data-intermediate-scale'];?>" />
              (0-100)</td>
            </tr>
          <tr>
		    <td align="left" valign="middle" class="row-title">Intermediate Duration</td>
		    <td align="left" valign="middle"><input name="data-intermediate-duration<?php echo $row_text['id'];?>" type="text" id="data-intermediate-duration<?php echo $row_text['id'];?>" size="10" value="<?php echo $row_text['data-intermediate-duration'];?>" />
		    seconds </td>
		    <td align="left" valign="middle" class="row-title">Intermediate Easing</td>
		    <td align="left" valign="middle"><select name="data-intermediate-easing<?php echo $row_text['id'];?>" id="data-intermediate-easing<?php echo $row_text['id'];?>">
              <option value="swing" <?php echo (($row_text['data-intermediate-easing']=='swing')?'selected="selected"':'')?>>swing</option>
              <option value="linear" <?php echo (($row_text['data-intermediate-easing']=='linear')?'selected="selected"':'')?>>linear</option>
              <option value="ease" <?php echo (($row_text['data-intermediate-easing']=='ease')?'selected="selected"':'')?>>ease</option> 
              <option value="ease-in" <?php echo (($row_text['data-intermediate-easing']=='ease-in')?'selected="selected"':'')?>>ease-in</option> 
              <option value="ease-out" <?php echo (($row_text['data-intermediate-easing']=='ease-out')?'selected="selected"':'')?>>ease-out</option> 
              <option value="ease-in-out" <?php echo (($row_text['data-intermediate-easing']=='ease-in-out')?'selected="selected"':'')?>>ease-in-out</option>            
		      <option value="easeInQuad" <?php echo (($row_text['data-intermediate-easing']=='easeInQuad')?'selected="selected"':'')?>>easeInQuad</option>
		      <option value="easeOutQuad" <?php echo (($row_text['data-intermediate-easing']=='easeOutQuad')?'selected="selected"':'')?>>easeOutQuad</option>
              <option value="easeInOutQuad" <?php echo (($row_text['data-intermediate-easing']=='easeInOutQuad')?'selected="selected"':'')?>>easeInOutQuad</option>
              <option value="easeInCubic" <?php echo (($row_text['data-intermediate-easing']=='easeInCubic')?'selected="selected"':'')?>>easeInCubic</option>
              <option value="easeOutCubic" <?php echo (($row_text['data-intermediate-easing']=='easeOutCubic')?'selected="selected"':'')?>>easeOutCubic</option>
              <option value="easeInOutCubic" <?php echo (($row_text['data-intermediate-easing']=='easeInOutCubic')?'selected="selected"':'')?>>easeInOutCubic</option>              
              <option value="easeInQuart" <?php echo (($row_text['data-intermediate-easing']=='easeInQuart')?'selected="selected"':'')?>>easeInQuart</option>
              <option value="easeOutQuart" <?php echo (($row_text['data-intermediate-easing']=='easeOutQuart')?'selected="selected"':'')?>>easeOutQuart</option>
              <option value="easeInOutQuart" <?php echo (($row_text['data-intermediate-easing']=='easeInOutQuart')?'selected="selected"':'')?>>easeInOutQuart</option>
              <option value="easeInSine" <?php echo (($row_text['data-intermediate-easing']=='easeInSine')?'selected="selected"':'')?>>easeInSine</option>   
              <option value="easeOutSine" <?php echo (($row_text['data-intermediate-easing']=='easeOutSine')?'selected="selected"':'')?>>easeOutSine</option>
              <option value="easeInOutSine" <?php echo (($row_text['data-intermediate-easing']=='easeInOutSine')?'selected="selected"':'')?>>easeInOutSine</option>
              <option value="easeInExpo" <?php echo (($row_text['data-intermediate-easing']=='easeInExpo')?'selected="selected"':'')?>>easeInExpo</option>
              <option value="easeOutExpo" <?php echo (($row_text['data-intermediate-easing']=='easeOutExpo')?'selected="selected"':'')?>>easeOutExpo</option>
              <option value="easeInOutExpo" <?php echo (($row_text['data-intermediate-easing']=='easeInOutExpo')?'selected="selected"':'')?>>easeInOutExpo</option>
              <option value="easeInQuint" <?php echo (($row_text['data-intermediate-easing']=='easeInQuint')?'selected="selected"':'')?>>easeInQuint</option>
              <option value="easeOutQuint" <?php echo (($row_text['data-intermediate-easing']=='easeOutQuint')?'selected="selected"':'')?>>easeOutQuint</option>
              <option value="easeInOutQuint" <?php echo (($row_text['data-intermediate-easing']=='easeInOutQuint')?'selected="selected"':'')?>>easeInOutQuint</option>
              <option value="easeInCirc" <?php echo (($row_text['data-intermediate-easing']=='easeInCirc')?'selected="selected"':'')?>>easeInCirc</option>
              <option value="easeOutCirc" <?php echo (($row_text['data-intermediate-easing']=='easeOutCirc')?'selected="selected"':'')?>>easeOutCirc</option>
              <option value="easeInOutCirc" <?php echo (($row_text['data-intermediate-easing']=='easeInOutCirc')?'selected="selected"':'')?>>easeInOutCirc</option>
              <option value="easeInBack" <?php echo (($row_text['data-intermediate-easing']=='easeInBack')?'selected="selected"':'')?>>easeInBack</option>
              <option value="easeOutBack" <?php echo (($row_text['data-intermediate-easing']=='easeOutBack')?'selected="selected"':'')?>>easeOutBack</option>
              <option value="easeInOutBack" <?php echo (($row_text['data-intermediate-easing']=='easeInOutBack')?'selected="selected"':'')?>>easeInOutBack</option>
	        </select></td>
		  </tr>           
		  <tr>
		    <td align="left" valign="middle" class="row-title">Intermediate Delay</td>
		    <td align="left" valign="middle"><input name="data-intermediate-delay<?php echo $row_text['id'];?>" type="text" id="data-intermediate-delay<?php echo $row_text['id'];?>" size="10" value="<?php echo $row_text['data-intermediate-delay'];?>" />
		      seconds</td>
		    <td align="left" valign="middle">&nbsp;</td>
		    <td align="left" valign="middle">&nbsp;</td>
		    </tr>
		  <tr>
		    <td align="left" valign="middle">&nbsp;</td>
		    <td align="left" valign="middle">&nbsp;</td>
		    <td align="left" valign="middle">&nbsp;</td>
		    <td align="left" valign="middle">&nbsp;</td>
		    </tr>
		  <tr>
		    <td align="left" valign="middle">&nbsp;</td>
		    <td align="left" valign="middle">&nbsp;</td>
		    <td align="left" valign="middle">&nbsp;</td>
		    <td align="left" valign="middle">&nbsp;</td>
		    </tr>
            
		</table>
       </div>   
       
       <?php } ?>   
     
        </td>
    <td width="50%" align="left" valign="top">
    	<ul id="lbg_zoominoutslider_layers_sortable">
        <?php foreach ( $result_text as $row_text ) { ?>
        	<li class="ui-state-default cursor_move" id="<?php echo $row_text['id']?>" data-photoid="<?php echo $row['id']?>" onclick="activate_layer(<?php echo $row_text['id']?>);"><div id="li_div<?php echo $row_text['id']?>"><?php echo strip_tags(substr($row_text['content'],0,50)).((strip_tags(strlen($row_text['content'])>50))?'...':'');?></div> <input name="ord_input_<?php echo $row_text['id']?>" type="text" disabled="disabled" id="ord_input_<?php echo $row_text['id']?>" style="float:right; margin-top:-20px;" value="<?php echo $row_text['ord']?>" size="3" readonly="readonly" /></li>
        <?php } ?>    
        </ul>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
    </tr>
    <tr>
    <td colspan="2" align="center"><input name="Submit" id="Submit" type="submit" class="button-primary" value="Save All Changes"></td>
    </tr>
    <tr>
    <td colspan="2" align="center">&nbsp;</td>
    </tr>  
    <tr>
    <td colspan="2" align="center">&nbsp;</td>
    </tr>  
    <tr>
    <td colspan="2" align="center">&nbsp;</td>
    </tr>              
    </tbody>
</table>
</form>
</div>

<div style="text-align:center; padding:100px 0px 20px 0px;"><img src="<?php echo plugins_url('images/icons/back_icon.gif', dirname(__FILE__))?>" alt="back" align="absmiddle" /> <a href="?page=lbg_zoominoutslider_Playlist&amp;id=<?php echo $row['id']?>">Back to Playlist</a></div>




</div>				