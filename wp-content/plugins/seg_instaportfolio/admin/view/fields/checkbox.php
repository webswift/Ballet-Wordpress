<br />

<script>

jQuery(document).ready(function(){
	jQuery("#<?php echo $field['name']; ?>-checkbox").click(function(){
		if(jQuery(this).attr("checked")){
			jQuery("#<?php echo $field['name']; ?>").val("on");
		}else{
			jQuery("#<?php echo $field['name']; ?>").val("off");
		}
	});
});

</script>

<div class="<?php echo $field['name'] . ' ' . $field['type']; ?> settings-group">

	 <input type="checkbox" class="seg-checkbox" id="<?php echo $field['name']; ?>-checkbox"
	 	     <?php echo ($this->get_option( $field['name'] ) == "on" ? "checked" : "" ); ?>>
     
	 <input type="text" name="data[<?php echo $field['name']; ?>]" id="<?php echo $field['name']; ?>" style="opacity:0;"
	 value="<?php echo ($this->get_option( $field['name'] ) == "on" ? "on" : "off" ); ?>" />


     <label for="<?php echo $field['name']; ?>"><?php _e( $field['title'] , $namespace ); ?></label>

    <?php if (array_key_exists('shortcode', $field)) { if($field['shortcode']){ ?>
	
		<code class="shortcode"><?php echo $field['shortcode']; ?></code>

	<?php } } ?>
    
     <p class="description"><?php echo sprintf( __( $field['desc'], $namespace ), '<code>', '</code>' ); ?></p>
     

</div>