<div class="<?php echo $field['name'] . ' ' . $field['type']  . ' ' . ( array_key_exists('class', $field) ? $field['class'] : '' ); ?> settings-group">

     <label for="<?php echo $field['name']; ?>"><?php _e( $field['title'] , $namespace ); ?></label>

     <select name="data[<?php echo $field['name']; ?>]" id="<?php echo $field['name']; ?>" >

     <?php

     	//// OPTIONS
		foreach($field['options'] as $field_option) {

			if( $this->get_option( $field['name'] ) == $field_option) { 

				echo '<option selected="selected" value="'. stripslashes($field_option).'">'.stripslashes($field_option).'</option>'; 

			}else { 

				echo '<option value="'. stripslashes($field_option).'">'. stripslashes($field_option) .'</option>'; 
			}
													
		}

	?>

	</select>

	<?php if (array_key_exists('shortcode', $field)) { if($field['shortcode']){ ?>
	
		<code class="shortcode"><?php echo $field['shortcode']; ?></code>

	<?php } } ?>

     <p class="description"><?php echo sprintf( __( $field['desc'], $namespace ), '<code>', '</code>' ); ?></p>

</div>