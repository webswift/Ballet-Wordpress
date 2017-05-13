<div class="wrap">
	<div id="lbg_logo">
			<h2>Banner Settings for banner: <span style="color:#FF0000; font-weight:bold;"><?php echo $_SESSION['xname']?> - ID #<?php echo $_SESSION['xid']?></span></h2>
 	</div>

    <div style="text-align:center; padding:0px 0px 20px 0px;"><img src="<?php echo plugins_url('images/icons/magnifier.png', dirname(__FILE__))?>" alt="add" align="absmiddle" /> <a href="javascript: void(0);" onclick="showDialogPreview(<?php echo strip_tags($_SESSION['xid'])?>)">Preview Slider</a></div>    
    
  <form method="POST" enctype="multipart/form-data">
	<script>
	jQuery(function() {
		var icons = {
			header: "ui-icon-circle-arrow-e",
			headerSelected: "ui-icon-circle-arrow-s"
		};
		jQuery( "#accordion" ).accordion({
			icons: icons,
			autoHeight: false
		});
	});
	</script>

<div id="previewDialog"><iframe id="previewDialogIframe" src="" width="100%" height="600" style="border:0;"></iframe></div>

<div id="accordion">
  <h3><a href="#">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;General Settings</a></h3>
  <div style="padding:30px;">
	  <table class="wp-list-table widefat fixed pages" cellspacing="0">
     
		  <tr>
		    <td align="right" valign="top" class="row-title" width="30%">Banner Name</td>
		    <td align="left" valign="top" width="75%"><input name="name" type="text" size="40" id="name" value="<?php echo $_SESSION['xname'];?>"/></td>
	      </tr>
		  <tr>
            <td width="30%" align="right" valign="top" class="row-title">Banner Width</td>
		    <td width="75%" align="left" valign="top"><input name="width" type="text" size="25" id="width" value="<?php echo $_POST['width'];?>"/> 
		      px</td>
	    </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Banner Height</td>
		    <td align="left" valign="top"><input name="height" type="text" size="25" id="height" value="<?php echo $_POST['height'];?>"/> 
		      px</td>
	    </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Set as Background</td>
		    <td align="left" valign="middle"><select name="setAsBg" id="setAsBg">
              <option value="true" <?php echo (($_POST['setAsBg']=='true')?'selected="selected"':'')?>>true</option>
              <option value="false" <?php echo (($_POST['setAsBg']=='false')?'selected="selected"':'')?>>false</option>
            </select></td>
	    </tr>         
		<tr>
		    <td align="right" valign="top" class="row-title">Width 100%</td>
		    <td align="left" valign="middle"><select name="width100Proc" id="width100Proc">
              <option value="true" <?php echo (($_POST['width100Proc']=='true')?'selected="selected"':'')?>>true</option>
              <option value="false" <?php echo (($_POST['width100Proc']=='false')?'selected="selected"':'')?>>false</option>
            </select></td>
	    </tr>
		<tr>
		    <td align="right" valign="top" class="row-title">Height 100%</td>
		    <td align="left" valign="middle"><select name="height100Proc" id="height100Proc">
              <option value="true" <?php echo (($_POST['height100Proc']=='true')?'selected="selected"':'')?>>true</option>
              <option value="false" <?php echo (($_POST['height100Proc']=='false')?'selected="selected"':'')?>>false</option>
            </select></td>
	    </tr>        
        <tr>
		    <td align="right" valign="top" class="row-title">Responsive</td>
		    <td align="left" valign="middle"><select name="responsive" id="responsive">
              <option value="true" <?php echo (($_POST['responsive']=='true')?'selected="selected"':'')?>>true</option>
              <option value="false" <?php echo (($_POST['responsive']=='false')?'selected="selected"':'')?>>false</option>
            </select></td>
	    </tr>
        <tr>
		    <td align="right" valign="top" class="row-title">Responsive Relative To Browser</td>
		    <td align="left" valign="middle"><select name="responsiveRelativeToBrowser" id="responsiveRelativeToBrowser">
              <option value="true" <?php echo (($_POST['responsiveRelativeToBrowser']=='true')?'selected="selected"':'')?>>true</option>
              <option value="false" <?php echo (($_POST['responsiveRelativeToBrowser']=='false')?'selected="selected"':'')?>>false</option>
            </select></td>
	    </tr>        
		  <tr>
		    <td align="right" valign="top" class="row-title">Skin Name</td>
		    <td align="left" valign="middle"><select name="skin" id="skin">
		      <option value="opportune" <?php echo (($_POST['skin']=='opportune')?'selected="selected"':'')?>>opportune</option>
		      <option value="majestic" <?php echo (($_POST['skin']=='majestic')?'selected="selected"':'')?>>majestic</option>
		      <option value="generous" <?php echo (($_POST['skin']=='generous')?'selected="selected"':'')?>>generous</option>
            </select></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Auto Play</td>
		    <td align="left" valign="middle"><input name="autoPlay" type="text" size="25" id="autoPlay" value="<?php echo $_POST['autoPlay'];?>"/>	          seconds</td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Loop</td>
		    <td align="left" valign="middle"><select name="loop" id="loop">
              <option value="true" <?php echo (($_POST['loop']=='true')?'selected="selected"':'')?>>true</option>
              <option value="false" <?php echo (($_POST['loop']=='false')?'selected="selected"':'')?>>false</option>
            </select></td>
	    </tr>

		  <tr>
		    <td align="right" valign="top" class="row-title">Fade Slides</td>
		    <td align="left" valign="middle"><select name="fadeSlides" id="fadeSlides">
		      <option value="true" <?php echo (($_POST['fadeSlides']=='true')?'selected="selected"':'')?>>true</option>
		      <option value="false" <?php echo (($_POST['fadeSlides']=='false')?'selected="selected"':'')?>>false</option>
		      </select></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Scroll Slide Duration</td>
		    <td align="left" valign="middle"><input name="scrollSlideDuration" type="text" size="15" id="scrollSlideDuration" value="<?php echo strip_tags($_POST['scrollSlideDuration']);?>"/> seconds</td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Scroll Slide Easing</td>
		    <td align="left" valign="middle"><select name="scrollSlideEasing" id="scrollSlideEasing">
              <option value="swing" <?php echo (($_POST['scrollSlideEasing']=='swing')?'selected="selected"':'')?>>swing</option>
              <option value="linear" <?php echo (($_POST['scrollSlideEasing']=='linear')?'selected="selected"':'')?>>linear</option>
		      <option value="easeInQuad" <?php echo (($_POST['scrollSlideEasing']=='easeInQuad')?'selected="selected"':'')?>>easeInQuad</option>
		      <option value="easeOutQuad" <?php echo (($_POST['scrollSlideEasing']=='easeOutQuad')?'selected="selected"':'')?>>easeOutQuad</option>
              <option value="easeInOutQuad" <?php echo (($_POST['scrollSlideEasing']=='easeInOutQuad')?'selected="selected"':'')?>>easeInOutQuad</option>
              <option value="easeInCubic" <?php echo (($_POST['scrollSlideEasing']=='easeInCubic')?'selected="selected"':'')?>>easeInCubic</option>
              <option value="easeOutCubic" <?php echo (($_POST['scrollSlideEasing']=='easeOutCubic')?'selected="selected"':'')?>>easeOutCubic</option>
              <option value="easeInOutCubic" <?php echo (($_POST['scrollSlideEasing']=='easeInOutCubic')?'selected="selected"':'')?>>easeInOutCubic</option>              
              <option value="easeInQuart" <?php echo (($_POST['scrollSlideEasing']=='easeInQuart')?'selected="selected"':'')?>>easeInQuart</option>
              <option value="easeOutQuart" <?php echo (($_POST['scrollSlideEasing']=='easeOutQuart')?'selected="selected"':'')?>>easeOutQuart</option>
              <option value="easeInOutQuart" <?php echo (($_POST['scrollSlideEasing']=='easeInOutQuart')?'selected="selected"':'')?>>easeInOutQuart</option>
              <option value="easeInSine" <?php echo (($_POST['scrollSlideEasing']=='easeInSine')?'selected="selected"':'')?>>easeInSine</option>   
              <option value="easeOutSine" <?php echo (($_POST['scrollSlideEasing']=='easeOutSine')?'selected="selected"':'')?>>easeOutSine</option>
              <option value="easeInOutSine" <?php echo (($_POST['scrollSlideEasing']=='easeInOutSine')?'selected="selected"':'')?>>easeInOutSine</option>
              <option value="easeInExpo" <?php echo (($_POST['scrollSlideEasing']=='easeInExpo')?'selected="selected"':'')?>>easeInExpo</option>
              <option value="easeOutExpo" <?php echo (($_POST['scrollSlideEasing']=='easeOutExpo')?'selected="selected"':'')?>>easeOutExpo</option>
              <option value="easeInOutExpo" <?php echo (($_POST['scrollSlideEasing']=='easeInOutExpo')?'selected="selected"':'')?>>easeInOutExpo</option>
              <option value="easeInQuint" <?php echo (($_POST['scrollSlideEasing']=='easeInQuint')?'selected="selected"':'')?>>easeInQuint</option>
              <option value="easeOutQuint" <?php echo (($_POST['scrollSlideEasing']=='easeOutQuint')?'selected="selected"':'')?>>easeOutQuint</option>
              <option value="easeInOutQuint" <?php echo (($_POST['scrollSlideEasing']=='easeInOutQuint')?'selected="selected"':'')?>>easeInOutQuint</option>
              <option value="easeInCirc" <?php echo (($_POST['scrollSlideEasing']=='easeInCirc')?'selected="selected"':'')?>>easeInCirc</option>
              <option value="easeOutCirc" <?php echo (($_POST['scrollSlideEasing']=='easeOutCirc')?'selected="selected"':'')?>>easeOutCirc</option>
              <option value="easeInOutCirc" <?php echo (($_POST['scrollSlideEasing']=='easeInOutCirc')?'selected="selected"':'')?>>easeInOutCirc</option>
              <option value="easeInElastic" <?php echo (($_POST['scrollSlideEasing']=='easeInElastic')?'selected="selected"':'')?>>easeInElastic</option>
              <option value="easeOutElastic" <?php echo (($_POST['scrollSlideEasing']=='easeOutElastic')?'selected="selected"':'')?>>easeOutElastic</option>
              <option value="easeInOutElastic" <?php echo (($_POST['scrollSlideEasing']=='easeInOutElastic')?'selected="selected"':'')?>>easeInOutElastic</option>
              <option value="easeInBack" <?php echo (($_POST['scrollSlideEasing']=='easeInBack')?'selected="selected"':'')?>>easeInBack</option>
              <option value="easeOutBack" <?php echo (($_POST['scrollSlideEasing']=='easeOutBack')?'selected="selected"':'')?>>easeOutBack</option>
              <option value="easeInOutBack" <?php echo (($_POST['scrollSlideEasing']=='easeInOutBack')?'selected="selected"':'')?>>easeInOutBack</option>
              <option value="easeInBounce" <?php echo (($_POST['scrollSlideEasing']=='easeInBounce')?'selected="selected"':'')?>>easeInBounce</option>
              <option value="easeOutBounce" <?php echo (($_POST['scrollSlideEasing']=='easeOutBounce')?'selected="selected"':'')?>>easeOutBounce</option>
              <option value="easeInOutBounce" <?php echo (($_POST['scrollSlideEasing']=='easeInOutBounce')?'selected="selected"':'')?>>easeInOutBounce</option>
	        </select></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Pause On Mouse Over</td>
		    <td align="left" valign="middle"><select name="pauseOnMouseOver" id="pauseOnMouseOver">
		      <option value="true" <?php echo (($_POST['pauseOnMouseOver']=='true')?'selected="selected"':'')?>>true</option>
		      <option value="false" <?php echo (($_POST['pauseOnMouseOver']=='false')?'selected="selected"':'')?>>false</option>
	        </select></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Preload Time</td>
		    <td align="left" valign="middle"><input name="myloaderTime" type="text" size="15" id="myloaderTime" value="<?php echo strip_tags($_POST['myloaderTime']);?>"/> 
		      seconds</td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Default Easing <br />
	        for texts/elements over main image</td>
		    <td align="left" valign="middle"><select name="defaultEasing" id="defaultEasing">
              <option value="swing" <?php echo (($_POST['defaultEasing']=='swing')?'selected="selected"':'')?>>swing</option>
              <option value="linear" <?php echo (($_POST['defaultEasing']=='linear')?'selected="selected"':'')?>>linear</option>
              <option value="ease" <?php echo (($_POST['defaultEasing']=='ease')?'selected="selected"':'')?>>ease</option> 
              <option value="ease-in" <?php echo (($_POST['defaultEasing']=='ease-in')?'selected="selected"':'')?>>ease-in</option> 
              <option value="ease-out" <?php echo (($_POST['defaultEasing']=='ease-out')?'selected="selected"':'')?>>ease-out</option> 
              <option value="ease-in-out" <?php echo (($_POST['defaultEasing']=='ease-in-out')?'selected="selected"':'')?>>ease-in-out</option>  
		      <option value="easeInQuad" <?php echo (($_POST['defaultEasing']=='easeInQuad')?'selected="selected"':'')?>>easeInQuad</option>
		      <option value="easeOutQuad" <?php echo (($_POST['defaultEasing']=='easeOutQuad')?'selected="selected"':'')?>>easeOutQuad</option>
              <option value="easeInOutQuad" <?php echo (($_POST['defaultEasing']=='easeInOutQuad')?'selected="selected"':'')?>>easeInOutQuad</option>
              <option value="easeInCubic" <?php echo (($_POST['defaultEasing']=='easeInCubic')?'selected="selected"':'')?>>easeInCubic</option>
              <option value="easeOutCubic" <?php echo (($_POST['defaultEasing']=='easeOutCubic')?'selected="selected"':'')?>>easeOutCubic</option>
              <option value="easeInOutCubic" <?php echo (($_POST['defaultEasing']=='easeInOutCubic')?'selected="selected"':'')?>>easeInOutCubic</option>              
              <option value="easeInQuart" <?php echo (($_POST['defaultEasing']=='easeInQuart')?'selected="selected"':'')?>>easeInQuart</option>
              <option value="easeOutQuart" <?php echo (($_POST['defaultEasing']=='easeOutQuart')?'selected="selected"':'')?>>easeOutQuart</option>
              <option value="easeInOutQuart" <?php echo (($_POST['defaultEasing']=='easeInOutQuart')?'selected="selected"':'')?>>easeInOutQuart</option>
              <option value="easeInSine" <?php echo (($_POST['defaultEasing']=='easeInSine')?'selected="selected"':'')?>>easeInSine</option>   
              <option value="easeOutSine" <?php echo (($_POST['defaultEasing']=='easeOutSine')?'selected="selected"':'')?>>easeOutSine</option>
              <option value="easeInOutSine" <?php echo (($_POST['defaultEasing']=='easeInOutSine')?'selected="selected"':'')?>>easeInOutSine</option>
              <option value="easeInExpo" <?php echo (($_POST['defaultEasing']=='easeInExpo')?'selected="selected"':'')?>>easeInExpo</option>
              <option value="easeOutExpo" <?php echo (($_POST['defaultEasing']=='easeOutExpo')?'selected="selected"':'')?>>easeOutExpo</option>
              <option value="easeInOutExpo" <?php echo (($_POST['defaultEasing']=='easeInOutExpo')?'selected="selected"':'')?>>easeInOutExpo</option>
              <option value="easeInQuint" <?php echo (($_POST['defaultEasing']=='easeInQuint')?'selected="selected"':'')?>>easeInQuint</option>
              <option value="easeOutQuint" <?php echo (($_POST['defaultEasing']=='easeOutQuint')?'selected="selected"':'')?>>easeOutQuint</option>
              <option value="easeInOutQuint" <?php echo (($_POST['defaultEasing']=='easeInOutQuint')?'selected="selected"':'')?>>easeInOutQuint</option>
              <option value="easeInCirc" <?php echo (($_POST['defaultEasing']=='easeInCirc')?'selected="selected"':'')?>>easeInCirc</option>
              <option value="easeOutCirc" <?php echo (($_POST['defaultEasing']=='easeOutCirc')?'selected="selected"':'')?>>easeOutCirc</option>
              <option value="easeInOutCirc" <?php echo (($_POST['defaultEasing']=='easeInOutCirc')?'selected="selected"':'')?>>easeInOutCirc</option>
	        </select></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Target Window For Link</td>
		    <td align="left" valign="middle"><select name="target" id="target">
		      <option value="_blank" <?php echo (($_POST['target']=='_blank')?'selected="selected"':'')?>>_blank</option>
		      <option value="_self" <?php echo (($_POST['target']=='_self')?'selected="selected"':'')?>>_self</option>
            </select></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Enable Touch Screen</td>
		    <td align="left" valign="middle"><select name="enableTouchScreen" id="enableTouchScreen">
              <option value="true" <?php echo (($_POST['enableTouchScreen']=='true')?'selected="selected"':'')?>>true</option>
              <option value="false" <?php echo (($_POST['enableTouchScreen']=='false')?'selected="selected"':'')?>>false</option>
            </select></td>
	    </tr>        

        
      </table>
  </div>
<h3><a href="#">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Zoom In/Out Effect Default Settings</a></h3>
  <div style="padding:30px;">
	  <table class="wp-list-table widefat fixed pages" cellspacing="0">
		<tr>
		    <td align="right" valign="top" class="row-title" width="30%">Default Horizontal Position</td>
		    <td align="left" valign="middle" width="70%"><select name="horizontalPosition" id="horizontalPosition">
              <option value="left" <?php echo (($_POST['horizontalPosition']=='left')?'selected="selected"':'')?>>left</option>
              <option value="center" <?php echo (($_POST['horizontalPosition']=='center')?'selected="selected"':'')?>>center</option>
              <option value="right" <?php echo (($_POST['horizontalPosition']=='right')?'selected="selected"':'')?>>right</option>
            </select></td>
	    </tr>      
		<tr>
		    <td align="right" valign="top" class="row-title" width="30%">Default Vertical Position</td>
		    <td align="left" valign="middle" width="70%"><select name="verticalPosition" id="verticalPosition">
              <option value="top" <?php echo (($_POST['verticalPosition']=='top')?'selected="selected"':'')?>>top</option>
              <option value="center" <?php echo (($_POST['verticalPosition']=='center')?'selected="selected"':'')?>>center</option>
              <option value="bottom" <?php echo (($_POST['verticalPosition']=='bottom')?'selected="selected"':'')?>>bottom</option>
            </select></td>
	    </tr> 
		<tr>
		  <td align="right" valign="top" class="row-title">Default Initial Zoom</td>
		  <td align="left" valign="middle"><input name="initialZoom" type="text" size="25" id="initialZoom" value="<?php echo $_POST['initialZoom'];?>"/>
		    (<i>We recommend values between 0.5 - 1</i>)</td>
		  </tr>
		<tr>
		  <td align="right" valign="top" class="row-title">Default Final Zoom</td>
		  <td align="left" valign="middle"><input name="finalZoom" type="text" size="25" id="finalZoom" value="<?php echo $_POST['finalZoom'];?>"/>
		    (<i>We recommend values between 0.5 - 1</i>)</td>
		  </tr>
		<tr>
		  <td align="right" valign="top" class="row-title">Default Zoom In/Out Effect Duration</td>
		  <td align="left" valign="middle"><input name="duration" type="text" size="25" id="duration" value="<?php echo $_POST['duration'];?>"/> 
		    <i>seconds</i></td>
		  </tr>

		<tr>
		  <td align="right" valign="top" class="row-title">Zoom In/Out Effect IE Duration Fix</td>
		  <td align="left" valign="middle"><input name="durationIEfix" type="text" size="25" id="durationIEfix" value="<?php echo $_POST['durationIEfix'];?>"/>
		    <i>seconds</i></td>
		  </tr>
		<tr>
		  <td align="right" valign="top" class="row-title">&nbsp;</td>
		  <td align="left" valign="middle"><i>this value will be added to the 'Default Zoom In/Out Effect Duration' value for IE browsers if Width 100%=true.   This is done because IE doesn't support css3 transitions and IE rendering engine is very poor. For IE browsers, if you have full width or   full screen banners you need to make the move slower.</i></td>
		  </tr>
		<tr>
		  <td align="right" valign="top" class="row-title">Default Zoom Effect Easing</td>
		  <td align="left" valign="middle"><select name="zoomEasing" id="zoomEasing">
              <option value="swing" <?php echo (($_POST['zoomEasing']=='swing')?'selected="selected"':'')?>>swing</option>
              <option value="linear" <?php echo (($_POST['zoomEasing']=='linear')?'selected="selected"':'')?>>linear</option>
              <option value="ease" <?php echo (($_POST['zoomEasing']=='ease')?'selected="selected"':'')?>>ease</option> 
              <option value="ease-in" <?php echo (($_POST['zoomEasing']=='ease-in')?'selected="selected"':'')?>>ease-in</option> 
              <option value="ease-out" <?php echo (($_POST['zoomEasing']=='ease-out')?'selected="selected"':'')?>>ease-out</option> 
              <option value="ease-in-out" <?php echo (($_POST['zoomEasing']=='ease-in-out')?'selected="selected"':'')?>>ease-in-out</option>  
		      <option value="easeInQuad" <?php echo (($_POST['zoomEasing']=='easeInQuad')?'selected="selected"':'')?>>easeInQuad</option>
		      <option value="easeOutQuad" <?php echo (($_POST['zoomEasing']=='easeOutQuad')?'selected="selected"':'')?>>easeOutQuad</option>
              <option value="easeInOutQuad" <?php echo (($_POST['zoomEasing']=='easeInOutQuad')?'selected="selected"':'')?>>easeInOutQuad</option>
              <option value="easeInCubic" <?php echo (($_POST['zoomEasing']=='easeInCubic')?'selected="selected"':'')?>>easeInCubic</option>
              <option value="easeOutCubic" <?php echo (($_POST['zoomEasing']=='easeOutCubic')?'selected="selected"':'')?>>easeOutCubic</option>
              <option value="easeInOutCubic" <?php echo (($_POST['zoomEasing']=='easeInOutCubic')?'selected="selected"':'')?>>easeInOutCubic</option>              
              <option value="easeInQuart" <?php echo (($_POST['zoomEasing']=='easeInQuart')?'selected="selected"':'')?>>easeInQuart</option>
              <option value="easeOutQuart" <?php echo (($_POST['zoomEasing']=='easeOutQuart')?'selected="selected"':'')?>>easeOutQuart</option>
              <option value="easeInOutQuart" <?php echo (($_POST['zoomEasing']=='easeInOutQuart')?'selected="selected"':'')?>>easeInOutQuart</option>
              <option value="easeInSine" <?php echo (($_POST['zoomEasing']=='easeInSine')?'selected="selected"':'')?>>easeInSine</option>   
              <option value="easeOutSine" <?php echo (($_POST['zoomEasing']=='easeOutSine')?'selected="selected"':'')?>>easeOutSine</option>
              <option value="easeInOutSine" <?php echo (($_POST['zoomEasing']=='easeInOutSine')?'selected="selected"':'')?>>easeInOutSine</option>
              <option value="easeInExpo" <?php echo (($_POST['zoomEasing']=='easeInExpo')?'selected="selected"':'')?>>easeInExpo</option>
              <option value="easeOutExpo" <?php echo (($_POST['zoomEasing']=='easeOutExpo')?'selected="selected"':'')?>>easeOutExpo</option>
              <option value="easeInOutExpo" <?php echo (($_POST['zoomEasing']=='easeInOutExpo')?'selected="selected"':'')?>>easeInOutExpo</option>
              <option value="easeInQuint" <?php echo (($_POST['zoomEasing']=='easeInQuint')?'selected="selected"':'')?>>easeInQuint</option>
              <option value="easeOutQuint" <?php echo (($_POST['zoomEasing']=='easeOutQuint')?'selected="selected"':'')?>>easeOutQuint</option>
              <option value="easeInOutQuint" <?php echo (($_POST['zoomEasing']=='easeInOutQuint')?'selected="selected"':'')?>>easeInOutQuint</option>
              <option value="easeInCirc" <?php echo (($_POST['zoomEasing']=='easeInCirc')?'selected="selected"':'')?>>easeInCirc</option>
              <option value="easeOutCirc" <?php echo (($_POST['zoomEasing']=='easeOutCirc')?'selected="selected"':'')?>>easeOutCirc</option>
              <option value="easeInOutCirc" <?php echo (($_POST['zoomEasing']=='easeInOutCirc')?'selected="selected"':'')?>>easeInOutCirc</option>
	        </select></td>
		  </tr>
		<tr>
		  <td align="right" valign="top" class="row-title">&nbsp;</td>
		  <td align="left" valign="middle">&nbsp;</td>
		  </tr>      
      </table>
  </div>

  <h3><a href="#">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Layers Default Exit Animation Values</a></h3>
  <div style="padding:30px;">
	  <table class="wp-list-table widefat fixed pages" cellspacing="0">
		  <tr>
		    <td align="right" valign="top" class="row-title" width="30%">Default Exit Left Position</td>
		    <td align="left" valign="top" width="70%"><input name="defaultExitLeft" type="text" size="25" id="defaultExitLeft" value="<?php echo strip_tags($_POST['defaultExitLeft']);?>"/> px</td>
	      </tr>       
		  <tr>
		    <td align="right" valign="top" class="row-title">Default Exit Top Position</td>
		    <td align="left" valign="top"><input name="defaultExitTop" type="text" size="25" id="defaultExitTop" value="<?php echo strip_tags($_POST['defaultExitTop']);?>"/> px</td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Default Exit Fade</td>
		    <td align="left" valign="top"><input name="defaultExitFade" type="text" size="25" id="defaultExitFade" value="<?php echo strip_tags($_POST['defaultExitFade']);?>"/>
		      (0-1)</td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Default Exit Duration</td>
		    <td align="left" valign="top"><input name="defaultExitDuration" type="text" size="25" id="defaultExitDuration" value="<?php echo strip_tags($_POST['defaultExitDuration']);?>"/> seconds</td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Default Exit Delay</td>
		    <td align="left" valign="top"><input name="defaultExitDelay" type="text" size="25" id="defaultExitDelay" value="<?php echo strip_tags($_POST['defaultExitDelay']);?>"/> seconds</td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Default Exit Easing</td>
		    <td align="left" valign="top"><select name="defaultExitEasing" id="defaultExitEasing">
              <option value="swing" <?php echo (($_POST['defaultExitEasing']=='swing')?'selected="selected"':'')?>>swing</option>
              <option value="linear" <?php echo (($_POST['defaultExitEasing']=='linear')?'selected="selected"':'')?>>linear</option>      
              <option value="ease" <?php echo (($_POST['defaultExitEasing']=='ease')?'selected="selected"':'')?>>ease</option> 
              <option value="ease-in" <?php echo (($_POST['defaultExitEasing']=='ease-in')?'selected="selected"':'')?>>ease-in</option> 
              <option value="ease-out" <?php echo (($_POST['defaultExitEasing']=='ease-out')?'selected="selected"':'')?>>ease-out</option> 
              <option value="ease-in-out" <?php echo (($_POST['defaultExitEasing']=='ease-in-out')?'selected="selected"':'')?>>ease-in-out</option>       
		      <option value="easeInQuad" <?php echo (($_POST['defaultExitEasing']=='easeInQuad')?'selected="selected"':'')?>>easeInQuad</option>
		      <option value="easeOutQuad" <?php echo (($_POST['defaultExitEasing']=='easeOutQuad')?'selected="selected"':'')?>>easeOutQuad</option>
              <option value="easeInOutQuad" <?php echo (($_POST['defaultExitEasing']=='easeInOutQuad')?'selected="selected"':'')?>>easeInOutQuad</option>
              <option value="easeInCubic" <?php echo (($_POST['defaultExitEasing']=='easeInCubic')?'selected="selected"':'')?>>easeInCubic</option>
              <option value="easeOutCubic" <?php echo (($_POST['defaultExitEasing']=='easeOutCubic')?'selected="selected"':'')?>>easeOutCubic</option>
              <option value="easeInOutCubic" <?php echo (($_POST['defaultExitEasing']=='easeInOutCubic')?'selected="selected"':'')?>>easeInOutCubic</option>              
              <option value="easeInQuart" <?php echo (($_POST['defaultExitEasing']=='easeInQuart')?'selected="selected"':'')?>>easeInQuart</option>
              <option value="easeOutQuart" <?php echo (($_POST['defaultExitEasing']=='easeOutQuart')?'selected="selected"':'')?>>easeOutQuart</option>
              <option value="easeInOutQuart" <?php echo (($_POST['defaultExitEasing']=='easeInOutQuart')?'selected="selected"':'')?>>easeInOutQuart</option>
              <option value="easeInSine" <?php echo (($_POST['defaultExitEasing']=='easeInSine')?'selected="selected"':'')?>>easeInSine</option>   
              <option value="easeOutSine" <?php echo (($_POST['defaultExitEasing']=='easeOutSine')?'selected="selected"':'')?>>easeOutSine</option>
              <option value="easeInOutSine" <?php echo (($_POST['defaultExitEasing']=='easeInOutSine')?'selected="selected"':'')?>>easeInOutSine</option>
              <option value="easeInExpo" <?php echo (($_POST['defaultExitEasing']=='easeInExpo')?'selected="selected"':'')?>>easeInExpo</option>
              <option value="easeOutExpo" <?php echo (($_POST['defaultExitEasing']=='easeOutExpo')?'selected="selected"':'')?>>easeOutExpo</option>
              <option value="easeInOutExpo" <?php echo (($_POST['defaultExitEasing']=='easeInOutExpo')?'selected="selected"':'')?>>easeInOutExpo</option>
              <option value="easeInQuint" <?php echo (($_POST['defaultExitEasing']=='easeInQuint')?'selected="selected"':'')?>>easeInQuint</option>
              <option value="easeOutQuint" <?php echo (($_POST['defaultExitEasing']=='easeOutQuint')?'selected="selected"':'')?>>easeOutQuint</option>
              <option value="easeInOutQuint" <?php echo (($_POST['defaultExitEasing']=='easeInOutQuint')?'selected="selected"':'')?>>easeInOutQuint</option>
              <option value="easeInCirc" <?php echo (($_POST['defaultExitEasing']=='easeInCirc')?'selected="selected"':'')?>>easeInCirc</option>
              <option value="easeOutCirc" <?php echo (($_POST['defaultExitEasing']=='easeOutCirc')?'selected="selected"':'')?>>easeOutCirc</option>
              <option value="easeInOutCirc" <?php echo (($_POST['defaultExitEasing']=='easeInOutCirc')?'selected="selected"':'')?>>easeInOutCirc</option>
	        </select></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Disable Exit Move (default value)</td>
		    <td align="left" valign="top"><select name="defaultExitOFF" id="defaultExitOFF">
              <option value="true" <?php echo (($_POST['defaultExitOFF']=='true')?'selected="selected"':'')?>>true</option>
              <option value="false" <?php echo (($_POST['defaultExitOFF']=='false')?'selected="selected"':'')?>>false</option>
            </select></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">&nbsp;</td>
		    <td align="left" valign="top">&nbsp;</td>
	      </tr>
      </table>
  </div>          
  
  <h3><a href="#">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Controllers Settings</a></h3>
  <div style="padding:30px;">
	  <table class="wp-list-table widefat fixed pages" cellspacing="0">

		  <tr>
		    <td align="right" valign="top" class="row-title" width="30%">Show All Controllers</td>
		    <td align="left" valign="middle" width="70%"><select name="showAllControllers" id="showAllControllers">
              <option value="true" <?php echo (($_POST['showAllControllers']=='true')?'selected="selected"':'')?>>true</option>
              <option value="false" <?php echo (($_POST['showAllControllers']=='false')?'selected="selected"':'')?>>false</option>
            </select></td>
	    </tr>
        <tr>
		    <td align="right" valign="top" class="row-title">Show Navigation Arrows</td>
		    <td align="left" valign="middle"><select name="showNavArrows" id="showNavArrows">
              <option value="true" <?php echo (($_POST['showNavArrows']=='true')?'selected="selected"':'')?>>true</option>
              <option value="false" <?php echo (($_POST['showNavArrows']=='false')?'selected="selected"':'')?>>false</option>
            </select></td>
	    </tr>
         <tr>
		    <td align="right" valign="top" class="row-title">Show Navigation Arrows On Init</td>
		    <td align="left" valign="middle"><select name="showOnInitNavArrows" id="showOnInitNavArrows">
              <option value="true" <?php echo (($_POST['showOnInitNavArrows']=='true')?'selected="selected"':'')?>>true</option>
              <option value="false" <?php echo (($_POST['showOnInitNavArrows']=='false')?'selected="selected"':'')?>>false</option>
            </select></td>
	    </tr>
         <tr>
		    <td align="right" valign="top" class="row-title">Auto Hide Navigation Arrows</td>
		    <td align="left" valign="middle"><select name="autoHideNavArrows" id="autoHideNavArrows">
              <option value="true" <?php echo (($_POST['autoHideNavArrows']=='true')?'selected="selected"':'')?>>true</option>
              <option value="false" <?php echo (($_POST['autoHideNavArrows']=='false')?'selected="selected"':'')?>>false</option>
            </select></td>
	    </tr>
        <tr>
		    <td align="right" valign="top" class="row-title">Show Bottom Navigation</td>
		    <td align="left" valign="middle"><select name="showBottomNav" id="autoPlay">
              <option value="true" <?php echo (($_POST['showBottomNav']=='true')?'selected="selected"':'')?>>true</option>
              <option value="false" <?php echo (($_POST['showBottomNav']=='false')?'selected="selected"':'')?>>false</option>
            </select></td>
	    </tr>
        <tr>
		    <td align="right" valign="top" class="row-title">Show Bottom Navigation On Init</td>
		    <td align="left" valign="middle"><select name="showOnInitBottomNav" id="showOnInitBottomNav">
              <option value="true" <?php echo (($_POST['showOnInitBottomNav']=='true')?'selected="selected"':'')?>>true</option>
              <option value="false" <?php echo (($_POST['showOnInitBottomNav']=='false')?'selected="selected"':'')?>>false</option>
            </select></td>
	    </tr>
        <tr>
		    <td align="right" valign="top" class="row-title">Auto Hide Bottom Navigation</td>
		    <td align="left" valign="middle"><select name="autoHideBottomNav" id="autoHideBottomNav">
              <option value="true" <?php echo (($_POST['autoHideBottomNav']=='true')?'selected="selected"':'')?>>true</option>
              <option value="false" <?php echo (($_POST['autoHideBottomNav']=='false')?'selected="selected"':'')?>>false</option>
            </select></td>
	    </tr>
        <tr>
		    <td align="right" valign="top" class="row-title">Show Preview Thumbs</td>
		    <td align="left" valign="middle"><select name="showPreviewThumbs" id="showPreviewThumbs">
              <option value="true" <?php echo (($_POST['showPreviewThumbs']=='true')?'selected="selected"':'')?>>true</option>
              <option value="false" <?php echo (($_POST['showPreviewThumbs']=='false')?'selected="selected"':'')?>>false</option>
            </select></td>
	    </tr>
        <tr>
          <td align="right" valign="top" class="row-title">Number Of Thumbs Per Screen</td>
          <td align="left" valign="middle"><input name="numberOfThumbsPerScreen" type="text" size="25" id="numberOfThumbsPerScreen" value="<?php echo $_POST['numberOfThumbsPerScreen'];?>"/>  <i>If you set it to 0, it will be calculated automatically.</i></td>
        </tr>
        <tr>
          <td align="right" valign="top" class="row-title">Thumbs On Margin Top</td>
          <td align="left" valign="middle"><input name="thumbsOnMarginTop" type="text" size="15" id="thumbsOnMarginTop" value="<?php echo $_POST['thumbsOnMarginTop'];?>"/> 
          px</td>
        </tr>
        <tr>
          <td align="right" valign="top" class="row-title">Thumbs Wrapper Margin Top</td>
          <td align="left" valign="middle"><input name="thumbsWrapperMarginTop" type="text" size="15" id="thumbsWrapperMarginTop" value="<?php echo $_POST['thumbsWrapperMarginTop'];?>"/> 
          px</td>
        </tr>
        

      </table>
  </div>


<h3><a href="#">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Circle Timer Settings</a></h3>
  <div style="padding:30px;">
	  <table class="wp-list-table widefat fixed pages" cellspacing="0">
		<tr>
		    <td align="right" valign="top" class="row-title" width="30%">Show Circle Timer</td>
		    <td align="left" valign="middle" width="70%"><select name="showCircleTimer" id="showCircleTimer">
              <option value="true" <?php echo (($_POST['showCircleTimer']=='true')?'selected="selected"':'')?>>true</option>
              <option value="false" <?php echo (($_POST['showCircleTimer']=='false')?'selected="selected"':'')?>>false</option>
            </select></td>
	    </tr>
        <tr>
		    <td align="right" valign="top" class="row-title">Circle Radius</td>
		    <td align="left" valign="middle"><input name="circleRadius" type="text" size="15" id="circleRadius" value="<?php echo $_POST['circleRadius'];?>"/> 
		    px</td>
	    </tr>
        <tr>
		    <td align="right" valign="top" class="row-title">Circle Line Width</td>
		    <td align="left" valign="middle"><input name="circleLineWidth" type="text" size="15" id="circleLineWidth" value="<?php echo $_POST['circleLineWidth'];?>"/> 
		    px</td>
	    </tr>
<tr>
		    <td align="right" valign="top" class="row-title">Circle Color</td>
		    <td align="left" valign="top"><input name="circleColor" type="text" size="25" id="circleColor" value="<?php echo $_POST['circleColor'];?>" style="background-color:#<?php echo $_POST['circleColor'];?>" />
                <script>
jQuery('#circleColor').ColorPicker({
	onSubmit: function(hsb, hex, rgb, el) {
		jQuery(el).val(hex);
		jQuery(el).css("background-color",'#'+hex);
		jQuery(el).ColorPickerHide();
	},
	onBeforeShow: function () {
		jQuery(this).ColorPickerSetColor(this.value);
	}
})
.bind('keyup', function(){
	jQuery(this).ColorPickerSetColor(this.value);
});
              </script>            </td>
	    </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Circle Alpha</td>
		    <td align="left" valign="middle"><script>
	jQuery(function() {
		jQuery( "#circleAlpha-slider-range-min" ).slider({
			range: "min",
			value: <?php echo $_POST['circleAlpha'];?>,
			min: 0,
			max: 100,
			slide: function( event, ui ) {
				jQuery( "#circleAlpha" ).val(ui.value );
			}
		});
		jQuery( "#circleAlpha" ).val( jQuery( "#circleAlpha-slider-range-min" ).slider( "value" ) );
	});
	        </script>
                <div id="circleAlpha-slider-range-min" class="inlinefloatleft"></div>
		      <div class="inlinefloatleft" style="padding-left:20px;">%
		        <input name="circleAlpha" type="text" size="10" id="circleAlpha" style="border:0; color:#000000; font-weight:bold;"/>
	          </div></td>
	    </tr>
        <tr>
		    <td align="right" valign="top" class="row-title">Behind Circle Color</td>
		    <td align="left" valign="top"><input name="behindCircleColor" type="text" size="25" id="behindCircleColor" value="<?php echo $_POST['behindCircleColor'];?>" style="background-color:#<?php echo $_POST['behindCircleColor'];?>" />
                <script>
jQuery('#behindCircleColor').ColorPicker({
	onSubmit: function(hsb, hex, rgb, el) {
		jQuery(el).val(hex);
		jQuery(el).css("background-color",'#'+hex);
		jQuery(el).ColorPickerHide();
	},
	onBeforeShow: function () {
		jQuery(this).ColorPickerSetColor(this.value);
	}
})
.bind('keyup', function(){
	jQuery(this).ColorPickerSetColor(this.value);
});
              </script>            </td>
	    </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Behind Circle Alpha</td>
		    <td align="left" valign="middle"><script>
	jQuery(function() {
		jQuery( "#behindCircleAlpha-slider-range-min" ).slider({
			range: "min",
			value: <?php echo $_POST['behindCircleAlpha'];?>,
			min: 0,
			max: 100,
			slide: function( event, ui ) {
				jQuery( "#behindCircleAlpha" ).val(ui.value );
			}
		});
		jQuery( "#behindCircleAlpha" ).val( jQuery( "#behindCircleAlpha-slider-range-min" ).slider( "value" ) );
	});
	        </script>
                <div id="behindCircleAlpha-slider-range-min" class="inlinefloatleft"></div>
		      <div class="inlinefloatleft" style="padding-left:20px;">%
		        <input name="behindCircleAlpha" type="text" size="10" id="behindCircleAlpha" style="border:0; color:#000000; font-weight:bold;"/>
	          </div></td>
	    </tr>      
      </table>
  </div>
  
  
  
</div>

<div style="text-align:center; padding:20px 0px 20px 0px;"><input name="Submit" type="submit" id="Submit" class="button-primary" value="Update Slider Settings"></div>

</form>
</div>