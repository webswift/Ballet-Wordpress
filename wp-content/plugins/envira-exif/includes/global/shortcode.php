<?php
/**
 * Shortcode class.
 *
 * @since 1.0.0
 *
 * @package Envira_Pagination
 * @author  Tim Carr
 */
class Envira_Exif_Shortcode {

    /**
     * Holds the class object.
     *
     * @since 1.0.0
     *
     * @var object
     */
    public static $instance;

    /**
     * Path to the file.
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $file = __FILE__;

    /**
     * Holds the base class object.
     *
     * @since 1.0.0
     *
     * @var object
     */
    public $base;

     /**
     * Holds gallery IDs for init firing checks.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public $enabled = array();

    /**
     * Primary class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {

	    // Load the base class object.
        $this->base = Envira_Exif::get_instance();

	    // Register CSS
        wp_register_style( $this->base->plugin_slug . '-style', plugins_url( 'assets/css/envira-exif.css', $this->base->file ), array(), $this->base->version );

	    // Gallery: EXIF
	    add_action( 'envira_gallery_before_output', array( $this, 'output_css' ) );
		add_filter( 'envira_gallery_output_after_link', array( $this, 'gallery_build_exif_html' ), 10, 5 );
		add_filter( 'envira_gallery_output_image_attr', array( $this, 'gallery_build_exif_lightbox_data' ), 10, 5 );
		add_action( 'envira_gallery_api_lightbox_image_attributes', array( $this, 'lightbox_image_attributes' ), 10, 4 );
		add_action( 'envira_gallery_api_before_show', array( $this, 'gallery_output_exif_lightbox_data' ) );

		// Gallery Tags, if Tags Addon is enabled
		add_filter( 'envira_tags_filter_markup', array( $this, 'tags_filter_markup' ), 1, 3 );
	    add_filter( 'envira_tags_item_data', array( $this, 'tags_item_data' ), 1, 4 );
	    add_filter( 'envira_tags_filter_classes', array( $this, 'tags_filter_classes' ), 10, 4 );
	    
		// Albums
		add_action( 'envira_albums_before_output', array( $this, 'output_css' ) );
		add_filter( 'envira_albums_lightbox_template', array( $this, 'output_exif_lightbox_template' ), 10, 2 );
		add_action( 'envira_albums_gallery_lightbox_image_attributes', array( $this, 'album_output_exif_data' ), 10, 4 );
		add_action( 'envira_albums_api_after_show', array( $this, 'album_output_exif_lightbox_data' ) );

    }

    /**
	* Enqueue CSS if EXIF data is enabled
	*
	* @since 1.0.0
	*
	* @param array $data Gallery Data
	*/
	public function output_css( $data ) {

		// Check if EXIF data output is enabled
		if ( ! $this->get_config( 'exif', $data ) && ! $this->get_config( 'exif_lightbox', $data ) ) {
			return;
		}

		// Enqueue
		wp_enqueue_style( $this->base->plugin_slug . '-style' );

	}

    /**
	 * Outputs EXIF Image data for the gallery thumbnail if enabled
	 *
	 * @since 1.0.0
	 *
	 * @param string $output HTML Output
	 * @param int $id Attachment ID
	 * @param array $item Image Item
	 * @param array $data Gallery Config
	 * @param int $i Image number in gallery
	 * @return string HTML Output
	 */
    public function gallery_build_exif_html( $output, $id, $item, $data, $i ) {

	    // Check if EXIF data output is enabled
		if ( ! $this->get_config( 'exif', $data ) ) {
			return $output;
		}

		// Get EXIF data
		$exif_data = $this->get_exif_data( $id );
		if ( ! $exif_data ) {
			return $output;
		}

		// Build EXIF output
		$exif = '<div class="envira-exif">';

		// Make & Model
		if ( $this->get_config( 'exif_make', $data ) || $this->get_config( 'exif_model', $data ) ) {
			$exif .= '<div class="model"><span>';

			// Make
			if ( $this->get_config( 'exif_make', $data ) && isset( $exif_data['Make'] ) ) {
 				$exif .= $exif_data['Make'];
			}

			// Model
			if ( $this->get_config( 'exif_model', $data ) && isset( $exif_data['Model'] ) ) {
 				$exif .= ' ' . $exif_data['Model'];
			}

			$exif .= '</span></div>';
		}

		// Aperture
		if ( $this->get_config( 'exif_aperture', $data ) && isset( $exif_data['Aperture'] ) ) {
			$exif .= '<div class="aperture"><span>f/' . $exif_data['Aperture'].'</span></div>';
		}

		// Shutter speed
		if ( $this->get_config( 'exif_shutter_speed', $data ) && isset( $exif_data['ShutterSpeed'] ) ) {
			$exif .= '<div class="shutter-speed"><span>' . $exif_data['ShutterSpeed'].'</span></div>';
		}

		// Focal length
		if ( $this->get_config( 'exif_focal_length', $data ) && isset( $exif_data['FocalLength'] ) ) {
			$exif .= '<div class="focal-length"><span>' . $exif_data['FocalLength'].'</span></div>';
		}

		// ISO
		if ( $this->get_config( 'exif_iso', $data ) && isset( $exif_data['iso'] ) ) {
			$exif .= '<div class="iso"><span>' . $exif_data['iso'].'</span></div>';
		}

		$exif .= '</div>';

		// Return Output with EXIF output
		return $output . $exif;

    }

    /**
     * Outputs EXIF Image data in JSON format for the gallery thumbnail. Data is then transported to the lightbox
     * when the image is clicked
     *
     * @since 1.0.0
	 *
	 * @param 	string 	$output 	HTML Output
	 * @param 	int 	$id 		Attachment ID
	 * @param 	array 	$item 		Image Item
	 * @param 	array 	$data 		Gallery Config
	 * @param 	int 	$i 			Image number in gallery
	 * @return 	string 				HTML Output
	 */
    public function gallery_build_exif_lightbox_data( $output, $id, $item, $data, $i ) {

    	// Check if EXIF lightbox data output is enabled
		if ( ! $this->get_config( 'exif_lightbox', $data ) ) {
			return $output;
		}

		// Define array to be JSON-encoded
		$exif = array();

		// Get EXIF data
		if ( $this->get_config( 'exif_lightbox', $data ) ) {
			$exif_data = $this->get_exif_data( $id );

			// Build EXIF output for use on lightbox

			// Make
			if ( $this->get_config( 'exif_lightbox_make', $data ) && isset( $exif_data['Make'] ) ) {
				$exif['Make'] = $exif_data['Make'];
			}

			// Model
			if ( $this->get_config( 'exif_lightbox_model', $data ) && isset( $exif_data['Model'] ) ) {
				$exif['Model'] = $exif_data['Model'];
			}

			// Aperture
			if ( $this->get_config( 'exif_lightbox_aperture', $data ) && isset( $exif_data['Aperture'] ) ) {
				$exif['Aperture'] = $exif_data['Aperture'];
			}

			// Shutter speed
			if ( $this->get_config( 'exif_lightbox_shutter_speed', $data ) && isset( $exif_data['ShutterSpeed'] ) ) {
				$exif['ShutterSpeed'] = $exif_data['ShutterSpeed'];
			}

			// Focal length
			if ( $this->get_config( 'exif_lightbox_focal_length', $data ) && isset( $exif_data['FocalLength'] ) ) {
				$exif['FocalLength'] = $exif_data['FocalLength'];
			}

			// ISO
			if ( $this->get_config( 'exif_lightbox_iso', $data ) && isset( $exif_data['iso'] ) ) {
				$exif['iso'] = $exif_data['iso'];
			}
		}

		// Return
		$output .= " data-envira-data='" . json_encode( $exif ) . "'";
		return $output;

    }

    /**
     * Appends EXIF data to the Lightbox Gallery Image object.
     *
     * Called when $lightbox_images are defined in e.g. Pagination Addon AJAX
     *
     * @since 1.1.6
     *
     * @param 	array 	$image 				Image
     * @param 	int 	$image_id 			Image ID
     * @param 	array 	$lightbox_images 	Lightbox Images
     * @param 	array 	$data 				Gallery Config
     */
    public function lightbox_image_attributes( $image, $image_id, $lightbox_images, $data ) {

    	// Check if EXIF data output is enabled
		if ( ! $this->get_config( 'exif_lightbox', $data ) ) {
			return;
		}

		// Get EXIF data
		$exif_data = $this->get_exif_data( $image_id );
		if ( ! $exif_data ) {
			return;
		}

		// Build EXIF output for use on gallery and/or lightbox

		// Make
		$exif = array();
		if ( $this->get_config( 'exif_lightbox_make', $data ) && isset( $exif_data['Make'] ) ) {
			$exif['Make'] = $exif_data['Make'];
		}

		// Model
		if ( $this->get_config( 'exif_lightbox_model', $data ) && isset( $exif_data['Model'] ) ) {
			$exif['Model'] = $exif_data['Model'];
		}

		// Aperture
		if ( $this->get_config( 'exif_lightbox_aperture', $data ) && isset( $exif_data['Aperture'] ) ) {
			$exif['Aperture'] = $exif_data['Aperture'];
		}

		// Shutter speed
		if ( $this->get_config( 'exif_lightbox_shutter_speed', $data ) && isset( $exif_data['ShutterSpeed'] ) ) {
			$exif['ShutterSpeed'] = $exif_data['ShutterSpeed'];
		}

		// Focal length
		if ( $this->get_config( 'exif_lightbox_focal_length', $data ) && isset( $exif_data['FocalLength'] ) ) {
			$exif['FocalLength'] = $exif_data['FocalLength'];
		}

		// ISO
		if ( $this->get_config( 'exif_lightbox_iso', $data ) && isset( $exif_data['iso'] ) ) {
			$exif['iso'] = $exif_data['iso'];
		}
		?>
		, exif_data: {
			<?php
			if ( isset( $exif['Make'] ) ) {
				?>
				'Make': '<?php echo $exif['Make']; ?>',
				<?php
			}
			if ( isset( $exif['Model'] ) ) {
				?>
				'Model': '<?php echo $exif['Model']; ?>',
				<?php
			}
			
			if ( isset( $exif['Aperture'] ) ) {
				?>
				'Aperture': '<?php echo $exif['Aperture']; ?>',
				<?php
			}
			
			if ( isset( $exif['ShutterSpeed'] ) ) {
				?>
				'ShutterSpeed': '<?php echo $exif['ShutterSpeed']; ?>',
				<?php
			}
			
			if ( isset( $exif['FocalLength'] ) ) {
				?>
				'FocalLength': '<?php echo $exif['FocalLength']; ?>',
				<?php
			}
			
			if ( isset( $exif['iso'] ) ) {
				?>
				'iso': '<?php echo $exif['iso']; ?>',
				<?php
			}
			?>
		}
		<?php

    }

    /**
	* Outputs a new template for the Lightbox
	*
	* @param string $template Template HTML
	* @param array $data Gallery Data
	* @return string Template HTML
	*/
    public function output_exif_lightbox_template( $template, $data ) {

	    // Check if EXIF data output is enabled
		if ( ! $this->get_config( 'exif_lightbox', $data ) ) {
			return $template;
		}

		// Return the amended markup
	    return '<div class="envirabox-wrap" tabIndex="-1"><div class="envirabox-skin"><div class="envirabox-outer"><div class="envirabox-inner"></div><div class="envirabox-exif"></div></div></div></div>';

    }

    /**
	* Gallery: Outputs EXIF Lightbox data when a lightbox image is displayed from a Gallery
	*
	* @param array $data Gallery Data
	* @return JS
	*/
    public function gallery_output_exif_lightbox_data( $data ) {

	    // Check if EXIF data output is enabled
		if ( ! $this->get_config( 'exif_lightbox', $data ) ) {
			return;
		}

		// Define CSS Classes for the EXIF Metadata
		$css_class = 'position-' . $this->get_config( 'exif_lightbox_position', $data );
		$css_class .= ( $this->get_config( 'exif_lightbox_outside', $data ) ? ' outside' : '' );
	    // Destroy EXIF HTML and get new EXIF data
	    ?>
	    $('div.envirabox-exif').remove();

		if ( typeof this.element === 'undefined' ) {
            <?php
            // Using $lightbox_images
            ?>
            var exif_data = this.group[ this.index ].exif_data;
        } else {
            <?php
            // Using image from DOM
            ?>
            var exif_data = this.element.find('img').data('envira-data');
        }

	    <?php
	    // Check EXIF data exists
	    ?>
	    if (typeof exif_data != 'undefined') {
		    var envira_html = '<div class="envira-exif <?php echo $css_class; ?>">';
		    if (typeof exif_data.Make !== 'undefined' || typeof exif_data.Model !== 'undefined') {
		    	envira_html += '<div class="model"><span>';
		    	if (typeof exif_data.Make !== 'undefined') {
		    		envira_html += exif_data.Make;
		    	}
		    	if (typeof exif_data.Model !== 'undefined') {
		    		envira_html += ' ' + exif_data.Model;
		    	}
		    	envira_html += '</span></div>';
		    }
		    if (typeof exif_data.Aperture !== 'undefined') {
		    	envira_html += '<div class="aperture"><span>f/' + exif_data.Aperture + '</span></div>';
		    }
		    if (typeof exif_data.ShutterSpeed !== 'undefined') {
		    	envira_html += '<div class="shutter-speed"><span>' + exif_data.ShutterSpeed + '</span></div>';
		    }
		    if (typeof exif_data.FocalLength !== 'undefined') {
		    	envira_html += '<div class="focal-length"><span>' + exif_data.FocalLength + '</span></div>';
		    }
		    if (typeof exif_data.iso !== 'undefined') {
		    	envira_html += '<div class="iso"><span>' + exif_data.iso + '</span></div>';
		    }
		    envira_html += '</div>';

		    <?php
		    // Create EXIF HTML
		    ?>
		    $('div.envirabox-inner').after('<div class="envirabox-exif" />');
		    $('div.envirabox-exif').html( envira_html ).fadeIn(300);
	    }
		<?php

    }

    /**
	* Album: Prepare EXIF Lightbox data for a Gallery
	*
	* @since 1.0.0
	*
	* @param array 	$image 		Gallery Image
	* @param array 	$gallery 	Gallery Data
	* @param int 	$image_id 	Image ID
	* @param array 	$data 		Album Data
	*/
    public function album_output_exif_data( $image, $gallery, $image_id, $data ) {

	    // Check if EXIF data output is enabled
		if ( ! $this->get_album_config( 'exif_lightbox', $data ) ) {
			return;
		}

		// Get EXIF data
		$exif_data = $this->get_exif_data( $image_id );
		if ( ! $exif_data ) {
			return;
		}

		// Define the CSS classes for positioning the EXIF metadata
		$css_class = 'position-' . $this->get_config( 'exif_lightbox_position', $data );
		$css_class .= ( $this->get_config( 'exif_lightbox_outside', $data ) ? ' outside' : '' );

		// Build EXIF output for use on gallery and/or lightbox
		$exif = '<div class="envira-exif ' . $css_class . '">';

		// Make & Model
		if ( $this->get_album_config( 'exif_lightbox_make', $data ) || $this->get_album_config( 'exif_lightbox_model', $data ) ) {
			$exif .= '<div class="model"><span>';

			// Make
			if ( $this->get_album_config( 'exif_lightbox_make', $data ) && isset( $exif_data['Make'] ) ) {
 				$exif .= $exif_data['Make'];
			}

			// Model
			if ( $this->get_album_config( 'exif_lightbox_model', $data ) && isset( $exif_data['Model'] ) ) {
 				$exif .= ' ' . $exif_data['Model'];
			}

			$exif .= '</span></div>';
		}

		// Aperture
		if ( $this->get_album_config( 'exif_lightbox_aperture', $data ) && isset( $exif_data['Aperture'] ) ) {
			$exif .= '<div class="aperture"><span>f/' . $exif_data['Aperture'].'</span></div>';
		}

		// Shutter speed
		if ( $this->get_album_config( 'exif_lightbox_shutter_speed', $data ) && isset( $exif_data['ShutterSpeed'] ) ) {
			$exif .= '<div class="shutter-speed"><span>' . $exif_data['ShutterSpeed'].'</span></div>';
		}

		// Focal length
		if ( $this->get_album_config( 'exif_lightbox_focal_length', $data ) && isset( $exif_data['FocalLength'] ) ) {
			$exif .= '<div class="focal-length"><span>' . $exif_data['FocalLength'].'</span></div>';
		}

		// ISO
		if ( $this->get_album_config( 'exif_lightbox_iso', $data ) && isset( $exif_data['iso'] ) ) {
			$exif .= '<div class="iso"><span>' . $exif_data['iso'].'</span></div>';
		}

		$exif .= '</div>';
		?>
		, exif_html: '<?php echo $exif; ?>'
		<?php

    }

    /**
	* Outputs EXIF Lightbox data when a lightbox image is displayed from an Album
	*
	* @param array $data Gallery Data
	* @return JS
	*/
    public function album_output_exif_lightbox_data( $data ) {

	    // Check if EXIF data output is enabled
		if ( ! $this->get_config( 'exif_lightbox', $data ) ) {
			return;
		}

	    ?>
	    $('div.envirabox-exif').hide().html( this.exif_html ).fadeIn(300);
	    <?php

    }

    /**
	 * Outputs the tag filter links at the top of the gallery.
	 *
	 * @since 1.0.0
	 *
	 * @param string $markup  	The HTML output for the gallery.
	 * @param array $tags 		Tags
	 * @param array $data      	Data for the Envira gallery.
	 * @return string 			Amended gallery HTML.
	 */
	function tags_filter_markup( $markup, $tags, $data ) {

	    // If tag filtering is not enabled, return early.
	    if ( ! $this->get_config( 'exif_tags', $data ) ) {
	        return $markup;
	    }

	    // Now we need to ensure that we actually have tags to process. If we have no tags, return early.
	    $tags = envira_tags_get_tags_from_gallery( $data, 'envira-exif-manufacturer' );
	    if ( ! $tags ) {
	        return $markup;
	    }

	    // Remove trailing </ul>
	    $markup = str_replace( '</ul>', '', $markup);

	    // Add spacer
	    $markup .= '<li class="envira-tags-filter"> | </li>';

	    // Loop through the tags and add them to the filter list.
	    foreach ( $tags as $i => $tag ) {
		    $markup .= '<li id="envira-exif-manufacturer-filter-' . sanitize_html_class( $tag ) . '" class="envira-tags-filter">';
	            $markup .= '<a href="#" class="envira-tags-filter-link" title="' . sprintf( __( 'Filter by %s', 'envira-tags' ), $tag ) . '" data-envira-filter=".envira-exif-manufacturer-' . sanitize_html_class( $tag ) . '">';
	                $markup .= $tag;
	            $markup .= '</a>';
	        $markup .= '</li>';
	    }

	    // Add </ul> back
	    $markup .= '</ul>';

	    // Return the amended gallery HTML.
	    return $markup;

	}

	/**
	 * Adds taxonomy terms to $item, so envira_tags_filter_classes can
	 * output taxonomy term classes against the $item
	 *
	 * @since 1.0.5
	 * @param array $item     Array of item data.
	 * @param int $id		  Item ID
	 * @param array $data     Array of gallery data.
	 * @param int $i          The current position in the gallery.
	 * @return array $item Amended item.
	 */
	function tags_item_data( $item, $id, $data, $i ) {

		// If no more tags, return the classes.
	    $terms = wp_get_object_terms( $id, 'envira-exif-manufacturer' );
	    if ( count($terms) == 0 ) {
		    return $item;
	    }

	    // Loop through tags and output them as custom classes.
	    foreach ( $terms as $term ) {
	    	// Set new array key if it doesn't exist
	    	if ( !isset( $item['exif_tags'] )) {
		    	$item['exif_tags'] = array();
	    	}

	    	// Add term to array key
	    	$item['exif_tags'][ $term->term_id ] = $term->name;
	    }

		return $item;

	}

	/**
	 * Outputs the filter classes on the gallery item.
	 *
	 * @since 1.0.0
	 *
	 * @param array $classes  Current item classes.
	 * @param array $item     Array of item data.
	 * @param int $i          The current position in the gallery.
	 * @param array $data     Array of gallery data.
	 * @return array $classes Amended item classes.
	 */
	function tags_filter_classes( $classes, $item, $i, $data ) {

	    // If filtering is not enabled, do nothing.
	    if ( ! $this->get_config( 'exif_tags', $data ) ) {
	        return $classes;
	    }

	    // If no more tags, return the classes.
	    if ( !isset( $item['exif_tags'] ) || count( $item['exif_tags'] ) == 0 ) {
		    return $classes;
	    }

	    // Loop through tags and output them as custom classes.
	    foreach ( $item['exif_tags'] as $termID => $termName ) {
	        $classes[] = 'envira-exif-manufacturer-' . sanitize_html_class( $termName );
	    }

	    return $classes;

	}

    /**
     * Helper method for retrieving gallery config values.
     *
     * @since 1.0.0
     *
     * @param string $key The config key to retrieve.
     * @param array $data The gallery data to use for retrieval.
     * @return string     Key value on success, default if not set.
     */
    public function get_config( $key, $data ) {

        return Envira_Gallery_Shortcode::get_instance()->get_config( $key, $data );

    }

    /**
     * Helper method for retrieving album config values.
     *
     * @since 1.0.0
     *
     * @param string $key The config key to retrieve.
     * @param array $data The gallery data to use for retrieval.
     * @return string     Key value on success, default if not set.
     */
    public function get_album_config( $key, $data ) {

    	return Envira_Albums_Shortcode::get_instance()->get_config( $key, $data );

    }

    /**
     * Helper method for retrieving EXIF data from an image.
     *
     * @since 1.0.0
     *
     * @param 	int 	$id 	Attachment ID
     * @return 	array 			EXIF data
     */
    public function get_exif_data( $id ) {

		// Get data
        $data = Envira_Exif_Parser::get_instance()->get_exif_data( $id );

        // Check data exists - if the below array keys are blank, there isn't any EXIF data available
        if ( empty( $data['Make'] ) && 
        	 empty( $data['Model'] ) && 
        	 empty( $data['Aperture'] ) &&
        	 empty( $data['ShutterSpeed'] ) &&
        	 empty( $data['FocalLength'] ) &&
        	 empty( $data['iso'] ) ) {

        	return false;

        }

        return $data;

    }

    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Pagination_Shortcode object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Exif_Shortcode ) ) {
            self::$instance = new Envira_Exif_Shortcode();
        }

        return self::$instance;

    }

}

// Load the shortcode class.
$envira_exif_shortcode = Envira_Exif_Shortcode::get_instance();