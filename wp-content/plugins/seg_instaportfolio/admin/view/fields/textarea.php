<div class="<?php echo $field['name'] . ' ' . $field['type']  . ' ' . $field['class'];  ?> settings-group">

     <label for="<?php echo $tab['name']; ?>"><?php _e( $field['title'] , $namespace ); ?></label>

     <textarea id="<?php echo $field['name']; ?>" name="data[<?php echo $field['name']; ?>]" size="3" ><?php echo $this->get_option( $field['name'] ); ?></textarea>

    <?php if (array_key_exists('shortcode', $field)) { if($field['shortcode']){ ?>
	
		<code class="shortcode"><?php echo $field['shortcode']; ?></code>

	<?php } } ?>

     <p class="description"><?php echo sprintf( __( $field['desc'], $namespace ), '<code>', '</code>' ); ?></p>

</div>