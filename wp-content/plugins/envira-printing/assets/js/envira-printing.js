jQuery( document ).ready( function( $ ) {

	/**
	* Open image in a new browser tab with a print dialog when the print button is clicked
	* 
	* If Password Protection is enabled, request a password when the download button
	* is clicked.
	*/
	$( document ).on( 'click', '.envira-printing-button a', function( e ) {

		e.preventDefault();

		// Get image based on whether we're in the lightbox or not
		if ( $( '.envirabox-overlay' ).css( 'display' ) == 'block' ) {
			// Lightbox
			var image 			= $( 'img.envirabox-image' ).attr( 'src' ),
				gallery_id 		= $( 'img.envirabox-image' ).data( 'envira-gallery-id' );
			
		} else {
			// Gallery
			var image = $( this ).attr( 'href' ),
				gallery_id = $( this ).closest( '.envira-gallery-public' ).attr( 'id' ).split( '-' )[2];
		}

		// Build URL
		url = envira_printing.url + '?envira_printing_image=' + image;

		// Display new window with printing dialog
		// Calculate the position of the window we'll open
    	var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    	var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;
		var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    	var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
    	var left = ((width / 2) - (32 / 2)) + dualScreenLeft;
    	var top = ((height / 2) - (32 / 2)) + dualScreenTop;

    	// Open the window
		var envira_printing_window = window.open( url, 'Print', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=640, height=480, top=' + top + ', left=' + left );

		return false;
	} );

	// Gallery: Show Printing Button on Image Hover
	$( 'div.envira-gallery-item-inner' ).hover( function() {
		$( 'div.envira-printing-button', $( this ) ).fadeIn().css('display', 'inline-block');		
	}, function() {
		$( 'div.envira-printing-button', $( this ) ).fadeOut();	
	} );

} );

/**
* Returns the value of the given cookie name
*
* @since 1.0.0
*
* @param 	string 	cname 	Cookie Name
* @return 	string 			Cookie Value
*/
function envira_printing_get_cookie( cname ) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}