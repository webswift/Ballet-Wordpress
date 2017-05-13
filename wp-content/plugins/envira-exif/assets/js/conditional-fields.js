/**
* Handles showing and hiding fields conditionally
*/
jQuery( document ).ready( function( $ ) {

	// Show/hide elements as necessary when a conditional field is changed
	$( '#envira-gallery-settings input:not([type=hidden]), #envira-gallery-settings select' ).conditions( 
		[

			{	// Exif Elements Independant of Theme
				conditions: {
					element: '[name="_envira_gallery[exif_lightbox]"]',
					type: 'checked',
					operator: 'is'
				},
				actions: {
					if: {
						element: '#envira-config-exif-lightbox-metadata-box, #envira-config-exif-lightbox-position-box',
						action: 'show'
					},
					else: {
						element: '#envira-config-exif-lightbox-metadata-box, #envira-config-exif-lightbox-position-box',
						action: 'hide'
					}
				}
			},
			{	// Exif Elements Dependant on Theme
				conditions: [
					{
						element: '[name="_envira_gallery[lightbox_theme]"]',
						type: 'value',
						operator: 'array',
						condition: [ 'base', 'caption', 'polaroid', 'showcase', 'sleek', 'subtle' ]
					},
					{
						element: '[name="_envira_gallery[exif_lightbox]"]',
						type: 'checked',
						operator: 'is'
					}
				],
				actions: {
					if: {
						element: '#envira-config-exif-lightbox-outside-box',
						action: 'show'
					},
					else: {
						element: '#envira-config-exif-lightbox-outside-box',
						action: 'hide'
					}
				}
			},

		]
	);

} );