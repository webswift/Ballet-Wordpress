jQuery(document).ready(function($) {

	/**
	* Show or hide Envira Proofing Fields (Quantity, Size) for each image
	* based on the image's checkbox state
	*
	* @since 1.0
	*
	* @param obj checkbox_element Checkbox Element
	*/
	var envira_proofing_toggle_fields = function( checkbox_element ) {
		if ( $( checkbox_element ).prop( 'checked' ) ) {
			$( '.envira-proofing-fields', $( checkbox_element ).parent() ).fadeIn();
			$( checkbox_element ).closest( '.envira-gallery-item-inner' ).addClass( 'envira-proofing-selected' );

			// Add to summary
			var image = $( 'img', $( checkbox_element ).closest( '.envira-gallery-item-inner' ) ).attr( 'src' );
			$( 'div.envira-proofing-summary-box-inner div.images-inner' ).append( '<div class="image" data-src="' + image + '"><img src="' + image + '" /></div>' );
			
			// If the summary bar isn't displayed, slide it in
			if ( $( 'div.envira-proofing-summary-box-inner' ).css('bottom') != '0' ) {
				$( 'div.envira-proofing-summary-box-inner' ).animate({
					bottom: '0'
				});
			}

		} else {
			$( '.envira-proofing-fields', $( checkbox_element ).parent() ).fadeOut();
			$( checkbox_element ).closest( '.envira-gallery-item-inner' ).removeClass( 'envira-proofing-selected' );

			// If isotope is enabled, relayout so the fields display
			var envira_container 	= $( checkbox_element ).closest( '.envira-gallery-public' );
			
			if ( $( envira_container ).hasClass( 'enviratope' ) ) {
				$( envira_container ).enviratope('layout');
			}

			// Remove from summary
			var image = $( 'img', $( checkbox_element ).closest( '.envira-gallery-item-inner' ) ).attr( 'src' );
			$( 'div.envira-proofing-summary-box-inner div.images-inner div[data-src="' + image + '"]' ).remove();
		}

		// If the summary bar has no images in it, slide it out
		if ( $( 'div.envira-proofing-summary-box-inner div.images div.image' ).length == 0 ) {
			$( 'div.envira-proofing-summary-box-inner' ).animate({
				bottom: '-80px'
			});
		}
	}
	
	// Show/hide proofing fields on select/deselect
	$( 'input.envira-proofing-select-image' ).each( function() {
		envira_proofing_toggle_fields( $( this ) );	
	} );
	$( 'input.envira-proofing-select-image' ).on( 'change', function() {

		envira_proofing_toggle_fields( $( this ) );

		// If isotope is enabled, relayout so the fields display
		var envira_container 	= $( this ).closest( '.envira-gallery-public' ),
			envira_container_arr= $( envira_container ).attr( 'id' ).split( '-' ),
			envira_gallery_id 	= envira_container_arr[2];

		if ( $( envira_container ).hasClass( 'enviratope' ) ) {
			$( envira_container ).enviratope('layout');
		}
	} );

	// Show/hide proofing fields on lightbox select/deselect
	$( document ).on( 'change', 'input.envira-proofing-lightbox-select-image', function() {

		// If isotope is enabled, relayout so the fields display
		var envira_container 	= $( this ).closest( '.envira-gallery-public' ),
			envira_container_arr= $( envira_container ).attr( 'id' ).split( '-' ),
			envira_gallery_id 	= envira_container_arr[2];

		if ( $( this ).prop( 'checked' ) ) {
			$( '.envira-proofing-fields', $( this ).parent().parent() ).fadeIn();
		} else {
			$( '.envira-proofing-fields', $( this ).parent().parent() ).fadeOut();
			if ( $( envira_container ).hasClass( 'enviratope' ) ) {
				$( envira_container ).enviratope('layout');
			}
		}
	} );

	/**
	* Summary Box
	*/
	$( 'div.envira-proofing-summary-box-inner div.images-inner' ).mousewheel(function(event, delta) {
		this.scrollLeft -= (delta * 10);
		event.preventDefault();
	});	

	/**
	* Summary Box: Save/Submit Buttons
	*/
	$( 'div.envira-proofing-summary-box-inner div.buttons button' ).on( 'click', function(e) {
		e.preventDefault();

		// Find form
		var gallery_id 	= $( this ).closest( 'div.envira-proofing-summary-box' ).data( 'envira-id' ),
			form 		= $( 'form[data-envira-id="' + gallery_id + '"]' );

		// Simulate click on the equivalent form button to save/submit the order
		$( 'input[name=' + $( this ).attr( 'name' ) + ']', $( form ) ).trigger( 'click' );
	} );

});

/**
* Populate Lightbox Proofing Fields with Gallery Proofing Field values
*
* @since 1.0
*
* @param int item_id Gallery Image ID
*/
function envira_proofing_populate_lightbox_fields( item_id ) {

	jQuery(document).ready(function($) {
		$('.envirabox-proofing form.envira-proofing-form-lightbox input').each(function() {

	        var field_name = $(this).attr('name'),
	        	field_name_parts = field_name.split( '][' );

	        // Find non-lightbox form field and use its value
	        switch ( $(this).attr('type') ) {
	        	/**
	        	* Checkbox
	        	*/
	            case 'checkbox':
	            	if ( field_name_parts.length == 1 ) {
						var is_checked = $( 'input[name="envira_proofing[images][' + item_id + ']"]' ).prop( 'checked' );
	                }
	                if ( field_name_parts.length == 2) {
	                	var size = field_name_parts[1].replace(']', ''),
	                		is_checked = $( 'input[name="envira_proofing[quantities][' + item_id + '][' + size + ']"]' ).prop( 'checked' );
	                }

	                $( this ).prop( 'checked', is_checked );
	                break;
	            
	            /**
	            * All other inputs
	            */
	            default:
	                if ( field_name_parts.length == 1 ) {
						var field_value = $( 'input[name="envira_proofing[quantities][' + item_id + ']"]' ).val();
	                }
	                if ( field_name_parts.length == 2) {
	                	var size = field_name_parts[1].replace(']', ''),
	                		field_value = $( 'input[name="envira_proofing[quantities][' + item_id + '][' + size + ']"]' ).val();
	                }

	                $( this ).val( field_value );
	                break;

	        }

	    });
	});

}

/**
* Populate Gallery Proofing Fields with Lightbox Proofing Field values
*
* @since 1.0
*/
function envira_proofing_populate_gallery_fields( item_id ) {

	jQuery(document).ready(function($) {
		$('.envirabox-proofing form.envira-proofing-form-lightbox input').each(function() {

	        var field_name = $(this).attr('name'),
	        	field_name_parts = field_name.split( '][' );
	        
	        // Find non-lightbox form field and use its value
	        switch ( $(this).attr('type') ) {
	        	/**
	        	* Checkbox
	        	*/
	            case 'checkbox':
	            	var is_checked = $( this ).prop( 'checked' );

	            	if ( field_name_parts.length == 1 ) {
						$( 'input[name="envira_proofing[images][' + item_id + ']"]' ).prop( 'checked', is_checked ).trigger( 'change' );
	                }
	                if ( field_name_parts.length == 2) {
	                	var size = field_name_parts[1].replace(']', '');
	                	$( 'input[name="envira_proofing[quantities][' + item_id + '][' + size + ']"]' ).prop( 'checked', is_checked );
	                }

	                break;
	            
	            /**
	            * All other inputs
	            */
	            default:
	            	if ( field_name_parts.length == 1 ) {
						var field_value = $( this ).val();
						$( 'input[name="envira_proofing[quantities][' + item_id + ']"]' ).val( field_value )
	                }
	                if ( field_name_parts.length == 2) {
	                	var size = field_name_parts[1].replace(']', ''),
	                		field_value = $( this ).val();
	                	$( 'input[name="envira_proofing[quantities][' + item_id + '][' + size + ']"]' ).val( field_value );
	                }

	                break;
	        }

	    });
    });

}

