/*
jQuery( document ).ready( function( $ ) {

	// Reload Isotope when variable product option changed
	$( '.variations_form' ).on( 'woocommerce_variation_has_changed', function( e ) {
		
		var gallery_id = $( this ).closest( 'div.enviratope' ).attr( 'id' );
		setTimeout(function() {
			$( '#' + gallery_id ).enviratope( 'layout' );
		}, 500);
		
	} );

} );
*/

 /**
 * WooCommerce helper for envirabox
 * @requires envirabox v2.0 or later
 *
 * Usage:
 *     $(".envirabox").envirabox({
 *         helpers : {
 *             woocommerce: {
 *                 gallery_id: <gallery ID>
 *             }
 *         }
 *     });
 *
 */
;(function ($) {
	//Shortcut for envirabox object
	var F = $.envirabox;

	// Does WooCommerce Plugin Exist?
	if (!envira_woocommerce.woocommerce_is_active) {
		return;
	}

	//Add helper object
	F.helpers.woocommerce = {
		defaults : {
			gallery_id: ''
		},

		// The wrapper / container for the WooCommerce form.
		container:  null,
		is_supersize: false,

		/**
		 * Fired before Envirabox loads the requested media into view
		 *
		 * @since 1.0.0
		 *
		 * @param 	object 	opts 	Options
		 * @param 	object 	obj 	Envirabox Instance
		 */
		beforeShow: function( opts, obj ) {

			// Output debugging if enabled
			if ( envira_woocommerce.debug ) {
				console.log( 'beforeShow' );
			}

			// Fire the beforeClose event, which destroys the container and unbinds any events.
			this.beforeClose();

			// Check whether we're using the standard Lightbox view or the Supersize view.
			if ( obj.tpl.wrap.search( 'envira-supersize' ) > -1 ) {
				this.is_supersize = true;
			}

			// Append a fresh container to the DOM.
			this.container = $( '<div id="envira-woocommerce"' + ( this.is_supersize ? ' class="envira-supersize"' : '' ) + '></div>' );
			this.container.appendTo( 'div.envirabox-inner' );

			// Get the attachment ID of the media item we're about to view.
			// Depending on how Envirabox is populated with images (i.e. from either the DOM or a JS array),
			// we need to get the media item in a certain way.
			var item = obj.group[ obj.index ];
			if ( typeof item.element === 'undefined' ) {
				// Using $lightbox_images
				var attachment_id = item.id;
			} else {
				// Using DOM
				var attachment_id = item.element.find( 'img' ).data( 'envira-item-id' );
			}

			// Store the container element in a variable that's accessible within our AJAX call.
			var container = this.container;

			// If no Gallery ID defined, try to get it from the current image
			// This happens when using WooCommerce on an Album's Gallery.
			var gallery_id = ( opts.gallery_id.length == 0 ? item.gallery_id : opts.gallery_id );

			/**
			 * Get WooCommerce Add to Cart Form
			 */
			$.ajax( {
	            url:      envira_woocommerce.ajax,
	            type:     'post',
	            data: {
	                action:        	'envira_woocommerce_get_add_to_cart_form',
	                gallery_id:  	gallery_id,
	                attachment_id: 	attachment_id,
	                nonce:         	envira_woocommerce.get_add_to_cart_form_nonce
	            },
	            success: function( response ) {

	            	// If WP_DEBUG enabled, output response data
	            	if ( envira_woocommerce.debug ) {
		            	console.log( response );
	            	}

	            	// Check whether the request was truly successful.
	            	if ( ! response.success ) {
	            		return; 
	            	}

	            	// Check whether any data was returned.
	            	// If not, this means the attachment doesn't have a WooCommerce product assigned to it
	            	if ( response.data.length == 0 ) {
	            		return;
	            	}

	            	// Output the WooCommerce Form
	            	container.html( response.data.woocommerce_form );

	            	// Rebind WooCommerce variation functions
	            	$( '.variations_form' ).wc_variation_form();
        			$( '.variations_form .variations select' ).change();

	            	// Finally, display the container
         			container.fadeIn();

	            },
	            error: function( xhr, textStatus, e ) {

	            	// If WP_DEBUG enabled, output error details
	            	if ( envira_woocommerce.debug ) {
		            	console.log( xhr );
						console.log( textStatus );
	            		console.log( e );
	            	}

	            }
	        } );
			
		},

		/**
		 * Fired just before Envirabox closes
		 *
		 * @since 1.0.0
		 *
		 * @param 	object 	opts 	Options
		 * @param 	object 	obj 	Envirabox Instance
		 */
		beforeClose: function () {

			// Output debugging if enabled
			if ( envira_woocommerce.debug ) {
				console.log( 'beforeClose' );
			}

			// Remove the container from the DOM if it exists.
			if ( this.container ) {
				this.container.remove();
				this.container = null;
			}

		}
	}

}(jQuery));