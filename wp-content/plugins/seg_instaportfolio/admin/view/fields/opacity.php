<script>

  jQuery(window).load(function() {

    jQuery( "#<?php echo $field['name']; ?>-slider" ).slider({

      range: "max",
      min: 1,
      max: 10,
      value: <?php echo $this->get_option( $field['name'] ); ?>,
      slide: function( event, ui ) {

        jQuery( "#<?php echo $field['name']; ?>" ).val( ui.value );

      }

    });

    //jQuery( "#<?php echo $field['name']; ?>" ).val( $( "#<?php echo $field['name']; ?>-slider" ).slider( "value" ) );

  });

  </script>

<div class="<?php echo $field['name'] . ' ' . $field['type']; ?> settings-group">

     <label for="<?php echo $tab['name']; ?>"><?php _e( $field['title'] , $namespace ); ?></label>

     

	 <input id="<?php echo $field['name']; ?>" name="data[<?php echo $field['name']; ?>]" size="3" value="<?php echo $this->get_option( $field['name'] ); ?>">

	 <div class="opacity-slider">

	     <div id="<?php echo $field['name']; ?>-slider"></div>

	 </div>

   <?php if (array_key_exists('shortcode', $field)) { if($field['shortcode']){ ?>
  
    <code class="shortcode"><?php echo $field['shortcode']; ?></code>

  <?php } } ?>

  <p class="description"><?php echo sprintf( __( $field['desc'], $namespace ), '<code>', '</code>' ); ?></p>

</div>