<?php
/**
 * Shortcode class.
 *
 * @since 1.0.0
 *
 * @package Envira_Printing
 * @author  Tim Carr
 */
class Envira_Printing_Shortcode {

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
     * Holds a flag to determine whether metadata has been set
     *
     * @since 1.0.0
     *
     * @var array
     */
    public $meta_data_set = false; 

    /**
     * Primary class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {
	    
	    // Load the base class object.
        $this->base = Envira_Printing::get_instance();
	    
	    // Register CSS
        wp_register_style( $this->base->plugin_slug . '-style', plugins_url( 'assets/css/envira-printing.css', $this->base->file ), array(), $this->base->version );
	    
        // Register JS
        wp_register_script( $this->base->plugin_slug . '-script', plugins_url( 'assets/js/min/envira-printing-min.js', $this->base->file ), array( 'jquery' ), $this->base->version, true );
        
	    // Gallery
        add_action( 'envira_gallery_before_output', array( $this, 'gallery_output_css_js' ) );
        add_filter( 'envira_gallery_output_before_container', array( $this, 'gallery_output_error_message' ), 10, 2 );
        add_filter( 'envira_gallery_output_dynamic_position', array( $this, 'gallery_output_html' ), 10, 6 );
        add_action( 'envirabox_output_dynamic_position', array( $this, 'gallery_output_lightbox_html' ), 10, 3 );
        add_filter( 'envira_gallery_output_dynamic_position_css', array( $this, 'gallery_output_dynamic_position_css' ), 10, 7 );

        // Album
        add_action( 'envira_albums_before_output', array( $this, 'albums_output_css_js' ) );

    }

    /**
     * Add A Custom CSS Class To Gallery HTML div.envira-gallery-position-overlay
     *
     * @since 1.0.0
     *
     * @param array $data Gallery Data
     */
    public function gallery_output_dynamic_position_css( $css, $output, $id, $item, $data, $i, $position ) {

        // Check if Printing Button output is enabled
        if ( ! $this->get_config( 'print', $data ) && ! $this->get_config( 'print_lightbox', $data ) ) {
            return;
        }

        if ( !$this->get_config( 'print_position', $data ) ) { return $css; }

        if ( $this->get_config( 'print_position', $data ) == $position ) {
            return $css . ' printing-addon';
        } else {
            return $css;
        }

    }

    /**
	* Enqueue CSS and JS if the Printing Button is enabled
	*
	* @since 1.0.0
	*
	* @param array $data Gallery Data
	*/
	public function gallery_output_css_js( $data ) {

		// Check if Printing Button output is enabled
        if ( ! $this->get_config( 'print', $data ) && ! $this->get_config( 'print_lightbox', $data ) ) {
			return;
		}

        // Get instance
        $common = Envira_Printing_Common::get_instance();
		
		// Enqueue CSS + JS
		wp_enqueue_style( $this->base->plugin_slug . '-style' );
        wp_enqueue_script( $this->base->plugin_slug . '-script' );

        // Check if a password exists
        $password = $this->get_config( 'password_protection_print', $data );

        // Localize JS
        wp_localize_script( 
            $this->base->plugin_slug . '-script',
            'envira_printing',
            array(
                'url'                 => plugins_url( 'includes/views/print.php', $this->base->file ),
                'password_protection' => ( ! empty( $password ) ? true : false ),
                'password_required'   => __( 'Please enter the password to print this image.', 'envira-printing' ),
                'id'                  => $data['id'],
            )
        );
		
	}

    /**
     * Outputs an error message if the URL contains the envira-printing-invalid-password query parameter, 
     * telling the user the password they entered was invalid.
     *
     * @since 1.0.0
     *
     * @param   string  $html   HTML Output
     * @param   array   $data   Gallery
     * @return  string          HTML Output
     */
    public function gallery_output_error_message( $html, $data ) {

        // Check if the query parameters exists
        if ( ! isset( $_REQUEST['envira-printing-gallery-id'] ) ) {
            return $html;
        }
        if ( ! isset( $_REQUEST['envira-printing-invalid-password'] ) ) {
            return $html;
        }

        // Check that the error is for this gallery
        if ( $data['id'] != $_REQUEST['envira-printing-gallery-id'] ) {
            return $html;
        }

        // Output the error message.
        $html .= '<div id="envira-printing-invalid-password">' . $this->get_config( 'print_invalid_password_message', $data ) . '</div>';

        // Return.
        return $html;

    }

    /**
    * Enqueue CSS and JS for Albums if Print Button is enabled
    *
    * @since 1.0.3
    *
    * @param array $data Album Data
    */
    public function albums_output_css_js( $data ) {

        // Check if Print Button output is enabled
        if ( ! $this->get_config( 'print_lightbox', $data ) ) {
            return;
        }

        // Get instance
        $common = Envira_Printing_Common::get_instance();
        
        // Enqueue CSS + JS
        wp_enqueue_style( $this->base->plugin_slug . '-style' );
        wp_enqueue_script( $this->base->plugin_slug . '-script' );

        // Check if a password exists
        $password = $this->get_config( 'password_protection_print', $data );

        // Localize JS
        wp_localize_script( 
            $this->base->plugin_slug . '-script',
            'envira_printing',
            array(
                'url'                 => plugins_url( 'includes/views/print.php', $this->base->file ),
                'password_protection' => ( ! empty( $password ) ? true : false ),
                'password_required'   => __( 'Please enter the password to print this image.', 'envira-printing' ),
                'id'                  => $data['id'],
            )
        );
        
    }

    
    /**
	* Outputs Print Button HTML for the Gallery thumbnail
	*
	* @since 1.0.0
	* 
	* @param string    $output HTML Output
	* @param int       $id     Attachment ID
	* @param array     $item   Image Item
	* @param array     $data   Gallery Config
	* @param int       $i      Image number in gallery
	* @return string           HTML Output
	*/
    public function gallery_output_html( $output, $id, $item, $data, $i, $position ) {

        // Check if Print Buttons output is enabled
        if ( ! $this->get_config( 'print', $data ) ) {
            return $output;
        }

        // Prepend Button
	    $buttons = $this->get_print_button( $id, $item, $data, $i, $position );

		return $output . $buttons;
	    
    }
    
    /**
	* Gallery: Outputs Print Button when a lightbox image is displayed from a Gallery
	*
	* @param array $data Gallery Data
	* @return JS
	*/
    public function gallery_output_lightbox_html( $template, $data, $position ) {
	    
        // Check if Print Button output is enabled
        if ( ! $this->get_config( 'print_lightbox', $data ) ) {
            return $template;
        }

        // Get Button
        $button = $this->get_lightbox_print_button( $data, $position );
	   
        return $template . $button;
    }

    /**
    * Helper to output print button for an image
    *
    * @since 1.0.0
    *
    * @param int    $id     Image ID
    * @param array  $item   Image Data
    * @param array  $data   Gallery Data
    * @param int    $i      Index
    * @return string        HTML
    */
    function get_print_button( $id, $item, $data, $i, $position ) {

        if ( $this->get_config( 'print_position', $data ) !== $position ) {
            return;
        }
        
        // Get instance
        $common = Envira_Printing_Common::get_instance();

        // Build Button
        $button = '<div class="envira-printing-button">
            <a href="' . $item['link'] . '" data-image-id="'.$id.'" target="_blank">' . __( 'Print', 'envira-printing' ) . '</a>
        </div>';

        // Return
        return $button; 
    }

    /**
    * Helper to output the print button for the lightbox
    *
    * @since 1.0.0
    *
    * @param array $data Gallery Data
    * @return string HTML
    */
    function get_lightbox_print_button( $data, $position ) {

        if ( $this->get_config( 'print_lightbox_position', $data ) !== $position ) {
            return;
        }
        
        // Get instance
        $common = Envira_Printing_Common::get_instance();

        // Build Button
        $button = '<div class="envira-printing-button">
            <a href="#">' . __( 'Print', 'envira-printing' ) . '</a>
        </div>';

        // Return
        return $button;

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

        // Determine whether data is for a gallery or album
        $post_type = get_post_type( $data['id'] );

        // If post type is false, we're probably on a dynamic gallery/album
        // Grab the ID from the config
        if ( ! $post_type && isset( $data['config']['id'] ) ) {
            $post_type = get_post_type( $data['config']['id'] );
        }

        switch ( $post_type ) {
            case 'envira':
                $instance = Envira_Gallery_Shortcode::get_instance();
                break;
            case 'envira_album':
                $instance = Envira_Albums_Shortcode::get_instance();
                break;
        }

        // If no instance was set, bail
        if ( ! isset( $instance ) ) {
            return false;
        }

        // Return value
        return $instance->get_config( $key, $data );

    }

    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Printing_Shortcode object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Printing_Shortcode ) ) {
            self::$instance = new Envira_Printing_Shortcode();
        }

        return self::$instance;

    }

}

// Load the shortcode class.
$envira_printing_shortcode = Envira_Printing_Shortcode::get_instance();